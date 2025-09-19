@extends('layouts.app')

@section('content')
<section class="content pt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-dark font-weight-medium">Give Employee Bonus</h2>
                        <div class="float-right">
                            <a href="{{ route('admin.employee-performance.bonusList') }}" class="btn btn-info btn-sm">
                                <i class="fas fa-list"></i> Manage Bonuses
                            </a>
                            <a href="{{ route('admin.employee-performance.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to Performance Dashboard
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="bonusForm" method="POST" action="{{ route('admin.employee-performance.giveBonus') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="employee_name">Employee Name</label>
                                        <select id="employee_name" name="employee_name" class="form-control">
                                            <option value="">All Active Employees</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee }}" {{ request('employee') == $employee ? 'selected' : '' }}>
                                                    {{ $employee }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Leave empty to give bonus to all active employees</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="amount">Bonus Amount (Rp)</label>
                                        <input type="number" 
                                               id="amount" 
                                               name="amount" 
                                               class="form-control" 
                                               min="1000" 
                                               step="1000" 
                                               required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="period_start">Period Start</label>
                                        <input type="date" 
                                               id="period_start" 
                                               name="period_start" 
                                               class="form-control" 
                                               value="{{ date('Y-m-01') }}" 
                                               required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="period_end">Period End</label>
                                        <input type="date" 
                                               id="period_end" 
                                               name="period_end" 
                                               class="form-control" 
                                               value="{{ date('Y-m-t') }}" 
                                               required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" 
                                          name="description" 
                                          class="form-control" 
                                          rows="3" 
                                          required 
                                          placeholder="Enter bonus description..."></textarea>
                            </div>

                            <div class="form-group">
                                <label for="notes">Additional Notes (Optional)</label>
                                <textarea id="notes" 
                                          name="notes" 
                                          class="form-control" 
                                          rows="2" 
                                          placeholder="Any additional notes..."></textarea>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-gift"></i> Give Bonus
                                </button>
                                <a href="{{ route('admin.employee-performance.index') }}" class="btn btn-secondary btn-lg">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('script-alt')
<script>
$(document).ready(function() {
    $('#bonusForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    window.location.href = '{{ route("admin.employee-performance.index") }}';
                } else {
                    alert('Error: ' + (response.message || 'Unknown error'));
                }
            },
            error: function(xhr, status, error) {
                var errorMsg = 'Error giving bonus';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                alert(errorMsg);
            }
        });
    });
});
</script>
@endpush
