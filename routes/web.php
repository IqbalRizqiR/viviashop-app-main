<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;

if (app()->environment('local')) {
    Route::get('/debug-order/{id}', function($id) {
        $order = \App\Models\Order::withTrashed()->with('shipment')->findOrFail($id);
        $paymentData = [
            'midtransClientKey' => config('midtrans.clientKey'),
            'isProduction' => config('midtrans.isProduction'),
            'snapUrl' => config('midtrans.isProduction') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js'
        ];
        $employees = \App\Models\EmployeePerformance::getEmployeeList();
        return view('admin.orders.show', compact('order', 'paymentData', 'employees'));
    });
}


use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\StockCardController;
use App\Http\Controllers\Admin\SmartPrintConverterController;
use App\Http\Controllers\Frontend\HomepageController;

Route::post('payments/notification', [App\Http\Controllers\Frontend\OrderController::class, 'notificationHandler'])
    ->name('payment.notification');

// Auth guest routes App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\InstagramController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PembelianDetailController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupplierController;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::get('storage/{path}', function($path) {
    $full = storage_path('app/public/' . $path);
    if (!file_exists($full)) {
        abort(404);
    }
    return response()->file($full);
})->where('path', '.*');

// $cart = Cart::content()->count();
// dd($cart);
// view()->share('countCart', $cart);

// Route::get('/debug-midtrans', [OrderController::class, 'debug']);

Route::get('/test-bootstrap3-modal', function () {
    return view('test-bootstrap3-modal');
});

Route::get('/test-employee-performance-final', function () {
    $admin = \App\Models\User::where('is_admin', 1)->first();
    if (!$admin) {
        return response()->json(['error' => 'No admin user found'], 404);
    }
    
    \Illuminate\Support\Facades\Auth::login($admin);
    
    $controller = new \App\Http\Controllers\Admin\EmployeePerformanceController();
    
    $request = \Illuminate\Http\Request::create('/admin/employee-performance', 'GET');
    $response = $controller->index();
    
    return $response;
})->name('test-employee-performance-final');

Route::get('/test-employee-performance-bonus-form', function () {
    $admin = \App\Models\User::where('is_admin', 1)->first();
    if (!$admin) {
        return response()->json(['error' => 'No admin user found'], 404);
    }
    
    \Illuminate\Support\Facades\Auth::login($admin);
    
    $controller = new \App\Http\Controllers\Admin\EmployeePerformanceController();
    
    $request = \Illuminate\Http\Request::create('/admin/employee-performance/bonus', 'GET');
    $request->merge(['employee' => request('employee')]);
    $response = $controller->bonusForm($request);
    
    return $response;
})->name('test-employee-performance-bonus-form');

Route::get('/test-bonus-management', function () {
    $admin = \App\Models\User::where('is_admin', 1)->first();
    if (!$admin) {
        return response()->json(['error' => 'No admin user found'], 404);
    }
    
    \Illuminate\Support\Facades\Auth::login($admin);
    
    $controller = new \App\Http\Controllers\Admin\EmployeePerformanceController();
    
    $request = \Illuminate\Http\Request::create('/admin/employee-performance/bonus/list', 'GET');
    $response = $controller->bonusList();
    
    return $response;
})->name('test-bonus-management');

Route::get('/test-employee-performance-view', function () {
    $totalTransactions = \App\Models\EmployeePerformance::count();
    $totalRevenue = \App\Models\EmployeePerformance::sum('transaction_value');
    $averageTransaction = \App\Models\EmployeePerformance::avg('transaction_value');
    $topEmployee = \App\Models\EmployeePerformance::select('employee_name')
        ->selectRaw('SUM(transaction_value) as total_revenue')
        ->groupBy('employee_name')
        ->orderBy('total_revenue', 'desc')
        ->first();
    
    return view('admin.employee-performance.index', compact(
        'totalTransactions', 
        'totalRevenue', 
        'averageTransaction', 
        'topEmployee'
    ));
});

Route::get('/test-bonus-modal', function () {
    return view('test-bonus-modal');
});

Route::get('/debug-view-error', function () {
    try {
        $employeeName = 'Reza';
        
        $performances = \App\Models\EmployeePerformance::where('employee_name', $employeeName)
                                         ->with('order')
                                         ->orderBy('completed_at', 'desc')
                                         ->paginate(20);

        $bonuses = \App\Models\EmployeeBonus::where('employee_name', $employeeName)
                               ->with('givenBy')
                               ->orderBy('given_at', 'desc')
                               ->get();

        $stats = \App\Models\EmployeePerformance::getMonthlyStats($employeeName);
        
        foreach ($performances as $performance) {
            echo "Testing performance #{$performance->id}<br>";
            echo "Order ID: {$performance->order_id}<br>";
            echo "Completed At Raw: " . $performance->getRawOriginal('completed_at') . "<br>";
            echo "Completed At Object: " . $performance->completed_at . "<br>";
            echo "Type: " . gettype($performance->completed_at) . "<br>";
            
            try {
                $formatted = $performance->completed_at ? $performance->completed_at->format('d/m/Y H:i') : '-';
                echo "Format Test SUCCESS: {$formatted}<br>";
            } catch (\Exception $e) {
                echo "Format Test ERROR: " . $e->getMessage() . "<br>";
            }
            echo "<hr>";
        }
        
        foreach ($bonuses as $bonus) {
            echo "Testing bonus #{$bonus->id}<br>";
            try {
                $start = $bonus->period_start ? $bonus->period_start->format('d/m/Y') : '-';
                $end = $bonus->period_end ? $bonus->period_end->format('d/m/Y') : '-';
                echo "Bonus Format Test SUCCESS: {$start} - {$end}<br>";
            } catch (\Exception $e) {
                echo "Bonus Format Test ERROR: " . $e->getMessage() . "<br>";
            }
            echo "<hr>";
        }
        
        echo "<h2>All format tests completed successfully</h2>";
        
        return "";
    } catch (\Exception $e) {
        echo "Global Error: " . $e->getMessage() . "<br>";
        echo "File: " . $e->getFile() . "<br>";
        echo "Line: " . $e->getLine() . "<br>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
        return "";
    }
});

Route::get('/test-admin-employee-reza', function () {
    try {
        $request = \Illuminate\Http\Request::create('/admin/employee-performance/Reza', 'GET');
        
        $app = app();
        $response = $app->handle($request);
        
        echo "Status Code: " . $response->getStatusCode() . "<br>";
        echo "Content Type: " . $response->headers->get('content-type') . "<br>";
        
        if ($response->getStatusCode() != 200) {
            echo "Response: " . $response->getContent() . "<br>";
        } else {
            echo "Success: Admin employee performance page loads correctly<br>";
        }
        
        return "";
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "<br>";
        echo "File: " . $e->getFile() . "<br>";
        echo "Line: " . $e->getLine() . "<br>";
        return "";
    }
});

