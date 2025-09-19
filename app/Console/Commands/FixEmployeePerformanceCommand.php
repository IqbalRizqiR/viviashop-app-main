<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\EmployeePerformance;

class FixEmployeePerformanceCommand extends Command
{
    protected $signature = 'fix:employee-performance {employee_name?}';
    protected $description = 'Fix employee performance tracking data';

    public function handle()
    {
        $employeeName = $this->argument('employee_name') ?: 'Reza';
        
        $this->info("🔧 FIXING EMPLOYEE PERFORMANCE FOR: {$employeeName}");
        $this->newLine();

        $this->line("📋 Step 1: Finding all orders handled by {$employeeName}");
        $orders = Order::where('handled_by', $employeeName)
                      ->where('use_employee_tracking', true)
                      ->get();
        
        $this->line("   Found {$orders->count()} orders with employee tracking");
        foreach ($orders as $order) {
            $this->line("   - Order #{$order->id}: {$order->status} - Rp " . number_format((float)$order->grand_total, 0, ',', '.'));
        }
        $this->newLine();

        $this->line("📊 Step 2: Checking existing performance records");
        $existingPerformances = EmployeePerformance::where('employee_name', $employeeName)->get();
        $this->line("   Found {$existingPerformances->count()} existing performance records");
        foreach ($existingPerformances as $perf) {
            $this->line("   - Order #{$perf->order_id}: Rp " . number_format((float)$perf->transaction_value, 0, ',', '.') . " - " . ($perf->completed_at ? $perf->completed_at->format('Y-m-d H:i:s') : 'No date'));
        }
        $this->newLine();

        $this->line("🔄 Step 3: Syncing performance records with orders");
        $created = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($orders as $order) {
            if ($order->status === 'completed') {
                $performance = EmployeePerformance::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'employee_name' => $order->handled_by,
                        'transaction_value' => $order->grand_total,
                        'completed_at' => $order->updated_at
                    ]
                );
                
                if ($performance->wasRecentlyCreated) {
                    $created++;
                    $this->line("   ✅ Created performance record for Order #{$order->id}");
                } else {
                    $updated++;
                    $this->line("   🔄 Updated performance record for Order #{$order->id}");
                }
            } else {
                $skipped++;
                $this->line("   ⏭️  Skipped Order #{$order->id} (status: {$order->status})");
            }
        }
        $this->newLine();

        $this->line("📈 Step 4: Final verification");
        $finalPerformances = EmployeePerformance::where('employee_name', $employeeName)->get();
        $totalValue = $finalPerformances->sum('transaction_value');
        
        $this->info("🎉 FINAL RESULTS:");
        $this->line("   Employee: {$employeeName}");
        $this->line("   Total Transactions: {$finalPerformances->count()}");
        $this->line("   Total Revenue: Rp " . number_format((float)$totalValue, 0, ',', '.'));
        $this->line("   Records Created: {$created}");
        $this->line("   Records Updated: {$updated}");
        $this->line("   Records Skipped: {$skipped}");
        $this->newLine();

        $this->line("📋 All Performance Records for {$employeeName}:");
        foreach ($finalPerformances as $index => $perf) {
            $order = Order::find($perf->order_id);
            $orderCode = $order ? $order->code : "Order #{$perf->order_id}";
            $this->line("   " . ($index + 1) . ". {$orderCode} - Rp " . number_format((float)$perf->transaction_value, 0, ',', '.') . " - " . ($perf->completed_at ? $perf->completed_at->format('Y-m-d H:i:s') : 'No date'));
        }
        $this->newLine();

        $this->comment("💡 You can now visit: http://127.0.0.1:8000/admin/employee-performance/{$employeeName}");
        $this->comment("   Expected to show {$finalPerformances->count()} transactions");

        return Command::SUCCESS;
    }
}
