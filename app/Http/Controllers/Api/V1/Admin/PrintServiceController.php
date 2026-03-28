<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\BaseController;
use App\Models\PrintSession;
use App\Models\PrintOrder;
use App\Models\PrintFile;
use App\Services\PrintService;
use App\Services\StockManagementService;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PrintServiceController extends BaseController
{
    protected $printService;
    protected $stockService;

    public function __construct(PrintService $printService)
    {
        $this->printService = $printService;
        $this->stockService = new StockManagementService();
    }

    public function dashboard()
    {
        $stockData = $this->stockService->getStockReport();
        $lowStockVariants = $this->stockService->getLowStockVariants();

        return $this->success([
            'active_sessions' => PrintSession::active()->count(),
            'pending_orders' => PrintOrder::where('payment_status', PrintOrder::PAYMENT_WAITING)->count(),
            'print_queue' => PrintOrder::printQueue()->count(),
            'today_orders' => PrintOrder::whereDate('created_at', today())->count(),
            'recent_orders' => PrintOrder::with(['paperProduct', 'paperVariant'])
                ->orderBy('created_at', 'desc')->limit(10)->get(),
            'stock_data' => $stockData,
            'low_stock_variants' => $lowStockVariants,
        ]);
    }

    public function queue()
    {
        return $this->success($this->printService->getPrintQueue());
    }

    public function sessions(Request $request)
    {
        $sessions = PrintSession::with('printOrders')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return $this->paginated($sessions);
    }

    public function orders(Request $request)
    {
        $query = PrintOrder::with(['paperProduct', 'paperVariant', 'session'])
            ->orderBy('created_at', 'desc');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($paymentStatus = $request->input('payment_status')) {
            $query->where('payment_status', $paymentStatus);
        }

        return $this->paginated($query->paginate(20));
    }

    public function generateSession()
    {
        try {
            $session = $this->printService->generateSession();
            return $this->success([
                'session' => $session,
                'qr_code_url' => $session->getQrCodeUrl(),
            ], 'Session generated', 201);
        } catch (\Exception $e) {
            Log::error('Generate session error: ' . $e->getMessage());
            return $this->error('Failed to generate session', 500);
        }
    }

    public function confirmPayment($id)
    {
        try {
            $printOrder = PrintOrder::findOrFail($id);

            if ($printOrder->payment_method !== 'toko' && $printOrder->payment_method !== 'manual') {
                return $this->error('Invalid payment method for manual confirmation', 422);
            }
            if ($printOrder->payment_status === PrintOrder::PAYMENT_PAID) {
                return $this->error('Payment already confirmed', 422);
            }

            $this->printService->confirmPayment($printOrder);

            return $this->success(null, 'Payment confirmed and stock reduced');
        } catch (\Exception $e) {
            Log::error('Confirm payment error: ' . $e->getMessage());
            return $this->error($e->getMessage(), 400);
        }
    }

    public function printOrder($id)
    {
        try {
            $printOrder = PrintOrder::findOrFail($id);
            $this->printService->printDocument($printOrder);
            return $this->success(null, 'Document sent to printer');
        } catch (\Exception $e) {
            Log::error('Print order error: ' . $e->getMessage());
            return $this->error($e->getMessage(), 400);
        }
    }

    public function printFiles($id)
    {
        try {
            $printOrder = PrintOrder::with(['files'])->findOrFail($id);

            $files = [];
            foreach ($printOrder->files as $file) {
                $storagePath = storage_path('app/' . str_replace('/', DIRECTORY_SEPARATOR, $file->file_path));
                $publicPath = public_path('storage/' . str_replace('/', DIRECTORY_SEPARATOR, $file->file_path));

                $exists = file_exists($storagePath) || file_exists($publicPath);
                if ($exists) {
                    $files[] = [
                        'id' => $file->id,
                        'original_name' => $file->file_name,
                        'url' => asset('storage/' . $file->file_path),
                    ];
                }
            }

            return $this->success([
                'files' => $files,
                'order_code' => $printOrder->order_code,
                'customer_name' => $printOrder->customer_name,
                'print_data' => [
                    'paper_size' => $printOrder->paperVariant->paper_size ?? 'A4',
                    'print_type' => $printOrder->print_type,
                    'quantity' => $printOrder->quantity,
                    'total_pages' => $printOrder->total_pages,
                ],
            ]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 400);
        }
    }

    public function completeOrder($id)
    {
        try {
            $printOrder = PrintOrder::with('files')->findOrFail($id);

            if ($printOrder->isCompleted()) {
                return $this->error('Order is already completed', 422);
            }
            if ($printOrder->status === PrintOrder::STATUS_CANCELLED) {
                return $this->error('Cannot complete a cancelled order', 422);
            }
            if (!$printOrder->isPaid()) {
                return $this->error('Order must be paid before completion', 422);
            }

            // Delete files for privacy
            foreach ($printOrder->files as $file) {
                $storagePath = storage_path('app/' . str_replace('/', DIRECTORY_SEPARATOR, $file->file_path));
                $publicPath = public_path('storage/' . str_replace('/', DIRECTORY_SEPARATOR, $file->file_path));

                if (file_exists($storagePath)) unlink($storagePath);
                if (file_exists($publicPath)) unlink($publicPath);
                $file->delete();
            }

            $printOrder->update([
                'status' => PrintOrder::STATUS_COMPLETED,
                'completed_at' => now(),
            ]);

            // Record stock movement
            if ($printOrder->paper_product_id) {
                try {
                    $product = \App\Models\Product::find($printOrder->paper_product_id);
                    $variant = \App\Models\ProductVariant::find($printOrder->paper_variant_id);

                    if ($product && $variant) {
                        app(StockService::class)->recordMovement(
                            $printOrder->paper_product_id,
                            $printOrder->paper_variant_id,
                            $printOrder->quantity,
                            'out',
                            'Smart Print Service',
                            "Print Order #{$printOrder->order_code}"
                        );
                    }
                } catch (\Exception $e) {
                    Log::error("Stock movement failed: " . $e->getMessage());
                }
            }

            return $this->success(null, 'Order completed and files deleted');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 400);
        }
    }

    public function cancelOrder($id)
    {
        $printOrder = PrintOrder::findOrFail($id);
        $printOrder->update(['status' => PrintOrder::STATUS_CANCELLED]);
        return $this->success(null, 'Order cancelled');
    }

    public function reports(Request $request)
    {
        $startDate = $request->input('start_date', today()->format('Y-m-d'));
        $endDate = $request->input('end_date', today()->format('Y-m-d'));

        $orders = PrintOrder::whereBetween('created_at', [$startDate, $endDate])
            ->with(['paperProduct', 'paperVariant'])
            ->get();

        $totalRevenue = $orders->where('payment_status', PrintOrder::PAYMENT_PAID)->sum('total_price');
        $totalOrders = $orders->count();
        $completed = $orders->where('status', PrintOrder::STATUS_COMPLETED)->count();

        return $this->success([
            'stats' => [
                'total_revenue' => (float) $totalRevenue,
                'total_orders' => $totalOrders,
                'completed_orders' => $completed,
                'total_pages' => $orders->sum('total_pages'),
                'completion_rate' => $totalOrders > 0 ? round(($completed / $totalOrders) * 100, 2) : 0,
            ],
            'orders' => $orders,
            'period' => ['start' => $startDate, 'end' => $endDate],
        ]);
    }

    public function stockManagement()
    {
        return $this->success([
            'variants' => $this->stockService->getVariantsByStock('asc'),
            'low_stock' => $this->stockService->getLowStockVariants(),
            'recent_movements' => $this->stockService->getStockReport(null, now()->subDays(7), now()),
        ]);
    }

    public function adjustStock(Request $request, $variantId)
    {
        $validated = $request->validate([
            'new_stock' => 'required|integer|min:0',
            'reason' => 'required|string',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $result = $this->stockService->adjustStock(
                $variantId, $validated['new_stock'], $validated['reason'], $validated['notes'] ?? null
            );
            return $this->success($result, 'Stock adjusted');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 400);
        }
    }

    public function downloadPaymentProof($id)
    {
        $printOrder = PrintOrder::findOrFail($id);

        if (!$printOrder->payment_proof) {
            return $this->error('No payment proof found', 404);
        }

        $filePath = storage_path('app/' . $printOrder->payment_proof);
        if (!file_exists($filePath)) {
            return $this->error('Payment proof file not found on server', 404);
        }

        return response()->file($filePath);
    }
}
