@extends('layouts.app')

@section('title', 'Print Service Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-print text-primary me-2"></i>Print Service Dashboard
        </h1>
        <div>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#sessionModal">
                <i class="fas fa-plus me-1"></i>New Session
            </button>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Active Sessions
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeSessions }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-desktop fa-2x text-gray-300"></i>
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
                                Pending Payments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingOrders }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                                Print Queue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $printQueue }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-print fa-2x text-gray-300"></i>
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
                                Today's Orders
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayOrders }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list me-2"></i>Recent Print Orders
                    </h6>
                    <a href="{{ route('admin.print-service.orders') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-eye me-1"></i>View All
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Order Code</th>
                                    <th>Customer</th>
                                    <th>Paper Type</th>
                                    <th>Pages</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                <tr>
                                    <td>
                                        <strong class="text-primary">{{ $order->order_code }}</strong>
                                        <br><small class="text-muted">{{ $order->created_at->format('d M Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $order->customer_name }}</strong>
                                        <br><small class="text-muted">{{ $order->customer_phone }}</small>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $order->paperVariant->name ?? 'N/A' }}</span>
                                        <br><small class="text-muted">{{ ucfirst($order->print_type) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $order->total_pages }} pages</span>
                                        @if($order->quantity > 1)
                                        <br><small class="text-muted">{{ $order->quantity }} copies</small>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($order->status) {
                                                'payment_pending' => 'warning',
                                                'payment_confirmed' => 'success',
                                                'ready_to_print' => 'info',
                                                'printing' => 'primary',
                                                'printed' => 'dark',
                                                'completed' => 'success',
                                                'cancelled' => 'danger',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">
                                            {{ str_replace('_', ' ', ucwords($order->status, '_')) }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="text-success">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @if($order->payment_status === 'waiting' && $order->status === 'payment_pending')
                                            <button class="btn btn-success btn-sm" onclick="confirmPayment({{ $order->id }})" title="Confirm Payment">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            @endif
                                            
                                            @if(in_array($order->status, ['payment_confirmed', 'ready_to_print']))
                                            <button class="btn btn-primary btn-sm" onclick="printOrderFiles({{ $order->id }})" title="Print Files">
                                                <i class="fas fa-print"></i>
                                            </button>
                                            @endif
                                            
                                            @if($order->status === 'printing')
                                            <button class="btn btn-success btn-sm" onclick="completeOrder({{ $order->id }})" title="Mark Complete">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                            @endif
                                            
                                            <a href="{{ route('admin.print-service.orders') }}" class="btn btn-outline-info btn-sm" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-3x mb-3 d-block text-gray-300"></i>
                                        <h5>No recent orders</h5>
                                        <p>Orders will appear here when customers place print requests.</p>
                                    </td>
                                </tr>
                                @endforelse
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
                        <i class="fas fa-tachometer-alt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.print-service.queue') }}" class="btn btn-outline-primary">
                            <i class="fas fa-print me-2"></i>Print Queue
                            @if($printQueue > 0)
                            <span class="badge bg-primary ms-1">{{ $printQueue }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.print-service.orders') }}" class="btn btn-outline-info">
                            <i class="fas fa-list-alt me-2"></i>All Orders
                        </a>
                        <a href="{{ route('admin.print-service.sessions') }}" class="btn btn-outline-warning">
                            <i class="fas fa-desktop me-2"></i>Active Sessions
                            @if($activeSessions > 0)
                            <span class="badge bg-warning ms-1">{{ $activeSessions }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.print-service.reports') }}" class="btn btn-outline-success">
                            <i class="fas fa-chart-line me-2"></i>Reports & Analytics
                        </a>
                        <a href="{{ route('admin.print-service.stock') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-boxes me-2"></i>Stock Management
                            @if(isset($lowStockVariants) && $lowStockVariants->count() > 0)
                            <span class="badge bg-danger ms-1">{{ $lowStockVariants->count() }}</span>
                            @endif
                        </a>
                        <hr>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sessionModal">
                            <i class="fas fa-plus me-2"></i>Generate New Session
                        </button>
                    </div>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-boxes me-2"></i>Stock Status
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($stockData) && $stockData->count() > 0)
                        <div class="table-responsive" style="max-height: 300px;">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Paper</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stockData->take(10) as $variant)
                                    <tr>
                                        <td>
                                            <small>{{ $variant->paper_size }} {{ $variant->print_type }}</small>
                                        </td>
                                        <td>
                                            <small>{{ number_format($variant->stock) }}</small>
                                        </td>
                                        <td>
                                            @if($variant->stock <= 0)
                                                <span class="badge bg-danger">Out</span>
                                            @elseif($variant->stock <= ($variant->min_stock_threshold ?? 100))
                                                <span class="badge bg-warning">Low</span>
                                            @else
                                                <span class="badge bg-success">OK</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-2">
                            <a href="{{ route('admin.print-service.stock') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>View All
                            </a>
                        </div>
                    @else
                        <p class="text-muted text-center">No stock data available</p>
                    @endif
                </div>
            </div>

            <div class="card shadow mt-3">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-qrcode me-2"></i>Current Session
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div id="qr-code-display">
                        <p class="text-muted">Click "Generate New Session" to create QR code for customers</p>
                    </div>
                    <div id="session-info" style="display: none;">
                        <small class="text-muted">Session expires in: <span id="session-timer"></span></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Session Modal -->
<div class="modal fade" id="sessionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Print Session</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div id="modal-qr-code"></div>
                <div class="mt-3">
                    <p><strong>Session URL:</strong></p>
                    <div class="input-group">
                        <input type="text" class="form-control" id="session-url" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copySessionUrl()">
                            <i class="mdi mdi-content-copy"></i> Copy
                        </button>
                    </div>
                </div>
                <div class="mt-3">
                    <small class="text-muted">Display this QR code on your tablet for customers to scan</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="activateSession()">Activate Session</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    let currentSession = null;
    let sessionTimer = null;

    $(document).ready(function() {
        $('#generate-session-btn').click(function() {
            generateNewSession();
        });
    });

    async function generateNewSession() {
        try {
            const response = await fetch('{{ route("admin.print-service.generate-session") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const data = await response.json();
            
            if (data.success) {
                currentSession = data.session;
                showSessionModal(data);
            } else {
                alert('Failed to generate session: ' + (data.error || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error generating session:', error);
            alert('Failed to generate session');
        }
    }

    function showSessionModal(data) {
        document.getElementById('modal-qr-code').innerHTML = data.qr_code_svg || '';
        document.getElementById('session-url').value = data.qr_code_url;
        
        $('#sessionModal').modal('show');
    }

    function activateSession() {
        if (currentSession) {
            // Update main dashboard QR code
            document.getElementById('qr-code-display').innerHTML = document.getElementById('modal-qr-code').innerHTML;
            document.getElementById('session-info').style.display = 'block';
            
            startSessionTimer();
            $('#sessionModal').modal('hide');
        }
    }

    function startSessionTimer() {
        if (sessionTimer) clearInterval(sessionTimer);
        
        const expiresAt = new Date(currentSession.expires_at);
        
        sessionTimer = setInterval(function() {
            const now = new Date();
            const remaining = expiresAt - now;
            
            if (remaining <= 0) {
                clearInterval(sessionTimer);
                document.getElementById('session-timer').textContent = 'Expired';
                document.getElementById('qr-code-display').innerHTML = '<p class="text-muted">Session expired</p>';
                return;
            }
            
            const hours = Math.floor(remaining / (1000 * 60 * 60));
            const minutes = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((remaining % (1000 * 60)) / 1000);
            
            document.getElementById('session-timer').textContent = 
                `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }, 1000);
    }

    function copySessionUrl() {
        const urlInput = document.getElementById('session-url');
        urlInput.select();
        document.execCommand('copy');
        
        alert('URL copied to clipboard');
    }

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
            console.error('Confirm payment error:', error);
        }
    }

    async function printOrderFiles(orderId) {
        if (!confirm('Open customer files for printing? This will mark the order as "Printing".')) return;
        
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
                alert(`Files ready for printing!\n\nOrder: ${data.order_code}\nCustomer: ${data.customer_name}\nPaper: ${data.print_data.paper_size} - ${data.print_data.print_type}\nPages: ${data.print_data.total_pages} x ${data.print_data.quantity} copies\n\nPress Ctrl+P to print when files open, then click "Complete" when done.`);
                
                for (let filePath of data.files) {
                    window.open(`file:///${filePath}`, '_blank');
                }
                
                location.reload();
            } else {
                alert('Failed to prepare files for printing: ' + (data.error || 'Unknown error'));
            }
        } catch (error) {
            alert('Error preparing files for printing');
            console.error('Print files error:', error);
        }
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
</script>
@endsection
