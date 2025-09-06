@extends('layouts.app')

@section('content')

<section class="content pt-4">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h2 class="text-dark font-weight-medium">Order ID #{{ $order->code }}</h2>
                @if ($order->attachments != null)
							<a href="{{ asset('/storage/' . $order->attachments) }}" class="btn btn-primary">See attachments</a>
						@endif
                @if ($order->payment_slip != null)
							<a href="{{ asset('/storage/' . $order->payment_slip) }}" class="btn btn-success">View Payment Slip</a>
						@endif
                <div class="btn-group float-right">
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row pt-2 mb-3">
                    <div class="col-lg-4">
                        <p class="text-dark" style="font-weight: normal; font-size:16px; text-transform: uppercase;">Billing Address</p>
                        <address>
                            {{ $order->customer_full_name }}
                             {{ $order->customer_address1 }}
                             {{ $order->customer_address2 }}
                            <br> Email: {{ $order->customer_email }}
                            <br> Phone: {{ $order->customer_phone }}
                            <br> Postcode: {{ $order->customer_postcode }}
                        </address>
                    </div>
                    <div class="col-lg-4">
                        <p class="text-dark" style="font-weight: normal; font-size:16px; text-transform: uppercase;">Shipment Address</p>
                        @if ($order->shipment != null)
                            <address>
                                {{ $order->shipment->first_name }} {{ $order->shipment->last_name }}
                                    {{ $order->shipment->address1 }}
                                    {{ $order->shipment->address2 }}
                                <br> Email: {{ $order->shipment->email }}
                                <br> Phone: {{ $order->shipment->phone }}
                                <br> Postcode: {{ $order->shipment->postcode }}
                            </address>
                        @else
                            <address>
                            <br> Ambil di Toko
                        </address>
                        @endif
                    </div>
                    <div class="col-lg-4">
                        <p class="text-dark mb-2" style="font-weight: normal; font-size:16px; text-transform: uppercase;">Details</p>
                        <address>
                            ID: <span class="text-dark">#{{ $order->code }}</span>
                            <br> {{ $order->order_date }}
                            <br> Status: {{ $order->status }} {{ $order->isCancelled() ? '('. $order->cancelled_at .')' : null}}
                            @if ($order->isCancelled())
                                <br> Cancellation Note : {{ $order->cancellation_note}}
                            @endif
                            <br> Payment Status: {{ $order->payment_status }}
                            <br> Payment Method: {{ $order->payment_method }}
                            <br> Shipped by: {{ $order->shipping_service_name }}
                        </address>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="data-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Item</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Unit Cost</th>
                                <th>Total</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                function showAttributes($jsonAttributes)
                                {
                                    $jsonAttr = (string) $jsonAttributes;
                                    $attributes = json_decode($jsonAttr, true);
                                    $showAttributes = '';
                                    if ($attributes) {
                                        $showAttributes .= '<ul class="item-attributes list-unstyled">';
                                        foreach ($attributes as $key => $attribute) {
                                            if(count($attribute) != 0){
                                                foreach($attribute as $value => $attr){
                                                    $showAttributes .= '<li>'.$value . ': <span>' . $attr . '</span><li>';
                                                }
                                            }else {
                                                $showAttributes .= '<li><span> - </span></li>';
                                            }
                                        }
                                        $showAttributes .= '</ul>';
                                    }
                                    return $showAttributes;
                                }
                            @endphp
                            @forelse ($order->orderItems as $item)
                                <tr>
                                    <td>{{ $item->sku }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{!! showAttributes($item->attributes) !!}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>Rp{{ number_format($item->base_price,0,",",".") }}</td>
                                    <td>Rp{{ number_format($item->sub_total,0,",",".") }}</td>
                                    <td>{{ $order->note }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">Order item not found!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="row ">
                        @if ($order->payment_method == 'manual' || $order->payment_method == 'qris')
                            <div class="col-lg-6 justify-content-start col-xl-4 col-xl-3 ml-sm-auto pb-4">
                                <h4>Payment Slip :</h4>
                                <br>
                                <img  src="{{ asset('/storage/' . $order->payment_slip ) }}" width="600" alt="">
                            </div>
                        @endif
                        <div class="col-lg-5 justify-content-end col-xl-4 col-xl-3 ml-sm-auto pb-4">
                            <ul class="list-unstyled mt-4">
                                <li class="mid pb-3 text-dark">Subtotal
                                    <span class="d-inline-block float-right text-default">Rp{{ number_format($order->base_total_price,0,",",".") }}</span>
                                </li>
                                <li class="mid pb-3 text-dark">Tax(10%)
                                    <span class="d-inline-block float-right text-default">Rp{{ number_format($order->tax_amount,0,",",".") }}</span>
                                </li>
                                <li class="mid pb-3 text-dark">Shipping Cost
                                    <span class="d-inline-block float-right text-default">Rp{{ number_format($order->shipping_cost,0,",",".") }}</span>
                                </li>
                                <li class="pb-3 text-dark">Unique Code
                                    <span class="d-inline-block float-right">Rp{{ number_format(0,0,",",".") }}</span>
                                </li>
                                <li class="pb-3 text-dark">Total
                                    <span class="d-inline-block float-right">Rp{{ number_format($order->grand_total,0,",",".") }}</span>
                                </li>
                            </ul>
                            @if (!$order->trashed())
                                    @if ($order->isPaid() && $order->isConfirmed() && $order->needsShipment())
                                        {{-- Orders that need shipment (online orders with courier delivery) --}}
                                        @if($order->shipment && $order->shipment->id)
                                            <a href="{{ url('admin/shipments/'. $order->shipment->id .'/edit')}}" class="btn btn-block mt-2 btn-lg btn-primary btn-pill"> Procced to Shipment</a>
                                        @else
                                            <a href="{{ route('admin.shipments.create') }}?order_id={{ $order->id }}" class="btn btn-block mt-2 btn-lg btn-primary btn-pill"> Create Shipment</a>
                                        @endif
                                    @elseif(!$order->isCancelled() && $order->isPaid() && $order->isConfirmed() && !$order->needsShipment() && !$order->isCompleted())
                                        {{-- Orders that don't need shipment --}}
                                        @if($order->shipping_service_name == 'Self Pickup')
                                            {{-- Self pickup orders need confirmation of pickup --}}
                                            @unless ($order->isCancelled())
                                                <a href="#" class="btn btn-block mt-2 btn-lg btn-warning btn-pill" onclick="event.preventDefault();
                                                document.getElementById('pickup-confirm-form-{{ $order->id }}').submit();">Customer Sudah Ambil Barang?</a>
                                                <form class="d-none" method="POST" action="{{ route('admin.orders.confirmPickup', $order) }}" id="pickup-confirm-form-{{ $order->id }}">
                                                    @csrf
                                                </form>
                                            @endunless
                                        @else
                                            {{-- Other orders (offline store, COD) --}}
                                            @unless ($order->isCancelled())
                                                <a href="#" class="btn btn-block mt-2 btn-lg btn-success btn-pill" onclick="event.preventDefault();
                                                document.getElementById('complete-form-{{ $order->id }}').submit();"> Mark as Completed</a>
                                                <form class="d-none" method="POST" action="{{ route('admin.orders.complete', $order) }}" id="complete-form-{{ $order->id }}">
                                                    @csrf
                                                </form>
                                            @endunless
                                        @endif
                                    @elseif(!$order->isCancelled() && $order->isPaid() && $order->isCompleted() && $order->shipping_service_name == 'Self Pickup')
                                        {{-- Self pickup orders that were auto-completed but need pickup confirmation --}}
                                        @if($order->shipment && $order->shipment->status == 'shipped' && $order->shipment->shipped_by)
                                            {{-- Pickup already confirmed - no button needed --}}
                                        @else
                                            {{-- Auto-completed order that still needs pickup confirmation --}}
                                            @unless ($order->isCancelled())
                                                <a href="#" class="btn btn-block mt-2 btn-lg btn-warning btn-pill" onclick="event.preventDefault();
                                                document.getElementById('pickup-confirm-form-{{ $order->id }}').submit();">Customer Sudah Ambil Barang?</a>
                                                <form class="d-none" method="POST" action="{{ route('admin.orders.confirmPickup', $order) }}" id="pickup-confirm-form-{{ $order->id }}">
                                                    @csrf
                                                </form>
                                            @endunless
                                        @endif
                                    @endif

                                    @unless ($order->isCancelled())
                                        @if ($order->isPaid() && !$order->isCancelled() && in_array($order->payment_method, ['manual', 'cod', 'qris', 'midtrans', 'toko', 'transfer']))
                                            <a href="{{ route('admin.orders.invoices', $order->id) }}" class="btn btn-block mt-2 btn-lg btn-primary btn-pill">Download Invoice</a>
                                        @endif
                                    @endunless

                                    @if ($order->payment_status == 'waiting' && $order->payment_method == 'qris' && !$order->isCancelled())
                                        <form action="{{ route('admin.orders.confirmAdmin', $order->id) }}" method="POST">
                                            @method('PUT')
                                            @csrf
                                            <button type="submit" class="btn btn-block mt-2 btn-lg btn-success btn-pill">Confirm QRIS Payment</button>
                                        </form>
                                    @elseif ($order->payment_status == 'waiting' && $order->payment_method == 'midtrans' && !$order->isCancelled())
                                        <form action="{{ route('admin.orders.confirmAdmin', $order->id) }}" method="POST">
                                            @method('PUT')
                                            @csrf
                                            <button type="submit" class="btn btn-block mt-2 btn-lg btn-success btn-pill">Confirm Midtrans Payment</button>
                                        </form>
                                    @elseif ($order->payment_status == 'waiting' && !$order->isCancelled() && $order->payment_method == 'manual')
                                        <form action="{{ route('admin.orders.confirmAdmin', $order->id) }}" method="POST">
                                            @method('PUT')
                                            @csrf
                                            <button type="submit" class="btn btn-block mt-2 btn-lg btn-success btn-pill">Confirm Manual Payment</button>
                                        </form>
                                    @elseif($order->payment_status == 'unpaid' && !$order->isCancelled() && $order->payment_method == 'manual')
                                        <form action="{{ route('admin.orders.confirmAdmin', $order->id) }}" method="POST">
                                            @method('PUT')
                                            @csrf
                                            <button type="submit" class="btn btn-block mt-2 btn-lg btn-success btn-pill">Confirm Payment</button>
                                        </form>
                                    @elseif($order->payment_status == 'unpaid' && !$order->isCancelled() && $order->payment_method == 'cod')
                                        <form action="{{ route('admin.orders.confirmAdmin', $order->id) }}" method="POST">
                                            @method('PUT')
                                            @csrf
                                            <button type="submit" class="btn btn-block mt-2 btn-lg btn-success btn-pill">Confirm COD Payment</button>
                                        </form>
                                    @elseif($order->payment_status == 'unpaid' && !$order->isCancelled() && $order->payment_method == 'qris')
                                        <div class="payment-section mb-3">
                                            <button id="pay-button-qris" class="btn btn-block mt-2 btn-lg btn-warning btn-pill">Process QRIS Payment</button>
                                        </div>
                                    @elseif($order->payment_status == 'unpaid' && !$order->isCancelled() && $order->payment_method == 'midtrans')
                                        <div class="payment-section mb-3">
                                            <button id="pay-button-midtrans" class="btn btn-block mt-2 btn-lg btn-warning btn-pill">Process Midtrans Payment</button>
                                        </div>
                                    @elseif($order->payment_status == 'unpaid' && !$order->isCancelled() && $order->payment_method == 'toko')
                                        <form action="{{ route('admin.orders.confirmAdmin', $order->id) }}" method="POST">
                                            @method('PUT')
                                            @csrf
                                            <button type="submit" class="btn btn-block mt-2 btn-lg btn-success btn-pill">Confirm Store Payment</button>
                                        </form>
                                    @elseif($order->payment_status == 'unpaid' && !$order->isCancelled() && $order->payment_method == 'transfer')
                                        <form action="{{ route('admin.orders.confirmAdmin', $order->id) }}" method="POST">
                                            @method('PUT')
                                            @csrf
                                            <button type="submit" class="btn btn-block mt-2 btn-lg btn-success btn-pill">Confirm Bank Transfer</button>
                                        </form>
                                    @endif
                                    @if ($order->isDelivered() && !$order->isCancelled())
                                        <a href="#" class="btn btn-block mt-2 btn-lg btn-success btn-pill" onclick="event.preventDefault();
                                        document.getElementById('complete-form-{{ $order->id }}').submit();"> Mark as Completed</a>
                                        <form class="d-none" method="POST" action="{{ route('admin.orders.complete', $order) }}" id="complete-form-{{ $order->id }}">
                                            @csrf
                                        </form>
                                    @endif

                                    @if (!in_array($order->status, [\App\Models\Order::DELIVERED, \App\Models\Order::COMPLETED]))
                                        <a href="#" class="btn btn-block mt-2 btn-lg btn-secondary btn-pill delete" order-id="{{ $order->id }}"> Remove</a>
                                        <form action="{{ route('admin.orders.destroy',$order) }}" method="post" id="delete-form-{{ $order->id }}" class="d-none">
                                            @csrf
                                            @method('delete')
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ url('admin/orders/restore/'. $order->id)}}" class="btn btn-block mt-2 btn-lg btn-outline-secondary btn-pill restore">Restore</a>
                                    <a href="#" class="btn btn-block mt-2 btn-lg btn-danger btn-pill delete" order-id="{{ $order->id }}"> Remove Permanently</a>
                                    <form action="{{ route('admin.orders.destroy',$order) }}" method="post" id="delete-form-{{ $order->id }}" class="d-none">
                                            @csrf
                                            @method('delete')
                                        </form>
                                @endif
                                <a href="{{ route('admin.orders.invoices', $order->id) }}" class="btn btn-primary mt-3">Cetak Invoice</a>
                            </div>
                        </div>
                        <div class="row">
                            @if ($order->attachments != null)
                                <div class="col-md-6 mb-5">
                                    <a class="btn btn-primary" href="{{ route('download-file', $order->id) }}">Download Attachments File</a>
                                </div>
                            @endif
                        </div>
                    </div>
              </div>
              {{--  <!-- /.card-body -->  --}}
            </div>
            {{--  <!-- /.card -->  --}}
          </div>
          {{--  <!-- /.col -->  --}}
        </div>
        {{--  <!-- /.row -->  --}}
      </div>
      {{--  <!-- /.container-fluid -->  --}}
    </section>
@endsection

@push('style-alt')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css">
@endpush

@push('script-alt')
    <!-- Load Midtrans Snap.js -->
    <script type="text/javascript" src="{{ $paymentData['snapUrl'] }}" data-client-key="{{ $paymentData['midtransClientKey'] }}"></script>
    
    <script
        src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU="
        crossorigin="anonymous"
    >
    </script>
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script>
    $(document).ready(function() {
        $("#data-table").DataTable();

        $(".delete").on("submit", function () {
            return confirm("Do you want to remove this?");
        });
        
        $("a.delete").on("click", function () {
            event.preventDefault();
            var orderId = $(this).attr('order-id');
            if (confirm("Do you want to remove this?")) {
                document.getElementById('delete-form-' + orderId ).submit();
            }
        });

        $(".restore").on("click", function () {
            return confirm("Do you want to restore this?");
        });

        @if($order->payment_method == 'qris' || $order->payment_method == 'midtrans')
            console.log('Payment data:', {
                snapUrl: '{{ $paymentData["snapUrl"] }}',
                clientKey: '{{ $paymentData["midtransClientKey"] }}',
                isProduction: {{ $paymentData['isProduction'] ? 'true' : 'false' }}
            });
            
            // Wait for Snap.js to load
            function waitForSnap(callback, timeout = 10000) {
                console.log('Waiting for Snap.js to load...');
                var startTime = Date.now();
                function checkSnap() {
                    console.log('Checking snap object, typeof snap:', typeof snap);
                    if (typeof snap !== 'undefined') {
                        console.log('Snap.js loaded successfully');
                        callback();
                    } else if (Date.now() - startTime > timeout) {
                        console.error('Snap.js failed to load within timeout');
                        alert('Payment system failed to load. Please refresh the page and try again.');
                        return;
                    } else {
                        setTimeout(checkSnap, 200);
                    }
                }
                // Wait a bit for script to load
                setTimeout(checkSnap, 500);
            }
            
            // Payment button click handler
            $('#pay-button-qris, #pay-button-midtrans').on('click', function(e) {
                e.preventDefault();
                console.log('Payment button clicked');
                
                var button = $(this);
                var originalText = button.attr('id') === 'pay-button-qris' ? 'Process QRIS Payment' : 'Process Midtrans Payment';
                button.prop('disabled', true).text('Processing...');
                
                waitForSnap(function() {
                    @if(!empty($order->payment_token))
                        console.log('Using existing token: {{ $order->payment_token }}');
                        // Use existing token
                        try {
                            snap.pay('{{ $order->payment_token }}', {
                                onSuccess: function(result) {
                                    console.log('Payment success:', result);
                                    window.location.href = '{{ route("admin.payment.finish") }}?order_id={{ $order->code }}';
                                },
                                onPending: function(result) {
                                    console.log('Payment pending:', result);
                                    window.location.href = '{{ route("admin.payment.unfinish") }}?order_id={{ $order->code }}';
                                },
                                onError: function(result) {
                                    console.log('Payment error:', result);
                                    window.location.href = '{{ route("admin.payment.error") }}?order_id={{ $order->code }}';
                                },
                                onClose: function() {
                                    console.log('Payment window closed');
                                    button.prop('disabled', false).text(originalText);
                                }
                            });
                        } catch (error) {
                            console.error('Error calling snap.pay:', error);
                            alert('Error opening payment. Please try again.');
                            button.prop('disabled', false).text(originalText);
                        }
                    @else
                        console.log('Generating new payment token...');
                        // Generate new token
                        $.ajax({
                            url: '{{ route("admin.orders.generate-payment-token", $order->id) }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            timeout: 30000, // 30 second timeout
                            success: function(response) {
                                console.log('Token generation response:', response);
                                if (response.success && response.payment_token) {
                                    try {
                                        snap.pay(response.payment_token, {
                                            onSuccess: function(result) {
                                                console.log('Payment success:', result);
                                                window.location.href = '{{ route("admin.payment.finish") }}?order_id={{ $order->code }}';
                                            },
                                            onPending: function(result) {
                                                console.log('Payment pending:', result);
                                                window.location.href = '{{ route("admin.payment.unfinish") }}?order_id={{ $order->code }}';
                                            },
                                            onError: function(result) {
                                                console.log('Payment error:', result);
                                                window.location.href = '{{ route("admin.payment.error") }}?order_id={{ $order->code }}';
                                            },
                                            onClose: function() {
                                                console.log('Payment window closed');
                                                button.prop('disabled', false).text(originalText);
                                            }
                                        });
                                    } catch (error) {
                                        console.error('Error calling snap.pay with new token:', error);
                                        alert('Error opening payment. Please try again.');
                                        button.prop('disabled', false).text(originalText);
                                    }
                                } else {
                                    console.error('Token generation failed:', response);
                                    alert('Failed to generate payment token: ' + (response.message || 'Unknown error'));
                                    button.prop('disabled', false).text(originalText);
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('AJAX Error:', {xhr: xhr, status: status, error: error});
                                var errorMessage = 'Error generating payment token. ';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage += xhr.responseJSON.message;
                                } else if (status === 'timeout') {
                                    errorMessage += 'Request timed out. Please try again.';
                                } else {
                                    errorMessage += 'Please try again.';
                                }
                                alert(errorMessage);
                                button.prop('disabled', false).text(originalText);
                            }
                        });
                    @endif
                }, 10000); // 10 second timeout for snap loading
            });
        @endif
    });
    </script>
@endpush
