@extends('layouts.app')

@section('title', 'Stock Report')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-line text-primary me-2"></i>Stock Report - Print Service
        </h1>
        <div>
            <a href="{{ route('admin.print-service.stock') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Back to Stock Management
            </a>
            <button class="btn btn-primary btn-sm" onclick="exportReport()">
                <i class="fas fa-download me-1"></i>Export CSV
            </button>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>Filter Options
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.print-service.stock-report') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="variant_id" class="form-label">Paper Variant</label>
                    <select class="form-select" id="variant_id" name="variant_id">
                        <option value="">All Variants</option>
                        @if(isset($variants))
                            @foreach($variants as $variant)
                            <option value="{{ $variant->id }}" {{ request('variant_id') == $variant->id ? 'selected' : '' }}>
                                {{ $variant->paper_size }} {{ $variant->print_type }}
                            </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date_from" class="form-label">Date From</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" 
                           value="{{ request('date_from', now()->subDays(30)->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label">Date To</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" 
                           value="{{ request('date_to', now()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    @if(isset($movements))
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Stock In
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($movements->where('movement_type', 'in')->sum('quantity')) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-plus-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Total Stock Out
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($movements->where('movement_type', 'out')->sum('quantity')) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-minus-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Movements
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($movements->count()) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exchange-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Net Change
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($movements->where('movement_type', 'in')->sum('quantity') - $movements->where('movement_type', 'out')->sum('quantity')) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-balance-scale fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Stock Movements Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-history me-2"></i>Stock Movement History
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="stockReportTable" width="100%" cellspacing="0">
                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            <th>Variant</th>
                            <th>Movement Type</th>
                            <th>Quantity</th>
                            <th>Before</th>
                            <th>After</th>
                            <th>Reason</th>
                            <th>Notes</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($movements) && $movements->count() > 0)
                            @foreach($movements as $movement)
                            <tr>
                                <td>
                                    <small>{{ $movement->created_at->format('d/m/Y H:i') }}</small>
                                </td>
                                <td>
                                    @if($movement->variant)
                                        <span class="badge bg-secondary">{{ $movement->variant->paper_size }}</span>
                                        <span class="badge bg-info">{{ $movement->variant->print_type }}</span>
                                    @else
                                        <span class="text-muted">Variant Deleted</span>
                                    @endif
                                </td>
                                <td>
                                    @if($movement->movement_type == 'in')
                                        <span class="badge bg-success">
                                            <i class="fas fa-plus-circle me-1"></i>Stock In
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-minus-circle me-1"></i>Stock Out
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ number_format($movement->quantity) }}</strong>
                                </td>
                                <td>{{ number_format($movement->old_stock) }}</td>
                                <td>{{ number_format($movement->new_stock) }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $movement->reason }}</span>
                                </td>
                                <td>
                                    @if($movement->notes)
                                        <small>{{ Str::limit($movement->notes, 50) }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($movement->user)
                                        <small>{{ $movement->user->name }}</small>
                                    @else
                                        <span class="text-muted">System</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="fas fa-history fa-3x mb-3 d-block text-gray-300"></i>
                                    <h5>No stock movements found</h5>
                                    <p>Stock movement history will appear here when there are stock changes.</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function exportReport() {
    const params = new URLSearchParams();
    
    const variantId = document.getElementById('variant_id').value;
    const dateFrom = document.getElementById('date_from').value;
    const dateTo = document.getElementById('date_to').value;
    
    if (variantId) params.append('variant_id', variantId);
    if (dateFrom) params.append('date_from', dateFrom);
    if (dateTo) params.append('date_to', dateTo);
    params.append('export', 'csv');
    
    window.location.href = '{{ route("admin.print-service.stock-report") }}?' + params.toString();
}

// Initialize DataTable if data exists
@if(isset($movements) && $movements->count() > 0)
$(document).ready(function() {
    $('#stockReportTable').DataTable({
        responsive: true,
        order: [[0, 'desc']], // Sort by date descending
        pageLength: 25,
        language: {
            search: "Search movements:",
            lengthMenu: "Show _MENU_ movements per page",
            info: "Showing _START_ to _END_ of _TOTAL_ movements",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });
});
@endif
</script>
@endsection