<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	<span class="pe-7s-close" aria-hidden="true"></span>
</button>
<div class="modal-dialog modal-quickview-width" role="document">
	<div class="modal-content">
		<div class="modal-body">
			<div class="qwick-view-left">
				<div class="quick-view-learg-img">
					<div class="quick-view-tab-content tab-content">
						@php
							$i = 1
						@endphp
						@foreach ($product->productImages as $image)
							<div class="tab-pane fade {{ ($i == 1) ? 'active show' : '' }}" id="modal{{ $i}}" role="tabpanel">
								@if ($image->path)
									<img src="{{ asset('storage/'.$image->path) }}" alt="{{ $product->name }}" style="width:320px;">
								@else
									<img src="{{ asset('themes/ezone/assets/img/quick-view/l3.jpg') }}" alt="">
								@endif
							</div>
							@php
								$i++
							@endphp
						@endforeach
					</div>
				</div>
				<div class="quick-view-list nav" role="tablist">
					@php
						$i = 1
					@endphp
					@foreach ($product->productImages as $image)
						<a class="{{ ($i == 1) ? 'active' : '' }} mr-12" href="#modal{{ $i }}" data-toggle="tab" role="tab">
							@if ($image->path)
								<img src="{{ asset('storage/'.$image->path) }}" alt="{{ $product->name }}" style="width:100px; height:112px">
							@else
								<img src="{{ asset('themes/ezone/assets/img/quick-view/s3.jpg') }}" alt="{{ $product->name }}">
							@endif
						</a>
						@php
							$i++
						@endphp
					@endforeach
				</div>
			</div>
			<div class="qwick-view-right">
				<div class="qwick-view-content">
					<h3>{{ $product->name }}</h3>
					<div class="price">
						<span class="new">{{ number_format($product->priceLabel()) }}</span>
						{{-- <span class="old">$120.00  </span> --}}
					</div>
					<p>{{ $product->short_description }}</p>
					@if ($row->products->productInventory != null)
						<p>Stok : {{ $row->products->type == 'configurable' ? $row->products->total_stock : ($row->products->productInventory->qty ?? 0) }}</p>
					@endif
					<form action="{{ route('carts.store') }}" method="post" id="quick-view-form">
						@csrf 
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="variant_id" id="selected-variant-id" value="">
                        
						@if ($product->configurable())
							<div class="quick-view-select">
								@php
									$variantOptions = $product->getVariantOptions();
								@endphp
								
								@foreach($variantOptions as $attributeName => $options)
									<div class="select-option-part">
										<label>{{ ucfirst($attributeName) }}*</label>
										<select name="variant_attributes[{{ $attributeName }}]" 
												class="select variant-select" 
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
									<div class="alert alert-info">
										<h6 class="mb-2"><i class="fas fa-tag"></i> <strong>Selected Variant:</strong></h6>
										<div class="variant-name mb-2">
											<strong>Name:</strong> <span id="variant-name" class="text-primary">-</span>
										</div>
										<div class="variant-attributes mb-2">
											<strong>Attributes:</strong> <span id="variant-attributes" class="text-secondary">-</span>
										</div>
										<div class="row">
											<div class="col-4">
												<small><strong>SKU:</strong> <span id="variant-sku">-</span></small>
											</div>
											<div class="col-4">
												<small><strong>Price:</strong> <span id="variant-price">-</span></small>
											</div>
											<div class="col-4">
												<small><strong>Stock:</strong> <span id="variant-stock">-</span></small>
											</div>
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
				</div>
			</div>
		</div>
	</div>
</div>

@if ($product->configurable())
<script>
document.addEventListener('DOMContentLoaded', function() {
    const variantSelects = document.querySelectorAll('.variant-select');
    const productPrice = document.querySelector('.price .new');
    const variantDetails = document.getElementById('variant-details');
    const variantSku = document.getElementById('variant-sku');
    const variantPrice = document.getElementById('variant-price');
    const variantName = document.getElementById('variant-name');
    const variantAttributes = document.getElementById('variant-attributes');
    const variantStock = document.getElementById('variant-stock');
    const selectedVariantId = document.getElementById('selected-variant-id');
    const addToCartBtn = document.getElementById('add-to-cart-btn');
    const qtyInput = document.querySelector('input[name="qty"]');

    let selectedAttributes = {};

    function updateVariantInfo() {
        let allSelected = true;

        variantSelects.forEach(select => {
            if (select.value) {
                selectedAttributes[select.dataset.attribute] = select.value;
            } else {
                allSelected = false;
            }
        });

        if (allSelected) {
            fetch(`{{ route('api.products.variant-by-attributes', $product->id) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    attributes: selectedAttributes
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    const variant = data.data;
                    selectedVariantId.value = variant.id;
                    productPrice.textContent = variant.formatted_price;
                    
                    // Update variant details
                    variantName.textContent = variant.name;
                    variantSku.textContent = variant.sku;
                    variantPrice.textContent = variant.formatted_price;
                    variantStock.textContent = variant.stock;
                    
                    // Format attributes display
                    if (variant.attributes && Object.keys(variant.attributes).length > 0) {
                        const attributesList = Object.entries(variant.attributes)
                            .map(([key, value]) => `${key}: ${value}`)
                            .join(', ');
                        variantAttributes.textContent = attributesList;
                    } else {
                        variantAttributes.textContent = '-';
                    }
                    
                    variantDetails.style.display = 'block';
                    addToCartBtn.disabled = !variant.is_available;
                    addToCartBtn.innerHTML = variant.is_available ? '<i class="fa fa-shopping-cart"></i> add to cart' : 'Out of Stock';
                    qtyInput.max = variant.stock;
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
        productPrice.textContent = '{{ number_format($product->priceLabel()) }}';
        variantDetails.style.display = 'none';
        addToCartBtn.disabled = true;
        addToCartBtn.innerHTML = 'Select Variant';
        qtyInput.max = 999;
    }

    variantSelects.forEach(select => {
        select.addEventListener('change', updateVariantInfo);
    });

    resetVariantInfo();

    document.getElementById('quick-view-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!selectedVariantId.value) {
            alert('Please select a product variant');
            return;
        }

        const formData = new FormData(this);
        
        fetch('{{ route("carts.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Product added to cart successfully!');
                location.reload();
            } else {
                alert('Error adding product to cart: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error adding product to cart');
        });
    });
});
</script>
@endif