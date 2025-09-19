<?php

namespace App\Services;

use App\Models\ProductVariant;
use App\Models\Product;

class SmartPrintVariantService
{
    /**
     * Auto-detect and fix paper_size and print_type for existing variants
     * Only for products that are in Stock Management Print Service (is_print_service = true)
     */
    public function autoFixPrintServiceVariants()
    {
        $results = [
            'fixed' => 0,
            'skipped' => 0,
            'details' => []
        ];

        // Get all variants from print service products that have NULL paper_size or print_type
        // Using same filter as StockManagementService
        $variants = ProductVariant::where('is_active', true)
            ->whereHas('product', function($query) {
                $query->where('is_print_service', true)
                      ->where('status', 1);
            })
            ->where(function($query) {
                $query->whereNull('paper_size')->orWhereNull('print_type');
            })
            ->with('product')
            ->get();

        foreach($variants as $variant) {
            $fixed = $this->detectAndSetPrintFields($variant);
            
            if($fixed) {
                $results['fixed']++;
                $results['details'][] = "Fixed: {$variant->product->name} → {$variant->name} - Paper Size: {$variant->paper_size}, Print Type: {$variant->print_type}";
            } else {
                $results['skipped']++;
                $results['details'][] = "Skipped: {$variant->product->name} → {$variant->name} - Could not auto-detect";
            }
        }

        return $results;
    }

    /**
     * Auto-detect paper_size and print_type from variant name
     */
    public function detectAndSetPrintFields(ProductVariant $variant)
    {
        $name = strtolower($variant->name);
        $fixed = false;

        // Auto-detect paper_size
        if(!$variant->paper_size) {
            if(strpos($name, 'a4') !== false) {
                $variant->paper_size = 'A4';
                $fixed = true;
            } elseif(strpos($name, 'a3') !== false) {
                $variant->paper_size = 'A3';
                $fixed = true;
            } elseif(strpos($name, 'f4') !== false) {
                $variant->paper_size = 'F4';
                $fixed = true;
            } else {
                // Default to A4 if no size detected
                $variant->paper_size = 'A4';
                $fixed = true;
            }
        }

        // Auto-detect print_type
        if(!$variant->print_type) {
            if(strpos($name, 'black') !== false || 
               strpos($name, 'white') !== false || 
               strpos($name, 'bw') !== false ||
               strpos($name, 'hitam') !== false ||
               strpos($name, 'putih') !== false) {
                $variant->print_type = 'bw';
                $fixed = true;
            } elseif(strpos($name, 'color') !== false || 
                     strpos($name, 'colour') !== false ||
                     strpos($name, 'warna') !== false) {
                $variant->print_type = 'color';
                $fixed = true;
            } else {
                // Default to bw if no type detected
                $variant->print_type = 'bw';
                $fixed = true;
            }
        }

        if($fixed) {
            $variant->save();
        }

        return $fixed;
    }

    /**
     * Create smart print variants for a product (like the simple product auto-creation)
     */
    public function createSmartPrintVariants(Product $product, $basePrice = 2000)
    {
        $variants = [];
        
        // Create Black & White variant
        $bwVariant = new ProductVariant([
            'product_id' => $product->id,
            'name' => $product->name . ' - Black & White',
            'sku' => $this->generateSKU($product->name . ' BW'),
            'barcode' => $this->generateBarcode($product->id . 'BW'),
            'paper_size' => 'A4',
            'print_type' => 'bw',
            'price' => $basePrice,
            'stock' => 100,
            'is_active' => true
        ]);
        $bwVariant->save();
        $variants[] = $bwVariant;

        // Create Color variant
        $colorVariant = new ProductVariant([
            'product_id' => $product->id,
            'name' => $product->name . ' - Color',
            'sku' => $this->generateSKU($product->name . ' COLOR'),
            'barcode' => $this->generateBarcode($product->id . 'COLOR'),
            'paper_size' => 'A4',
            'print_type' => 'color',
            'price' => $basePrice * 2.5, // Color is usually more expensive
            'stock' => 50,
            'is_active' => true
        ]);
        $colorVariant->save();
        $variants[] = $colorVariant;

        return $variants;
    }

    private function generateSKU($name)
    {
        $sku = strtoupper(str_replace([' ', '-'], '', $name));
        $sku = substr($sku, 0, 15);
        
        // Add random suffix to avoid duplicates
        $sku .= '-' . strtoupper(substr(md5(time() . $name), 0, 6));
        
        return $sku;
    }

    private function generateBarcode($base)
    {
        return strtoupper(substr(md5($base . time()), 0, 12));
    }
}