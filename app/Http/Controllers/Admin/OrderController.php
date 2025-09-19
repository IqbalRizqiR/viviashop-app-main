<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipment;
use Illuminate\Http\Request;
use App\Models\ProductInventory;
use App\Services\StockService;
use App\Http\Controllers\Controller;
use App\Exceptions\OutOfStockException;
use App\Models\Payment;
use App\Models\Product;
use App\Models\EmployeePerformance;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use Midtrans\Notification;

class OrderController extends Controller
{
    protected $midtransServerKey;
    protected $midtransClientKey;
    protected $isProduction;
    protected $isSanitized;
    protected $is3ds;

    public function __construct()
    {
        $this->midtransServerKey = config('midtrans.serverKey');
        $this->midtransClientKey = config('midtrans.clientKey');
        $this->isProduction = config('midtrans.isProduction');
        $this->isSanitized = config('midtrans.isSanitized');
        $this->is3ds = config('midtrans.is3ds');
    }

    private function initPaymentGateway()
    {
        MidtransConfig::$serverKey = $this->midtransServerKey;
        MidtransConfig::$isProduction = $this->isProduction;
        MidtransConfig::$isSanitized = $this->isSanitized;
        MidtransConfig::$is3ds = $this->is3ds;
        
        $isLocalhost = in_array(request()->getHost(), ['localhost', '127.0.0.1', '::1']) || 
                       str_contains(request()->getHost(), '.local') ||
                       str_contains(request()->getHost(), 'laragon');
        
        if ($isLocalhost) {
            MidtransConfig::$curlOptions = [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTPHEADER => []
            ];
        }
    }

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

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
	{
		$order = Order::withTrashed()->with('shipment')->findOrFail($id);
		
		// Prepare payment data for Midtrans
		$paymentData = [
			'midtransClientKey' => config('midtrans.clientKey'),
			'isProduction' => config('midtrans.isProduction'),
			'snapUrl' => config('midtrans.isProduction')
				? 'https://app.midtrans.com/snap/snap.js'
				: 'https://app.sandbox.midtrans.com/snap/snap.js'
		];
		
		// Get employee list for dropdown
		$employees = EmployeePerformance::getEmployeeList();
		
		return view('admin.orders.show', compact('order', 'paymentData', 'employees'));
	}

    public function invoices($id)
    {
        $order = Order::where('id', $id)->first();
        $pdf  = Pdf::loadView('admin.orders.invoices', compact('order'))->setOptions(['defaultFont' => 'sans-serif']);
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('invoice.pdf');
    }