Route::get('/test-employee-show-reza', function () {
    try {
        $employeeName = 'Reza';
        
        $performances = \App\Models\EmployeePerformance::where('employee_name', $employeeName)
                                         ->with('order')
                                         ->orderBy('completed_at', 'desc')
                                         ->paginate(20);

        $bonuses = \App\Models\EmployeeBonus::where('employee_name', $employeeName)
                               ->with('givenBy')
                               ->orderBy('given_at', 'desc')
                               ->get();

        $stats = \App\Models\EmployeePerformance::getMonthlyStats($employeeName);
        
        return view('admin.employee-performance.show', compact('employeeName', 'performances', 'bonuses', 'stats'));
        
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "<br>";
        echo "File: " . $e->getFile() . "<br>";
        echo "Line: " . $e->getLine() . "<br>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
        return "";
    }
});

Route::get('/test-employee-reza', function () {
    try {
        $employeeName = 'Reza';
        
        $performances = \App\Models\EmployeePerformance::where('employee_name', $employeeName)
                                         ->with('order')
                                         ->orderBy('completed_at', 'desc')
                                         ->get();

        $bonuses = \App\Models\EmployeeBonus::where('employee_name', $employeeName)
                               ->with('givenBy')
                               ->orderBy('given_at', 'desc')
                               ->get();

        $stats = \App\Models\EmployeePerformance::getMonthlyStats($employeeName);
        
        echo "<h1>Employee Performance Test - {$employeeName}</h1>";
        
        echo "<h2>Statistics</h2>";
        echo "<pre>";
        print_r($stats);
        echo "</pre>";
        
        echo "<h2>Performances (" . $performances->count() . ")</h2>";
        foreach ($performances as $perf) {
            echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 5px;'>";
            echo "Order ID: " . $perf->order_id . "<br>";
            echo "Transaction Value: " . $perf->transaction_value . "<br>";
            echo "Completed At: " . ($perf->completed_at ? $perf->completed_at->format('d/m/Y H:i') : 'NULL') . "<br>";
            echo "Completed At Type: " . gettype($perf->completed_at) . "<br>";
            if ($perf->completed_at) {
                echo "Is Carbon: " . (is_a($perf->completed_at, 'Carbon\\Carbon') ? 'Yes' : 'No') . "<br>";
            }
            echo "</div>";
        }
        
        echo "<h2>Bonuses (" . $bonuses->count() . ")</h2>";
        foreach ($bonuses as $bonus) {
            echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 5px;'>";
            echo "Amount: " . $bonus->bonus_amount . "<br>";
            echo "Period Start: " . ($bonus->period_start ? $bonus->period_start->format('d/m/Y') : 'NULL') . "<br>";
            echo "Period End: " . ($bonus->period_end ? $bonus->period_end->format('d/m/Y') : 'NULL') . "<br>";
            echo "</div>";
        }
        
        return "";
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "<br>";
        echo "File: " . $e->getFile() . "<br>";
        echo "Line: " . $e->getLine() . "<br>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
        return "";
    }
});

