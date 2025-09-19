<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StockCardController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 12);
        
        if (!in_array($perPage, [10, 12, 20, 30, 50, 100])) {
            $perPage = 12;
        }
        
        $products = Product::with(['productVariants', 'productInventory'])
                          ->orderBy('name')
                          ->paginate($perPage);

        return view('admin.stock.index', compact('products'));
    }

    public function show($variantId)
    {
        $variant = ProductVariant::with(['product', 'variantAttributes'])->findOrFail($variantId);
        
        $movements = StockMovement::with(['purchase', 'order', 'printOrder'])
                                  ->where('variant_id', $variantId)
                                  ->orderBy('created_at', 'desc')
                                  ->paginate(50);

        return view('admin.stock.show', compact('variant', 'movements'));
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

        $movements = $query->orderBy('created_at', 'desc')->paginate(50);

        return view('admin.stock.movements', compact('movements'));
    }

    public function movementData(Request $request)
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

        $movements = $query->orderBy('created_at', 'desc')->get();

        return datatables()
            ->of($movements)
            ->addIndexColumn()
            ->addColumn('product_name', function ($movement) {
                $name = $movement->variant->product->name;
                if ($movement->variant->variantAttributes->count() > 0) {
                    $attributes = $movement->variant->variantAttributes->pluck('attribute_value')->implode(', ');
                    $name .= ' (' . $attributes . ')';
                }
                return $name;
            })
            ->addColumn('movement_type_label', function ($movement) {
                $color = $movement->movement_type === 'in' ? 'success' : 'danger';
                $icon = $movement->movement_type === 'in' ? 'fa-arrow-up' : 'fa-arrow-down';
                $label = $movement->movement_type === 'in' ? 'MASUK' : 'KELUAR';
                
                return '<span class="label label-' . $color . '">
                    <i class="fa ' . $icon . '"></i> ' . $label . '
                </span>';
            })
            ->addColumn('quantity_formatted', function ($movement) {
                $color = $movement->movement_type === 'in' ? 'text-success' : 'text-danger';
                $sign = $movement->movement_type === 'in' ? '+' : '-';
                
                return '<span class="' . $color . '">' . $sign . number_format($movement->quantity) . '</span>';
            })
            ->addColumn('stock_info', function ($movement) {
                return '<small>
                    Stok Lama: <strong>' . number_format($movement->old_stock) . '</strong><br>
                    Stok Baru: <strong>' . number_format($movement->new_stock) . '</strong>
                </small>';
            })
            ->addColumn('reference_info', function ($movement) {
                $info = '';
                switch ($movement->reference_type) {
                    case 'purchase':
                        $info = '<span class="label label-info">Pembelian #' . $movement->reference_id . '</span>';
                        break;
                    case 'order':
                        $info = '<span class="label label-warning">Order #' . $movement->reference_id . '</span>';
                        break;
                    case 'print_order':
                        $info = '<span class="label label-primary">Print Order #' . $movement->reference_id . '</span>';
                        break;
                    default:
                        $info = '<span class="label label-default">' . ucfirst($movement->reference_type ?? 'Manual') . '</span>';
                }
                
                if ($movement->notes) {
                    $info .= '<br><small class="text-muted">' . $movement->notes . '</small>';
                }
                
                return $info;
            })
            ->addColumn('created_at_formatted', function ($movement) {
                return $movement->created_at->format('d/m/Y H:i:s');
            })
            ->rawColumns(['movement_type_label', 'quantity_formatted', 'stock_info', 'reference_info'])
            ->make(true);
    }

    public function report(Request $request)
    {
        $dateFrom = $request->input('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', Carbon::now()->format('Y-m-d'));

        $summary = [
            'total_in' => StockMovement::where('movement_type', 'in')
                                     ->whereBetween('created_at', [$dateFrom, $dateTo])
                                     ->sum('quantity'),
            'total_out' => StockMovement::where('movement_type', 'out')
                                      ->whereBetween('created_at', [$dateFrom, $dateTo])
                                      ->sum('quantity'),
            'purchases' => StockMovement::where('movement_type', 'in')
                                      ->where('reason', 'purchase_confirmed')
                                      ->whereBetween('created_at', [$dateFrom, $dateTo])
                                      ->sum('quantity'),
            'sales' => StockMovement::where('movement_type', 'out')
                                   ->where('reason', 'order_confirmed')
                                   ->whereBetween('created_at', [$dateFrom, $dateTo])
                                   ->sum('quantity'),
            'print_orders' => StockMovement::where('movement_type', 'out')
                                         ->where('reason', 'print_order')
                                         ->whereBetween('created_at', [$dateFrom, $dateTo])
                                         ->sum('quantity'),
        ];

        $topProducts = StockMovement::selectRaw('variant_id, SUM(quantity) as total_movement')
                                  ->with('variant.product')
                                  ->whereBetween('created_at', [$dateFrom, $dateTo])
                                  ->groupBy('variant_id')
                                  ->orderBy('total_movement', 'desc')
                                  ->limit(10)
                                  ->get();

        return view('admin.stock.report', compact('summary', 'topProducts', 'dateFrom', 'dateTo'));
    }

    public function showProduct($productId)
    {
        $product = Product::with(['productVariants.variantAttributes'])->findOrFail($productId);
        
        $variants = $product->productVariants;
        
        $movements = collect();
        foreach ($variants as $variant) {
            $variantMovements = StockMovement::where('variant_id', $variant->id)
                                           ->orderBy('created_at', 'desc')
                                           ->get();
            $movements = $movements->merge($variantMovements);
        }
        
        $movements = $movements->sortByDesc('created_at')->take(100);

        return view('admin.stock.product', compact('product', 'variants', 'movements'));
    }
}
