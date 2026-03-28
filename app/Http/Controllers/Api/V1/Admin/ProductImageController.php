<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\BaseController;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends BaseController
{
    public function index($productId)
    {
        $product = Product::with('images')->findOrFail($productId);

        return $this->success([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'images' => $product->images->map(fn($img) => [
                'id' => $img->id,
                'path' => $img->path,
                'url' => asset('storage/' . $img->path),
            ]),
        ]);
    }

    public function store(Request $request, $productId)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'required|file|mimes:jpg,jpeg,png,webp,gif|max:5120',
        ]);

        $product = Product::findOrFail($productId);
        $uploaded = [];

        foreach ($request->file('images') as $image) {
            $path = $image->store('product/images', 'public');
            $productImage = ProductImage::create([
                'path' => $path,
                'product_id' => $product->id,
            ]);
            $uploaded[] = [
                'id' => $productImage->id,
                'path' => $path,
                'url' => asset('storage/' . $path),
            ];
        }

        return $this->success($uploaded, count($uploaded) . ' image(s) uploaded', 201);
    }

    public function destroy($productId, $imageId)
    {
        $image = ProductImage::where('product_id', $productId)->findOrFail($imageId);

        // Delete file from storage
        if ($image->path && Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }

        $image->delete();

        return $this->success(null, 'Image deleted');
    }

    public function reorder(Request $request, $productId)
    {
        $validated = $request->validate([
            'image_ids' => 'required|array',
            'image_ids.*' => 'integer|exists:product_images,id',
        ]);

        foreach ($validated['image_ids'] as $position => $imageId) {
            ProductImage::where('id', $imageId)
                ->where('product_id', $productId)
                ->update(['position' => $position]);
        }

        return $this->success(null, 'Images reordered');
    }
}
