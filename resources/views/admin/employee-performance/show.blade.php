@extends('layouts.app')

@section('content')
<section class="content pt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-dark font-weight-medium">Employee Performance: {{ $employeeName }}</h2>
                        <a href="{{ route('admin.employee-performance.index') }}" class="btn btn-secondary float-right">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                    <div class="card-body">
                        <!-- Statistics Cards -->
                        <div class="row mb-4">
                            <div class="col-lg-4 col-md-6">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h4>{{ $stats['total_transactions'] ?? 0 }}</h4>
                                        <p>Total Transactions</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h4>Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</h4>
                                        <p>Total Revenue</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h4>Rp {{ number_format($stats['average_transaction'] ?? 0, 0, ',', '.') }}</h4>
                                        <p>Average Transaction</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Transaction History -->
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Transaction History</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Order ID</th>
                                                        <th>Transaction Value</th>
                                                        <th>Completed At</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($performances as $performance)
                                                    <tr>
                                                        <td>
                                                            <a href="{{ route('admin.orders.show', $performance->order_id) }}">
                                                                #{{ $performance->order->code ?? $performance->order_id }}
                                                            </a>
                                                        </td>
                                                        <td>Rp {{ number_format($performance->transaction_value, 0, ',', '.') }}</td>
                                                        <td>{{ $performance->completed_at ? $performance->completed_at->format('d/m/Y H:i') : '-' }}</td>
                                                        <td>
                                                            <a href="{{ route('admin.orders.show', $performance->order_id) }}" class="btn btn-sm btn-info">
                                                                View Order
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">No transactions found</td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                        {{ $performances->links() }}
                                    </div>
                                </div>
                            </div>

                            <!-- Bonus History -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Bonus History</h5>
                                        <button type="button" class="btn btn-sm btn-success float-right" onclick="showBonusModal('{{ $employeeName }}')">
                                            <i class="fas fa-gift"></i> Give Bonus
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        @forelse($bonuses as $bonus)
                                        <div class="mb-3 p-3 border rounded">
                                            <h6 class="text-success">Rp {{ number_format($bonus->bonus_amount, 0, ',', '.') }}</h6>
                                            <small class="text-muted">
                                                {{ $bonus->period_start ? $bonus->period_start->format('d/m/Y') : '-' }} - {{ $bonus->period_end ? $bonus->period_end->format('d/m/Y') : '-' }}
                                            </small>
                                            <br>
                                            <small>Given by: {{ $bonus->givenBy->name ?? 'Unknown' }}</small>
                                            @if($bonus->notes)
                                            <br>
                                            <small class="text-muted">{{ $bonus->notes }}</small>
                                            @endif
                                        </div>
                                        @empty
                                        <p class="text-muted">No bonuses given yet</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Bonus Modal -->
<div class="modal fade" id="bonusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Give Bonus to {{ $employeeName }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="bonusForm">
                <div class="modal-body">
                    <input type="hidden" name="employee_name" value="{{ $employeeName }}">
                    <div class="form-group">
                        <label for="bonusAmount">Bonus Amount:</label>
                        <input type="number" id="bonusAmount" name="bonus_amount" class="form-control" min="0" step="1000" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="periodStart">Period Start:</label>
                                <input type="date" id="periodStart" name="period_start" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="periodEnd">Period End:</label>
                                <input type="date" id="periodEnd" name="period_end" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bonusNotes">Notes (Optional):</label>
                        <textarea id="bonusNotes" name="notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Give Bonus</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script-alt')
<script>
$(document).ready(function() {
    // Bonus form submission
    $('#bonusForm').submit(function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        formData += '&_token={{ csrf_token() }}';
        
        $.ajax({
            url: '{{ route("admin.employee-performance.giveBonus") }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    $('#bonusModal').modal('hide');
                    location.reload(); // Reload to show new bonus
                }
            },
            error: function(xhr, status, error) {
                alert('Error giving bonus: ' + error);
            }
        });
    });
});

// Show bonus modal for specific employee
function showBonusModal(employeeName) {
    $('#bonusModal').modal('show');
}
</script>
@endpush
