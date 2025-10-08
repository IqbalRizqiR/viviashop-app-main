@extends('frontend.layouts')
@section('content')
    <style>
        /* Variant option visuals (keep classes intact for JS) */
        .variant-option {
            min-width: 60px;
            border-radius: 8px;
            transition: all 0.18s ease;
            padding: .4rem .6rem;
        }

        .variant-option:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
        }

        .variant-option:disabled {
            opacity: 0.45;
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

        .price-range h5, #price-display {
            font-weight: 700;
            font-size: 1.75rem;
            color: #e63946; /* a warm primary tint */
            letter-spacing: .2px;
        }

        .variant-group {
            border-bottom: 1px solid #f1f1f1;
            padding-bottom: 1rem;
        }

        .variant-group:last-child {
            border-bottom: none;
        }

        /* Page hero */
        .page-header { background: linear-gradient(90deg,#1e3a8a, #0ea5a0); }

        /* Product area */
        .product-image-frame { padding: .5rem; background: #fff; box-shadow: 0 6px 20px rgba(18,24,32,0.06); }

        .product-name { font-size: 1.3rem; color: #0f172a; }

        .badge-stock { font-size: .8rem; padding: .35rem .6rem; border-radius: 999px; }

        /* Right summary card - Enhanced */
        .summary-card { 
            background: linear-gradient(145deg, #ffffff, #f8fafc); 
            border-radius: .75rem; 
            padding: 1.5rem; 
            box-shadow: 0 20px 50px rgba(0,0,0,0.08), 0 4px 20px rgba(0,0,0,0.04);
            border: 1px solid rgba(255,255,255,0.8);
            backdrop-filter: blur(10px);
        }

        .summary-card .product-thumb { 
            width: 90px; 
            height: 90px; 
            object-fit: cover; 
            border-radius: .6rem; 
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
            border: 2px solid rgba(255,255,255,0.9);
        }

        .sticky-card { position: sticky; top: 100px; }
        
        /* Enhanced visual elements */
        .product-info-header {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
        }
        
        .info-badge {
            background: linear-gradient(45deg, #48bb78 0%, #38a169 100%);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            margin: 0.2rem;
            box-shadow: 0 3px 10px rgba(72, 187, 120, 0.3);
        }
        
        .pricing-section {
            background: linear-gradient(135deg, #e6fffa 0%, #b2f5ea 100%);
            padding: 1rem;
            border-radius: 0.6rem;
            margin: 0.5rem 0;
            border: 1px solid rgba(72, 187, 120, 0.2);
        }

        .share-actions i { font-size: 1.05rem; }

        /* CTA gradient style - Enhanced */
        .btn-gradient {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            border: none;
            color: #fff;
            font-weight: 700;
            padding: 0.75rem 1.5rem;
            border-radius: 0.6rem;
            box-shadow: 0 8px 25px rgba(72, 187, 120, 0.4);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(72, 187, 120, 0.5);
            background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
        }

        .btn-gradient:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .btn-gradient:hover:before {
            left: 100%;
        }

        .btn-gradient:disabled {
            opacity: 0.6;
            transform: none;
            box-shadow: 0 4px 15px rgba(72, 187, 120, 0.2);
        }

        .btn-gradient:disabled:hover {
            transform: none;
        }

        .product-thumb { border-radius: 8px; }

        .text-decoration-line-through { opacity: .7; }

        .fw-semibold { font-weight: 600; }
        
        /* Quantity input enhancement */
        .quantity-wrapper {
            background: linear-gradient(145deg, #f7fafc, #edf2f7);
            border-radius: 0.5rem;
            padding: 0.5rem;
            border: 1px solid #e2e8f0;
        }
        
        .quantity-wrapper input {
            border: none;
            background: white;
            border-radius: 0.4rem;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.06);
        }
        
        /* Badge enhancements */
        .badge-stock.bg-success {
            background: linear-gradient(45deg, #48bb78, #38a169) !important;
            box-shadow: 0 3px 10px rgba(72, 187, 120, 0.3);
        }
        
        /* Share icons enhancement */
        .share-actions i {
            transition: all 0.3s ease;
            padding: 0.4rem;
            border-radius: 50%;
            margin: 0 0.2rem;
        }
        
        .share-actions i:hover {
            background: linear-gradient(45deg, #48bb78, #38a169);
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(72, 187, 120, 0.3);
        }
        
        /* Sticky panel for the entire summary card */
        .cta-wrapper {
            position: sticky;
            bottom: 20px; /* small gap for visual breathing when card is not fixed */
            width: 100%;
            padding-top: .5rem;
            background: transparent;
        }

        /* On larger screens make the whole summary card a fixed panel on the right */
        @media(min-width: 992px) {
            /* Use pure sticky positioning that follows scroll naturally */
            .summary-card.sticky-card {
                position: sticky;
                top: 120px; /* Safe distance below navbar */
                width: 320px;
                z-index: 500; /* Below navbar but above content */
                box-shadow: 0 18px 40px rgba(2,6,23,0.12);
                border-radius: .6rem;
                background: #fff;
                margin-left: auto;
                transition: box-shadow .15s ease;
                /* Ensure it follows scroll naturally without fixed behavior */
                transform: translateZ(0);
                will-change: auto;
            }

            /* Remove fixed panel behavior completely */
            .summary-card.fixed-panel {
                /* Disable fixed positioning completely */
                position: sticky !important;
                top: 120px !important;
                right: auto !important;
                width: 320px !important;
                z-index: 500 !important;
                margin-left: auto;
            }

            /* Remove forced padding on content; layout will remain stable because sticky element stays in column */
            .col-xl-9 { padding-right: 0; }
        }

        @media(max-width: 991px) {
            .summary-card.sticky-card { 
                position: static !important; 
                width: 100% !important; 
                box-shadow: none !important; 
                top: auto !important;
                z-index: auto !important;
                margin-left: 0 !important;
            }
            .col-xl-9 { padding-right: 0; }
            .cta-wrapper { position: static; }
        }

        /* Tab styling */
        .nav-tabs .nav-link { color: #334155; background: transparent; border: 0; padding: .5rem 1rem; }
        .nav-tabs .nav-link.active { border-bottom: 3px solid #48bb78; color: #111827; }

        @media (max-width: 991px) {
            .sticky-card { 
                position: static !important; 
                top: auto !important;
                width: 100% !important;
            }
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
                            <div class="product-image-section">
                                <div class="border-0 rounded-lg overflow-hidden shadow-lg" style="background: linear-gradient(145deg, #f7fafc, #edf2f7);">
                                    @if ($parentProduct->productImages->count() > 0)
                                        @if ($parentProduct->productImages->count() > 1)
                                            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                                                <div class="carousel-indicators">
                                                    @foreach ($parentProduct->productImages as $key)
                                                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}" style="background: linear-gradient(45deg, #667eea, #764ba2);"></button>
                                                    @endforeach
                                                </div>
                                                <div class="carousel-inner">
                                                    @foreach($parentProduct->productImages as $key => $images)
                                                    <div class="carousel-item {{$key == 0 ? 'active' : '' }}">
                                                        <img src="{{ asset('storage/'. $images->path) }}" class="d-block w-100" style="border-radius: 0.5rem;" alt="Product Image">
                                                    </div>
                                                    @endforeach
                                                </div>
                                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true" style="background: linear-gradient(45deg, #667eea, #764ba2); border-radius: 50%; width: 40px; height: 40px;"></span>
                                                    <span class="visually-hidden">Previous</span>
                                                </button>
                                                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true" style="background: linear-gradient(45deg, #667eea, #764ba2); border-radius: 50%; width: 40px; height: 40px;"></span>
                                                    <span class="visually-hidden">Next</span>
                                                </button>
                                            </div>
                                        @else
                                            <img src="{{ asset('storage/'. $parentProduct->productImages->first()->path) }}" class="img-fluid rounded" alt="Product Image" style="border-radius: 0.75rem;">
                                        @endif
                                    @else
                                    <img src="{{ asset('images/placeholder.jpg') }}" class="img-fluid rounded" alt="Product Image" style="border-radius: 0.75rem;">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="product-details-section">
                                <div class="mb-3">
                                    <h4 class="fw-bold mb-3" style="color: #2d3748; font-size: 1.75rem; line-height: 1.3;">{{ $parentProduct->name }}</h4>
                                    @if($productCategory)
                                        <div class="mb-3">
                                            <span class="info-badge" style="background: linear-gradient(45deg, #48bb78, #38a169);">
                                                <i class="fa fa-tag me-1"></i>{{ $productCategory->categories->name }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Price and stock are displayed in the right summary card to improve visual hierarchy -->
                                <div class="d-flex mb-4">
                                </div>
                                
                                <div class="product-description-card p-3 mb-4" style="background: linear-gradient(145deg, #f8fafc, #e2e8f0); border-radius: 0.75rem; border: 1px solid #cbd5e0;">
                                    <h6 class="fw-bold text-dark mb-2">Deskripsi Singkat</h6>
                                    <p class="mb-0 text-dark">{{ $parentProduct->short_description }}</p>
                                </div>
                                
                                <!-- Stock display moved to summary card on the right to avoid duplicate IDs and improve layout -->
                                
                                @if($parentProduct->type == 'configurable' && $variants->count() > 0)
                                    <div class="product-variants mb-4">
                                        <div class="variant-header p-3 mb-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 0.6rem; color: white;">
                                            <h6 class="mb-0"><i class="fa fa-cogs me-2"></i>Pilih Varian Produk</h6>
                                        </div>
                                        
                                        @foreach($variantOptions as $attributeName => $options)
                                            <div class="variant-group mb-3">
                                                <label class="form-label fw-bold text-dark mb-2">{{ ucfirst($attributeName) }}:</label>
                                                <div class="variant-options d-flex flex-wrap gap-2" data-attribute="{{ $attributeName }}">
                                                    @foreach($options as $option)
                                                        <button type="button" 
                                                                class="btn btn-outline-secondary variant-option" 
                                                                data-attribute="{{ $attributeName }}" 
                                                                data-value="{{ $option }}"
                                                                style="border-radius: 0.5rem; font-weight: 600;">
                                                            {{ $option }}
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                        
                        <div class="price-range mb-3">
                            <div class="pricing-display p-3" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); border-radius: 0.6rem; border: 1px solid rgba(255,255,255,0.5);">
                                <h5 class="text-dark mb-0">
                                    @if($priceRange && !$priceRange['same'])
                                        Rp {{ number_format($priceRange['min'], 0, ',', '.') }} - 
                                        Rp {{ number_format($priceRange['max'], 0, ',', '.') }}
                                    @elseif($priceRange)
                                        Rp {{ number_format($priceRange['min'], 0, ',', '.') }}
                                    @else
                                        Rp {{ number_format($parentProduct->price, 0, ',', '.') }}
                                    @endif
                                </h5>
                            </div>
                        </div>

                        <div id="variant-info" class="mt-3" style="display: none;">
                                            <div class="alert" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); border: none; color: white; border-radius: 0.6rem;">
                                                <h6 class="mb-2"><i class="fas fa-check-circle me-2"></i><strong>Varian Terpilih:</strong></h6>
                                                <div class="variant-name mb-2">
                                                    <strong>Nama:</strong> <span id="variant-name" class="text-white">-</span>
                                                </div>
                                                <div class="variant-attributes mb-2">
                                                    <strong>Spesifikasi:</strong> <span id="variant-attributes" class="text-white">-</span>
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
                                        
                                        <div id="selection-message" class="alert mt-2" style="background: linear-gradient(135deg, #ffd89b 0%, #19547b 100%); border: none; color: white; border-radius: 0.6rem;">
                                            <i class="fas fa-info-circle me-2"></i>Pilih varian untuk melanjutkan
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="full-description p-3" style="background: linear-gradient(145deg, #ffffff, #f8fafc); border-radius: 0.75rem; border: 1px solid #e2e8f0; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                                    <h6 class="fw-bold text-dark mb-3">Deskripsi Lengkap</h6>
                                    <div class="text-dark">{!! $parentProduct->description !!}</div>
                                </div>
                                <!-- Quantity and add-to-cart moved to the right summary card to improve layout and visual hierarchy -->
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="tabs-section mt-5">
                                <nav>
                                    <div class="nav nav-tabs mb-4" style="border-bottom: 2px solid #e2e8f0;">
                                        <button class="nav-link active fw-bold" type="button" role="tab"
                                            id="nav-about-tab" data-bs-toggle="tab" data-bs-target="#nav-about"
                                            aria-controls="nav-about" aria-selected="true" 
                                            style="color: #4a5568; border: none; padding: 1rem 1.5rem; background: linear-gradient(145deg, #667eea, #764ba2); color: white; border-radius: 0.5rem 0.5rem 0 0; margin-right: 0.5rem;">
                                            <i class="fa fa-info-circle me-2"></i>Description
                                        </button>
                                        <button class="nav-link fw-bold" type="button" role="tab"
                                            id="nav-links-tab" data-bs-toggle="tab" data-bs-target="#nav-links"
                                            aria-controls="nav-links" aria-selected="false"
                                            style="color: #4a5568; border: none; padding: 1rem 1.5rem; background: #f7fafc; border-radius: 0.5rem 0.5rem 0 0; margin-right: 0.5rem; transition: all 0.3s ease;">
                                            <i class="fa fa-link me-2"></i>Link Product
                                        </button>
                                    </div>
                                </nav>
                                <div class="tab-content mb-5">
                                    <div class="tab-pane active" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                                        <div class="content-card p-4" style="background: linear-gradient(145deg, #ffffff, #f8fafc); border-radius: 0.75rem; border: 1px solid #e2e8f0; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                                            <h5 class="fw-bold text-dark mb-3">{{ $parentProduct->short_description }}</h5>
                                            @if ($parentProduct->productInventory && $parentProduct->productInventory->qty)
                                                <div class="stock-info mb-3 p-3" style="background: linear-gradient(45deg, #48bb78, #38a169); color: white; border-radius: 0.5rem;">
                                                    <i class="fa fa-box me-2"></i>Stok : {{ $parentProduct->productInventory->qty }} unit tersedia
                                                </div>
                                            @endif
                                            <div class="description-content text-dark" style="line-height: 1.7;">
                                                {!! $parentProduct->description !!}
                                            </div>
                                            <div class="px-2 mt-4">
                                                <div class="row g-4">
                                                    <div class="col-6">
                                                        <div class="spec-card p-3 text-center" style="background: linear-gradient(145deg, #e6fffa, #f0fff4); border-radius: 0.5rem; border: 1px solid #9ae6b4;">
                                                            <div class="spec-label text-success fw-semibold mb-1">
                                                                <i class="fa fa-weight-hanging me-2"></i>Weight
                                                            </div>
                                                            <div class="spec-value fw-bold text-dark">{{ $parentProduct->weight }} gram</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="nav-links" role="tabpanel" aria-labelledby="nav-links-tab">
                                        <div class="content-card p-4" style="background: linear-gradient(145deg, #ffffff, #f8fafc); border-radius: 0.75rem; border: 1px solid #e2e8f0; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                                            <h5 class="fw-bold text-dark mb-4">Related Product Links</h5>
                                            @if($parentProduct->link1)
                                                <div class="link-item mb-3 p-3" style="background: linear-gradient(145deg, #667eea, #764ba2); border-radius: 0.5rem; transition: all 0.3s ease;">
                                                    <a href="{{ $parentProduct->link1 }}" class="text-white text-decoration-none fw-semibold" target="_blank">
                                                        <i class="fa fa-external-link-alt me-2"></i>Product Link 1 : {{ Str::limit($parentProduct->link1, 50) }}
                                                    </a>
                                                </div>
                                            @endif
                                            @if($parentProduct->link2)
                                                <div class="link-item mb-3 p-3" style="background: linear-gradient(145deg, #667eea, #764ba2); border-radius: 0.5rem; transition: all 0.3s ease;">
                                                    <a href="{{ $parentProduct->link2 }}" class="text-white text-decoration-none fw-semibold" target="_blank">
                                                        <i class="fa fa-external-link-alt me-2"></i>Product Link 2 : {{ Str::limit($parentProduct->link2, 50) }}
                                                    </a>
                                                </div>
                                            @endif
                                            @if($parentProduct->link3)
                                                <div class="link-item mb-3 p-3" style="background: linear-gradient(145deg, #667eea, #764ba2); border-radius: 0.5rem; transition: all 0.3s ease;">
                                                    <a href="{{ $parentProduct->link3 }}" class="text-white text-decoration-none fw-semibold" target="_blank">
                                                        <i class="fa fa-external-link-alt me-2"></i>Product Link 3 : {{ Str::limit($parentProduct->link3, 50) }}
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xl-3">
                    <div class="row g-4 fruite">
                        <div class="col-lg-12">
                            <div class="summary-card sticky-card">
                                <div class="product-info-header">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h6 class="mb-0">Ringkasan Produk</h6>
                                        @if($parentProduct->is_featured ?? false)
                                            <span class="info-badge">Featured</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="d-flex gap-3 align-items-start mb-3">
                                    @php
                                        $thumb = $parentProduct->productImages->first() ? asset('storage/'.$parentProduct->productImages->first()->path) : asset('images/placeholder.jpg');
                                    @endphp
                                    <img src="{{ $thumb }}" alt="thumb" class="product-thumb">
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="product-name fw-bold" style="color: #2d3748; line-height: 1.3;">{{ Str::limit($parentProduct->name, 70) }}</div>
                                        </div>
                                        <div class="mt-2 d-flex align-items-center gap-2">
                                            {{-- Ratings (visual only) --}}
                                            <div class="text-warning">
                                                @for($i=0;$i<5;$i++)
                                                    <i class="fa fa-star{{ $i < ($parentProduct->rating ?? 4) ? '' : '-o' }}" style="font-size: 0.9rem;"></i>
                                                @endfor
                                            </div>
                                            <small class="text-muted">({{ $parentProduct->reviews_count ?? rand(5,50) }})</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="pricing-section">
                                    <div class="d-flex align-items-baseline gap-2">
                                        <div class="fw-bold text-dark" style="font-size:1.4rem;" id="price-display"></div>
                                        @if($parentProduct->original_price && $parentProduct->original_price > $parentProduct->price)
                                            <div class="text-muted text-decoration-line-through">Rp {{ number_format($parentProduct->original_price,0,',','.') }}</div>
                                            <div class="info-badge ms-auto">{{ round((($parentProduct->original_price-$parentProduct->price)/$parentProduct->original_price)*100) }}% off</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-6">
                                        <small class="text-muted d-block">SKU</small>
                                        <div class="fw-semibold text-dark">{{ $parentProduct->sku ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-6 text-end">
                        @php $stockQty = $parentProduct->type == 'configurable' ? $parentProduct->total_stock : ($parentProduct->productInventory->qty ?? 0); @endphp
                        @if ($stockQty)
                            <i class="fa fa-box me-2"></i>Stok : {{ $stockQty }} unit tersedia
                                        @else
                                            <span class="badge bg-secondary badge-stock">Out of Stock</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mb-3 p-3" style="background: linear-gradient(145deg, #e6fffa, #f0fff4); border-radius: 0.5rem; border: 1px solid #9ae6b4;">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <small class="text-success fw-semibold">🚚 Estimasi Pengiriman</small>
                                            <div class="fw-semibold text-dark">3-5 hari kerja</div>
                                        </div>
                                        <small class="text-muted">JNE / Tiki / Gojek</small>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label mb-2 fw-semibold text-dark">Kuantitas</label>
                                    <div class="quantity-wrapper">
                                        <div class="input-group" style="max-width: 140px;">
                                            <input type="number" class="form-control" id="quantity" value="1" min="1">
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" id="selected-variant-id" value="">

                                <div class="cta-wrapper">
                                    <div class="d-grid mb-2">
                                        <button class="btn btn-gradient btn-lg add-to-cart-btn"
                                                data-product-id="{{ $parentProduct->id }}"
                                                data-product-type="{{ $parentProduct->type }}"
                                                data-product-slug="{{ $parentProduct->slug }}"
                                                @if($parentProduct->type == 'configurable' && $variants->count() > 0) disabled @endif>
                                            <i class="fa fa-shopping-bag me-2"></i>
                                            <span class="cta-text">
                                            @if($parentProduct->type == 'configurable' && $variants->count() > 0)
                                                Pilih varian terlebih dahulu
                                            @else
                                                Tambah ke Keranjang
                                            @endif
                                            </span>
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-3 text-center">
                                    <div class="share-actions">
                                        <small class="text-muted me-2">Share:</small>
                                        <i class="fab fa-facebook text-primary" style="cursor: pointer;"></i>
                                        <i class="fab fa-twitter text-info" style="cursor: pointer;"></i>
                                        <i class="fab fa-whatsapp text-success" style="cursor: pointer;"></i>
                                    </div>
                                </div>
                            </div>
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
            let allVariants = @json($variants && $variants->count() > 0 ? $variants->values() : []);
            let availableOptions = @json($variantOptions ?? []);
            
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
                    resetVariantDisplay();
                    return;
                }
                
                const exactVariant = findExactVariant();
                
                if (exactVariant) {
                    priceDisplay.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(exactVariant.price)}`;
                    updateVariantDisplay(exactVariant);
                } else if (variants.length === 1) {
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
                    stockElement.textContent = `Stok: ${@json($parentProduct->productInventory ? $parentProduct->productInventory->qty : 0)}`;
                }
            }
            
            function updateCartButton() {
                const hasVariants = @json($parentProduct->type) === 'configurable' && @json($variants->count()) > 0;
                
                if (hasVariants) {
                    const selectedCount = Object.keys(selectedAttributes).length;
                    const exactVariant = findExactVariant();
                    const isComplete = exactVariant !== null;
                    
                    addToCartBtn.disabled = !isComplete;
                    
                    if (isComplete) {
                        addToCartBtn.innerHTML = '<i class="fa fa-shopping-bag me-2"></i>Tambah ke Keranjang';
                        addToCartBtn.classList.remove('btn-secondary');
                        addToCartBtn.classList.add('btn-primary');
                    } else if (selectedCount === 0) {
                        addToCartBtn.innerHTML = '<i class="fa fa-info-circle me-2"></i>Pilih varian untuk melanjutkan';
                        addToCartBtn.classList.remove('btn-primary');
                        addToCartBtn.classList.add('btn-secondary');
                    } else {
                        addToCartBtn.innerHTML = '<i class="fa fa-info-circle me-2"></i>Kombinasi varian tidak tersedia';
                        addToCartBtn.classList.remove('btn-primary');
                        addToCartBtn.classList.add('btn-secondary');
                    }
                }
            }
            
            function findExactVariant() {
                if (Object.keys(selectedAttributes).length === 0) {
                    return null;
                }
                
                return allVariants.find(variant => {
                    return Object.entries(selectedAttributes).every(([attrName, attrValue]) => {
                        return variant.variant_attributes.some(attr => 
                            attr.attribute_name === attrName && attr.attribute_value === attrValue
                        );
                    });
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
                
                if (@json($parentProduct->type) === 'configurable' && @json($variants->count()) > 0) {
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

            // --- Simple sticky positioning with dynamic navbar detection ---
            function adjustStickySummaryTop() {
                try {
                    const summary = document.querySelector('.summary-card.sticky-card');
                    if (!summary) return;

                    // Skip on mobile
                    if (window.innerWidth <= 991) return;

                    // Find navbar/header elements
                    const navbar = document.querySelector('.navbar') || document.querySelector('header') || document.querySelector('.site-header');
                    const breadcrumb = document.querySelector('.page-header');

                    let offset = 120; // default safe offset
                    
                    // Calculate safe distance below navbar
                    if (navbar) {
                        const navRect = navbar.getBoundingClientRect();
                        offset = Math.max(120, navRect.height + 30); // 30px safe margin
                    }
                    
                    // Add breadcrumb height if exists
                    if (breadcrumb) {
                        const br = breadcrumb.getBoundingClientRect();
                        offset += Math.max(0, br.height);
                    }

                    // Apply sticky top - this makes it follow scroll naturally
                    summary.style.top = offset + 'px';
                    summary.style.position = 'sticky';
                    summary.style.zIndex = '500'; // Safe z-index below navbar
                    
                    // Remove any fixed positioning classes
                    summary.classList.remove('fixed-panel');
                    summary.classList.add('sticky-card');
                    
                } catch (e) {
                    console.warn('adjustStickySummaryTop failed', e);
                }
            }

            // Simple initialization - just set sticky positioning correctly
            function initializeStickyBehavior() {
                adjustStickySummaryTop();
            }

            // Event listeners for responsive behavior
            window.addEventListener('load', initializeStickyBehavior);
            window.addEventListener('resize', () => {
                setTimeout(adjustStickySummaryTop, 100);
            });
            
            // Re-adjust after potential layout changes
            setTimeout(initializeStickyBehavior, 300);
            setTimeout(initializeStickyBehavior, 600);
            setTimeout(initializeStickyBehavior, 1000);
        });
    </script>
@endsection
