<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Shipment;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\ProductInventory;
use App\Services\StockService;
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
        
        // Disable SSL verification for localhost development (even in production mode)
        $isLocalhost = in_array(request()->getHost(), ['localhost', '127.0.0.1', '::1']) || 
                       str_contains(request()->getHost(), '.local') ||
                       str_contains(request()->getHost(), 'laragon');
        
        if ($isLocalhost) {
            \Midtrans\Config::$curlOptions = [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTPHEADER => []
            ];
        }
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
			$itemWeight = 0;
			
			if (isset($item->options['type']) && $item->options['type'] === 'configurable') {
				$itemWeight = $item->weight ?? 0;
			} else {
				// For simple products
				if ($item->model) {
					$itemWeight = $item->model->weight ?? 0;
				} else {
					// Fallback: use weight from cart item or load from product
					$itemWeight = $item->weight ?? 0;
					if ($itemWeight <= 0 && isset($item->options['product_id'])) {
						$product = \App\Models\Product::find($item->options['product_id']);
						$itemWeight = $product ? ($product->weight ?? 100) : 100; // Default 100g if no weight
					}
				}
			}
			
			// Ensure minimum weight
			if ($itemWeight <= 0) {
				$itemWeight = 100; // Default 100 grams
			}
			
			$totalWeight += ($item->qty * $itemWeight);
		}

		return $totalWeight;
	}

	public function provinces()
	{
		try {
			require_once base_path('rajaongkir_komerce.php');
			$rajaOngkir = new \RajaOngkirKomerce();
			$provinces = $rajaOngkir->getProvinces();
			
			// Return the provinces array directly
			return response()->json($provinces ?: []);
		} catch (\Exception $e) {
			Log::error('Error fetching provinces: ' . $e->getMessage());
			return response()->json([], 500);
		}
	}

	public function cities($provinceId)
	{
		try {
			require_once base_path('rajaongkir_komerce.php');
			$rajaOngkir = new \RajaOngkirKomerce();
			$cities = $rajaOngkir->getCities($provinceId);
			
			// Convert key-value pairs to array of objects for JavaScript
			$cityArray = [];
			foreach($cities as $id => $name) {
				$cityArray[] = [
					'id' => $id,
					'name' => $name
				];
			}
			
			return response()->json($cityArray);
		} catch (\Exception $e) {
			Log::error('Error fetching cities: ' . $e->getMessage());
			return response()->json([], 500);
		}
	}

	public function districts($cityId)
	{
		try {
			require_once base_path('rajaongkir_komerce.php');
			$rajaOngkir = new \RajaOngkirKomerce();
			$districts = $rajaOngkir->getDistricts($cityId);
			
			// Convert key-value pairs to array of objects for JavaScript
			$districtArray = [];
			foreach($districts as $id => $name) {
				$districtArray[] = [
					'id' => $id,
					'name' => $name
				];
			}
			
			return response()->json($districtArray);
		} catch (\Exception $e) {
			Log::error('Error fetching districts: ' . $e->getMessage());
			return response()->json([], 500);
		}
	}

	public function shippingCost(Request $request)
	{
		$destination = $request->get('district_id');
		$weight = $this->_getTotalWeight();
		
		Log::info('Shipping cost request', [
			'destination' => $destination,
			'weight' => $weight,
			'user_id' => auth()->id()
		]);

		if ($weight <= 0) {
			$weight = 1000;
		}

		try {
			$result = $this->_getShippingCost($destination, $weight);
			Log::info('Shipping cost result', ['result' => $result]);
			return response()->json($result);
		} catch (\Exception $e) {
			Log::error('Shipping cost error: ' . $e->getMessage(), [
				'destination' => $destination,
				'weight' => $weight
			]);
			return response()->json([
				'error' => 'Failed to get shipping costs: ' . $e->getMessage(),
				'results' => []
			]);
		}
	}	private function _getShippingCost($destination, $weight)
    {
        $results = [];

        try {
            require_once base_path('rajaongkir_komerce.php');
            $rajaOngkir = new \RajaOngkirKomerce();
            
            // Jombang District ID (origin)
            $origin = 3852;
            
            // Define supported couriers
            $couriers = ['jne', 'tiki', 'pos'];
            
            foreach ($couriers as $courier) {
                try {
                    $shippingOptions = $rajaOngkir->calculateShippingCost($origin, $destination, $weight, $courier);
                    
                    if (is_array($shippingOptions) && !empty($shippingOptions)) {
                        foreach ($shippingOptions as $option) {
                            if (is_array($option) && isset($option['service'], $option['cost'])) {
                                $results[] = [
                                    'service' => strtoupper($courier) . ' - ' . $option['service'],
                                    'cost' => $option['cost'],
                                    'etd' => $option['etd'] ?? '',
                                    'courier' => $courier,
                                ];
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Courier request failed for ' . $courier . ': ' . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            Log::error('RajaOngkir shipping cost calculation failed: ' . $e->getMessage());
        }

        $response = [
            'origin' => 3852, // Jombang District ID
            'destination' => $destination,
            'weight' => $weight,
            'results' => $results,
        ];

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
        $resultsCount = is_array($shippingOptions['results']) ? count($shippingOptions['results']) : 0;
        Log::info('Available shipping options count: ' . $resultsCount);

        $selectedShipping = null;

        if ($resultsCount == 0) {
            // No shipping options available
            Log::error('No shipping options available for destination: ' . $destination);
        } else if ($resultsCount == 1) {
            // Only one option, select it
            $selectedShipping = is_array($shippingOptions['results']) && isset($shippingOptions['results'][0]) ? $shippingOptions['results'][0] : null;
            Log::info('Selected the only shipping option available', $selectedShipping);
        } else {
            // Multiple options, find the requested one
            if (is_array($shippingOptions['results'])) {
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

		$unique_code = 0;

		$totalWeight = $this->_getTotalWeight();

		$provinces = [];
		$cities = [];
		$districts = [];

		return view('frontend.orders.checkout', compact('items', 'unique_code', 'totalWeight','provinces','cities','districts'));
	}

	public function doCheckout(Request $request)
	{
		try {
			Log::info('Checkout request received', $request->all());
			
			$validationRules = [
				'name' => 'required|string|max:255',
				'address1' => 'required|string|max:255',
				'address2' => 'nullable|string|max:255',
				'postcode' => 'required|string|max:20',
				'phone' => 'required|string|max:15',
				'email' => 'required|email|max:255',
				'payment_method' => 'required|string|in:manual,automatic,cod,toko',
				'delivery_method' => 'required|string|in:self,courier',
			];

			if ($request->delivery_method === 'courier') {
				$validationRules['province_id'] = 'required|numeric';
				$validationRules['shipping_city_id'] = 'required|numeric';
				$validationRules['shipping_district_id'] = 'required|numeric';
				$validationRules['shipping_service'] = 'required';
			}

			$request->validate($validationRules);

			Log::info('Checkout validation passed');

			// Add detailed cart validation
			if (Cart::count() <= 0) {
				Log::error('Checkout failed: Cart is empty');
				return redirect('carts')->with('error', 'Your cart is empty');
			}

			$cartItems = Cart::content();
			Log::info('Cart items for checkout', [
				'count' => $cartItems->count(),
				'items' => $cartItems->map(function($item) {
					return [
						'id' => $item->id,
						'name' => $item->name,
						'qty' => $item->qty,
						'price' => $item->price,
						'type' => $item->options['type'] ?? 'unknown',
						'model_exists' => $item->model ? true : false
					];
				})->toArray()
			]);

			$params = $request->except('_token');
			$params['attachments'] = $request->file('attachments');
			$params['payment_slip'] = $request->file('payment_slip');

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

				Log::info('Checkout successful, redirecting to order received page', [
					'order_id' => $order->id,
					'order_code' => $order->code,
					'redirect_url' => 'orders/received/' . $order->id
				]);

				// Redirect to order received page
				return redirect('orders/received/' . $order->id);

			} catch (\Exception $e) {
				// Rollback transaction on error
				DB::rollBack();

				Log::error('Checkout Error: ' . $e->getMessage(), [
					'file' => $e->getFile(),
					'line' => $e->getLine(),
					'trace' => $e->getTraceAsString()
				]);

				// Redirect back with error message
				return redirect()->back()->withInput()->with('error', 'There was an error processing your order: ' . $e->getMessage());
			}
		} catch (\Exception $e) {
			Log::error('Checkout Input Validation Error: ' . $e->getMessage(), [
				'file' => $e->getFile(),
				'line' => $e->getLine(),
				'request_data' => $request->all()
			]);

			return redirect()->back()->withInput()->withErrors($e->getMessage())->with('error', 'Please check your input: ' . $e->getMessage());
		}
	}

	private function _getSelectedShipping($destination, $totalWeight, $shippingService)
	{
		$shippingOptions = $this->_getShippingCost($destination, $totalWeight);

		$selectedShipping = null;
		
		if (is_string($shippingService) && (strpos($shippingService, '{') === 0)) {
			$shippingData = json_decode($shippingService, true);
			if ($shippingData && isset($shippingData['service'])) {
				return [
					'service' => $shippingData['service'],
					'cost' => $shippingData['cost'],
					'etd' => $shippingData['etd'],
					'courier' => $shippingData['courier']
				];
			}
		}
		
		$resultsCount = is_array($shippingOptions['results']) ? count($shippingOptions['results']) : 0;
		
		if ($resultsCount <= 1) {
			$selectedShipping = ($resultsCount > 0 && isset($shippingOptions['results'][0])) ? $shippingOptions['results'][0] : null;
		} elseif($resultsCount > 1) {
			if (is_array($shippingOptions['results'])) {
				foreach ($shippingOptions['results'] as $shippingOption) {
					if (str_replace(' ', '', $shippingOption['service']) == str_replace(' ', '', $shippingService)) {
						$selectedShipping = $shippingOption;
						break;
					}
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
		if ($params['delivery_method'] == 'self') {
			$destination = auth()->user()->city_id ?? 1;
			$selectedShipping = [
				'service' => 'Self Pickup',
				'cost' => 0,
				'etd' => 'Same Day',
				'courier' => 'SELF'
			];
		} else {
			$destination = !isset($params['ship_to']) ? ($params['shipping_city_id'] ?? auth()->user()->city_id) : $params['customer_shipping_city_id'];
			$shippingDestination = isset($params['shipping_district_id']) ? $params['shipping_district_id'] : $destination;
			$shippingService = $params['shipping_service'] ?? '';
			$selectedShipping = $this->_getSelectedShipping($shippingDestination, $this->_getTotalWeight(), $shippingService);
			
			if (!$selectedShipping) {
				$selectedShipping = [
					'service' => 'Standard Delivery',
					'cost' => 0,
					'etd' => '1-2 days',
					'courier' => 'COURIER'
				];
			}
		}

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
		} elseif($params['payment_method'] == 'cod') {
			$paymentMethod = 'cod';
		} elseif($params['payment_method'] == 'toko') {
			$paymentMethod = 'toko';
		} else {
			$paymentMethod = 'manual';
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
			'province_id' => $params['delivery_method'] == 'courier' ? ($params['province_id'] ?? auth()->user()->province_id) : auth()->user()->province_id,
			'city_id' => $params['delivery_method'] == 'courier' ? ($params['shipping_city_id'] ?? auth()->user()->city_id) : auth()->user()->city_id,
			'postcode' => $params['postcode'],
			'phone' => $params['phone'],
		];

		if ($params['email'] !== auth()->user()->email) {
			$existingUser = DB::table('users')->where('email', $params['email'])->where('id', '!=', auth()->id())->first();
			if (!$existingUser) {
				$user_profile['email'] = $params['email'];
			}
		}

		DB::table('users')
			->where('id', auth()->id())
			->update($user_profile);

		if (isset($params['attachments']) && $params['attachments'] != null || isset($params['payment_slip']) && $params['payment_slip'] != null) {
			$orderParams = [
				'user_id' => auth()->id(),
				'code' => Order::generateCode(),
				'status' => Order::CREATED,
				'order_date' => $orderDate,
				'payment_due' => $paymentDue,
				'payment_status' => Order::UNPAID,
				'attachments' => isset($params['attachments']) && $params['attachments'] ? $params['attachments']->store('assets/slides', 'public') : null,
				'payment_slip' => isset($params['payment_slip']) ? $params['payment_slip']->store('assets/payment_slips', 'public') : null,
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
				'customer_city_id' => $params['delivery_method'] == 'courier' ? $params['shipping_city_id'] : (auth()->user()->city_id ?? 1),
				'customer_province_id' => $params['delivery_method'] == 'courier' ? $params['province_id'] : (auth()->user()->province_id ?? 1),
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
				'payment_slip' => isset($params['payment_slip']) ? $params['payment_slip']->store('assets/payment_slips', 'public') : null,
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
				'customer_city_id' => $params['delivery_method'] == 'courier' ? $params['shipping_city_id'] : (auth()->user()->city_id ?? 1),
				'customer_province_id' => $params['delivery_method'] == 'courier' ? $params['province_id'] : (auth()->user()->province_id ?? 1),
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

				// Handle both simple and variant items
				if (isset($item->options['type']) && $item->options['type'] === 'configurable') {
					// Variant item
					$product = \App\Models\Product::find($item->options['product_id']);
					$productId = $item->options['variant_id']; // Store variant ID as product_id for order item
					$sku = $item->options['sku'] ?? 'VAR-' . $item->options['variant_id'];
					$weight = $item->weight ?? 0;
				} else {
					// Simple item
					if ($item->model) {
						$product = isset($item->model->parent) ? $item->model->parent : $item->model;
						$productId = $item->model->id;
						$sku = $item->model->sku ?? '';
						$weight = $item->model->weight ?? 0;
					} else {
						// Fallback for when model is null (load from options)
						$product = \App\Models\Product::find($item->options['product_id'] ?? $item->id);
						$productId = $item->options['product_id'] ?? $item->id;
						$sku = $product ? $product->sku : '';
						$weight = $product ? $product->weight : 0;
					}
				}

				$orderItemParams = [
					'order_id' => $order->id,
					'product_id' => $productId,
					'qty' => $item->qty,
					'base_price' => $item->price,
					'base_total' => $itemBaseTotal,
					'tax_amount' => $itemTaxAmount,
					'tax_percent' => $itemTaxPercent,
					'discount_amount' => $itemDiscountAmount,
					'discount_percent' => $itemDiscountPercent,
					'sub_total' => $itemSubTotal,
					'sku' => $sku,
					'type' => $product ? $product->type : 'simple',
					'name' => $item->name,
					'weight' => $weight / 1000,
					'attributes' => json_encode($item->options),
				];

				$orderItem = OrderItem::create($orderItemParams);

				if ($orderItem) {
					// Handle stock reduction for different item types using StockService
					if (isset($item->options['type']) && $item->options['type'] === 'configurable') {
						// For variant items, record stock movement
						$variant = \App\Models\ProductVariant::find($item->options['variant_id']);
						if ($variant) {
							app(StockService::class)->recordMovement(
								$variant->id, // variant_id
								\App\Models\StockMovement::MOVEMENT_OUT, // movement_type
								$orderItem->qty, // quantity
								'Frontend Sale', // reference_type
								$order->id, // reference_id
								"Order #{$order->code}" // reason
							);
						}
					} else {
						// For simple items, use recordSimpleProductMovement
						app(StockService::class)->recordSimpleProductMovement(
							$orderItem->product_id, // product_id
							\App\Models\StockMovement::MOVEMENT_OUT, // movement_type
							$orderItem->qty, // quantity
							'Frontend Sale', // reference_type
							$order->id, // reference_id
							"Order #{$order->code}" // reason
						);
					}
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
					'city' => 'Jakarta',
					'postal_code' => $order->customer_postcode,
					'country_code' => 'IDN'
				],
				'shipping_address' => [
					'first_name' => $order->shipment ? $order->shipment->name : $order->customer_first_name,
					'last_name' => $order->shipment ? '' : $order->customer_last_name,
					'email' => $order->shipment ? $order->shipment->email : $order->customer_email,
					'phone' => $order->shipment ? $order->shipment->phone : $order->customer_phone,
					'address' => $order->shipment ? $order->shipment->address1 : $order->customer_address1,
					'city' => 'Jakarta',
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
		try {
			$cities = $this->getCities($provinceId);
			return isset($cities[$cityId]) ? $cities[$cityId] : 'Unknown City';
		} catch (\Exception $e) {
			Log::warning('Failed to get city name: ' . $e->getMessage());
			return 'City ID: ' . $cityId;
		}
	}
	private function _restoreStock($order)
	{
		foreach ($order->orderItems as $item) {
			// Check if item has variant
			if ($item->product_variant_id) {
				app(StockService::class)->recordMovement(
					$item->product_id,
					$item->product_variant_id,
					$item->qty,
					'in',
					'Stock Restoration',
					"Cancelled Order #{$order->order_code}"
				);
			} else {
				app(StockService::class)->recordMovement(
					$item->product_id,
					null,
					$item->qty,
					'in',
					'Stock Restoration',
					"Cancelled Order #{$order->order_code}"
				);
			}
		}
	}
	public function notificationHandler(Request $request)
	{
		Log::info('Midtrans Raw Notification Data:', $request->all());

		try {
			$this->initPaymentGateway();
			
			$notification = new \Midtrans\Notification();

			$transaction = $notification->transaction_status;
			$type = $notification->payment_type;
			$orderId = $notification->order_id;
			$fraud = $notification->fraud_status;
			$amount = $notification->gross_amount;
			$transactionId = $notification->transaction_id;

			Log::info('Midtrans Notification Received', [
				'order_id' => $orderId,
				'transaction_status' => $transaction,
				'payment_type' => $type,
				'fraud_status' => $fraud,
				'amount' => $amount,
				'transaction_id' => $transactionId
			]);

			$order = Order::where('code', $orderId)->first();

			if (!$order) {
				Log::warning("Order not found for notification: {$orderId}");
				return response()->json([
					'success' => false,
					'message' => 'Order not found',
				], 404);
			}

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

			switch ($transaction) {
				case 'capture':
					if ($type == 'credit_card') {
						if ($fraud == 'challenge') {
							$order->payment_status = Order::WAITING;
							$order->notes = $order->notes . "\nPayment challenged by Fraud Detection System. Manual verification required.";
							Log::info("Order {$orderId} payment challenged by FDS");
						} else {
							$order->payment_status = Order::PAID;
							if ($order->shipping_service_name == 'Self Pickup') {
								$order->status = Order::CONFIRMED;
								$order->notes = $order->notes . "\nPayment completed using credit card. Waiting for pickup confirmation.";
								Log::info("Order {$orderId} payment successful with credit card - self pickup order awaiting confirmation");
							} else {
								$order->status = Order::COMPLETED;
								$order->approved_at = now();
								$order->notes = $order->notes . "\nPayment completed using credit card";
								Log::info("Order {$orderId} payment successful with credit card");
							}
						}
					}
					break;

				case 'settlement':
					$order->payment_status = Order::PAID;
					if ($order->shipping_service_name == 'Self Pickup') {
						$order->status = Order::CONFIRMED;
						$order->notes = $order->notes . "\nPayment settled using {$type}. Waiting for pickup confirmation.";
						Log::info("Order {$orderId} payment settled with {$type} - self pickup order awaiting confirmation");
					} else {
						$order->status = Order::COMPLETED;
						$order->approved_at = now();
						$order->notes = $order->notes . "\nPayment settled using {$type}";
						Log::info("Order {$orderId} payment settled with {$type}");
					}
					break;

				case 'pending':
					$order->payment_status = Order::WAITING;
					$order->notes = $order->notes . "\nPayment pending using {$type}";
					Log::info("Order {$orderId} payment pending with {$type}");
					break;

				case 'deny':
					$order->payment_status = Order::CANCELLED;
					$order->status = Order::CANCELLED;
					$order->notes = $order->notes . "\nPayment denied using {$type}";
					Log::warning("Order {$orderId} payment denied with {$type}");
					$this->_restoreStock($order);
					break;

				case 'expire':
					$order->payment_status = Order::CANCELLED;
					$order->status = Order::CANCELLED;
					$order->notes = $order->notes . "\nPayment expired for {$type}";
					Log::warning("Order {$orderId} payment expired for {$type}");
					$this->_restoreStock($order);
					break;

				case 'cancel':
					$order->payment_status = Order::CANCELLED;
					$order->status = Order::CANCELLED;
					$order->notes = $order->notes . "\nPayment canceled for {$type}";
					Log::warning("Order {$orderId} payment canceled for {$type}");
					$this->_restoreStock($order);
					break;

				default:
					Log::error("Unknown transaction status: {$transaction} for order {$orderId}");
					break;
			}

			try {
				\App\Models\Payment::create(array_merge($paymentData, ['order_id' => $order->id]));
				Log::info("Payment record created for order {$orderId}");
			} catch (\Exception $e) {
				Log::warning("Failed to create payment record for order {$orderId}: " . $e->getMessage());
			}

			$order->save();

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
		
		if ($params['delivery_method'] == 'courier') {
			$shippingCityId = isset($params['ship_to']) ? $params['shipping_city_id'] : ($params['shipping_city_id'] ?? (auth()->user()->city_id ?? 1));
			$shippingProvinceId = isset($params['ship_to']) ? $params['shipping_province_id'] : ($params['province_id'] ?? (auth()->user()->province_id ?? 1));
		} else {
			$shippingCityId = auth()->user()->city_id ?? 1;
			$shippingProvinceId = auth()->user()->province_id ?? 1;
		}
		
		$shippingPostcode = isset($params['ship_to']) ? $params['shipping_postcode'] : $params['postcode'];
		
		if ($params['delivery_method'] == 'self') {
			$shippingName = 'Ambil di Toko';
			$shippingAddress1 = 'Toko ViVia Shop';
			$shippingAddress2 = '';
		}
		
		$totalQty = 0;
		foreach($order->orderItems as $orderItem) {
			$totalQty += $orderItem->qty;
		}

		$shipmentParams = [
			'user_id' => auth()->id(),
			'order_id' => $order->id,
			'status' => 'pending', // Use string instead of constant for safety
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
		$order = Order::where('code', $orderId)->first();
		
		if ($order) {
			Log::info("Payment finish redirect for order: {$orderId}");
			
			try {
				$this->initPaymentGateway();
				$status = \Midtrans\Transaction::status($orderId);
				
				$transactionStatus = is_object($status) ? $status->transaction_status : $status['transaction_status'] ?? null;
				$paymentType = is_object($status) ? $status->payment_type : $status['payment_type'] ?? null;
				
				Log::info("Midtrans transaction status check", [
					'order_id' => $orderId,
					'status' => $transactionStatus,
					'payment_type' => $paymentType
				]);
				
				if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
					$order->payment_status = Order::PAID;
					if ($order->shipping_service_name == 'Self Pickup') {
						$order->status = Order::CONFIRMED;
						$order->notes = $order->notes . "\nPayment confirmed via finish redirect. Waiting for pickup confirmation.";
						Log::info("Payment confirmed for self pickup order {$orderId} - awaiting pickup confirmation");
					} else {
						$order->status = Order::COMPLETED;
						$order->approved_at = now();
						$order->notes = $order->notes . "\nPayment confirmed via finish redirect";
						Log::info("Payment confirmed and order completed for {$orderId}");
					}
					$order->approved_at = now();
					$order->save();
					
					Log::info("Order payment status updated to paid: {$orderId}");
				}
			} catch (\Exception $e) {
				Log::error("Failed to check transaction status: " . $e->getMessage());
			}
		}

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

	public function invoice($id)
	{
		$order = Order::where('id', $id)->first();
		
		if (!$order) {
			abort(404, 'Order not found');
		}
		
		// Check if user owns this order or is admin
		if (auth()->check()) {
			if (auth()->user()->email !== $order->customer_email && !auth()->user()->is_admin) {
				abort(403, 'Unauthorized to view this invoice');
			}
		} else {
			abort(403, 'Please login to view invoice');
		}
		
		$pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.orders.invoices', compact('order'))
			->setOptions(['defaultFont' => 'sans-serif']);
		$customPaper = array(0, 0, (58 * 2.83), auto);
        $pdf->setPaper($customPaper);
		
		return $pdf->stream('invoice-' . $order->code . '.pdf');
	}

	public function getOrderStatus($id)
	{
		$order = Order::find($id);
		
		if (!$order) {
			return response()->json(['error' => 'Order not found'], 404);
		}
		
		return response()->json([
			'payment_status' => $order->payment_status,
			'status' => $order->status,
			'is_paid' => $order->isPaid()
		]);
	}

	/**
	 * Send shipping cost request to komerce.id RajaOngkir API
	 *
	 * @param string $resource endpoint
	 * @param array  $params   parameters
	 * @param string $method   request method
	 *
	 * @return array
	 */
	private function shippingCostRequest($resource, $params = [], $method = 'GET')
	{
		$baseUrl = config('ongkir.shipping_base_url', 'https://rajaongkir.komerce.id/api/v1');
		$apiKey = config('ongkir.shipping_api_key');
		
		if (empty($baseUrl)) {
			Log::error('Shipping cost API base URL is not set');
			throw new \Exception('Shipping cost API base URL is not configured properly.');
		}

		if (empty($apiKey)) {
			Log::error('Shipping cost API key is not set');
			throw new \Exception('Shipping cost API key is not configured properly.');
		}

		$client = new \GuzzleHttp\Client(['verify' => false]);

		$headers = [
			'key' => $apiKey,
			'Content-Type' => 'application/x-www-form-urlencoded'
		];
		$requestParams = [
			'headers' => $headers,
		];

		if (!str_starts_with($resource, '/')) {
			$resource = '/' . $resource;
		}

		$url = $baseUrl . $resource;

		if ($method == 'POST') {
			$requestParams['form_params'] = $params;
		} else if ($method == 'GET' && !empty($params)) {
			$query = is_array($params) ? '?'.http_build_query($params) : '';
			$url = $baseUrl . $resource . $query;
		}

		try {
			$response = $client->request($method, $url, $requestParams);
			$responseBody = $response->getBody()->getContents();
			$data = json_decode($responseBody, true);

			if (json_last_error() !== JSON_ERROR_NONE) {
				throw new \Exception('Invalid JSON response from shipping cost API');
			}

			return $data;
		} catch (\GuzzleHttp\Exception\RequestException $e) {
			Log::error('Shipping cost API request failed', [
				'url' => $url,
				'method' => $method,
				'params' => $params,
				'error' => $e->getMessage(),
				'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null
			]);
			throw $e;
		}
	}

	public function complete(Order $order)
	{
		// Ensure user can only complete their own orders
		if ($order->customer_email !== auth()->user()->email) {
			abort(403, 'Unauthorized');
		}

		// Check if order can be completed
		if ($order->isCancelled()) {
			Session::flash('error', 'Cannot complete a cancelled order.');
			return redirect()->route('orders.show', $order->id);
		}

		if ($order->isCompleted()) {
			Session::flash('info', 'Order is already completed.');
			return redirect()->route('orders.show', $order->id);
		}

		if (!$order->isDelivered()) {
			Session::flash('error', 'Order cannot be completed until it has been delivered.');
			return redirect()->route('orders.show', $order->id);
		}

		// Automatically complete the order since it meets all requirements
		return $this->doComplete(request(), $order);
	}

	public function doComplete(Request $request, Order $order)
	{
		// Ensure user can only complete their own orders
		if ($order->customer_email !== auth()->user()->email) {
			abort(403, 'Unauthorized');
		}

		// Check if order can be completed
		if ($order->isCancelled()) {
			Session::flash('error', 'Cannot complete a cancelled order.');
			return redirect()->route('orders.show', $order->id);
		}

		if ($order->isCompleted()) {
			Session::flash('info', 'Order is already completed.');
			return redirect()->route('orders.show', $order->id);
		}

		if (!$order->isDelivered()) {
			Session::flash('error', 'Order cannot be completed until it has been delivered.');
			return redirect()->route('orders.show', $order->id);
		}

		// Mark order as completed
		$order->status = Order::COMPLETED;
		$order->completed_at = now();
		$order->notes = $order->notes . "\nOrder marked as completed by customer";

		if ($order->save()) {
			Session::flash('success', 'Order has been marked as completed successfully!');
		} else {
			Session::flash('error', 'Failed to complete the order. Please try again.');
		}

		return redirect()->route('orders.show', $order->id);
	}

}
