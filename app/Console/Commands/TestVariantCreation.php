<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\ProductVariantService;

class TestVariantCreation extends Command
{
    protected $signature = 'test:variant-creation';
    protected $description = 'Test variant creation with flexible attributes';

    public function handle()
    {
        $this->info('Testing flexible variant creation...');
        
        $product = Product::where('is_print_service', true)->first();
        if (!$product) {
            $product = Product::create([
                'name' => 'Test Print Service',
                'sku' => 'TEST-PRINT-' . time(),
                'price' => 2000,
                'is_print_service' => true,
                'type' => 'simple',
                'description' => 'Test print service product',
                'status' => 'active',
                'user_id' => 1
            ]);
        }

        $this->info("Product ID: {$product->id}");
        $this->info("Product Name: {$product->name}");
        $this->info("Is Print Service: " . ($product->is_print_service ? 'Yes' : 'No'));

        $productVariantService = new ProductVariantService();

        $variantData = [
            'name' => $product->name . ' - A1001 Multi Color',
            'sku' => $product->sku . '-A1001-MC',
            'price' => 3500,
            'harga_beli' => 2000,
            'stock' => 50,
            'weight' => 0.2,
            'length' => 29.7,
            'width' => 42.0,
            'height' => 0.1,
        ];

        $attributes = [
            [
                'attribute_name' => 'paper_size',
                'attribute_value' => 'A1001'
            ],
            [
                'attribute_name' => 'print_type', 
                'attribute_value' => 'multi color'
            ]
        ];

        try {
            $variant = $productVariantService->createVariant($product, $variantData, $attributes);
            $this->info("✅ Variant created successfully!");
            $this->info("Variant ID: {$variant->id}");
            $this->info("Variant Name: {$variant->name}");
            $this->info("Paper Size: {$variant->paper_size}");
            $this->info("Print Type: {$variant->print_type}");
            
            $variantAttributes = $variant->variantAttributes;
            $this->info("Variant Attributes count: {$variantAttributes->count()}");
            foreach ($variantAttributes as $attr) {
                $this->info("- {$attr->attribute_name}: {$attr->attribute_value}");
            }
            
            $this->info('');
            $this->info('Testing custom A2002 variant...');
            
            $variantData2 = [
                'name' => $product->name . ' - A2002 Ultra HD',
                'sku' => $product->sku . '-A2002-UHD',
                'price' => 5000,
                'harga_beli' => 3000,
                'stock' => 25,
                'weight' => 0.3,
                'length' => 42.0,
                'width' => 59.4,
                'height' => 0.1,
            ];

            $attributes2 = [
                [
                    'attribute_name' => 'paper_size',
                    'attribute_value' => 'A2002'
                ],
                [
                    'attribute_name' => 'print_type', 
                    'attribute_value' => 'ultra hd color'
                ]
            ];
            
            $variant2 = $productVariantService->createVariant($product, $variantData2, $attributes2);
            $this->info("✅ Second variant created successfully!");
            $this->info("Variant ID: {$variant2->id}");
            $this->info("Variant Name: {$variant2->name}");
            $this->info("Paper Size: {$variant2->paper_size}");
            $this->info("Print Type: {$variant2->print_type}");
            
        } catch (\Exception $e) {
            $this->error("❌ Error creating variant: {$e->getMessage()}");
            $this->error("Stack trace: {$e->getTraceAsString()}");
        }
        
        return 0;
    }
}
