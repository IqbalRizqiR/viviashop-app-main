@extends('frontend.layouts')

@section('title', 'Katalog Produk - VIVIA PrintShop')
@section('meta-description', 'Jelajahi katalog lengkap produk VIVIA PrintShop. Temukan berbagai produk berkualitas untuk kebutuhan kantor, sekolah, dan percetakan dengan harga terbaik.')
@section('meta-keywords', 'vivia printshop, katalog produk, alat tulis kantor, ATK, percetakan, amplop, kertas, pulpen, produk kantor')

@section('content')
<style>
    .page-header {
        background: linear-gradient(135deg, #28a745 0%, #ffc107 100%);
        position: relative;
        overflow: hidden;
    }
    
    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></svg>') repeat;
        z-index: 1;
    }
    
    .page-header .container {
        position: relative;
        z-index: 2;
    }
    
    .search-container {
        background: transparent;
        border-radius: 12px;
        padding: 0;
        margin-bottom: 1rem;
    }
    
    .search-input-group {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        box-shadow: 0 6px 22px rgba(40, 167, 69, 0.08);
        border: 1px solid rgba(40,167,69,0.06);
    }
    
    .search-input {
        border: none;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        background: #fffef8;
        transition: all 0.25s ease;
    }
    
    .search-input:focus {
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(40,167,69,0.12);
    }
    
    .search-btn {
        background: #28a745;
        border: none;
        padding: 0.6rem 1rem;
        transition: all 0.18s ease;
        color: white;
        font-weight: 700;
        border-radius: 8px;
    }
    
    .search-btn:hover {
        transform: scale(1.05);
    }
    
    .sort-dropdown {
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }
    
    .sort-dropdown:focus {
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
    }
    
    .category-sidebar {
        background: #ffffff;
        border-radius: 14px;
        padding: 1rem 1rem 1.25rem 1rem;
        box-shadow: 0 10px 28px rgba(40,167,69,0.04);
        margin-bottom: 2rem;
        position: sticky;
        top: 110px;
        height: fit-content;
        max-height: calc(100vh - 130px);
        overflow-y: auto;
        z-index: 50;
        border: 1px solid rgba(40,167,69,0.06);
    }
    
    /* Custom scrollbar for category sidebar */
    .category-sidebar::-webkit-scrollbar {
        width: 6px;
    }
    
    .category-sidebar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .category-sidebar::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, var(--bs-primary), var(--bs-success));
        border-radius: 10px;
    }
    
    .category-sidebar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, var(--bs-success), var(--bs-primary));
    }
    
    .category-item {
        padding: 0.75rem 1rem;
        margin-bottom: 0.5rem;
        border-radius: 10px;
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    
    .category-item:hover {
        background: rgba(40,167,69,0.04);
        border-left-color: #ffc107;
        transform: translateX(4px);
    }
    
    .category-item.active {
        background: linear-gradient(90deg, #28a745, #ffc107);
        border-left-color: #ffc107;
    }
    
    .category-item.active a {
        color: white !important;
        font-weight: bold;
    }
    
    .product-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 12px 28px rgba(40,167,69,0.03);
        transition: all 0.28s ease;
        margin-bottom: 2rem;
        position: relative;
        border: 1px solid rgba(40,167,69,0.04);
    }
    
    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    
    .product-image {
        position: relative;
        overflow: hidden;
        height: 250px;
    }
    
    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    
    .product-card:hover .product-image img {
        transform: scale(1.1);
    }
    
    .product-category-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        background: #28a745;
        color: white;
        padding: 0.35rem 0.85rem;
        border-radius: 14px;
        font-size: 0.82rem;
        font-weight: 700;
        box-shadow: 0 6px 18px rgba(40,167,69,0.06);
        z-index: 2;
    }
    
    .product-stock-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: #28a745;
        color: white;
        padding: 0.35rem 0.7rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    
    .product-content {
        padding: 1.5rem;
    }
    
    .product-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        line-height: 1.3;
        transition: color 0.3s ease;
    }
    
    .product-title:hover {
        color: var(--bs-primary);
    }
    
    .product-description {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 1rem;
        line-height: 1.5;
    }
    
    .product-price {
        font-size: 1.25rem;
        font-weight: 800;
        color: #28a745;
        margin-bottom: 0.75rem;
    }
    
    .product-stock {
        display: inline-flex;
        align-items: center;
        color: var(--bs-success);
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    .btn-add-cart {
        background: linear-gradient(90deg, #28a745 0%, #28a745 100%);
        border: none;
        color: white;
        padding: 0.6rem 1rem;
        border-radius: 12px;
        font-weight: 700;
        transition: all 0.22s ease;
        width: 100%;
    }
    
    .btn-add-cart:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(40,167,69,0.18);
        color: #ffffff !important;
        background: linear-gradient(90deg, #2ecc71 0%, #28a745 100%);
    }
    
    .reset-btn, .view-all-btn {
        background: #ffc107;
        border: none;
        color: #0b2a1a;
        padding: 0.55rem 1rem;
        border-radius: 18px;
        font-weight: 700;
        transition: all 0.18s ease;
        text-decoration: none;
    }
    
    .reset-btn:hover, .view-all-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        color: white;
    }
    
    .no-products {
        text-align: center;
        padding: 3rem;
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    .no-products i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }
    
    .filter-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .filter-pill {
        background: #e9ecef;
        color: #495057;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .filter-pill:hover {
        background: var(--bs-primary);
        color: white;
        text-decoration: none;
    }
    
    .products-header {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .pagination .page-link {
        background: #ffffff;
        border: 1px solid rgba(40,167,69,0.08);
        color: #28a745;
        font-weight: 700;
        padding: 0.45rem 0.85rem;
        border-radius: 50px;
        transition: all 0.18s ease;
    }

    .pagination .page-item.active .page-link {
        background: #28a745;
        border-color: #28a745;
        color: #ffffff;
        box-shadow: 0 8px 20px rgba(40,167,69,0.12);
    }

    .pagination .page-link:hover {
        background: #28a745;
        color: #ffffff;
        border-color: #28a745;
    }

    /* Force pagination to be horizontal, compact and centered */
    .pagination {
        display: flex;
        flex-wrap: nowrap;
        gap: 0.5rem;
        align-items: center;
        justify-content: center;
        list-style: none;
        padding-left: 0;
        margin: 0.5rem 0;
    }

    .pagination .page-item {
        display: inline-block;
    }

    /* Small previous/next buttons as square pills */
    .pagination .page-item .page-link {
        min-width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }

    /* Active page stronger green */
    .pagination .page-item.active .page-link {
        background: #28a745;
        border-color: #28a745;
        color: #ffffff;
        box-shadow: 0 8px 20px rgba(40,167,69,0.18);
    }

    /* Disabled state subdued */
    .pagination .page-item.disabled .page-link {
        opacity: 0.45;
        cursor: default;
        background: #ffffff;
        color: rgba(40,167,69,0.45);
    }

    /* Responsive: allow wrapping on very small screens */
    @media (max-width: 420px) {
        .pagination { gap: 0.25rem; }
        .pagination .page-item .page-link { min-width: 34px; height: 34px; }
    }

    @media (max-width: 768px) {
        .product-image { height: 180px; }
        .product-title { font-size: 1.05rem; }
        .btn-add-cart { padding: 0.5rem; font-size: 0.95rem; }
        .search-input { padding: 0.6rem 0.8rem; }
        .pagination .page-link { padding: 0.35rem 0.6rem; font-size: 0.9rem; }
    }
    
    @media (max-width: 768px) {
        .product-card {
            margin-bottom: 1.5rem;
        }
        
        .search-container {
            padding: 1rem;
        }
        
        .category-sidebar {
            position: static !important;
            margin-bottom: 1rem;
            max-height: none;
            overflow-y: visible;
        }
    }
    
    @media (max-width: 992px) {
        .category-sidebar {
            top: 80px;
            max-height: calc(100vh - 100px);
        }
    }
</style>

    <div class="container-fluid page-header py-5">
        <div class="container">
            <div class="text-center">
                <h1 class="text-white display-4 fw-bold mb-3">
                    <i class="fas fa-store me-3"></i>
                    Katalog Produk
                </h1>
                <p class="text-white-50 lead">Temukan produk berkualitas untuk kebutuhan Anda</p>
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}" class="text-white-50">Home</a></li>
                    <li class="breadcrumb-item active text-white">Shop</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5">
        <div class="container">
            <!-- Search and Filter Section -->
            <div class="search-container">
                <div class="row g-4 align-items-center">
                    <div class="col-md-6">
                        <div class="">
                            <h5 class="mb-0 text-white-50 small">Telusuri produk dengan cepat</h5>
                            <small class="text-white-50">Gunakan pencarian di sidebar untuk akses cepat</small>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <form action="{{ route('shop') }}" method="GET" id="sortForm">
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            <label class="form-label fw-bold text-primary">
                                <i class="fas fa-sort me-2"></i>Urutkan:
                            </label>
                            <select name="sort" class="form-select sort-dropdown" onchange="document.getElementById('sortForm').submit()">
                                <option value="">Default</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Rendah</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tinggi</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                            </select>
                        </form>
                    </div>
                    
                    <div class="col-md-3 text-end">
                        <div class="d-flex gap-2 justify-content-end flex-wrap">
                            @if (request()->has('search'))
                                <a href="{{ route('shop') }}" class="reset-btn">
                                    <i class="fas fa-times me-2"></i>Reset Pencarian
                                </a>
                            @endif
                            @if (Request::is('shopCategory*'))
                                <a href="{{ route('shop') }}" class="view-all-btn">
                                    <i class="fas fa-th me-2"></i>Semua Produk
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Active Filters -->
                @if(request('search') || request('sort'))
                    <div class="mt-3">
                        <div class="d-flex align-items-center mb-2">
                            <span class="text-muted me-2">Filter aktif:</span>
                        </div>
                        <div class="filter-pills">
                            @if(request('search'))
                                <span class="filter-pill">
                                    <i class="fas fa-search me-1"></i>
                                    Pencarian: "{{ request('search') }}"
                                </span>
                            @endif
                            @if(request('sort'))
                                <span class="filter-pill">
                                    <i class="fas fa-sort me-1"></i>
                                    Urutkan: 
                                    @switch(request('sort'))
                                        @case('name_asc') Nama A-Z @break
                                        @case('name_desc') Nama Z-A @break
                                        @case('price_asc') Harga Rendah @break
                                        @case('price_desc') Harga Tinggi @break
                                        @case('newest') Terbaru @break
                                    @endswitch
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
            <!-- Main Content -->
            <div class="row g-4">
                <!-- Sidebar Categories -->
                <div class="col-lg-3">
                    <div class="category-sidebar">
                        <form action="{{ route('shop') }}" method="GET" class="mb-3">
                            @if(request('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                            @endif
                            <div class="search-input-group d-flex align-items-center">
                                <input type="text"
                                       name="search"
                                       class="form-control search-input"
                                       placeholder="Cari produk..."
                                       value="{{ request('search') }}"
                                       aria-label="Cari produk">
                                <button type="submit" class="btn search-btn ms-2">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <small class="text-muted d-block mt-2">Pencarian toleran terhadap typo</small>
                        </form>

                        <h4 class="text-primary fw-bold mb-3">
                            <i class="fas fa-list me-2"></i>
                            Kategori Produk
                        </h4>
                        <div class="category-list">
                            @php
                                $currentCategory = request()->route('slug') ?? '';
                            @endphp
                            @foreach ($categories as $item)
                                <div class="category-item {{ $currentCategory == $item->slug ? 'active' : '' }}">
                                    <a href="{{ route('shopCategory', $item->slug) }}" 
                                       class="text-decoration-none d-flex align-items-center justify-content-between">
                                        <span class="fw-semibold">
                                            <i class="fas fa-tag me-2"></i>
                                            {{ $item->name }}
                                        </span>
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Popular Categories Quick Links -->
                        <div class="mt-4 pt-4 border-top">
                            <h6 class="text-muted mb-3">Kategori Popular</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($categories->take(4) as $popular)
                                    <a href="{{ route('shopCategory', $popular->slug) }}" 
                                       class="badge bg-light text-primary text-decoration-none p-2 rounded-pill">
                                        {{ $popular->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Products Grid -->
                <div class="col-lg-9">
                    <!-- Products Header with Count -->
                    <div class="products-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="text-primary fw-bold mb-1">
                                    @if(request('search'))
                                        Hasil Pencarian: "{{ request('search') }}"
                                    @elseif(request()->route('slug'))
                                        Kategori: {{ $categories->where('slug', request()->route('slug'))->first()->name ?? 'Kategori' }}
                                    @else
                                        Semua Produk
                                    @endif
                                </h5>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-box me-1"></i>
                                    {{ $products->count() }} produk ditemukan
                                    @if(request('search'))
                                        <span class="ms-2 badge bg-info bg-opacity-10 text-info">
                                            <i class="fas fa-search me-1"></i>
                                            Termasuk hasil pencarian serupa
                                        </span>
                                    @endif
                                </p>
                            </div>
                            
                            <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="fas fa-th-large me-2"></i>
                                        <span class="small">Grid View</span>
                                    </div>
                                    <div class="text-muted small">
                                        <i class="fas fa-clock me-1"></i>
                                        Diperbarui hari ini
                                    </div>
                                    <form method="GET" class="ms-3">
                                        @foreach(request()->except('perPage') as $k => $v)
                                            @if(is_array($v))
                                                @foreach($v as $val)
                                                    <input type="hidden" name="{{ $k }}[]" value="{{ $val }}">
                                                @endforeach
                                            @else
                                                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                            @endif
                                        @endforeach
                                        <select name="perPage" class="form-select form-select-sm" onchange="this.form.submit()" aria-label="Hasil per halaman">
                                            @php $pp = (int) request('perPage', 12); @endphp
                                            <option value="6" {{ $pp==6 ? 'selected' : '' }}>6 / halaman</option>
                                            <option value="12" {{ $pp==12 ? 'selected' : '' }}>12 / halaman</option>
                                            <option value="24" {{ $pp==24 ? 'selected' : '' }}>24 / halaman</option>
                                            <option value="48" {{ $pp==48 ? 'selected' : '' }}>48 / halaman</option>
                                        </select>
                                    </form>
                                </div>
                        </div>
                    </div>
                    
                    <!-- Products Grid -->
                    <?php
                        use Illuminate\Pagination\LengthAwarePaginator;
                        $currentPage = request()->get('page', 1);
                        $perPage = (int) request('perPage', 12);
                        if (!in_array($perPage, [6,12,24,48])) { $perPage = 12; }
                        if ($products instanceof \Illuminate\Support\Collection) {
                            $items = $products->values()->all();
                            $total = $products->count();
                        } elseif (is_array($products)) {
                            $items = $products;
                            $total = count($items);
                        } elseif (is_object($products) && method_exists($products, 'all')) {
                            $items = $products->all();
                            $total = count($items);
                        } else {
                            $items = [];
                            $total = 0;
                        }
                        $offset = ($currentPage - 1) * $perPage;
                        $pagedData = array_slice($items, $offset, $perPage);
                        $paginator = new LengthAwarePaginator($pagedData, $total, $perPage, $currentPage, [
                            'path' => request()->url(),
                            'query' => request()->query()
                        ]);
                    ?>

                    <div class="row g-4">
                        @forelse ($paginator as $row)
                            <div class="col-md-6 col-lg-4">
                                <div class="product-card h-100">
                                    <div class="product-image">
                                        @php
                                            $image = !empty($row->products->productImages->first()) 
                                                ? asset('storage/'.$row->products->productImages->first()->path) 
                                                : asset('images/placeholder.jpg');
                                        @endphp
                                        <img src="{{ $image }}" alt="{{ $row->products->name }}">
                                        
                                        <!-- Category Badge -->
                                        <div class="product-category-badge">
                                            {{ $row->categories->name }}
                                        </div>
                                        
                                        <!-- Stock Badge -->
                                            @php $stock = $row->products->type == 'configurable' ? $row->products->total_stock : ($row->products->productInventory->qty ?? 0); @endphp
                                            @if ($stock > 0)
                                                <div class="product-stock-badge">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    {{ $stock }} tersedia
                                                </div>
                                        @endif
                                    </div>
                                    
                                    <div class="product-content d-flex flex-column">
                                        <a href="{{ route('shop-detail', $row->products->id) }}" class="text-decoration-none">
                                            <h5 class="product-title">{{ $row->products->name }}</h5>
                                        </a>
                                        
                                        <p class="product-description flex-grow-1">
                                            {{ Str::limit($row->products->short_description, 80) }}
                                        </p>
                                        
                                        @if ($row->products->productInventory)
                                            <div class="product-stock">
                                                <i class="fas fa-warehouse me-2"></i>
                                                Stok: {{ $row->products->productInventory->qty }}
                                            </div>
                                        @endif
                                        
                                        <div class="product-price">
                                            Rp {{ number_format($row->products->price, 0, ',', '.') }}
                                        </div>
                                        
                                        <button class="btn btn-add-cart add-to-card" 
                                                product-id="{{ $row->products->id }}" 
                                                product-type="{{ $row->products->type }}" 
                                                product-slug="{{ $row->products->slug }}"
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="Klik untuk menambahkan {{ $row->products->name }} ke keranjang"
                                                aria-label="Tambah {{ $row->products->name }} ke keranjang">
                                            <i class="fas fa-cart-plus me-2"></i>
                                            Tambah ke Keranjang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="no-products">
                                    <i class="fas fa-search"></i>
                                    <h4 class="text-muted mb-3">Produk Tidak Ditemukan</h4>
                                    <p class="text-muted mb-4">
                                        @if(request('search'))
                                            Maaf, tidak ada produk yang cocok dengan pencarian "{{ request('search') }}".
                                            <br>Coba gunakan kata kunci lain atau periksa ejaan.
                                        @else
                                            Tidak ada produk tersedia saat ini.
                                        @endif
                                    </p>
                                    @if(request('search'))
                                        <a href="{{ route('shop') }}" class="btn btn-primary rounded-pill px-4">
                                            <i class="fas fa-arrow-left me-2"></i>
                                            Lihat Semua Produk
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $paginator->links('pagination::bootstrap-5') }}
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-alt')
<script>
$(document).ready(function() {
    // Smooth loading animation
    $('.product-card').hide().each(function(index) {
        $(this).delay(100 * index).fadeIn(500);
    });
    
    // Search input enhancements
    let searchTimeout;
    $('input[name="search"]').on('input', function() {
        const $input = $(this);
        const $form = $input.closest('form');
        
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            // Show typing indicator (optional)
            $input.removeClass('is-invalid').addClass('is-valid');
        }, 300);
    });
    
    // Form submission loading state
    $('form').on('submit', function() {
        const $submitBtn = $(this).find('button[type="submit"]');
        const originalHtml = $submitBtn.html();
        
        $submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Mencari...')
                  .prop('disabled', true);
        
        // Re-enable after 5 seconds as fallback
        setTimeout(function() {
            $submitBtn.html(originalHtml).prop('disabled', false);
        }, 5000);
    });
    
    // Category hover effects
    $('.category-item').hover(
        function() {
            $(this).find('i').addClass('fa-chevron-right').removeClass('fa-chevron-right');
        },
        function() {
            $(this).find('i').removeClass('fa-chevron-right').addClass('fa-chevron-right');
        }
    );
    
    // Product card hover effects
    $('.product-card').hover(
        function() {
            $(this).find('.btn-add-cart').removeClass('btn-add-cart').addClass('btn-add-cart-hover');
        },
        function() {
            $(this).find('.btn-add-cart-hover').removeClass('btn-add-cart-hover').addClass('btn-add-cart');
        }
    );
    
    // Initialize tooltips if Bootstrap is available
    if (typeof bootstrap !== 'undefined') {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    
    // Smooth scroll to products when filtering
    if (window.location.search.includes('search=') || window.location.search.includes('sort=')) {
        $('html, body').animate({
            scrollTop: $('.products-header').offset().top - 100
        }, 800);
    }
});

// Add to cart functionality enhancement
$(document).on('click', '.add-to-card', function(e) {
    e.preventDefault();
    
    const $btn = $(this);
    const originalHtml = $btn.html();
    
    // Visual feedback
    $btn.html('<i class="fas fa-spinner fa-spin me-2"></i>Menambahkan...')
        .prop('disabled', true);
    
    // Simulate adding to cart (replace with actual functionality)
    setTimeout(function() {
        $btn.html('<i class="fas fa-check me-2"></i>Ditambahkan!')
            .removeClass('btn-add-cart')
            .addClass('btn-success');
        
        setTimeout(function() {
            $btn.html(originalHtml)
                .removeClass('btn-success')
                .addClass('btn-add-cart')
                .prop('disabled', false);
        }, 2000);
    }, 1000);
});
</script>

<style>
.btn-add-cart-hover {
    background: linear-gradient(90deg, #2ecc71 0%, #28a745 100%) !important;
    color: #ffffff !important;
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(40,167,69,0.18) !important;
    border: none !important;
}

.is-valid {
    border-color: var(--bs-success) !important;
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25) !important;
}

.search-input:focus.is-valid {
    border-color: var(--bs-success) !important;
    box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.1) !important;
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(13, 110, 253, 0); }
    100% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0); }
}

.btn-add-cart:focus {
    animation: pulse 1.5s infinite;
}

.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endpush
