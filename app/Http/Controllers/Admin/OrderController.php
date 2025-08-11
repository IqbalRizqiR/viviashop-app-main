<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\ProductInventory;
use App\Http\Controllers\Controller;
use App\Exceptions\OutOfStockException;
use App\Models\Payment;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $statuses = Order::STATUSES;
        $orders = Order::latest();

        $q = $request->input('q');
		if ($q) {
			$orders = $orders->where('code', 'like', '%'. $q .'%')
				->orWhere('customer_first_name', 'like', '%'. $q .'%')
				->orWhere('customer_last_name', 'like', '%'. $q .'%');
		}


		if ($request->input('status') && in_array($request->input('status'), array_keys(Order::STATUSES))) {
			$orders = $orders->where('status', '=', $request->input('status'));
		}

		$startDate = $request->input('start');
		$endDate = $request->input('end');

		if ($startDate && !$endDate) {
			Session::flash('error', 'The end date is required if the start date is present');
			return redirect('admin/orders');
		}

		if (!$startDate && $endDate) {
			Session::flash('error', 'The start date is required if the end date is present');
			return redirect('admin/orders');
		}

		if ($startDate && $endDate) {
			if (strtotime($endDate) < strtotime($startDate)) {
				Session::flash('error', 'The end date should be greater or equal than start date');
				return redirect('admin/orders');
			}

			$order = $orders->whereRaw("DATE(order_date) >= ?", $startDate)
				->whereRaw("DATE(order_date) <= ? ", $endDate);
        }

        $orders = $orders->get();;

		return view('admin.orders.index', compact('orders','statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
	{
		$order = Order::withTrashed()->findOrFail($id);
		// dd($order);

		return view('admin.orders.show', compact('order'));
	}

    public function invoices($id)
    {
        $order = Order::where('id', $id)->first();

        $pdf  = Pdf::loadView('admin.orders.invoices', compact('order'))->setOptions(['defaultFont' => 'sans-serif']);
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('invoice.pdf');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
		//
		dd('ok');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
	{
		$order = Order::withTrashed()->findOrFail($id);

		if ($order->trashed()) {
			$canDestroy = DB::transaction(
				function () use ($order) {
					OrderItem::where('order_id', $order->id)->delete();
                    Payment::where('order_id', $order->id)->delete();
					$order->shipment->delete();
					$order->forceDelete();

					return true;
				}
			);
			return redirect('admin/orders/trashed');
		} else {
			$canDestroy = DB::transaction(
				function () use ($order) {
					if (!$order->isCancelled()) {
						foreach ($order->orderItems as $item) {
							ProductInventory::increaseStock($item->product_id, $item->qty);
						}
					};

					$order->delete();

					return true;
				}
			);

			return redirect('admin/orders');
		}
	}

	public function checkPage()
	{
		$provinces = [];
		$products = Product::where('type', 'simple')->get();
		return view('admin.order-admin.create', compact('provinces', 'products'));
	}

	public function storeAdmin(Request $request)
	{
		$validated = $request->validate([
			'first_name' => 'required|string|max:255',
			'last_name' => 'required|string|max:255',
			'address1' => 'required|string|max:255',
			'postcode' => 'required|string|max:10',
			'phone' => 'required|string|max:20',
			'email' => 'required|email|max:255',
			'product_id' => 'required|array|min:1',
			'product_id.*' => 'required|integer',
			'qty' => 'required|array|min:1',
			'qty.*' => 'required|integer|min:1',
		]);

		if (count($validated['product_id']) !== count($validated['qty'])) {
			return redirect()->back()->withErrors(['error' => 'Product and quantity count mismatch'])->withInput();
		}

		$order = DB::transaction(function () use ($request, $validated) {
			$totalPrice = 0;
			$orderItems = [];

			for ($i = 0; $i < count($validated['product_id']); $i++) {
				$product = Product::find($validated['product_id'][$i]);
				if (!$product) {
					throw new \Exception('Product not found: ' . $validated['product_id'][$i]);
				}

				$qty = $validated['qty'][$i];
				$itemTotal = $product->price * $qty;
				$totalPrice += $itemTotal;

				$orderItems[] = [
					'product_id' => $product->id,
					'qty' => $qty,
					'base_price' => $product->price,
					'base_total' => $itemTotal,
					'tax_amount' => 0,
					'tax_percent' => 0,
					'discount_amount' => 0,
					'discount_percent' => 0,
					'sub_total' => $itemTotal,
					'sku' => $product->sku ?? '',
					'type' => $product->type ?? 'simple',
					'name' => $product->name,
					'weight' => (string)($product->weight ?? 0),
					'attributes' => '[]',
				];
			}

			$uniqueCode = rand(1, 999);
			$grandTotal = $totalPrice + $uniqueCode;

			$orderData = [
				'user_id' => auth()->id(),
				'code' => Order::generateCode(),
				'status' => Order::CREATED,
				'order_date' => now(),
				'payment_due' => now()->addDays(7),
				'payment_status' => Order::UNPAID,
				'payment_method' => 'toko',
				'base_total_price' => $totalPrice,
				'tax_amount' => 0,
				'tax_percent' => 0,
				'discount_amount' => 0,
				'discount_percent' => 0,
				'shipping_cost' => 0,
				'grand_total' => $grandTotal,
				'notes' => $request->input('note'),
				'customer_first_name' => $validated['first_name'],
				'customer_last_name' => $validated['last_name'],
				'customer_address1' => $validated['address1'],
				'customer_phone' => $validated['phone'],
				'customer_email' => $validated['email'],
				'customer_postcode' => (int)$validated['postcode'],
			];

			if ($request->hasFile('attachments')) {
				$orderData['attachments'] = $request->file('attachments')->store('assets/slides', 'public');
			}

			$order = Order::create($orderData);

			foreach ($orderItems as $itemData) {
				$itemData['order_id'] = $order->id;
				$orderItem = OrderItem::create($itemData);
				
				if ($orderItem) {
					try {
						ProductInventory::reduceStock($itemData['product_id'], $itemData['qty']);
					} catch (\Exception $e) {
					}
				}
			}

			return $order;
		});

		Session::flash('success', 'Order has been created successfully!');
		return redirect()->route('admin.orders.index');
	}

    public function cancel(Order $order)
	{
		return view('admin.orders.cancel', compact('order'));
    }

    public function doCancel(Request $request, Order $order)
	{
		$request->validate(
			[
				'cancellation_note' => 'required|max:255',
			]
		);

		$cancelOrder = DB::transaction(
			function () use ($order, $request) {
				$params = [
					'status' => Order::CANCELLED,
					'cancelled_by' => auth()->id(),
					'cancelled_at' => now(),
					'cancellation_note' => $request->input('cancellation_note'),
				];

				if ($cancelOrder = $order->update($params) && $order->orderItems->count() > 0) {
					foreach ($order->orderItems as $item) {
						ProductInventory::increaseStock($item->product_id, $item->qty);
					}
				}

				return $cancelOrder;
			}
		);

		// \Session::flash('success', 'The order has been cancelled');

		return redirect('admin/orders');
	}

    public function doComplete(Request $request,Order $order)
	{
		if (!$order->isDelivered() && $order->isPaid()) {
			if ($order->shipping_service_name == 'SELF' && $order->isPaid()) {
                $order->shipment->status = 'delivered';
                $order->shipment->delivered_by = auth()->id();
                $order->shipment->delivered_at = now();
                $order->status = Order::COMPLETED;
                $order->approved_by = auth()->id();
                $order->approved_at = now();

                if ($order->save()) {
                    Alert::success('Success', 'Order has been completed successfully!');
                    return redirect()->back();
                }
            }
            Alert::error('Error', 'Order cannot be completed because it has not been delivered or paid yet.');
            return redirect()->back();
		}

		if(!$order->isPaid()) {
            Alert::error('Error', 'Order cannot be completed because it has not been paid yet.');
            return redirect()->back();
        } else {
            $order->status = Order::COMPLETED;
            $order->approved_by = auth()->id();
            $order->approved_at = now();
        }

		if ($order->save()) {
			return redirect()->back()->with('success', 'Order has been completed successfully!');
		}
	}

    public function trashed()
	{
		$orders = Order::onlyTrashed()->latest()->get();

		return view('admin.orders.trashed', compact('orders'));
	}

	public function restore($id)
	{
		$order = Order::onlyTrashed()->findOrFail($id);

		$canRestore = DB::transaction(
			function () use ($order) {
				$isOutOfStock = false;
				if (!$order->isCancelled()) {
					foreach ($order->orderItems as $item) {
						try {
							ProductInventory::reduceStock($item->product_id, $item->qty);
						} catch (OutOfStockException $e) {
							$isOutOfStock = true;
							Session::flash('error', $e->getMessage());
						}
					}
				};

				if ($isOutOfStock) {
					return false;
				} else {
					return $order->restore();
				}
			}
		);

		if ($canRestore) {
			return redirect('admin/orders');
		} else {
			return redirect('admin/orders/trashed');
		}
	}
}
