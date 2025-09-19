@extends('layouts.app')

@section('title', 'Print Service Orders')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{ route('admin.print-service.index') }}" class="btn btn-secondary">
                        <i class="mdi mdi-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
                <h4 class="page-title">Print Service Orders</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-centered mb-0">
                            <thead>
                                <tr>
                                    <th>Order Code</th>
                                    <th>Customer</th>
                                    <th>Product</th>
                                    <th>Pages</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Total</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td>
                                        <strong>{{ $order->order_code }}</strong>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $order->customer_name }}</strong><br>
                                            <small class="text-muted">{{ $order->customer_phone }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            {{ $order->paperProduct ? $order->paperProduct->name : 'N/A' }}<br>
                                            <small class="text-muted">
                                                {{ $order->paperVariant ? $order->paperVariant->paper_size . ' ' . ucfirst($order->paperVariant->print_type) : 'N/A' }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $order->total_pages }} pages</span>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($order->status) {
                                                'payment_pending' => 'warning',
                                                'payment_confirmed' => 'success',
                                                'ready_to_print' => 'info',
                                                'printing' => 'primary',
                                                'completed' => 'success',
                                                'cancelled' => 'danger',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">
                                            {{ ucwords(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $paymentClass = match($order->payment_status) {
                                                'unpaid' => 'danger',
                                                'waiting' => 'warning',
                                                'paid' => 'success',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $paymentClass }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                                    </td>
                                    <td>
                                        <small>{{ $order->created_at->format('d M Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @if($order->payment_status === 'waiting' && $order->status === 'payment_pending')
                                            <button class="btn btn-sm btn-success" onclick="confirmPayment({{ $order->id }})" title="Confirm Payment">
                                                <i class="mdi mdi-check"></i> Confirm Payment
                                            </button>
                                            @endif
                                            
                                            @if($order->payment_method === 'manual' && $order->payment_proof)
                                            <button class="btn btn-sm btn-info" onclick="viewPaymentProof({{ $order->id }})" title="View Payment Proof">
                                                <i class="mdi mdi-file-image"></i> View Payment Proof
                                            </button>
                                            @endif
                                            
                                            @if($order->status === 'payment_confirmed' || $order->status === 'ready_to_print' || $order->status === 'printing')
                                            @if($order->status === 'completed')
                                            <button class="btn btn-sm btn-danger" disabled title="Order Completed - Files Deleted">
                                                <i class="mdi mdi-file-remove"></i> Files Deleted
                                            </button>
                                            @else
                                            <button class="btn btn-sm btn-primary" onclick="viewOrderFiles({{ $order->id }})" title="View Customer Files">
                                                <i class="mdi mdi-file-document"></i> See Files
                                            </button>
                                            <button class="btn btn-sm btn-success ms-1" onclick="completeOrder({{ $order->id }})" title="Complete Order & Delete Files">
                                                <i class="mdi mdi-check-circle"></i> Complete Order
                                            </button>
                                            @endif
                                            @endif
                                            
                                            <button class="btn btn-sm btn-outline-info" onclick="viewDetails({{ $order->id }})" title="View Order Details">
                                                <i class="mdi mdi-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="mdi mdi-inbox mdi-24px"></i><br>
                                        No orders found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($orders->hasPages())
                    <div class="mt-3">
                        {{ $orders->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
async function confirmPayment(orderId) {
    if (!confirm('Confirm payment for this order?')) return;
    
    try {
        const response = await fetch(`/admin/print-service/orders/${orderId}/confirm-payment`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Payment confirmed successfully!');
            location.reload();
        } else {
            alert('Failed to confirm payment: ' + (data.error || 'Unknown error'));
        }
    } catch (error) {
        alert('Error confirming payment');
    }
}

async function viewOrderFiles(orderId) {
    try {
        const response = await fetch(`/admin/print-service/orders/${orderId}/print-files`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            if (data.files.length === 1) {
                window.open(data.files[0].view_url, '_blank');
            } else {
                let fileList = '';
                data.files.forEach(file => {
                    fileList += `• ${file.original_name}\n`;
                });
                
                const message = `Order: ${data.order_code} | Customer: ${data.customer_name}
Paper: ${data.print_data.paper_size} - ${data.print_data.print_type} | Pages: ${data.print_data.total_pages} x ${data.print_data.quantity}

Files (${data.files.length}):
${fileList}
Files will open in new tabs. Use Ctrl+P to print.`;
                
                if (confirm(message + '\n\nOpen all files now?')) {
                    data.files.forEach(file => {
                        window.open(file.view_url, '_blank');
                    });
                }
            }
        } else {
            alert('Failed to load files: ' + (data.error || 'Unknown error'));
        }
    } catch (error) {
        alert('Error loading files');
        console.error('View files error:', error);
    }
}

async function printOrder(orderId) {
    return printOrderFiles(orderId);
}

async function completeOrder(orderId) {
    if (!confirm('Mark this order as completed?\n\nThis will:\n- Mark the order as complete\n- Delete customer files for privacy\n- Close the session\n\nThis action cannot be undone.')) return;
    
    try {
        const response = await fetch(`/admin/print-service/orders/${orderId}/complete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Order completed and files deleted for privacy!');
            location.reload();
        } else {
            alert('Failed to complete order: ' + (data.error || 'Unknown error'));
        }
    } catch (error) {
        alert('Error completing order');
        console.error('Complete order error:', error);
    }
}

function viewDetails(orderId) {
    // You can implement a modal or redirect to detailed view
    alert('View details functionality - to be implemented');
}

async function viewPaymentProof(orderId) {
    try {
        window.open(`/admin/print-service/orders/${orderId}/payment-proof`, '_blank');
    } catch (error) {
        alert('Error opening payment proof');
        console.error('Payment proof error:', error);
    }
}
</script>
@endsection
