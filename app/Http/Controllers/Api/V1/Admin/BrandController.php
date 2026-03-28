<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends BaseController
{
    public function index()
    {
        $brands = Brand::withCount('products')->orderBy('name')->get();
        return $this->success(BrandResource::collection($brands));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:brands,slug',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['is_active'] = $validated['is_active'] ?? true;

        $brand = Brand::create($validated);

        return $this->success(new BrandResource($brand), 'Brand created', 201);
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|max:255|unique:brands,slug,' . $id,
            'is_active' => 'nullable|boolean',
        ]);

        $brand->update($validated);

        return $this->success(new BrandResource($brand->fresh()), 'Brand updated');
    }

    public function destroy($id)
    {
        Brand::findOrFail($id)->delete();
        return $this->success(null, 'Brand deleted');
    }
}
