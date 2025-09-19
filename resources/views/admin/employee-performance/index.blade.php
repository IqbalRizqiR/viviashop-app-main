@extends('layouts.app')

@section('content')
<section class="content pt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-dark font-weight-medium">Employee Performance Dashboard</h2>
                    </div>
                    <div class="card-body">
                        <!-- Summary Cards -->
                        <div class="row mb-4">
                            <div class="col-lg-3 col-md-6">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h4>{{ $totalTransactions ?? 0 }}</h4>
                                        <p>Total Transactions</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h4>Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h4>
                                        <p>Total Revenue</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h4>Rp {{ number_format($averageTransaction ?? 0, 0, ',', '.') }}</h4>
                                        <p>Average Transaction</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <h4>{{ $topEmployee->employee_name ?? 'N/A' }}</h4>
                                        <p>Top Performer</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filters -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <select id="periodFilter" class="form-control">
                                    <option value="">All Time</option>
                                    <option value="today">Today</option>
                                    <option value="week">This Week</option>
                                    <option value="month">This Month</option>
                                    <option value="year">This Year</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" id="employeeFilter" class="form-control" placeholder="Search employee...">
                            </div>
                            <div class="col-md-3">
                                <select id="sortFilter" class="form-control">
                                    <option value="highest">Highest Revenue</option>
                                    <option value="lowest">Lowest Revenue</option>
                                    <option value="latest">Latest Activity</option>
                                    <option value="oldest">Oldest Activity</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="btn-group w-100">
                                    <a href="{{ route('admin.employee-performance.bonus') }}" class="btn btn-success">
                                        <i class="fas fa-gift"></i> Give Bonus
                                    </a>
                                    <a href="{{ route('admin.employee-performance.bonusList') }}" class="btn btn-info">
                                        <i class="fas fa-list"></i> Manage Bonuses
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- DataTable -->
                        <div class="table-responsive">
                            <table id="performanceTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Employee Name</th>
                                        <th>Total Transactions</th>
                                        <th>Total Revenue</th>
                                        <th>Average Transaction</th>
                                        <th>Last Transaction</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('style-alt')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap4.min.css">
@endpush

@push('script-alt')
<script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    var table = $('#performanceTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.employee-performance.data') }}",
            data: function(d) {
                d.period = $('#periodFilter').val();
                d.employee = $('#employeeFilter').val();
                d.sort_by = $('#sortFilter').val();
            }
        },
        columns: [
            {data: 'employee_name', name: 'employee_name'},
            {data: 'total_transactions', name: 'total_transactions'},
            {data: 'formatted_revenue', name: 'total_revenue'},
            {data: 'formatted_average', name: 'average_transaction'},
            {data: 'formatted_date', name: 'last_transaction'},
            {data: 'actions', name: 'actions', orderable: false, searchable: false}
        ]
    });

    $('#periodFilter, #employeeFilter, #sortFilter').change(function() {
        table.ajax.reload();
    });
});
</script>
@endpush
