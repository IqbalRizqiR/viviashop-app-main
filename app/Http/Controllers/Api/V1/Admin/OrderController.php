<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Shipment;
use App\Models\ProductInventory;
use App\Models\EmployeePerformance;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends BaseController
{
    public function index(Request $request)
    {
        $query = Order::with(['orderItems', 'shipment'])->orderBy('order_date', 'desc');

        if ($q = $request->input('q')) {
            $query->where(function ($qr) use ($q) {
                $qr->where('code', 'like', "%{$q}%")
                   ->orWhere('customer_first_name', 'like', "%{$q}%")
                   ->orWhere('customer_last_name', 'like', "%{$q}%");
            });
        }

        if ($status = $request->input('status')) {
            if (array_key_exists($status, Order::STATUSES)) {
                $query->where('status', $status);
            }
        }

        if ($paymentStatus = $request->input('payment_status')) {
            $query->where('payment_status', $paymentStatus);
        }

        if ($start = $request->input('start')) {
            $query->whereDate('order_date', '>=', $start);
        }
        if ($end = $request->input('end')) {
            $query->whereDate('order_date', '<=', $end);
        }

        // Counts for dashboard badges
        $countsQuery = clone $query;
        $counts = [
            'paid' => (clone $countsQuery)->where('payment_status', Order::PAID)->count(),
            'waiting' => (clone $countsQuery)->where('payment_status', Order::WAITING)->count(),
            'unpaid' => (clone $countsQuery)->where('payment_status', Order::UNPAID)->count(),
        ];

        $perPage = min((int) $request->input('per_page', 20), 100);
        $orders = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => OrderResource::collection($orders->items()),
            'counts' => $counts,
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ],
        ]);
    }

    public function show($id)
    {
        $order = Order::withTrashed()
            ->with(['orderItems.product.images', 'shipment'])
            ->findOrFail($id);

        $employees = EmployeePerformance::getEmployeeList();

        return $this->success([
            'order' => new OrderResource($order),
            'employees' => $employees,
            'payment_config' => [
                'client_key' => config('midtrans.clientKey'),
                'is_production' => config('midtrans.isProduction'),
                'snap_url' => config('midtrans.isProduction')
                    ? 'https://app.midtrans.com/snap/snap.js'
                    : 'https://app.sandbox.midtrans.com/snap/snap.js',
            ],
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|string',
            'payment_status' => 'nullable|string',
            'note' => 'nullable|string|max:500',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $validated['status'];

        if (isset($validated['payment_status'])) {
            $order->payment_status = $validated['payment_status'];
        }

        if (!empty($validated['note'])) {
            $order->notes = ($order->notes ?? '') . "\n" . $validated['note'];
        }

        $order->save();

        return $this->success(new OrderResource($order->fresh()), 'Order status updated');
    }

    public function confirm(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($order->payment_status !== Order::PAID && $order->payment_status !== Order::WAITING) {
            return $this->error('Order payment must be paid or waiting to confirm', 422);
        }

        $order->update([
            'status' => Order::CONFIRMED,
            'payment_status' => Order::PAID,
            'approved_at' => now(),
        ]);

        return $this->success(new OrderResource($order->fresh()), 'Order confirmed');
    }

    public function cancel(Request $request, $id)
    {
        $validated = $request->validate([
            'cancellation_note' => 'required|string|max:255',
        ]);

        $order = Order::findOrFail($id);

        DB::transaction(function () use ($order, $validated) {
            $order->update([
                'status' => Order::CANCELLED,
                'cancelled_by' => auth()->id(),
                'cancelled_at' => now(),
                'cancellation_note' => $validated['cancellation_note'],
            ]);

            // Eager load to prevent N+1
            $order->load('orderItems');
            foreach ($order->orderItems as $item) {
                ProductInventory::increaseStock($item->product_id, $item->qty);
            }
        });

        return $this->success(new OrderResource($order->fresh()), 'Order cancelled');
    }

    public function complete(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($order->isCancelled()) {
            return $this->error('Cannot complete a cancelled order', 422);
        }
        if (!$order->isPaid()) {
            return $this->error('Order must be paid first', 422);
        }

        DB::transaction(function () use ($order, $request) {
            // Eager load to prevent N+1
            $order->load('orderItems');

            // Batch load all variant IDs to prevent N+1 in loop
            $variantIds = $order->orderItems->pluck('variant_id')->filter()->unique();
            $variants = $variantIds->isNotEmpty()
                ? \App\Models\ProductVariant::whereIn('id', $variantIds)->get()->keyBy('id')
                : collect();

            // Reduce stock
            foreach ($order->orderItems as $item) {
                if ($item->variant_id && $variants->has($item->variant_id)) {
                    $variants[$item->variant_id]->decrement('stock', $item->qty);
                } else {
                    ProductInventory::reduceStock($item->product_id, $item->qty);
                }
            }

            $order->update([
                'status' => Order::COMPLETED,
                'approved_at' => now(),
            ]);

            // Track employee performance
            if ($order->use_employee_tracking && $order->handled_by) {
                EmployeePerformance::create([
                    'order_id' => $order->id,
                    'employee_name' => $order->handled_by,
                    'transaction_value' => $order->grand_total,
                    'completed_at' => now(),
                ]);
            }
        });

        return $this->success(new OrderResource($order->fresh()), 'Order completed');
    }

    public function assignEmployee(Request $request, $id)
    {
        $validated = $request->validate([
            'employee_name' => 'required|string|max:255',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'handled_by' => $validated['employee_name'],
            'use_employee_tracking' => true,
        ]);

        return $this->success(new OrderResource($order->fresh()), 'Employee assigned');
    }

    public function updateShipping(Request $request, $id)
    {
        $validated = $request->validate([
            'track_number' => 'required|string|max:100',
            'shipped_by' => 'nullable|string|max:255',
        ]);

        $order = Order::findOrFail($id);
        $shipment = Shipment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'track_number' => $validated['track_number'],
                'status' => 'shipped',
                'shipped_by' => $validated['shipped_by'] ?? auth()->user()->name,
                'shipped_at' => now(),
            ]
        );

        $order->update(['status' => Order::DELIVERED]);

        return $this->success([
            'order' => new OrderResource($order->fresh()->load('shipment')),
            'shipment' => $shipment,
        ], 'Shipping updated');
    }

    public function invoice($id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);

        $pdf = Pdf::loadView('admin.orders.invoices', compact('order'))
            ->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'dpi' => 96]);
        $pdf->setPaper([0, 0, 226.77, 2000], 'portrait');

        return $pdf->stream("invoice-{$order->code}.pdf");
    }

    public function destroy($id)
    {
        $order = Order::withTrashed()->findOrFail($id);

        if ($order->trashed()) {
            DB::transaction(function () use ($order) {
                OrderItem::where('order_id', $order->id)->delete();
                Payment::where('order_id', $order->id)->delete();
                if ($order->shipment) $order->shipment->delete();
                $order->forceDelete();
            });
        } else {
            DB::transaction(function () use ($order) {
                if (!$order->isCancelled()) {
                    // Eager load to prevent N+1
                    $order->load('orderItems');
                    foreach ($order->orderItems as $item) {
                        ProductInventory::increaseStock($item->product_id, $item->qty);
                    }
                }
                $order->delete();
            });
        }

        return $this->success(null, 'Order deleted');
    }

    public function trashed(Request $request)
    {
        $orders = Order::onlyTrashed()->with('orderItems')
            ->orderBy('deleted_at', 'desc')
            ->paginate(20);

        return $this->paginated($orders->through(fn($o) => new OrderResource($o)));
    }

    public function restore($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->restore();

        return $this->success(new OrderResource($order), 'Order restored');
    }
}
