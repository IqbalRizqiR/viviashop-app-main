<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductInventory;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProdukImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            try {
                // Validasi data minimal yang harus ada
                if (empty($row['sku']) || !isset($row['price'])) {
                    Log::warning("Baris $index dilewati karena tidak memiliki 'name' atau 'price'.", $row->toArray());
                    continue;
                }

                // Buat produk
                $product = Product::create([
                    'sku' => $row['sku'] ?? 'sku-' . uniqid(),
                    'weight' => $row['weight'] ?? 0,
                    'length' => $row['length'] ?? 0,
                    'width' => $row['width'] ?? 0,
                    'height' => $row['height'] ?? 0,
                    'type' => 'simple',
                    'name' => $row['name'],
                    'price' => $row['price'] ?? 0,
                    'harga_beli' => $row['harga_beli'] ?? 0,
                    'status' => 1,
                    'description' => $row['description'] ?? '',
                    'user_id' => Auth::id(),
                    'barcode' => rand(1000000000, 9999999999),
                    'short_description' => $row['short_description'] ?? '',
                    'slug' => Str::slug($row['name']),
                ]);

                // Cek category, fallback ke default (ID 1)
                $category = Category::where('name', $row['category_name'] ?? '')->first();
                ProductCategory::create([
                    'product_id' => $product->id,
                    'category_id' => $category?->id ?? 1,
                ]);

                // Tambah stok awal
                ProductInventory::create([
                    'product_id' => $product->id,
                    'qty' => $row['stok'],
                ]);
            } catch (\Throwable $e) {
                Log::error("Gagal import baris $index: " . $e->getMessage(), [
                    'row' => $row->toArray(),
                ]);
                continue;
            }
        }
    }
}
