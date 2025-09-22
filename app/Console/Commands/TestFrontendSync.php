<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\ProductVariant;

class TestFrontendSync extends Command
{
    protected $signature = 'test:frontend-sync';
    protected $description = 'Test frontend synchronization with flexible variants';

    public function handle()
    {
        $this->info('Testing frontend synchronization...');
        
        $product = Product::with(['activeVariants.variantAttributes'])
            ->where('is_print_service', true)
            ->first();
            
        if (!$product) {
            $this->error('No print service product found');
            return 1;
        }
        
        $this->info("Testing product: {$product->name}");
        $this->info("Product type: {$product->type}");
        
        $variants = $product->activeVariants;
        $this->info("Active variants count: {$variants->count()}");
        
        foreach ($variants as $variant) {
            $this->info("--- Variant: {$variant->name} ---");
            $this->info("SKU: {$variant->sku}");
            $this->info("Price: {$variant->price}");
            $this->info("Stock: {$variant->stock}");
            $this->info("Paper Size: {$variant->paper_size}");
            $this->info("Print Type: {$variant->print_type}");
            
            $attributes = $variant->variantAttributes;
            $this->info("Attributes count: {$attributes->count()}");
            foreach ($attributes as $attr) {
                $this->info("  - {$attr->attribute_name}: {$attr->attribute_value}");
            }
            $this->info('');
        }
        
        try {
            $variantOptions = $product->getVariantOptions();
            $this->info("Variant options method works: " . ($variantOptions ? 'Yes' : 'No'));
            
            if ($variantOptions && $variantOptions->count() > 0) {
                $this->info("Variant options:");
                foreach ($variantOptions as $option) {
                    $this->info("  Option: " . json_encode($option));
                }
            }
            
        } catch (\Exception $e) {
            $this->warn("getVariantOptions() error: {$e->getMessage()}");
        }
        
        $this->info('');
        $this->info('Testing getAllVariants API format...');
        
        $apiVariants = $variants->map(function($variant) {
            return [
                'id' => $variant->id,
                'sku' => $variant->sku,
                'name' => $variant->name,
                'price' => $variant->price,
                'formatted_price' => number_format($variant->price, 0, ',', '.'),
                'stock' => $variant->stock,
                'weight' => $variant->weight ?? 0,
                'variant_attributes' => $variant->variantAttributes->map(function($attr) {
                    return [
                        'attribute_name' => $attr->attribute_name,
                        'attribute_value' => $attr->attribute_value
                    ];
                })->toArray()
            ];
        });
        
        $this->info("API format variants:");
        foreach ($apiVariants as $variant) {
            $this->info("- {$variant['name']} (Stock: {$variant['stock']})");
            foreach ($variant['variant_attributes'] as $attr) {
                $this->info("    {$attr['attribute_name']}: {$attr['attribute_value']}");
            }
        }
        
        $this->info('✅ Frontend synchronization test completed successfully!');
        return 0;
    }
}
