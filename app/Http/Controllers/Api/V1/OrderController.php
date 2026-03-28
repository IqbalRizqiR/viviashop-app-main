<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipment;
use App\Models\ProductInventory;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends BaseController
{
    public function index(Request $request)
    {
        $query = Order::forUser($request->user())->with(['orderItems', 'shipment']);

        if ($q = $request->input('q')) {
            $query->where(function ($qr) use ($q) {
                $qr->where('code', 'like', "%{$q}%")
                   ->orWhere('status', 'like', "%{$q}%")
                   ->orWhere('payment_status', 'like', "%{$q}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('order', 'desc') === 'asc' ? 'asc' : 'desc';
        $allowed = ['id', 'grand_total', 'status', 'created_at', 'order_date'];
        $query->orderBy(in_array($sort, $allowed) ? $sort : 'created_at', $direction);

        $perPage = min((int) $request->input('per_page', 10), 50);
        $orders = $query->paginate($perPage);

        return $this->paginated($orders->through(fn($o) => new OrderResource($o)));
    }

    public function show(Request $request, $id)
    {
        $order = Order::forUser($request->user())
            ->with(['orderItems.product.images', 'orderItems.productVariant', 'shipment'])
            ->findOrFail($id);

        return $this->success(new OrderResource($order));
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address1' => 'required|string|max:255',
            'address2' => 'nullable|string|max:255',
            'postcode' => 'required|string|max:20',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'payment_method' => 'required|string|in:manual,automatic,cod,toko',
            'delivery_method' => 'required|string|in:self,courier',
            'note' => 'nullable|string|max:1000',
            // Courier delivery fields
            'province_id' => 'required_if:delivery_method,courier|nullable|numeric',
            'city_id' => 'required_if:delivery_method,courier|nullable|numeric',
            'district_id' => 'required_if:delivery_method,courier|nullable|numeric',
            'shipping_service' => 'required_if:delivery_method,courier|nullable|string',
        ]);

        $user = $request->user();
        $cart = Cart::getOrCreateForUser($user->id);
        $cart->load('items.product', 'items.variant.variantAttributes');

        if ($cart->items->isEmpty()) {
            return $this->error('Cart is empty', 422);
        }

        try {
            DB::beginTransaction();

            // Calculate shipping
            $shippingCost = 0;
            $shippingService = 'Self Pickup';
            $shippingCourier = 'SELF';

            if ($validated['delivery_method'] === 'courier' && !empty($validated['shipping_service'])) {
                $serviceData = json_decode($validated['shipping_service'], true);
                if ($serviceData && isset($serviceData['cost'])) {
                    $shippingCost = $serviceData['cost'];
                    $shippingService = $serviceData['service'] ?? 'Standard';
                    $shippingCourier = $serviceData['courier'] ?? 'COURIER';
                }
            }

            $baseTotalPrice = (int) $cart->subtotal;
            $grandTotal = $baseTotalPrice + $shippingCost;
            $orderDate = now();

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'code' => Order::generateCode(),
                'status' => Order::CREATED,
                'order_date' => $orderDate,
                'payment_due' => $orderDate->copy()->addDays(7),
                'payment_status' => Order::UNPAID,
                'payment_method' => $validated['payment_method'],
                'base_total_price' => $baseTotalPrice,
                'tax_amount' => 0,
                'tax_percent' => 0,
                'discount_amount' => 0,
                'discount_percent' => 0,
                'shipping_cost' => $shippingCost,
                'grand_total' => $grandTotal,
                'note' => $validated['note'] ?? null,
                'customer_first_name' => $validated['name'],
                'customer_last_name' => $validated['name'],
                'customer_address1' => $validated['address1'],
                'customer_address2' => $validated['address2'] ?? '',
                'customer_phone' => $validated['phone'],
                'customer_email' => $validated['email'],
                'customer_postcode' => $validated['postcode'],
                'customer_city_id' => $validated['city_id'] ?? $user->city_id ?? 1,
                'customer_province_id' => $validated['province_id'] ?? $user->province_id ?? 1,
                'shipping_courier' => $shippingCourier,
                'shipping_service_name' => $shippingService,
            ]);

            // Create order items from cart
            foreach ($cart->items as $cartItem) {
                $product = $cartItem->product;
                $variant = $cartItem->variant;

                $attributes = [];
                if ($variant) {
                    foreach ($variant->variantAttributes ?? [] as $attr) {
                        $attributes[$attr->attribute_name] = $attr->attribute_value;
                    }
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'variant_id' => $cartItem->variant_id,
                    'qty' => $cartItem->qty,
                    'base_price' => $cartItem->price,
                    'base_total' => $cartItem->price * $cartItem->qty,
                    'tax_amount' => 0,
                    'tax_percent' => 0,
                    'discount_amount' => 0,
                    'discount_percent' => 0,
                    'sub_total' => $cartItem->price * $cartItem->qty,
                    'sku' => $variant->sku ?? $product->sku ?? '',
                    'type' => $product->type ?? 'simple',
                    'name' => $variant->name ?? $product->name,
                    'weight' => (string) ($cartItem->options['weight'] ?? $product->weight ?? 0),
                    'attributes' => json_encode($attributes),
                ]);
            }

            // Create shipment record
            Shipment::create([
                'order_id' => $order->id,
                'status' => 'pending',
                'total_qty' => $cart->total_items,
                'total_weight' => $cart->total_weight,
            ]);

            // Generate Midtrans token if automatic payment
            $paymentData = null;
            if ($validated['payment_method'] === 'automatic') {
                $paymentData = $this->generateMidtransToken($order);
            }

            // Update user profile
            $user->update(array_filter([
                'name' => $validated['name'],
                'address1' => $validated['address1'],
                'address2' => $validated['address2'] ?? null,
                'postcode' => $validated['postcode'],
                'phone' => $validated['phone'],
            ]));

            // Clear cart after successful order
            $cart->items()->delete();

            DB::commit();

            $order->load('orderItems', 'shipment');

            $response = [
                'order' => new OrderResource($order),
                'redirect_url' => url("/orders/received/{$order->id}"),
            ];

            if ($paymentData && $paymentData['success']) {
                $response['payment_token'] = $paymentData['token'];
                $response['payment_url'] = $paymentData['redirect_url'];
            }

            return $this->success($response, 'Order placed successfully', 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('API Checkout Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->error('Failed to place order: ' . $e->getMessage(), 500);
        }
    }

    public function confirmPayment(Request $request, $id)
    {
        $request->validate([
            'payment_slip' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $order = Order::forUser($request->user())->findOrFail($id);

        $path = $request->file('payment_slip')->store('assets/payment_slips', 'public');

        $order->update([
            'payment_slip' => $path,
            'payment_status' => Order::WAITING,
        ]);

        return $this->success(new OrderResource($order->fresh()), 'Payment confirmation uploaded');
    }

    public function received(Request $request, $id)
    {
        $order = Order::forUser($request->user())
            ->with(['orderItems.product.images', 'shipment'])
            ->findOrFail($id);

        return $this->success(new OrderResource($order));
    }

    public function complete(Request $request, $id)
    {
        $order = Order::forUser($request->user())->findOrFail($id);

        if ($order->status !== Order::DELIVERED) {
            return $this->error('Order can only be completed after delivery', 422);
        }

        $order->update(['status' => Order::COMPLETED]);

        return $this->success(new OrderResource($order->fresh()), 'Order marked as completed');
    }

    private function generateMidtransToken(Order $order): array
    {
        try {
            \Midtrans\Config::$serverKey = config('midtrans.serverKey');
            \Midtrans\Config::$isProduction = config('midtrans.isProduction');
            \Midtrans\Config::$isSanitized = config('midtrans.isSanitized');
            \Midtrans\Config::$is3ds = config('midtrans.is3ds');

            $isLocal = in_array(request()->getHost(), ['localhost', '127.0.0.1', '::1']);
            if ($isLocal) {
                \Midtrans\Config::$curlOptions = [
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                ];
            }

            $params = [
                'transaction_details' => [
                    'order_id' => $order->code,
                    'gross_amount' => (int) $order->grand_total,
                ],
                'customer_details' => [
                    'first_name' => $order->customer_first_name,
                    'email' => $order->customer_email,
                    'phone' => $order->customer_phone ?? '',
                ],
            ];

            $snap = \Midtrans\Snap::createTransaction($params);

            if (isset($snap->token)) {
                $order->update([
                    'payment_token' => $snap->token,
                    'payment_url' => $snap->redirect_url ?? null,
                ]);

                return ['success' => true, 'token' => $snap->token, 'redirect_url' => $snap->redirect_url ?? null];
            }

            return ['success' => false, 'message' => 'No token received'];
        } catch (\Exception $e) {
            Log::error('Midtrans token error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
