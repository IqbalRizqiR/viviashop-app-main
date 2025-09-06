<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class TestAdminEdit extends Command
{
    protected $signature = 'test:admin-edit {product_id}';
    protected $description = 'Test admin product edit functionality';

    public function handle()
    {
        $productId = $this->argument('product_id');
        
        try {
            $this->info("Testing admin edit for product ID: {$productId}");
            
            $product = Product::with(['brand', 'productVariants.variantAttributes', 'categories'])->findOrFail($productId);
            $categories = Category::orderBy('name', 'ASC')->get(['name','id']);
            $brands = Brand::active()->orderBy('name', 'ASC')->get(['name','id']);
            $statuses = Product::statuses();
            $types = Product::types();
            $variantOptions = [];
            
            $this->info("✓ Product loaded: {$product->name}");
            $this->info("✓ Categories loaded: {$categories->count()}");
            $this->info("✓ Brands loaded: {$brands->count()}");
            $this->info("✓ Product type: {$product->type}");
            
            if ($product->type === 'configurable') {
                $variantCount = $product->productVariants ? $product->productVariants->count() : 0;
                $this->info("✓ Product variants: {$variantCount}");
                
                if ($variantCount > 0) {
                    $variantOptions = $product->getVariantOptions();
                    $this->info("✓ Variant options generated successfully");
                    foreach ($variantOptions as $attrName => $values) {
                        $this->info("  - {$attrName}: " . implode(', ', $values));
                    }
                }
            }
            
            $configurable_attributes = collect();
            $selected_attributes = [];
            
            $this->info("✓ All data preparation successful");
            
            $view = view('admin.products.edit', compact(
                'product', 
                'categories', 
                'brands', 
                'statuses', 
                'types', 
                'variantOptions',
                'configurable_attributes',
                'selected_attributes'
            ))->render();
            
            $this->info("✓ View rendered successfully");
            $this->info("✓ View length: " . strlen($view) . " characters");
            $this->info("✅ Admin edit test PASSED - No syntax errors found!");
            
        } catch (\Exception $e) {
            $this->error("❌ Test FAILED");
            $this->error("Error: " . $e->getMessage());
            $this->error("File: " . $e->getFile());
            $this->error("Line: " . $e->getLine());
            return 1;
        }
        
        return 0;
    }
}
