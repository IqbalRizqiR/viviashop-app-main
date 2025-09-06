<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use App\Models\VariantAttribute;
use App\Models\Product;
use App\Services\ProductVariantService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductVariantController extends Controller
{
    protected $productVariantService;

    public function __construct(ProductVariantService $productVariantService)
    {
        $this->productVariantService = $productVariantService;
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'name' => 'required|string|max:255',
                'sku' => 'required|string|max:100',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'weight' => 'nullable|numeric|min:0',
                'attributes' => 'required|array|min:1',
                'attributes.*.attribute_name' => 'required|string|max:100',
                'attributes.*.attribute_value' => 'required|string|max:100',
            ]);

            $product = Product::findOrFail($request->product_id);
            
            $sku = $request->sku;
            $existingProduct = Product::where('sku', $sku)->first();
            $existingVariant = ProductVariant::where('sku', $sku)->first();
            
            if ($existingProduct || $existingVariant) {
                $sku = $this->generateUniqueSku($request->sku, $product);
            }
            
            $variantData = [
                'name' => $request->name,
                'sku' => $sku,
                'price' => $request->price,
                'harga_beli' => $request->harga_beli,
                'stock' => $request->stock,
                'weight' => $request->weight ?? $product->weight ?? 0,
                'length' => $request->length ?? $product->length ?? 0,
                'width' => $request->width ?? $product->width ?? 0,
                'height' => $request->height ?? $product->height ?? 0,
            ];

            $variant = $this->productVariantService->createVariant($product, $variantData, $request->input('attributes', []));

            if (!$variant) {
                return response()->json(['message' => 'Failed to create variant'], 500);
            }

            return response()->json([
                'message' => 'Variant created successfully',
                'variant' => $variant
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create variant: ' . $e->getMessage()
            ], 500);
        }
    }
    
    private function generateUniqueSku($baseSku, $product)
    {
        $counter = 1;
        do {
            $newSku = $baseSku . '-V' . $counter;
            $existsInProducts = Product::where('sku', $newSku)->exists();
            $existsInVariants = ProductVariant::where('sku', $newSku)->exists();
            $counter++;
        } while (($existsInProducts || $existsInVariants) && $counter < 1000);
        
        return $newSku;
    }

    public function index($productId)
    {
        $product = Product::findOrFail($productId);
        $variants = $product->variants()->with('attributes')->get();
        
        return response()->json([
            'variants' => $variants
        ]);
    }

    public function show($id)
    {
        $variant = ProductVariant::with('variantAttributes')->findOrFail($id);
        
        return response()->json([
            'variant' => $variant
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $variant = ProductVariant::findOrFail($id);
            
            $request->validate([
                'name' => 'required|string|max:255',
                'sku' => 'required|string|max:100',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'weight' => 'nullable|numeric|min:0',
                'attributes' => 'required|array|min:1',
                'attributes.*.attribute_name' => 'required|string|max:100',
                'attributes.*.attribute_value' => 'required|string|max:100',
            ]);

            $sku = $request->sku;
            $existingProduct = Product::where('sku', $sku)->where('id', '!=', $variant->product_id)->first();
            $existingVariant = ProductVariant::where('sku', $sku)->where('id', '!=', $id)->first();
            
            if ($existingProduct || $existingVariant) {
                $sku = $this->generateUniqueSku($request->sku, $variant->product);
            }

            $variantData = [
                'name' => $request->name,
                'sku' => $sku,
                'price' => $request->price,
                'harga_beli' => $request->harga_beli,
                'stock' => $request->stock,
                'weight' => $request->weight ?? $variant->product->weight ?? 0,
                'length' => $request->length ?? $variant->product->length ?? 0,
                'width' => $request->width ?? $variant->product->width ?? 0,
                'height' => $request->height ?? $variant->product->height ?? 0,
            ];

            $updatedVariant = $this->productVariantService->updateVariant($variant, $variantData, $request->input('attributes', []));

            return response()->json([
                'message' => 'Variant updated successfully',
                'variant' => $updatedVariant
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update variant: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $variant = ProductVariant::findOrFail($id);
            
            $variant->attributes()->delete();
            $variant->delete();

            return response()->json([
                'message' => 'Variant deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete variant: ' . $e->getMessage()
            ], 500);
        }
    }
}
