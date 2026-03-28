<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\BrandResource;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    public function index(Request $request)
    {
        $query = Product::where(function ($q) {
                $q->where('type', 'simple')->whereNull('parent_id')
                  ->orWhere('type', 'configurable');
            })
            ->where('status', Product::ACTIVE)
            ->with(['productproductImages', 'brand', 'categories', 'productInventory', 'productVariants.variantAttributes']);

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($categorySlug = $request->input('category')) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $query->whereHas('categories', fn($q) => $q->where('categories.id', $category->id));
            }
        }

        // Filter by brand
        if ($brandSlug = $request->input('brand')) {
            $brand = Brand::where('slug', $brandSlug)->first();
            if ($brand) {
                $query->where('brand_id', $brand->id);
            }
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        // Filter by type
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        // Sort
        $sortField = $request->input('sort', 'created_at');
        $sortDir = $request->input('order', 'desc');
        $allowed = ['name', 'price', 'created_at'];
        if (in_array($sortField, $allowed)) {
            $query->orderBy($sortField, $sortDir === 'asc' ? 'asc' : 'desc');
        }

        $perPage = min((int) $request->input('per_page', 12), 100);
        $products = $query->paginate($perPage);

        return $this->paginated($products->through(fn($p) => new ProductResource($p)));
    }

    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)
            ->with([
                'productImages',
                'brand',
                'categories',
                'productInventory',
                'productVariants' => fn($q) => $q->where('is_active', true),
                'productVariants.variantAttributes',
            ])
            ->firstOrFail();

        // If it's a child product, redirect to parent
        if ($product->parent_id) {
            $parent = Product::where('id', $product->parent_id)->first();
            if ($parent) {
                return $this->show($parent->slug);
            }
        }

        return $this->success(new ProductResource($product));
    }

    public function popular(Request $request)
    {
        $limit = min((int) $request->input('limit', 8), 50);

        $products = Product::where(function ($q) {
                $q->where('type', 'simple')->whereNull('parent_id')
                  ->orWhere('type', 'configurable');
            })
            ->where('status', Product::ACTIVE)
            ->with(['productImages', 'brand', 'productInventory', 'productVariants.variantAttributes'])
            ->limit($limit)
            ->get();

        return $this->success(ProductResource::collection($products));
    }

    public function categories()
    {
        $categories = Category::withCount('products')->get();
        return $this->success(CategoryResource::collection($categories));
    }

    public function brands()
    {
        $brands = Brand::active()->withCount('products')->get();
        return $this->success(BrandResource::collection($brands));
    }

    public function byCategory(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $products = Product::whereHas('categories', fn($q) => $q->where('categories.id', $category->id))
            ->where('status', Product::ACTIVE)
            ->with(['productImages', 'brand', 'productInventory', 'productVariants.variantAttributes'])
            ->paginate(12);

        return $this->paginated($products->through(fn($p) => new ProductResource($p)));
    }
}
