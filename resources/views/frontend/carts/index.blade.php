@extends('frontend.layouts')
@section('content')
    <style>
        :root{ --green-600:#16a34a; --green-500:#10b981; --muted:#6b7280; --base:#0f172a; }

        /* Cart table and items */
        .cart-table { border-collapse: separate; border-spacing: 0 14px; width:100%; }
        .cart-table thead th { background: transparent; color: var(--muted); font-weight: 700; border-bottom: 0; padding: .9rem 1rem; text-transform: uppercase; font-size: .8rem; }
        .cart-table tbody tr { background: #fff; box-shadow: 0 10px 30px rgba(2,6,23,0.06); border-radius: .75rem; }
        .cart-table tbody td, .cart-table tbody th { vertical-align: middle; padding: 1.15rem; border-top: 0; }
        .product-thumb-sm { width: 96px; height: 96px; object-fit: cover; border-radius: 0.6rem; box-shadow: 0 10px 30px rgba(2,6,23,0.06); border: 1px solid rgba(15,23,42,0.04); }
        .product-line-name { font-size: 1.02rem; color: var(--base); font-weight: 700; }
        .product-line-meta { font-size: .875rem; color: var(--muted); margin-top: .35rem; }
        .qty-input { max-width: 130px; }
        .qty-input input.form-control { border-radius: .5rem; border: 1px solid rgba(15,23,42,0.06); box-shadow: inset 0 2px 6px rgba(2,6,23,0.03); }
        .qty-input input.form-control:focus { box-shadow: 0 6px 18px rgba(16,185,129,0.08); border-color: var(--green-600); }
        .price-amount { font-weight: 800; color: var(--green-600); font-size: 1rem; }

        /* Action (remove) */
        .btn.delete { width:44px; height:44px; display:inline-flex; align-items:center; justify-content:center; }

        /* Summary panel */
        .cart-summary { background: linear-gradient(180deg,#ffffff,#f7fff9); border-radius: .85rem; padding: 1.25rem; box-shadow: 0 25px 60px rgba(8,20,11,0.06); border: 1px solid rgba(6,95,70,0.06); }
        .cart-summary h2 { font-size: 1.25rem; margin-bottom: .5rem; color: var(--base); }
        .cart-summary .summary-row { display:flex; justify-content:space-between; align-items:center; gap: 8px; margin-bottom: .75rem; }
        .cart-summary .summary-row small { color: #475569; }
        .cart-summary .summary-total { font-size:1.125rem; font-weight:800; color:var(--green-600); }
        .btn-gradient { background: linear-gradient(90deg, var(--green-600) 0%, var(--green-500) 100%); border: none; color: #fff; font-weight: 800; padding: .85rem 1.25rem; border-radius: .6rem; width: 100%; letter-spacing: .4px; }
        .btn-gradient:hover { transform: translateY(-3px); box-shadow: 0 18px 45px rgba(6,95,70,0.14); }

        /* Badges and helper */
        .badge-stock { font-size: .8rem; padding: .35rem .6rem; border-radius: 999px; background: linear-gradient(90deg,var(--green-500),var(--green-600)); color: #fff; box-shadow: 0 8px 25px rgba(6,95,70,0.12); }

        .empty-cart { text-align: center; padding: 3rem 1rem; }
        .empty-cart .btn-outline-primary { border-radius: 999px; padding: .6rem 1.25rem; border-color: var(--green-600); color: var(--green-600); }

        @media (max-width: 991px) {
            .product-thumb-sm { width:72px; height:72px; }
            .cart-table thead { display:none; }
            .cart-table tbody td { display:block; width:100%; }
            .cart-table tbody tr { display:block; padding: .75rem; }
            .cart-summary { margin-top: 1rem; }
        }
    </style>
    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Cart</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Pages</a></li>
            <li class="breadcrumb-item active text-white">Cart</li>
        </ol>
    </div>
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
                </div>
            @endif
            <div class="table-responsive">
                <table class="table cart-table">
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
                                if (isset($item->options['type']) && $item->options['type'] === 'configurable') {
                                    $product = \App\Models\Product::find($item->options['product_id']);
                                    $image = !empty($item->options['image']) ? asset('storage/' . $item->options['image']) : asset('themes/ezone/assets/img/cart/3.jpg');
                                    $maxQty = \App\Models\ProductVariant::find($item->options['variant_id'])->stock ?? 1;
                                    $displayName = $item->name;
                                    if (isset($item->options['attributes']) && !empty($item->options['attributes'])) {
                                        $attributes = [];
                                        foreach ($item->options['attributes'] as $attr => $value) {
                                            $attributes[] = $attr . ': ' . $value;
                                        }
                                        $displayName .= ' (' . implode(', ', $attributes) . ')';
                                    }
                                } else {
                                    $product = \App\Models\Product::find($item->options['product_id']);
                                    $image = !empty($product && $product->productImages->first()) ? asset('storage/'.$product->productImages->first()->path) : asset('themes/ezone/assets/img/cart/3.jpg');
                                    $maxQty = $product && $product->productInventory ? $product->productInventory->qty : 1;
                                    $displayName = $product ? $product->name : $item->name;
                                }
                            @endphp
                            <tr>
                                <th scope="row">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $image }}" class="product-thumb-sm me-4" alt="">
                                        <div>
                                            <div class="product-line-name">{{ Str::limit($displayName, 80) }}</div>
                                            @if(isset($item->options['attributes']) && !empty($item->options['attributes']))
                                                <div class="product-line-meta">{{ implode(', ', array_map(function($k, $v){ return $k.': '.$v; }, array_keys($item->options['attributes']), $item->options['attributes'])) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </th>
                                <td>
                                    <div class="d-flex flex-column">
                                        <div class="product-line-name d-lg-none">{{ Str::limit($displayName, 80) }}</div>
                                        <div class="product-line-meta">SKU: {{ $item->options['sku'] ?? ($product->sku ?? 'N/A') }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="price-amount">Rp. {{ number_format($item->price,0,',','.') }}</div>
                                </td>
                                <td>
                                    <div class="qty-input">
                                        <input type="number" class="form-control" id="change-qty" value="{{ $item->qty }}" data-productId="{{ $item->rowId }}" min="1" max="{{ $maxQty }}">
                                    </div>
                                </td>
                                <td>
                                    <div class="price-amount">Rp. {{ number_format($item->price * $item->qty, 0, ",", ".")}}</div>
                                </td>
                                <td>
                                    <a href="{{ url('carts/remove/'. $item->rowId)}}" class="btn delete btn-md rounded-circle bg-light border" >
                                        <i class="fa fa-times text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-cart">
                                        <h4>Your cart is empty</h4>
                                        <p class="text-muted">Looks like you haven't added anything to your cart yet.</p>
                                        <a href="/" class="btn btn-outline-primary">Continue Shopping</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="row g-4 justify-content-end">
                <div class="col-8"></div>
                <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4">
                    <div class="cart-summary">
                        <h2>Order Summary</h2>
                        <div class="summary-row">
                            <small>Items</small>
                            <strong>{{ Cart::count() }}</strong>
                        </div>
                        <div class="summary-row">
                            <small>Subtotal</small>
                            <strong>Rp. {{ Cart::subtotal(0, ",", ".") }}</strong>
                        </div>
                        <div class="summary-row" style="border-top:1px dashed rgba(0,0,0,0.06); padding-top: .75rem; margin-top: .5rem;">
                            <small>Estimated Delivery</small>
                            <small class="text-muted">3-5 days</small>
                        </div>
                        <div style="margin-top: .75rem;">
                            <a href="{{ url('orders/checkout') }}" class="btn btn-gradient" type="button">Proceed to Checkout</a>
                        </div>
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