<?php

namespace App\Services;

use App\Models\ProductVariant;
use App\Models\PrintOrder;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockManagementService
{
    public function checkStockAvailability($variantId, $quantity)
    {
        $variant = ProductVariant::find($variantId);
        
        if (!$variant) {
            return [
                'available' => false,
                'message' => 'Product variant not found'
            ];
        }

        if ($variant->stock < $quantity) {
            return [
                'available' => false,
                'message' => "Insufficient stock. Available: {$variant->stock}, Required: {$quantity}",
                'available_stock' => $variant->stock
            ];
        }

        if ($variant->stock <= $variant->min_stock_threshold) {
            return [
                'available' => true,
                'message' => "Low stock warning. Only {$variant->stock} units remaining",
                'low_stock' => true,
                'available_stock' => $variant->stock
            ];
        }

        return [
            'available' => true,
            'message' => 'Stock available',
            'available_stock' => $variant->stock
        ];
    }

    public function reduceStock($variantId, $quantity, $orderId, $reason = 'order_confirmed')
    {
        return DB::transaction(function() use ($variantId, $quantity, $orderId, $reason) {
            $variant = ProductVariant::lockForUpdate()->find($variantId);
            
            if (!$variant) {
                throw new \Exception('Product variant not found');
            }

            if ($variant->stock < $quantity) {
                throw new \Exception("Insufficient stock. Available: {$variant->stock}, Required: {$quantity}");
            }

            $oldStock = $variant->stock;
            $variant->stock -= $quantity;
            $variant->save();

            $this->createStockMovement([
                'variant_id' => $variantId,
                'movement_type' => 'out',
                'quantity' => $quantity,
                'old_stock' => $oldStock,
                'new_stock' => $variant->stock,
                'reference_type' => 'print_order',
                'reference_id' => $orderId,
                'reason' => $reason,
                'notes' => "Stock reduced for print order"
            ]);

            if ($variant->stock <= $variant->min_stock_threshold) {
                $this->triggerLowStockAlert($variant);
            }

            Log::info("Stock reduced for variant {$variantId}: {$oldStock} -> {$variant->stock} (Order: {$orderId})");

            return [
                'success' => true,
                'old_stock' => $oldStock,
                'new_stock' => $variant->stock,
                'reduced_quantity' => $quantity
            ];
        });
    }

    public function restoreStock($variantId, $quantity, $orderId, $reason = 'order_cancelled')
    {
        return DB::transaction(function() use ($variantId, $quantity, $orderId, $reason) {
            $variant = ProductVariant::lockForUpdate()->find($variantId);
            
            if (!$variant) {
                throw new \Exception('Product variant not found');
            }

            $oldStock = $variant->stock;
            $variant->stock += $quantity;
            $variant->save();

            $this->createStockMovement([
                'variant_id' => $variantId,
                'movement_type' => 'in',
                'quantity' => $quantity,
                'old_stock' => $oldStock,
                'new_stock' => $variant->stock,
                'reference_type' => 'print_order',
                'reference_id' => $orderId,
                'reason' => $reason,
                'notes' => "Stock restored from cancelled print order"
            ]);

            Log::info("Stock restored for variant {$variantId}: {$oldStock} -> {$variant->stock} (Order: {$orderId})");

            return [
                'success' => true,
                'old_stock' => $oldStock,
                'new_stock' => $variant->stock,
                'restored_quantity' => $quantity
            ];
        });
    }

    public function adjustStock($variantId, $newStock, $reason = 'manual_adjustment', $notes = null)
    {
        return DB::transaction(function() use ($variantId, $newStock, $reason, $notes) {
            $variant = ProductVariant::lockForUpdate()->find($variantId);
            
            if (!$variant) {
                throw new \Exception('Product variant not found');
            }

            $oldStock = $variant->stock;
            $difference = $newStock - $oldStock;
            $movementType = $difference > 0 ? 'in' : 'out';
            $quantity = abs($difference);

            $variant->stock = $newStock;
            $variant->save();

            if ($quantity > 0) {
                $this->createStockMovement([
                    'variant_id' => $variantId,
                    'movement_type' => $movementType,
                    'quantity' => $quantity,
                    'old_stock' => $oldStock,
                    'new_stock' => $newStock,
                    'reference_type' => 'manual',
                    'reference_id' => null,
                    'reason' => $reason,
                    'notes' => $notes ?: "Manual stock adjustment"
                ]);
            }

            Log::info("Stock adjusted for variant {$variantId}: {$oldStock} -> {$newStock}");

            return [
                'success' => true,
                'old_stock' => $oldStock,
                'new_stock' => $newStock,
                'difference' => $difference
            ];
        });
    }

    public function getLowStockVariants()
    {
        return ProductVariant::whereRaw('stock <= min_stock_threshold')
            ->with('product')
            ->get();
    }

    public function getStockReport($variantId = null, $dateFrom = null, $dateTo = null)
    {
        $query = StockMovement::with(['variant.product']);

        if ($variantId) {
            $query->where('variant_id', $variantId);
        }

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    private function createStockMovement($data)
    {
        return StockMovement::create([
            'variant_id' => $data['variant_id'],
            'movement_type' => $data['movement_type'],
            'quantity' => $data['quantity'],
            'old_stock' => $data['old_stock'],
            'new_stock' => $data['new_stock'],
            'reference_type' => $data['reference_type'],
            'reference_id' => $data['reference_id'],
            'reason' => $data['reason'],
            'notes' => $data['notes'],
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    private function triggerLowStockAlert($variant)
    {
        Log::warning("Low stock alert for variant {$variant->id}: {$variant->name} - Stock: {$variant->stock}, Threshold: {$variant->min_stock_threshold}");
        
    }

    public function getVariantsByStock($sortDirection = 'asc')
    {
        return ProductVariant::where('is_active', true)
            ->with('product')
            ->whereHas('product', function($query) {
                $query->where('is_print_service', true)
                      ->where('status', 1);
            })
            ->orderBy('stock', $sortDirection)
            ->get();
    }
    
    public function checkForDuplicateVariants()
    {
        $variants = ProductVariant::where('is_active', true)
            ->whereHas('product', function($query) {
                $query->where('is_print_service', true)
                      ->where('status', 1);
            })
            ->get();
            
        $duplicates = $variants->groupBy(function($variant) {
            return $variant->paper_size . '_' . $variant->print_type;
        })->filter(function($group) {
            return $group->count() > 1;
        });
            
        return $duplicates;
    }
    
    public function preventDuplicateVariants()
    {
        $duplicates = $this->checkForDuplicateVariants();
        
        if ($duplicates->count() > 0) {
            Log::warning('Duplicate variants detected in stock management', [
                'duplicates' => $duplicates->map(function($group, $key) {
                    return [
                        'combination' => $key,
                        'count' => $group->count(),
                        'variant_ids' => $group->pluck('id')->toArray()
                    ];
                })->toArray()
            ]);
            
            return false;
        }
        
        return true;
    }
}
