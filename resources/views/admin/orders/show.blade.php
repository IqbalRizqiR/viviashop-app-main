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
                            @if($order->handled_by)
                                <br> Handled by: {{ $order->handled_by }}
                            @endif
                        </address>
                    </div>
                </div>
                
                <!-- Employee Tracking Section -->
                <div class="row pt-3 mb-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Employee Performance Tracking</h5>
                            </div>
                            <div class="card-body">
                                <!-- Checkbox to enable/disable tracking -->
                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="useEmployeeTracking" 
                                           {{ $order->use_employee_tracking ? 'checked' : '' }}>
                                    <label class="form-check-label" for="useEmployeeTracking">
                                        Gunakan Employee Tracking (untuk bonus & performance)
                                    </label>
                                </div>
                                
                                <!-- Employee name input (conditional) -->
                                <div id="employeeNameSection" style="display: {{ $order->use_employee_tracking ? 'block' : 'none' }}">
                                    <div class="form-group">
                                        <label for="employeeName">Nama Karyawan/Admin yang Handle:</label>
                                        
                                        <!-- Dropdown for existing employees -->
                                        <select id="employeeDropdown" class="form-control mb-2">
                                            <option value="">-- Pilih Karyawan Terdaftar --</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee }}" {{ $order->handled_by == $employee ? 'selected' : '' }}>
                                                    {{ $employee }}
                                                </option>
                                            @endforeach
                                        </select>
                                        
                                        <!-- Manual input for new employees -->
                                        <div class="mt-2">
                                            <label for="employeeName" class="small text-muted">Atau tambah karyawan baru:</label>
                                            <input type="text" id="employeeName" class="form-control" 
                                                   value="{{ !in_array($order->handled_by, $employees->toArray()) ? $order->handled_by : '' }}" 
                                                   placeholder="Masukkan nama karyawan baru">
                                        </div>
                                        
                                        <small class="text-danger" id="employeeNameError" style="display: none;">
                                            Nama karyawan harus diisi untuk menyelesaikan transaksi
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                            if(is_array($attribute) && count($attribute) != 0){
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
                                    <span class="d-inline-block float-right text-default">
                                        <span id="shipping-cost-display">Rp{{ number_format($order->shipping_cost,0,",",".") }}</span>
                                        @if($order->isShippingCostAdjusted())
                                            <small class="text-info d-block">
                                                <i class="fa fa-edit"></i> Adjusted 
                                                @if($order->hasOriginalShippingData())
                                                    (Original: Rp{{ number_format($order->original_shipping_cost,0,",",".") }})
                                                @endif
                                            </small>
                                        @endif
                                    </span>
                                </li>
                                @if($order->needsShipment() && $order->shipping_courier)
                                <li class="mid pb-3 text-dark">Shipped by
                                    <span class="d-inline-block float-right text-default">
                                        <span id="shipping-courier-display">{{ strtoupper($order->shipping_courier) }} - {{ $order->shipping_service_name }}</span>
                                        @if($order->isShippingCostAdjusted() && $order->hasOriginalShippingData())
                                            <small class="text-info d-block">
                                                (Original: {{ strtoupper($order->original_shipping_courier) }} - {{ $order->original_shipping_service_name }})
                                            </small>
                                        @endif
                                    </span>
                                </li>
                                @endif
                                <li class="pb-3 text-dark">Unique Code
                                    <span class="d-inline-block float-right">Rp{{ number_format(0,0,",",".") }}</span>
                                </li>
                                <li class="pb-3 text-dark">Total
                                    <span class="d-inline-block float-right">Rp{{ number_format($order->grand_total,0,",",".") }}</span>
                                </li>
                            </ul>
                            
                            @if($order->needsShipment() && !$order->isCancelled() && $order->isPaid())
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <input type="checkbox" id="shipping-adjustment-toggle" class="mr-2">
                                        Adjust Shipping Cost & Courier
                                    </h6>
                                </div>
                                <div class="card-body" id="shipping-adjustment-form" style="display: none;">
                                    <form id="shipping-adjustment-form-data">
                                        @csrf
                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="new_shipping_cost">New Shipping Cost</label>
                                                    <input type="number" class="form-control" id="new_shipping_cost" name="new_shipping_cost" 
                                                           value="{{ $order->shipping_cost }}" min="0" step="100">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="new_shipping_courier">Courier</label>
                                                    <select class="form-control" id="new_shipping_courier" name="new_shipping_courier">
                                                        <option value="jne" {{ $order->shipping_courier == 'jne' ? 'selected' : '' }}>JNE</option>
                                                        <option value="tiki" {{ $order->shipping_courier == 'tiki' ? 'selected' : '' }}>TIKI</option>
                                                        <option value="pos" {{ $order->shipping_courier == 'pos' ? 'selected' : '' }}>POS Indonesia</option>
                                                        <option value="sicepat" {{ $order->shipping_courier == 'sicepat' ? 'selected' : '' }}>SiCepat</option>
                                                        <option value="jnt" {{ $order->shipping_courier == 'jnt' ? 'selected' : '' }}>J&T Express</option>
                                                        <option value="anteraja" {{ $order->shipping_courier == 'anteraja' ? 'selected' : '' }}>AnterAja</option>
                                                        <option value="spx" {{ $order->shipping_courier == 'spx' ? 'selected' : '' }}>Shopee Express</option>
                                                        <option value="other" {{ !in_array($order->shipping_courier, ['jne','tiki','pos','sicepat','jnt','anteraja','spx']) ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="new_shipping_service">Service Name</label>
                                            <input type="text" class="form-control" id="new_shipping_service" name="new_shipping_service" 
                                                   value="{{ $order->shipping_service_name }}" placeholder="e.g., REG, YES, Express">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="adjustment_note">Adjustment Note</label>
                                            <textarea class="form-control" id="adjustment_note" name="adjustment_note" rows="2" 
                                                      placeholder="Reason for adjustment (e.g., Field rate different from API)">{{ $order->shipping_adjustment_note }}</textarea>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Current Total</label>
                                                    <input type="text" class="form-control" id="current-total" 
                                                           value="Rp{{ number_format($order->grand_total,0,",",".") }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>New Total</label>
                                                    <input type="text" class="form-control" id="new-total" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <button type="button" class="btn btn-warning btn-block" id="update-shipping-btn">
                                            <i class="fa fa-sync"></i> Update Shipping Information
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endif
                            
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
        
        // Employee tracking functionality
        $('#useEmployeeTracking').change(function() {
            const isChecked = $(this).is(':checked');
            $('#employeeNameSection').toggle(isChecked);
            
            // If disabled, clear both inputs
            if (!isChecked) {
                $('#employeeDropdown').val('');
                $('#employeeName').val('');
                updateEmployeeName('');
            }
            
            // AJAX call to update order tracking status
            updateTrackingStatus(isChecked);
        });

        // Handle dropdown selection
        $('#employeeDropdown').change(function() {
            const selectedEmployee = $(this).val();
            if (selectedEmployee) {
                $('#employeeName').val('');
                updateEmployeeName(selectedEmployee);
            }
        });

        // Handle manual input
        $('#employeeName').on('input', function() {
            const manualInput = $(this).val().trim();
            if (manualInput) {
                $('#employeeDropdown').val('');
            }
        });

        // Validate before order completion
        function validateEmployeeTracking() {
            const useTracking = $('#useEmployeeTracking').is(':checked');
            const dropdownValue = $('#employeeDropdown').val().trim();
            const manualValue = $('#employeeName').val().trim();
            const employeeName = dropdownValue || manualValue;
            
            if (useTracking && !employeeName) {
                $('#employeeNameError').show();
                return false;
            }
            
            $('#employeeNameError').hide();
            return true;
        }

        // Update employee name on blur
        $('#employeeName').on('blur', function() {
            const manualValue = $(this).val().trim();
            const dropdownValue = $('#employeeDropdown').val().trim();
            const finalName = dropdownValue || manualValue;
            
            if (manualValue && !dropdownValue) {
                updateEmployeeName(manualValue);
            }
        });

        // Update completion forms to validate employee tracking
        $('form[action*="complete"], form[action*="confirmPickup"]').on('submit', function(e) {
            if (!validateEmployeeTracking()) {
                e.preventDefault();
                return false;
            }
            
            // Ensure the final employee name is set before submission
            const dropdownValue = $('#employeeDropdown').val().trim();
            const manualValue = $('#employeeName').val().trim();
            const finalName = dropdownValue || manualValue;
            
            if (finalName && $('#useEmployeeTracking').is(':checked')) {
                updateEmployeeName(finalName);
            }
        });

        // AJAX functions
        function updateTrackingStatus(enabled) {
            $.ajax({
                url: '{{ route("admin.orders.toggleEmployeeTracking", $order->id) }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    use_employee_tracking: enabled
                },
                success: function(response) {
                    console.log('Tracking status updated');
                },
                error: function(xhr, status, error) {
                    console.error('Error updating tracking status:', error);
                }
            });
        }

        function updateEmployeeName(name) {
            $.ajax({
                url: '{{ route("admin.orders.updateEmployeeTracking", $order->id) }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    handled_by: name
                },
                success: function(response) {
                    console.log('Employee name updated');
                    
                    // Auto-sync checkbox based on employee name
                    const hasName = name.trim() !== '';
                    const checkbox = $('#useEmployeeTracking');
                    const nameSection = $('#employeeNameSection');
                    
                    if (response.use_employee_tracking !== checkbox.is(':checked')) {
                        checkbox.prop('checked', response.use_employee_tracking);
                        nameSection.toggle(response.use_employee_tracking);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error updating employee name:', error);
                }
            });
        }
        
        // Shipping cost adjustment functionality
        $('#shipping-adjustment-toggle').change(function() {
            const isChecked = $(this).is(':checked');
            $('#shipping-adjustment-form').toggle(isChecked);
            
            if (isChecked) {
                calculateNewTotal();
            }
        });
        
        // Calculate new total when shipping cost changes
        $('#new_shipping_cost').on('input', function() {
            calculateNewTotal();
        });
        
        function calculateNewTotal() {
            const currentShippingCost = {{ $order->shipping_cost }};
            const baseTotal = {{ $order->grand_total }} - currentShippingCost;
            const newShippingCost = parseFloat($('#new_shipping_cost').val()) || 0;
            const newTotal = baseTotal + newShippingCost;
            
            $('#new-total').val('Rp' + number_format(newTotal, 0, ',', '.'));
        }
        
        function number_format(number, decimals, decPoint, thousandsSep) {
            decimals = decimals || 0;
            number = parseFloat(number);
            
            if (!decPoint || !thousandsSep) {
                decPoint = '.';
                thousandsSep = ',';
            }
            
            var roundedNumber = Math.round(Math.abs(number) * ('1e' + decimals)) + '';
            var numbersString = decimals ? (roundedNumber.slice(0, decimals * -1) || 0) : roundedNumber;
            var decimalsString = decimals ? roundedNumber.slice(decimals * -1) : '';
            var formattedNumber = '';
            
            while (numbersString.length > 3) {
                formattedNumber = thousandsSep + numbersString.slice(-3) + formattedNumber;
                numbersString = numbersString.slice(0, -3);
            }
            
            if (numbersString) {
                formattedNumber = numbersString + formattedNumber;
            }
            
            formattedNumber = decimals ? formattedNumber + decPoint + decimalsString : formattedNumber;
            
            return (number < 0 ? '-' : '') + formattedNumber;
        }
        
        // Handle shipping adjustment form submission
        $('#update-shipping-btn').click(function() {
            const button = $(this);
            const originalText = button.html();
            
            // Basic validation
            const newCost = parseFloat($('#new_shipping_cost').val());
            const newCourier = $('#new_shipping_courier').val();
            const newService = $('#new_shipping_service').val().trim();
            
            if (isNaN(newCost) || newCost < 0) {
                alert('Please enter a valid shipping cost');
                return;
            }
            
            if (!newCourier || !newService) {
                alert('Please fill in courier and service name');
                return;
            }
            
            button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');
            
            const formData = {
                order_id: {{ $order->id }},
                new_shipping_cost: newCost,
                new_shipping_courier: newCourier,
                new_shipping_service: newService,
                adjustment_note: $('#adjustment_note').val().trim(),
                _token: '{{ csrf_token() }}'
            };
            
            $.ajax({
                url: '{{ route("admin.orders.adjustShipping") }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        // Update display values
                        $('#shipping-cost-display').text('Rp' + number_format(response.new_shipping_cost, 0, ',', '.'));
                        $('#shipping-courier-display').text(response.new_courier.toUpperCase() + ' - ' + response.new_service);
                        $('#current-total').val('Rp' + number_format(response.new_grand_total, 0, ',', '.'));
                        
                        // Show success message
                        alert('Shipping cost updated successfully');
                        
                        // Refresh page to show all changes
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        alert('Error: ' + (response.message || 'Failed to update shipping cost'));
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', xhr.responseText);
                    let errorMessage = 'Failed to update shipping cost';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        try {
                            const errorResponse = JSON.parse(xhr.responseText);
                            errorMessage = errorResponse.message || errorMessage;
                        } catch (e) {
                            errorMessage = xhr.responseText;
                        }
                    }
                    
                    alert(errorMessage);
                },
                complete: function() {
                    button.prop('disabled', false).html(originalText);
                }
            });
        });
    });
    </script>
@endpush
