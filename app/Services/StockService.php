<?php

namespace App\Services;

use App\Models\StockMovement;
use App\Models\ProductVariant;
use App\Models\Product;
use App\Models\ProductInventory;
use Illuminate\Support\Facades\DB;

class StockService
{
    public static function recordMovement($variantId, $movementType, $quantity, $referenceType, $referenceId, $reason, $notes = null)
    {
        return DB::transaction(function () use ($variantId, $movementType, $quantity, $referenceType, $referenceId, $reason, $notes) {
            $variant = ProductVariant::findOrFail($variantId);
            $oldStock = $variant->stock;
            
            if ($movementType === StockMovement::MOVEMENT_IN) {
                $newStock = $oldStock + $quantity;
            } else {
                $newStock = max(0, $oldStock - $quantity);
            }
            
            $variant->update(['stock' => $newStock]);
            
            $variant->product->updateBasePrice();
            
            return StockMovement::create([
                'variant_id' => $variantId,
                'movement_type' => $movementType,
                'quantity' => $quantity,
                'old_stock' => $oldStock,
                'new_stock' => $newStock,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'reason' => $reason,
                'notes' => $notes
            ]);
        });
    }

    public static function recordSimpleProductMovement($productId, $movementType, $quantity, $referenceType, $referenceId, $reason, $notes = null)
    {
        return DB::transaction(function () use ($productId, $movementType, $quantity, $referenceType, $referenceId, $reason, $notes) {
            $product = Product::with(['productInventory', 'productVariants'])->findOrFail($productId);
            
            if (!$product->productInventory) {
                ProductInventory::create([
                    'product_id' => $productId,
                    'qty' => 0
                ]);
                $product->refresh();
            }
            
            $oldStock = $product->productInventory->qty;
            
            if ($movementType === StockMovement::MOVEMENT_IN) {
                $newStock = $oldStock + $quantity;
            } else {
                $newStock = max(0, $oldStock - $quantity);
            }
            
            // Update ProductInventory
            $product->productInventory->update(['qty' => $newStock]);
            
            // Get or create default variant for simple products
            $variant = $product->productVariants()->first();
            
            if (!$variant && $product->type === 'simple') {
                // Create default variant for simple product
                $variant = ProductVariant::create([
                    'product_id' => $productId,
                    'sku' => $product->sku ?? "VAR-{$productId}-DEFAULT",
                    'name' => $product->name . ' (Default)',
                    'price' => $product->price ?? 0,
                    'harga_beli' => $product->harga_beli ?? 0,
                    'stock' => $newStock
                ]);
            } elseif ($variant) {
                // CRITICAL: Keep ProductInventory and ProductVariant in sync!
                $variant->update(['stock' => $newStock]);
            }
            
            // Record the movement directly to avoid double updates
            if ($variant) {
                return StockMovement::create([
                    'variant_id' => $variant->id,
                    'movement_type' => $movementType,
                    'quantity' => $quantity,
                    'old_stock' => $oldStock,
                    'new_stock' => $newStock,
                    'reference_type' => $referenceType,
                    'reference_id' => $referenceId,
                    'reason' => $reason,
                    'notes' => $notes
                ]);
            }
            
            return null;
        });
    }

    public static function processPurchaseStockUpdate($pembelian)
    {
        $movements = [];
        
        foreach ($pembelian->details as $detail) {
            if ($detail->variant_id) {
                $movement = self::recordMovement(
                    $detail->variant_id,
                    StockMovement::MOVEMENT_IN,
                    $detail->jumlah,
                    'purchase',
                    $pembelian->id,
                    StockMovement::REASON_PURCHASE_CONFIRMED,
                    "Purchase from supplier: {$pembelian->supplier->nama}"
                );
            } else {
                $movement = self::recordSimpleProductMovement(
                    $detail->id_produk,
                    StockMovement::MOVEMENT_IN,
                    $detail->jumlah,
                    'purchase',
                    $pembelian->id,
                    StockMovement::REASON_PURCHASE_CONFIRMED,
                    "Purchase from supplier: {$pembelian->supplier->nama}"
                );
            }
            
            if ($movement) {
                $movements[] = $movement;
            }
        }
        
        return $movements;
    }

    public static function reversePurchaseStockUpdate($pembelian)
    {
        $movements = [];
        
        foreach ($pembelian->details as $detail) {
            if ($detail->variant_id) {
                $movement = self::recordMovement(
                    $detail->variant_id,
                    StockMovement::MOVEMENT_OUT,
                    $detail->jumlah,
                    'purchase',
                    $pembelian->id,
                    StockMovement::REASON_PURCHASE_CANCELLED,
                    "Purchase cancelled from supplier: {$pembelian->supplier->nama}"
                );
            } else {
                $movement = self::recordSimpleProductMovement(
                    $detail->id_produk,
                    StockMovement::MOVEMENT_OUT,
                    $detail->jumlah,
                    'purchase',
                    $pembelian->id,
                    StockMovement::REASON_PURCHASE_CANCELLED,
                    "Purchase cancelled from supplier: {$pembelian->supplier->nama}"
                );
            }
            
            if ($movement) {
                $movements[] = $movement;
            }
        }
        
        return $movements;
    }