    public function edit(string $id)
    {
		dd('ok');
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy($id)
	{
		$order = Order::withTrashed()->findOrFail($id);

		if ($order->trashed()) {
			$canDestroy = DB::transaction(
				function () use ($order) {
					OrderItem::where('order_id', $order->id)->delete();
                    Payment::where('order_id', $order->id)->delete();
					if ($order->shipment) {
						$order->shipment->delete();
					}
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
		$configurable_attributes = \App\Models\Attribute::where('is_configurable', true)
			->with(['attribute_variants.attribute_options'])
			->get();
		$paymentMethods = [
			'qris' => 'QRIS',
			'midtrans' => 'Midtrans Gateway',
			'toko' => 'Bayar di Toko',
			'transfer' => 'Transfer Bank'
		];
		return view('admin.order-admin.create', compact('provinces', 'products', 'paymentMethods', 'configurable_attributes'));
	}

	public function storeAdmin(Request $request)
	{
		try {
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
				'payment_method' => 'nullable|string|in:qris,midtrans,toko,transfer',
				'variant_id' => 'nullable|array',
				'variant_id.*' => 'nullable',
				'variant_attributes' => 'nullable|array',
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
				$price = $product->price;
				$productSku = $product->sku ?? '';
				$productName = $product->name;

				$variantId = null;
				if ($request->has('variant_id') && is_array($request->input('variant_id'))) {
					$variantIdValue = $request->input('variant_id')[$i] ?? null;
					
					if ($variantIdValue) {
						// Handle simple products with format 'simple_123'
						if (is_string($variantIdValue) && strpos($variantIdValue, 'simple_') === 0) {
							// For simple products, we don't need to find variant, just use product data
							$variantId = null;
						} else if (is_numeric($variantIdValue) && $product->configurable()) {
							// Handle configurable products with actual variant IDs
							$variantId = intval($variantIdValue);
							$variant = \App\Models\ProductVariant::find($variantId);
							if ($variant && $variant->product_id == $product->id) {
								$price = $variant->price;
								$productSku = $variant->sku;
								$productName = $variant->name;
							}
						}
					}
				}

				$itemTotal = $price * $qty;
				$totalPrice += $itemTotal;

				$attributes = $this->_collectProductAttributes($product, $request, $i);

				$orderItems[] = [
					'product_id' => $product->id,
					'variant_id' => $variantId,
					'qty' => $qty,
					'base_price' => $price,
					'base_total' => $itemTotal,
					'tax_amount' => 0,
					'tax_percent' => 0,
					'discount_amount' => 0,
					'discount_percent' => 0,
					'sub_total' => $itemTotal,
					'sku' => $productSku,
					'type' => $product->type ?? 'simple',
					'name' => $productName,
					'weight' => (string)($product->weight ?? 0),
					'attributes' => json_encode($attributes),
				];
			}

			$uniqueCode = 0;
			$grandTotal = $totalPrice + $uniqueCode;
			$paymentMethod = $validated['payment_method'] ?? 'toko';

			$orderData = [
				'user_id' => auth()->id(),
				'code' => Order::generateCode(),
				'status' => Order::CREATED,
				'order_date' => Carbon::now(),
				'payment_due' => Carbon::now()->addDays(7),
				'payment_status' => Order::UNPAID,
				'payment_method' => $paymentMethod,
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
				'customer_address2' => '',
				'customer_phone' => $validated['phone'],
				'customer_email' => $validated['email'],
				'customer_postcode' => $validated['postcode'],
				'customer_city_id' => 1,
				'customer_province_id' => 1,
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
						// Only validate stock availability, don't reduce it yet
						// Stock will be reduced when order is completed/confirmed
						if ($itemData['variant_id']) {
							$variant = \App\Models\ProductVariant::find($itemData['variant_id']);
							if ($variant) {
								if ($variant->stock < $itemData['qty']) {
									throw new OutOfStockException('The variant ' . $variant->sku . ' is out of stock. Available: ' . $variant->stock . ', Requested: ' . $itemData['qty']);
								}
								// Don't reduce stock here - will be done on completion
							} else {
								throw new \Exception('Variant not found: ' . $itemData['variant_id']);
							}
						} else {
							// For simple products, check ProductInventory stock availability
							$inventory = ProductInventory::where('product_id', $itemData['product_id'])->first();
							if ($inventory && $inventory->qty < $itemData['qty']) {
								$product = Product::findOrFail($itemData['product_id']);
								throw new OutOfStockException('The product '. $product->sku .' is out of stock. Available: ' . $inventory->qty . ', Requested: ' . $itemData['qty']);
							}
							// Don't reduce stock here - will be done on completion
						}
					} catch (\Exception $e) {
						Log::error('Stock validation failed', [
							'product_id' => $itemData['product_id'],
							'variant_id' => $itemData['variant_id'],
							'qty' => $itemData['qty'],
							'error' => $e->getMessage()
						]);
						throw $e;
					}
				}
			}

			if ($paymentMethod === 'qris' || $paymentMethod === 'midtrans') {
				$paymentResponse = $this->_generatePaymentToken($order);
				if ($paymentResponse['success']) {
					$order->payment_token = $paymentResponse['token'];
					$order->payment_url = $paymentResponse['redirect_url'];
					$order->save();
				} else {
					Log::error('Payment token generation failed for order', [
						'order_id' => $order->id,
						'error' => $paymentResponse['message']
					]);
					if ($request->ajax() || $request->expectsJson()) {
						return response()->json([
							'success' => false,
							'message' => 'Failed to generate payment token: ' . $paymentResponse['message']
						], 400);
					}
					throw new \Exception('Failed to generate payment token: ' . $paymentResponse['message']);
				}
			}

			return $order;
		});

		if ($request->ajax() || $request->expectsJson()) {
			$response = [
				'success' => true,
				'order_id' => $order->id,
				'order_code' => $order->code,
				'message' => 'Order created successfully!'
			];

			if (in_array($order->payment_method, ['qris', 'midtrans'])) {
				if ($order->payment_token) {
					$response['payment_token'] = $order->payment_token;
					$response['payment_url'] = $order->payment_url;
				} else {
					return response()->json([
						'success' => false,
						'message' => 'Order created but payment token generation failed. Please contact administrator.'
					], 500);
				}
			}

			return response()->json($response);
		}

		Session::flash('success', 'Order has been created successfully!');
		return redirect()->route('admin.orders.show', $order->id);
		
		} catch (\Exception $e) {
			Log::error('Order creation error: ' . $e->getMessage());
			
			if ($request->ajax() || $request->expectsJson()) {
				return response()->json([
					'success' => false,
					'message' => 'Error creating order: ' . $e->getMessage()
				], 500);
			}
			
			return redirect()->back()->withErrors(['error' => 'Error creating order. Please try again.'])->withInput();
		}
	}

	public function paymentNotification(Request $request)
	{
		try {
			$this->initPaymentGateway();

			$notification = new Notification();

			$transactionStatus = $notification->transaction_status;
			$orderCode = $notification->order_id;
			
			$order = Order::where('code', $orderCode)->first();
			
			if (!$order) {
				return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
			}

			if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
				$order->payment_status = Order::PAID;
				if ($order->shipping_service_name == 'Self Pickup') {
					$order->status = Order::CONFIRMED;
					$order->notes = $order->notes . "\nPayment confirmed via admin notification. Waiting for pickup confirmation.";
				} else {
					$order->status = Order::COMPLETED;
					$order->approved_at = Carbon::now();
				}
				$order->save();
			} elseif ($transactionStatus == 'pending') {
				$order->payment_status = Order::WAITING;
				$order->save();
			} elseif ($transactionStatus == 'cancel' || $transactionStatus == 'expire') {
				$order->payment_status = Order::UNPAID;
				$order->save();
			}

			return response()->json(['status' => 'success']);
		} catch (\Exception $e) {
			Log::error('Payment notification error: ' . $e->getMessage());
			return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
		}
	}

	public function generatePaymentToken(Order $order)
	{
		if (!in_array($order->payment_method, ['qris', 'midtrans'])) {
			if (request()->expectsJson()) {
				return response()->json(['success' => false, 'message' => 'Payment method not supported for token generation.']);
			}
			return redirect()->back()->with('error', 'Payment method not supported for token generation.');
		}

		if ($order->payment_status !== Order::UNPAID) {
			if (request()->expectsJson()) {
				return response()->json(['success' => false, 'message' => 'Payment token can only be generated for unpaid orders.']);
			}
			return redirect()->back()->with('error', 'Payment token can only be generated for unpaid orders.');
		}

		$paymentResponse = $this->_generatePaymentToken($order);
		
		if ($paymentResponse['success']) {
			$order->payment_token = $paymentResponse['token'];
			$order->payment_url = $paymentResponse['redirect_url'];
			$order->save();
			
			if (request()->expectsJson()) {
				return response()->json([
					'success' => true,
					'payment_token' => $paymentResponse['token'],
					'payment_url' => $paymentResponse['redirect_url'],
					'message' => 'Payment token generated successfully.'
				]);
			}
			
			return redirect()->back()->with('success', 'Payment token generated successfully. You can now process the payment.');
		}
		
		if (request()->expectsJson()) {
			return response()->json(['success' => false, 'message' => 'Failed to generate payment token: ' . $paymentResponse['message']]);
		}
		
		return redirect()->back()->with('error', 'Failed to generate payment token: ' . $paymentResponse['message']);
	}

	public function paymentFinishRedirect(Request $request)
	{
		$orderId = $request->get('order_id');
		$order = Order::where('code', $orderId)->first();
		
		if (!$order) {
			return redirect()->route('admin.orders.index')->with('error', 'Order not found.');
		}
		
		// Update payment status to paid
		$order->payment_status = Order::PAID;
		$order->status = Order::CONFIRMED;
		$order->approved_at = now();
		$order->notes = $order->notes . "\nPayment completed successfully via " . $order->payment_method;
		$order->save();
		
		return redirect()->route('admin.orders.show', $order->id)->with('success', 'Payment successful! Order has been confirmed.');
	}

	public function paymentUnfinishRedirect(Request $request)
	{
		$orderId = $request->get('order_id');
		$order = Order::where('code', $orderId)->first();
		
		if (!$order) {
			return redirect()->route('admin.orders.index')->with('error', 'Order not found.');
		}
		
		// Update payment status to waiting/pending
		$order->payment_status = Order::WAITING;
		$order->notes = $order->notes . "\nPayment pending via " . $order->payment_method;
		$order->save();
		
		return redirect()->route('admin.orders.show', $order->id)->with('warning', 'Payment pending. Please complete your payment or wait for payment confirmation.');
	}

	public function paymentErrorRedirect(Request $request)
	{
		$orderId = $request->get('order_id');
		$order = Order::where('code', $orderId)->first();
		
		if (!$order) {
			return redirect()->route('admin.orders.index')->with('error', 'Order not found.');
		}
		
		// Keep payment status as unpaid for error
		$order->notes = $order->notes . "\nPayment failed via " . $order->payment_method;
		$order->save();
		
		return redirect()->route('admin.orders.show', $order->id)->with('error', 'Payment failed. Please try again or contact support.');
	}

	private function _collectProductAttributes($product, $request, $itemIndex = null)
	{
		$attributes = [];
		
		if ($product->configurable()) {
			if ($itemIndex !== null && $request->has('variant_id') && is_array($request->input('variant_id'))) {
				$variantId = $request->input('variant_id')[$itemIndex] ?? null;
				if ($variantId) {
					$variant = \App\Models\ProductVariant::with('variantAttributes')->find($variantId);
					if ($variant && $variant->product_id == $product->id) {
						foreach ($variant->variantAttributes as $variantAttribute) {
							$attributes[$variantAttribute->attribute_name] = $variantAttribute->attribute_value;
						}
					}
				}
			} elseif ($request->has('variant_attributes') && is_array($request->input('variant_attributes'))) {
				$variantAttributesInput = $request->input('variant_attributes');
				if (isset($variantAttributesInput[$itemIndex]) && is_array($variantAttributesInput[$itemIndex])) {
					foreach ($variantAttributesInput[$itemIndex] as $attributeName => $attributeValue) {
						if ($attributeValue) {
							$attributes[$attributeName] = $attributeValue;
						}
					}
				}
			}
		}
		
		return $attributes;
	}

	private function _generatePaymentToken($order)
	{
		try {
			$this->initPaymentGateway();

			// Validate required fields
			if (empty($order->customer_first_name) || empty($order->customer_email) || empty($order->code) || empty($order->grand_total)) {
				return [
					'success' => false,
					'message' => 'Missing required order data (customer name, email, code, or total)',
				];
			}

			// Validate email format
			if (!filter_var($order->customer_email, FILTER_VALIDATE_EMAIL)) {
				return [
					'success' => false,
					'message' => 'Invalid customer email format',
				];
			}

			// Validate amount (must be positive integer)
			$grossAmount = (int) $order->grand_total;
			if ($grossAmount <= 0) {
				return [
					'success' => false,
					'message' => 'Invalid order amount',
				];
			}

			$customerDetails = [
				'first_name' => trim($order->customer_first_name),
				'last_name' => trim($order->customer_last_name ?? ''),
				'email' => trim($order->customer_email),
				'phone' => trim($order->customer_phone ?? ''),
			];

			$items = [
				[
					'id' => 'ORDER-' . $order->code,
					'price' => $grossAmount,
					'quantity' => 1,
					'name' => 'Order #' . $order->code,
					'category' => 'Order'
				]
			];

			$params = [
				'transaction_details' => [
					'order_id' => $order->code,
					'gross_amount' => $grossAmount,
				],
				'item_details' => $items,
				'customer_details' => $customerDetails,
			];

			Log::info('Midtrans Payment Token Request', [
				'order_id' => $order->id,
				'order_code' => $order->code,
				'params' => $params,
				'config' => [
					'serverKey' => MidtransConfig::$serverKey ? 'Set' : 'Not set',
					'isProduction' => MidtransConfig::$isProduction,
				]
			]);

			$snap = Snap::createTransaction($params);

			Log::info('Midtrans Payment Token Response', [
				'order_id' => $order->id,
				'token_exists' => isset($snap->token),
				'redirect_url_exists' => isset($snap->redirect_url)
			]);

			if (isset($snap->token) && $snap->token) {
				$order->payment_token = $snap->token;
				$order->payment_url = $snap->redirect_url ?? null;
				$order->save();

				return [
					'success' => true,
					'token' => $snap->token,
					'redirect_url' => $snap->redirect_url ?? null,
				];
			}

			return [
				'success' => false,
				'message' => 'Failed to generate payment token - No token received from Midtrans',
			];
		} catch (\Exception $e) {
			Log::error('Midtrans Payment Token Error', [
				'order_id' => $order->id ?? null,
				'message' => $e->getMessage(),
				'code' => $e->getCode(),
				'file' => $e->getFile(),
				'line' => $e->getLine()
			]);
			
			// Check if it's a specific Midtrans error
			$errorMessage = $e->getMessage();
			if (strpos($errorMessage, 'Undefined array key') !== false) {
				$errorMessage = 'Payment gateway configuration error. Please contact administrator.';
			} elseif (strpos($errorMessage, 'CURL') !== false) {
				$errorMessage = 'Network connection error. Please try again.';
			} elseif (strpos($errorMessage, 'ServerKey') !== false || strpos($errorMessage, 'ClientKey') !== false) {
				$errorMessage = 'Payment gateway authentication error. Please contact administrator.';
			}
			
			return [
				'success' => false,
				'message' => $errorMessage,
			];
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

		return redirect('admin/orders');
	}

	public function complete(Order $order)
	{
		// Check if order can be completed
		if ($order->isCancelled()) {
			Alert::error('Error', 'Cannot complete a cancelled order.');
			return redirect()->route('admin.orders.show', $order->id);
		}

		if ($order->isCompleted()) {
			Alert::info('Info', 'Order is already completed.');
			return redirect()->route('admin.orders.show', $order->id);
		}

		if (!$order->isPaid()) {
			Alert::error('Error', 'Order cannot be completed because it has not been paid yet.');
			return redirect()->route('admin.orders.show', $order->id);
		}

		if ($order->use_employee_tracking && empty($order->handled_by)) {
			Alert::error('Error', 'Employee name must be filled before completing the order.');
			return redirect()->route('admin.orders.show', $order->id);
		}

		// Automatically complete the order since it meets all requirements
		return $this->doComplete(request(), $order);
	}

    public function doComplete(Request $request,Order $order)
	{
		if ($order->use_employee_tracking && empty($order->handled_by)) {
			Alert::error('Error', 'Employee name must be filled before completing the order.');
			return redirect()->back();
		}

		// For offline store orders (toko) - can be completed directly after payment
		if ($order->isOfflineStoreOrder() && $order->isPaid()) {
			$order->status = Order::COMPLETED;
			$order->approved_by = auth()->id();
			$order->approved_at = now();
			$order->notes = $order->notes . "\nOrder completed for offline store purchase";

			// Record stock movements for order completion
			$this->recordOrderStockMovements($order, 'Admin Offline Sale');

			$this->saveEmployeePerformance($order);

			if ($order->save()) {
				Alert::success('Success', 'Offline store order has been completed successfully!');
				return redirect()->back();
			}
		}
		// For COD orders - complete after delivery confirmation
		elseif ($order->payment_method == 'cod' && $order->isPaid()) {
			$order->status = Order::COMPLETED;
			$order->approved_by = auth()->id();
			$order->approved_at = now();
			$order->notes = $order->notes . "\nCOD order completed after payment confirmation";

			// Record stock movements for order completion
			$this->recordOrderStockMovements($order, 'Admin COD Sale');

			$this->saveEmployeePerformance($order);

			if ($order->save()) {
				Alert::success('Success', 'COD order has been completed successfully!');
				return redirect()->back();
			}
		}
		// General completion for paid orders
		elseif($order->isPaid()) {
			$order->status = Order::COMPLETED;
			$order->approved_by = auth()->id();
			$order->approved_at = now();

			// Record stock movements for order completion
			$this->recordOrderStockMovements($order, 'Admin Sale');

			$this->saveEmployeePerformance($order);

			if ($order->save()) {
				Alert::success('Success', 'Order has been completed successfully!');
				return redirect()->back();
			}
		}
		else {
			Alert::error('Error', 'Order cannot be completed because it has not been paid yet.');
			return redirect()->back();
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

	public function confirmPickup(Request $request, Order $order)
	{
		if ($order->use_employee_tracking && empty($order->handled_by)) {
			Alert::error('Error', 'Employee name must be filled before confirming pickup.');
			return redirect()->back();
		}

		if ($order->shipping_service_name == 'Self Pickup' && $order->isPaid()) {
			if ($order->shipment) {
				$order->shipment->status = Shipment::SHIPPED;
				$order->shipment->shipped_by = auth()->id();
				$order->shipment->shipped_at = now();
				$order->shipment->save();
			}
			
			$order->status = Order::COMPLETED;
			$order->approved_by = auth()->id();
			$order->approved_at = now();
			$order->notes = $order->notes . "\nSelf pickup confirmed by admin - customer has collected items from store";

			// Record stock movements for pickup completion
			$this->recordOrderStockMovements($order, 'Admin Self Pickup Sale');

			$this->saveEmployeePerformance($order);

			if ($order->save()) {
				Alert::success('Success', 'Self pickup confirmed! Order marked as completed.');
				return redirect()->back();
			}
		}

		Alert::error('Error', 'Cannot confirm pickup for this order.');
		return redirect()->back();
	}

	public function updateEmployeeTracking(Request $request, Order $order)
	{
		$request->validate([
			'handled_by' => 'nullable|string|max:255',
		]);

		$handledBy = $request->handled_by;
		$useTracking = !empty($handledBy);

		$order->update([
			'handled_by' => $handledBy,
			'use_employee_tracking' => $useTracking
		]);

		if ($order->status === 'completed' && $useTracking && $handledBy) {
			$this->saveEmployeePerformance($order);
		}

		return response()->json([
			'success' => true,
			'message' => 'Employee name updated successfully',
			'use_employee_tracking' => $useTracking
		]);
	}

	public function toggleEmployeeTracking(Request $request, Order $order)
	{
		$request->validate([
			'use_employee_tracking' => 'required|boolean',
		]);

		$useTracking = $request->use_employee_tracking;

		$order->update([
			'use_employee_tracking' => $useTracking,
			'handled_by' => $useTracking ? $order->handled_by : null
		]);

		return response()->json([
			'success' => true,
			'message' => 'Employee tracking status updated successfully'
		]);
	}

	private function saveEmployeePerformance(Order $order)
	{
		if ($order->use_employee_tracking && !empty($order->handled_by)) {
			EmployeePerformance::updateOrCreate(
				['order_id' => $order->id],
				[
					'employee_name' => $order->handled_by,
					'transaction_value' => $order->grand_total,
					'completed_at' => now()
				]
			);
		}
	}

	public function adjustShipping(Request $request)
	{
		try {
			$request->validate([
				'order_id' => 'required|integer|exists:orders,id',
				'new_shipping_cost' => 'required|numeric|min:0',
				'new_shipping_courier' => 'required|string|max:255',
				'new_shipping_service' => 'required|string|max:255',
				'adjustment_note' => 'nullable|string|max:1000'
			]);

			$order = Order::findOrFail($request->order_id);

			if ($order->isCancelled()) {
				return response()->json([
					'success' => false,
					'message' => 'Cannot adjust shipping for cancelled orders'
				], 400);
			}

			$success = $order->adjustShippingCost(
				$request->new_shipping_cost,
				$request->new_shipping_courier,
				$request->new_shipping_service,
				$request->adjustment_note,
				auth()->id()
			);

			if ($success) {
				return response()->json([
					'success' => true,
					'message' => 'Shipping cost updated successfully',
					'new_shipping_cost' => $order->shipping_cost,
					'new_courier' => $order->shipping_courier,
					'new_service' => $order->shipping_service_name,
					'new_grand_total' => $order->grand_total,
					'adjustment_note' => $order->shipping_adjustment_note,
					'adjusted_at' => $order->shipping_adjusted_at?->format('Y-m-d H:i:s'),
					'adjusted_by' => $order->shippingAdjustedBy?->name
				]);
			} else {
				return response()->json([
					'success' => false,
					'message' => 'Failed to update shipping cost'
				], 500);
			}

		} catch (\Illuminate\Validation\ValidationException $e) {
			return response()->json([
				'success' => false,
				'message' => 'Validation failed',
				'errors' => $e->errors()
			], 422);
		} catch (\Exception $e) {
			Log::error('Shipping adjustment failed: ' . $e->getMessage(), [
				'order_id' => $request->order_id,
				'new_cost' => $request->new_shipping_cost,
				'trace' => $e->getTraceAsString()
			]);

			return response()->json([
				'success' => false,
				'message' => 'An error occurred while updating shipping cost'
			], 500);
		}
	}

	/**
	 * Record stock movements for order items when order is completed
	 */
	private function recordOrderStockMovements($order, $description = 'Admin Sale')
	{
		// Check if stock movements have already been recorded for this order
		$existingMovements = \App\Models\StockMovement::where('reference_type', 'order')
			->where('reference_id', $order->id)
			->exists();
		
		if ($existingMovements) {
			// Stock movements already recorded, skip to avoid double deduction
			\Illuminate\Support\Facades\Log::info("Stock movements already recorded for order {$order->id}, skipping duplicate recording");
			return;
		}

		foreach ($order->orderItems as $orderItem) {
			try {
				// Check if item has variant
				if ($orderItem->product_variant_id) {
					// For variant products, use StockService directly (it handles both stock update and movement recording)
					app(StockService::class)->recordMovement(
						$orderItem->product_variant_id, // variantId
						'out',                           // movementType
						$orderItem->qty,                 // quantity
						'order',                         // referenceType
						$order->id,                      // referenceId
						$description,                    // reason
						"Order #{$order->code}"          // notes
					);
				} else {
					// For simple products without variants, use the simple product method
					app(StockService::class)->recordSimpleProductMovement(
						$orderItem->product_id,          // productId
						'out',                           // movementType
						$orderItem->qty,                 // quantity
						'order',                         // referenceType
						$order->id,                      // referenceId
						$description,                    // reason
						"Order #{$order->code}"          // notes
					);
				}
			} catch (\Exception $e) {
				// Log the error but don't stop the completion process
				\Illuminate\Support\Facades\Log::warning("Failed to record stock movement for order item {$orderItem->id}: " . $e->getMessage());
			}
		}
	}
}
