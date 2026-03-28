<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductInventory;
use App\Models\EmployeePerformance;
use App\Models\Category;
use App\Models\Pembelian;
use App\Models\Supplier;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $data = $this->getDashboardData();
        return view('admin.dashboard', $data);
    }

    private function getDashboardData()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();

        $totalRevenue = $this->getRevenueMetrics();
        $orderMetrics = $this->getOrderMetrics();
        $inventoryMetrics = $this->getInventoryMetrics();
        $employeeMetrics = $this->getEmployeeMetrics();
        $chartData = $this->getChartData();
        $recentActivities = $this->getRecentActivities();
        $topProducts = $this->getTopProducts();
        $lowStockProducts = $this->getLowStockProducts();
        $deadStockProducts = $this->getDeadStockProducts();
        $supplierPerformance = $this->getSupplierPerformance();
        $categoryPerformance = $this->getCategoryPerformance();
        $shippingMethodStats = $this->getShippingMethodStats();

        return compact(
            'totalRevenue',
            'orderMetrics', 
            'inventoryMetrics',
            'employeeMetrics',
            'chartData',
            'recentActivities',
            'topProducts',
            'lowStockProducts',
            'deadStockProducts',
            'supplierPerformance',
            'categoryPerformance',
            'shippingMethodStats'
        );
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

        $pendingPayments = Order::whereIn('payment_status', [Order::WAITING, Order::UNPAID])
            ->sum('grand_total');

        $totalPurchases = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.payment_status', Order::PAID)
            ->where('orders.created_at', '>=', $thisMonth)
            ->selectRaw('SUM(order_items.qty * products.harga_beli) as total')
            ->value('total') ?? 0;

        $lastMonthRevenue = Order::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->where('payment_status', Order::PAID)->sum('grand_total');

        $monthRevenue = (float) ($revenue->month ?? 0);
        $revenueGrowth = $lastMonthRevenue > 0
            ? (($monthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;

        return [
            'today' => (float) ($revenue->today ?? 0),
            'week' => (float) ($revenue->week ?? 0),
            'month' => $monthRevenue,
            'year' => (float) ($revenue->year ?? 0),
            'pending_payments' => (float) $pendingPayments,
            'net_profit' => (float) (($revenue->month_base ?? 0) - $totalPurchases),
            'growth_percentage' => round($revenueGrowth, 2)
        ];
    }

    private function getOrderMetrics()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        // Single query — conditional count
        $metrics = Order::selectRaw("COUNT(*) as total")
            ->selectRaw("SUM(CASE WHEN DATE(created_at) = ? THEN 1 ELSE 0 END) as today", [$today->toDateString()])
            ->selectRaw("SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as week", [$thisWeek])
            ->selectRaw("SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as month", [$thisMonth])
            ->selectRaw("SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as completed", [Order::COMPLETED])
            ->first();

        $totalOrders = (int) ($metrics->total ?? 0);
        $completedOrders = (int) ($metrics->completed ?? 0);
        $conversionRate = $totalOrders > 0 ? ($completedOrders / $totalOrders) * 100 : 0;

        $averageOrderValue = Order::where('payment_status', Order::PAID)->avg('grand_total');
        $statusCounts = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')->pluck('count', 'status')->toArray();

        return [
            'today' => (int) ($metrics->today ?? 0),
            'week' => (int) ($metrics->week ?? 0),
            'month' => (int) ($metrics->month ?? 0),
            'total' => $totalOrders,
            'conversion_rate' => round($conversionRate, 2),
            'average_value' => round($averageOrderValue ?? 0, 2),
            'status_counts' => $statusCounts
        ];
    }

    private function getInventoryMetrics()
    {
        $totalProducts = Product::count();
        $activeProducts = Product::where('status', Product::ACTIVE)->count();
        $inactiveProducts = Product::where('status', Product::INACTIVE)->count();

        $lowStockCount = ProductInventory::where('qty', '<=', 5)->count();
        
        $totalStockValue = DB::table('product_inventories')
            ->join('products', 'product_inventories.product_id', '=', 'products.id')
            ->sum(DB::raw('product_inventories.qty * products.price'));

        $deadStockCount = $this->getDeadStockProducts()->count();

        $topSellingProduct = OrderItem::select('product_id', DB::raw('SUM(qty) as total_sold'))
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->with('product')
            ->first();

        return [
            'total_products' => $totalProducts,
            'active_products' => $activeProducts,
            'inactive_products' => $inactiveProducts,
            'low_stock_count' => $lowStockCount,
            'stock_value' => $totalStockValue,
            'dead_stock_count' => $deadStockCount,
            'top_selling_product' => $topSellingProduct
        ];
    }

    private function getEmployeeMetrics()
    {
        $thisMonth = Carbon::now()->startOfMonth();
        
        $topEmployee = EmployeePerformance::select('employee_name', DB::raw('SUM(transaction_value) as total_revenue'))
            ->where('completed_at', '>=', $thisMonth)
            ->groupBy('employee_name')
            ->orderBy('total_revenue', 'desc')
            ->first();

        $totalTeamRevenue = EmployeePerformance::where('completed_at', '>=', $thisMonth)
            ->sum('transaction_value');

        $activeEmployees = EmployeePerformance::select('employee_name', DB::raw('COUNT(*) as transaction_count'))
            ->where('completed_at', '>=', $thisMonth)
            ->groupBy('employee_name')
            ->orderBy('transaction_count', 'desc')
            ->get();

        return [
            'top_employee' => $topEmployee,
            'team_revenue' => $totalTeamRevenue,
            'active_employees' => $activeEmployees
        ];
    }

    private function getChartData()
    {
        $startDate = Carbon::now()->subDays(6)->startOfDay();

        // 2 queries instead of 14
        $revenueData = Order::where('created_at', '>=', $startDate)
            ->where('payment_status', Order::PAID)
            ->selectRaw('DATE(created_at) as date, SUM(grand_total) as total')
            ->groupBy('date')->pluck('total', 'date');

        $orderData = Order::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')->pluck('total', 'date');

        $last7Days = [];
        $revenue = [];
        $orders = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateKey = $date->toDateString();
            $last7Days[] = $date->format('M d');
            $revenue[] = (float) ($revenueData[$dateKey] ?? 0);
            $orders[] = (int) ($orderData[$dateKey] ?? 0);
        }

        return [
            'labels' => $last7Days,
            'revenue' => $revenue,
            'orders' => $orders
        ];
    }

    private function getRecentActivities()
    {
        return Order::with(['orderItems.product'])
            ->latest()
            ->take(10)
            ->get();
    }

    private function getTopProducts()
    {
        return OrderItem::select('product_id', DB::raw('SUM(qty) as total_sold'), DB::raw('SUM(sub_total) as total_revenue'))
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('total_revenue', 'desc')
            ->take(10)
            ->get();
    }

    private function getLowStockProducts()
    {
        return ProductInventory::with('product')
            ->where('qty', '<=', 5)
            ->orderBy('qty', 'asc')
            ->get();
    }

    private function getDeadStockProducts()
    {
        $cutoffDate = Carbon::now()->subDays(90);
        
        $recentSoldProducts = OrderItem::where('created_at', '>=', $cutoffDate)
            ->pluck('product_id')
            ->unique();

        return ProductInventory::with('product')
            ->whereNotIn('product_id', $recentSoldProducts)
            ->where('qty', '>', 0)
            ->get();
    }

    private function getSupplierPerformance()
    {
        return Pembelian::with('supplier')
            ->select('id_supplier', DB::raw('SUM(total_harga) as total_purchases'), DB::raw('COUNT(*) as purchase_count'))
            ->groupBy('id_supplier')
            ->orderBy('total_purchases', 'desc')
            ->take(5)
            ->get();
    }

    private function getCategoryPerformance()
    {
        return DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->join('categories', 'product_categories.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(order_items.sub_total) as revenue'), DB::raw('SUM(order_items.qty) as units_sold'))
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('revenue', 'desc')
            ->get();
    }

    private function getShippingMethodStats()
    {
        return Order::select('shipping_service_name', DB::raw('COUNT(*) as count'))
            ->whereNotNull('shipping_service_name')
            ->groupBy('shipping_service_name')
            ->orderBy('count', 'desc')
            ->get();
    }
}
