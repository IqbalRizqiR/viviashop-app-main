@extends('frontend.layouts')
@section('content')
    <style>
        .variant-option {
            min-width: 60px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        
        .variant-option:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .variant-option:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .variant-option.btn-outline-danger {
            position: relative;
        }
        
        .variant-option.btn-outline-danger:after {
            content: '';
            position: absolute;
            top: 50%;
            left: 10%;
            right: 10%;
            height: 1px;
            background: #dc3545;
            transform: rotate(-15deg);
        }
        
        .price-range h5 {
            font-weight: 600;
            font-size: 1.5rem;
        }
        
        .variant-group {
            border-bottom: 1px solid #eee;
            padding-bottom: 1rem;
        }
        
        .variant-group:last-child {
            border-bottom: none;
        }
    </style>
    
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Shop Detail</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Pages</a></li>
            <li class="breadcrumb-item active text-white">Shop Detail</li>
        </ol>
    </div>

    <div class="container-fluid py-5 mt-5">
        <div class="container py-5">
            <div class="row g-4 mb-5">
                <div class="col-lg-8 col-xl-9">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="border rounded">
                                @if ($parentProduct->productImages->count() > 0)
                                    @if ($parentProduct->productImages->count() > 1)
                                        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                                            <div class="carousel-indicators">
                                                @foreach ($parentProduct->productImages as $key)
                                                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $loop->index }}" class="active"></button>
                                                @endforeach
                                            </div>
                                            <div class="carousel-inner">
                                                @foreach($parentProduct->productImages as $key => $images)
                                                <div class="carousel-item {{$key == 0 ? 'active' : '' }}">
                                                    <img src="{{ asset('storage/'. $images->path) }}" class="d-block w-100"  alt="...">
                                                </div>
                                                @endforeach
                                            </div>
                                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Next</span>
                                            </button>
                                        </div>
                                    @else
                                        <img src="{{ asset('storage/'. $parentProduct->productImages->first()->path) }}" class="img-fluid rounded" alt="Image">
                                    @endif
                                @else
                                <img src="{{ asset('images/placeholder.jpg') }}" class="img-fluid rounded" alt="Image">
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h4 class="fw-bold mb-3">{{ $parentProduct->name }}</h4>
                            @if($productCategory)
                                <p class="mb-3">Category: {{ $productCategory->categories->name }}</p>
                            @endif
                            @if($parentProduct->productInventory)
                                <p class="mb-3">Stock: {{ $parentProduct->productInventory->qty }}</p>
                            @endif
                            <h5 class="fw-bold mb-3" id="product-price">Rp. {{ number_format($parentProduct->price) }}</h5>
                            <div class="d-flex mb-4">
                            </div>
                            <b class="mb-4">{{ $parentProduct->short_description }}</b>
                            @if ($parentProduct->productInventory != null)
                                <p id="stock-info">Stok : {{ $parentProduct->productInventory->qty }}</p>
                            @endif
                            
                            @if($parentProduct->type == 'configurable' && $parentProduct->activeVariants->count() > 0)
                                <div class="product-variants mb-4">
                                    <h6>Pilih Varian Produk:</h6>
                                    @php
                                        $variantOptions = $parentProduct->getVariantOptions();
                                    @endphp
                                    
                                    @foreach($variantOptions as $attributeName => $options)
                                        <div class="variant-group mb-3">
                                            <label class="form-label fw-bold">{{ ucfirst($attributeName) }}:</label>
                                            <div class="variant-options d-flex flex-wrap gap-2" data-attribute="{{ $attributeName }}">
                                                @foreach($options as $option)
                                                    <button type="button" 
                                                            class="btn btn-outline-secondary variant-option" 
                                                            data-attribute="{{ $attributeName }}" 
                                                            data-value="{{ $option }}">
                                                        {{ $option }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    <div class="price-range mb-3">
                                        <h5 id="price-display" class="text-primary">
                                            @if($parentProduct->activeVariants->count() > 1)
                                                Rp {{ number_format($parentProduct->activeVariants->min('price'), 0, ',', '.') }} - 
                                                Rp {{ number_format($parentProduct->activeVariants->max('price'), 0, ',', '.') }}
                                            @else
                                                Rp {{ number_format($parentProduct->price, 0, ',', '.') }}
                                            @endif
                                        </h5>
                                    </div>
                                    
                                    <div id="variant-info" class="mt-3" style="display: none;">
                                        <div class="alert alert-success">
                                            <h6 class="mb-2"><i class="fas fa-check-circle"></i> <strong>Varian Terpilih:</strong></h6>
                                            <div class="variant-name mb-2">
                                                <strong>Nama:</strong> <span id="variant-name" class="text-dark">-</span>
                                            </div>
                                            <div class="variant-attributes mb-2">
                                                <strong>Spesifikasi:</strong> <span id="variant-attributes" class="text-muted">-</span>
                                            </div>
                                            <div class="row">
                                                <div class="col-4">
                                                    <small><strong>SKU:</strong> <span id="variant-sku">-</span></small>
                                                </div>
                                                <div class="col-4">
                                                    <small><strong>Stok:</strong> <span id="variant-stock">-</span></small>
                                                </div>
                                                <div class="col-4">
                                                    <small><strong>Berat:</strong> <span id="variant-weight">-</span>g</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div id="selection-message" class="alert alert-warning mt-2">
                                        <i class="fas fa-info-circle"></i> Pilih semua varian untuk melanjutkan
                                    </div>
                                </div>
                            @endif
                            
                            <p class="mb-4">{!! $parentProduct->description !!}</p>
                            <div class="input-group quantity mb-5" style="width: 100px;">
                                <input type="number" class="form-control" id="quantity" value="1" min="1">
                            </div>
                            <input type="hidden" id="selected-variant-id" value="">
                            <button class="btn btn-primary btn-lg px-4 py-2 mb-4 add-to-cart-btn" 
                                    data-product-id="{{ $parentProduct->id }}" 
                                    data-product-type="{{ $parentProduct->type }}" 
                                    data-product-slug="{{ $parentProduct->slug }}"
                                    @if($parentProduct->type == 'configurable') disabled @endif>
                                <i class="fa fa-shopping-bag me-2"></i>
                                @if($parentProduct->type == 'configurable')
                                    Pilih varian terlebih dahulu
                                @else
                                    Tambah ke Keranjang
                                @endif
                            </button>
                        </div>
                        <div class="col-lg-12">
                            <nav>
                                <div class="nav nav-tabs mb-3">
                                    <button class="nav-link border-white border-bottom-0" type="button" role="tab"
                                        id="nav-about-tab" data-bs-toggle="tab" data-bs-target="#nav-about"
                                        aria-controls="nav-about" aria-selected="true">Description</button>
                                    <button class="nav-link border-white border-bottom-0" type="button" role="tab"
                                        id="nav-links-tab" data-bs-toggle="tab" data-bs-target="#nav-links"
                                        aria-controls="nav-links" aria-selected="true">Link Product</button>
                                </div>
                            </nav>
                            <div class="tab-content mb-5">
                                <div class="tab-pane active" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                                    <b>{{ $parentProduct->short_description }} </b>
                                    @if ($parentProduct->productInventory != null)
                                        <p>Stok : {{ $parentProduct->productInventory->qty }}</p>
                                    @endif
                                    <p>{!! $parentProduct->description !!}</p>
                                    <div class="px-2">
                                        <div class="row g-4">
                                            <div class="col-6">
                                                <div class="row bg-light align-items-center text-center justify-content-center py-2">
                                                    <div class="col-6">
                                                        <p class="mb-0">Weight</p>
                                                    </div>
                                                    <div class="col-6">
                                                        <p class="mb-0">{{ $parentProduct->weight }} gram</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="nav-links" role="tabpanel" aria-labelledby="nav-links-tab">
                                    <a href="{{ $parentProduct->link1 }}"><p>Product Link 1 : {{ $parentProduct->link1 }} </p></a>
                                    <a href="{{ $parentProduct->link2 }}"><p>Product Link 2 : {{ $parentProduct->link2 }}</p></a>
                                    <a href="{{ $parentProduct->link3 }}"><p>Product Link 3 : {{ $parentProduct->link3 }}</p></a>
                                </div>
                                <div class="tab-pane" id="nav-mission" role="tabpanel" aria-labelledby="nav-mission-tab">
                                    <div class="d-flex">
                                        <img src="img/avatar.jpg" class="img-fluid rounded-circle p-3" style="width: 100px; height: 100px;" alt="">
                                        <div class="">
                                            <p class="mb-2" style="font-size: 14px;">April 12, 2024</p>
                                            <div class="d-flex justify-content-between">
                                                <h5>Sam Peters</h5>
                                                <div class="d-flex mb-3">
                                                    <i class="fa fa-star text-secondary"></i>
                                                    <i class="fa fa-star text-secondary"></i>
                                                    <i class="fa fa-star text-secondary"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                </div>
                                            </div>
                                            <p class="text-dark">The generated Lorem Ipsum is therefore always free from repetition injected humour, or non-characteristic
                                                words etc. Susp endisse ultricies nisi vel quam suscipit </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="nav-vision" role="tabpanel">
                                    <p class="text-dark">Tempor erat elitr rebum at clita. Diam dolor diam ipsum et tempor sit. Aliqu diam
                                        amet diam et eos labore. 3</p>
                                    <p class="mb-0">Diam dolor diam ipsum et tempor sit. Aliqu diam amet diam et eos labore.
                                        Clita erat ipsum et lorem et sit</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xl-3">
                    <div class="row g-4 fruite">
                        <div class="col-lg-12">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const variantButtons = document.querySelectorAll('.variant-option');
            const addToCartBtn = document.querySelector('.add-to-cart-btn');
            const priceDisplay = document.getElementById('price-display');
            const stockElement = document.getElementById('stock-info');
            const variantInfo = document.getElementById('variant-info');
            const selectionMessage = document.getElementById('selection-message');
            const variantName = document.getElementById('variant-name');
            const variantAttributes = document.getElementById('variant-attributes');
            const variantSku = document.getElementById('variant-sku');
            const variantStock = document.getElementById('variant-stock');
            const variantWeight = document.getElementById('variant-weight');
            const selectedVariantId = document.getElementById('selected-variant-id');
            const quantityInput = document.getElementById('quantity');
            
            let selectedAttributes = {};
            let allVariants = @json($parentProduct->activeVariants->load('variantAttributes')->values());
            let availableOptions = @json($variantOptions);
            
            function initializeVariantSystem() {
                if (@json($parentProduct->type) === 'simple') {
                    addToCartBtn.disabled = false;
                    selectionMessage.style.display = 'none';
                    return;
                }
                
                updateAvailableOptions();
            }
            
            function updateAvailableOptions() {
                const possibleVariants = filterPossibleVariants();
                const newAvailableOptions = extractAvailableOptions(possibleVariants);
                
                Object.keys(availableOptions).forEach(attributeName => {
                    const buttons = document.querySelectorAll(`[data-attribute="${attributeName}"]`);
                    buttons.forEach(button => {
                        if (button.classList.contains('variant-option')) {
                            const value = button.dataset.value;
                            const isAvailable = newAvailableOptions[attributeName]?.includes(value);
                            const isSelected = selectedAttributes[attributeName] === value;
                            
                            button.disabled = !isAvailable && !isSelected;
                            button.classList.toggle('btn-outline-secondary', !isSelected && isAvailable);
                            button.classList.toggle('btn-primary', isSelected);
                            button.classList.toggle('btn-outline-danger', !isAvailable && !isSelected);
                            
                            if (!isAvailable && !isSelected) {
                                button.title = 'Tidak tersedia untuk kombinasi yang dipilih';
                            } else {
                                button.title = '';
                            }
                        }
                    });
                });
                
                updatePriceRange(possibleVariants);
                updateCartButton();
            }
            
            function filterPossibleVariants() {
                return allVariants.filter(variant => {
                    return Object.entries(selectedAttributes).every(([attrName, attrValue]) => {
                        return variant.variant_attributes.some(attr => 
                            attr.attribute_name === attrName && attr.attribute_value === attrValue
                        );
                    });
                });
            }
            
            function extractAvailableOptions(variants) {
                const options = {};
                Object.keys(availableOptions).forEach(attributeName => {
                    options[attributeName] = [...new Set(
                        variants.flatMap(variant => 
                            variant.variant_attributes
                                .filter(attr => attr.attribute_name === attributeName)
                                .map(attr => attr.attribute_value)
                        )
                    )];
                });
                return options;
            }
            
            function updatePriceRange(variants) {
                if (variants.length === 0) {
                    priceDisplay.textContent = 'Kombinasi tidak tersedia';
                    return;
                }
                
                if (variants.length === 1) {
                    const variant = variants[0];
                    priceDisplay.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(variant.price)}`;
                    updateVariantDisplay(variant);
                } else {
                    const minPrice = Math.min(...variants.map(v => v.price));
                    const maxPrice = Math.max(...variants.map(v => v.price));
                    
                    if (minPrice === maxPrice) {
                        priceDisplay.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(minPrice)}`;
                    } else {
                        priceDisplay.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(minPrice)} - Rp ${new Intl.NumberFormat('id-ID').format(maxPrice)}`;
                    }
                    
                    resetVariantDisplay();
                }
            }
            
            function updateVariantDisplay(variant) {
                variantName.textContent = variant.name;
                variantSku.textContent = variant.sku;
                variantStock.textContent = variant.stock;
                variantWeight.textContent = variant.weight || 0;
                
                const attributesList = variant.variant_attributes
                    .map(attr => `${attr.attribute_name}: ${attr.attribute_value}`)
                    .join(', ');
                variantAttributes.textContent = attributesList;
                
                selectedVariantId.value = variant.id;
                quantityInput.max = variant.stock;
                
                variantInfo.style.display = 'block';
                selectionMessage.style.display = 'none';
                
                if (stockElement) {
                    stockElement.textContent = `Stok: ${variant.stock}`;
                    stockElement.style.display = 'block';
                }
            }
            
            function resetVariantDisplay() {
                selectedVariantId.value = '';
                variantInfo.style.display = 'none';
                selectionMessage.style.display = 'block';
                
                if (stockElement && @json($parentProduct->productInventory)) {
                    stockElement.textContent = `Stok: ${@json($parentProduct->productInventory->qty)}`;
                }
            }
            
            function updateCartButton() {
                const totalAttributes = Object.keys(availableOptions).length;
                const selectedCount = Object.keys(selectedAttributes).length;
                const isComplete = selectedCount === totalAttributes;
                
                if (@json($parentProduct->type) === 'configurable') {
                    addToCartBtn.disabled = !isComplete;
                    
                    if (isComplete) {
                        addToCartBtn.innerHTML = '<i class="fa fa-shopping-bag me-2"></i>Tambah ke Keranjang';
                        addToCartBtn.classList.remove('btn-secondary');
                        addToCartBtn.classList.add('btn-primary');
                    } else {
                        const remaining = totalAttributes - selectedCount;
                        addToCartBtn.innerHTML = `<i class="fa fa-info-circle me-2"></i>Pilih ${remaining} varian lagi`;
                        addToCartBtn.classList.remove('btn-primary');
                        addToCartBtn.classList.add('btn-secondary');
                    }
                }
            }
            
            function findExactVariant() {
                return allVariants.find(variant => {
                    return Object.entries(selectedAttributes).every(([attrName, attrValue]) => {
                        return variant.variant_attributes.some(attr => 
                            attr.attribute_name === attrName && attr.attribute_value === attrValue
                        );
                    }) && Object.keys(selectedAttributes).length === variant.variant_attributes.length;
                });
            }
            
            variantButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const attributeName = this.dataset.attribute;
                    const value = this.dataset.value;
                    
                    if (this.disabled) return;
                    
                    if (selectedAttributes[attributeName] === value) {
                        delete selectedAttributes[attributeName];
                    } else {
                        selectedAttributes[attributeName] = value;
                    }
                    
                    updateAvailableOptions();
                });
            });
            
            addToCartBtn.addEventListener('click', function(e) {
                if (this.disabled) {
                    e.preventDefault();
                    return;
                }
                
                if (@json($parentProduct->type) === 'configurable') {
                    const exactVariant = findExactVariant();
                    if (!exactVariant) {
                        alert('Varian tidak ditemukan');
                        return;
                    }
                    
                    if (exactVariant.stock < 1) {
                        alert('Stok habis');
                        return;
                    }
                }
                
                const formData = {
                    product_id: @json($parentProduct->id),
                    qty: quantityInput.value,
                    variant_id: selectedVariantId.value || null,
                    _token: '{{ csrf_token() }}'
                };
                
                fetch('{{ route("carts.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Produk berhasil ditambahkan ke keranjang');
                        location.reload();
                    } else {
                        alert(data.message || 'Gagal menambahkan ke keranjang');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan');
                });
            });
            
            initializeVariantSystem();
        });
    </script>
@endsection
