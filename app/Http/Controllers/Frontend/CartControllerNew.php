<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ProductVariantService;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Throwable;

class CartController extends Controller
{
    protected $productVariantService;

    public function __construct(ProductVariantService $productVariantService)
    {
        $this->productVariantService = $productVariantService;
    }

    public function index()
    {
        $items = Cart::content();
        $cart = Cart::content()->count();
        $setting = Setting::first();
        view()->share('setting', $setting);
        view()->share('countCart', $cart);

        return view('frontend.carts.index', compact('items'));
    }

    private function _getItemQuantity($itemQty)
    {
        $items = Cart::content();
        $itemQuantity = $itemQty;
        if ($items) {
            foreach ($items as $item) {
                $itemQuantity += $item->qty;
            }
        }
        return $itemQuantity;
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please login to add items to cart'
            ]);
        }

        $params = $request->except('_token');
        $product = Product::findOrFail($params['product_id']);
        
        try {
            if ($product->type === 'configurable') {
                return $this->addConfigurableProductToCart($product, $params);
            } else {
                return $this->addSimpleProductToCart($product, $params);
            }
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to add product to cart: ' . $e->getMessage()
            ]);
        }
    }

    private function addConfigurableProductToCart(Product $product, array $params)
    {
        if (!isset($params['variant_id'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please select product variant'
            ]);
        }

        $variant = ProductVariant::where('id', $params['variant_id'])
            ->where('product_id', $product->id)
            ->where('is_active', true)
            ->first();

        if (!$variant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Selected variant not found or not available'
            ]);
        }

        $requestedQty = (int) ($params['qty'] ?? 1);
        $currentCartQty = $this->getVariantCartQuantity($variant->id);
        $totalQty = $currentCartQty + $requestedQty;

        if ($variant->stock < $totalQty) {
            return response()->json([
                'status' => 'error',
                'message' => "Insufficient stock. Available: {$variant->stock}, Requested: {$totalQty}"
            ]);
        }

        $cartItemId = $variant->id . '_variant';
        
        Cart::add([
            'id' => $cartItemId,
            'name' => $variant->name,
            'price' => $variant->price,
            'qty' => $requestedQty,
            'weight' => $variant->weight ?? 0,
            'options' => [
                'product_id' => $product->id,
                'variant_id' => $variant->id,
                'type' => 'configurable',
                'slug' => $product->slug,
                'image' => $product->productImages->first()?->path ?? '',
                'attributes' => $variant->variantAttributes->pluck('attribute_value', 'attribute_name')->toArray(),
            ]
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Product added to cart successfully',
            'cart_count' => Cart::content()->count()
        ]);
    }

    private function addSimpleProductToCart(Product $product, array $params)
    {
        $requestedQty = (int) ($params['qty'] ?? 1);
        $currentCartQty = $this->getProductCartQuantity($product->id);
        $totalQty = $currentCartQty + $requestedQty;

        $availableStock = $product->productInventory?->qty ?? 0;
        
        if ($availableStock < $totalQty) {
            return response()->json([
                'status' => 'error',
                'message' => "Insufficient stock. Available: {$availableStock}, Requested: {$totalQty}"
            ]);
        }

        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'qty' => $requestedQty,
            'weight' => $product->weight ?? 0,
            'options' => [
                'product_id' => $product->id,
                'variant_id' => null,
                'type' => 'simple',
                'slug' => $product->slug,
                'image' => $product->productImages->first()?->path ?? '',
            ]
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Product added to cart successfully',
            'cart_count' => Cart::content()->count()
        ]);
    }

    private function getVariantCartQuantity($variantId)
    {
        $cartItemId = $variantId . '_variant';
        $item = Cart::content()->get($cartItemId);
        return $item ? $item->qty : 0;
    }

    private function getProductCartQuantity($productId)
    {
        $item = Cart::content()->get($productId);
        return $item ? $item->qty : 0;
    }

    public function update(Request $request)
    {
        $cartItemId = $request->input('cart_item_id');
        $quantity = $request->input('quantity');

        Cart::update($cartItemId, $quantity);

        return response()->json([
            'status' => 'success',
            'message' => 'Cart updated successfully'
        ]);
    }

    public function destroy($id)
    {
        Cart::remove($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Item removed from cart'
        ]);
    }
}
