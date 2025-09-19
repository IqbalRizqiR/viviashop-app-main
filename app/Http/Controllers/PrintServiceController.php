<?php

namespace App\Http\Controllers;

use App\Models\PrintSession;
use App\Models\PrintOrder;
use App\Services\PrintService;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PrintServiceController extends Controller
{
    protected $printService;

    public function __construct(PrintService $printService)
    {
        $this->printService = $printService;
    }

    public function index($token)
    {
        try {
            $session = $this->printService->getSession($token);
            
            if (!$session) {
                return view('print-service.expired');
            }

            $products = $this->printService->getPrintProducts();
            
            return view('print-service.index', compact('session', 'products'));
        } catch (\Exception $e) {
            Log::error('Print service index error: ' . $e->getMessage());
            return view('print-service.error', ['message' => 'Terjadi kesalahan sistem']);
        }
    }

    public function upload(Request $request)
    {
        try {
            $request->validate([
                'session_token' => 'required|string',
                'files' => 'required|array|min:1|max:10',
                'files.*' => 'required|file|max:51200', // 50MB
            ]);

            $session = $this->printService->getSession($request->session_token);
            
            if (!$session) {
                return response()->json(['error' => 'Session expired'], 400);
            }

            $uploadResult = $this->printService->uploadFiles($request->file('files'), $session);
            
            return response()->json([
                'success' => true,
                'files' => $uploadResult['files'],
                'total_pages' => $uploadResult['total_pages']
            ]);
        } catch (\Exception $e) {
            Log::error('File upload error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function deleteFile(Request $request)
    {
        try {
            $request->validate([
                'session_token' => 'required|string',
                'file_id' => 'required|integer'
            ]);

            $session = $this->printService->getSession($request->session_token);
            
            if (!$session) {
                return response()->json(['error' => 'Session expired'], 400);
            }

            $result = $this->printService->deleteFile($request->file_id, $session);
            
            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('File delete error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function previewFile(Request $request)
    {
        try {
            $request->validate([
                'session_token' => 'required|string',
                'file_id' => 'required|integer'
            ]);

            $session = $this->printService->getSession($request->session_token);
            
            if (!$session) {
                return response()->json(['error' => 'Session expired'], 400);
            }

            $file = \App\Models\PrintFile::where('id', $request->file_id)
                                         ->where('print_session_id', $session->id)
                                         ->first();
            
            if (!$file) {
                return response()->json(['error' => 'File not found'], 404);
            }

            if (!Storage::exists($file->file_path)) {
                return response()->json(['error' => 'File not accessible'], 404);
            }

            return Storage::download($file->file_path, $file->file_name);
        } catch (\Exception $e) {
            Log::error('File preview error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getProducts(Request $request)
    {
        try {
            $products = $this->printService->getPrintProducts();
            
            return response()->json([
                'success' => true,
                'products' => $products->map(function($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'variants' => $product->activeVariants->map(function($variant) {
                            return [
                                'id' => $variant->id,
                                'name' => $variant->name,
                                'price' => $variant->price,
                                'print_type' => $variant->print_type,
                                'paper_size' => $variant->paper_size,
                                'stock' => $variant->stock
                            ];
                        })
                    ];
                })
            ]);
        } catch (\Exception $e) {
            Log::error('Get products error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load products'], 500);
        }
    }

    public function calculate(Request $request)
    {
        try {
            $request->validate([
                'variant_id' => 'required|exists:product_variants,id',
                'total_pages' => 'required|integer|min:1',
                'quantity' => 'integer|min:1'
            ]);

            $calculation = $this->printService->calculatePrice(
                $request->variant_id,
                $request->total_pages,
                $request->quantity ?? 1
            );

            return response()->json([
                'success' => true,
                'calculation' => $calculation
            ]);
        } catch (\Exception $e) {
            Log::error('Price calculation error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function checkout(Request $request)
    {
        try {
            $request->validate([
                'session_token' => 'required|string',
                'customer_name' => 'required|string|max:255',
                'customer_phone' => 'required|string|max:20',
                'variant_id' => 'required|exists:product_variants,id',
                'payment_method' => 'required|in:toko,manual,automatic',
                'files' => 'required|array|min:1',
                'files.*' => 'required',
                'total_pages' => 'required|integer|min:1',
                'quantity' => 'integer|min:1'
            ]);

            $session = $this->printService->getSession($request->session_token);
            
            if (!$session) {
                return response()->json(['error' => 'Session expired'], 400);
            }

            DB::beginTransaction();

            $printOrder = $this->printService->createPrintOrder($request->all(), $session);
            
            $paymentData = [];
            if ($request->hasFile('payment_proof')) {
                $paymentData['payment_proof'] = $request->file('payment_proof');
            }
            
            $paymentResult = $this->printService->processPayment($printOrder, $paymentData);

            DB::commit();

            return response()->json([
                'success' => true,
                'order' => $printOrder,
                'payment' => $paymentResult
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function status($orderCode)
    {
        try {
            $printOrder = PrintOrder::where('order_code', $orderCode)
                                   ->with(['paperProduct', 'paperVariant', 'files'])
                                   ->firstOrFail();

            return response()->json([
                'success' => true,
                'order' => $printOrder
            ]);
        } catch (\Exception $e) {
            Log::error('Status check error: ' . $e->getMessage());
            return response()->json(['error' => 'Order not found'], 404);
        }
    }

    public function print(Request $request, $orderCode)
    {
        try {
            $printOrder = PrintOrder::where('order_code', $orderCode)->firstOrFail();

            if (!$printOrder->canPrint()) {
                return response()->json(['error' => 'Order is not ready for printing'], 400);
            }

            $this->printService->printDocument($printOrder);

            return response()->json([
                'success' => true,
                'message' => 'Document sent to printer successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Print error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function complete(Request $request, $orderCode)
    {
        try {
            $printOrder = PrintOrder::where('order_code', $orderCode)->firstOrFail();

            $this->printService->completePrintOrder($printOrder);

            return response()->json([
                'success' => true,
                'message' => 'Order completed successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Complete order error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function generateSession(Request $request)
    {
        try {
            $session = $this->printService->generateSession();
            
            $qrCodeUrl = $session->getQrCodeUrl();
            $qrCode = QrCode::size(200)->generate($qrCodeUrl);

            return response()->json([
                'success' => true,
                'token' => $session->session_token,
                'session' => $session,
                'qr_code_url' => $qrCodeUrl,
                'qr_code_svg' => $qrCode
            ]);
        } catch (\Exception $e) {
            Log::error('Generate session error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to generate session'], 500);
        }
    }

    public function midtransCallback(Request $request)
    {
        try {
            $serverKey = config('midtrans.serverKey');
            $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
            
            if ($hashed !== $request->signature_key) {
                return response()->json(['error' => 'Invalid signature'], 400);
            }

            $printOrder = PrintOrder::where('order_code', $request->order_id)->firstOrFail();
            
            switch ($request->transaction_status) {
                case 'settlement':
                case 'capture':
                    $printOrder->markAsPaymentConfirmed();
                    $this->printService->markReadyToPrint($printOrder);
                    break;
                    
                case 'pending':
                    $printOrder->update(['payment_status' => PrintOrder::PAYMENT_WAITING]);
                    break;
                    
                case 'deny':
                case 'cancel':
                case 'expire':
                    $printOrder->update([
                        'status' => PrintOrder::STATUS_CANCELLED,
                        'payment_status' => PrintOrder::PAYMENT_UNPAID
                    ]);
                    break;
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Midtrans callback error: ' . $e->getMessage());
            return response()->json(['error' => 'Callback processing failed'], 500);
        }
    }

    /**
     * Handle payment finish callback
     */
    public function paymentFinish(Request $request)
    {
        try {
            $orderId = $request->get('order_id');
            $statusCode = $request->get('status_code');
            $transactionStatus = $request->get('transaction_status');

            Log::info('Payment finish callback', [
                'order_id' => $orderId,
                'status_code' => $statusCode,
                'transaction_status' => $transactionStatus,
                'all_params' => $request->all()
            ]);

            if ($orderId) {
                $printOrder = PrintOrder::where('order_code', $orderId)->first();
                
                if ($printOrder) {
                    // Update payment status jika berhasil settlement
                    if ($transactionStatus === 'settlement' && $statusCode == '200') {
                        // Confirm payment (this will handle stock reduction)
                        $this->printService->confirmPayment($printOrder);
                        
                        Log::info('Payment confirmed via finish callback', [
                            'order_code' => $printOrder->order_code,
                            'payment_status' => $printOrder->payment_status,
                            'status' => $printOrder->status
                        ]);
                        
                        return redirect()->route('print-service.customer', $printOrder->session->token)
                            ->with('success', 'Pembayaran berhasil! Pesanan Anda sedang diproses.');
                    } else {
                        return redirect()->route('print-service.customer', $printOrder->session->token)
                            ->with('info', 'Status pembayaran: ' . $transactionStatus);
                    }
                }
            }

            return redirect('/')->with('error', 'Order tidak ditemukan');
        } catch (\Exception $e) {
            Log::error('Payment finish callback error: ' . $e->getMessage());
            return redirect('/')->with('error', 'Terjadi kesalahan saat memproses callback');
        }
    }

    /**
     * Handle payment unfinish callback
     */
    public function paymentUnfinish(Request $request)
    {
        try {
            $orderId = $request->get('order_id');
            
            Log::info('Payment unfinish callback', [
                'order_id' => $orderId,
                'all_params' => $request->all()
            ]);

            if ($orderId) {
                $printOrder = PrintOrder::where('order_code', $orderId)->first();
                
                if ($printOrder) {
                    return redirect()->route('print-service.customer', $printOrder->session->token)
                        ->with('warning', 'Pembayaran belum selesai. Silakan lanjutkan pembayaran Anda.');
                }
            }

            return redirect('/')->with('error', 'Order tidak ditemukan');
        } catch (\Exception $e) {
            Log::error('Payment unfinish callback error: ' . $e->getMessage());
            return redirect('/')->with('error', 'Terjadi kesalahan saat memproses callback');
        }
    }

    /**
     * Handle payment error callback
     */
    public function paymentError(Request $request)
    {
        try {
            $orderId = $request->get('order_id');
            
            Log::info('Payment error callback', [
                'order_id' => $orderId,
                'all_params' => $request->all()
            ]);

            if ($orderId) {
                $printOrder = PrintOrder::where('order_code', $orderId)->first();
                
                if ($printOrder) {
                    return redirect()->route('print-service.customer', $printOrder->session->token)
                        ->with('error', 'Terjadi kesalahan pada pembayaran. Silakan coba lagi.');
                }
            }

            return redirect('/')->with('error', 'Order tidak ditemukan');
        } catch (\Exception $e) {
            Log::error('Payment error callback error: ' . $e->getMessage());
            return redirect('/')->with('error', 'Terjadi kesalahan saat memproses callback');
        }
    }
}
