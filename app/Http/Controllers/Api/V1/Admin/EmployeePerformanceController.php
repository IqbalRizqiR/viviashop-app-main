<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\BaseController;
use App\Models\EmployeePerformance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeePerformanceController extends BaseController
{
    public function index(Request $request)
    {
        $period = $request->input('period', 'month');
        $startDate = match ($period) {
            'today' => Carbon::today(),
            'week' => Carbon::now()->startOfWeek(),
            'month' => Carbon::now()->startOfMonth(),
            'year' => Carbon::now()->startOfYear(),
            default => Carbon::now()->startOfMonth(),
        };

        $employees = EmployeePerformance::select(
                'employee_name',
                DB::raw('COUNT(*) as total_transactions'),
                DB::raw('SUM(transaction_value) as total_revenue'),
                DB::raw('AVG(transaction_value) as avg_transaction'),
                DB::raw('SUM(bonus_amount) as total_bonus')
            )
            ->where('completed_at', '>=', $startDate)
            ->groupBy('employee_name')
            ->orderBy('total_revenue', 'desc')
            ->get();

        return $this->success([
            'employees' => $employees,
            'period' => $period,
            'start_date' => $startDate->toISOString(),
            'team_total' => (float) $employees->sum('total_revenue'),
        ]);
    }

    public function details(Request $request, $name)
    {
        $query = EmployeePerformance::where('employee_name', $name)
            ->with('order:id,code,grand_total,status')
            ->orderBy('completed_at', 'desc');

        if ($start = $request->input('start')) {
            $query->whereDate('completed_at', '>=', $start);
        }
        if ($end = $request->input('end')) {
            $query->whereDate('completed_at', '<=', $end);
        }

        return $this->success($query->paginate(20));
    }

    public function updateBonus(Request $request)
    {
        $validated = $request->validate([
            'employee_name' => 'required|string|max:255',
            'bonus_amount' => 'required|numeric|min:0',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
        ]);

        $updated = EmployeePerformance::where('employee_name', $validated['employee_name'])
            ->whereBetween('completed_at', [$validated['period_start'], $validated['period_end']])
            ->update(['bonus_amount' => $validated['bonus_amount']]);

        return $this->success(['updated_records' => $updated], 'Bonus updated');
    }

    public function employeeList()
    {
        return $this->success(EmployeePerformance::getEmployeeList());
    }
}
