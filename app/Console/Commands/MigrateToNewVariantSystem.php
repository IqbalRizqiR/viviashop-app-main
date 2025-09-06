<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\VariantAttribute;
use App\Models\ProductAttributeValue;
use App\Services\ProductVariantService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateToNewVariantSystem extends Command
{
    protected $signature = 'migrate:variant-system {--dry-run : Run without making changes}';
    protected $description = 'Migrate existing configurable products to new variant system';

    protected $productVariantService;

    public function __construct(ProductVariantService $productVariantService)
    {
        parent::__construct();
        $this->productVariantService = $productVariantService;
    }

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('DRY RUN MODE - No changes will be made');
        }

        $this->info('Starting migration to new variant system...');

        // Get all configurable products that use old system (have parent_id variants)
        $configurableProducts = Product::where('type', 'configurable')
            ->where('parent_id', null)
            ->whereHas('variants')
            ->get();

        $this->info("Found {$configurableProducts->count()} configurable products to migrate");

        $migrated = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($configurableProducts as $product) {
            try {
                if ($product->productVariants()->count() > 0) {
                    $this->warn("Product '{$product->name}' already has new variants, skipping...");
                    $skipped++;
                    continue;
                }

                $this->info("Migrating product: {$product->name}");
                
                if (!$dryRun) {
                    $this->migrateProduct($product);
                }
                
                $migrated++;
                
            } catch (\Exception $e) {
                $this->error("Error migrating product '{$product->name}': " . $e->getMessage());
                $errors++;
            }
        }

        $this->info("\nMigration Summary:");
        $this->info("Migrated: {$migrated}");
        $this->info("Skipped: {$skipped}");
        $this->info("Errors: {$errors}");

        if ($dryRun) {
            $this->info("\nThis was a DRY RUN. No actual changes were made.");
            $this->info("Run without --dry-run to perform the actual migration.");
        }

        return 0;
    }

    private function migrateProduct(Product $product)
    {
        DB::transaction(function () use ($product) {
            $variants = $product->variants()
                ->with(['variantAttributeValues.attribute', 'variantAttributeValues.attribute_option', 'productInventory'])
                ->get();

            foreach ($variants as $variant) {
                // Create new ProductVariant
                $newVariant = ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $variant->sku,
                    'name' => $variant->name,
                    'price' => $variant->price ?? 0,
                    'harga_beli' => $variant->harga_beli ?? 0,
                    'stock' => $variant->productInventory?->qty ?? 0,
                    'weight' => $variant->weight ?? $product->weight,
                    'length' => $variant->length ?? $product->length,
                    'width' => $variant->width ?? $product->width,
                    'height' => $variant->height ?? $product->height,
                    'barcode' => $variant->barcode,
                    'is_active' => $variant->status == 1,
                    'min_stock_threshold' => 10,
                ]);

                // Migrate attributes
                $sortOrder = 0;
                foreach ($variant->variantAttributeValues as $attrValue) {
                    if ($attrValue->attribute && $attrValue->attribute_option) {
                        VariantAttribute::create([
                            'variant_id' => $newVariant->id,
                            'attribute_name' => $attrValue->attribute->name,
                            'attribute_value' => $attrValue->attribute_option->name,
                            'sort_order' => $sortOrder++,
                        ]);
                    }
                }

                $this->line("  Created variant: {$newVariant->name}");
            }

            // Update base price and total stock
            $this->productVariantService->updateBasePrice($product);
            
            $this->line("  Updated base price and total stock for: {$product->name}");
        });
    }
}