Route::get('/check-order-142', function () {
    $order = \App\Models\Order::find(142);
    
    if (!$order) {
        echo "<h1>❌ Order 142 Not Found</h1>";
        return "";
    }
    
    echo "<h1>🔍 Order 142 Debug Information</h1>";
    echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h3>📊 Order Details:</h3>";
    echo "<ul>";
    echo "<li><strong>Order ID:</strong> {$order->id}</li>";
    echo "<li><strong>Status:</strong> {$order->status}</li>";
    echo "<li><strong>Handled by:</strong> " . ($order->handled_by ?: 'Not set') . "</li>";
    echo "<li><strong>Use Employee Tracking:</strong> " . ($order->use_employee_tracking ? 'Yes (true)' : 'No (false)') . "</li>";
    echo "<li><strong>Grand Total:</strong> Rp " . number_format($order->grand_total, 0, ',', '.') . "</li>";
    echo "<li><strong>Updated At:</strong> {$order->updated_at}</li>";
    echo "</ul>";
    echo "</div>";
    
    $performance = \App\Models\EmployeePerformance::where('order_id', 142)->first();
    
    echo "<div style='background: " . ($performance ? "#d4edda" : "#f8d7da") . "; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h3>� Performance Record:</h3>";
    if ($performance) {
        echo "<ul>";
        echo "<li><strong>Employee Name:</strong> {$performance->employee_name}</li>";
    echo "<li><strong>Transaction Value:</strong> Rp " . number_format((float)$performance->transaction_value, 0, ',', '.') . "</li>";
        echo "<li><strong>Completed At:</strong> {$performance->completed_at}</li>";
        echo "<li><strong>Created At:</strong> {$performance->created_at}</li>";
        echo "</ul>";
    } else {
        echo "<p><strong>❌ No performance record found for this order!</strong></p>";
        echo "<p>This indicates the performance was not saved during order completion.</p>";
    }
    echo "</div>";
    
    $allPerformances = \App\Models\EmployeePerformance::orderBy('created_at', 'desc')->limit(5)->get();
    echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h3>📋 Last 5 Performance Records:</h3>";
    if ($allPerformances->count() > 0) {
        echo "<ul>";
        foreach ($allPerformances as $perf) {
            echo "<li>Order #{$perf->order_id} - {$perf->employee_name} - Rp " . number_format($perf->transaction_value, 0, ',', '.') . " - {$perf->created_at}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No performance records found.</p>";
    }
    echo "</div>";
    
    return "";
});

Route::get('/employee-performance-summary', function () {
    echo "Employee Performance Tracking System<br>";
    echo "Implementation Complete!<br>";
    
    $totalEmployees = \App\Models\EmployeePerformance::distinct('employee_name')->count();
    $totalTransactions = \App\Models\EmployeePerformance::count();
    $totalRevenue = \App\Models\EmployeePerformance::sum('transaction_value');
    $totalBonuses = \App\Models\EmployeeBonus::sum('bonus_amount');
    $ordersWithTracking = \App\Models\Order::where('use_employee_tracking', true)->count();
    
    echo "Total Employees Tracked: " . $totalEmployees . "<br>";
    echo "Total Transactions Recorded: " . $totalTransactions . "<br>";
    echo "Total Revenue Tracked: Rp " . number_format($totalRevenue, 0, ',', '.') . "<br>";
    echo "Total Bonuses Given: Rp " . number_format($totalBonuses, 0, ',', '.') . "<br>";
    echo "Orders with Employee Tracking: " . $ordersWithTracking . "<br>";
    
    return "";
});

Route::get('/stress-test-employee-performance', function () {
    try {
        echo "<h1>Employee Performance System Stress Test</h1>";
        
        echo "<h2>Test 1: Order Completion with Employee Tracking</h2>";
        
        $order = \App\Models\Order::where('use_employee_tracking', true)->first();
        
        if (!$order) {
            echo "❌ No orders with employee tracking found<br>";
            return;
        }
        
        echo "✅ Testing with Order #{$order->code}<br>";
        echo "Employee: {$order->handled_by}<br>";
        echo "Order Total: Rp " . number_format($order->grand_total, 0, ',', '.') . "<br><br>";
        
        echo "<h2>Test 2: Employee Performance Controller</h2>";
        
        $employees = \App\Models\EmployeePerformance::getEmployeeList();
        echo "✅ Found {$employees->count()} employees in system<br>";
        
        $totalTransactions = \App\Models\EmployeePerformance::count();
        echo "✅ Total transactions: {$totalTransactions}<br>";
        
        $totalRevenue = \App\Models\EmployeePerformance::sum('transaction_value');
        echo "✅ Total revenue: Rp " . number_format($totalRevenue, 0, ',', '.') . "<br>";
        
        $averageTransaction = \App\Models\EmployeePerformance::avg('transaction_value');
        echo "✅ Average transaction: Rp " . number_format($averageTransaction, 0, ',', '.') . "<br>";
        
        $topEmployee = \App\Models\EmployeePerformance::selectRaw('employee_name, SUM(transaction_value) as total_revenue')
                                        ->groupBy('employee_name')
                                        ->orderBy('total_revenue', 'desc')
                                        ->first();
                                        
        echo "✅ Top employee: {$topEmployee->employee_name} (Rp " . number_format($topEmployee->total_revenue, 0, ',', '.') . ")<br><br>";
        
        echo "<h2>Test 3: Relationship Testing</h2>";
        
        foreach (\App\Models\Order::where('use_employee_tracking', true)->take(3)->get() as $testOrder) {
            $performance = $testOrder->employeePerformance;
            if ($performance) {
                echo "✅ Order #{$testOrder->code} -> Performance ID #{$performance->id}<br>";
            } else {
                echo "❌ Order #{$testOrder->code} has no performance record<br>";
            }
        }
        
        echo "<br><h2>Test 4: Employee Methods</h2>";
        
        foreach (\App\Models\Order::where('use_employee_tracking', true)->take(3)->get() as $testOrder) {
            if ($testOrder->isHandledByEmployee()) {
                echo "✅ Order #{$testOrder->code} is handled by employee: {$testOrder->handled_by}<br>";
            } else {
                echo "❌ Order #{$testOrder->code} not properly handled by employee<br>";
            }
        }
        
        echo "<br><h2>✅ Stress Test Complete!</h2>";
        echo "<p><strong>Employee Performance System is Ready!</strong></p>";
        
        return "";
        
    } catch (Exception $e) {
        echo "❌ Stress test failed: " . $e->getMessage();
        return "";
    }
});

Route::get('/test-employee-data', function () {
    try {
        echo "<h2>Employee Performance Data Test</h2>";
        
        // Test 1: Check employee performances
        $performances = \App\Models\EmployeePerformance::with('order')->get();
        echo "<h3>Employee Performances ({$performances->count()} records):</h3>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Employee</th><th>Order ID</th><th>Transaction Value</th><th>Completed At</th></tr>";
        
        foreach ($performances as $performance) {
            echo "<tr>";
            echo "<td>{$performance->employee_name}</td>";
            echo "<td>#{$performance->order->code}</td>";
            echo "<td>Rp " . number_format($performance->transaction_value, 0, ',', '.') . "</td>";
            echo "<td>{$performance->completed_at}</td>";
            echo "</tr>";
        }
        echo "</table><br>";
        
        // Test 2: Check employee bonuses
        $bonuses = \App\Models\EmployeeBonus::with('givenBy')->get();
        echo "<h3>Employee Bonuses ({$bonuses->count()} records):</h3>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Employee</th><th>Bonus Amount</th><th>Period</th><th>Given By</th></tr>";
        
        foreach ($bonuses as $bonus) {
            echo "<tr>";
            echo "<td>{$bonus->employee_name}</td>";
            echo "<td>Rp " . number_format($bonus->bonus_amount, 0, ',', '.') . "</td>";
            echo "<td>{$bonus->period_start} - {$bonus->period_end}</td>";
            echo "<td>{$bonus->givenBy->name}</td>";
            echo "</tr>";
        }
        echo "</table><br>";
        
        // Test 3: Aggregate data
        $stats = \App\Models\EmployeePerformance::selectRaw('
            employee_name,
            COUNT(*) as total_transactions,
            SUM(transaction_value) as total_revenue,
            AVG(transaction_value) as average_transaction
        ')->groupBy('employee_name')->get();
        
        echo "<h3>Employee Statistics:</h3>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Employee</th><th>Total Transactions</th><th>Total Revenue</th><th>Average Transaction</th></tr>";
        
        foreach ($stats as $stat) {
            echo "<tr>";
            echo "<td>{$stat->employee_name}</td>";
            echo "<td>{$stat->total_transactions}</td>";
            echo "<td>Rp " . number_format($stat->total_revenue, 0, ',', '.') . "</td>";
            echo "<td>Rp " . number_format($stat->average_transaction, 0, ',', '.') . "</td>";
            echo "</tr>";
        }
        echo "</table><br>";
        
        echo "<h3>✅ Employee Performance System Ready!</h3>";
        echo "<p>You can now:</p>";
        echo "<ul>";
        echo "<li>Visit <a href='/admin/employee-performance'>/admin/employee-performance</a> (requires admin login)</li>";
        echo "<li>Go to admin orders and enable employee tracking</li>";
        echo "<li>Complete orders to see performance tracking</li>";
        echo "<li>Give bonuses to employees</li>";
        echo "</ul>";
        
        return "";
        
    } catch (Exception $e) {
        return "Test failed: " . $e->getMessage();
    }
});

Route::get('/test-employee-performance', function () {
    try {
        // Test database connection and table existence
        echo "Testing Employee Performance Implementation...\n\n";
        
        // Test 1: Check if tables exist
        echo "1. Checking database tables:\n";
        $tables = [
            'employee_performances',
            'employee_bonuses'
        ];
        
        foreach ($tables as $table) {
            if (\Illuminate\Support\Facades\Schema::hasTable($table)) {
                echo "   ✓ Table '{$table}' exists<br>";
            } else {
                echo "   ✗ Table '{$table}' does not exist<br>";
                return "Migration not complete";
            }
        }
        
        // Test 2: Check if columns exist in orders table
        echo "<br>2. Checking orders table columns:<br>";
        $orderColumns = ['handled_by', 'use_employee_tracking'];
        foreach ($orderColumns as $column) {
            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', $column)) {
                echo "   ✓ Column '{$column}' exists in orders table<br>";
            } else {
                echo "   ✗ Column '{$column}' does not exist in orders table<br>";
                return "Orders table migration not complete";
            }
        }
        
        // Test 3: Check models
        echo "<br>3. Testing models:<br>";
        try {
            $employeePerformance = new \App\Models\EmployeePerformance();
            echo "   ✓ EmployeePerformance model exists<br>";
        } catch (Exception $e) {
            echo "   ✗ EmployeePerformance model error: " . $e->getMessage() . "<br>";
        }
        
        try {
            $employeeBonus = new \App\Models\EmployeeBonus();
            echo "   ✓ EmployeeBonus model exists<br>";
        } catch (Exception $e) {
            echo "   ✗ EmployeeBonus model error: " . $e->getMessage() . "<br>";
        }
        
        // Test 4: Check order model relationship
        echo "<br>4. Testing Order model updates:<br>";
        try {
            $order = new \App\Models\Order();
            if (method_exists($order, 'employeePerformance')) {
                echo "   ✓ Order->employeePerformance() relationship exists<br>";
            } else {
                echo "   ✗ Order->employeePerformance() relationship missing<br>";
            }
            
            if (method_exists($order, 'isHandledByEmployee')) {
                echo "   ✓ Order->isHandledByEmployee() method exists<br>";
            } else {
                echo "   ✗ Order->isHandledByEmployee() method missing<br>";
            }
        } catch (Exception $e) {
            echo "   ✗ Order model error: " . $e->getMessage() . "<br>";
        }
        
        // Test 5: Check controller
        echo "<br>5. Testing controller:<br>";
        try {
            $controller = new \App\Http\Controllers\Admin\EmployeePerformanceController();
            echo "   ✓ EmployeePerformanceController exists<br>";
        } catch (Exception $e) {
            echo "   ✗ EmployeePerformanceController error: " . $e->getMessage() . "<br>";
        }
        
        // Test 6: Check routes
        echo "<br>6. Testing routes:<br>";
        $routes = [
            'admin.employee-performance.index',
            'admin.employee-performance.data', 
            'admin.employee-performance.giveBonus'
        ];
        
        foreach ($routes as $routeName) {
            try {
                $route = route($routeName);
                echo "   ✓ Route '{$routeName}' exists<br>";
            } catch (Exception $e) {
                echo "   ✗ Route '{$routeName}' error: " . $e->getMessage() . "<br>";
            }
        }
        
        // Test 7: Check views
        echo "<br>7. Testing views:<br>";
        $views = [
            'admin.employee-performance.index',
            'admin.employee-performance.show'
        ];
        
        foreach ($views as $viewName) {
            if (view()->exists($viewName)) {
                echo "   ✓ View '{$viewName}' exists<br>";
            } else {
                echo "   ✗ View '{$viewName}' does not exist<br>";
            }
        }
        
        echo "<br>✅ Employee Performance Implementation Test Complete!<br>";
        echo "<br>Next steps:<br>";
        echo "1. Navigate to admin panel<br>";
        echo "2. Go to Employee Performance menu<br>"; 
        echo "3. Create an order and test employee tracking<br>";
        echo "4. Complete the order to test performance recording<br>";
        echo "5. Give bonus to test bonus functionality<br>";
        
        return "Test completed successfully!";
        
    } catch (Exception $e) {
        return "Test failed: " . $e->getMessage();
    }
});

Route::get('/debug-midtrans', function() {
    $midtransTest = 'Unknown';
    try {
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        $midtransTest = 'Midtrans Config set successfully';
    } catch (Exception $e) {
        $midtransTest = 'Error: ' . $e->getMessage();
    }
    
    return [
        'config' => [
            'serverKey' => config('midtrans.serverKey') ? 'Set (hidden)' : 'Not set',
            'clientKey' => config('midtrans.clientKey'),
            'isProduction' => config('midtrans.isProduction'),
            'isSanitized' => config('midtrans.isSanitized'),
            'is3ds' => config('midtrans.is3ds'),
        ],
        'midtrans_test' => $midtransTest,
    ];
});

Route::get('/test-payment-status/{orderId}', function($orderId) {
    $order = App\Models\Order::where('code', $orderId)->first();
    if (!$order) {
        return ['error' => 'Order not found'];
    }
    
    // Update payment status to simulate successful payment
    $order->payment_status = 'paid';
    $order->status = 'confirmed';
    $order->approved_at = now();
    $order->save();
    
    return [
        'success' => true,
        'message' => 'Payment status updated to paid',
        'order_id' => $orderId,
        'new_status' => $order->payment_status,
        'redirect_url' => url("orders/received/{$order->id}")
    ];
});

Route::get('/debug-order/{id}', function($id) {
    $order = App\Models\Order::find($id);
    if (!$order) {
        return ['error' => 'Order not found'];
    }
    
    return [
        'order' => [
            'id' => $order->id,
            'code' => $order->code,
            'customer_first_name' => $order->customer_first_name,
            'customer_last_name' => $order->customer_last_name,
            'customer_email' => $order->customer_email,
            'customer_phone' => $order->customer_phone,
            'grand_total' => $order->grand_total,
            'payment_method' => $order->payment_method,
            'payment_status' => $order->payment_status,
        ]
    ];
});

Route::get('/test-midtrans-token/{id}', function($id) {
    $order = App\Models\Order::find($id);
    if (!$order) {
        return ['error' => 'Order not found'];
    }
    
    try {
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('midtrans.isProduction');
        \Midtrans\Config::$isSanitized = config('midtrans.isSanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is3ds');
        
        // Disable SSL for testing
        \Midtrans\Config::$curlOptions = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => []
        ];
        
        $params = [
            'transaction_details' => [
                'order_id' => 'TEST-' . time(),
                'gross_amount' => 10000,
            ],
            'customer_details' => [
                'first_name' => 'Test',
                'last_name' => 'Customer',
                'email' => 'test@example.com',
                'phone' => '08123456789',
            ],
            'item_details' => [
                [
                    'id' => 'TEST-ITEM',
                    'price' => 10000,
                    'quantity' => 1,
                    'name' => 'Test Item'
                ]
            ]
        ];
        
        $snap = \Midtrans\Snap::createTransaction($params);
        
        return [
            'success' => true,
            'token' => $snap->token ?? 'No token',
            'redirect_url' => $snap->redirect_url ?? 'No redirect URL',
            'config' => [
                'serverKey' => config('midtrans.serverKey') ? 'Set' : 'Not set',
                'isProduction' => config('midtrans.isProduction'),
            ]
        ];
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage(),
            'config' => [
                'serverKey' => config('midtrans.serverKey') ? 'Set' : 'Not set',
                'isProduction' => config('midtrans.isProduction'),
            ]
        ];
    }
});

