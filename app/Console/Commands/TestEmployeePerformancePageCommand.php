<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\EmployeePerformanceController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestEmployeePerformancePageCommand extends Command
{
    protected $signature = 'test:employee-performance-page';
    protected $description = 'Test employee performance page and DataTables functionality';

    public function handle()
    {
        $this->info('🔄 Testing Employee Performance Page...');
        
        $admin = User::where('is_admin', 1)->first();
        if (!$admin) {
            $this->error('❌ No admin user found');
            return 1;
        }
        
        Auth::login($admin);
        $this->info("✅ Logged in as admin: {$admin->name}");
        
        try {
            $controller = new EmployeePerformanceController();
            
            $this->info('📊 Testing index page...');
            $response = $controller->index();
            $this->info('✅ Index page loaded successfully');
            
            $this->info('📊 Testing DataTables data endpoint...');
            $request = new Request();
            $dataResponse = $controller->data($request);
            $this->info('✅ DataTables data loaded successfully');
            
            $this->info('📊 Testing bonus form page...');
            $bonusResponse = $controller->bonusForm($request);
            $this->info('✅ Bonus form page loaded successfully');
            
            $this->info('📊 Testing bonus form with employee parameter...');
            $requestWithEmployee = new Request(['employee' => 'Reza']);
            $bonusWithEmployeeResponse = $controller->bonusForm($requestWithEmployee);
            $this->info('✅ Bonus form with employee parameter loaded successfully');
            
        } catch (\Exception $e) {
            $this->error("❌ Error testing pages: " . $e->getMessage());
            return 1;
        }
        
        $this->info('🔗 Testing routes...');
        $routes = [
            'admin.employee-performance.index',
            'admin.employee-performance.data',
            'admin.employee-performance.bonus',
            'admin.employee-performance.giveBonus'
        ];
        
        foreach ($routes as $route) {
            try {
                $url = route($route);
                $this->info("✅ Route '{$route}' generated URL: {$url}");
            } catch (\Exception $e) {
                $this->error("❌ Route '{$route}' failed: " . $e->getMessage());
            }
        }
        
        $this->info('✅ Employee Performance Page test completed successfully!');
        return 0;
    }
}
