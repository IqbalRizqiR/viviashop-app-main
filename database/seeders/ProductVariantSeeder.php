<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Services\ProductVariantService;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    protected $productVariantService;

    public function __construct()
    {
        $this->productVariantService = app(ProductVariantService::class);
    }

    public function run(): void
    {
        // Get brands and categories
        $appBrand = Brand::where('slug', 'app')->first();
        $sinarDuniaBrand = Brand::where('slug', 'sinar-dunia')->first();
        
        $category = Category::first();
        
        if (!$appBrand || !$sinarDuniaBrand || !$category) {
            $this->command->error('Brands atau categories belum di-seed. Jalankan BrandSeeder terlebih dahulu.');
            return;
        }

        // Create Kertas HVS - Configurable Product
        $productData = [
            'name' => 'Kertas HVS',
            'sku' => 'HVS-001',
            'type' => 'configurable',
            'brand_id' => $appBrand->id,
            'weight' => 0.5,
            'short_description' => 'Kertas HVS berkualitas tinggi untuk kebutuhan kantor dan sekolah.',
            'description' => 'Kertas HVS dengan berbagai varian brand, ukuran, dan gramatur. Tersedia brand APP, Sinar Dunia dengan ukuran A4, F4 dan gramatur 70gr, 80gr.',
            'status' => Product::ACTIVE,
            'category_id' => [$category->id],
            'is_featured' => true,
        ];

        $variantData = [
            // APP A4 70gr
            [
                'price' => 45000,
                'harga_beli' => 35000,
                'stock' => 100,
                'attributes' => [
                    'brand' => 'APP',
                    'size' => 'A4',
                    'weight' => '70gr'
                ],
                'min_stock_threshold' => 20,
            ],
            // APP A4 80gr
            [
                'price' => 50000,
                'harga_beli' => 40000,
                'stock' => 75,
                'attributes' => [
                    'brand' => 'APP',
                    'size' => 'A4',
                    'weight' => '80gr'
                ],
                'min_stock_threshold' => 20,
            ],
            // APP F4 70gr
            [
                'price' => 48000,
                'harga_beli' => 38000,
                'stock' => 50,
                'attributes' => [
                    'brand' => 'APP',
                    'size' => 'F4',
                    'weight' => '70gr'
                ],
                'min_stock_threshold' => 15,
            ],
            // Sinar Dunia A4 70gr
            [
                'price' => 42000,
                'harga_beli' => 32000,
                'stock' => 120,
                'attributes' => [
                    'brand' => 'Sinar Dunia',
                    'size' => 'A4',
                    'weight' => '70gr'
                ],
                'min_stock_threshold' => 25,
            ],
            // Sinar Dunia A4 80gr
            [
                'price' => 47000,
                'harga_beli' => 37000,
                'stock' => 90,
                'attributes' => [
                    'brand' => 'Sinar Dunia',
                    'size' => 'A4',
                    'weight' => '80gr'
                ],
                'min_stock_threshold' => 20,
            ],
        ];

        $result = $this->productVariantService->createConfigurableProduct($productData, $variantData);
        
        $this->command->info('Created configurable product: ' . $result['product']->name);
        $this->command->info('Created ' . count($result['variants']) . ' variants');

        // Create Pulpen - Simple Product for comparison
        $simpleProductData = [
            'name' => 'Pulpen Pilot',
            'sku' => 'PILOT-001',
            'type' => 'simple',
            'brand_id' => Brand::where('slug', 'pilot')->first()?->id,
            'price' => 5000,
            'weight' => 0.02,
            'short_description' => 'Pulpen Pilot warna biru, tinta lancar.',
            'description' => 'Pulpen berkualitas dari Pilot dengan tinta yang lancar dan awet.',
            'status' => Product::ACTIVE,
            'category_id' => [$category->id],
            'qty' => 500,
        ];

        $simpleProduct = $this->productVariantService->createBaseProduct($simpleProductData);
        
        // Create inventory for simple product
        \App\Models\ProductInventory::create([
            'product_id' => $simpleProduct->id,
            'qty' => 500,
        ]);

        $this->command->info('Created simple product: ' . $simpleProduct->name);
    }
}Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
    }
}