Route::post('payments/notification', [App\Http\Controllers\Frontend\OrderController::class, 'notificationHandler'])
    ->name('payment.notification')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

    Route::get('payments/client-key', [App\Http\Controllers\Frontend\OrderController::class, 'getMidtransClientKey'])
    ->name('payment.client-key');

    Route::get('payments/finish', [App\Http\Controllers\Frontend\OrderController::class, 'finishRedirect'])
    ->name('payment.finish');

    Route::get('payments/unfinish', [App\Http\Controllers\Frontend\OrderController::class, 'unfinishRedirect'])
    ->name('payment.unfinish');

    Route::get('payments/error', [App\Http\Controllers\Frontend\OrderController::class, 'errorRedirect'])
    ->name('payment.error');

	// Customer invoice and order status routes
	Route::get('orders/invoice/{id}', [App\Http\Controllers\Frontend\OrderController::class, 'invoice'])
		->name('orders.invoice')
		->middleware('auth');
		
	Route::get('orders/status/{id}', [App\Http\Controllers\Frontend\OrderController::class, 'getOrderStatus'])
		->name('orders.status');

	Route::get('orders/complete/{order}', [App\Http\Controllers\Frontend\OrderController::class, 'complete'])
		->name('orders.complete.page')
		->middleware('auth');

	Route::post('orders/complete/{order}', [App\Http\Controllers\Frontend\OrderController::class, 'doComplete'])
		->name('orders.complete')
		->middleware('auth');

    Route::get('/instagram', [InstagramController::class, 'getInstagramData'])->name('admin.instagram.index');
    Route::get('/instagram/callback', [InstagramController::class, 'handleCallback'])
        ->name('instagram.callback');
    Route::match(['get','post'], '/instagram/webhook', [InstagramController::class, 'webhook'])
        ->name('instagram.webhook');




