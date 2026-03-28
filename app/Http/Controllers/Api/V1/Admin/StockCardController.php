<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\BaseController;
use App\Models\StockMovement;
use App\Models\Product;
use App\Models\ProductVariant;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StockCardController extends BaseController
{
    public function index(Request $request)
    {
        $perPage = min((int) $request->input('per_page', 20), 100);

        $query = Product::with(['productVariants.variantAttributes', 'productInventory']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        return $this->paginated($query->orderBy('name')->paginate($perPage));
    }

    public function show($variantId)
    {
        $variant = ProductVariant::with(['product', 'variantAttributes'])->findOrFail($variantId);

        $movements = StockMovement::with(['purchase', 'order', 'printOrder'])
            ->where('variant_id', $variantId)
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return $this->success([
            'variant' => $variant,
            'movements' => $movements,
        ]);
    }

    public function movements(Request $request)
    {
        $query = StockMovement::with(['variant.product', 'variant.variantAttributes']);

        if ($request->filled('variant_id')) {
            $query->where('variant_id', $request->variant_id);
        }
        if ($request->filled('movement_type')) {
            $query->where('movement_type', $request->movement_type);
        }
        if ($request->filled('reason')) {
            $query->where('reason', $request->reason);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return $this->paginated($query->orderBy('created_at', 'desc')->paginate(50));
    }

    public function report(Request $request)
    {
        $dateFrom = $request->input('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', Carbon::now()->format('Y-m-d'));

        $summary = [
            'total_in' => (int) StockMovement::where('movement_type', 'in')
                ->whereBetween('created_at', [$dateFrom, $dateTo])->sum('quantity'),
            'total_out' => (int) StockMovement::where('movement_type', 'out')
                ->whereBetween('created_at', [$dateFrom, $dateTo])->sum('quantity'),
            'purchases' => (int) StockMovement::where('movement_type', 'in')
                ->where('reason', 'purchase_confirmed')
                ->whereBetween('created_at', [$dateFrom, $dateTo])->sum('quantity'),
            'sales' => (int) StockMovement::where('movement_type', 'out')
                ->where('reason', 'order_confirmed')
                ->whereBetween('created_at', [$dateFrom, $dateTo])->sum('quantity'),
            'print_orders' => (int) StockMovement::where('movement_type', 'out')
                ->where('reason', 'print_order')
                ->whereBetween('created_at', [$dateFrom, $dateTo])->sum('quantity'),
        ];

        $topProducts = StockMovement::selectRaw('variant_id, SUM(quantity) as total_movement')
            ->with('variant.product')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy('variant_id')
            ->orderBy('total_movement', 'desc')
            ->limit(10)
            ->get();

        return $this->success([
            'summary' => $summary,
            'top_products' => $topProducts,
            'period' => ['from' => $dateFrom, 'to' => $dateTo],
        ]);
    }

    public function showProduct($productId)
    {
        $product = Product::with(['productVariants.variantAttributes'])->findOrFail($productId);

        $variantIds = $product->productVariants->pluck('id');

        $movements = StockMovement::whereIn('variant_id', $variantIds)
            ->with('variant')
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();

        return $this->success([
            'product' => $product,
            'variants' => $product->productVariants,
            'movements' => $movements,
        ]);
    }
}
