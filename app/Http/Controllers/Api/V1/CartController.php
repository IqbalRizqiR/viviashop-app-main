<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\CartItemResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductInventory;
use Illuminate\Http\Request;

class CartController extends BaseController
{
    public function index(Request $request)
    {
        $cart = Cart::getOrCreateForUser($request->user()->id);
        $cart->load('items.product.images', 'items.variant.variantAttributes');

        return $this->success([
            'items' => CartItemResource::collection($cart->items),
            'subtotal' => (float) $cart->subtotal,
            'total_items' => $cart->total_items,
            'total_weight' => $cart->total_weight,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'variant_id' => 'nullable|integer|exists:product_variants,id',
            'qty' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $variant = null;
        $price = $product->price;
        $options = ['type' => $product->type, 'weight' => $product->weight ?? 0];

        // Handle configurable products
        if ($product->type === 'configurable' && !empty($validated['variant_id'])) {
            $variant = ProductVariant::where('id', $validated['variant_id'])
                ->where('product_id', $product->id)
                ->where('is_active', true)
                ->firstOrFail();

            $price = $variant->price;
            $options['weight'] = $variant->weight ?? $product->weight ?? 0;
            $options['variant_name'] = $variant->name;
            $options['variant_sku'] = $variant->sku;

            // Stock check
            if ($variant->stock < $validated['qty']) {
                return $this->error("Insufficient stock. Available: {$variant->stock}", 422);
            }

            // Add variant attributes
            $attrs = $variant->variantAttributes->mapWithKeys(fn($a) => [$a->attribute_name => $a->attribute_value]);
            $options['attributes'] = $attrs->toArray();
        } else {
            // Simple product stock check
            $inventory = ProductInventory::where('product_id', $product->id)->first();
            if ($inventory && $inventory->qty < $validated['qty']) {
                return $this->error("Insufficient stock. Available: {$inventory->qty}", 422);
            }
        }

        $cart = Cart::getOrCreateForUser($request->user()->id);

        // Check if item already exists — update qty instead
        $existing = $cart->items()
            ->where('product_id', $product->id)
            ->where('variant_id', $validated['variant_id'] ?? null)
            ->first();

        if ($existing) {
            $newQty = $existing->qty + $validated['qty'];

            // Re-check stock for updated quantity
            if ($variant && $variant->stock < $newQty) {
                return $this->error("Insufficient stock. Available: {$variant->stock}", 422);
            }

            $existing->update(['qty' => $newQty, 'price' => $price]);
            $item = $existing;
        } else {
            $item = $cart->items()->create([
                'product_id' => $product->id,
                'variant_id' => $validated['variant_id'] ?? null,
                'qty' => $validated['qty'],
                'price' => $price,
                'options' => $options,
            ]);
        }

        $item->load('product.images', 'variant.variantAttributes');

        return $this->success(new CartItemResource($item), 'Item added to cart', 201);
    }

    public function update(Request $request, $itemId)
    {
        $validated = $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        $cart = Cart::getOrCreateForUser($request->user()->id);
        $item = $cart->items()->findOrFail($itemId);

        // Stock check
        if ($item->variant_id) {
            $variant = ProductVariant::find($item->variant_id);
            if ($variant && $variant->stock < $validated['qty']) {
                return $this->error("Insufficient stock. Available: {$variant->stock}", 422);
            }
        } else {
            $inventory = ProductInventory::where('product_id', $item->product_id)->first();
            if ($inventory && $inventory->qty < $validated['qty']) {
                return $this->error("Insufficient stock. Available: {$inventory->qty}", 422);
            }
        }

        $item->update(['qty' => $validated['qty']]);
        $item->load('product.images', 'variant.variantAttributes');

        return $this->success(new CartItemResource($item), 'Cart updated');
    }

    public function destroy(Request $request, $itemId)
    {
        $cart = Cart::getOrCreateForUser($request->user()->id);
        $cart->items()->findOrFail($itemId)->delete();

        return $this->success(null, 'Item removed from cart');
    }

    public function clear(Request $request)
    {
        $cart = Cart::getOrCreateForUser($request->user()->id);
        $cart->items()->delete();

        return $this->success(null, 'Cart cleared');
    }
}
