<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductInventory;
use App\Models\ProductVariant;
use App\Services\ProductVariantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends BaseController
{
    protected $variantService;

    public function __construct(ProductVariantService $variantService)
    {
        $this->variantService = $variantService;
    }

    public function index(Request $request)
    {
        $query = Product::with(['brand', 'productInventory', 'productVariants', 'images'])
            ->whereNull('parent_id');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }
        if ($brandId = $request->input('brand_id')) {
            $query->where('brand_id', $brandId);
        }

        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'asc');
        $allowed = ['name', 'price', 'created_at', 'sku', 'status'];
        $query->orderBy(in_array($sort, $allowed) ? $sort : 'name', $order === 'desc' ? 'desc' : 'asc');

        $perPage = min((int) $request->input('per_page', 20), 100);

        return $this->paginated(
            $query->paginate($perPage)->through(fn($p) => new ProductResource($p))
        );
    }

    public function show($id)
    {
        $product = Product::with([
            'brand', 'categories', 'images', 'productInventory',
            'productVariants.variantAttributes',
        ])->findOrFail($id);

        return $this->success([
            'product' => new ProductResource($product),
            'categories' => Category::orderBy('name')->get(['id', 'name']),
            'brands' => Brand::active()->orderBy('name')->get(['id', 'name']),
            'statuses' => Product::statuses(),
            'types' => Product::types(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku',
            'type' => 'required|in:simple,configurable',
            'price' => 'required|numeric|min:0',
            'harga_beli' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric',
            'short_description' => 'nullable|string',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'nullable|in:0,1',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'qty' => 'nullable|integer|min:0',
            'is_print_service' => 'nullable|boolean',
            'is_smart_print_enabled' => 'nullable|boolean',
            'variants' => 'nullable|array',
        ]);

        try {
            $product = DB::transaction(function () use ($validated, $request) {
                if ($validated['type'] === 'configurable' && !empty($validated['variants'])) {
                    $result = $this->variantService->createConfigurableProduct($validated, $validated['variants']);
                    $product = $result['product'];
                } else {
                    $product = $this->variantService->createBaseProduct($validated);
                    if ($validated['type'] === 'simple' && isset($validated['qty'])) {
                        ProductInventory::create([
                            'product_id' => $product->id,
                            'qty' => $validated['qty'],
                        ]);
                    }
                }

                if (!empty($validated['category_ids'])) {
                    $product->categories()->sync($validated['category_ids']);
                }

                return $product;
            });

            return $this->success(
                new ProductResource($product->load(['brand', 'categories', 'images', 'productInventory', 'productVariants'])),
                'Product created',
                201
            );
        } catch (\Exception $e) {
            Log::error('Product creation error: ' . $e->getMessage());
            return $this->error('Failed to create product: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'sku' => 'sometimes|string|max:100|unique:products,sku,' . $id,
            'type' => 'sometimes|in:simple,configurable',
            'price' => 'sometimes|numeric|min:0',
            'harga_beli' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric',
            'short_description' => 'nullable|string',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'nullable|in:0,1',
            'category_ids' => 'nullable|array',
            'qty' => 'nullable|integer|min:0',
            'is_print_service' => 'nullable|boolean',
            'is_smart_print_enabled' => 'nullable|boolean',
            'variants' => 'nullable|array',
        ]);

        try {
            DB::transaction(function () use ($product, $validated, $request) {
                $product->update(collect($validated)->except(['category_ids', 'qty', 'variants'])->toArray());

                if (isset($validated['category_ids'])) {
                    $product->categories()->sync($validated['category_ids']);
                }

                if ($product->type === 'simple' && isset($validated['qty'])) {
                    ProductInventory::updateOrCreate(
                        ['product_id' => $product->id],
                        ['qty' => $validated['qty']]
                    );
                }

                if (!empty($validated['variants'])) {
                    foreach ($validated['variants'] as $variantData) {
                        if (isset($variantData['id'])) {
                            $variant = ProductVariant::find($variantData['id']);
                            if ($variant) {
                                $this->variantService->updateProductVariant($variant, $variantData);
                            }
                        } else {
                            $this->variantService->createSingleVariant($product, $variantData);
                        }
                    }
                }
            });

            return $this->success(
                new ProductResource($product->fresh()->load(['brand', 'categories', 'images', 'productInventory', 'productVariants'])),
                'Product updated'
            );
        } catch (\Exception $e) {
            Log::error('Product update error: ' . $e->getMessage());
            return $this->error('Failed to update product: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return $this->success(null, 'Product deleted');
    }

    public function findByBarcode(Request $request)
    {
        $product = Product::where('barcode', $request->input('barcode'))
            ->with(['productInventory', 'productVariants'])
            ->first();

        if (!$product) {
            return $this->error('Product not found', 404);
        }

        return $this->success(new ProductResource($product));
    }

    public function generateBarcode($id)
    {
        $product = Product::findOrFail($id);

        do {
            $barcode = rand(1000000000, 9999999999);
        } while (Product::where('barcode', $barcode)->exists());

        $product->update(['barcode' => $barcode]);

        return $this->success(['barcode' => $barcode], 'Barcode generated');
    }
}
