<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\BaseController;
use App\Models\Product;
use App\Models\ProductInventory;
use App\Models\ProductVariant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends BaseController
{
    public function index(Request $request)
    {
        $query = ProductInventory::with('product:id,name,sku,price,type');

        if ($search = $request->input('search')) {
            $query->whereHas('product', fn($q) => $q->where('name', 'like', "%{$search}%")->orWhere('sku', 'like', "%{$search}%"));
        }

        if ($request->input('low_stock')) {
            $query->where('qty', '<=', 5);
        }

        return $this->success($query->orderBy('qty', 'asc')->paginate(20));
    }

    public function variantStock(Request $request)
    {
        $query = ProductVariant::with('product:id,name,sku')
            ->where('is_active', true);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->input('low_stock')) {
            $query->where('stock', '<=', 5);
        }

        return $this->success($query->orderBy('stock', 'asc')->paginate(20));
    }

    public function updateStock(Request $request, $productId)
    {
        $validated = $request->validate([
            'qty' => 'required|integer|min:0',
            'reason' => 'nullable|string|max:255',
        ]);

        $inventory = ProductInventory::updateOrCreate(
            ['product_id' => $productId],
            ['qty' => $validated['qty']]
        );

        return $this->success($inventory->load('product:id,name,sku'), 'Stock updated');
    }

    public function updateVariantStock(Request $request, $variantId)
    {
        $validated = $request->validate([
            'stock' => 'required|integer|min:0',
            'reason' => 'nullable|string|max:255',
        ]);

        $variant = ProductVariant::findOrFail($variantId);
        $variant->update(['stock' => $validated['stock']]);

        return $this->success($variant->load('product:id,name,sku'), 'Variant stock updated');
    }

    public function deadStock()
    {
        $cutoff = Carbon::now()->subDays(90);

        $recentSold = DB::table('order_items')
            ->where('created_at', '>=', $cutoff)
            ->pluck('product_id')->unique();

        $deadStock = ProductInventory::with('product:id,name,sku,price')
            ->whereNotIn('product_id', $recentSold)
            ->where('qty', '>', 0)
            ->get();

        return $this->success($deadStock);
    }

    public function stockCard($productId)
    {
        $product = Product::with(['productInventory', 'productVariants'])->findOrFail($productId);

        // Get order items as stock movements
        $movements = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('order_items.product_id', $productId)
            ->select(
                'orders.code as order_code',
                'orders.status',
                'orders.payment_status',
                'order_items.qty',
                'order_items.created_at'
            )
            ->orderBy('order_items.created_at', 'desc')
            ->limit(50)
            ->get();

        return $this->success([
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'current_stock' => $product->productInventory->qty ?? 0,
            ],
            'variants' => $product->productVariants->map(fn($v) => [
                'id' => $v->id, 'name' => $v->name, 'sku' => $v->sku, 'stock' => $v->stock,
            ]),
            'movements' => $movements,
        ]);
    }
}
