@extends('frontend.layouts')

@section('content')
	<style>
		.user-sidebar .nav-link.active { background: rgba(16,185,129,0.12); color: #065f46 !important; }
		.user-sidebar .avatar { font-weight:700 }
		.table thead th { border-bottom: 0; }
		.table tbody tr:hover { background: rgba(16,185,129,0.03); }
		.badge { border-radius: .4rem; padding: .35rem .6rem; }
		@media (max-width: 767px) {
			.user-sidebar { margin-top: 2rem !important }
			.card .table-responsive { overflow-x: auto }
			.input-group { width: 100%; }
			form.d-flex { flex-direction: column; gap: .5rem }
			.card .table thead { display: none }
			.card .table tbody tr { display: block; margin-bottom: 12px; background: #fff; border-radius: 8px; box-shadow: 0 6px 18px rgba(16,185,129,0.04); padding: 12px; }
			.card .table tbody td { display:flex; justify-content: space-between; align-items:center; padding: 6px 8px; border-top:0; border-bottom:0; }
			.card .table tbody td .fw-bold { font-size: 14px }
			.card .table tbody td .text-muted { font-size: 12px }
			.card .table tbody td:nth-child(2) { text-align: right; white-space: nowrap }
			.card .table tbody td:nth-child(3), .card .table tbody td:nth-child(4), .card .table tbody td:nth-child(5) { width: auto }
		}
	</style>
	<style>
		/* Scoped pagination fixes to ensure horizontal Bootstrap pagination */
		.pagination-wrapper .pagination {
			display: flex !important;
			flex-wrap: nowrap !important;
			gap: .25rem;
			margin: 0;
			padding: 0;
		}
		.pagination-wrapper .page-item { display: inline-block; }
	</style>
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
					<div class="d-flex align-items-center justify-content-between mb-3 flex-column flex-md-row">
						<div class="mb-2 mb-md-0">
							<h3 class="mb-0">My Orders</h3>
							<small class="text-muted">Manage and view your orders</small>
						</div>
						<form method="GET" action="{{ url('orders') }}" class="d-flex gap-2 align-items-center">
							<div class="input-group">
								<input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search by id, resi, status...">
								<button class="btn btn-success" type="submit">Search</button>
							</div>
							<div class="d-flex gap-1 align-items-center ms-2">
								<select name="sort" class="form-select">
									<option value="id" {{ request('sort')=='id' ? 'selected' : '' }}>Sort by ID</option>
									<option value="grand_total" {{ request('sort')=='grand_total' ? 'selected' : '' }}>Grand Total</option>
									<option value="resi" {{ request('sort')=='resi' ? 'selected' : '' }}>Nomer Resi</option>
									<option value="status" {{ request('sort')=='status' ? 'selected' : '' }}>Status</option>
									<option value="payment_method" {{ request('sort')=='payment_method' ? 'selected' : '' }}>Payment Method</option>
									<option value="order_date" {{ request('sort')=='order_date' ? 'selected' : '' }}>Date</option>
								</select>
								<select name="direction" class="form-select">
									<option value="desc" {{ request('direction','desc')=='desc' ? 'selected' : '' }}>Desc</option>
									<option value="asc" {{ request('direction')=='asc' ? 'selected' : '' }}>Asc</option>
								</select>
							</div>
						</form>
					</div>
					<div class="card shadow-sm border-0">
						<div class="card-body p-0">
							<div class="table-responsive">
								<table class="table table-hover mb-0 align-middle">
									<thead class="table-light">
										<tr>
											<th class="py-3">Order ID</th>
											<th class="py-3 text-end">Grand Total</th>
											<th class="py-3">Nomer Resi</th>
											<th class="py-3">Status</th>
											<th class="py-3">Payment Status</th>
											<th class="py-3">Payment Method</th>
											<th class="py-3 text-center">Action</th>
										</tr>
									</thead>
									<tbody>
										@forelse($orders as $order)
											<tr>
												<td>
													<div class="fw-bold">{{ $order->code }}</div>
													<div class="text-muted small">{{ date('d M Y', strtotime($order->order_date)) }}</div>
												</td>
												<td class="text-end">Rp{{ number_format($order->grand_total, 0, ",", ".") }}</td>
												<td>
													@if ($order->shipment != NULL)
														@if ($order->shipment->track_number != NULL)
															{{ $order->shipment->track_number }}
														@elseif($order->shipment->track_number == NULL && $order->shipping_courier == 'SELF')
															<span class="badge bg-success text-white">Ambil di Toko</span>
														@else
															<span class="text-muted small">Belum Ada Resi</span>
														@endif
													@else
														<span class="badge bg-success text-white">Ambil di Toko</span>
													@endif
												</td>
												<td>
													<span class="badge text-uppercase" style="background: rgba(22,163,74,0.12); color:#116530;">{{ $order->status }}</span>
												</td>
												<td>{{ $order->payment_status }}</td>
												<td>
													@if ($order->payment_method == 'cod')
														Toko
													@else
														{{ $order->payment_method }}
													@endif
												</td>
												<td class="text-center">
													<div class="d-flex gap-1 justify-content-center flex-wrap">
														<a href="{{ url('orders/'. $order->id) }}" class="btn btn-sm btn-outline-success">Details</a>
														@if ($order->payment_method == 'manual' || $order->payment_method == 'qris')
															@if ($order->payment_status == 'unpaid')
																<a href="{{ route('orders.confirmation_payment', $order->id) }}" class="btn btn-sm btn-success">Confirm</a>
															@endif
														@endif
														@if(in_array($order->payment_status, ['unpaid', 'waiting']) && !in_array($order->status, ['completed','cancelled']))
															<a href="{{ url('orders/checkout?order_id=' . $order->id) }}" class="btn btn-sm btn-warning">Resume</a>
														@endif
													</div>
												</td>
											</tr>
										@empty
											<tr>
												<td colspan="7" class="text-center py-4">No records found</td>
											</tr>
										@endforelse
									</tbody>
								</table>
							</div>
									<div class="p-3 border-top d-flex justify-content-between align-items-center">
										<div>
											Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() ?? 0 }} orders
										</div>
										<div class="d-flex pagination-wrapper justify-content-end">
											{{ $orders->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
										</div>
									</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
