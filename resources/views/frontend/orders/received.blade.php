@extends('frontend.layouts')

@push('styles')
<style>
	@media print {
		.btn, .breadcrumb-area, .cart-heading, nav, footer {
			display: none !important;
		}
		
		body * {
			visibility: hidden;
		}
		
		.cart-main-area, .cart-main-area * {
			visibility: visible;
		}
		
		.cart-main-area {
			position: absolute;
			left: 0;
			top: 0;
			width: 100%;
		}
		
		.no-print {
			display: none !important;
		}
	}
</style>
@endpush

@section('content')
	<!-- header end -->
	<div class="breadcrumb-area pt-205 breadcrumb-padding pb-210" style="margin-top: 12rem;">
		<div class="container">
			<div class="breadcrumb-content text-center">
				<h2>Order Received</h2>
		</div>
	</div>
	<!-- checkout-area start -->
	<div class="cart-main-area  ptb-100">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
					<h1 class="cart-heading">Your Order:</h4>
					<div class="row">
						<div class="col-xl-3 col-lg-4">
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
						<div class="col-xl-3 col-lg-4">
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
						<div class="col-xl-3 col-lg-4">
							<p class="text-dark mb-2" style="font-weight: normal; font-size:16px; text-transform: uppercase;">Details</p>
							<address>
								Invoice ID:
								<span class="text-dark">#{{ $order->code }}</span>
								<br> {{ $order->order_date }}
								<br> Status: {{ $order->status }}
								<br> Payment Status: {{ $order->payment_status }}
								<br> Shipped by: {{ $order->shipping_service_name }}
								@if ($order->tracking_number != null)
                                    <br> Shipping Number: {{ $order->tracking_number }}
                                @endif
								<br> Payment Method : {{ $order->payment_method }}
								@if($order->payment_status == 'paid')
									<br><span class="text-success"><strong>✓ Payment Completed</strong></span>
								@elseif($order->payment_status == 'waiting')
									<br><span class="text-warning"><strong>⏳ Payment Pending</strong></span>
								@else
									<br><span class="text-danger"><strong>❌ Payment {{ ucfirst($order->payment_status) }}</strong></span>
								@endif
							</address>
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
									<li>Shipping Cost
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
