<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EmployeePerformance extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'employee_name',
        'transaction_value',
        'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'transaction_value' => 'decimal:2'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function scopeFilterByPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('completed_at', [$startDate, $endDate]);
    }

    public function scopeFilterByEmployee($query, $employeeName)
    {
        return $query->where('employee_name', $employeeName);
    }

    public static function getMonthlyStats($employeeName = null, $month = null, $year = null)
    {
        $query = self::query();
        
        if ($employeeName) {
            $query->where('employee_name', $employeeName);
        }

        if ($month && $year) {
            $query->whereMonth('completed_at', $month)
                  ->whereYear('completed_at', $year);
        }

        return [
            'total_transactions' => $query->count(),
            'total_revenue' => $query->sum('transaction_value'),
            'average_transaction' => $query->avg('transaction_value')
        ];
    }

    public static function getEmployeeList()
    {
        return self::select('employee_name')
                   ->distinct()
                   ->orderBy('employee_name')
                   ->pluck('employee_name');
    }
}
