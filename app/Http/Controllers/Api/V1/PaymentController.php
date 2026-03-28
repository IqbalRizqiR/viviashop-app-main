<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends BaseController
{
    private function initMidtrans()
    {
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
    }

    public function clientKey()
    {
        return $this->success([
            'client_key' => config('midtrans.clientKey'),
            'is_production' => config('midtrans.isProduction'),
            'snap_url' => config('midtrans.isProduction')
                ? 'https://app.midtrans.com/snap/snap.js'
                : 'https://app.sandbox.midtrans.com/snap/snap.js',
        ]);
    }

    public function generateToken(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
        ]);

        $order = Order::forUser($request->user())->findOrFail($request->order_id);

        if ($order->payment_status !== Order::UNPAID) {
            return $this->error('Payment token can only be generated for unpaid orders', 422);
        }

        try {
            $this->initMidtrans();

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

                return $this->success([
                    'token' => $snap->token,
                    'redirect_url' => $snap->redirect_url ?? null,
                ], 'Payment token generated');
            }

            return $this->error('Failed to generate payment token', 500);
        } catch (\Exception $e) {
            Log::error('Midtrans token error: ' . $e->getMessage());
            return $this->error('Payment gateway error: ' . $e->getMessage(), 500);
        }
    }

    public function notification(Request $request)
    {
        try {
            $this->initMidtrans();
            $notification = new \Midtrans\Notification();

            $status = $notification->transaction_status;
            $orderCode = $notification->order_id;

            $order = Order::where('code', $orderCode)->first();

            if (!$order) {
                return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
            }

            if ($status === 'settlement' || $status === 'capture') {
                $order->payment_status = Order::PAID;
                if ($order->shipping_service_name === 'Self Pickup') {
                    $order->status = Order::CONFIRMED;
                } else {
                    $order->status = Order::COMPLETED;
                    $order->approved_at = now();
                }
            } elseif ($status === 'pending') {
                $order->payment_status = Order::WAITING;
            } elseif ($status === 'cancel' || $status === 'expire') {
                $order->payment_status = Order::UNPAID;
            }

            $order->save();

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Payment notification error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
