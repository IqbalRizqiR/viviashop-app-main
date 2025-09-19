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

        $revenueToday = Order::whereDate('created_at', $today)
            ->where('payment_status', Order::PAID)
            ->sum('grand_total');

        $revenueThisWeek = Order::where('created_at', '>=', $thisWeek)
            ->where('payment_status', Order::PAID)
            ->sum('grand_total');

        $revenueThisMonth = Order::where('created_at', '>=', $thisMonth)
            ->where('payment_status', Order::PAID)
            ->sum('grand_total');

        $revenueThisYear = Order::where('created_at', '>=', $thisYear)
            ->where('payment_status', Order::PAID)
            ->sum('grand_total');

        $pendingPayments = Order::where('payment_status', Order::WAITING)
            ->orWhere('payment_status', Order::UNPAID)
            ->sum('grand_total');

        $totalPurchases = Pembelian::sum('total_harga');
        $netProfit = $revenueThisMonth - $totalPurchases;

        $lastMonthRevenue = Order::whereBetween('created_at', [
            Carbon::now()->subMonth()->startOfMonth(),
            Carbon::now()->subMonth()->endOfMonth()
        ])->where('payment_status', Order::PAID)->sum('grand_total');

        $revenueGrowth = $lastMonthRevenue > 0 ? 
            (($revenueThisMonth - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;

        return [
            'today' => $revenueToday,
            'week' => $revenueThisWeek,
            'month' => $revenueThisMonth,
            'year' => $revenueThisYear,
            'pending_payments' => $pendingPayments,
            'net_profit' => $netProfit,
            'growth_percentage' => round($revenueGrowth, 2)
        ];
    }

    private function getOrderMetrics()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        $ordersToday = Order::whereDate('created_at', $today)->count();
        $ordersThisWeek = Order::where('created_at', '>=', $thisWeek)->count();
        $ordersThisMonth = Order::where('created_at', '>=', $thisMonth)->count();

        $totalOrders = Order::count();
        $completedOrders = Order::where('status', Order::COMPLETED)->count();
        $conversionRate = $totalOrders > 0 ? ($completedOrders / $totalOrders) * 100 : 0;

        $averageOrderValue = Order::where('payment_status', Order::PAID)
            ->avg('grand_total');

        $statusCounts = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'today' => $ordersToday,
            'week' => $ordersThisWeek,
            'month' => $ordersThisMonth,
            'total' => $totalOrders,
            'conversion_rate' => round($conversionRate, 2),
            'average_value' => round($averageOrderValue, 2),
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
        $last7Days = [];
        $revenueData = [];
        $orderData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $last7Days[] = $date->format('M d');
            
            $dailyRevenue = Order::whereDate('created_at', $date)
                ->where('payment_status', Order::PAID)
                ->sum('grand_total');
            $revenueData[] = $dailyRevenue;

            $dailyOrders = Order::whereDate('created_at', $date)->count();
            $orderData[] = $dailyOrders;
        }

        return [
            'labels' => $last7Days,
            'revenue' => $revenueData,
            'orders' => $orderData
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
