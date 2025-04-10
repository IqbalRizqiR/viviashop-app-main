@extends('frontend.layouts')
@section('content')
    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Cart</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Pages</a></li>
            <li class="breadcrumb-item active text-white">Cart</li>
        </ol>
    </div>
    <!-- Single Page Header End -->


    <!-- Cart Page Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            @if(session()->has('message'))
				<div class="content-header mb-0 pb-0">
					<div class="container-fluid">
						<div class="mb-0 alert alert-{{ session()->get('alert-type') }} alert-dismissible fade show" role="alert">
							<strong>{{ session()->get('message') }}</strong>
						</div> 
				</div>
			@endif
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Products</th>
                        <th scope="col">Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Total</th>
                        <th scope="col">Handle</th>
                    </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            @php
                                $product = $item->model;
                                $image = !empty($product->productImages->first()) ? asset('storage/'.$product->productImages->first()->path) : asset('themes/ezone/assets/img/cart/3.jpg');
                            @endphp
                            <tr>
                                <th scope="row">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $image }}" class="img-fluid me-5 rounded" style="width: 80px; height: 80px;" alt="">
                                    </div>
                                </th>
                                <td>
                                    <p class="mb-0 mt-4">{{ $product->name }}</p>
                                </td>
                                <td>
                                    <p class="mb-0 mt-4">Rp. {{ number_format($product->price) }}</p>
                                </td>
                                <td>
                                    <input type="number" className="form-control" id="change-qty" value="{{ $item->qty }}" data-productId="{{ $item->rowId }}" min="1" max="{{ $product->productInventory->qty }}">
                                </td>
                                <td>
                                    <p class="mb-0 mt-4">Rp. {{ number_format($item->price * $item->qty, 0, ",", ".")}}</p>
                                </td>
                                <td>
                                    <a href="{{ url('carts/remove/'. $item->rowId)}}" class="btn delete btn-md rounded-circle bg-light border mt-4" >
                                        <i class="fa fa-times text-danger"></i>
                                    </a>
                                </td>
                            
                            </tr>
                        @empty
                            <td colspan="6">The cart is empty!</td>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="row g-4 justify-content-end">
                <div class="col-8"></div>
                <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4">
                    <div class="bg-light rounded">
                        <div class="p-4">
                            <h1 class="display-6 mb-4">Cart <span class="fw-normal">Total</span></h1>
                            <div class="d-flex justify-content-between mb-4">
                                <h5 class="mb-0 me-4">Subtotal:</h5>
                                <p class="mb-0">Rp. {{ Cart::subtotal(0, ",", ".") }}</p>
                            </div>
                        </div>
                        <div class="py-4 mb-4 border-top border-bottom d-flex justify-content-between">
                            <h5 class="mb-0 ps-4 me-4">Total</h5>
                            <p class="mb-0 pe-4">Rp. {{ Cart::subtotal(0, ",", ".") }}</p>
                        </div>
                        <a href="{{ url('orders/checkout') }}" class="btn border-secondary rounded-pill px-4 py-3 text-primary text-uppercase mb-4 ms-4" type="button">Proceed Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cart Page End -->
@endsection
@push('script-alt')
<script>
	$(document).on("change", function (e) {
		var qty = e.target.value;
		var productId = e.target.attributes['data-productid'].value;

        $.ajax({
            type: "POST",
            url: "/carts/update",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                productId,
                qty
            },
            success: function (response) {
				location.reload(true);
				Swal.fire({
                        title: "Jumlah Produk",
                        text: "Berhasil di ganti !",
                        icon: "success",
                        confirmButtonText: "Close",
                    });
            },
        });
    });
</script>
@endpush