@extends('layouts.app')

@section('content')
<section class="content pt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-dark font-weight-medium">Bonus Details #{{ $bonus->id }}</h2>
                        <div class="float-right">
                            <a href="{{ route('admin.employee-performance.bonusEdit', $bonus->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('admin.employee-performance.bonusList') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="30%">Bonus ID:</th>
                                        <td>#{{ $bonus->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Employee:</th>
                                        <td>
                                            @if($bonus->employee_name)
                                                <span class="badge badge-info">{{ $bonus->employee_name }}</span>
                                            @else
                                                <span class="badge badge-success">All Active Employees</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Amount:</th>
                                        <td><strong class="text-success">Rp {{ number_format($bonus->bonus_amount, 0, ',', '.') }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Description:</th>
                                        <td>{{ $bonus->description }}</td>
                                    </tr>
                                    <tr>
                                        <th>Additional Notes:</th>
                                        <td>{{ $bonus->notes ?: '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="30%">Period Start:</th>
                                        <td>{{ $bonus->period_start ? $bonus->period_start->format('d F Y') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Period End:</th>
                                        <td>{{ $bonus->period_end ? $bonus->period_end->format('d F Y') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Given By:</th>
                                        <td>{{ $bonus->givenBy ? $bonus->givenBy->name : 'Unknown' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Given At:</th>
                                        <td>{{ $bonus->given_at ? $bonus->given_at->format('d F Y H:i') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Created At:</th>
                                        <td>{{ $bonus->created_at ? $bonus->created_at->format('d F Y H:i') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last Updated:</th>
                                        <td>{{ $bonus->updated_at ? $bonus->updated_at->format('d F Y H:i') : '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if($bonus->description || $bonus->notes)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Bonus Information</h5>
                                    </div>
                                    <div class="card-body">
                                        @if($bonus->description)
                                        <div class="mb-3">
                                            <strong>Description:</strong>
                                            <p class="mb-0">{{ $bonus->description }}</p>
                                        </div>
                                        @endif
                                        
                                        @if($bonus->notes)
                                        <div class="mb-0">
                                            <strong>Additional Notes:</strong>
                                            <p class="mb-0">{{ $bonus->notes }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($bonus->employee_name)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Employee Performance Summary</h5>
                                    </div>
                                    <div class="card-body">
                                        @php
                                            $stats = \App\Models\EmployeePerformance::where('employee_name', $bonus->employee_name)
                                                ->whereBetween('completed_at', [$bonus->period_start, $bonus->period_end])
                                                ->selectRaw('COUNT(*) as total_transactions, SUM(transaction_value) as total_revenue, AVG(transaction_value) as avg_transaction')
                                                ->first();
                                        @endphp
                                        
                                        @if($stats && $stats->total_transactions > 0)
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <h4 class="text-primary">{{ $stats->total_transactions }}</h4>
                                                    <p class="mb-0">Transactions in Period</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <h4 class="text-success">Rp {{ number_format($stats->total_revenue, 0, ',', '.') }}</h4>
                                                    <p class="mb-0">Total Revenue in Period</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <h4 class="text-info">Rp {{ number_format($stats->avg_transaction, 0, ',', '.') }}</h4>
                                                    <p class="mb-0">Average Transaction</p>
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        <p class="text-muted">No performance data available for this employee in the bonus period.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="btn-group">
                                    <a href="{{ route('admin.employee-performance.bonusEdit', $bonus->id) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Edit Bonus
                                    </a>
                                    <button type="button" class="btn btn-danger" onclick="deleteBonus({{ $bonus->id }})">
                                        <i class="fas fa-trash"></i> Delete Bonus
                                    </button>
                                    <a href="{{ route('admin.employee-performance.bonusList') }}" class="btn btn-secondary">
                                        <i class="fas fa-list"></i> Back to List
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('script-alt')
<script>
function deleteBonus(bonusId) {
    if (confirm('Are you sure you want to delete this bonus? This action cannot be undone.')) {
        $.ajax({
            url: '{{ route("admin.employee-performance.bonusDelete", ":id") }}'.replace(':id', bonusId),
            type: 'DELETE',
            data: {
                '_token': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    window.location.href = '{{ route("admin.employee-performance.bonusList") }}';
                } else {
                    alert('Error: ' + (response.message || 'Unknown error'));
                }
            },
            error: function(xhr, status, error) {
                var errorMsg = 'Error deleting bonus';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                alert(errorMsg);
            }
        });
    }
}
</script>
@endpush
