<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmployeePerformance;
use App\Models\EmployeeBonus;
use Carbon\Carbon;

class DebugEmployeeReza extends Command
{
    protected $signature = 'debug:employee-reza';
    protected $description = 'Debug Reza employee performance data';

    public function handle()
    {
        $employeeName = 'Reza';
        
        $this->info('=== DEBUGGING EMPLOYEE: ' . $employeeName . ' ===');
        
        $performances = EmployeePerformance::where('employee_name', $employeeName)
                                         ->with('order')
                                         ->orderBy('completed_at', 'desc')
                                         ->get();

        $bonuses = EmployeeBonus::where('employee_name', $employeeName)
                               ->with('givenBy')
                               ->orderBy('given_at', 'desc')
                               ->get();

        $stats = EmployeePerformance::getMonthlyStats($employeeName);
        
        $this->info('=== STATISTICS ===');
        $this->line('Total Transactions: ' . ($stats['total_transactions'] ?? 0));
        $this->line('Total Revenue: ' . ($stats['total_revenue'] ?? 0));
        $this->line('Average Transaction: ' . ($stats['average_transaction'] ?? 0));
        
        $this->info('=== PERFORMANCES (' . $performances->count() . ') ===');
        foreach ($performances as $perf) {
            $this->line('Order ID: ' . $perf->order_id);
            $this->line('Transaction Value: ' . $perf->transaction_value);
            $this->line('Completed At Raw: ' . $perf->getRawOriginal('completed_at'));
            $this->line('Completed At Casted: ' . $perf->completed_at);
            $this->line('Completed At Type: ' . gettype($perf->completed_at));
            if ($perf->completed_at) {
                $this->line('Is Carbon: ' . (is_a($perf->completed_at, 'Carbon\\Carbon') ? 'Yes' : 'No'));
                try {
                    $this->line('Format Test: ' . $perf->completed_at->format('d/m/Y H:i'));
                } catch (\Exception $e) {
                    $this->error('Format Error: ' . $e->getMessage());
                }
            }
            $this->line('---');
        }
        
        $this->info('=== BONUSES (' . $bonuses->count() . ') ===');
        foreach ($bonuses as $bonus) {
            $this->line('Amount: ' . $bonus->bonus_amount);
            $this->line('Period Start Raw: ' . $bonus->getRawOriginal('period_start'));
            $this->line('Period Start Casted: ' . $bonus->period_start);
            $this->line('Period End Raw: ' . $bonus->getRawOriginal('period_end'));
            $this->line('Period End Casted: ' . $bonus->period_end);
            $this->line('---');
        }
        
        $this->info('=== TESTING CONTROLLER METHOD ===');
        try {
            $controller = new \App\Http\Controllers\Admin\EmployeePerformanceController();
            $this->info('Controller instantiated successfully');
        } catch (\Exception $e) {
            $this->error('Controller error: ' . $e->getMessage());
        }
    }
}
