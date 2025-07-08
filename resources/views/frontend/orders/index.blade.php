@extends('frontend.layouts')

@section('content')
	<div class="breadcrumb-area pt-205 breadcrumb-padding pb-210" style="margin-top: 12rem;">
		<div class="container-fluid">
			<div class="breadcrumb-content text-center">
				<h2>My Order</h2>
			</div>
		</div>
	</div>
	<div class="shop-page-wrapper shop-page-padding ptb-100">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-3">
					@include('frontend.partials.user_menu')
				</div>
				<div class="col-lg-9">
					<div class="shop-product-wrapper res-xl">
						<div class="table-content table-responsive">
							<table class="table table-bordered table-striped">
								<thead>
									<th>Order ID</th>
									<th>Grand Total</th>
									<th>Nomer Resi</th>
									<th>Status</th>
									<th>Payment Status</th>
									<th>Payment Method</th>
									<th>Action</th>
								</thead>
								<tbody>
									@forelse($orders as $order)
										<tr>
											<td>
												{{ $order->code }}<br>
												<span style="font-size: 12px; font-weight: normal"> {{ date('d M Y', strtotime($order->order_date)) }}</span>
											</td>
											<td>Rp{{ number_format($order->grand_total, 0, ",", ".") }}</td>
											@if ($order->shipment != NULL)
												@if ($order->shipment->track_number != NULL)
													<td>{{ $order->shipment->track_number }}</td>
												@elseif($order->shipment->track_number == NULL && $order->shipping_courier == 'SELF')
													<td>Ambil di Toko</td>
                                                @else
                                                    <td>Belum Ada Resi</td>
												@endif
											@else
												<td>Ambil di Toko</td>
											@endif
											<td>{{ $order->status }}</td>
											<td>{{ $order->payment_status }}</td>
											@if ($order->payment_method == 'cod')
                                                <td>Toko</td>
                                            @else
                                                <td>{{ $order->payment_method }}</td>
                                            @endif
											<td>
												@if ($order->payment_method == 'manual' || $order->payment_method == 'qris')
													<a href="{{ url('orders/'. $order->id) }}" class="btn btn-info btn-sm">details</a>
													@if ($order->payment_status == 'unpaid')
													<a href="{{ route('orders.confirmation_payment', $order->id) }}" class="btn btn-info btn-sm">confirm payment</a>
													@endif
												@else
													<a href="{{ url('orders/'. $order->id) }}" class="btn btn-info btn-sm">details</a>
												@endif
											</td>
										</tr>
									@empty
										<tr>
											<td colspan="5">No records found</td>
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
@endsection
