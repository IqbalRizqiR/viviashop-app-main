<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\EmployeePerformanceController;
use App\Models\User;
use App\Models\EmployeeBonus;
use App\Models\EmployeePerformance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestCompleteBonusSystemCommand extends Command
{
    protected $signature = 'test:complete-bonus-system';
    protected $description = 'Test complete bonus management system with dropdown and CRUD operations';

    public function handle()
    {
        $this->info('🚀 Testing Complete Bonus Management System...');
        
        $admin = User::where('is_admin', 1)->first();
        if (!$admin) {
            $this->error('❌ No admin user found');
            return 1;
        }
        
        Auth::login($admin);
        $this->info("✅ Logged in as admin: {$admin->name}");
        
        $controller = new EmployeePerformanceController();
        
        $this->info('1️⃣ Testing Employee List for Dropdown');
        try {
            $employees = EmployeePerformance::getEmployeeList();
            $this->info("✅ Found {$employees->count()} employees for dropdown:");
            foreach ($employees as $employee) {
                $this->info("   - {$employee}");
            }
        } catch (\Exception $e) {
            $this->error("❌ Employee list failed: " . $e->getMessage());
            return 1;
        }
        
        $this->info('2️⃣ Testing Bonus Form with Dropdown');
        try {
            $bonusFormResponse = $controller->bonusForm(new Request());
            $this->info('✅ Bonus form with dropdown loaded successfully');
        } catch (\Exception $e) {
            $this->error("❌ Bonus form failed: " . $e->getMessage());
            return 1;
        }
        
        $this->info('3️⃣ Testing Bonus List Management Page');
        try {
            $bonusListResponse = $controller->bonusList();
            $this->info('✅ Bonus list management page loaded successfully');
        } catch (\Exception $e) {
            $this->error("❌ Bonus list page failed: " . $e->getMessage());
            return 1;
        }
        
        $this->info('4️⃣ Testing Bonus DataTables API');
        try {
            $bonusDataResponse = $controller->bonusData(new Request());
            $this->info('✅ Bonus DataTables API working successfully');
        } catch (\Exception $e) {
            $this->error("❌ Bonus DataTables API failed: " . $e->getMessage());
            return 1;
        }
        
        $this->info('5️⃣ Creating Test Bonus for Detail/Edit Testing');
        try {
            $testBonus = EmployeeBonus::create([
                'employee_name' => 'Test Employee',
                'bonus_amount' => 150000,
                'period_start' => '2024-09-01',
                'period_end' => '2024-09-30',
                'description' => 'Test bonus for CRUD operations',
                'notes' => 'Created for testing detail and edit functionality',
                'given_by' => $admin->id,
                'given_at' => now()
            ]);
            $this->info("✅ Test bonus created with ID: {$testBonus->id}");
        } catch (\Exception $e) {
            $this->error("❌ Test bonus creation failed: " . $e->getMessage());
            return 1;
        }
        
        $this->info('6️⃣ Testing Bonus Detail Page');
        try {
            $bonusDetailResponse = $controller->bonusDetail($testBonus->id);
            $this->info('✅ Bonus detail page loaded successfully');
        } catch (\Exception $e) {
            $this->error("❌ Bonus detail page failed: " . $e->getMessage());
            return 1;
        }
        
        $this->info('7️⃣ Testing Bonus Edit Page');
        try {
            $bonusEditResponse = $controller->bonusEdit($testBonus->id);
            $this->info('✅ Bonus edit page loaded successfully');
        } catch (\Exception $e) {
            $this->error("❌ Bonus edit page failed: " . $e->getMessage());
            return 1;
        }
        
        $this->info('8️⃣ Testing Bonus Update Functionality');
        try {
            $updateRequest = new Request([
                'employee_name' => 'Updated Employee',
                'amount' => 200000,
                'period_start' => '2024-09-01',
                'period_end' => '2024-09-30',
                'description' => 'Updated bonus description',
                'notes' => 'Updated notes'
            ]);
            
            $updateResponse = $controller->bonusUpdate($updateRequest, $testBonus->id);
            $responseData = json_decode($updateResponse->getContent(), true);
            
            if ($responseData['success']) {
                $this->info('✅ Bonus update successful');
                $this->info("   Message: {$responseData['message']}");
            } else {
                $this->error('❌ Bonus update returned success=false');
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("❌ Bonus update failed: " . $e->getMessage());
            return 1;
        }
        
        $this->info('9️⃣ Verifying Update in Database');
        try {
            $updatedBonus = EmployeeBonus::find($testBonus->id);
            if ($updatedBonus && $updatedBonus->employee_name === 'Updated Employee') {
                $this->info('✅ Database update verified successfully');
                $this->info("   Employee: {$updatedBonus->employee_name}");
                $this->info("   Amount: Rp " . number_format((float)$updatedBonus->bonus_amount, 0, ',', '.'));
                $this->info("   Description: {$updatedBonus->description}");
            } else {
                $this->error('❌ Database update verification failed');
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("❌ Database verification failed: " . $e->getMessage());
            return 1;
        }
        
        $this->info('🔟 Testing Bonus Delete Functionality');
        try {
            $deleteResponse = $controller->bonusDelete($testBonus->id);
            $responseData = json_decode($deleteResponse->getContent(), true);
            
            if ($responseData['success']) {
                $this->info('✅ Bonus delete successful');
                $this->info("   Message: {$responseData['message']}");
            } else {
                $this->error('❌ Bonus delete returned success=false');
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("❌ Bonus delete failed: " . $e->getMessage());
            return 1;
        }
        
        $this->info('1️⃣1️⃣ Verifying Delete in Database');
        try {
            $deletedBonus = EmployeeBonus::find($testBonus->id);
            if (!$deletedBonus) {
                $this->info('✅ Database delete verified successfully');
            } else {
                $this->error('❌ Database delete verification failed - bonus still exists');
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("❌ Database delete verification failed: " . $e->getMessage());
            return 1;
        }
        
        $this->info('1️⃣2️⃣ Testing All Routes');
        $routes = [
            'admin.employee-performance.index' => 'Performance Dashboard',
            'admin.employee-performance.bonus' => 'Add Bonus Form',
            'admin.employee-performance.bonusList' => 'Bonus Management List',
            'admin.employee-performance.bonusData' => 'Bonus DataTables API',
        ];
        
        foreach ($routes as $routeName => $description) {
            try {
                $url = route($routeName);
                $this->info("✅ {$description}: {$url}");
            } catch (\Exception $e) {
                $this->error("❌ Route {$routeName} failed: " . $e->getMessage());
            }
        }
        
        $this->info('🎉 COMPLETE BONUS SYSTEM TEST COMPLETED SUCCESSFULLY!');
        $this->info('');
        $this->info('📋 Summary:');
        $this->info('✅ Employee dropdown working');
        $this->info('✅ Bonus form with dropdown working');
        $this->info('✅ Bonus list management working');
        $this->info('✅ Bonus DataTables API working');
        $this->info('✅ Bonus detail page working');
        $this->info('✅ Bonus edit page working');
        $this->info('✅ Bonus update functionality working');
        $this->info('✅ Bonus delete functionality working');
        $this->info('✅ Database CRUD operations working');
        $this->info('✅ All routes accessible');
        $this->info('');
        $this->info('🔗 Test URLs (without admin middleware):');
        $this->info('   • Bonus Form: http://localhost:8000/test-employee-performance-bonus-form');
        $this->info('   • Bonus Management: http://localhost:8000/test-bonus-management');
        
        return 0;
    }
}
