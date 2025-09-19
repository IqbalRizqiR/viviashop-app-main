<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\EmployeePerformanceController;
use Illuminate\Http\Request;

class TestControllerShow extends Command
{
    protected $signature = 'test:controller-show {employee?}';
    protected $description = 'Test employee performance controller show method';

    public function handle()
    {
        $employeeName = $this->argument('employee') ?? 'Reza';
        
        $this->info('Testing EmployeePerformanceController show method with: ' . $employeeName);
        
        try {
            $controller = new EmployeePerformanceController();
            
            $request = new Request();
            
            $result = $controller->show($employeeName);
            
            $this->info('Controller show method executed successfully');
            $this->line('Result type: ' . get_class($result));
            
            if (method_exists($result, 'getData')) {
                $data = $result->getData();
                $this->line('View data keys: ' . implode(', ', array_keys($data)));
                
                if (isset($data['performances'])) {
                    $this->line('Performances count: ' . $data['performances']->count());
                }
                
                if (isset($data['bonuses'])) {
                    $this->line('Bonuses count: ' . $data['bonuses']->count());
                }
                
                if (isset($data['stats'])) {
                    $this->line('Stats: ' . json_encode($data['stats']));
                }
            }
            
        } catch (\Exception $e) {
            $this->error('Controller error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            $this->line($e->getTraceAsString());
        }
    }
}
