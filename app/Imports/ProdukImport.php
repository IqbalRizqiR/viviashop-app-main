<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductInventory;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProdukImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $products = Product::create([
                'type' => 'simple',
                'name' => $row['name'],
                'sku' => $row['name'],
                'price' => $row['price'],
                'harga_beli' => $row['harga_beli'],
                'status' => 1,
                'description' => $row['description'],
                'user_id' => Auth::id(),
                'short_description' => $row['short_description'],
                'slug' => Str::slug($row['name']),
            ]);

            // dd($products->id);
            ProductCategory::create([
                'product_id' => $products->id,
                'category_id' => $row['id_category'],
            ]);

            ProductInventory::create([
                'product_id' => $products->id,
                'qty' => $row['stok'],
            ]);
        }
    }
}
