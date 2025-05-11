@php
    // get first image or placeholder
    if (!empty($product->productImages)) {
        $imgPath = $product->productImages->first()->path;
    } else {
        $imgPath = '/images/placeholder.jpg';
    }

    $imageUrl = $imgPath
        ? asset('/storage/'.$imgPath)
        : asset('images/placeholder.jpg');
@endphp

<div class="col-md-6 col-lg-4 col-xl-3">
  <div class="rounded position-relative fruite-item">
    <div class="fruite-img overflow-hidden">
      <img src="{{ $imageUrl }}" class="img-fluid w-100" alt="{{ $product->name }}">
    </div>
    <div class="p-4 rounded-bottom text-center">
      <a href="{{ route('shop-detail', $product->id) }}">
        <h4 class="mb-2">{{ $product->name }}</h4>
      </a>
      <p class="text-muted mb-2">
        {{ Str::limit($product->short_description ?? '', 60) }}
      </p>
      <p class="fs-5 fw-bold mb-3">
        Rp. {{ number_format($product->price, 0, ',', '.') }}
      </p>
      <a href="javascript:void(0)"
         class="btn border border-secondary rounded-pill px-3 text-primary add-to-cart"
         data-product-id="{{ $product->id }}"
         data-product-slug="{{ $product->slug }}">
        <i class="fa fa-shopping-bag me-2"></i> Add to cart
      </a>
    </div>
  </div>
</div>
