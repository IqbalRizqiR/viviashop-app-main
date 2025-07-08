<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Shipment;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\ProductInventory;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{

	// Add this property at the top of your class
    protected $midtransServerKey;
    protected $midtransClientKey;
    protected $isProduction;
    protected $isSanitized;
    protected $is3ds;

    public function __construct()
    {
        // Initialize Midtrans configuration from your config file
        $this->midtransServerKey = config('midtrans.serverKey');
        $this->midtransClientKey = config('midtrans.clientKey');
        $this->isProduction = config('midtrans.isProduction');
        $this->isSanitized = config('midtrans.isSanitized');
        $this->is3ds = config('midtrans.is3ds');

        // Log::info('Midtrans Configuration', [
        //     'server_key' => !empty($this->midtransServerKey) ? 'Set (hidden)' : 'Not set',
        //     'client_key' => $this->midtransClientKey,
        //     'is_production' => $this->isProduction
        // ]);
    }

    // public function debug()
    // {
    //     return [
    //         'config' => [
    //             'serverKey' => $this->midtransServerKey,
    //             'clientKey' => $this->midtransClientKey,
    //             'isProduction' => $this->isProduction,
    //             'isSanitized' => $this->isSanitized,
    //             'is3ds' => $this->is3ds,
    //         ],
    //     ];
    // }

	private function initPaymentGateway()
    {
        // Set your Midtrans server key from config
        \Midtrans\Config::$serverKey = $this->midtransServerKey;
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = $this->isProduction;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = $this->isSanitized;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = $this->is3ds;
    }
	public function index()
	{
		$orders = Order::forUser(auth()->user())
			->orderBy('created_at', 'DESC')
			->with(['shipment'])
			->get();
		// dd($orders[0]->shipment);
		$cart = Cart::content()->count();
        $setting = Setting::first();
        view()->share('setting', $setting);
		view()->share('countCart', $cart);

		return view('frontend.orders.index', compact('orders'));
	}

	public function show($id)
	{
		$order = Order::forUser(auth()->user())->findOrFail($id);
		$cart = Cart::content()->count();
        $setting = Setting::first();
view()->share('setting', $setting);
		view()->share('countCart', $cart);
		return view('frontend.orders.show',compact('order'));
	}

	private function _getTotalWeight()
	{
		if (Cart::count() <= 0) {
			return 0;
		}

		$totalWeight = 0;

		$items = Cart::content();

		foreach ($items as $item) {
			$totalWeight += ($item->qty * ($item->model->weight));
		}

		return $totalWeight;
	}

	public function cities(Request $request)
	{
		$cities = $this->getCities($request->query('province_id'));
		return response()->json(['cities' => $cities]);
	}

	public function shippingCost(Request $request)
	{
		$destination = $request->input('city_id');

		return $this->_getShippingCost($destination, $this->_getTotalWeight());
	}

	private function _getShippingCost($destination, $weight)
    {
        $results = [];

        $resultsed = [];
        // $includeSelf = true;

        // // Optionally add SELF pickup if your system supports it
        // if ($includeSelf == true) {
        //     $results[] = [
        //         'service' => 'SELF',
        //         'cost' => 0,
        //         'etd' => 'same day',
        //         'courier' => 'SELF',
        //     ];
        // }

        // Always get courier options from RajaOngkir API:
        if (!empty($this->couriers)) {
            try {

                // Use a valid origin (default '290' if not set)
                $origin = $this->rajaOngkirOrigin ?: '290';
                $params = [
                    'origin' => $origin,
                    'destination' => $destination,
                    'weight' => $weight,
                ];

                // Log the parameters we're using
                // echo(Log::info('Shipping cost calculation parameters', $params));

                foreach ($this->couriers as $code => $courier) {
                    $courierParams = $params;
                    $courierParams['courier'] = $code;

                    try {
                        $response = $this->rajaOngkirRequest('/cost', $courierParams, 'POST');
                        // dd($response['rajaongkir']['results']);

                        if (!empty($response['rajaongkir']['results'])) {
                            foreach ($response['rajaongkir']['results'] as $cost) {
                                if (!empty($cost['costs'])) {
                                    foreach ($cost['costs'] as $costDetail) {
                                        $serviceName = strtoupper($cost['code']) . ' - ' . $costDetail['service'];
                                        $costAmount = $costDetail['cost'][0]['value'];
                                        $etd = $costDetail['cost'][0]['etd'];

                                        $results[] = [
                                            'service' => $serviceName,
                                            'cost' => $costAmount,
                                            'etd' => $etd,
                                            'courier' => $code,
                                        ];
                                    }
                                }
                            }
                            $resultsed[] = $response;
                        }
                    } catch (\Exception $e) {
                        echo $e->getMessage();
                        Log::error('Courier request failed for ' . $code . ': ' . $e->getMessage());
                    }
                }
            } catch (\Exception $e) {
                Log::error('Shipping cost calculation failed: ' . $e->getMessage());
            }
        }

        // Set origin for response (using provided or default)
        $origin = $params['origin'] ?? $this->rajaOngkirOrigin ?? '290';

        $self = [
            'service' => 'SELF',
            'cost' => 0,
            'etd' => "Same Day",
            'courier' => 'SELF'
        ];

        // $resulted = array_merge($results, $self);
        array_push($results, $self);
        $response = [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'results' => $results,
        ];

        // dd($resultsed);


        // Log::info('Available shipping options', [
        //     'count' => count($results),
        //     'options' => $results,
        // ]);

        return $response;
    }

	public function confirmPayment(Request $request, $id)
	{
		$order = Order::where('id', $id)->first();

		$order->update([
			'payment_slip' => $request->file('file_bukti')->store('assets/bukti_pembayaran', 'public'),
			'payment_status' => Order::WAITING,
		]);

		return redirect()->route('showUsersOrder', $id);
	}

	public function setShipping(Request $request)
    {
        $shippingService = $request->get('shipping_service');
        $destination = $request->get('city_id');

        // Log the request
        Log::info('Setting shipping option', [
            'service' => $shippingService,
            'destination' => $destination
        ]);

        $shippingOptions = $this->_getShippingCost($destination, $this->_getTotalWeight());

        // Log all available shipping options
        Log::info('Available shipping options count: ' . count($shippingOptions['results']));

        $selectedShipping = null;

        if (count($shippingOptions['results']) == 0) {
            // No shipping options available
            Log::error('No shipping options available for destination: ' . $destination);
        } else if (count($shippingOptions['results']) == 1) {
            // Only one option, select it
            $selectedShipping = $shippingOptions['results'][0];
            Log::info('Selected the only shipping option available', $selectedShipping);
        } else {
            // Multiple options, find the requested one
            foreach ($shippingOptions['results'] as $shippingOption) {
                // Compare with and without spaces to be more flexible
                if (str_replace(' ', '', $shippingOption['service']) == str_replace(' ', '', $shippingService)) {
                    $selectedShipping = $shippingOption;
                    Log::info('Found matching shipping option', $selectedShipping);
                    break;
                }
            }

            // If no match found, log the issue
            if (!$selectedShipping) {
                Log::warning('Requested shipping service not found', [
                    'requested' => $shippingService,
                    'available' => array_column($shippingOptions['results'], 'service')
                ]);
            }
        }

        $status = null;
        $message = null;
        $data = [];

        if ($selectedShipping) {
            $status = 200;
            $message = 'Success set shipping cost';
            $data['total'] = (int)Cart::subtotal(0,'','') + $selectedShipping['cost'];
            $data['shipping_cost'] = $selectedShipping['cost'];
            $data['shipping_service'] = $selectedShipping['service'];
        } else {
            $status = 400;
            $message = 'Failed to set shipping cost';
        }

        $response = [
            'status' => $status,
            'message' => $message
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        return $response;
    }

    public function checkout()
    {
		$cart = Cart::content()->count();
        $setting = Setting::first();
		view()->share('setting', $setting);
		view()->share('countCart', $cart);
        if (Cart::count() == 0) {
			return redirect('carts');
		}

		$items = Cart::content();

		$unique_code = random_int('1', '999');
		// dd($unique_code);

		$totalWeight = $this->_getTotalWeight();

		$provinces = $this->getProvinces();

		$cities = isset(auth()->user()->province_id) ? $this->getCities(auth()->user()->province_id) : [];

		return view('frontend.orders.checkout', compact('items', 'unique_code', 'totalWeight','provinces','cities'));
	}

	public function doCheckout(Request $request)
	{
		try {
			// Validate request
			$request->validate([
				'name' => 'required|string|max:255',
				'address1' => 'required|string|max:255',
				'province_id' => 'required|numeric',
				'shipping_city_id' => 'required|numeric',
				'phone' => 'required|string|max:15',
				'email' => 'required|email|max:255',
				'payment_method' => 'required|string|in:manual,automatic,qris,cod,toko',
				'shipping_service' => 'required|string',
			]);

			$params = $request->except('_token');
			$params['attachments'] = $request->file('attachments');

			DB::beginTransaction();

			try {
				// Save order data
				$order = $this->_saveOrder($params);

				// Save order items
				$this->_saveOrderItems($order);

				// Save shipment information
				$this->_saveShipment($order, $params);

				// Process payment based on selected method
				if ($params['payment_method'] == 'automatic') {
					$paymentResponse = $this->_generatePaymentToken($order);

					if (!$paymentResponse['success']) {
						throw new \Exception('Failed to generate payment token: ' . $paymentResponse['message']);
					}

					// Log successful token generation
					// dd('Payment token generated for order', [
					// 	'order_code' => $order->code,
					// 	'payment_url' => $order->payment_url
					// ]);
                    Log::info('Payment token generated for order', [
                        'order_code' => $order->code,
                        'payment_url' => $order->payment_url
                    ]);
				}

				// Commit database transaction
				DB::commit();

				// Clear the cart after successful checkout
				Cart::destroy();

				// Add success message
				Session::flash('success', 'Thank you! Your order has been received!');

				// Redirect to order received page
				return redirect('orders/received/' . $order->id);

			} catch (\Exception $e) {
				// Rollback transaction on error
				DB::rollBack();

				Log::error('Checkout Error: ' . $e->getMessage());

				// Redirect back with error message
				return redirect()->back()->withInput()->with('error', 'There was an error processing your order: ' . $e->getMessage());
			}
		} catch (\Exception $e) {
			Log::error('Checkout Input Validation Error: ' . $e->getMessage());

			return redirect()->back()->withInput()->with('error', 'Please check your input: ' . $e->getMessage());
		}
	}

	private function _getSelectedShipping($destination, $totalWeight, $shippingService)
	{
		$shippingOptions = $this->_getShippingCost($destination, $totalWeight);

		$selectedShipping = null;
		if (count($shippingOptions['results']) <= 1) {
			$selectedShipping = $shippingOptions['results'][0];
		} elseif(count($shippingOptions['results']) > 1) {
			foreach ($shippingOptions['results'] as $shippingOption) {
				if (str_replace(' ', '', $shippingOption['service']) == $shippingService) {
					$selectedShipping = $shippingOption;
					break;
				}
			}
		}

		return $selectedShipping;
	}

	public function downloadFile($id)
	{
		$order = Order::find($id);

		return Storage::download('/' . $order->attachments);
	}

    private function _saveOrder($params)
	{
		$destination = !isset($params['ship_to']) ? $params['shipping_city_id'] : $params['customer_shipping_city_id'];
		$selectedShipping = $this->_getSelectedShipping($destination, $this->_getTotalWeight(), $params['shipping_service']);

		$baseTotalPrice = (int)Cart::subtotal(0,'','');
		$taxAmount = 0;
		$taxPercent = 0;
		$shippingCost = $selectedShipping['cost'];
		// dd($params);
		$discountAmount = 0;
		if ($params['payment_method'] == 'manual') {
			$paymentMethod = 'manual';
		} elseif($params['payment_method'] == 'automatic') {
			$paymentMethod = 'automatic';
		} elseif($params['payment_method'] == 'qris') {
			$paymentMethod = 'qris';
		} else {
			$paymentMethod = 'cod';
		}
		$unique_code = $params['unique_code'];
		$discountPercent = 0;
		$grandTotal = ($baseTotalPrice + $taxAmount + $shippingCost) - $discountAmount + $unique_code;

		$orderDate = date('Y-m-d H:i:s');
		$paymentDue = (new \DateTime($orderDate))->modify('+7 day')->format('Y-m-d H:i:s');

		$user_profile = [
			'name' => $params['name'],
			'address1' => $params['address1'],
			'address2' => $params['address2'],
			'province_id' => $params['province_id'],
			'city_id' => $params['shipping_city_id'],
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
				'payment_status' => Order::UNPAID,
				'attachments' => $params['attachments']->store('assets/slides', 'public'),
				'base_total_price' => $baseTotalPrice,
				'tax_amount' => $taxAmount,
				'tax_percent' => $taxPercent,
				'discount_amount' => $discountAmount,
				'discount_percent' => $discountPercent,
				'shipping_cost' => $shippingCost,
				'grand_total' => $grandTotal,
				'note' => $params['note'],
				'customer_first_name' => $params['name'],
				'customer_last_name' => $params['name'],
				'customer_address1' => $params['address1'],
				'payment_method' => $paymentMethod,
				'customer_address2' => $params['address2'],
				'customer_phone' => $params['phone'],
				'customer_email' => $params['email'],
				'customer_city_id' => $params['shipping_city_id'],
				'customer_province_id' => $params['province_id'],
				'customer_postcode' => $params['postcode'],
				'shipping_courier' => $selectedShipping['courier'],
				'shipping_service_name' => $selectedShipping['service'],
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
				'shipping_cost' => $shippingCost,
				'grand_total' => $grandTotal,
				'note' => $params['note'],
				'customer_first_name' => $params['name'],
				'customer_last_name' => $params['name'],
				'customer_address1' => $params['address1'],
				'payment_method' => $paymentMethod,
				'customer_address2' => $params['address2'],
				'customer_phone' => $params['phone'],
				'customer_email' => $params['email'],
				'customer_city_id' => $params['shipping_city_id'],
				'customer_province_id' => $params['province_id'],
				'customer_postcode' => $params['postcode'],
				'shipping_courier' => $selectedShipping['courier'],
				'shipping_service_name' => $selectedShipping['service'],
			];
		}


		return Order::create($orderParams);
	}

	private function _saveOrderItems($order)
	{
		$cartItems = Cart::content();

		if ($order && $cartItems) {
			foreach ($cartItems as $item) {
				$itemTaxAmount = 0;
				$itemTaxPercent = 0;
				$itemDiscountAmount = 0;
				$itemDiscountPercent = 0;
				$itemBaseTotal = $item->qty * $item->price;
				$itemSubTotal = $itemBaseTotal + $itemTaxAmount - $itemDiscountAmount;

				$product = isset($item->model->parent) ? $item->model->parent : $item->model;

				$orderItemParams = [
					'order_id' => $order->id,
					'product_id' => $item->model->id,
					'qty' => $item->qty,
					'base_price' => $item->price,
					'base_total' => $itemBaseTotal,
					'tax_amount' => $itemTaxAmount,
					'tax_percent' => $itemTaxPercent,
					'discount_amount' => $itemDiscountAmount,
					// 'attachments' => $order->
					'discount_percent' => $itemDiscountPercent,
					'sub_total' => $itemSubTotal,
					'sku' => $item->model->sku,
					'type' => $product->type,
					'name' => $item->name,
					'weight' => $item->model->weight / 1000,
					'attributes' => json_encode($item->options),
				];

				$orderItem = OrderItem::create($orderItemParams);

				if ($orderItem) {
					ProductInventory::reduceStock($orderItem->product_id, $orderItem->qty);
				}
			}
		}
	}

	public function confirmPaymentManual($id) {
		$order = Order::where('id', $id)->first();
		if ($order->payment_status != 'unpaid') {
			return redirect('profile');
		} else {
			$cart = Cart::content()->count();
            $setting = Setting::first();
view()->share('setting', $setting);
			view()->share('countCart', $cart);
			return view('admin.orders.confirmPayment', compact('order'));
		}


	}

	public function confirmPaymentAdmin($id)
	{
		$order = Order::where('id', $id)->first();
		$order->update([
			'payment_status' => Order::PAID,
			'status' => Order::CONFIRMED,
		]);
		$cart = Cart::content()->count();
        $setting = Setting::first();
view()->share('setting', $setting);
		view()->share('countCart', $cart);
		return redirect()->route('admin.orders.show', $id);
	}

	private function _generatePaymentToken($order)
	{
		try {
			$this->initPaymentGateway();

			// Format customer details properly
			$customerDetails = [
				'first_name' => $order->customer_first_name,
				'last_name' => $order->customer_last_name,
				'email' => $order->customer_email,
				'phone' => $order->customer_phone,
				'billing_address' => [
					'first_name' => $order->customer_first_name,
					'last_name' => $order->customer_last_name,
					'email' => $order->customer_email,
					'phone' => $order->customer_phone,
					'address' => $order->customer_address1,
					'city' => $this->getCityName($order->customer_city_id, $order->customer_province_id),
					'postal_code' => $order->customer_postcode,
					'country_code' => 'IDN'
				],
				'shipping_address' => [
					'first_name' => $order->shipment ? $order->shipment->name : $order->customer_first_name,
					'last_name' => $order->shipment ? '' : $order->customer_last_name,
					'email' => $order->shipment ? $order->shipment->email : $order->customer_email,
					'phone' => $order->shipment ? $order->shipment->phone : $order->customer_phone,
					'address' => $order->shipment ? $order->shipment->address1 : $order->customer_address1,
					'city' => $order->shipment ? $this->getCityName($order->shipment->city_id, $order->shipment->province_id) : $this->getCityName($order->customer_city_id, $order->customer_province_id),
					'postal_code' => $order->shipment ? $order->shipment->postcode : $order->customer_postcode,
					'country_code' => 'IDN'
				]
			];

			// Generate item details for better reporting in Midtrans dashboard
			$items = [];
			foreach ($order->orderItems as $item) {
				$items[] = [
					'id' => $item->product_id,
					'price' => $item->base_price,
					'quantity' => $item->qty,
					'name' => Str::limit($item->name, 50),
					'category' => 'Product'
				];
			}

			// Add shipping as an item
			if ($order->shipping_cost > 0) {
				$items[] = [
					'id' => 'SHIPPING-' . $order->shipping_courier,
					'price' => $order->shipping_cost,
					'quantity' => 1,
					'name' => 'Shipping Cost - ' . $order->shipping_service_name,
					'category' => 'Shipping'
				];
			}

			// Add unique code as an item if used
				$items[] = [
					'id' => 'UNIQUE-CODE',
					'price' => $order->grand_total - $order->base_total_price - $order->shipping_cost,
					'quantity' => 1,
					'name' => 'Unique Payment Code',
					'category' => 'Fee'
				];

			// Define the transaction parameters
			$params = [
				'transaction_details' => [
					'order_id' => $order->code,
					'gross_amount' => (int) $order->grand_total,
				],
				'item_details' => $items,
				'customer_details' => $customerDetails,
				'enabled_payments' => Payment::PAYMENT_CHANNELS,
				'expiry' => [
					'start_time' => date('Y-m-d H:i:s T'),
					'unit' => Payment::EXPIRY_UNIT,
					'duration' => Payment::EXPIRY_DURATION,
				],
			];

			Log::info('Creating Midtrans transaction', ['order_code' => $order->code, 'params' => $params]);

			// Create the transaction
			$snap = \Midtrans\Snap::createTransaction($params);

			if (isset($snap->token) && $snap->token) {
				$order->payment_token = $snap->token;
				$order->payment_url = $snap->redirect_url;
				$order->save();

				Log::info('Midtrans token generated', [
					'order_code' => $order->code,
					'token' => $snap->token
				]);

				return [
					'success' => true,
					'token' => $snap->token,
					'redirect_url' => $snap->redirect_url,
				];
			} else {
				Log::error('Midtrans response missing token', ['response' => $snap]);

				return [
					'success' => false,
					'message' => 'Payment gateway failed to generate token',
				];
			}
		} catch (\Exception $e) {
			Log::error('Midtrans Token Generation Error: ' . $e->getMessage());
			Log::error($e->getTraceAsString());

			return [
				'success' => false,
				'message' => 'Payment token generation failed: ' . $e->getMessage(),
			];
		}
	}
	private function getCityName($cityId, $provinceId)
	{
		// Implement logic to get city name from city ID
		// You might have a City model or need to use the RajaOngkir API

		// Placeholder implementation - replace with your actual code
		$cities = $this->getCities($provinceId);
		return isset($cities[$cityId]) ? $cities[$cityId] : 'Unknown City';
	}
	private function _restoreStock($order)
	{
		foreach ($order->orderItems as $item) {
			ProductInventory::increaseStock($item->product_id, $item->qty);
		}
	}
	public function notificationHandler(Request $request)
	{
        // Log all raw input for debugging
        Log::info('Midtrans Raw Notification Data:', $request->all());

		try {
            $this->initPaymentGateway();
			// Get notification instance from Midtrans
			$notification = new \Midtrans\Notification();

			// Extract important data
			$transaction = $notification->transaction_status;
			$type = $notification->payment_type;
			$orderId = $notification->order_id;
			$fraud = $notification->fraud_status;
			$amount = $notification->gross_amount;
			$transactionId = $notification->transaction_id;

			// Log the notification
			Log::info('Midtrans Notification Received', [
				'order_id' => $orderId,
				'transaction_status' => $transaction,
				'payment_type' => $type,
				'fraud_status' => $fraud,
				'amount' => $amount,
				'transaction_id' => $transactionId
			]);

			// Find the order
			$order = Order::where('code', $orderId)->first();

			if (!$order) {
				Log::warning("Order not found for notification: {$orderId}");
				return response()->json([
					'success' => false,
					'message' => 'Order not found',
				], 404);
			}

			// Record payment details
			$paymentData = [
				'transaction_id' => $transactionId,
				'amount' => $amount,
				'method' => $type,
				'status' => $transaction,
				'token' => $order->payment_token,
				'payloads' => json_encode($notification),
                'number' => $transactionId,
				'payment_type' => $type,
				'va_number' => $type == 'bank_transfer' ? $notification->va_numbers[0]->va_number : null,
				'va_bank' => $type == 'bank_transfer' ? $notification->va_numbers[0]->bank : null,
				'bill_key' => $type == 'echannel' ? $notification->bill_key : null,
				'biller_code' => $type == 'echannel' ? $notification->biller_code : null,
			];

			// Process different transaction statuses
			switch ($transaction) {
				case 'capture':
					// For credit card transaction
					if ($type == 'credit_card') {
						if ($fraud == 'challenge') {
							// When payment is challenged by FDS
							$order->payment_status = Order::WAITING;
							$order->notes = $order->notes . "\nPayment challenged by Fraud Detection System. Manual verification required.";
							Log::info("Order {$orderId} payment challenged by FDS");
						} else {
							// When payment is successful
							$order->payment_status = Order::PAID;
							$order->status = Order::CONFIRMED;
							$order->approved_at = now();
							$order->notes = $order->notes . "\nPayment completed using credit card";
							Log::info("Order {$orderId} payment successful with credit card");
						}
					}
					break;

				case 'settlement':
					// Payment has been settled (for bank transfers, etc)
					$order->payment_status = Order::PAID;
					$order->status = Order::CONFIRMED;
					$order->approved_at = now();
					$order->notes = $order->notes . "\nPayment settled using {$type}";
					Log::info("Order {$orderId} payment settled with {$type}");
					break;

				case 'pending':
					// Payment is pending (waiting for customer to complete payment)
					$order->payment_status = Order::WAITING;
					$order->notes = $order->notes . "\nPayment pending using {$type}";
					Log::info("Order {$orderId} payment pending with {$type}");
					break;

				case 'deny':
					// Payment was denied
					$order->payment_status = Order::CANCELLED;
					$order->status = Order::CANCELLED;
					$order->notes = $order->notes . "\nPayment denied using {$type}";
					Log::warning("Order {$orderId} payment denied with {$type}");

					// Return items to inventory
					$this->_restoreStock($order);
					break;

				case 'expire':
					// Payment has expired
					$order->payment_status = Order::CANCELLED;
					$order->status = Order::CANCELLED;
					$order->notes = $order->notes . "\nPayment expired for {$type}";
					Log::warning("Order {$orderId} payment expired for {$type}");

					// Return items to inventory
					$this->_restoreStock($order);
					break;

				case 'cancel':
					// Payment was canceled
					$order->payment_status = Order::CANCELLED;
					$order->status = Order::CANCELLED;
					$order->notes = $order->notes . "\nPayment canceled for {$type}";
					Log::warning("Order {$orderId} payment canceled for {$type}");

					// Return items to inventory
					$this->_restoreStock($order);
					break;

				default:
					// Unknown status
					Log::error("Unknown transaction status: {$transaction} for order {$orderId}");
					break;
			}

			// Save payment details to database (assuming you have a Payment model)
			if (class_exists('App\Models\Payment')) {
				Payment::create(array_merge($paymentData, ['order_id' => $order->id]));
			}

			// Save order changes
			$order->save();

			// You might want to send notifications here based on the payment status
			// $this->_sendPaymentStatusNotification($order);

			return response()->json([
				'success' => true,
				'message' => 'Notification processed successfully',
				'order_id' => $orderId,
				'status' => $order->payment_status
			]);
		} catch (\Exception $e) {
			Log::error('Midtrans Notification Error: ' . $e->getMessage());
			Log::error($e->getTraceAsString());
			return response()->json([
				'success' => false,
				'message' => 'Error processing notification: ' . $e->getMessage()
			], 500);
		}
	}

    // Add method to get client key for frontend
    public function getMidtransClientKey()
    {
        return response()->json(['clientKey' => $this->midtransClientKey]);
    }

	private function _saveShipment($order, $params)
	{
		$shippingName = isset($params['ship_to']) ? $params['shipping_name'] : $params['name'];
		$shippingAddress1 = isset($params['ship_to']) ? $params['shipping_address1'] : $params['address1'];
		$shippingAddress2 = isset($params['ship_to']) ? $params['shipping_address2'] : $params['address2'];
		$shippingPhone = isset($params['ship_to']) ? $params['shipping_phone'] : $params['phone'];
		$shippingEmail = isset($params['ship_to']) ? $params['shipping_email'] : $params['email'];
		$shippingCityId = isset($params['ship_to']) ? $params['shipping_city_id'] : $params['shipping_city_id'];
		$shippingProvinceId = isset($params['ship_to']) ? $params['shipping_province_id'] : $params['province_id'];
		$shippingPostcode = isset($params['ship_to']) ? $params['shipping_postcode'] : $params['postcode'];
		$totalQty = 0;
		foreach($order->orderItems as $orderItem) {
			$totalQty += $orderItem->qty;
		}

		$shipmentParams = [
			'user_id' => auth()->id(),
			'order_id' => $order->id,
			'status' => Shipment::PENDING,
			'total_qty' => $totalQty,
			'total_weight' => $this->_getTotalWeight(),
			'name' => $shippingName,
			'address1' => $shippingAddress1,
			'address2' => $shippingAddress2,
			'phone' => $shippingPhone,
			'email' => $shippingEmail,
			'city_id' => $shippingCityId,
			'province_id' => $shippingProvinceId,
			'postcode' => $shippingPostcode,
		];

		Shipment::create($shipmentParams);
	}

	public function received($orderId)
	{
		// Find the order
		$order = Order::with(['orderItems.product', 'shipment'])
			->where('id', $orderId)
			->where('user_id', auth()->id())
			->firstOrFail();

		$cart = Cart::content()->count();
		$setting = Setting::first();
		view()->share('setting', $setting);
		view()->share('countCart', $cart);

		// Pass payment-related data when applicable
		$paymentData = [
			'midtransClientKey' => config('midtrans.clientKey'),
			'isProduction' => config('midtrans.isProduction'),
			'snapUrl' => config('midtrans.isProduction')
				? 'https://app.midtrans.com/snap/snap.js'
				: 'https://app.sandbox.midtrans.com/snap/snap.js'
		];

		return view('frontend.orders.received', compact('order', 'paymentData'));
	}
	public function finishRedirect(Request $request)
	{
		$orderId = $request->get('order_id');
		$order = Order::where('code', $orderId)->firstOrFail();

		return redirect('orders/received/'. $order->id)->with('success', 'Thank you for your payment!');
	}

	public function unfinishRedirect(Request $request)
	{
		$orderId = $request->get('order_id');
		$order = Order::where('code', $orderId)->firstOrFail();

		return redirect('orders/received/'. $order->id)->with('warning', 'Please complete your payment!');
	}

	public function errorRedirect(Request $request)
	{
		$orderId = $request->get('order_id');
		$order = Order::where('code', $orderId)->firstOrFail();

		return redirect('orders/received/'. $order->id)->with('error', 'There was a problem with your payment!');
	}

}
