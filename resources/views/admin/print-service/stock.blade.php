@extends('layouts.app')

@section('title', 'Stock Management')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-boxes text-primary me-2"></i>Stock Management - Print Service
        </h1>
        <div>
            <a href="{{ route('admin.print-service.stock-report') }}" class="btn btn-info btn-sm">
                <i class="fas fa-chart-line me-1"></i>Stock Report
            </a>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#adjustStockModal">
                <i class="fas fa-edit me-1"></i>Adjust Stock
            </button>
        </div>
    </div>

    @if(isset($lowStockVariants) && $lowStockVariants->count() > 0)
    <div class="alert alert-warning" role="alert">
        <h4 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Low Stock Alert!</h4>
        <p>The following paper variants are running low on stock:</p>
        <ul class="mb-0">
            @foreach($lowStockVariants as $variant)
            <li>{{ $variant->paper_size }} {{ $variant->print_type }} - Only {{ $variant->stock }} sheets left</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Variants
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ isset($variants) ? $variants->count() : 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-layer-group fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                In Stock
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ isset($variants) ? $variants->where('stock', '>', 0)->count() : 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                Low Stock
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ isset($lowStockVariants) ? $lowStockVariants->count() : 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
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
                                Out of Stock
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ isset($variants) ? $variants->where('stock', '<=', 0)->count() : 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-boxes me-2"></i>Paper Stock Levels
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Product</th>
                                    <th>Paper Size</th>
                                    <th>Print Type</th>
                                    <th>Current Stock</th>
                                    <th>Price per Sheet</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($variants))
                                    @foreach($variants as $variant)
                                    <tr>
                                        <td>{{ $variant->product->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $variant->paper_size }}</span>
                                        </td>
                                        <td>
                                            @if($variant->print_type == 'bw')
                                                <span class="badge bg-dark">Black & White</span>
                                            @else
                                                <span class="badge bg-info">{{ ucfirst($variant->print_type) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ number_format($variant->stock) }}</strong> sheets
                                        </td>
                                        <td>Rp {{ number_format((float)$variant->price) }}</td>
                                        <td>
                                            @if($variant->stock <= 0)
                                                <span class="badge bg-danger">Out of Stock</span>
                                            @elseif($variant->stock <= ($variant->min_stock_threshold ?? 100))
                                                <span class="badge bg-warning">Low Stock</span>
                                            @else
                                                <span class="badge bg-success">Available</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" onclick="showAdjustStock({{ $variant->id }}, '{{ $variant->paper_size }} {{ $variant->print_type }}', {{ $variant->stock }})">
                                                <i class="fas fa-edit"></i> Adjust
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="fas fa-box-open fa-3x mb-3 d-block text-gray-300"></i>
                                            <h5>No stock data available</h5>
                                            <p>Stock information will appear here when variants are available.</p>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history me-2"></i>Recent Stock Movements
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($recentMovements) && $recentMovements->count() > 0)
                        <div style="max-height: 400px; overflow-y: auto;">
                            @foreach($recentMovements->take(10) as $movement)
                            <div class="border-left-{{ $movement->movement_type == 'in' ? 'success' : 'danger' }} p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">
                                            @if($movement->movement_type == 'in')
                                                <i class="fas fa-plus-circle text-success me-1"></i>Stock In
                                            @else
                                                <i class="fas fa-minus-circle text-danger me-1"></i>Stock Out
                                            @endif
                                        </h6>
                                        <p class="mb-1 text-sm">
                                            {{ $movement->variant->paper_size ?? 'N/A' }} {{ $movement->variant->print_type ?? 'N/A' }}
                                        </p>
                                        <p class="mb-1 text-sm text-muted">
                                            {{ $movement->reason }}
                                        </p>
                                        <small class="text-muted">
                                            {{ $movement->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-weight-bold">
                                            {{ $movement->movement_type == 'in' ? '+' : '-' }}{{ $movement->quantity }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $movement->old_stock }} → {{ $movement->new_stock }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">No recent stock movements</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="adjustStockModal" tabindex="-1" aria-labelledby="adjustStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adjustStockModalLabel">
                    <i class="fas fa-edit me-2"></i>Adjust Stock
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="adjustStockForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="variant_name" class="form-label">Paper Variant</label>
                        <input type="text" class="form-control" id="variant_name" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="current_stock" class="form-label">Current Stock</label>
                        <input type="text" class="form-control" id="current_stock" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="new_stock" class="form-label">New Stock <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="new_stock" name="new_stock" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason <span class="text-danger">*</span></label>
                        <select class="form-select" id="reason" name="reason" required>
                            <option value="">Select reason...</option>
                            <option value="restock">Restock</option>
                            <option value="damage">Damage/Loss</option>
                            <option value="correction">Stock Correction</option>
                            <option value="theft">Theft</option>
                            <option value="expired">Expired</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Additional notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Update Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showAdjustStock(variantId, variantName, currentStock) {
    document.getElementById('variant_name').value = variantName;
    document.getElementById('current_stock').value = currentStock + ' sheets';
    document.getElementById('new_stock').value = currentStock;
    
    const form = document.getElementById('adjustStockForm');
    form.action = '{{ route("admin.print-service.stock.adjust", ":id") }}'.replace(':id', variantId);
    
    new bootstrap.Modal(document.getElementById('adjustStockModal')).show();
}

document.getElementById('adjustStockForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to update stock'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating stock');
    });
});
</script>
@endsection
