<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\EmployeePerformance;

class DebugOrder142 extends Command
{
    protected $signature = 'debug:order142';
    protected $description = 'Debug order 142 and employee performance';

    public function handle()
    {
        $order = Order::find(142);
        
        if (!$order) {
            $this->error('Order 142 not found');
            return;
        }
        
        $this->info('=== ORDER 142 DEBUG ===');
        $this->line('Order ID: ' . $order->id);
        $this->line('Status: ' . $order->status);
        $this->line('Handled by: ' . ($order->handled_by ?: 'Not set'));
        $this->line('Use Employee Tracking: ' . ($order->use_employee_tracking ? 'Yes' : 'No'));
        $this->line('Grand Total: ' . $order->grand_total);
        $this->line('Updated At: ' . $order->updated_at);
        
        $performance = EmployeePerformance::where('order_id', 142)->first();
        
        $this->info('=== PERFORMANCE RECORD ===');
        if ($performance) {
            $this->info('Performance Record FOUND');
            $this->line('Employee Name: ' . $performance->employee_name);
            $this->line('Transaction Value: ' . $performance->transaction_value);
            $this->line('Completed At: ' . $performance->completed_at);
            $this->line('Created At: ' . $performance->created_at);
        } else {
            $this->error('Performance Record NOT FOUND');
            $this->line('This means the performance was not saved during order completion');
        }
        
        $allPerformances = EmployeePerformance::count();
        $this->info('=== SYSTEM STATUS ===');
        $this->line('Total Performance Records: ' . $allPerformances);
        
        $ordersWithTracking = Order::where('use_employee_tracking', true)->count();
        $this->line('Orders with Employee Tracking: ' . $ordersWithTracking);
        
        if ($order->handled_by && !$order->use_employee_tracking) {
            $this->warn('=== ISSUE DETECTED ===');
            $this->warn('Order has employee name but tracking is disabled!');
            $this->warn('This is a data inconsistency - fixing it now...');
            
            $this->info('=== FIXING DATA INCONSISTENCY ===');
            try {
                $order->update(['use_employee_tracking' => true]);
                $this->info('Employee tracking enabled for this order');
                
                if (!$performance) {
                    EmployeePerformance::updateOrCreate(
                        ['order_id' => $order->id],
                        [
                            'employee_name' => $order->handled_by,
                            'transaction_value' => $order->grand_total,
                            'completed_at' => $order->updated_at
                        ]
                    );
                    $this->info('Performance record created successfully!');
                } else {
                    $this->info('Performance record already exists');
                }
            } catch (\Exception $e) {
                $this->error('Failed to fix data: ' . $e->getMessage());
            }
        }
        
        if ($order->use_employee_tracking && !empty($order->handled_by) && !$performance) {
            $this->warn('=== ISSUE DETECTED ===');
            $this->warn('Order has employee tracking enabled but no performance record!');
            $this->warn('This suggests the saveEmployeePerformance method was not called or failed.');
            
            $this->info('=== ATTEMPTING TO FIX ===');
            try {
                EmployeePerformance::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'employee_name' => $order->handled_by,
                        'transaction_value' => $order->grand_total,
                        'completed_at' => $order->updated_at
                    ]
                );
                $this->info('Performance record created successfully!');
            } catch (\Exception $e) {
                $this->error('Failed to create performance record: ' . $e->getMessage());
            }
        }
    }
}
