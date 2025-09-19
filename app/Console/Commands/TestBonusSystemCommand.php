<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmployeeBonus;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TestBonusSystemCommand extends Command
{
    protected $signature = 'test:bonus-system';
    protected $description = 'Test the employee bonus system functionality';

    public function handle()
    {
        $this->info('🔄 Testing Employee Bonus System...');
        
        $admin = User::where('is_admin', 1)->first();
        if (!$admin) {
            $this->error('❌ No admin user found');
            return 1;
        }
        
        Auth::login($admin);
        $this->info("✅ Logged in as admin: {$admin->name}");
        
        $bonusData = [
            'employee_name' => 'Reza',
            'bonus_amount' => 50000,
            'period_start' => '2024-09-01',
            'period_end' => '2024-09-30',
            'description' => 'Test bonus for excellent performance',
            'notes' => 'Additional notes for the bonus',
            'given_by' => $admin->id,
            'given_at' => now()
        ];
        
        try {
            $bonus = EmployeeBonus::create($bonusData);
            $this->info("✅ Bonus created successfully!");
            $this->info("   - ID: {$bonus->id}");
            $this->info("   - Employee: {$bonus->employee_name}");
            $this->info("   - Amount: Rp " . number_format((float)$bonus->bonus_amount, 0, ',', '.'));
            $this->info("   - Description: {$bonus->description}");
            $this->info("   - Period: {$bonus->period_start} to {$bonus->period_end}");
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to create bonus: " . $e->getMessage());
            return 1;
        }
        
        try {
            $generalBonusData = [
                'employee_name' => null,
                'bonus_amount' => 25000,
                'period_start' => '2024-09-01',
                'period_end' => '2024-09-30',
                'description' => 'General bonus for all employees',
                'notes' => 'Monthly performance bonus',
                'given_by' => $admin->id,
                'given_at' => now()
            ];
            
            $generalBonus = EmployeeBonus::create($generalBonusData);
            $this->info("✅ General bonus created successfully!");
            $this->info("   - ID: {$generalBonus->id}");
            $this->info("   - For: All Employees");
            $this->info("   - Amount: Rp " . number_format((float)$generalBonus->bonus_amount, 0, ',', '.'));
            $this->info("   - Description: {$generalBonus->description}");
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to create general bonus: " . $e->getMessage());
            return 1;
        }
        
        $this->info('📊 Recent bonuses:');
        $recentBonuses = EmployeeBonus::orderBy('given_at', 'desc')->take(5)->get();
        
        foreach ($recentBonuses as $bonus) {
            $employee = $bonus->employee_name ?: 'All Employees';
            $amount = number_format((float)$bonus->bonus_amount, 0, ',', '.');
            $this->info("   - {$employee}: Rp {$amount} ({$bonus->description})");
        }
        
        $this->info('✅ Bonus system test completed successfully!');
        return 0;
    }
}
