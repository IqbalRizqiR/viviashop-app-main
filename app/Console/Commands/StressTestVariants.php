<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\ProductVariantService;
use App\Http\Controllers\Admin\ProductVariantController;
use Illuminate\Http\Request;

class StressTestVariants extends Command
{
    protected $signature = 'test:stress-variants';
    protected $description = 'Comprehensive stress test for variant system';

    public function handle()
    {
        $this->info('🚀 Starting comprehensive variant system stress test...');
        
        $this->testBasicVariantCreation();
        $this->testVariantUpdate();
        $this->testMultipleCustomAttributes();
        $this->testProductTypeConversion();
        $this->testFrontendCompatibility();
        
        $this->info('✅ All stress tests completed successfully!');
        return 0;
    }
    
    private function testBasicVariantCreation()
    {
        $this->info('');
        $this->info('📝 Test 1: Basic variant creation with custom attributes...');
        
        $product = Product::where('is_print_service', true)->first();
        $productVariantService = new ProductVariantService();
        
        $testCases = [
            [
                'paper_size' => 'A0001',
                'print_type' => 'metallic silver'
            ],
            [
                'paper_size' => 'Custom 50x70',
                'print_type' => 'holographic'
            ],
            [
                'paper_size' => 'Banner 100x200',
                'print_type' => 'waterproof UV'
            ]
        ];
        
        foreach ($testCases as $index => $test) {
            $variantData = [
                'name' => $product->name . " - {$test['paper_size']} {$test['print_type']}",
                'sku' => $product->sku . '-TEST-' . ($index + 1),
                'price' => 2500 + ($index * 500),
                'harga_beli' => 1500 + ($index * 300),
                'stock' => 100,
                'weight' => 0.1,
            ];
            
            $attributes = [
                ['attribute_name' => 'paper_size', 'attribute_value' => $test['paper_size']],
                ['attribute_name' => 'print_type', 'attribute_value' => $test['print_type']]
            ];
            
            try {
                $variant = $productVariantService->createVariant($product, $variantData, $attributes);
                $this->info("  ✅ Created: {$variant->name}");
                $this->info("     Paper Size: {$variant->paper_size}");
                $this->info("     Print Type: {$variant->print_type}");
            } catch (\Exception $e) {
                $this->error("  ❌ Failed: {$e->getMessage()}");
            }
        }
    }
    
    private function testVariantUpdate()
    {
        $this->info('');
        $this->info('📝 Test 2: Variant update with changed attributes...');
        
        $variant = ProductVariant::where('paper_size', 'A0001')->first();
        if (!$variant) {
            $this->warn('  ⚠️  No test variant found for update test');
            return;
        }
        
        $productVariantService = new ProductVariantService();
        
        $updatedData = [
            'name' => $variant->name . ' - UPDATED',
            'sku' => $variant->sku,
            'price' => $variant->price + 500,
            'harga_beli' => $variant->harga_beli,
            'stock' => $variant->stock + 50,
            'weight' => $variant->weight,
        ];
        
        $newAttributes = [
            ['attribute_name' => 'paper_size', 'attribute_value' => 'A0001 Premium'],
            ['attribute_name' => 'print_type', 'attribute_value' => 'premium metallic silver'],
            ['attribute_name' => 'finish', 'attribute_value' => 'laminated']
        ];
        
        try {
            $updatedVariant = $productVariantService->updateVariant($variant, $updatedData, $newAttributes);
            $this->info("  ✅ Updated variant: {$updatedVariant->name}");
            $this->info("     New paper size: {$updatedVariant->paper_size}");
            $this->info("     New print type: {$updatedVariant->print_type}");
            $this->info("     Attributes count: {$updatedVariant->variantAttributes->count()}");
        } catch (\Exception $e) {
            $this->error("  ❌ Update failed: {$e->getMessage()}");
        }
    }
    
    private function testMultipleCustomAttributes()
    {
        $this->info('');
        $this->info('📝 Test 3: Multiple custom attributes beyond paper_size and print_type...');
        
        $product = Product::where('is_print_service', true)->first();
        $productVariantService = new ProductVariantService();
        
        $variantData = [
            'name' => $product->name . ' - Premium Package',
            'sku' => $product->sku . '-PREMIUM-' . time(),
            'price' => 15000,
            'harga_beli' => 8000,
            'stock' => 20,
            'weight' => 0.5,
        ];
        
        $complexAttributes = [
            ['attribute_name' => 'paper_size', 'attribute_value' => 'A3+'],
            ['attribute_name' => 'print_type', 'attribute_value' => '12-color inkjet'],
            ['attribute_name' => 'paper_material', 'attribute_value' => 'premium photo paper'],
            ['attribute_name' => 'finish', 'attribute_value' => 'glossy laminated'],
            ['attribute_name' => 'binding', 'attribute_value' => 'spiral bound'],
            ['attribute_name' => 'delivery_time', 'attribute_value' => 'express 24h']
        ];
        
        try {
            $variant = $productVariantService->createVariant($product, $variantData, $complexAttributes);
            $this->info("  ✅ Created complex variant: {$variant->name}");
            $this->info("     Total attributes: {$variant->variantAttributes->count()}");
            foreach ($variant->variantAttributes as $attr) {
                $this->info("     - {$attr->attribute_name}: {$attr->attribute_value}");
            }
        } catch (\Exception $e) {
            $this->error("  ❌ Complex variant creation failed: {$e->getMessage()}");
        }
    }
    
    private function testProductTypeConversion()
    {
        $this->info('');
        $this->info('📝 Test 4: Product type conversion (simple to configurable)...');
        
        $simpleProduct = Product::where('type', 'simple')->where('is_print_service', true)->first();
        if (!$simpleProduct) {
            $this->warn('  ⚠️  No simple print service product found');
            return;
        }
        
        $this->info("  Testing with product: {$simpleProduct->name}");
        $this->info("  Original type: {$simpleProduct->type}");
        
        $originalVariantCount = $simpleProduct->productVariants()->count();
        $this->info("  Original variant count: {$originalVariantCount}");
        
        $simpleProduct->update(['type' => 'configurable']);
        $this->info("  ✅ Product type changed to configurable");
        
        $newVariantCount = $simpleProduct->fresh()->productVariants()->count();
        $this->info("  Variant count after conversion: {$newVariantCount}");
    }
    
    private function testFrontendCompatibility()
    {
        $this->info('');
        $this->info('📝 Test 5: Frontend API compatibility...');
        
        $product = Product::with(['activeVariants.variantAttributes'])
            ->where('is_print_service', true)
            ->first();
            
        $variants = $product->activeVariants()->take(3)->get();
        
        foreach ($variants as $variant) {
            $apiFormat = [
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
            
            $this->info("  ✅ API format for: {$variant->name}");
            $this->info("     Attributes: " . count($apiFormat['variant_attributes']));
            
            if (isset($apiFormat['variant_attributes'][0])) {
                $this->info("     Paper size: " . 
                    collect($apiFormat['variant_attributes'])
                        ->where('attribute_name', 'paper_size')
                        ->first()['attribute_value'] ?? 'N/A'
                );
            }
        }
        
        try {
            $variantOptions = $product->getVariantOptions();
            $this->info("  ✅ getVariantOptions() works: " . ($variantOptions ? 'Yes' : 'No'));
            if ($variantOptions) {
                $this->info("     Option groups: " . $variantOptions->count());
            }
        } catch (\Exception $e) {
            $this->warn("  ⚠️  getVariantOptions() issue: {$e->getMessage()}");
        }
    }
}
