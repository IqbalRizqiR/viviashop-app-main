<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductInventory;
use App\Models\VariantAttribute;
use App\Services\ProductVariantService;
use App\Services\StockManagementService;

class TestPrintServiceFlow extends Command
{
    protected $signature = 'test:print-service-flow';
    protected $description = 'Test the complete print service product flow';
    private $productVariantService;
    private $testProduct;
    
    public function __construct()
    {
        parent::__construct();
        $this->productVariantService = app(ProductVariantService::class);
    }

    public function handle()
    {
        $this->info("=== Product Print Service Flow Test ===\n");
        
        try {
            DB::transaction(function () {
                $this->testSimpleProductCreation();
                $this->testSimpleToConfigurableConversion();
                $this->testConfigurableProductVariantAddition();
                $this->testStockManagement();
            });
            
            $this->info("✅ All tests completed successfully!");
            
        } catch (\Exception $e) {
            $this->error("❌ Test failed: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
        }
    }
    
    private function testSimpleProductCreation()
    {
        $this->info("1. Testing Simple Product Creation with Print Service...");
        
        $category = Category::first() ?: Category::create(['name' => 'Test Category']);
        $brand = Brand::first() ?: Brand::create(['name' => 'Test Brand', 'status' => 1]);
        
        $productData = [
            'name' => 'KERTAS VINYL TEST - ' . time(),
            'sku' => 'KVT-' . time(),
            'type' => 'simple',
            'price' => 2,
            'harga_beli' => 1,
            'weight' => 0.1,
            'category_id' => [$category->id],
            'brand_id' => $brand->id,
            'short_description' => 'Test vinyl paper',
            'description' => 'Test description',
            'is_print_service' => true,
            'is_smart_print_enabled' => true,
            'status' => 1
        ];
        
        $product = $this->productVariantService->createBaseProduct($productData);
        $product->categories()->sync($productData['category_id']);
        
        ProductInventory::create([
            'product_id' => $product->id,
            'qty' => 100,
        ]);
        
        $this->createDefaultSmartPrintVariants($product);
        
        $variants = $product->productVariants;
        
        if ($variants->count() !== 2) {
            throw new \Exception("Should create 2 default variants, got: " . $variants->count());
        }
        
        // Debug: Let's see what values are actually stored in database vs accessors
        $this->info("Variants created:");
        foreach ($variants as $variant) {
            // Get fresh data from database
            $dbRow = DB::table('product_variants')->where('id', $variant->id)->first();
            $this->info("- Variant ID: {$variant->id}, print_type DB: '{$dbRow->print_type}', print_type accessor: '{$variant->print_type}'");
        }
        
        // Use database query to find variants since accessors pull from variant_attributes table
        $bwVariantId = DB::table('product_variants')
            ->where('product_id', $product->id)
            ->where('print_type', 'bw')
            ->value('id');
            
        $colorVariantId = DB::table('product_variants')
            ->where('product_id', $product->id)
            ->where('print_type', 'color')
            ->value('id');
        
        if (!$bwVariantId) {
            $dbValues = DB::table('product_variants')
                ->where('product_id', $product->id)
                ->pluck('print_type')
                ->toArray();
            throw new \Exception("BW variant not found. Available print_types in DB: " . implode(', ', $dbValues));
        }
        
        if (!$colorVariantId) {
            $dbValues = DB::table('product_variants')
                ->where('product_id', $product->id)
                ->pluck('print_type')
                ->toArray();
            throw new \Exception("Color variant not found. Available print_types in DB: " . implode(', ', $dbValues));
        }
        
        // Get the actual variant models
        $bwVariant = $variants->where('id', $bwVariantId)->first();
        $colorVariant = $variants->where('id', $colorVariantId)->first();
        
        if ($bwVariant->price != 2) {
            throw new \Exception("BW variant price should inherit from parent (2), got: " . $bwVariant->price);
        }
        
        if ($colorVariant->price != 2) {
            throw new \Exception("Color variant price should inherit from parent (2), got: " . $colorVariant->price);
        }
        
        if ($bwVariant->harga_beli != 1) {
            throw new \Exception("BW variant cost should inherit from parent (1), got: " . $bwVariant->harga_beli);
        }
        
        if ($colorVariant->harga_beli != 1) {
            throw new \Exception("Color variant cost should inherit from parent (1), got: " . $colorVariant->harga_beli);
        }
        
        if ($bwVariant->stock != 100) {
            throw new \Exception("BW variant stock should inherit from parent (100), got: " . $bwVariant->stock);
        }
        
        if ($colorVariant->stock != 100) {
            throw new \Exception("Color variant stock should inherit from parent (100), got: " . $colorVariant->stock);
        }
        
        if ($bwVariant->paper_size !== 'A4') {
            throw new \Exception("BW variant paper_size should be A4, got: " . $bwVariant->paper_size);
        }
        
        if ($colorVariant->paper_size !== 'A4') {
            throw new \Exception("Color variant paper_size should be A4, got: " . $colorVariant->paper_size);
        }
        
        $bwAttributes = $bwVariant->variantAttributes->keyBy('attribute_name');
        $colorAttributes = $colorVariant->variantAttributes->keyBy('attribute_name');
        
        if (!$bwAttributes->has('paper_size')) {
            throw new \Exception("BW variant should have paper_size attribute");
        }
        
        if (!$bwAttributes->has('print_type')) {
            throw new \Exception("BW variant should have print_type attribute");
        }
        
        if (!$colorAttributes->has('paper_size')) {
            throw new \Exception("Color variant should have paper_size attribute");
        }
        
        if (!$colorAttributes->has('print_type')) {
            throw new \Exception("Color variant should have print_type attribute");
        }
        
        $this->info("   ✅ Simple product created with correct default variants");
        $this->info("   ✅ Variant prices inherit from parent product");
        $this->info("   ✅ Variant attributes are properly formatted");
        
        $this->testProduct = $product;
    }
    
    private function testSimpleToConfigurableConversion()
    {
        $this->info("\n2. Testing Simple to Configurable Product Conversion...");
        
        $product = $this->testProduct;
        $product->update(['type' => 'configurable']);
        
        $variants = $product->fresh()->productVariants;
        if ($variants->count() !== 2) {
            throw new \Exception("Should maintain existing variants after conversion, got: " . $variants->count());
        }
        
        $this->info("   ✅ Product type converted to configurable");
        $this->info("   ✅ Existing variants preserved");
    }
    
    private function testConfigurableProductVariantAddition()
    {
        $this->info("\n3. Testing Addition of New Variant to Configurable Product...");
        
        $product = $this->testProduct;
        
        $newVariantData = [
            'name' => $product->name . ' - A3 Color',
            'sku' => $product->sku . '-A3-CLR',
            'price' => 2,
            'harga_beli' => 1,
            'stock' => 100,
            'weight' => 0.1,
            'is_active' => true,
        ];
        
        $newVariantAttributes = [
            ['attribute_name' => 'paper_size', 'attribute_value' => 'A3'],
            ['attribute_name' => 'print_type', 'attribute_value' => 'Color']
        ];
        
        $newVariant = $this->productVariantService->createVariant($product, $newVariantData, $newVariantAttributes);
        
        if ($newVariant->paper_size !== 'A3') {
            throw new \Exception("New variant paper_size should be A3, got: " . $newVariant->paper_size);
        }
        
        // Check database value for print_type, not accessor value
        $dbRow = DB::table('product_variants')->where('id', $newVariant->id)->first();
        if ($dbRow->print_type !== 'color') {
            throw new \Exception("New variant print_type in DB should be 'color', got: " . $dbRow->print_type . " (accessor returns: " . $newVariant->print_type . ")");
        }
        
        $attributes = $newVariant->variantAttributes->keyBy('attribute_name');
        if (!$attributes->has('paper_size')) {
            throw new \Exception("New variant should have paper_size attribute");
        }
        
        if (!$attributes->has('print_type')) {
            throw new \Exception("New variant should have print_type attribute");
        }
        
        if ($attributes->get('paper_size')->attribute_value !== 'A3') {
            throw new \Exception("paper_size attribute should be A3, got: " . $attributes->get('paper_size')->attribute_value);
        }
        
        if ($attributes->get('print_type')->attribute_value !== 'Color') {
            throw new \Exception("print_type attribute should be Color, got: " . $attributes->get('print_type')->attribute_value);
        }
        
        $this->info("   ✅ New variant added to configurable product");
        $this->info("   ✅ New variant has proper paper_size and print_type columns");
        $this->info("   ✅ New variant has proper variant attributes");
        
        $this->info("\n3b. Testing Edge Case: Wrong Attribute Names in UI...");
        
        $edgeCaseVariantData = [
            'name' => $product->name . ' - A4 BW Test',
            'sku' => $product->sku . '-A4-BW-TEST',
            'price' => 1.5,
            'harga_beli' => 0.8,
            'stock' => 75,
            'weight' => 0.1,
            'is_active' => true,
        ];
        
        $edgeCaseAttributes = [
            ['attribute_name' => 'A4', 'attribute_value' => 'Black & White']
        ];
        
        $edgeCaseVariant = $this->productVariantService->createVariant($product, $edgeCaseVariantData, $edgeCaseAttributes);
        
        if (empty($edgeCaseVariant->paper_size) && empty($edgeCaseVariant->print_type)) {
            throw new \Exception("Edge case variant should have normalized paper_size and print_type based on attribute parsing");
        }
        
        $edgeAttributes = $edgeCaseVariant->variantAttributes->keyBy('attribute_name');
        if ($edgeAttributes->count() === 1) {
            throw new \Exception("Edge case should have been normalized to proper paper_size and print_type attributes");
        }
        
        $this->info("   ✅ Edge case handling works for variants with wrong attribute format");
        
        $allVariants = $product->fresh()->productVariants;
        if ($allVariants->count() < 4) {
            throw new \Exception("Should have at least 4 variants total, got: " . $allVariants->count());
        }
    }
    
    private function testStockManagement()
    {
        $this->info("\n4. Testing Stock Management Service...");
        
        $stockService = app(StockManagementService::class);
        $variants = $stockService->getVariantsByStock();
        
        $testVariants = $variants->where('product_id', $this->testProduct->id);
        if ($testVariants->count() < 3) {
            throw new \Exception("Should find at least 3 test product variants in stock management, got: " . $testVariants->count());
        }
        
        foreach ($testVariants as $variant) {
            if (empty($variant->paper_size)) {
                throw new \Exception("Variant should have paper_size. Variant data: " . json_encode($variant->toArray()));
            }
            
            if (empty($variant->print_type)) {
                throw new \Exception("Variant should have print_type. Variant data: " . json_encode($variant->toArray()));
            }
        }
        
        $this->info("   ✅ Stock management service retrieves variants correctly");
        $this->info("   ✅ All variants have proper paper_size and print_type values");
        
        $variant = $testVariants->first();
        $originalStock = $variant->stock;
        $newStock = $originalStock + 50;
        
        $result = $stockService->adjustStock($variant->id, $newStock, 'restock', 'Test stock adjustment');
        
        if ($result['success'] !== true) {
            throw new \Exception("Stock adjustment should succeed");
        }
        
        if ($result['new_stock'] !== $newStock) {
            throw new \Exception("New stock should be " . $newStock . ", got: " . $result['new_stock']);
        }
        
        $updatedVariant = \App\Models\ProductVariant::find($variant->id);
        if ($updatedVariant->stock !== $newStock) {
            throw new \Exception("Variant stock should be updated in database to " . $newStock . ", got: " . $updatedVariant->stock);
        }
        
        $this->info("   ✅ Stock adjustment functionality works correctly");
    }
    
    private function createDefaultSmartPrintVariants(Product $product)
    {
        $basePrice = $product->price ?? 2000;
        $baseCost = $product->harga_beli ?? 1000;
        $baseStock = $product->productInventory ? $product->productInventory->qty : 100;
        $baseWeight = $product->weight ?? 0.1;
        $baseLength = $product->length ?? 0;
        $baseWidth = $product->width ?? 0;
        $baseHeight = $product->height ?? 0;
        
        $defaultVariants = [
            [
                'name' => $product->name . ' - Black & White',
                'sku' => $product->sku . '-BW',
                'paper_size' => 'A4',
                'print_type' => 'bw',
                'stock' => $baseStock,
                'price' => $basePrice,
                'harga_beli' => $baseCost,
                'weight' => $baseWeight,
                'length' => $baseLength,
                'width' => $baseWidth,
                'height' => $baseHeight,
                'attributes' => [
                    'print_type' => 'Black & White',
                    'paper_size' => 'A4'
                ]
            ],
            [
                'name' => $product->name . ' - Color',
                'sku' => $product->sku . '-CLR',
                'paper_size' => 'A4', 
                'print_type' => 'color',
                'stock' => $baseStock,
                'price' => $basePrice, // Same price as parent, not multiplied
                'harga_beli' => $baseCost, // Same cost as parent, not multiplied
                'weight' => $baseWeight,
                'length' => $baseLength,
                'width' => $baseWidth,
                'height' => $baseHeight,
                'attributes' => [
                    'print_type' => 'Color',
                    'paper_size' => 'A4'
                ]
            ]
        ];
        
        foreach ($defaultVariants as $variantData) {
            $variant = \App\Models\ProductVariant::create([
                'product_id' => $product->id,
                'sku' => $variantData['sku'],
                'name' => $variantData['name'],
                'price' => $variantData['price'],
                'harga_beli' => $variantData['harga_beli'],
                'stock' => $variantData['stock'],
                'weight' => $variantData['weight'],
                'length' => $variantData['length'],
                'width' => $variantData['width'],
                'height' => $variantData['height'],
                'print_type' => $variantData['print_type'], // Include in initial creation
                'paper_size' => $variantData['paper_size'], // Include in initial creation
                'is_active' => true,
                'min_stock_threshold' => max(1, $variantData['stock'] * 0.1),
            ]);

            foreach ($variantData['attributes'] as $attrName => $attrValue) {
                \App\Models\VariantAttribute::create([
                    'variant_id' => $variant->id,
                    'attribute_name' => $attrName,
                    'attribute_value' => $attrValue,
                    'sort_order' => 0
                ]);
            }
        }
    }
}
