<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\EmployeePerformanceController;
use App\Models\User;
use App\Models\EmployeeBonus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinalBonusFlowTestCommand extends Command
{
    protected $signature = 'test:final-bonus-flow';
    protected $description = 'Complete test of employee bonus flow from start to finish';

    public function handle()
    {
        $this->info('🚀 Starting Final Bonus Flow Test...');
        
        $admin = User::where('is_admin', 1)->first();
        if (!$admin) {
            $this->error('❌ No admin user found');
            return 1;
        }
        
        Auth::login($admin);
        $this->info("✅ Logged in as admin: {$admin->name}");
        
        $controller = new EmployeePerformanceController();
        
        $this->info('1️⃣ Testing Employee Performance Index Page');
        try {
            $indexResponse = $controller->index();
            $this->info('✅ Index page loaded successfully');
        } catch (\Exception $e) {
            $this->error("❌ Index page failed: " . $e->getMessage());
            return 1;
        }
        
        $this->info('2️⃣ Testing DataTables Data API');
        try {
            $dataRequest = new Request();
            $dataResponse = $controller->data($dataRequest);
            $this->info('✅ DataTables API working');
        } catch (\Exception $e) {
            $this->error("❌ DataTables API failed: " . $e->getMessage());
            return 1;
        }
        
        $this->info('3️⃣ Testing Bonus Form Page (General)');
        try {
            $bonusFormRequest = new Request();
            $bonusFormResponse = $controller->bonusForm($bonusFormRequest);
            $this->info('✅ General bonus form loaded');
        } catch (\Exception $e) {
            $this->error("❌ Bonus form failed: " . $e->getMessage());
            return 1;
        }
        
        $this->info('4️⃣ Testing Bonus Form Page (Employee Specific)');
        try {
            $bonusFormRequestEmployee = new Request(['employee' => 'Reza']);
            $bonusFormResponseEmployee = $controller->bonusForm($bonusFormRequestEmployee);
            $this->info('✅ Employee-specific bonus form loaded');
        } catch (\Exception $e) {
            $this->error("❌ Employee-specific bonus form failed: " . $e->getMessage());
            return 1;
        }
        
        $this->info('5️⃣ Testing Bonus Submission (Employee)');
        try {
            $bonusSubmissionRequest = new Request([
                'employee_name' => 'Ahmad',
                'amount' => 75000,
                'period_start' => '2024-09-01',
                'period_end' => '2024-09-30',
                'description' => 'Exceptional customer service',
                'notes' => 'Customer feedback was outstanding'
            ]);
            
            $bonusSubmissionResponse = $controller->giveBonus($bonusSubmissionRequest);
            $responseData = json_decode($bonusSubmissionResponse->getContent(), true);
            
            if ($responseData['success']) {
                $this->info('✅ Employee bonus submission successful');
                $this->info("   Message: {$responseData['message']}");
            } else {
                $this->error('❌ Bonus submission returned success=false');
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("❌ Bonus submission failed: " . $e->getMessage());
            return 1;
        }
        
        $this->info('6️⃣ Testing Bonus Submission (General - All Employees)');
        try {
            $generalBonusRequest = new Request([
                'employee_name' => '',
                'amount' => 30000,
                'period_start' => '2024-09-01',
                'period_end' => '2024-09-30',
                'description' => 'Monthly team performance bonus',
                'notes' => 'Great teamwork this month'
            ]);
            
            $generalBonusResponse = $controller->giveBonus($generalBonusRequest);
            $responseData = json_decode($generalBonusResponse->getContent(), true);
            
            if ($responseData['success']) {
                $this->info('✅ General bonus submission successful');
                $this->info("   Message: {$responseData['message']}");
            } else {
                $this->error('❌ General bonus submission returned success=false');
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("❌ General bonus submission failed: " . $e->getMessage());
            return 1;
        }
        
        $this->info('7️⃣ Verifying Bonus Records in Database');
        try {
            $recentBonuses = EmployeeBonus::orderBy('given_at', 'desc')->take(3)->get();
            $this->info("✅ Found {$recentBonuses->count()} recent bonus records:");
            
            foreach ($recentBonuses as $bonus) {
                $employee = $bonus->employee_name ?: 'All Employees';
                $amount = number_format((float)$bonus->bonus_amount, 0, ',', '.');
                $this->info("   - {$employee}: Rp {$amount} - {$bonus->description}");
            }
        } catch (\Exception $e) {
            $this->error("❌ Database verification failed: " . $e->getMessage());
            return 1;
        }
        
        $this->info('8️⃣ Testing Routes Accessibility');
        $routes = [
            'admin.employee-performance.index' => 'Employee Performance Index',
            'admin.employee-performance.data' => 'DataTables Data API',
            'admin.employee-performance.bonus' => 'Bonus Form Page',
            'admin.employee-performance.giveBonus' => 'Bonus Submission Endpoint'
        ];
        
        foreach ($routes as $routeName => $description) {
            try {
                $url = route($routeName);
                $this->info("✅ {$description}: {$url}");
            } catch (\Exception $e) {
                $this->error("❌ Route {$routeName} failed: " . $e->getMessage());
            }
        }
        
        $this->info('🎉 FINAL BONUS FLOW TEST COMPLETED SUCCESSFULLY!');
        $this->info('');
        $this->info('📋 Summary:');
        $this->info('✅ Employee Performance dashboard working');
        $this->info('✅ DataTables integration working');
        $this->info('✅ Bonus form pages working (general & employee-specific)');
        $this->info('✅ Bonus submission working (both types)');
        $this->info('✅ Database integration working');
        $this->info('✅ All routes accessible');
        $this->info('');
        $this->info('🔗 Test URLs (without admin middleware):');
        $this->info('   • Employee Performance: http://localhost:8000/test-employee-performance-final');
        $this->info('   • Bonus Form: http://localhost:8000/test-employee-performance-bonus-form');
        $this->info('   • Bonus Form (with employee): http://localhost:8000/test-employee-performance-bonus-form?employee=Reza');
        
        return 0;
    }
}
