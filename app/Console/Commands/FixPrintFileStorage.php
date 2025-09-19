<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PrintFile;
use App\Models\PrintOrder;

class FixPrintFileStorage extends Command
{
    protected $signature = 'print:fix-storage {--order_id=} {--all}';
    
    protected $description = 'Fix print file storage paths - auto copy from public to storage';

    public function handle()
    {
        $this->info('🔧 FIXING PRINT FILE STORAGE');
        $this->info('============================');

        $query = PrintFile::query();
        
        if ($this->option('order_id')) {
            $order = PrintOrder::where('order_id', $this->option('order_id'))->first();
            if (!$order) {
                $this->error('Order not found: ' . $this->option('order_id'));
                return 1;
            }
            $query->where('print_order_id', $order->id);
            $this->info('Fixing files for order: ' . $order->order_id);
        } elseif ($this->option('all')) {
            $this->info('Fixing all print files...');
        } else {
            $this->info('Fixing recent files (last 50)...');
            $query->orderBy('id', 'desc')->limit(50);
        }

        $files = $query->get();
        
        if ($files->isEmpty()) {
            $this->warn('No files found to process');
            return 0;
        }

        $fixedCount = 0;
        $alreadyOkCount = 0;
        $notFoundCount = 0;

        foreach ($files as $file) {
            $storageFullPath = storage_path('app' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $file->file_path));
            $publicFullPath = public_path('storage' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $file->file_path));
            
            if (file_exists($storageFullPath)) {
                $alreadyOkCount++;
                continue;
            }
            
            if (file_exists($publicFullPath)) {
                $storageDir = dirname($storageFullPath);
                if (!file_exists($storageDir)) {
                    mkdir($storageDir, 0755, true);
                }
                
                copy($publicFullPath, $storageFullPath);
                
                if (file_exists($storageFullPath)) {
                    $this->line("✅ Fixed: {$file->file_name}");
                    $fixedCount++;
                } else {
                    $this->error("❌ Failed to copy: {$file->file_name}");
                }
            } else {
                $this->warn("⚠️ Not found in both locations: {$file->file_name}");
                $notFoundCount++;
            }
        }

        $this->info('');
        $this->info('SUMMARY:');
        $this->info("Files processed: {$files->count()}");
        $this->info("Already OK: {$alreadyOkCount}");
        $this->info("Fixed: {$fixedCount}");
        $this->info("Not found: {$notFoundCount}");

        if ($fixedCount > 0) {
            $this->info('✅ File storage auto-fix completed successfully!');
        }

        return 0;
    }
}
