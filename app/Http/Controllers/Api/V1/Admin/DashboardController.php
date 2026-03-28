<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\BaseController;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductInventory;
use App\Models\EmployeePerformance;
use App\Models\Category;
use App\Models\Pembelian;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends BaseController
{
    public function index()
    {
        return $this->success([
            'revenue' => $this->getRevenueMetrics(),
            'orders' => $this->getOrderMetrics(),
            'inventory' => $this->getInventoryMetrics(),
            'employees' => $this->getEmployeeMetrics(),
            'charts' => $this->getChartData(),
            'recent_orders' => $this->getRecentOrders(),
            'top_products' => $this->getTopProducts(),
            'low_stock' => $this->getLowStock(),
        ]);
    }

    private function getRevenueMetrics()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Single query — conditional aggregation for all revenue periods
        $revenue = Order::where('payment_status', Order::PAID)
            ->selectRaw("SUM(CASE WHEN DATE(created_at) = ? THEN grand_total ELSE 0 END) as today", [$today->toDateString()])
            ->selectRaw("SUM(CASE WHEN created_at >= ? THEN grand_total ELSE 0 END) as week", [$thisWeek])
            ->selectRaw("SUM(CASE WHEN created_at >= ? THEN grand_total ELSE 0 END) as month", [$thisMonth])
            ->selectRaw("SUM(CASE WHEN created_at >= ? THEN grand_total ELSE 0 END) as year", [$thisYear])
            ->selectRaw("SUM(CASE WHEN created_at >= ? THEN base_total_price ELSE 0 END) as month_base", [$thisMonth])
            ->first();

        $pending = (float) Order::whereIn('payment_status', [Order::WAITING, Order::UNPAID])->sum('grand_total');

        // COGS — single join query
        $totalPurchases = (float) (OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.payment_status', Order::PAID)
            ->where('orders.created_at', '>=', $thisMonth)
            ->selectRaw('SUM(order_items.qty * products.harga_beli) as total')
            ->value('total') ?? 0);

        $lastMonthRevenue = (float) Order::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->where('payment_status', Order::PAID)->sum('grand_total');

        $monthRevenue = (float) ($revenue->month ?? 0);
        $growth = $lastMonthRevenue > 0
            ? (($monthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
            : 0;

        return [
            'today' => (float) ($revenue->today ?? 0),
            'week' => (float) ($revenue->week ?? 0),
            'month' => $monthRevenue,
            'year' => (float) ($revenue->year ?? 0),
            'pending' => $pending,
            'net_profit' => (float) (($revenue->month_base ?? 0) - $totalPurchases),
            'growth' => round($growth, 2),
        ];
    }

    private function getOrderMetrics()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        // Single query — conditional count for all periods + statuses
        $metrics = Order::selectRaw("COUNT(*) as total")
            ->selectRaw("SUM(CASE WHEN DATE(created_at) = ? THEN 1 ELSE 0 END) as today", [$today->toDateString()])
            ->selectRaw("SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as week", [$thisWeek])
            ->selectRaw("SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as month", [$thisMonth])
            ->selectRaw("SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as completed", [Order::COMPLETED])
            ->first();

        $avgValue = round(Order::where('payment_status', Order::PAID)->avg('grand_total') ?? 0, 2);
        $statusCounts = Order::select('status', DB::raw('count(*) as count'))->groupBy('status')->pluck('count', 'status');

        $total = (int) ($metrics->total ?? 0);
        $completed = (int) ($metrics->completed ?? 0);

        return [
            'today' => (int) ($metrics->today ?? 0),
            'week' => (int) ($metrics->week ?? 0),
            'month' => (int) ($metrics->month ?? 0),
            'total' => $total,
            'conversion_rate' => $total > 0 ? round(($completed / $total) * 100, 2) : 0,
            'average_value' => $avgValue,
            'status_counts' => $statusCounts,
        ];
    }

    private function getInventoryMetrics()
    {
        $topSelling = OrderItem::select('product_id', DB::raw('SUM(qty) as total_sold'))
            ->groupBy('product_id')->orderBy('total_sold', 'desc')
            ->with('product:id,name,sku')->first();

        return [
            'total_products' => Product::count(),
            'active_products' => Product::where('status', Product::ACTIVE)->count(),
            'low_stock_count' => ProductInventory::where('qty', '<=', 5)->count(),
            'stock_value' => (float) DB::table('product_inventories')
                ->join('products', 'product_inventories.product_id', '=', 'products.id')
                ->sum(DB::raw('product_inventories.qty * products.price')),
            'top_selling' => $topSelling ? ['name' => $topSelling->product->name ?? 'N/A', 'total_sold' => $topSelling->total_sold] : null,
        ];
    }

    private function getEmployeeMetrics()
    {
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'top_employee' => EmployeePerformance::select('employee_name', DB::raw('SUM(transaction_value) as total'))
                ->where('completed_at', '>=', $thisMonth)->groupBy('employee_name')->orderBy('total', 'desc')->first(),
            'team_revenue' => (float) EmployeePerformance::where('completed_at', '>=', $thisMonth)->sum('transaction_value'),
            'active_employees' => EmployeePerformance::select('employee_name', DB::raw('COUNT(*) as count'))
                ->where('completed_at', '>=', $thisMonth)->groupBy('employee_name')->orderBy('count', 'desc')->get(),
        ];
    }

    private function getChartData()
    {
        $startDate = Carbon::now()->subDays(6)->startOfDay();

        // 2 queries instead of 14 — batch aggregation with GROUP BY
        $revenueData = Order::where('created_at', '>=', $startDate)
            ->where('payment_status', Order::PAID)
            ->selectRaw('DATE(created_at) as date, SUM(grand_total) as total')
            ->groupBy('date')->pluck('total', 'date');

        $orderData = Order::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')->pluck('total', 'date');

        $labels = [];
        $revenue = [];
        $orders = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateKey = $date->toDateString();
            $labels[] = $date->format('M d');
            $revenue[] = (float) ($revenueData[$dateKey] ?? 0);
            $orders[] = (int) ($orderData[$dateKey] ?? 0);
        }

        return compact('labels', 'revenue', 'orders');
    }

    private function getRecentOrders()
    {
        return Order::with('orderItems.product:id,name')
            ->latest()->take(10)->get()
            ->map(fn($o) => [
                'id' => $o->id, 'code' => $o->code, 'status' => $o->status,
                'payment_status' => $o->payment_status, 'grand_total' => (float) $o->grand_total,
                'customer' => $o->customer_first_name, 'date' => $o->created_at?->toISOString(),
                'items_count' => $o->orderItems->count(),
            ]);
    }

    private function getTopProducts()
    {
        return OrderItem::select('product_id', DB::raw('SUM(qty) as total_sold'), DB::raw('SUM(sub_total) as total_revenue'))
            ->with('product:id,name,sku,price')
            ->groupBy('product_id')->orderBy('total_revenue', 'desc')
            ->take(10)->get();
    }

    private function getLowStock()
    {
        return ProductInventory::with('product:id,name,sku')
            ->where('qty', '<=', 5)->orderBy('qty')->get()
            ->map(fn($i) => [
                'product' => $i->product->name ?? 'N/A',
                'sku' => $i->product->sku ?? '',
                'qty' => $i->qty,
            ]);
    }
}
