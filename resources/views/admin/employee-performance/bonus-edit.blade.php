@extends('layouts.app')

@section('content')
<section class="content pt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-dark font-weight-medium">Edit Bonus #{{ $bonus->id }}</h2>
                        <div class="float-right">
                            <a href="{{ route('admin.employee-performance.bonusDetail', $bonus->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                            <a href="{{ route('admin.employee-performance.bonusList') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="editBonusForm" method="POST" action="{{ route('admin.employee-performance.bonusUpdate', $bonus->id) }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="employee_name">Employee Name</label>
                                        <select id="employee_name" name="employee_name" class="form-control">
                                            <option value="">All Active Employees</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee }}" {{ $bonus->employee_name == $employee ? 'selected' : '' }}>
                                                    {{ $employee }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Leave empty to assign bonus to all active employees</small>
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
                                               value="{{ $bonus->bonus_amount }}"
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
                                               value="{{ $bonus->period_start ? $bonus->period_start->format('Y-m-d') : '' }}" 
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
                                               value="{{ $bonus->period_end ? $bonus->period_end->format('Y-m-d') : '' }}" 
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
                                          placeholder="Enter bonus description...">{{ $bonus->description }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="notes">Additional Notes (Optional)</label>
                                <textarea id="notes" 
                                          name="notes" 
                                          class="form-control" 
                                          rows="2" 
                                          placeholder="Any additional notes...">{{ $bonus->notes }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Original Created By:</label>
                                        <p class="form-control-static">{{ $bonus->givenBy ? $bonus->givenBy->name : 'Unknown' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Original Created At:</label>
                                        <p class="form-control-static">{{ $bonus->given_at ? $bonus->given_at->format('d F Y H:i') : '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> Update Bonus
                                </button>
                                <a href="{{ route('admin.employee-performance.bonusDetail', $bonus->id) }}" class="btn btn-secondary btn-lg">
                                    Cancel
                                </a>
                                <button type="button" class="btn btn-danger btn-lg" onclick="deleteBonus({{ $bonus->id }})">
                                    <i class="fas fa-trash"></i> Delete Bonus
                                </button>
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
    $('#editBonusForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'PUT',
            data: formData,
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    window.location.href = '{{ route("admin.employee-performance.bonusDetail", $bonus->id) }}';
                } else {
                    alert('Error: ' + (response.message || 'Unknown error'));
                }
            },
            error: function(xhr, status, error) {
                var errorMsg = 'Error updating bonus';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                alert(errorMsg);
            }
        });
    });
});

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
