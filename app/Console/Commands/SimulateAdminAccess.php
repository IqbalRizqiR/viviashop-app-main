<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Http\Controllers\Admin\EmployeePerformanceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SimulateAdminAccess extends Command
{
    protected $signature = 'simulate:admin-access {employee?}';
    protected $description = 'Simulate admin access to employee performance page';

    public function handle()
    {
        $employeeName = $this->argument('employee') ?? 'Reza';
        
        $this->info('Simulating admin access to employee performance: ' . $employeeName);
        
        try {
            $admin = User::first();
            
            if (!$admin) {
                $this->error('No user found');
                return;
            }
            
            Auth::login($admin);
            $this->info('Logged in as: ' . $admin->name . ' (ID: ' . $admin->id . ')');
            
            $request = Request::create('/admin/employee-performance/' . $employeeName, 'GET');
            $request->setUserResolver(function () use ($admin) {
                return $admin;
            });
            
            $controller = new EmployeePerformanceController();
            
            $result = $controller->show($employeeName);
            
            $this->info('Controller executed successfully');
            
            if (method_exists($result, 'render')) {
                try {
                    $rendered = $result->render();
                    $this->info('View rendered successfully');
                    $this->line('Content length: ' . strlen($rendered) . ' characters');
                    
                    if (strpos($rendered, 'Call to a member function format()') !== false) {
                        $this->error('Found format() error in rendered content');
                    } else {
                        $this->info('No format() errors found in rendered content');
                    }
                    
                } catch (\Exception $e) {
                    $this->error('View render error: ' . $e->getMessage());
                    $this->error('File: ' . $e->getFile());
                    $this->error('Line: ' . $e->getLine());
                }
            }
            
        } catch (\Exception $e) {
            $this->error('Simulation error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            $this->line($e->getTraceAsString());
        } finally {
            Auth::logout();
        }
    }
}
