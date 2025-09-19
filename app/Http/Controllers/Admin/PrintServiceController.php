<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrintSession;
use App\Models\PrintOrder;
use App\Models\PrintFile;
use App\Services\PrintService;
use App\Services\StockManagementService;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PrintServiceController extends Controller
{
    protected $printService;
    protected $stockService;

    public function __construct(PrintService $printService)
    {
        $this->printService = $printService;
        $this->stockService = new StockManagementService();
    }

    public function index()
    {
        $activeSessions = PrintSession::active()->count();
        $pendingOrders = PrintOrder::where('payment_status', PrintOrder::PAYMENT_WAITING)->count();
        $printQueue = PrintOrder::printQueue()->count();
        $todayOrders = PrintOrder::whereDate('created_at', today())->count();
        
        $recentOrders = PrintOrder::with(['paperProduct', 'paperVariant'])
                                 ->orderBy('created_at', 'desc')
                                 ->limit(10)
                                 ->get();

        $stockData = $this->stockService->getStockReport();
        $lowStockVariants = $this->stockService->getLowStockVariants();

        return view('admin.print-service.index', compact(
            'activeSessions', 
            'pendingOrders', 
            'printQueue', 
            'todayOrders',
            'recentOrders',
            'stockData',
            'lowStockVariants'
        ));
    }

    public function queue()
    {
        $printQueue = $this->printService->getPrintQueue();
        
        return view('admin.print-service.queue', compact('printQueue'));
    }

    public function sessions()
    {
        $sessions = PrintSession::with('printOrders')
                               ->orderBy('created_at', 'desc')
                               ->paginate(20);
        
        return view('admin.print-service.sessions', compact('sessions'));
    }

    public function orders()
    {
        $orders = PrintOrder::with(['paperProduct', 'paperVariant', 'session'])
                           ->orderBy('created_at', 'desc')
                           ->paginate(20);
        
        return view('admin.print-service.orders', compact('orders'));
    }

    public function generateSession(Request $request)
    {
        try {
            $session = $this->printService->generateSession();
            
            return response()->json([
                'success' => true,
                'session' => $session,
                'qr_code_url' => $session->getQrCodeUrl()
            ]);
        } catch (\Exception $e) {
            Log::error('Generate session error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to generate session'], 500);
        }
    }

    public function confirmPayment(Request $request, $id)
    {
        try {
            $printOrder = PrintOrder::findOrFail($id);
            
            if ($printOrder->payment_method !== 'toko' && $printOrder->payment_method !== 'manual') {
                return response()->json(['error' => 'Invalid payment method for manual confirmation'], 400);
            }

            if ($printOrder->payment_status === PrintOrder::PAYMENT_PAID) {
                return response()->json(['error' => 'Payment already confirmed'], 400);
            }

            // Use PrintService confirmPayment method (with stock reduction and duplicate prevention)
            $this->printService->confirmPayment($printOrder);

            return response()->json([
                'success' => true,
                'message' => 'Payment confirmed and stock reduced successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Confirm payment error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function printOrder(Request $request, $id)
    {
        try {
            $printOrder = PrintOrder::findOrFail($id);

            $this->printService->printDocument($printOrder);

            return response()->json([
                'success' => true,
                'message' => 'Document sent to printer successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Print order error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function printFiles(Request $request, $id)
    {
        try {
            $printOrder = PrintOrder::with(['files'])->findOrFail($id);
            
            $files = [];
            foreach ($printOrder->files as $file) {
                $storageFullPath = storage_path('app' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $file->file_path));
                $publicFullPath = public_path('storage' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $file->file_path));
                
                $fileExists = false;
                $actualPath = '';
                
                if (file_exists($storageFullPath)) {
                    $fileExists = true;
                    $actualPath = $storageFullPath;
                } elseif (file_exists($publicFullPath)) {
                    $fileExists = true;
                    $actualPath = $publicFullPath;
                    
                    $storageDir = dirname($storageFullPath);
                    if (!file_exists($storageDir)) {
                        mkdir($storageDir, 0755, true);
                    }
                    copy($publicFullPath, $storageFullPath);
                }
                
                if ($fileExists && !empty($actualPath)) {
                    $files[] = [
                        'id' => $file->id,
                        'original_name' => $file->file_name,
                        'name' => basename($actualPath),
                        'path' => $actualPath,
                        'view_url' => route('admin.print-service.view-file', $file->id)
                    ];
                }
            }

            if (empty($files)) {
                return response()->json(['error' => 'No files found for printing'], 400);
            }

            return response()->json([
                'success' => true,
                'files' => $files,
                'order_code' => $printOrder->order_code,
                'customer_name' => $printOrder->customer_name,
                'print_data' => [
                    'paper_size' => $printOrder->paperVariant->paper_size ?? 'A4',
                    'print_type' => $printOrder->print_type,
                    'quantity' => $printOrder->quantity,
                    'total_pages' => $printOrder->total_pages
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Print files error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function viewFile($fileId)
    {
        try {
            $printFile = PrintFile::findOrFail($fileId);
            $storageFullPath = storage_path('app' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $printFile->file_path));
            $publicFullPath = public_path('storage' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $printFile->file_path));
            
            $actualPath = '';
            
            if (file_exists($storageFullPath)) {
                $actualPath = $storageFullPath;
            } elseif (file_exists($publicFullPath)) {
                $actualPath = $publicFullPath;
                
                $storageDir = dirname($storageFullPath);
                if (!file_exists($storageDir)) {
                    mkdir($storageDir, 0755, true);
                }
                copy($publicFullPath, $storageFullPath);
                $actualPath = $storageFullPath;
            }
            
            if (empty($actualPath) || !file_exists($actualPath)) {
                abort(404, 'File not found');
            }
            
            $extension = strtolower(pathinfo($actualPath, PATHINFO_EXTENSION));
            $fileName = basename($actualPath);
            
            $mimeType = match($extension) {
                'pdf' => 'application/pdf',
                'jpg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'xls' => 'application/vnd.ms-excel',
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'ppt' => 'application/vnd.ms-powerpoint',
                'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'txt' => 'text/plain',
                default => mime_content_type($actualPath) ?: 'application/octet-stream'
            };
            
            $headers = [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
                'Cache-Control' => 'public, max-age=3600',
                'X-Content-Type-Options' => 'nosniff',
                'X-Frame-Options' => 'SAMEORIGIN'
            ];
            
            return response()->file($actualPath, $headers);
            
        } catch (\Exception $e) {
            abort(404, 'File not found: ' . $e->getMessage());
        }
    }

    public function completeOrder(Request $request, $id)
    {
        try {
            $printOrder = PrintOrder::with('files')->findOrFail($id);

            if ($printOrder->isCompleted()) {
                return response()->json(['error' => 'Order is already completed'], 400);
            }

            if ($printOrder->status === PrintOrder::STATUS_CANCELLED) {
                return response()->json(['error' => 'Cannot complete a cancelled order'], 400);
            }

            if (!$printOrder->isPaid()) {
                return response()->json(['error' => 'Order must be paid before completion'], 400);
            }

            $validStatuses = [
                PrintOrder::STATUS_PAYMENT_CONFIRMED,
                PrintOrder::STATUS_READY_TO_PRINT,
                PrintOrder::STATUS_PRINTING,
                PrintOrder::STATUS_PRINTED
            ];
            
            if (!in_array($printOrder->status, $validStatuses)) {
                return response()->json(['error' => 'Order cannot be completed from current status: ' . $printOrder->status], 400);
            }

            foreach ($printOrder->files as $file) {
                $storageFullPath = storage_path('app' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $file->file_path));
                $publicFullPath = public_path('storage' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $file->file_path));
                
                if (file_exists($storageFullPath)) {
                    unlink($storageFullPath);
                }
                if (file_exists($publicFullPath)) {
                    unlink($publicFullPath);
                }
                
                $file->delete();
            }

            $printOrder->update([
                'status' => PrintOrder::STATUS_COMPLETED,
                'completed_at' => now()
            ]);

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
                    } else {
                        Log::warning("Stock movement skipped for print order {$printOrder->order_code}: Missing product (ID: {$printOrder->paper_product_id}) or variant (ID: {$printOrder->paper_variant_id})");
                    }
                } catch (\Exception $stockException) {
                    Log::error("Stock movement failed for print order {$printOrder->order_code}: " . $stockException->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Order completed and files deleted for privacy'
            ]);
        } catch (\Exception $e) {
            Log::error('Complete order error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function cancelOrder(Request $request, $id)
    {
        try {
            $printOrder = PrintOrder::findOrFail($id);
            
            $printOrder->update(['status' => PrintOrder::STATUS_CANCELLED]);

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Cancel order error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function reports(Request $request)
    {
        $startDate = $request->get('start_date', today()->format('Y-m-d'));
        $endDate = $request->get('end_date', today()->format('Y-m-d'));

        $orders = PrintOrder::whereBetween('created_at', [$startDate, $endDate])
                           ->with(['paperProduct', 'paperVariant'])
                           ->get();

        $totalRevenue = $orders->where('payment_status', PrintOrder::PAYMENT_PAID)->sum('total_price');
        $totalOrders = $orders->count();
        $completedOrders = $orders->where('status', PrintOrder::STATUS_COMPLETED)->count();
        $totalPages = $orders->sum('total_pages');

        $stats = [
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'completed_orders' => $completedOrders,
            'total_pages' => $totalPages,
            'completion_rate' => $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 2) : 0,
        ];

        return view('admin.print-service.reports', compact('orders', 'stats', 'startDate', 'endDate'));
    }

    public function stockManagement()
    {
        $variants = $this->stockService->getVariantsByStock('asc');
        $lowStockVariants = $this->stockService->getLowStockVariants();
        $recentMovements = $this->stockService->getStockReport(null, now()->subDays(7), now());

        return view('admin.print-service.stock', compact('variants', 'lowStockVariants', 'recentMovements'));
    }

    public function adjustStock(Request $request, $variantId)
    {
        $request->validate([
            'new_stock' => 'required|integer|min:0',
            'reason' => 'required|string',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            $result = $this->stockService->adjustStock(
                $variantId,
                $request->new_stock,
                $request->reason,
                $request->notes
            );

            return response()->json([
                'success' => true,
                'message' => 'Stock adjusted successfully',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            Log::error('Stock adjustment error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function stockReport(Request $request)
    {
        $variantId = $request->get('variant_id');
        $dateFrom = $request->get('date_from', now()->subDays(30));
        $dateTo = $request->get('date_to', now());

        $movements = $this->stockService->getStockReport($variantId, $dateFrom, $dateTo);
        $variants = $this->printService->getPrintProducts()->flatMap->activeVariants;

        return view('admin.print-service.stock-report', compact('movements', 'variants', 'variantId', 'dateFrom', 'dateTo'));
    }

    public function downloadPaymentProof($id)
    {
        try {
            $printOrder = PrintOrder::findOrFail($id);
            
            if (!$printOrder->payment_proof) {
                return response()->json([
                    'error' => 'No payment proof found for this order',
                    'order_code' => $printOrder->order_code
                ], 404);
            }

            $filePath = storage_path('app/' . $printOrder->payment_proof);
            
            if (!file_exists($filePath)) {
                Log::warning('Payment proof file not found', [
                    'order_id' => $id,
                    'order_code' => $printOrder->order_code,
                    'expected_path' => $filePath,
                    'stored_path' => $printOrder->payment_proof
                ]);
                
                return response()->json([
                    'error' => 'Payment proof file not found on server',
                    'order_code' => $printOrder->order_code,
                    'stored_path' => $printOrder->payment_proof
                ], 404);
            }

            // Get file extension for proper content type
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $mimeType = match(strtolower($extension)) {
                'jpg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'pdf' => 'application/pdf',
                default => 'application/octet-stream'
            };

            return response()->file($filePath, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="payment_proof_' . $printOrder->order_code . '.' . $extension . '"'
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Order not found',
                'order_id' => $id
            ], 404);
        } catch (\Exception $e) {
            Log::error('Download payment proof error', [
                'order_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'File download failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
