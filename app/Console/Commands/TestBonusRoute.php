<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\EmployeePerformanceController;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TestBonusRoute extends Command
{
    protected $signature = 'test:bonus-route';
    protected $description = 'Test bonus route functionality';

    public function handle()
    {
        $this->info('Testing Bonus Route Functionality');
        
        try {
            $admin = User::first();
            Auth::login($admin);
            $this->info('Logged in as: ' . $admin->name);
            
            $request = new Request([
                'employee_name' => 'Test Employee',
                'bonus_amount' => 100000,
                'period_start' => '2025-09-01',
                'period_end' => '2025-09-30',
                'notes' => 'Test bonus from command'
            ]);
            
            $controller = new EmployeePerformanceController();
            
            $response = $controller->giveBonus($request);
            
            $this->info('Controller response received');
            
            if (method_exists($response, 'getData')) {
                $data = $response->getData(true);
                $this->line('Response: ' . json_encode($data));
                
                if (isset($data['success']) && $data['success']) {
                    $this->info('✅ Bonus route working successfully');
                } else {
                    $this->error('❌ Bonus route returned error');
                }
            }
            
            $bonusCount = \App\Models\EmployeeBonus::where('employee_name', 'Test Employee')->count();
            $this->line('Test Employee bonuses in database: ' . $bonusCount);
            
        } catch (\Exception $e) {
            $this->error('Test failed: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
        } finally {
            Auth::logout();
        }
    }
}
