@extends('frontend.layouts')

@push('styles')
<style>
	:root{
		--brand-green: #28a745;
		--brand-dark: #0b533f;
		--muted: #6c757d;
		--surface: #ffffff;
		--bg-soft: #fbfff9;
		--radius: 12px;
		--shadow-sm: 0 8px 24px rgba(16,24,32,0.06);
	}

	/* Preserve print helpers (no functional change) */
	@media print {
		.btn, .breadcrumb-area, .cart-heading, nav, footer { display: none !important; }
		body * { visibility: hidden; }
		.cart-main-area, .cart-main-area * { visibility: visible; }
		.cart-main-area { position: absolute; left: 0; top: 0; width: 100%; }
		.no-print { display: none !important; }
	}

	/* Breadcrumb / Hero */
	.breadcrumb-area { margin-top: 3.2rem; padding: 2.2rem 0; background: linear-gradient(180deg, rgba(40,167,69,0.06), rgba(255,255,255,0)); border-bottom:1px solid rgba(40,167,69,0.08); }
	.breadcrumb-content h2 { color:var(--brand-dark); font-weight:900; font-size:30px; margin-bottom:6px; letter-spacing:0.2px; }
	.breadcrumb-content p { color:var(--muted); font-size:14px; margin:0; }

	/* Page heading */
	.cart-heading { font-size:18px; font-weight:800; margin-bottom:.6rem; color:#0f1720; }

	/* Cards & product rows */
	.card { border-radius:var(--radius); box-shadow:var(--shadow-sm); border: 1px solid rgba(11,83,63,0.06); background: linear-gradient(180deg, #ffffff, #fbfff9 60%); }
	.card-header { background:transparent; border-bottom:0; font-weight:800; color:var(--brand-dark); padding:1rem 1rem; font-size:15px; }
	.card-body { padding:1.15rem; }

	.card.mb-3 { margin-bottom:1rem; }

	/* Product item styling (kept layout same) */
	.card .muted { color:var(--muted); }
	.card .text-right { text-align:right; }
	.card .text-right .muted { font-size:13px; }
	.card .product-qty { font-weight:800; color:var(--brand-dark); }

	/* Make product rows feel lighter */
	.card .d-flex.align-items-center { gap: .75rem; }

	/* Summary card specifics */
	.summary-card .card-body { display:flex; flex-direction:column; gap:.6rem; }
	.summary-card .list-unstyled li { padding:.55rem 0; border-bottom:1px dashed rgba(11,83,63,0.05); }
	.summary-card .list-unstyled li:last-child { border-bottom:0; }

	.summary-card hr { margin: .6rem 0; border-color: rgba(11,83,63,0.06); }

	/* Buttons styling (purely presentational) */
	.btn { border-radius:10px; font-weight:800; letter-spacing:0.2px; }
	.btn-success { background:var(--brand-green); border-color:var(--brand-dark); color:#fff; box-shadow: 0 6px 18px rgba(40,167,69,0.12); }
	.btn-success:hover, .btn-success:focus { background: #23913a; border-color: #1b6f2e; }
	.btn-primary { background:#0b5e46; border-color:#094a39; color:#fff; }
	.btn-secondary { background:#f3f4f6; color:#222; border-color:transparent; }

	.btn-block { display:block; width:100%; }

	/* Badges */
	.badge-success { background: linear-gradient(90deg, rgba(40,167,69,0.14), rgba(40,167,69,0.02)); color:var(--brand-dark); font-weight:800; padding:.45rem .65rem; border-radius:999px; }

	/* Attachment preview for manual payments (UI only) */
	.attachment-card { display:flex; gap:.75rem; align-items:center; padding:.8rem; border-radius:8px; background: rgba(12,89,63,0.03); border:1px solid rgba(11,83,63,0.04); }
	.attachment-thumb { width:84px; height:64px; background:#fff; border-radius:6px; overflow:hidden; display:flex; align-items:center; justify-content:center; box-shadow: 0 4px 12px rgba(11,83,63,0.04); }
	.attachment-thumb img { max-width:100%; max-height:100%; display:block; }
	.attachment-meta { flex:1; }
	.attachment-meta .title { font-weight:800; color:var(--brand-dark); margin-bottom:4px; }
	.attachment-meta .hint { color:var(--muted); font-size:13px; }
	.attachment-actions a { text-decoration:none; }

	/* Responsive tweaks */
	@media (max-width: 991px) {
		.breadcrumb-area { margin-top: 2.2rem; padding:1rem 0; }
		.card-body { padding:.9rem; }
		.summary-card { position:relative; top:auto; }
		.attachment-card { flex-direction:row; }
	}

	/* Small utilities kept for compatibility */
	.muted { color:var(--muted); }
	.badge-small { font-size:12px; padding:4px 8px; border-radius:999px; background:rgba(40,167,69,0.09); color:var(--brand-dark); font-weight:700; }

</style>
@endpush

@section('content')
	<!-- header end -->
	<div class="breadcrumb-area pt-205 breadcrumb-padding pb-210">
		<div class="container-fluid">
			<div class="breadcrumb-content text-center">
				<h2>Order Received</h2>
				<p class="muted">Thank you — your order has been recorded. Below are the details of your purchase.</p>
			</div>
		</div>
	</div>
	<!-- checkout-area start -->
	<div class="cart-main-area  ptb-100">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="row pt-4">
						<div class="col-xl-4 col-lg-4">
							<p class="text-dark mb-2" style="font-weight: normal; font-size:16px; text-transform: uppercase;">Billing Address</p>
							<address>
								{{ $order->customer_first_name }} {{ $order->customer_last_name }}
								<br> {{ $order->customer_address1 }}
								<br> {{ $order->customer_address2 }}
								<br> Email: {{ $order->customer_email }}
								<br> Phone: {{ $order->customer_phone }}
								<br> Postcode: {{ $order->customer_postcode }}
							</address>
						</div>
						@if ($order->shipment != null)
							<div class="col-xl-4 col-lg-4">
								<p class="text-dark mb-2" style="font-weight: normal; font-size:16px; text-transform: uppercase;">Shipment Address</p>
								<address>
									{{ $order->shipment->first_name }} {{ $order->shipment->last_name }}
									<br> {{ $order->shipment->address1 }}
									<br> {{ $order->shipment->address2 }}
									<br> Email: {{ $order->shipment->email }}
									<br> Phone: {{ $order->shipment->phone }}
									<br> Postcode: {{ $order->shipment->postcode }}
								</address>
							</div>
						@endif
						<div class="col-xl-4 col-lg-4">
							<p class="text-dark mb-2" style="font-weight: normal; font-size:16px; text-transform: uppercase;">Details</p>
							<address>
								ID: <span class="text-dark">#{{ $order->code }}</span>
								<br> {{ date('d M Y', strtotime($order->order_date)) }}
								<br> Status: {{ $order->status }} {{ $order->isCancelled() ? '('. date('d M Y', strtotime($order->cancelled_at)) .')' : null}}
								<br> Payment Status: {{ $order->payment_status }}
								<br> Shipped by: {{ $order->shipping_courier }} - {{ $order->shipping_service_name }}
								@if ($order->isShippingCostAdjusted())
									<br> <span class="text-info">Shipping Cost: Rp{{ number_format($order->shipping_cost, 0, ",", ".") }}</span>
									<small class="text-muted d-block">Original Cost: Rp{{ number_format($order->original_shipping_cost, 0, ",", ".") }}</small>
								@else
									<br> Shipping Cost: Rp{{ number_format($order->shipping_cost, 0, ",", ".") }}
								@endif
								@php
									$resi = \App\Models\Shipment::where('order_id', $order->id)->pluck('track_number')->first();
								@endphp
								<br> Tracking Number: {{ $resi }}
								@if($order->handled_by)
									<br> Handled by: {{ $order->handled_by }}
								@endif
							</address>
						</div>
					</div>
                @if(session()->has('message'))
                    <div class="content-header mb-0 pb-0">
                        <div class="container-fluid">
                            <div class="mb-0 alert alert-{{ session()->get('alert-type') }} alert-dismissible fade show" role="alert">
                                <strong>{{ session()->get('message') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div><!-- /.container-fluid -->
                    </div>
                @endif
					<h1 class="cart-heading">Your Order:</h1>
					{{-- Order header and layout similar to professional e-commerce --}}
					<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
						<div>
							<h2 class="mb-0">Order #{{ $order->code }}</h2>
							<div class="muted">{{ date('d M Y H:i', strtotime($order->order_date)) }}</div>
						</div>
						<div class="d-none d-md-block">
							<div class="badge badge-success">{{ $order->payment_status == 'paid' ? 'Paid' : ucfirst($order->payment_status) }}</div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-8">
							{{-- Product list using existing card styles --}}
							@forelse ($order->orderItems as $item)
								<div class="card mb-3">
									<div class="card-body d-flex align-items-center p-3">
										<div style="width:84px;height:84px;flex-shrink:0;display:flex;align-items:center;justify-content:center;background:#f7fbf7;border-radius:6px;overflow:hidden;margin-right:1rem;">
											@if(isset($item->product) && $item->product->image)
												<img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->name }}" style="max-width:100%;max-height:100%;">
											@else
												<img src="{{ asset('images/no-image.png') }}" alt="no image" style="max-width:100%;max-height:100%;">
											@endif
										</div>
										<div class="flex-fill">
											<div class="d-flex justify-content-between align-items-start">
												<div>
													<div style="font-weight:700">{{ $item->name }}</div>
													<div class="muted" style="font-size:13px;">SKU: {{ $item->sku }}</div>
													<div class="muted" style="font-size:13px;margin-top:.35rem;">{!! showAttributes($item->attributes) !!}</div>
												</div>
												<div class="text-right">
													<div class="muted">{{ $item->qty }} ×</div>
													<div style="font-weight:800">Rp{{ number_format($item->sub_total,0,',','.') }}</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							@empty
								<div class="card"><div class="card-body">Order item not found!</div></div>
							@endforelse

							{{-- Shipping & Payment card using existing card classes --}}
							<div class="card">
								<div class="card-header">Shipping & Payment</div>
								<div class="card-body">
									<p class="mb-1"><strong>Shipped by</strong></p>
									<p class="muted">{{ $order->shipping_courier }} - {{ $order->shipping_service_name }}</p>
									<p class="mb-1"><strong>Tracking</strong></p>
									<p class="muted">{{ $order->tracking_number ?? '—' }}</p>
									<p class="mb-1"><strong>Payment Method</strong></p>
									<p class="muted">{{ ucfirst($order->payment_method) }} @if($order->isPaid()) · <span style="color:var(--brand-dark);font-weight:700;">PAID</span>@endif</p>

									{{-- Attachment preview (UI only) --}}
									@if(!empty($order->payment_attachment))
										<div class="mt-3 attachment-card">
											<div class="attachment-thumb">
												{{-- Show image thumbnail if image, else show file icon --}}
												@if( preg_match('/\.(jpg|jpeg|png|gif)$/i', $order->payment_attachment) )
													<img src="{{ asset('storage/' . $order->payment_attachment) }}" alt="Bukti Pembayaran">
												@else
													<img src="{{ asset('images/file-icon.png') }}" alt="file">
												@endif
											</div>
											<div class="attachment-meta">
												<div class="title">Bukti Pembayaran</div>
												<div class="hint">Diterima pada {{ date('d M Y H:i', strtotime($order->updated_at ?? $order->order_date)) }}</div>
											</div>
											<div class="attachment-actions">
												<a class="btn btn-success btn-sm" href="{{ asset('storage/' . $order->payment_attachment) }}" target="_blank" rel="noopener">Lihat</a>
												<a class="btn btn-secondary btn-sm ml-2" href="{{ asset('storage/' . $order->payment_attachment) }}" download>Unduh</a>
											</div>
										</div>
									@endif
								</div>
							</div>
						</div>

						<div class="col-lg-4">
							<div class="card summary-card">
								<div class="card-body">
									<h5 class="card-title">Order Summary</h5>
									<ul class="list-unstyled mt-3 mb-0">
										<li class="d-flex justify-content-between"><span class="muted">Subtotal</span><strong>Rp{{ number_format($order->base_total_price,0,',','.') }}</strong></li>
										<li class="d-flex justify-content-between"><span class="muted">Tax (10%)</span><strong>Rp{{ number_format($order->tax_amount,0,',','.') }}</strong></li>
										<li class="d-flex justify-content-between"><span class="muted">Shipping</span><strong>Rp{{ number_format($order->shipping_cost,0,',','.') }}</strong></li>
										<li class="d-flex justify-content-between"><span class="muted">Unique Code</span><strong>Rp{{ number_format(0,0,',','.') }}</strong></li>
									</ul>
									<hr>
									<div class="d-flex justify-content-between align-items-center"><strong>Grand Total</strong><h4 class="mb-0">Rp{{ number_format($order->grand_total,0,',','.') }}</h4></div>

									<div class="mt-3">
										@if (!$order->isPaid() && $order->payment_method == 'automatic')
											<button class="btn btn-success btn-block d-none no-print" id="pay-button">Proceed to payment</button>
										@elseif(!$order->isPaid() && $order->payment_method == 'manual')
											<a class="btn btn-success btn-block no-print" href="{{ route('orders.confirmation_payment', $order->id) }}">Proceed to payment</a>
										@elseif(!$order->isPaid() && in_array($order->payment_method,['cod','toko']))
											<div class="muted">Silahkan lakukan pembayaran ke toko.</div>
											<a href="{{ route('orders.index') }}" class="btn btn-primary btn-block no-print mt-2">Kembali</a>
										@endif

										@if($order->isPaid())
											<a href="{{ route('orders.invoice', $order->id) }}" class="btn btn-primary btn-block mt-2">Download Invoice</a>
											<button onclick="window.print()" class="btn btn-secondary btn-block mt-2">Print Page</button>
										@endif
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="table-content table-responsive">
						<table class="table mt-3 table-striped table-responsive table-responsive-large" style="width:100%">
							<thead>
								<tr>
									<th>#</th>
									<th>Item</th>
									<th>Description</th>
									<th>Quantity</th>
									<th>Unit Cost</th>
									<th>Total</th>
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
									</tr>
								@empty
									<tr>
										<td colspan="6">Order item not found!</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>
					<div class="row">
						<div class="col-md-5 ml-auto">
							<div class="cart-page-total">
								<ul>
									<li> Subtotal
										<span>Rp{{ number_format($order->base_total_price, 0 ,",", ".") }}</span>
									</li>
									<li>Tax (10%)
										<span>Rp{{ number_format($order->tax_amount,0,",",".") }}</span>
									</li>
									<li>
										@if ($order->isShippingCostAdjusted())
											Shipping Cost <small class="text-info">(Adjusted)</small>
										@else
											Shipping Cost
										@endif
										<span>Rp{{ number_format($order->shipping_cost,0,",",".") }}</span>
									</li>
									<li>Unique Code
										<span>Rp{{ number_format(0,0,",",".") }}</span>
									</li>
									<li>Total
										<span>Rp{{ number_format($order->grand_total, 0,",", ".") }}</span>
									</li>
								</ul>
								@if (!$order->isPaid() && $order->payment_method == 'automatic')
									<button class="btn btn-success mt-3 d-none no-print" id="pay-button">Proceed to payment</button>
								@elseif(!$order->isPaid() && $order->payment_method == 'manual')
									<a class="btn btn-success mt-3 no-print" href="{{ route('orders.confirmation_payment', $order->id) }}">Proceed to payment</a>
								@elseif(!$order->isPaid() && $order->payment_method == 'cod')
									<h1 class="text-center no-print">Silahkan Lakukan Pembayaran ke Toko</h1>
									<a href="{{ route('orders.index') }}" class="btn btn-primary no-print">Kembali</a>
								@elseif(!$order->isPaid() && $order->payment_method == 'toko')
									<h1 class="text-center no-print">Silahkan Lakukan Pembayaran ke Toko</h1>
									<a href="{{ route('orders.index') }}" class="btn btn-primary no-print">Kembali</a>
								@endif
								
								@if($order->isPaid())
									<div class="mt-3 no-print">
										<a href="{{ route('orders.invoice', $order->id) }}" class="btn btn-primary btn-lg">
											<i class="fa fa-download"></i> Download Invoice
										</a>
										<button onclick="window.print()" class="btn btn-secondary btn-lg ml-2">
											<i class="fa fa-print"></i> Print Page
										</button>
									</div>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('script-alt')
	@if($order->payment_method == 'automatic' && $order->payment_status == 'unpaid' && !empty($order->payment_token))
		<!-- Load Midtrans JS library -->
		<script type="text/javascript" src="{{ $paymentData['snapUrl'] }}"
				data-client-key="{{ $paymentData['midtransClientKey'] }}"></script>

		<script type="text/javascript">
			document.addEventListener('DOMContentLoaded', function() {
				// Show payment button
				const payButton = document.getElementById('pay-button');
				if (payButton) {
					payButton.classList.remove('d-none');

					// Handle button click to open Snap payment page
					payButton.addEventListener('click', function() {
						snap.pay('{{ $order->payment_token }}', {
							onSuccess: function(result) {
								console.log('Payment success:', result);
								window.location.href = '{{ route('payment.finish') }}?order_id={{ $order->code }}';
							},
							onPending: function(result) {
								console.log('Payment pending:', result);
								window.location.href = '{{ route('payment.unfinish') }}?order_id={{ $order->code }}';
							},
							onError: function(result) {
								console.log('Payment error:', result);
								window.location.href = '{{ route('payment.error') }}?order_id={{ $order->code }}';
							},
							onClose: function() {
								console.log('Customer closed the payment window');
							}
						});
					});
				}
				
				// Auto-refresh payment status if unpaid
				@if(!$order->isPaid())
				function checkPaymentStatus() {
					fetch('{{ route('orders.status', $order->id) }}')
						.then(response => response.json())
						.then(data => {
							if (data.payment_status === 'paid') {
								location.reload();
							}
						})
						.catch(error => console.log('Status check error:', error));
				}
				
				// Check payment status every 10 seconds
				setInterval(checkPaymentStatus, 10000);
				@endif
			});
		</script>
	@endif
@endpush