    /**
     * Ensure ProductInventory and ProductVariant stock are synchronized
     * This method should be called periodically or after major operations
     */
    public static function synchronizeStockTables()
    {
        return DB::transaction(function () {
            $inconsistencies = DB::select("
                SELECT 
                    p.id as product_id,
                    pi.qty as inventory_qty,
                    pv.id as variant_id,
                    pv.stock as variant_stock
                FROM products p
                LEFT JOIN product_inventories pi ON p.id = pi.product_id
                LEFT JOIN product_variants pv ON p.id = pv.product_id
                WHERE pi.qty IS NOT NULL 
                AND pv.stock IS NOT NULL 
                AND pi.qty != pv.stock
            ");
            
            $syncedCount = 0;
            
            foreach ($inconsistencies as $item) {
                // Use latest stock movement as source of truth
                $latestMovement = DB::table('stock_movements')
                    ->where('variant_id', $item->variant_id)
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                $correctStock = $latestMovement ? $latestMovement->new_stock : max($item->inventory_qty, $item->variant_stock);
                
                // Update both tables
                DB::table('product_inventories')
                    ->where('product_id', $item->product_id)
                    ->update(['qty' => $correctStock]);
                
                DB::table('product_variants')
                    ->where('id', $item->variant_id)
                    ->update(['stock' => $correctStock]);
                
                // Record the sync operation
                if ($correctStock != $item->variant_stock) {
                    $movementType = ($correctStock > $item->variant_stock) ? StockMovement::MOVEMENT_IN : StockMovement::MOVEMENT_OUT;
                    $quantity = abs($correctStock - $item->variant_stock);
                    
                    StockMovement::create([
                        'variant_id' => $item->variant_id,
                        'movement_type' => $movementType,
                        'quantity' => $quantity,
                        'old_stock' => $item->variant_stock,
                        'new_stock' => $correctStock,
                        'reference_type' => 'system',
                        'reference_id' => null,
                        'reason' => StockMovement::REASON_STOCK_SYNCHRONIZATION,
                        'notes' => "Automatic synchronization: Inventory({$item->inventory_qty}) + Variant({$item->variant_stock}) = Correct({$correctStock})"
                    ]);
                }
                
                $syncedCount++;
            }
            
            return [
                'total_inconsistencies' => count($inconsistencies),
                'synced_count' => $syncedCount,
                'status' => count($inconsistencies) === 0 ? 'all_synced' : 'synced'
            ];
        });
    }

    /**
     * Validate stock consistency for a specific product
     */
    public static function validateStockConsistency($productId)
    {
        $product = Product::with(['productInventory', 'productVariants'])->find($productId);
        
        if (!$product || !$product->productInventory) {
            return ['status' => 'error', 'message' => 'Product or inventory not found'];
        }
        
        $variant = $product->productVariants()->first();
        
        if (!$variant) {
            return ['status' => 'error', 'message' => 'Product variant not found'];
        }
        
        $inventoryQty = $product->productInventory->qty;
        $variantStock = $variant->stock;
        
        if ($inventoryQty === $variantStock) {
            return [
                'status' => 'consistent',
                'inventory_qty' => $inventoryQty,
                'variant_stock' => $variantStock
            ];
        } else {
            return [
                'status' => 'inconsistent',
                'inventory_qty' => $inventoryQty,
                'variant_stock' => $variantStock,
                'difference' => $inventoryQty - $variantStock
            ];
        }
    }

    /**
     * Reduce stock for a variant
     */
    public function reduceStock($variantId, $quantity, $referenceId, $reason, $notes = null)
    {
        return self::recordMovement(
            $variantId,
            StockMovement::MOVEMENT_OUT,
            $quantity,
            'print_order',
            $referenceId,
            $reason,
            $notes
        );
    }

    /**
     * Restore stock for a variant
     */
    public function restoreStock($variantId, $quantity, $referenceId, $reason, $notes = null)
    {
        return self::recordMovement(
            $variantId,
            StockMovement::MOVEMENT_IN,
            $quantity,
            'print_order',
            $referenceId,
            $reason,
            $notes
        );
    }

    /**
     * Check stock availability
     */
    public function checkStockAvailability($variantId, $requiredQuantity)
    {
        $variant = ProductVariant::find($variantId);
        
        if (!$variant) {
            return [
                'available' => false,
                'message' => 'Variant not found'
            ];
        }

        if ($variant->stock >= $requiredQuantity) {
            return [
                'available' => true,
                'current_stock' => $variant->stock,
                'required' => $requiredQuantity
            ];
        } else {
            return [
                'available' => false,
                'message' => "Not enough stock. Available: {$variant->stock}, Required: {$requiredQuantity}",
                'current_stock' => $variant->stock,
                'required' => $requiredQuantity
            ];
        }
    }
}