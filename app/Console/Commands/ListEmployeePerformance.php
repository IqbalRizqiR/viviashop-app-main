<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmployeePerformance;

class ListEmployeePerformance extends Command
{
    protected $signature = 'list:employees';
    protected $description = 'List all employee performance data';

    public function handle()
    {
        $this->info('=== EMPLOYEE PERFORMANCE SUMMARY ===');
        
        $employees = EmployeePerformance::select('employee_name')
            ->selectRaw('COUNT(*) as total_transactions')
            ->selectRaw('SUM(transaction_value) as total_revenue')
            ->selectRaw('AVG(transaction_value) as avg_transaction')
            ->selectRaw('MIN(completed_at) as first_transaction')
            ->selectRaw('MAX(completed_at) as last_transaction')
            ->groupBy('employee_name')
            ->orderBy('total_revenue', 'desc')
            ->get();
            
        if ($employees->isEmpty()) {
            $this->warn('No employee performance data found');
            return;
        }
        
        $this->table(
            ['Employee Name', 'Transactions', 'Total Revenue', 'Avg Transaction', 'First', 'Last'],
            $employees->map(function ($emp) {
                return [
                    $emp->employee_name,
                    $emp->total_transactions,
                    'Rp ' . number_format($emp->total_revenue, 0, ',', '.'),
                    'Rp ' . number_format($emp->avg_transaction, 0, ',', '.'),
                    $emp->first_transaction,
                    $emp->last_transaction
                ];
            })
        );
        
        $this->info('=== RECENT TRANSACTIONS ===');
        $recent = EmployeePerformance::with('order')
            ->orderBy('completed_at', 'desc')
            ->limit(5)
            ->get();
            
        $this->table(
            ['Order ID', 'Employee', 'Amount', 'Completed At'],
            $recent->map(function ($perf) {
                return [
                    $perf->order_id,
                    $perf->employee_name,
                    'Rp ' . number_format($perf->transaction_value, 0, ',', '.'),
                    $perf->completed_at
                ];
            })
        );
        
        $totalEmployees = $employees->count();
        $totalTransactions = EmployeePerformance::count();
        $totalRevenue = EmployeePerformance::sum('transaction_value');
        
        $this->info("Total Employees: {$totalEmployees}");
        $this->info("Total Transactions: {$totalTransactions}");
        $this->info("Total Revenue: Rp " . number_format($totalRevenue, 0, ',', '.'));
    }
}
