<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\BaseController;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Pembelian;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends BaseController
{
    public function revenue(Request $request)
    {
        $validated = $request->validate([
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        $start = $validated['start'];
        $end = $validated['end'];

        // Batch query 1: Daily order aggregates (1 query instead of N)
        $orderAggregates = Order::where('payment_status', 'paid')
            ->where('grand_total', '>', 0)
            ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
            ->selectRaw('DATE(created_at) as date, SUM(grand_total) as sales, SUM(shipping_cost) as shipping')
            ->groupBy('date')
            ->pluck(null, 'date')
            ->keyBy('date');

        // Batch query 2: COGS per day via single JOIN
        $cogsData = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.payment_status', 'paid')
            ->where('orders.grand_total', '>', 0)
            ->whereBetween(DB::raw('DATE(orders.created_at)'), [$start, $end])
            ->selectRaw('DATE(orders.created_at) as date, SUM(order_items.qty * products.harga_beli) as cogs')
            ->groupBy('date')
            ->pluck('cogs', 'date');

        // Batch query 3: Daily purchases
        $purchasesData = Pembelian::whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
            ->selectRaw('DATE(created_at) as date, SUM(total_harga) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        // Batch query 4: Daily expenses
        $expensesData = Pengeluaran::whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
            ->selectRaw('DATE(created_at) as date, SUM(nominal) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $data = [];
        $totals = ['sales' => 0, 'purchases' => 0, 'expenses' => 0, 'profit' => 0];
        $current = $start;

        while (strtotime($current) <= strtotime($end)) {
            $sales = (float) ($orderAggregates[$current]->sales ?? 0);
            $shipping = (float) ($orderAggregates[$current]->shipping ?? 0);
            $purchases = (float) ($purchasesData[$current] ?? 0);
            $expenses = (float) ($expensesData[$current] ?? 0);
            $cogs = (float) ($cogsData[$current] ?? 0);

            $netSales = $sales - $shipping;
            $profit = $netSales - $cogs - $expenses;

            $data[] = [
                'date' => $current,
                'sales' => $sales,
                'purchases' => $purchases,
                'expenses' => $expenses,
                'shipping' => $shipping,
                'net_sales' => $netSales,
                'cogs' => $cogs,
                'profit' => $profit,
                'margin' => $sales > 0 ? round(($profit / $sales) * 100, 2) : 0,
            ];

            $totals['sales'] += $sales;
            $totals['purchases'] += $purchases;
            $totals['expenses'] += $expenses;
            $totals['profit'] += $profit;

            $current = date('Y-m-d', strtotime('+1 day', strtotime($current)));
        }

        return $this->success(['daily' => $data, 'totals' => $totals]);
    }

    public function productPerformance(Request $request)
    {
        $validated = $request->validate([
            'start' => 'nullable|date',
            'end' => 'nullable|date',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        $query = OrderItem::select(
            'product_id',
            DB::raw('SUM(qty) as total_sold'),
            DB::raw('SUM(sub_total) as total_revenue'),
            DB::raw('AVG(base_price) as avg_price')
        )->with('product:id,name,sku,price,harga_beli');

        if (isset($validated['start'])) {
            $query->whereDate('created_at', '>=', $validated['start']);
        }
        if (isset($validated['end'])) {
            $query->whereDate('created_at', '<=', $validated['end']);
        }

        $products = $query->groupBy('product_id')
            ->orderBy('total_revenue', 'desc')
            ->take($validated['limit'] ?? 20)
            ->get();

        return $this->success($products);
    }
}
