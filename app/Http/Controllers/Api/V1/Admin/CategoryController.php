<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends BaseController
{
    public function index()
    {
        $categories = Category::with('parent')->withCount('products')->get();
        return $this->success(CategoryResource::collection($categories));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category = Category::create($validated);

        return $this->success(new CategoryResource($category), 'Category created', 201);
    }

    public function show($id)
    {
        $category = Category::with(['parent', 'children'])->withCount('products')->findOrFail($id);
        return $this->success(new CategoryResource($category));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|max:255|unique:categories,slug,' . $id,
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $category->update($validated);

        return $this->success(new CategoryResource($category->fresh()), 'Category updated');
    }

    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        return $this->success(null, 'Category deleted');
    }
}
