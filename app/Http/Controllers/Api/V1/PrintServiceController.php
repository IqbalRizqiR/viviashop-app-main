<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\BaseController;
use App\Models\Product;
use App\Models\PrintOrder;
use App\Models\PrintSession;
use App\Services\PrintService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PrintServiceController extends BaseController
{
    protected $printService;

    public function __construct(PrintService $printService)
    {
        $this->printService = $printService;
    }

    public function products()
    {
        $products = $this->printService->getPrintProducts();

        return $this->success($products->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'sku' => $p->sku,
            'price' => (float) $p->price,
            'variants' => $p->activeVariants->map(fn($v) => [
                'id' => $v->id,
                'name' => $v->name,
                'sku' => $v->sku,
                'price' => (float) $v->price,
                'stock' => (int) $v->stock,
                'paper_size' => $v->paper_size,
                'print_type' => $v->print_type,
            ]),
        ]));
    }

    public function createSession(Request $request)
    {
        try {
            $session = $this->printService->generateSession();
            return $this->success([
                'session' => $session,
                'qr_code_url' => $session->getQrCodeUrl(),
            ], 'Session created', 201);
        } catch (\Exception $e) {
            Log::error('Session creation error: ' . $e->getMessage());
            return $this->error('Failed to create session', 500);
        }
    }

    public function submitOrder(Request $request)
    {
        $validated = $request->validate([
            'session_code' => 'required|string',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'paper_product_id' => 'required|exists:products,id',
            'paper_variant_id' => 'required|exists:product_variants,id',
            'print_type' => 'required|in:bw,color',
            'quantity' => 'required|integer|min:1',
            'total_pages' => 'required|integer|min:1',
            'payment_method' => 'required|in:manual,automatic,toko',
            'notes' => 'nullable|string|max:500',
            'files' => 'required|array',
            'files.*' => 'required|file|max:51200', // 50MB max per file
        ]);

        try {
            $session = PrintSession::where('session_code', $validated['session_code'])
                ->where('status', 'active')
                ->firstOrFail();

            $printOrder = $this->printService->createPrintOrder([$validated, $request->file('files')], $session);

            return $this->success([
                'order' => $printOrder,
                'order_code' => $printOrder->order_code,
            ], 'Print order submitted', 201);
        } catch (\Exception $e) {
            Log::error('Print order submission error: ' . $e->getMessage());
            return $this->error($e->getMessage(), 400);
        }
    }

    public function orderStatus($orderCode)
    {
        $order = PrintOrder::where('order_code', $orderCode)
            ->with(['paperProduct', 'paperVariant'])
            ->firstOrFail();

        return $this->success([
            'order_code' => $order->order_code,
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'total_price' => (float) $order->total_price,
            'print_type' => $order->print_type,
            'quantity' => $order->quantity,
            'total_pages' => $order->total_pages,
            'customer_name' => $order->customer_name,
            'created_at' => $order->created_at?->toISOString(),
            'completed_at' => $order->completed_at?->toISOString(),
        ]);
    }

    public function uploadPaymentProof(Request $request, $orderCode)
    {
        $request->validate([
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $order = PrintOrder::where('order_code', $orderCode)->firstOrFail();

        $path = $request->file('payment_proof')->store('print-service/payment-proofs', 'public');
        $order->update([
            'payment_proof' => $path,
            'payment_status' => PrintOrder::PAYMENT_WAITING,
        ]);

        return $this->success(null, 'Payment proof uploaded');
    }
}
