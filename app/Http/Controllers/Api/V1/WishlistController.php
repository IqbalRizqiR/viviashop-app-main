<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\WishList;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;

class WishlistController extends BaseController
{
    public function index(Request $request)
    {
        $wishlists = WishList::where('user_id', $request->user()->id)
            ->with('product.images')
            ->latest()
            ->get();

        $products = $wishlists->map(function ($w) {
            return [
                'id' => $w->id,
                'product' => new ProductResource($w->product),
                'added_at' => $w->created_at?->toISOString(),
            ];
        });

        return $this->success($products);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $existing = WishList::where('user_id', $request->user()->id)
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($existing) {
            return $this->error('Product already in wishlist', 409);
        }

        $wishlist = WishList::create([
            'user_id' => $request->user()->id,
            'product_id' => $validated['product_id'],
        ]);

        return $this->success(['id' => $wishlist->id], 'Added to wishlist', 201);
    }

    public function destroy(Request $request, $id)
    {
        $wishlist = WishList::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $wishlist->delete();

        return $this->success(null, 'Removed from wishlist');
    }
}
