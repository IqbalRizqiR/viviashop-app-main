@extends('layouts.app')

@section('title', 'Print Queue')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <button class="btn btn-success" onclick="refreshQueue()">
                        <i class="mdi mdi-refresh"></i> Refresh
                    </button>
                </div>
                <h4 class="page-title">Print Queue</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Order Code</th>
                                    <th>Customer</th>
                                    <th>Paper</th>
                                    <th>Files</th>
                                    <th>Pages</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($printQueue as $order)
                                <tr id="order-row-{{ $order->id }}">
                                    <td>
                                        <strong>{{ $order->order_code }}</strong>
                                    </td>
                                    <td>
                                        <div>{{ $order->customer_name }}</div>
                                        <small class="text-muted">{{ $order->customer_phone }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $order->paperVariant->name }}</div>
                                        <small class="text-muted">{{ ucfirst($order->print_type) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $order->files->count() }} files</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $order->total_pages }} pages</span>
                                        @if($order->quantity > 1)
                                        <br><small class="text-muted">{{ $order->quantity }} copies</small>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                                    </td>
                                    <td>
                                        @php
                                            $statusClasses = [
                                                'payment_confirmed' => 'bg-success',
                                                'ready_to_print' => 'bg-primary',
                                                'printing' => 'bg-warning',
                                                'printed' => 'bg-info'
                                            ];
                                            $statusClass = $statusClasses[$order->status] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $statusClass }}">
                                            {{ str_replace('_', ' ', ucwords($order->status, '_')) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $order->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @if($order->status === 'payment_confirmed')
                                            <button class="btn btn-sm btn-primary" onclick="printOrder({{ $order->id }})">
                                                <i class="mdi mdi-printer"></i> Print
                                            </button>
                                            @endif
                                            
                                            @if($order->status === 'printed')
                                            <button class="btn btn-sm btn-success" onclick="completeOrder({{ $order->id }})">
                                                <i class="mdi mdi-check"></i> Complete
                                            </button>
                                            @endif
                                            
                                            <button class="btn btn-sm btn-info" onclick="viewOrderDetails({{ $order->id }})">
                                                <i class="mdi mdi-eye"></i> View
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="mdi mdi-inbox mdi-2x mb-2"></i>
                                        <br>No orders in print queue
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="orderDetailsContent">
                Loading...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    function refreshQueue() {
        window.location.reload();
    }

    async function printOrder(orderId) {
        if (!confirm('Send this order to printer?')) return;
        
        try {
            const response = await fetch(`{{ url('admin/print-service/orders') }}/${orderId}/print`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const data = await response.json();
            
            if (data.success) {
                alert('Order sent to printer successfully');
                refreshQueue();
            } else {
                alert('Failed to print: ' + (data.error || 'Unknown error'));
            }
        } catch (error) {
            console.error('Print error:', error);
            alert('Failed to print order');
        }
    }

    async function completeOrder(orderId) {
        if (!confirm('Mark this order as completed? This will delete the uploaded files.')) return;
        
        try {
            const response = await fetch(`{{ url('admin/print-service/orders') }}/${orderId}/complete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const data = await response.json();
            
            if (data.success) {
                alert('Order completed successfully');
                document.getElementById(`order-row-${orderId}`).remove();
            } else {
                alert('Failed to complete order: ' + (data.error || 'Unknown error'));
            }
        } catch (error) {
            console.error('Complete error:', error);
            alert('Failed to complete order');
        }
    }

    async function viewOrderDetails(orderId) {
        try {
            // Show loading state
            document.getElementById('orderDetailsContent').innerHTML = 'Loading...';
            $('#orderDetailsModal').modal('show');
            
            // Here you would load order details
            // For now, we'll show a placeholder
            document.getElementById('orderDetailsContent').innerHTML = `
                <div class="alert alert-info">
                    Order details would be loaded here. This feature will show:
                    <ul>
                        <li>Customer information</li>
                        <li>File details and thumbnails</li>
                        <li>Payment information</li>
                        <li>Print specifications</li>
                    </ul>
                </div>
            `;
        } catch (error) {
            console.error('Error loading order details:', error);
            document.getElementById('orderDetailsContent').innerHTML = 'Error loading order details';
        }
    }

    // Auto-refresh every 30 seconds
    setInterval(function() {
        refreshQueue();
    }, 30000);
</script>
@endsection
