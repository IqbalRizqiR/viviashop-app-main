<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\ProductInventory;
use App\Http\Controllers\Controller;
use App\Exceptions\OutOfStockException;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

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
		$provinces = $this->getProvinces();
		$products = Product::where('type', 'simple')->get();
		// dd($provinces);
		return view('admin.order-admin.create', compact('provinces', 'products'));
	}

	public function storeAdmin(Request $request)
	{
		$params = $request->all();
		$params['attachments'] = $request->file('file');
		$params['unique_code'] = random_int('1', '999');

		$order = DB::transaction(function () use ($params) {
			$order = $this->_saveOrder($params);
			$this->_saveOrderItems($order, $params);
			return $order;
		});

		if ($order) {
			Session::flash('success', 'Order has been created successfully!');
			return redirect()->route('admin.orders.index');
		}

		return redirect()->back()->withErrors(['error' => 'Order creation failed. Please try again.']);
	}

	private function _saveOrder($params)
	{
		// dd($params['product_id']);
		$products = Product::where('id', $params['product_id'])->first();
		$baseTotalPrice = $products->price;
		$taxAmount = 0;
		$taxPercent = 0;
		// dd($params);
		$discountAmount = 0;
		$paymentMethod = 'toko';
		$unique_code = $params['unique_code'];
		$discountPercent = 0;
		$grandTotal = ($baseTotalPrice + $taxAmount) - $discountAmount + $unique_code;
		$orderDate = date('Y-m-d H:i:s');
		$paymentDue = (new \DateTime($orderDate))->modify('+7 day')->format('Y-m-d H:i:s');

		$user_profile = [
			'first_name' => $params['first_name'],
			'last_name' => $params['last_name'],
			'address1' => $params['address1'],
			'postcode' => $params['postcode'],
			'phone' => $params['phone'],
			'email' => $params['email'],
		];

		auth()->user()->update($user_profile);

		if ($params['attachments'] != null) {
			$orderParams = [
				'user_id' => auth()->id(),
				'code' => Order::generateCode(),
				'status' => Order::CREATED,
				'order_date' => $orderDate,
				'payment_due' => $paymentDue,
				'payment_status' => Order::PAID,
				'attachments' => $params['attachments']->store('assets/slides', 'public'),
				'base_total_price' => $baseTotalPrice,
				'tax_amount' => $taxAmount,
				'tax_percent' => $taxPercent,
				'discount_amount' => $discountAmount,
				'discount_percent' => $discountPercent,
				'grand_total' => $grandTotal,
				'note' => $params['note'],
				'customer_first_name' => $params['first_name'],
				'customer_last_name' => $params['last_name'],
				'customer_address1' => $params['address1'],
				'payment_method' => $paymentMethod,
				'customer_phone' => $params['phone'],
				'customer_email' => $params['email'],
				'customer_postcode' => $params['postcode'],
			];
		} else {
			$orderParams = [
				'user_id' => auth()->id(),
				'code' => Order::generateCode(),
				'status' => Order::CREATED,
				'order_date' => $orderDate,
				'payment_due' => $paymentDue,
				'payment_status' => Order::UNPAID,
				'base_total_price' => $baseTotalPrice,
				'tax_amount' => $taxAmount,
				'tax_percent' => $taxPercent,
				'discount_amount' => $discountAmount,
				'discount_percent' => $discountPercent,
				'grand_total' => $grandTotal,
				'note' => $params['note'],
				'customer_first_name' => $params['first_name'],
				'customer_last_name' => $params['last_name'],
				'customer_address1' => $params['address1'],
				'payment_method' => $paymentMethod,
				'customer_phone' => $params['phone'],
				'customer_email' => $params['email'],
				'customer_postcode' => $params['postcode'],
			];
		}


		return Order::create($orderParams);
	}

	private function _saveOrderItems($order, $params)
	{
		if ($order) {
			$products = Product::where('id', $params['product_id'])->first();
			$itemTaxAmount = 0;
			$itemTaxPercent = 0;
			$itemDiscountAmount = 0;
			$itemDiscountPercent = 0;
			$itemBaseTotal = $params['qty'] * $products->price;
			$itemSubTotal = $itemBaseTotal + $itemTaxAmount - $itemDiscountAmount;

			$product = isset($products->model->parent) ? $products->model->parent : $products->model;

			$orderItemParams = [
				'order_id' => $order->id,
				'product_id' => $params['product_id'],
				'qty' => $params['qty'],
				'base_price' => $products->price,
				'base_total' => $itemBaseTotal,
				'tax_amount' => $itemTaxAmount,
				'tax_percent' => $itemTaxPercent,
				'discount_amount' => $itemDiscountAmount,
				// 'attachments' => $order->
				'discount_percent' => $itemDiscountPercent,
				'sub_total' => $itemSubTotal,
				'sku' => $products->sku,
				'type' => $products->type,
				'name' => $products->name,
				'weight' => $products->weight,
				'attributes' => json_encode($products->options),
			];

			$orderItem = OrderItem::create($orderItemParams);

			if ($orderItem) {
				ProductInventory::reduceStock($orderItem->product_id, $orderItem->qty);
			}
		}
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
		if (!$order->isDelivered()) {
			return redirect('admin/orders');
		}

		$order->status = Order::COMPLETED;
		$order->approved_by = auth()->id();
		$order->approved_at = now();

		if ($order->save()) {
			return redirect('admin/orders');
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
