<?php

namespace App\Imports;

use App\Models\Category;
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
        $rowed = [];
        // dd($rows);
        foreach ($rows as $row) {
            $rowed[] = $row;
        //     $products = Product::create([
        //         'sku' => $row['name'], // jika sku tidak ada, gunakan nama
        //         'type' => 'simple',
        //         'name' => $row['name'],
        //         'price' => $row['price'],
        //         'harga_beli' => $row['harga_beli'],
        //         'status' => 1,
        //         'description' => $row['description'],
        //         'user_id' => Auth::id(),
        //         'barcode' => rand(1000000000, 9999999999),
        //         'short_description' => $row['short_description'],
        //         'slug' => Str::slug($row['name']),
        //     ]);

        // $category = Category::where('name', $row['category_name'])->first();
        // // $product->category_id = $category?->id;

        //     // dd($products->id);
        //     ProductCategory::create([
        //         'product_id' => $products->id,
        //         'category_id' => $category?->id ?? 1, // jika tidak ada category, set ke 1 (default)
        //     ]);

        //     ProductInventory::create([
        //         'product_id' => $products->id,
        //         'qty' => $row['stok'],
        //     ]);
        }

        dd($rowed);
    }
}
