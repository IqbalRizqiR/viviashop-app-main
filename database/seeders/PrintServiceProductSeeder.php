<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\VariantAttribute;
use App\Models\Category;
use App\Models\Brand;
use App\Services\ProductVariantService;

class PrintServiceProductSeeder extends Seeder
{
    protected $productVariantService;

    public function __construct()
    {
        $this->productVariantService = new ProductVariantService();
    }

    public function run(): void
    {
        $category = Category::firstOrCreate([
            'name' => 'Layanan Cetak',
            'slug' => 'layanan-cetak'
        ], [
            'description' => 'Layanan cetak dokumen dan file digital',
            'image' => null,
            'parent_id' => null,
        ]);

        $brand = Brand::firstOrCreate([
            'name' => 'ViVia Print Service',
            'slug' => 'vivia-print-service'
        ], [
            'description' => 'Layanan cetak terpercaya',
            'image' => null,
        ]);

        $this->command->info('Creating print service products...');

        $this->createPaperProduct($category, $brand);
    }

    private function createPaperProduct($category, $brand)
    {
        $productData = [
            'name' => 'Kertas HVS - Layanan Cetak',
            'sku' => 'PRINT-HVS-001',
            'type' => 'configurable',
            'brand_id' => $brand->id,
            'weight' => 0.005,
            'short_description' => 'Layanan cetak dokumen pada kertas HVS berkualitas tinggi.',
            'description' => 'Layanan cetak profesional dengan pilihan kertas HVS berbagai ukuran dan jenis cetak (hitam putih atau berwarna). Cocok untuk dokumen kantor, tugas sekolah, presentasi, dan kebutuhan cetak lainnya.',
            'status' => Product::ACTIVE,
            'category_id' => [$category->id],
            'is_featured' => true,
            'is_print_service' => true,
        ];

        $variantData = [
            [
                'name' => 'Kertas HVS A4 - Hitam Putih',
                'price' => 500,
                'harga_beli' => 300,
                'stock' => 10000,
                'attributes' => [
                    'paper_size' => 'A4',
                    'print_type' => 'Hitam Putih'
                ],
                'print_type' => 'bw',
                'paper_size' => 'A4',
                'min_stock_threshold' => 1000,
            ],
            [
                'name' => 'Kertas HVS A4 - Berwarna',
                'price' => 1500,
                'harga_beli' => 900,
                'stock' => 5000,
                'attributes' => [
                    'paper_size' => 'A4',
                    'print_type' => 'Berwarna'
                ],
                'print_type' => 'color',
                'paper_size' => 'A4',
                'min_stock_threshold' => 500,
            ],
            [
                'name' => 'Kertas HVS A3 - Hitam Putih',
                'price' => 1000,
                'harga_beli' => 600,
                'stock' => 3000,
                'attributes' => [
                    'paper_size' => 'A3',
                    'print_type' => 'Hitam Putih'
                ],
                'print_type' => 'bw',
                'paper_size' => 'A3',
                'min_stock_threshold' => 300,
            ],
            [
                'name' => 'Kertas HVS A3 - Berwarna',
                'price' => 3000,
                'harga_beli' => 1800,
                'stock' => 2000,
                'attributes' => [
                    'paper_size' => 'A3',
                    'print_type' => 'Berwarna'
                ],
                'print_type' => 'color',
                'paper_size' => 'A3',
                'min_stock_threshold' => 200,
            ],
            [
                'name' => 'Kertas HVS F4 - Hitam Putih',
                'price' => 750,
                'harga_beli' => 450,
                'stock' => 4000,
                'attributes' => [
                    'paper_size' => 'F4',
                    'print_type' => 'Hitam Putih'
                ],
                'print_type' => 'bw',
                'paper_size' => 'F4',
                'min_stock_threshold' => 400,
            ],
            [
                'name' => 'Kertas HVS F4 - Berwarna',
                'price' => 2000,
                'harga_beli' => 1200,
                'stock' => 2500,
                'attributes' => [
                    'paper_size' => 'F4',
                    'print_type' => 'Berwarna'
                ],
                'print_type' => 'color',
                'paper_size' => 'F4',
                'min_stock_threshold' => 250,
            ],
        ];

        $result = $this->productVariantService->createConfigurableProduct($productData, $variantData);

        $product = $result['product'];
        $product->update(['is_print_service' => true]);

        foreach ($result['variants'] as $index => $variant) {
            $variantInfo = $variantData[$index];
            $variant->update([
                'print_type' => $variantInfo['print_type'],
                'paper_size' => $variantInfo['paper_size']
            ]);
        }

        $this->command->info('Created print service product: ' . $result['product']->name);
        $this->command->info('Created ' . count($result['variants']) . ' variants');
    }
}
