<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeePerformance;
use App\Models\EmployeeBonus;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EmployeePerformanceController extends Controller
{
    public function index()
    {
        $employees = EmployeePerformance::getEmployeeList();
        $totalTransactions = EmployeePerformance::count();
        $totalRevenue = EmployeePerformance::sum('transaction_value');
        $averageTransaction = EmployeePerformance::avg('transaction_value');
        
        $topEmployee = EmployeePerformance::selectRaw('employee_name, SUM(transaction_value) as total_revenue')
                                        ->groupBy('employee_name')
                                        ->orderBy('total_revenue', 'desc')
                                        ->first();

        return view('admin.employee-performance.index', compact(
            'employees', 'totalTransactions', 'totalRevenue', 'averageTransaction', 'topEmployee'
        ));
    }

    public function data(Request $request)
    {
        $query = EmployeePerformance::selectRaw('
            employee_name,
            COUNT(*) as total_transactions,
            SUM(transaction_value) as total_revenue,
            AVG(transaction_value) as average_transaction,
            MAX(completed_at) as last_transaction
        ')->groupBy('employee_name');

        if ($request->has('period') && $request->period) {
            $period = $request->period;
            $now = Carbon::now();
            
            switch ($period) {
                case 'today':
                    $query->whereDate('completed_at', $now->toDateString());
                    break;
                case 'week':
                    $query->whereBetween('completed_at', [$now->startOfWeek(), $now->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('completed_at', $now->month)
                          ->whereYear('completed_at', $now->year);
                    break;
                case 'year':
                    $query->whereYear('completed_at', $now->year);
                    break;
            }
        }

        if ($request->has('employee') && $request->employee) {
            $query->where('employee_name', 'like', '%' . $request->employee . '%');
        }

        if ($request->has('sort_by') && $request->sort_by) {
            $sortBy = $request->sort_by;
            switch ($sortBy) {
                case 'highest':
                    $query->orderBy('total_revenue', 'desc');
                    break;
                case 'lowest':
                    $query->orderBy('total_revenue', 'asc');
                    break;
                case 'latest':
                    $query->orderBy('last_transaction', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('last_transaction', 'asc');
                    break;
                default:
                    $query->orderBy('total_revenue', 'desc');
            }
        } else {
            $query->orderBy('total_revenue', 'desc');
        }

        return DataTables::of($query)
            ->addColumn('formatted_revenue', function ($row) {
                return 'Rp ' . number_format($row->total_revenue, 0, ',', '.');
            })
            ->addColumn('formatted_average', function ($row) {
                return 'Rp ' . number_format($row->average_transaction, 0, ',', '.');
            })
            ->addColumn('formatted_date', function ($row) {
                return Carbon::parse($row->last_transaction)->format('d/m/Y H:i');
            })
            ->addColumn('actions', function ($row) {
                return '<div class="btn-group">
                    <a href="' . route('admin.employee-performance.show', $row->employee_name) . '" class="btn btn-sm btn-info">
                        <i class="fas fa-eye"></i> Detail
                    </a>
                    <a href="' . route('admin.employee-performance.bonus', ['employee' => $row->employee_name]) . '" class="btn btn-sm btn-success">
                        <i class="fas fa-gift"></i> Bonus
                    </a>
                </div>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function show($employeeName)
    {
        $performances = EmployeePerformance::where('employee_name', $employeeName)
                                         ->with('order')
                                         ->orderBy('completed_at', 'desc')
                                         ->paginate(20);

        $bonuses = EmployeeBonus::where('employee_name', $employeeName)
                               ->with('givenBy')
                               ->orderBy('given_at', 'desc')
                               ->get();

        $stats = EmployeePerformance::getMonthlyStats($employeeName);

        return view('admin.employee-performance.show', compact('employeeName', 'performances', 'bonuses', 'stats'));
    }

    public function bonusForm(Request $request)
    {
        $employees = EmployeePerformance::getEmployeeList();
        return view('admin.employee-performance.bonus', compact('employees'));
    }

    public function giveBonus(Request $request)
    {
        $request->validate([
            'employee_name' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:1000',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'description' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000'
        ]);

        EmployeeBonus::create([
            'employee_name' => $request->employee_name ?: null,
            'bonus_amount' => $request->amount,
            'period_start' => $request->period_start,
            'period_end' => $request->period_end,
            'description' => $request->description,
            'notes' => $request->notes,
            'given_by' => Auth::id(),
            'given_at' => now()
        ]);

        $message = $request->employee_name 
            ? "Bonus berhasil diberikan kepada {$request->employee_name}!" 
            : "Bonus berhasil diberikan kepada semua karyawan aktif!";

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function bonusList()
    {
        $employees = EmployeePerformance::getEmployeeList();
        return view('admin.employee-performance.bonus-list', compact('employees'));
    }

    public function bonusData(Request $request)
    {
        $query = EmployeeBonus::with('givenBy');

        if ($request->has('employee') && $request->employee) {
            if ($request->employee === 'general') {
                $query->whereNull('employee_name');
            } else {
                $query->where('employee_name', $request->employee);
            }
        }

        if ($request->has('period') && $request->period) {
            $period = $request->period;
            $now = Carbon::now();
            
            switch ($period) {
                case 'today':
                    $query->whereDate('given_at', $now->toDateString());
                    break;
                case 'week':
                    $query->whereBetween('given_at', [$now->startOfWeek(), $now->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('given_at', $now->month)
                          ->whereYear('given_at', $now->year);
                    break;
                case 'year':
                    $query->whereYear('given_at', $now->year);
                    break;
            }
        }

        if ($request->has('description') && $request->description) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }

        return DataTables::of($query)
            ->addColumn('employee_display', function ($row) {
                return $row->employee_name ?: 'All Active Employees';
            })
            ->addColumn('formatted_amount', function ($row) {
                return 'Rp ' . number_format($row->bonus_amount, 0, ',', '.');
            })
            ->addColumn('period_display', function ($row) {
                $start = $row->period_start ? $row->period_start->format('d/m/Y') : '-';
                $end = $row->period_end ? $row->period_end->format('d/m/Y') : '-';
                return $start . ' - ' . $end;
            })
            ->addColumn('given_by_name', function ($row) {
                return $row->givenBy ? $row->givenBy->name : 'Unknown';
            })
            ->addColumn('formatted_given_at', function ($row) {
                return $row->given_at ? $row->given_at->format('d/m/Y H:i') : '-';
            })
            ->addColumn('actions', function ($row) {
                return '<div class="btn-group">
                    <a href="' . route('admin.employee-performance.bonusDetail', $row->id) . '" class="btn btn-sm btn-info">
                        <i class="fas fa-eye"></i> Detail
                    </a>
                    <a href="' . route('admin.employee-performance.bonusEdit', $row->id) . '" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function bonusDetail($id)
    {
        $bonus = EmployeeBonus::with('givenBy')->findOrFail($id);
        return view('admin.employee-performance.bonus-detail', compact('bonus'));
    }

    public function bonusEdit($id)
    {
        $bonus = EmployeeBonus::with('givenBy')->findOrFail($id);
        $employees = EmployeePerformance::getEmployeeList();
        return view('admin.employee-performance.bonus-edit', compact('bonus', 'employees'));
    }

    public function bonusUpdate(Request $request, $id)
    {
        $request->validate([
            'employee_name' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:1000',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'description' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000'
        ]);

        $bonus = EmployeeBonus::findOrFail($id);
        
        $bonus->update([
            'employee_name' => $request->employee_name ?: null,
            'bonus_amount' => $request->amount,
            'period_start' => $request->period_start,
            'period_end' => $request->period_end,
            'description' => $request->description,
            'notes' => $request->notes,
        ]);

        $message = $request->employee_name 
            ? "Bonus untuk {$request->employee_name} berhasil diupdate!" 
            : "Bonus untuk semua karyawan berhasil diupdate!";

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function bonusDelete($id)
    {
        $bonus = EmployeeBonus::findOrFail($id);
        $employeeName = $bonus->employee_name ?: 'semua karyawan';
        
        $bonus->delete();

        return response()->json([
            'success' => true,
            'message' => "Bonus untuk {$employeeName} berhasil dihapus!"
        ]);
    }

    public function bonusHistory()
    {
        $bonuses = EmployeeBonus::with('givenBy')
                               ->orderBy('given_at', 'desc')
                               ->paginate(20);

        return view('admin.employee-performance.bonus-history', compact('bonuses'));
    }
}
