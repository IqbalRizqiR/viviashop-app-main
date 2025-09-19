@extends('layouts.app')

@section('content')
<section class="content pt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-dark font-weight-medium">Bonus Management</h2>
                        <div class="float-right">
                            <a href="{{ route('admin.employee-performance.bonus') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i> Add New Bonus
                            </a>
                            <a href="{{ route('admin.employee-performance.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select id="employeeFilter" class="form-control">
                                    <option value="">All Employees</option>
                                    <option value="general">General Bonuses</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee }}">{{ $employee }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select id="periodFilter" class="form-control">
                                    <option value="">All Periods</option>
                                    <option value="today">Today</option>
                                    <option value="week">This Week</option>
                                    <option value="month">This Month</option>
                                    <option value="year">This Year</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" id="descriptionFilter" class="form-control" placeholder="Search description...">
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="bonusTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Employee</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                        <th>Period</th>
                                        <th>Given By</th>
                                        <th>Given At</th>
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
    var table = $('#bonusTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.employee-performance.bonusData') }}",
            data: function(d) {
                d.employee = $('#employeeFilter').val();
                d.period = $('#periodFilter').val();
                d.description = $('#descriptionFilter').val();
            }
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'employee_display', name: 'employee_name'},
            {data: 'formatted_amount', name: 'bonus_amount'},
            {data: 'description', name: 'description'},
            {data: 'period_display', name: 'period_start'},
            {data: 'given_by_name', name: 'given_by'},
            {data: 'formatted_given_at', name: 'given_at'},
            {data: 'actions', name: 'actions', orderable: false, searchable: false}
        ],
        order: [[6, 'desc']]
    });

    $('#employeeFilter, #periodFilter, #descriptionFilter').on('change keyup', function() {
        table.ajax.reload();
    });
});
</script>
@endpush