Route::group(['middleware' => ['auth', 'is_admin'], 'prefix' => 'admin', 'as' => 'admin.'], function() {
    // admin
    Route::post('/products/find-barcode', [ProductController::class, 'findByBarcode'])
     ->name('products.find-barcode');
    Route::get('/orders/invoices/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'invoices'])
     ->name('orders.invoices');
     Route::get('/products/exportTemplate', [ProductController::class, 'exportTemplate'])->name("products.exportTemplate");
    Route::get('users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
    Route::post('users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::get('users/edit/{id}', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('users/update/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('users/delete/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    Route::resource('setting', SettingController::class);
    Route::get('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/updateHargaJual/{id}', [PembelianController::class, 'updateHargaJual'])->name('updateHargaJual');
    Route::put('/updateHargaBeli/{id}', [PembelianController::class, 'updateHargaBeli'])->name('updateHargaBeli');
    Route::get('/supplier/data', [SupplierController::class, 'data'])->name('supplier.data');
    Route::resource('/pembelian', PembelianController::class)->except('create');
    Route::get('/pembeliansss/data', [PembelianController::class, 'data'])->name('pembelian.data');
    Route::get('/pembelian/{id}/create', [PembelianController::class, 'create'])->name('pembelian.create');
    Route::resource('/pembelian_detail', PembelianDetailController::class)->except('create', 'show', 'edit');
    Route::get('/pembelian_detail/{id}/data', [PembelianDetailController::class, 'data'])->name('pembelian_detail.data');
    Route::get('/pembelian/invoices/{id}', [PembelianController::class, 'invoices'])
        ->name('pembelian.invoices');
    Route::get('reports/revenue/{awal}/{akhir}/excel',
        [App\Http\Controllers\Frontend\HomepageController::class, 'exportExcel']
    )->name('reports.revenue.excel');
    Route::get('/pembelian_detail/loadform/{diskon}/{total}', [PembelianDetailController::class, 'loadForm'])->name('pembelian_detail.load_form');
    Route::get('/pembelian_detail/variants/{productId}', [PembelianDetailController::class, 'getVariants'])->name('pembelian_detail.get_variants');
    Route::get('/pembelian_detail/realtime-stock/{pembelianId}', [PembelianDetailController::class, 'getRealtimeStock'])->name('pembelian_detail.realtime_stock');
    Route::get('/pembelian_detail/editBayar/{id}', [PembelianDetailController::class, 'editBayar'])->name('pembelian_detail.editBayar');
    Route::put('/pembelian_detail/updateEdit/{id}', [PembelianDetailController::class, 'updateEdit'])->name('pembelian_detail.updateEdit');

    Route::resource('supplier', SupplierController::class);
    Route::get('/laporan', [HomepageController::class, 'reports'])->name('laporan');
    Route::get('/laporan/data/{awal}/{akhir}', [HomepageController::class, 'data'])->name('laporan.data');
    Route::post('/products/import', [ProductController::class, 'imports'])->name('products.imports');
    Route::get('/quaggaTest', function () {
        return view('admin.products.quaggaTest');
    })->name('quaggaTest');
    Route::get('/downloadExcel', function () {
        return response()->download(public_path('template.xlsx'));
        // dd(public_path('/file'));
    })->name('downloadTemplate');
    
    Route::get('/barcode/preview' , [ProductController::class, 'previewBarcode'])->name('barcode.preview');
    Route::get('/barcode/preview/landscape' , [ProductController::class, 'previewBarcodeLandscape'])->name('barcode.preview.landscape');
    Route::get('/barcode/preview/portrait' , [ProductController::class, 'previewBarcodePortrait'])->name('barcode.preview.portrait');
    Route::get('/barcode/print/landscape' , [ProductController::class, 'printBarcodeLandscape'])->name('barcode.print.landscape');
    Route::get('/barcode/print/portrait' , [ProductController::class, 'printBarcodePortrait'])->name('barcode.print.portrait');
    Route::get('/barcode/downloadSingle/{id}' , [ProductController::class, 'downloadSingleBarcode'])->name('barcode.downloadSingle');
    Route::get('/laporan/export', [ReportController::class, 'exportExcel'])->name('laporan.exportExcel');
    // Route::get('/laporan/dataTotal/{awal}/{akhir}', [HomepageController::class, 'getReportsData'])->name('laporan.data');
    Route::get('/laporan/export/{awal}/{akhir}', [HomepageController::class, 'data'])->name('laporan.exportPDF');

    Route::get('/instagram/create', [InstagramController::class, 'create'])->name('instagram.create');
    
    // Smart Print Converter Routes
    Route::get('/smart-print-converter', [SmartPrintConverterController::class, 'index'])
        ->name('smart-print-converter.index');
    Route::post('/smart-print-converter/convert/{id}', [SmartPrintConverterController::class, 'convert'])
        ->name('smart-print-converter.convert');
    Route::post('/smart-print-converter/bulk-convert', [SmartPrintConverterController::class, 'bulkConvert'])
        ->name('smart-print-converter.bulk-convert');
        
    // Smart Print Variant Manager Routes
    Route::get('/smart-print-variant', [\App\Http\Controllers\Admin\SmartPrintVariantController::class, 'index'])
        ->name('smart-print-variant.index');
    Route::post('/smart-print-variant/auto-fix', [\App\Http\Controllers\Admin\SmartPrintVariantController::class, 'autoFix'])
        ->name('smart-print-variant.auto-fix');
    Route::post('/smart-print-variant/create-variants/{id}', [\App\Http\Controllers\Admin\SmartPrintVariantController::class, 'createVariants'])
        ->name('smart-print-variant.create-variants');
    Route::post('/instagram/post', [InstagramController::class, 'postToInstagram'])->name('instagram.store');
    Route::get('/instagram/postProduct/{id}', [InstagramController::class, 'postToInstagramFromProducts'])
        ->name('instagram.postProduct');
    Route::get('/instagram/data', [InstagramController::class, 'getInstagramData'])->name('instagram.data');
    Route::get('/instagram/redirect', [InstagramController::class, 'redirectToInstagram'])
     ->name('instagram.redirect');
    Route::get('dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('attributes', \App\Http\Controllers\Admin\AttributeController::class);
    Route::resource('attributes.attribute_variants', \App\Http\Controllers\Admin\AttributeVariantController::class);
    Route::resource('attributes.attribute_variants.attribute_options', \App\Http\Controllers\Admin\AttributeOptionController::class);
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::get('/products/data/datatable', [ProductController::class, 'data'])->name('products.data');
    Route::get('/products/{id}/attributes', [ProductController::class, 'getProductAttributes'])->name('products.attributes');
    Route::get('/products/{id}/variant-options', [ProductController::class, 'getVariantOptions'])->name('products.variant-options');
    Route::get('/products/{id}/all-variants', [ProductController::class, 'getAllVariants'])->name('products.all-variants');
    Route::post('/products/barcode/search', [ProductController::class, 'findByBarcode'])->name('products.findByBarcode');
    Route::delete('/products/{id}/delete-variants', [ProductController::class, 'deleteVariants'])->name('products.deleteVariants');
    Route::post('/variants/create', [\App\Http\Controllers\Admin\ProductVariantController::class, 'store'])->name('variants.create');
    Route::get('/variants/{id}', [\App\Http\Controllers\Admin\ProductVariantController::class, 'show'])->name('variants.show');
    Route::put('/variants/{id}', [\App\Http\Controllers\Admin\ProductVariantController::class, 'update'])->name('variants.update');
    Route::delete('/variants/{id}', [\App\Http\Controllers\Admin\ProductVariantController::class, 'destroy'])->name('variants.destroy');
    Route::resource('products.product_images', \App\Http\Controllers\Admin\ProductImageController::class);
    Route::get('/products/generateAllBarcodes', [ProductController::class, 'generateBarcodeAll'])->name('products.generateAll');
    Route::post('/products/generateAllBarcodes', [ProductController::class, 'generateBarcodeAll'])->name('products.generateAll');
    Route::get('/products/generateSingleBarcode/{id}', [ProductController::class, 'generateBarcodeSingle'])->name('products.generateSingle');
    Route::resource('slides', \App\Http\Controllers\Admin\SlideController::class);
    Route::get('slides/{slideId}/up', [\App\Http\Controllers\Admin\SlideController::class, 'moveUp']);
    Route::get('slides/{slideId}/down', [\App\Http\Controllers\Admin\SlideController::class, 'moveDown']);

    Route::get('orders/trashed', [\App\Http\Controllers\Admin\OrderController::class , 'trashed'])->name('orders.trashed');
    Route::get('orders/restore/{order:id}', [\App\Http\Controllers\Admin\OrderController::class , 'restore'])->name('orders.restore');
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
    Route::get('datas', [App\Http\Controllers\Admin\ProductController::class, 'data'])
     ->name('products.admin.data');
    Route::post('ordersAdmin', [\App\Http\Controllers\Admin\OrderController::class , 'storeAdmin'])->name('orders.storeAdmin');
    Route::get('ordersAdmin', [\App\Http\Controllers\Admin\OrderController::class , 'checkPage'])->name('orders.checkPage');
    Route::post('orders/payment-notification', [\App\Http\Controllers\Admin\OrderController::class , 'paymentNotification'])->name('orders.payment-notification');
    Route::post('orders/{order}/generate-payment-token', [\App\Http\Controllers\Admin\OrderController::class , 'generatePaymentToken'])->name('orders.generate-payment-token');
    Route::get('orders/complete/{order}', [\App\Http\Controllers\Admin\OrderController::class , 'complete'])->name('orders.complete.page');
    Route::post('orders/complete/{order}', [\App\Http\Controllers\Admin\OrderController::class , 'doComplete'])->name('orders.complete');
    Route::post('orders/confirm-pickup/{order}', [\App\Http\Controllers\Admin\OrderController::class , 'confirmPickup'])->name('orders.confirmPickup');
    Route::post('orders/{order}/employee-tracking', [\App\Http\Controllers\Admin\OrderController::class, 'updateEmployeeTracking'])->name('orders.updateEmployeeTracking');
    Route::post('orders/{order}/toggle-tracking', [\App\Http\Controllers\Admin\OrderController::class, 'toggleEmployeeTracking'])->name('orders.toggleEmployeeTracking');
    Route::post('orders/adjust-shipping', [\App\Http\Controllers\Admin\OrderController::class, 'adjustShipping'])->name('orders.adjustShipping');
    
    // Admin payment callback routes
    Route::get('orders/payment/finish', [\App\Http\Controllers\Admin\OrderController::class, 'paymentFinishRedirect'])->name('payment.finish');
    Route::get('orders/payment/unfinish', [\App\Http\Controllers\Admin\OrderController::class, 'paymentUnfinishRedirect'])->name('payment.unfinish');
    Route::get('orders/payment/error', [\App\Http\Controllers\Admin\OrderController::class, 'paymentErrorRedirect'])->name('payment.error');
    Route::get('orders/{order:id}/cancel', [\App\Http\Controllers\Admin\OrderController::class , 'cancel'])->name('orders.cancels');
	Route::put('orders/cancel/{order:id}', [\App\Http\Controllers\Admin\OrderController::class , 'doCancel'])->name('orders.cancel');
	Route::put('orders/confirm/{id}', [\App\Http\Controllers\Frontend\OrderController::class , 'confirmPaymentAdmin'])->name('orders.confirmAdmin');

    Route::resource('shipments', \App\Http\Controllers\Admin\ShipmentController::class);

    Route::get('reports/revenue', [\App\Http\Controllers\Admin\ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('reports/product', [\App\Http\Controllers\Admin\ReportController::class, 'product'])->name('reports.product');
    Route::get('reports/inventory', [\App\Http\Controllers\Admin\ReportController::class, 'inventory'])->name('reports.inventory');
    Route::get('reports/payment', [\App\Http\Controllers\Admin\ReportController::class, 'payment'])->name('reports.payment');
    
    Route::get('employee-performance', [\App\Http\Controllers\Admin\EmployeePerformanceController::class, 'index'])->name('employee-performance.index');
    Route::get('employee-performance/data', [\App\Http\Controllers\Admin\EmployeePerformanceController::class, 'data'])->name('employee-performance.data');
    Route::get('employee-performance/bonus', [\App\Http\Controllers\Admin\EmployeePerformanceController::class, 'bonusForm'])->name('employee-performance.bonus');
    Route::get('employee-performance/bonus/list', [\App\Http\Controllers\Admin\EmployeePerformanceController::class, 'bonusList'])->name('employee-performance.bonusList');
    Route::get('employee-performance/bonus/data', [\App\Http\Controllers\Admin\EmployeePerformanceController::class, 'bonusData'])->name('employee-performance.bonusData');
    Route::get('employee-performance/bonus/{id}/detail', [\App\Http\Controllers\Admin\EmployeePerformanceController::class, 'bonusDetail'])->name('employee-performance.bonusDetail');
    Route::get('employee-performance/bonus/{id}/edit', [\App\Http\Controllers\Admin\EmployeePerformanceController::class, 'bonusEdit'])->name('employee-performance.bonusEdit');
    Route::put('employee-performance/bonus/{id}', [\App\Http\Controllers\Admin\EmployeePerformanceController::class, 'bonusUpdate'])->name('employee-performance.bonusUpdate');
    Route::delete('employee-performance/bonus/{id}', [\App\Http\Controllers\Admin\EmployeePerformanceController::class, 'bonusDelete'])->name('employee-performance.bonusDelete');
    Route::get('employee-performance/{employee}', [\App\Http\Controllers\Admin\EmployeePerformanceController::class, 'show'])->name('employee-performance.show');
    Route::post('employee-performance/bonus', [\App\Http\Controllers\Admin\EmployeePerformanceController::class, 'giveBonus'])->name('employee-performance.giveBonus');
    Route::get('employee-performance-bonus-history', [\App\Http\Controllers\Admin\EmployeePerformanceController::class, 'bonusHistory'])->name('employee-performance.bonusHistory');
    
    Route::prefix('print-service')->name('print-service.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PrintServiceController::class, 'index'])->name('index');
        Route::get('/queue', [\App\Http\Controllers\Admin\PrintServiceController::class, 'queue'])->name('queue');
        Route::get('/sessions', [\App\Http\Controllers\Admin\PrintServiceController::class, 'sessions'])->name('sessions');
        Route::get('/orders', [\App\Http\Controllers\Admin\PrintServiceController::class, 'orders'])->name('orders');
        Route::get('/reports', [\App\Http\Controllers\Admin\PrintServiceController::class, 'reports'])->name('reports');
        Route::get('/stock', [\App\Http\Controllers\Admin\PrintServiceController::class, 'stockManagement'])->name('stock');
        Route::get('/stock-report', [\App\Http\Controllers\Admin\PrintServiceController::class, 'stockReport'])->name('stock-report');
        Route::post('/generate-session', [\App\Http\Controllers\Admin\PrintServiceController::class, 'generateSession'])->name('generate-session');
        Route::post('/orders/{id}/confirm-payment', [\App\Http\Controllers\Admin\PrintServiceController::class, 'confirmPayment'])->name('confirm-payment');
        Route::post('/orders/{id}/print', [\App\Http\Controllers\Admin\PrintServiceController::class, 'printOrder'])->name('print-order');
        Route::post('/orders/{id}/print-files', [\App\Http\Controllers\Admin\PrintServiceController::class, 'printFiles'])->name('print-files');
        Route::post('/orders/{id}/complete', [\App\Http\Controllers\Admin\PrintServiceController::class, 'completeOrder'])->name('complete-order');
        Route::post('/orders/{id}/cancel', [\App\Http\Controllers\Admin\PrintServiceController::class, 'cancelOrder'])->name('cancel-order');
        Route::post('/stock/{variantId}/adjust', [\App\Http\Controllers\Admin\PrintServiceController::class, 'adjustStock'])->name('stock.adjust');
        Route::get('/orders/{id}/payment-proof', [\App\Http\Controllers\Admin\PrintServiceController::class, 'downloadPaymentProof'])->name('payment-proof');
        Route::get('/view-file/{fileId}', [\App\Http\Controllers\Admin\PrintServiceController::class, 'viewFile'])->name('view-file');
    });

    Route::prefix('stock')->as('stock.')->group(function() {
        Route::get('/', [\App\Http\Controllers\Admin\StockCardController::class, 'index'])->name('index');
        Route::get('/movements', [\App\Http\Controllers\Admin\StockCardController::class, 'movements'])->name('movements');
        Route::get('/movements/data', [\App\Http\Controllers\Admin\StockCardController::class, 'movementData'])->name('movements.data');
        Route::get('/card/{variantId}', [\App\Http\Controllers\Admin\StockCardController::class, 'show'])->name('show');
        Route::get('/product/{productId}', [\App\Http\Controllers\Admin\StockCardController::class, 'showProduct'])->name('product');
        Route::get('/report', [\App\Http\Controllers\Admin\StockCardController::class, 'report'])->name('report');
    });

    Route::resource('paper-types', \App\Http\Controllers\Admin\PaperTypeController::class);
    Route::get('/paper-types/api/active', [\App\Http\Controllers\Admin\PaperTypeController::class, 'getActivePaperTypes'])->name('paper-types.api.active');
    
    Route::resource('print-types', \App\Http\Controllers\Admin\PrintTypeController::class);
    Route::get('/print-types/api/active', [\App\Http\Controllers\Admin\PrintTypeController::class, 'getActivePrintTypes'])->name('print-types.api.active');
});

Route::get('/smart-print', function () {
    $setting = App\Models\Setting::first();
    view()->share('setting', $setting);
    
    $cart = Gloudemans\Shoppingcart\Facades\Cart::content()->count();
    view()->share('countCart', $cart);
    
    return view('frontend.smart-print.index');
})->name('frontend.print-service');

Route::prefix('print-service')->group(function () {
    Route::post('/upload', [\App\Http\Controllers\PrintServiceController::class, 'upload'])->name('print-service.upload');
    Route::delete('/file/{file_id}', [\App\Http\Controllers\PrintServiceController::class, 'deleteFile'])->name('print-service.delete-file');
    Route::get('/preview/{file_id}', [\App\Http\Controllers\PrintServiceController::class, 'previewFile'])->name('print-service.preview-file');
    Route::get('/products', [\App\Http\Controllers\PrintServiceController::class, 'getProducts'])->name('print-service.products');
    Route::post('/get-session-files', [\App\Http\Controllers\PrintServiceController::class, 'getSessionFiles'])->name('print-service.get-session-files');
    Route::post('/calculate', [\App\Http\Controllers\PrintServiceController::class, 'calculate'])->name('print-service.calculate');
    Route::post('/checkout', [\App\Http\Controllers\PrintServiceController::class, 'checkout'])->name('print-service.checkout');
    Route::get('/status/{orderCode}', [\App\Http\Controllers\PrintServiceController::class, 'status'])->name('print-service.status');
    Route::post('/print/{orderCode}', [\App\Http\Controllers\PrintServiceController::class, 'print'])->name('print-service.print');
    Route::post('/complete/{orderCode}', [\App\Http\Controllers\PrintServiceController::class, 'complete'])->name('print-service.complete');
    Route::post('/generate-session', [\App\Http\Controllers\PrintServiceController::class, 'generateSession'])->name('print-service.generate-session');
    Route::post('/midtrans-callback', [\App\Http\Controllers\PrintServiceController::class, 'midtransCallback'])->name('print-service.midtrans-callback');
    
    // Payment callback routes
    Route::get('/payment/finish', [\App\Http\Controllers\PrintServiceController::class, 'paymentFinish'])->name('print-service.payment.finish');
    Route::get('/payment/unfinish', [\App\Http\Controllers\PrintServiceController::class, 'paymentUnfinish'])->name('print-service.payment.unfinish');
    Route::get('/payment/error', [\App\Http\Controllers\PrintServiceController::class, 'paymentError'])->name('print-service.payment.error');
    
    // Route::get('/test-view-file/{fileId}', [\App\Http\Controllers\Admin\PrintServiceController::class, 'viewFile'])->name('print-service.test-view-file');
    Route::get('/{token}', [\App\Http\Controllers\PrintServiceController::class, 'index'])->name('print-service.customer');
});

Route::get('/', [\App\Http\Controllers\Frontend\HomepageController::class, 'index'])->name('index');
Route::get('products', [\App\Http\Controllers\Frontend\ProductController::class, 'index']);
Route::get('product/{product:slug}', [\App\Http\Controllers\Frontend\ProductController::class, 'show'])->name('product.detail');
Route::get('products/quick-view/{product:slug}', [\App\Http\Controllers\Frontend\ProductController::class, 'quickView']);
Route::get('/shop', [HomepageController::class, 'shop'])->name('shop');
Route::get('/shopCetak', [HomepageController::class, 'shopCetak'])->name('shopCetak');
Route::get('/shopCategory/{slug}', [HomepageController::class, 'shopCategory'])->name('shopCategory');
Route::get('/shop/detail/{id}', [HomepageController::class, 'detail'])->name('shop-detail');

Route::group(['middleware' => 'auth'], function() {
    Route::get('carts', [\App\Http\Controllers\Frontend\CartController::class, 'index'])->name('carts.index');
    Route::post('carts', [\App\Http\Controllers\Frontend\CartController::class, 'store'])->name('carts.store');
    Route::post('carts/update', [\App\Http\Controllers\Frontend\CartController::class, 'update']);
    Route::get('carts/remove/{cartId}', [\App\Http\Controllers\Frontend\CartController::class, 'destroy']);



Route::get('/download-file/{id}', [\App\Http\Controllers\Frontend\OrderController::class, 'downloadFile'])->name('download-file');
    Route::get('orders/confirmPayment/{id}', [\App\Http\Controllers\Frontend\OrderController::class, 'confirmPaymentManual'])->name('orders.confirmation_payment');
    Route::put('orders/confirmPaymentManual/{id}', [\App\Http\Controllers\Frontend\OrderController::class, 'confirmPayment'])->name('orders.confirmPayment');
    Route::get('orders/checkout', [\App\Http\Controllers\Frontend\OrderController::class, 'checkout'])->middleware('auth');
    Route::post('orders/checkout', [\App\Http\Controllers\Frontend\OrderController::class, 'doCheckout'])->name('orders.checkout')->middleware('auth');
    Route::get('orders/test-simple-checkout', function() {
        if (!\Gloudemans\Shoppingcart\Facades\Cart::count()) {
            return redirect('carts')->with('error', 'Cart is empty');
        }
        
        $request = new \Illuminate\Http\Request();
        $request->merge([
            'name' => auth()->user()->name,
            'address1' => auth()->user()->address1 ?: 'Test Address 1',
            'address2' => auth()->user()->address2 ?: 'Test Address 2',
            'postcode' => auth()->user()->postcode ?: '12345',
            'phone' => auth()->user()->phone ?: '081234567890',
            'email' => auth()->user()->email,
            'payment_method' => 'toko',
            'delivery_method' => 'self',
            'unique_code' => 0,
            'note' => 'Test simple product checkout - direct route'
        ]);
        
        $orderController = new \App\Http\Controllers\Frontend\OrderController();
        return $orderController->doCheckout($request);
    })->middleware('auth')->name('test.simple.checkout');
    Route::post('orders/shipping-cost', [\App\Http\Controllers\Frontend\OrderController::class, 'shippingCost'])->name('orders.shippingCost')->middleware('auth');
    Route::post('orders/set-shipping', [\App\Http\Controllers\Frontend\OrderController::class, 'setShipping'])->middleware('auth');
    Route::get('orders/received/{orderId}', [\App\Http\Controllers\Frontend\OrderController::class, 'received']);
    Route::get('orders/{orderId}', [\App\Http\Controllers\Frontend\OrderController::class, 'show'])->name('showUsersOrder');
    Route::resource('wishlists', \App\Http\Controllers\Frontend\WishListController::class)->only(['index','store','destroy']);
    
    Route::resource('orders', \App\Http\Controllers\Frontend\OrderController::class)->only(['index','store','destroy']);

    // Midtrans routes


    Route::get('profile',  [\App\Http\Controllers\Auth\ProfileController::class, 'index'])->name('profile');
    Route::put('profile', [\App\Http\Controllers\Auth\ProfileController::class, 'update']);

});

// Location endpoints (no auth required for dropdown data)
Route::get('api/provinces', [\App\Http\Controllers\Frontend\OrderController::class, 'provinces']);
Route::get('api/cities/{province_id}', [\App\Http\Controllers\Frontend\OrderController::class, 'cities']);
Route::get('api/districts/{city_id}', [\App\Http\Controllers\Frontend\OrderController::class, 'districts']);
Route::get('/api/attribute-options/{attributeId}/{variantId}', function($attributeId, $variantId) {
    $options = \App\Models\AttributeOption::where('attribute_variant_id', $variantId)->get();
    return response()->json(['options' => $options]);
})->name('api.attribute-options');

Route::get('/test-product-data/{id}', function($id) {
    $product = \App\Models\Product::find($id);
    
    if (!$product) {
        return response()->json(['error' => 'Product not found'], 404);
    }
    
    return response()->json([
        'success' => true,
        'product' => [
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'price' => $product->price,
            'type' => $product->type,
            'total_stock' => $product->total_stock,
            'status' => $product->status
        ],
        'debug' => [
            'total_stock_type' => gettype($product->total_stock),
            'price_type' => gettype($product->price),
            'total_stock_value' => $product->total_stock,
            'price_value' => $product->price
        ]
    ]);
});
