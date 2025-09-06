<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\VariantAttribute;
use App\Services\ProductVariantService;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    protected $productVariantService;

    public function __construct(ProductVariantService $productVariantService)
    {
        $this->productVariantService = $productVariantService;
    }

    public function getVariantsByProduct(Product $product)
    {
        $variants = $product->activeVariants()
            ->with('variantAttributes')
            ->get()
            ->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'sku' => $variant->sku,
                    'name' => $variant->name,
                    'price' => $variant->price,
                    'formatted_price' => $variant->getFormattedPriceAttribute(),
                    'stock' => $variant->stock,
                    'attributes' => $variant->variantAttributes->pluck('attribute_value', 'attribute_name'),
                    'is_low_stock' => $variant->isLowStock(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $variants
        ]);
    }

    public function getAttributeOptions(Product $product, $attributeName)
    {
        $options = $this->productVariantService->getAttributeOptions($product, $attributeName);

        return response()->json([
            'success' => true,
            'data' => $options
        ]);
    }

    public function getVariantByAttributes(Product $product, Request $request)
    {
        $attributes = $request->get('attributes', []);
        $variant = $this->productVariantService->getVariantByAttributes($product, $attributes);

        if (!$variant) {
            return response()->json([
                'success' => false,
                'message' => 'Variant not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $variant->id,
                'sku' => $variant->sku,
                'name' => $variant->name,
                'price' => $variant->price,
                'formatted_price' => $variant->getFormattedPriceAttribute(),
                'stock' => $variant->stock,
                'attributes' => $variant->variantAttributes->pluck('attribute_value', 'attribute_name'),
                'is_available' => $variant->stock > 0 && $variant->is_active,
            ]
        ]);
    }

    public function getVariantOptions(Product $product)
    {
        $options = $product->getVariantOptions();

        return response()->json([
            'success' => true,
            'data' => $options
        ]);
    }

    public function checkStock(ProductVariant $variant)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'stock' => $variant->stock,
                'is_available' => $variant->stock > 0 && $variant->is_active,
                'is_low_stock' => $variant->isLowStock(),
            ]
        ]);
    }

    public function getLowStockVariants()
    {
        $variants = $this->productVariantService->getLowStockVariants();

        return response()->json([
            'success' => true,
            'data' => $variants->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'product_id' => $variant->product_id,
                    'product_name' => $variant->product->name,
                    'sku' => $variant->sku,
                    'name' => $variant->name,
                    'stock' => $variant->stock,
                    'min_threshold' => $variant->min_stock_threshold,
                    'attributes' => $variant->variantAttributes->pluck('attribute_value', 'attribute_name'),
                ];
            })
        ]);
    }

    public function updateStock(ProductVariant $variant, Request $request)
    {
        $request->validate([
            'stock' => 'required|integer|min:0'
        ]);

        $variant->update(['stock' => $request->stock]);
        
        $this->productVariantService->updateBasePrice($variant->product);

        return response()->json([
            'success' => true,
            'message' => 'Stock updated successfully',
            'data' => [
                'variant_id' => $variant->id,
                'new_stock' => $variant->stock,
                'product_total_stock' => $variant->product->total_stock,
            ]
        ]);
    }

    public function bulkUpdateStock(Product $product, Request $request)
    {
        $request->validate([
            'variants' => 'required|array',
            'variants.*.id' => 'required|exists:product_variants,id',
            'variants.*.stock' => 'required|integer|min:0'
        ]);

        $updates = collect($request->variants)->pluck('stock', 'id')->toArray();
        
        $this->productVariantService->bulkUpdateVariants($product, $updates);

        return response()->json([
            'success' => true,
            'message' => 'Bulk stock update completed',
            'data' => [
                'updated_count' => count($updates),
                'product_total_stock' => $product->fresh()->total_stock,
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:product_variants',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'attributes' => 'required|array|min:1',
            'attributes.*.attribute_name' => 'required|string|max:255',
            'attributes.*.attribute_value' => 'required|string|max:255',
        ]);

        try {
            $product = Product::findOrFail($request->product_id);
            
            if ($product->type !== 'configurable') {
                return response()->json([
                    'success' => false,
                    'message' => 'Product must be configurable type to add variants'
                ], 400);
            }

            $variant = ProductVariant::create([
                'product_id' => $request->product_id,
                'sku' => $request->sku,
                'name' => $request->name,
                'price' => $request->price,
                'harga_beli' => $request->harga_beli ?? 0,
                'stock' => $request->stock,
                'weight' => $request->weight ?? $product->weight ?? 0,
                'length' => $product->length ?? 0,
                'width' => $product->width ?? 0,
                'height' => $product->height ?? 0,
                'is_active' => true,
                'min_stock_threshold' => 10,
            ]);

            foreach ($request->attributes as $index => $attribute) {
                VariantAttribute::create([
                    'variant_id' => $variant->id,
                    'attribute_name' => $attribute['attribute_name'],
                    'attribute_value' => $attribute['attribute_value'],
                    'sort_order' => $index,
                ]);
            }

            $this->productVariantService->updateBasePrice($product);

            $variant->load('variantAttributes');

            return response()->json([
                'success' => true,
                'message' => 'Product variant created successfully',
                'data' => $variant
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating variant: ' . $e->getMessage()
            ], 500);
        }
    }
}
