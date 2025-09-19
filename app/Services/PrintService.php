<?php

namespace App\Services;

use App\Models\PrintSession;
use App\Models\PrintOrder;
use App\Models\PrintFile;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\StockManagementService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PrintService
{
    protected $stockService;

    public function __construct()
    {
        $this->stockService = new StockManagementService();
    }
    public function generateSession()
    {
        PrintSession::cleanup();
        
        return PrintSession::generateNew();
    }

    public function getSession($token)
    {
        return PrintSession::where('session_token', $token)
                          ->where('is_active', true)
                          ->where('expires_at', '>', now())
                          ->first();
    }

    public function getPrintProducts()
    {
        return Product::where('is_print_service', true)
                     ->where('is_smart_print_enabled', true)
                     ->where('status', Product::ACTIVE)
                     ->with(['activeVariants' => function($query) {
                         $query->orderBy('paper_size', 'asc')
                               ->orderBy('print_type', 'asc');
                     }])
                     ->get();
    }

    public function uploadFiles(array $files, PrintSession $session)
    {
        $uploadedFiles = [];
        $totalPages = 0;
        $skippedFiles = [];

        foreach ($files as $file) {
            if ($this->validateFile($file)) {
                // 🛡️ SAFEGUARD: Check for duplicate file in same session
                $existingFile = PrintFile::where('print_session_id', $session->id)
                    ->where('file_name', $file->getClientOriginalName())
                    ->where('file_size', $file->getSize())
                    ->first();

                if ($existingFile) {
                    Log::warning('Duplicate file upload prevented', [
                        'session_id' => $session->id,
                        'file_name' => $file->getClientOriginalName(),
                        'existing_file_id' => $existingFile->id
                    ]);
                    
                    $skippedFiles[] = [
                        'name' => $file->getClientOriginalName(),
                        'reason' => 'File already exists in this session'
                    ];
                    continue;
                }

                $fileData = $this->storeFile($file, $session);
                
                $printFile = PrintFile::create([
                    'print_session_id' => $session->id,
                    'file_path' => $fileData['path'],
                    'file_name' => $fileData['name'],
                    'file_type' => $fileData['type'],
                    'file_size' => $fileData['size'],
                    'pages_count' => $fileData['pages_count'],
                ]);
                
                Log::info('File uploaded successfully', [
                    'session_id' => $session->id,
                    'file_id' => $printFile->id,
                    'file_name' => $printFile->file_name,
                    'pages_count' => $printFile->pages_count
                ]);
                
                $uploadedFiles[] = $printFile;
                $totalPages += $fileData['pages_count'];
            }
        }

        $response = [
            'success' => true,
            'files' => collect($uploadedFiles)->map(function($file) {
                return [
                    'id' => $file->id,
                    'name' => $file->file_name,
                    'file_name' => $file->file_name,
                    'file_type' => $file->file_type,
                    'file_size' => $file->file_size,
                    'pages_count' => $file->pages_count,
                    'file_path' => $file->file_path
                ];
            })->toArray(),
            'total_pages' => $totalPages
        ];

        // Include skipped files info if any
        if (!empty($skippedFiles)) {
            $response['skipped_files'] = $skippedFiles;
            $response['message'] = count($skippedFiles) . ' duplicate file(s) were skipped.';
        }

        return $response;
    }

    public function deleteFile($fileId, PrintSession $session)
    {
        $file = PrintFile::where('id', $fileId)
                         ->where('print_session_id', $session->id)
                         ->first();
        
        if (!$file) {
            throw new \Exception('File not found or access denied');
        }

        if (Storage::exists($file->file_path)) {
            Storage::delete($file->file_path);
        }

        $file->delete();

        $remainingFiles = $session->printFiles()->get();
        $totalPages = $remainingFiles->sum('pages_count');

        return [
            'success' => true,
            'files' => $remainingFiles->map(function($file) {
                return [
                    'id' => $file->id,
                    'name' => $file->file_name,
                    'file_name' => $file->file_name,
                    'file_type' => $file->file_type,
                    'file_size' => $file->file_size,
                    'pages_count' => $file->pages_count,
                    'file_path' => $file->file_path
                ];
            })->toArray(),
            'total_pages' => $totalPages
        ];
    }

    public function createPrintOrder(array $data, PrintSession $session)
    {
        $variant = ProductVariant::findOrFail($data['variant_id']);
        $product = $variant->product;

        if (!$product->is_print_service) {
            throw new \Exception('Invalid product for print service');
        }

        $sessionFiles = $session->printFiles;
        if ($sessionFiles->isEmpty()) {
            throw new \Exception('No files uploaded for this session');
        }

        $totalPages = $sessionFiles->sum('pages_count');
        $requiredStock = $totalPages * ($data['quantity'] ?? 1);

        $stockCheck = $this->stockService->checkStockAvailability($variant->id, $requiredStock);
        if (!$stockCheck['available']) {
            throw new \Exception($stockCheck['message']);
        }

        $unitPrice = $variant->price;
        $totalPrice = $unitPrice * $totalPages * ($data['quantity'] ?? 1);

        $printOrder = PrintOrder::create([
            'order_code' => PrintOrder::generateCode(),
            'customer_phone' => $data['customer_phone'],
            'customer_name' => $data['customer_name'],
            'file_data' => json_encode($sessionFiles->map(function($file) {
                return [
                    'name' => $file->file_name,
                    'type' => $file->file_type,
                    'size' => $file->file_size,
                    'pages' => $file->pages_count
                ];
            })),
            'paper_product_id' => $product->id,
            'paper_variant_id' => $variant->id,
            'print_type' => $variant->print_type,
            'quantity' => $data['quantity'] ?? 1,
            'total_pages' => $totalPages,
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice,
            'payment_method' => $data['payment_method'],
            'session_id' => $session->id,
        ]);

        foreach ($sessionFiles as $file) {
            $file->update([
                'print_order_id' => $printOrder->id,
                'print_session_id' => null
            ]);
        }

        $session->updateStep(PrintSession::STEP_PAYMENT);

        return $printOrder;
    }

    public function calculatePrice($variantId, $totalPages, $quantity = 1)
    {
        $variant = ProductVariant::findOrFail($variantId);
        
        if (!$variant->product->is_print_service) {
            throw new \Exception('Invalid product for print service');
        }

        $unitPrice = $variant->price;
        $totalPrice = $unitPrice * $totalPages * $quantity;

        return [
            'unit_price' => $unitPrice,
            'total_pages' => $totalPages,
            'quantity' => $quantity,
            'total_price' => $totalPrice,
            'variant' => $variant
        ];
    }

    public function processPayment(PrintOrder $printOrder, $paymentData)
    {
        switch ($printOrder->payment_method) {
            case 'toko':
                return $this->processStorePayment($printOrder, $paymentData);
            case 'manual':
                return $this->processManualPayment($printOrder, $paymentData);
            case 'automatic':
                return $this->processAutomaticPayment($printOrder, $paymentData);
            default:
                throw new \Exception('Invalid payment method');
        }
    }

    public function confirmPayment(PrintOrder $printOrder)
    {
        // Check if stock has already been reduced for this order
        $existingMovement = \App\Models\StockMovement::where('reference_type', 'print_order')
                                                    ->where('reference_id', $printOrder->id)
                                                    ->where('variant_id', $printOrder->paper_variant_id)
                                                    ->where('movement_type', 'out')
                                                    ->first();
        
        if ($existingMovement) {
            Log::info("Stock already reduced for order {$printOrder->order_code}, skipping stock reduction");
            
            // Just update status without reducing stock again
            $printOrder->update([
                'payment_status' => PrintOrder::PAYMENT_PAID,
                'status' => PrintOrder::STATUS_READY_TO_PRINT
            ]);
            
            return $printOrder;
        }

        $printOrder->update([
            'payment_status' => PrintOrder::PAYMENT_PAID,
            'status' => PrintOrder::STATUS_READY_TO_PRINT
        ]);

        $requiredStock = $printOrder->total_pages * $printOrder->quantity;
        
        try {
            $this->stockService->reduceStock(
                $printOrder->paper_variant_id,
                $requiredStock,
                $printOrder->id,
                'order_confirmed'
            );
            
            Log::info("Stock reduced for confirmed order {$printOrder->order_code}: {$requiredStock} units");
        } catch (\Exception $e) {
            Log::error("Failed to reduce stock for order {$printOrder->order_code}: " . $e->getMessage());
            
            $printOrder->update([
                'payment_status' => PrintOrder::PAYMENT_WAITING,
                'status' => PrintOrder::STATUS_PAYMENT_PENDING
            ]);
            
            throw new \Exception('Stock not available. Order payment reverted.');
        }

        return $printOrder;
    }

    public function cancelOrder(PrintOrder $printOrder)
    {
        if (in_array($printOrder->status, [PrintOrder::STATUS_PRINTING, PrintOrder::STATUS_PRINTED, PrintOrder::STATUS_COMPLETED])) {
            throw new \Exception('Cannot cancel order in current status');
        }

        if ($printOrder->payment_status === PrintOrder::PAYMENT_PAID) {
            $requiredStock = $printOrder->total_pages * $printOrder->quantity;
            
            try {
                $this->stockService->restoreStock(
                    $printOrder->paper_variant_id,
                    $requiredStock,
                    $printOrder->id,
                    'order_cancelled'
                );
                
                Log::info("Stock restored for cancelled order {$printOrder->order_code}: {$requiredStock} units");
            } catch (\Exception $e) {
                Log::error("Failed to restore stock for cancelled order {$printOrder->order_code}: " . $e->getMessage());
            }
        }

        $printOrder->update(['status' => PrintOrder::STATUS_CANCELLED]);
        
        return $printOrder;
    }

    public function markReadyToPrint(PrintOrder $printOrder)
    {
        if (!$printOrder->isPaid()) {
            throw new \Exception('Payment not confirmed');
        }

        $printOrder->update(['status' => PrintOrder::STATUS_READY_TO_PRINT]);
    }

    public function printDocument(PrintOrder $printOrder)
    {
        if (!$printOrder->canPrint()) {
            throw new \Exception('Order is not ready for printing');
        }

        $printOrder->markAsPrinting();

        try {
            $this->sendToPrinter($printOrder);
            $printOrder->markAsPrinted();
        } catch (\Exception $e) {
            Log::error('Print failed for order ' . $printOrder->order_code . ': ' . $e->getMessage());
            throw $e;
        }

        return true;
    }

    public function completePrintOrder(PrintOrder $printOrder)
    {
        if (!in_array($printOrder->status, [PrintOrder::STATUS_PRINTING, PrintOrder::STATUS_PRINTED])) {
            throw new \Exception('Order is not ready for completion');
        }

        if ($printOrder->status === PrintOrder::STATUS_PRINTING) {
            $printOrder->markAsPrinted();
        }

        $printOrder->markAsCompleted();
        $this->cleanupFiles($printOrder);

        $session = $printOrder->session;
        if ($session) {
            $session->updateStep(PrintSession::STEP_COMPLETE);
            $session->markInactive();
        }

        Log::info('Print order completed and files cleaned: ' . $printOrder->order_code);

        return true;
    }

    public function getPrintQueue()
    {
        return PrintOrder::printQueue()
                         ->with(['paperProduct', 'paperVariant', 'files'])
                         ->orderBy('created_at', 'asc')
                         ->get();
    }

    private function validateFile(UploadedFile $file)
    {
        $allowedTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'txt', 'csv', 'log', 'rtf', 'odt', 'ods', 'ppt', 'pptx'];
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (!in_array($extension, $allowedTypes)) {
            throw new \Exception('File type not supported: ' . $extension);
        }

        if ($file->getSize() > 50 * 1024 * 1024) { // 50MB limit
            throw new \Exception('File size too large: ' . $file->getClientOriginalName());
        }

        return true;
    }

    private function storeFile(UploadedFile $file, PrintSession $session)
    {
        $date = now()->format('Y-m-d');
        $directory = "print-files/{$date}/{$session->session_token}";
        
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs($directory, $filename, 'local');
        
        $storagePath = storage_path('app/' . $path);
        $publicDirectory = public_path('storage/' . $directory);
        $publicPath = $publicDirectory . '/' . $filename;
        
        if (!file_exists($publicDirectory)) {
            mkdir($publicDirectory, 0755, true);
        }
        
        if (file_exists($storagePath)) {
            copy($storagePath, $publicPath);
        }
        
        $pagesCount = $this->countPages($file);

        return [
            'path' => $path,
            'name' => $file->getClientOriginalName(),
            'type' => strtolower($file->getClientOriginalExtension()),
            'size' => $file->getSize(),
            'pages_count' => $pagesCount
        ];
    }

    private function countPages(UploadedFile $file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        switch ($extension) {
            case 'pdf':
                return $this->countPdfPages($file);
            case 'doc':
            case 'docx':
            case 'rtf':
            case 'odt':
                return $this->estimateDocPages($file);
            case 'xls':
            case 'xlsx':
            case 'ods':
                return $this->estimateExcelPages($file);
            case 'ppt':
            case 'pptx':
                return $this->estimatePptPages($file);
            case 'jpg':
            case 'jpeg':
            case 'png':
                return 1;
            case 'txt':
            case 'csv':
            case 'log':
                return $this->estimateTxtPages($file);
            default:
                return 1;
        }
    }

    private function countPdfPages(UploadedFile $file)
    {
        try {
            $content = file_get_contents($file->path());
            $pageCount = preg_match_all("/\/Page\W/", $content);
            return max(1, $pageCount);
        } catch (\Exception $e) {
            Log::warning('Could not count PDF pages: ' . $e->getMessage());
            return 1;
        }
    }

    private function estimateDocPages(UploadedFile $file)
    {
        $sizeInBytes = $file->getSize();
        $estimatedPages = max(1, round($sizeInBytes / 50000));
        return $estimatedPages;
    }

    private function estimateExcelPages(UploadedFile $file)
    {
        $sizeInBytes = $file->getSize();
        $estimatedPages = max(1, round($sizeInBytes / 75000));
        return $estimatedPages;
    }

    private function estimatePptPages(UploadedFile $file)
    {
        $sizeInBytes = $file->getSize();
        $estimatedPages = max(1, round($sizeInBytes / 100000));
        return $estimatedPages;
    }

    private function estimateTxtPages(UploadedFile $file)
    {
        try {
            $content = file_get_contents($file->path());
            $lines = substr_count($content, "\n") + 1;
            $estimatedPages = max(1, ceil($lines / 50));
            return $estimatedPages;
        } catch (\Exception $e) {
            return 1;
        }
    }

    private function processStorePayment(PrintOrder $printOrder, $paymentData)
    {
        $printOrder->update([
            'status' => PrintOrder::STATUS_PAYMENT_PENDING,
            'payment_status' => PrintOrder::PAYMENT_WAITING
        ]);

        return ['status' => 'pending', 'message' => 'Menunggu konfirmasi pembayaran di toko'];
    }

    private function processManualPayment(PrintOrder $printOrder, $paymentData)
    {
        if (!isset($paymentData['payment_proof'])) {
            throw new \Exception('Payment proof is required');
        }

        $proofPath = $this->storePaymentProof($paymentData['payment_proof'], $printOrder);
        
        $printOrder->update([
            'status' => PrintOrder::STATUS_PAYMENT_PENDING,
            'payment_status' => PrintOrder::PAYMENT_WAITING,
            'payment_proof' => $proofPath
        ]);

        return ['status' => 'pending', 'message' => 'Bukti pembayaran berhasil dikirim, menunggu verifikasi'];
    }

    private function processAutomaticPayment(PrintOrder $printOrder, $paymentData)
    {
        $paymentToken = $this->generateMidtransToken($printOrder);
        
        $printOrder->update([
            'status' => PrintOrder::STATUS_PAYMENT_PENDING,
            'payment_status' => PrintOrder::PAYMENT_UNPAID,
            'payment_token' => $paymentToken['token'],
            'payment_url' => $paymentToken['redirect_url']
        ]);

        return [
            'status' => 'redirect',
            'token' => $paymentToken['token'],
            'redirect_url' => $paymentToken['redirect_url']
        ];
    }

    private function storePaymentProof(UploadedFile $file, PrintOrder $printOrder)
    {
        try {
            // Validate file
            if (!$file->isValid()) {
                throw new \Exception('Invalid file upload');
            }
            
            $directory = "print-payments/{$printOrder->order_code}";
            $filename = 'payment_proof_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Ensure directory exists
            $fullDir = storage_path("app/{$directory}");
            if (!is_dir($fullDir)) {
                mkdir($fullDir, 0755, true);
            }
            
            // Store file
            $path = $file->storeAs($directory, $filename, 'local');
            
            // Verify file was stored
            if (!Storage::disk('local')->exists($path)) {
                throw new \Exception('File was not stored successfully');
            }
            
            Log::info('Payment proof stored successfully', [
                'order_code' => $printOrder->order_code,
                'file_path' => $path,
                'file_size' => $file->getSize()
            ]);
            
            return $path;
            
        } catch (\Exception $e) {
            Log::error('Failed to store payment proof', [
                'order_code' => $printOrder->order_code,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    private function generateMidtransToken(PrintOrder $printOrder)
    {
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('midtrans.isProduction');
        \Midtrans\Config::$isSanitized = config('midtrans.isSanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is3ds');

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

        $params = [
            'transaction_details' => [
                'order_id' => $printOrder->order_code,
                'gross_amount' => (int) $printOrder->total_price,
            ],
            'item_details' => [
                [
                    'id' => $printOrder->paper_variant_id,
                    'price' => (int) $printOrder->unit_price,
                    'quantity' => $printOrder->total_pages * $printOrder->quantity,
                    'name' => $printOrder->paperVariant->name . ' (' . $printOrder->total_pages . ' halaman)',
                ]
            ],
            'customer_details' => [
                'first_name' => $printOrder->customer_name,
                'phone' => $printOrder->customer_phone,
            ],
            'callbacks' => [
                'finish' => url('/print-service/payment/finish?order_code=' . $printOrder->order_code),
                'unfinish' => url('/print-service/payment/unfinish?order_code=' . $printOrder->order_code),
                'error' => url('/print-service/payment/error?order_code=' . $printOrder->order_code),
            ],
        ];

        try {
            Log::info('Generating Midtrans token', [
                'order_code' => $printOrder->order_code,
                'amount' => $printOrder->total_price,
                'params' => $params
            ]);

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            
            Log::info('Midtrans token generated successfully', [
                'order_code' => $printOrder->order_code,
                'token' => $snapToken
            ]);

            $isProduction = config('midtrans.isProduction');
            $baseUrl = $isProduction ? 'https://app.midtrans.com' : 'https://app.sandbox.midtrans.com';

            return [
                'token' => $snapToken,
                'redirect_url' => $baseUrl . '/snap/v2/vtweb/' . $snapToken
            ];
        } catch (\Exception $e) {
            Log::error('Midtrans token generation failed: ' . $e->getMessage(), [
                'order_code' => $printOrder->order_code,
                'config' => [
                    'serverKey' => config('midtrans.serverKey') ? 'Set' : 'Not set',
                    'isProduction' => config('midtrans.isProduction'),
                    'isSanitized' => config('midtrans.isSanitized'),
                    'is3ds' => config('midtrans.is3ds')
                ]
            ]);
            throw new \Exception('Payment gateway error: ' . $e->getMessage());
        }
    }

    private function sendToPrinter(PrintOrder $printOrder)
    {
        Log::info('Sending print order to printer: ' . $printOrder->order_code);
        return true;
    }

    private function cleanupFiles(PrintOrder $printOrder)
    {
        foreach ($printOrder->files as $file) {
            try {
                $fullPath = storage_path('app' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $file->file_path));
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                    Log::info('Deleted file: ' . $file->file_path);
                } else {
                    Log::warning('File not found for deletion: ' . $file->file_path);
                }
            } catch (\Exception $e) {
                Log::warning('Could not delete file: ' . $file->file_path . ' - ' . $e->getMessage());
            }
        }

        if ($printOrder->payment_proof) {
            try {
                $proofPath = storage_path('app' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $printOrder->payment_proof));
                if (file_exists($proofPath)) {
                    unlink($proofPath);
                    Log::info('Deleted payment proof: ' . $printOrder->payment_proof);
                }
            } catch (\Exception $e) {
                Log::warning('Could not delete payment proof: ' . $printOrder->payment_proof . ' - ' . $e->getMessage());
            }
        }
    }
}
