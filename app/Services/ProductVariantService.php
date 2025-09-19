<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\VariantAttribute;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductVariantService
{
    public function createConfigurableProduct(array $productData, array $variantData)
    {
        return DB::transaction(function () use ($productData, $variantData) {
            $product = $this->createBaseProduct($productData);
            $variants = $this->createProductVariants($product, $variantData);
            $this->updateBasePrice($product);
            
            return [
                'product' => $product,
                'variants' => $variants
            ];
        });
    }

    public function createBaseProduct(array $data)
    {
        $basePrice = isset($data['variants']) ? $this->calculateBasePrice($data['variants']) : ($data['price'] ?? 0);
        
        $product = Product::create([
            'name' => $data['name'],
            'sku' => $data['sku'],
            'type' => $data['type'],
            'brand_id' => $data['brand_id'] ?? null,
            'price' => $data['price'] ?? null,
            'base_price' => $basePrice,
            'weight' => $data['weight'] ?? 0,
            'length' => $data['length'] ?? null,
            'width' => $data['width'] ?? null,
            'height' => $data['height'] ?? null,
            'short_description' => $data['short_description'] ?? null,
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? Product::ACTIVE,
            'user_id' => auth()->id() ?? 1, // Default to user ID 1 if not authenticated
            'link1' => $data['link1'] ?? null,
            'link2' => $data['link2'] ?? null,
            'link3' => $data['link3'] ?? null,
            'is_featured' => $data['is_featured'] ?? false,
            'is_print_service' => $data['is_print_service'] ?? false,
            'is_smart_print_enabled' => $data['is_smart_print_enabled'] ?? false,
            'harga_beli' => $data['harga_beli'] ?? null,
            'barcode' => $data['barcode'] ?? null,
        ]);

        if (isset($data['category_id'])) {
            $product->categories()->sync($data['category_id']);
        }

        return $product;
    }

    public function createProductVariants(Product $product, array $variantData)
    {
        $variants = [];
        
        foreach ($variantData as $variantInfo) {
            $variant = $this->createSingleVariant($product, $variantInfo);
            $variants[] = $variant;
        }
        
        return $variants;
    }

    public function createSingleVariant(Product $product, array $variantInfo)
    {
        $sku = $this->generateVariantSku($product, $variantInfo['attributes']);
        
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => $sku,
            'name' => $this->generateVariantName($product->name, $variantInfo['attributes']),
            'price' => $variantInfo['price'],
            'harga_beli' => $variantInfo['harga_beli'] ?? null,
            'stock' => $variantInfo['stock'] ?? 0,
            'weight' => $variantInfo['weight'] ?? $product->weight,
            'length' => $variantInfo['length'] ?? $product->length,
            'width' => $variantInfo['width'] ?? $product->width,
            'height' => $variantInfo['height'] ?? $product->height,
            'barcode' => $variantInfo['barcode'] ?? $this->generateBarcode(),
            'is_active' => $variantInfo['is_active'] ?? true,
            'min_stock_threshold' => $variantInfo['min_stock_threshold'] ?? 10,
        ]);

        $this->createVariantAttributes($variant, $variantInfo['attributes']);
        
        return $variant;
    }

    public function createVariantAttributes(ProductVariant $variant, array $attributes)
    {
        $sortOrder = 0;
        
        foreach ($attributes as $attributeName => $attributeValue) {
            VariantAttribute::create([
                'variant_id' => $variant->id,
                'attribute_name' => $attributeName,
                'attribute_value' => $attributeValue,
                'sort_order' => $sortOrder++,
            ]);
        }
    }

    public function createVariantAttributesFromArray(ProductVariant $variant, array $attributes)
    {
        $sortOrder = 0;
        
        foreach ($attributes as $attribute) {
            if (isset($attribute['attribute_name']) && isset($attribute['attribute_value'])) {
                VariantAttribute::create([
                    'variant_id' => $variant->id,
                    'attribute_name' => $attribute['attribute_name'],
                    'attribute_value' => $attribute['attribute_value'],
                    'sort_order' => $sortOrder++,
                ]);
            }
        }
    }

    public function generateVariantSku(Product $product, array $attributes)
    {
        $productCode = strtoupper(substr(str_replace(' ', '', $product->name), 0, 3));
        $attributeParts = [];
        
        foreach ($attributes as $name => $value) {
            $attributeParts[] = strtoupper(substr(str_replace(' ', '', $value), 0, 2));
        }
        
        $baseSku = $productCode . '-' . implode('-', $attributeParts);
        
        $counter = 1;
        $finalSku = $baseSku;
        
        while (ProductVariant::where('sku', $finalSku)->exists()) {
            $finalSku = $baseSku . '-' . str_pad($counter, 2, '0', STR_PAD_LEFT);
            $counter++;
        }
        
        return $finalSku;
    }

    public function generateVariantName(string $productName, array $attributes)
    {
        $attributeString = implode(' ', array_values($attributes));
        return $productName . ' - ' . $attributeString;
    }

    public function generateBarcode()
    {
        do {
            $barcode = rand(1000000000, 9999999999);
        } while (ProductVariant::where('barcode', $barcode)->exists());
        
        return $barcode;
    }

    public function calculateBasePrice(array $variants)
    {
        $prices = array_column($variants, 'price');
        return min($prices);
    }

    public function updateBasePrice(Product $product)
    {
        if ($product->type === 'configurable') {
            $minPrice = $product->productVariants()->min('price');
            $totalStock = $product->productVariants()->sum('stock');
            
            $product->update([
                'base_price' => $minPrice,
                'total_stock' => $totalStock,
            ]);
        }
    }

    public function updateProductVariant(ProductVariant $variant, array $data)
    {
        return DB::transaction(function () use ($variant, $data) {
            $variant->update($data);
            
            if (isset($data['attributes'])) {
                $variant->variantAttributes()->delete();
                $this->createVariantAttributes($variant, $data['attributes']);
            }
            
            $this->updateBasePrice($variant->product);
            
            return $variant;
        });
    }

    public function deleteProductVariant(ProductVariant $variant)
    {
        return DB::transaction(function () use ($variant) {
            $product = $variant->product;
            $variant->variantAttributes()->delete();
            $variant->delete();
            
            $this->updateBasePrice($product);
            
            return true;
        });
    }

    public function bulkUpdateVariants(Product $product, array $updates)
    {
        return DB::transaction(function () use ($product, $updates) {
            foreach ($updates as $variantId => $updateData) {
                $variant = $product->productVariants()->find($variantId);
                if ($variant) {
                    $variant->update($updateData);
                }
            }
            
            $this->updateBasePrice($product);
            
            return true;
        });
    }

    public function getVariantByAttributes(Product $product, array $attributes)
    {
        $query = $product->productVariants()->where('is_active', true);
        
        foreach ($attributes as $attributeName => $attributeValue) {
            $query->whereHas('variantAttributes', function ($q) use ($attributeName, $attributeValue) {
                $q->where('attribute_name', $attributeName)
                  ->where('attribute_value', $attributeValue);
            });
        }
        
        return $query->first();
    }

    public function getAttributeOptions(Product $product, string $attributeName)
    {
        return VariantAttribute::join('product_variants', 'variant_attributes.variant_id', '=', 'product_variants.id')
            ->where('product_variants.product_id', $product->id)
            ->where('variant_attributes.attribute_name', $attributeName)
            ->where('product_variants.is_active', true)
            ->distinct()
            ->pluck('variant_attributes.attribute_value')
            ->sort()
            ->values();
    }

    public function getLowStockVariants(int $limit = 20)
    {
        return ProductVariant::with(['product', 'variantAttributes'])
            ->whereRaw('stock <= min_stock_threshold')
            ->where('is_active', true)
            ->orderBy('stock', 'asc')
            ->limit($limit)
            ->get();
    }

    public function generateInventoryReport(Product $product)
    {
        $variants = $product->productVariants()->with('variantAttributes')->get();
        
        $report = [
            'product_name' => $product->name,
            'total_variants' => $variants->count(),
            'total_stock' => $variants->sum('stock'),
            'total_value' => $variants->sum(function($variant) {
                return $variant->stock * $variant->harga_beli;
            }),
            'low_stock_count' => $variants->filter(function($variant) {
                return $variant->stock <= $variant->min_stock_threshold;
            })->count(),
            'variants' => $variants->map(function($variant) {
                return [
                    'sku' => $variant->sku,
                    'name' => $variant->name,
                    'attributes' => $variant->variantAttributes->pluck('attribute_value', 'attribute_name'),
                    'stock' => $variant->stock,
                    'price' => $variant->price,
                    'harga_beli' => $variant->harga_beli,
                    'value' => $variant->stock * $variant->harga_beli,
                    'is_low_stock' => $variant->stock <= $variant->min_stock_threshold,
                ];
            })
        ];
        
        return $report;
    }

    public function createVariant(Product $product, array $variantData, array $attributes)
    {
        return DB::transaction(function () use ($product, $variantData, $attributes) {
            $variant = ProductVariant::create([
                'product_id' => $product->id,
                'name' => $variantData['name'],
                'sku' => $variantData['sku'],
                'price' => $variantData['price'],
                'stock' => $variantData['stock'],
                'weight' => $variantData['weight'] ?? 0,
                'is_active' => true,
            ]);

            $this->createVariantAttributesFromArray($variant, $attributes);

            $product->update(['type' => 'configurable']);
            $this->updateBasePrice($product);

            return $variant->load('variantAttributes');
        });
    }

    public function updateVariant(ProductVariant $variant, array $variantData, array $attributes)
    {
        return DB::transaction(function () use ($variant, $variantData, $attributes) {
            $variant->update([
                'name' => $variantData['name'],
                'sku' => $variantData['sku'],
                'price' => $variantData['price'],
                'harga_beli' => $variantData['harga_beli'] ?? null,
                'stock' => $variantData['stock'],
                'weight' => $variantData['weight'] ?? 0,
                'length' => $variantData['length'] ?? 0,
                'width' => $variantData['width'] ?? 0,
                'height' => $variantData['height'] ?? 0,
            ]);

            $variant->variantAttributes()->delete();
            $this->createVariantAttributesFromArray($variant, $attributes);

            $this->updateBasePrice($variant->product);

            return $variant->load('variantAttributes');
        });
    }
}
