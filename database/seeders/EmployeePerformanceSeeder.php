<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeePerformanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = \App\Models\Order::whereIn('status', ['completed', 'delivered'])->take(10)->get();
        
        if ($orders->count() == 0) {
            $this->command->info('No completed orders found to create test data');
            return;
        }

        $employees = ['Ahmad Susanto', 'Budi Rahman', 'Citra Dewi', 'Diana Putri', 'Eko Setiawan'];
        
        foreach ($orders as $order) {
            $randomEmployee = $employees[array_rand($employees)];
            
            $order->update([
                'use_employee_tracking' => true,
                'handled_by' => $randomEmployee
            ]);
            
            \App\Models\EmployeePerformance::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'employee_name' => $randomEmployee,
                    'transaction_value' => $order->grand_total,
                    'completed_at' => $order->updated_at
                ]
            );
        }
        
        $admin = \App\Models\User::where('is_admin', true)->first();
        
        if ($admin) {
            foreach ($employees as $employee) {
                \App\Models\EmployeeBonus::create([
                    'employee_name' => $employee,
                    'bonus_amount' => rand(50000, 500000),
                    'period_start' => now()->subDays(30)->toDateString(),
                    'period_end' => now()->toDateString(),
                    'notes' => 'Performance bonus for good work',
                    'given_by' => $admin->id,
                    'given_at' => now()
                ]);
            }
        }
        
        $this->command->info('Employee performance test data created successfully!');
    }
}
