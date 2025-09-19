<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeBonus extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_name',
        'bonus_amount',
        'period_start',
        'period_end',
        'description',
        'notes',
        'given_by',
        'given_at'
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'given_at' => 'datetime',
        'bonus_amount' => 'decimal:2'
    ];

    public function givenBy()
    {
        return $this->belongsTo(User::class, 'given_by');
    }

    public function scopeFilterByPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('period_start', [$startDate, $endDate])
                    ->orWhereBetween('period_end', [$startDate, $endDate]);
    }

    public function scopeFilterByEmployee($query, $employeeName)
    {
        return $query->where('employee_name', $employeeName);
    }
}
