@extends('frontend.layouts')

@section('content')
	<div class="breadcrumb-area pt-205 pb-210" style="background-image: url({{ asset('themes/ezone/assets/img/bg/breadcrumb.jpg') }})">
		<div class="container">
			<div class="breadcrumb-content text-center">
				<h2>product details</h2>
				<ul>
					<li><a href="/">home</a></li>
					<li> product details </li>
				</ul>
			</div>
		</div>
	</div>
	@if(session()->has('message'))
		<div class="content-header mb-0 pb-0 mt-3">
			<div class="container-fluid">
				<div class="mb-0 alert alert-{{ session()->get('alert-type') }} alert-dismissible fade show" role="alert">
					<strong>{{ session()->get('message') }}</strong>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			</div>
		</div>
	@endif
	<div class="product-details ptb-100 pb-90">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-lg-7 col-12">
					<div class="product-details-img-content">
						<div class="product-details-tab mr-70">
							<div class="product-details-large tab-content">
								@php
									$i = 1
								@endphp
								@forelse ($product->productImages as $image)
									<div class="tab-pane fade {{ ($i == 1) ? 'active show' : '' }}" id="pro-details{{ $i}}" role="tabpanel">
										<div class="easyzoom easyzoom--overlay">
											@if ($image->path)
												<a href="{{ asset('storage/'.$image->path) }}">
													<img src="{{ asset('storage/'.$image->path) }}" alt="{{ $product->name }}">
												</a>
											@else
												<a href="{{ asset('themes/ezone/assets/img/product-details/bl1.jpg') }}">
													<img src="{{ asset('themes/ezone/assets/img/product-details/l1.jpg') }}" alt="{{ $product->name }}">
												</a>
											@endif
                                        </div>
									</div>
									@php
										$i++
									@endphp
								@empty
									<div class="tab-pane fade active show" id="pro-details1" role="tabpanel">
										<div class="easyzoom easyzoom--overlay">
											<a href="{{ asset('themes/ezone/assets/img/product-details/bl1.jpg') }}">
												<img src="{{ asset('themes/ezone/assets/img/product-details/l1.jpg') }}" alt="{{ $product->name }}">
											</a>
										</div>
									</div>
								@endforelse
							</div>
							<div class="product-details-small nav mt-12" role=tablist>
								@php
									$i = 1
								@endphp
								@forelse ($product->productImages as $image)
									<a style="width: 100px;" class="{{ ($i == 1) ? 'active' : '' }} mr-12" href="#pro-details{{ $i }}" data-toggle="tab" role="tab" aria-selected="true">
										@if ($image->path)
											<img src="{{ asset('storage/'.$image->path) }}" alt="{{ $product->name }}">
										@else
											<img src="{{ asset('themes/ezone/assets/img/product-details/s1.jpg') }}" alt="{{ $product->name }}">
										@endif
									</a>
									@php
										$i++
									@endphp
								@empty
									<a style="width: 100px;" class="active mr-12" href="#pro-details1" data-toggle="tab" role="tab" aria-selected="true">
										<img src="{{ asset('themes/ezone/assets/img/product-details/s1.jpg') }}" alt="{{ $product->name }}">
									</a>
								@endforelse
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12 col-lg-5 col-12">
					<div class="product-details-content">
						<h3>{{ $product->name }}</h3>
						
						@if($product->brand)
							<div class="product-brand mb-2">
								<span class="text-muted">Brand: <strong>{{ $product->brand->name }}</strong></span>
							</div>
						@endif
						
						<div class="details-price">
							<span id="product-price">{{ $product->priceLabel() }}</span>
						</div>
						<p>{{ $product->short_description }}</p>
						
						<div id="stock-info" class="mb-3">
							@if ($product->type === 'simple')
								@if ($product->productInventory)
									<p>Stok: <span id="stock-qty">{{ $product->productInventory->qty }}</span></p>
								@else
									<p class="text-danger">Stok tidak tersedia</p>
								@endif
							@else
								<p>Stok: <span id="stock-qty">{{ $product->total_stock }}</span></p>
							@endif
						</div>

                        <form action="{{ route('carts.store') }}" method="post" id="add-to-cart-form">
							@csrf
							<input type="hidden" name="product_id" value="{{ $product->id }}">
							<input type="hidden" name="variant_id" id="selected-variant-id" value="">
							
							@if ($product->type === 'configurable')
								<div class="product-variants mb-4">
									@php
										$variantOptions = $product->getVariantOptions();
									@endphp
									
									@foreach($variantOptions as $attributeName => $options)
										<div class="variant-selector mb-3">
											<label class="variant-label">{{ ucfirst($attributeName) }}:</label>
											<select name="variant_attributes[{{ $attributeName }}]" 
													class="form-control variant-select" 
													data-attribute="{{ $attributeName }}" 
													required>
												<option value="">Pilih {{ ucfirst($attributeName) }}</option>
												@foreach($options as $option)
													<option value="{{ $option }}">{{ $option }}</option>
												@endforeach
											</select>
										</div>
									@endforeach
									
									<div id="variant-details" class="mt-3" style="display: none;">
										<div class="row">
											<div class="col-6">
												<strong>SKU:</strong> <span id="variant-sku">-</span>
											</div>
											<div class="col-6">
												<strong>Harga:</strong> <span id="variant-price">-</span>
											</div>
										</div>
									</div>
								</div>
							@endif
							
							<div class="quickview-plus-minus">
								<div class="cart-plus-minus">
									<input type="number" name="qty" value="1" class="cart-plus-minus-box" min="1" max="999">
								</div>
								<div class="quickview-btn-cart">
									<button type="submit" class="submit contact-btn btn-hover" id="add-to-cart-btn">
										<i class="fa fa-shopping-cart"></i> add to cart
									</button>
								</div>
								<div class="quickview-btn-wishlist">
									<a class="btn-hover" href="#"><i class="pe-7s-like"></i></a>
								</div>
							</div>
                        </form>
						
						<div class="product-details-cati-tag mt-35">
							<ul>
								<li class="categories-title">Categories :</li>
								@foreach ($product->categories as $category)
									<li><a href="{{ url('products/category/'. $category->slug ) }}">{{ $category->name }}</a></li>
								@endforeach
							</ul>
						</div>
						
						<div class="product-share">
							<ul>
								<li class="categories-title">Share :</li>
								<li>
									<a href="#" onclick="shareOnFacebook()">
										<i class="icofont icofont-social-facebook"></i>
									</a>
								</li>
								<li>
									<a href="#" onclick="shareOnTwitter()">
										<i class="icofont icofont-social-twitter"></i>
									</a>
								</li>
								<li>
									<a href="#" onclick="shareOnWhatsApp()">
										<i class="fa fa-whatsapp"></i>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="product-description-review-area pb-90">
		<div class="container">
			<div class="product-description-review text-center">
				<div class="description-review-title nav" role=tablist>
					<a class="active" href="#pro-dec" data-toggle="tab" role="tab" aria-selected="true">
						Description
					</a>
					<a href="#pro-review" data-toggle="tab" role="tab" aria-selected="false">
						Reviews (0)
					</a>
				</div>
				<div class="description-review-text tab-content">
					<div class="tab-pane active show fade" id="pro-dec" role="tabpanel">
						<p>{!! $product->description !!}</p>
					</div>
					<div class="tab-pane fade" id="pro-review" role="tabpanel">
						<a href="#">Be the first to write your review!</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	@if ($product->type === 'configurable')
	<script>
	document.addEventListener('DOMContentLoaded', function() {
		const variantSelects = document.querySelectorAll('.variant-select');
		const productPrice = document.getElementById('product-price');
		const stockQty = document.getElementById('stock-qty');
		const variantDetails = document.getElementById('variant-details');
		const variantSku = document.getElementById('variant-sku');
		const variantPrice = document.getElementById('variant-price');
		const selectedVariantId = document.getElementById('selected-variant-id');
		const addToCartBtn = document.getElementById('add-to-cart-btn');
		const qtyInput = document.querySelector('input[name="qty"]');

		function updateVariantInfo() {
			const selectedAttributes = {};
			let allSelected = true;

			variantSelects.forEach(select => {
				if (select.value) {
					selectedAttributes[select.dataset.attribute] = select.value;
				} else {
					allSelected = false;
				}
			});

			if (allSelected) {
				fetch('{{ route("api.products.variant-by-attributes", $product->id) }}', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
					},
					body: JSON.stringify({ attributes: selectedAttributes })
				})
				.then(response => response.json())
				.then(data => {
					if (data.success) {
						const variant = data.data;
						selectedVariantId.value = variant.id;
						productPrice.textContent = variant.formatted_price;
						stockQty.textContent = variant.stock;
						variantSku.textContent = variant.sku;
						variantPrice.textContent = variant.formatted_price;
						variantDetails.style.display = 'block';
						
						qtyInput.max = variant.stock;
						
						if (variant.is_available) {
							addToCartBtn.disabled = false;
							addToCartBtn.innerHTML = '<i class="fa fa-shopping-cart"></i> add to cart';
						} else {
							addToCartBtn.disabled = true;
							addToCartBtn.innerHTML = 'Out of Stock';
						}
					} else {
						resetVariantInfo();
					}
				})
				.catch(error => {
					console.error('Error:', error);
					resetVariantInfo();
				});
			} else {
				resetVariantInfo();
			}
		}

		function resetVariantInfo() {
			selectedVariantId.value = '';
			productPrice.textContent = '{{ $product->priceLabel() }}';
			stockQty.textContent = '{{ $product->total_stock }}';
			variantDetails.style.display = 'none';
			addToCartBtn.disabled = true;
			addToCartBtn.innerHTML = 'Select Variant';
			qtyInput.max = 999;
		}

		variantSelects.forEach(select => {
			select.addEventListener('change', updateVariantInfo);
		});

		// Initial state
		resetVariantInfo();

		// Form submission
		document.getElementById('add-to-cart-form').addEventListener('submit', function(e) {
			e.preventDefault();
			
			if (!selectedVariantId.value) {
				alert('Please select a product variant');
				return;
			}

			const formData = new FormData(this);
			
			fetch('{{ route("carts.store") }}', {
				method: 'POST',
				headers: {
					'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
				},
				body: formData
			})
			.then(response => response.json())
			.then(data => {
				if (data.status === 'success') {
					alert('Product added to cart successfully!');
					// Update cart count if you have a cart counter in your layout
					if (typeof updateCartCount === 'function') {
						updateCartCount(data.cart_count);
					}
				} else {
					alert(data.message || 'Failed to add product to cart');
				}
			})
			.catch(error => {
				console.error('Error:', error);
				alert('Failed to add product to cart');
			});
		});
	});

	function shareOnFacebook() {
		window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(window.location.href), '_blank');
	}

	function shareOnTwitter() {
		window.open('https://twitter.com/intent/tweet?url=' + encodeURIComponent(window.location.href) + '&text=' + encodeURIComponent('{{ $product->name }}'), '_blank');
	}

	function shareOnWhatsApp() {
		window.open('https://wa.me/?text=' + encodeURIComponent('{{ $product->name }} - ' + window.location.href), '_blank');
	}
	</script>
	@endif
@endsection
