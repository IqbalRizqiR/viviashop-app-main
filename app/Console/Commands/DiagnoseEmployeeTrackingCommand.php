<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\EmployeePerformance;

class DiagnoseEmployeeTrackingCommand extends Command
{
    protected $signature = 'diagnose:employee-tracking {order_id?}';
    protected $description = 'Diagnose employee tracking issues';

    public function handle()
    {
        $orderId = $this->argument('order_id') ?: 144;
        
        $this->info("🔍 DIAGNOSING EMPLOYEE TRACKING FOR ORDER #{$orderId}");
        $this->newLine();

        $order = Order::find($orderId);
        if (!$order) {
            $this->error("❌ Order #{$orderId} not found");
            return;
        }

        $this->line("📋 Order Details:");
        $this->line("   ID: {$order->id}");
        $this->line("   Code: " . ($order->code ?: 'N/A'));
        $this->line("   Status: {$order->status}");
        $this->line("   Grand Total: Rp " . number_format($order->grand_total, 0, ',', '.'));
        $this->line("   Handled By: " . ($order->handled_by ?: 'Not set'));
        $this->line("   Employee Tracking: " . ($order->use_employee_tracking ? 'Enabled' : 'Disabled'));
        $this->newLine();

        $performance = EmployeePerformance::where('order_id', $order->id)->first();
        $this->line("📊 Performance Record:");
        if ($performance) {
            $this->line("   ✅ Performance record exists");
            $this->line("   Employee Name: {$performance->employee_name}");
            $this->line("   Transaction Value: Rp " . number_format((float)$performance->transaction_value, 0, ',', '.'));
            $this->line("   Completed At: " . ($performance->completed_at ? $performance->completed_at->format('Y-m-d H:i:s') : 'Not set'));
        } else {
            $this->line("   ❌ No performance record found");
        }
        $this->newLine();

        if ($order->handled_by) {
            $allPerformances = EmployeePerformance::where('employee_name', $order->handled_by)->get();
            $this->line("📈 All Performance Records for '{$order->handled_by}':");
            $this->line("   Total Records: {$allPerformances->count()}");
            
            foreach ($allPerformances as $index => $perf) {
                $this->line("   " . ($index + 1) . ". Order #{$perf->order_id} - Rp " . number_format((float)$perf->transaction_value, 0, ',', '.') . " - " . ($perf->completed_at ? $perf->completed_at->format('Y-m-d H:i:s') : 'No date'));
            }
        }
        $this->newLine();

        $this->line("🔧 Attempting to fix the issue...");
        
        if ($order->use_employee_tracking && $order->handled_by && $order->status === 'completed') {
            $this->line("   Conditions met for performance recording");
            
            $updated = EmployeePerformance::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'employee_name' => $order->handled_by,
                    'transaction_value' => $order->grand_total,
                    'completed_at' => $order->updated_at
                ]
            );
            
            if ($updated->wasRecentlyCreated) {
                $this->info("   ✅ Performance record created");
            } else {
                $this->info("   ✅ Performance record updated");
            }
        } else {
            $this->warn("   ⚠️  Conditions not met:");
            $this->line("     - Employee tracking: " . ($order->use_employee_tracking ? '✅' : '❌'));
            $this->line("     - Employee name: " . ($order->handled_by ? '✅' : '❌'));
            $this->line("     - Order completed: " . ($order->status === 'completed' ? '✅' : '❌'));
        }
        $this->newLine();

        $finalPerformance = EmployeePerformance::where('order_id', $order->id)->first();
        if ($finalPerformance) {
            $totalForEmployee = EmployeePerformance::where('employee_name', $finalPerformance->employee_name)->count();
            $this->info("🎉 Final Result: Performance record exists");
            $this->line("   Total transactions for '{$finalPerformance->employee_name}': {$totalForEmployee}");
        } else {
            $this->error("❌ Performance record still missing");
        }

        return Command::SUCCESS;
    }
}
