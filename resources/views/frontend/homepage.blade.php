@extends('frontend.layouts')
@section('content')
<style>
    .hero-header {
        background: linear-gradient(135deg, rgba(13, 110, 253, 0.8), rgba(25, 135, 84, 0.7)), url('{{ asset('atkah.jpg') }}') !important;
        background-position: center !important;
        background-size: cover !important;
        background-repeat: no-repeat !important;
        position: relative;
        overflow: hidden;
    }
    
    .hero-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(13, 110, 253, 0.1) 0%, rgba(25, 135, 84, 0.1) 50%, rgba(255, 193, 7, 0.1) 100%);
        z-index: 1;
    }
    
    .hero-content {
        position: relative;
        z-index: 2;
    }
    
    .hero-title {
        background: linear-gradient(45deg, #fff, #f8f9fa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    
    .floating-animation {
        animation: floating 3s ease-in-out infinite;
    }
    
    @keyframes floating {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    .carousel-item img {
        border-radius: 15px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        transition: transform 0.3s ease;
    }
    
    .carousel-item:hover img {
        transform: scale(1.02);
    }
    
    .featurs-item {
        transition: all 0.4s ease;
        border: 1px solid transparent;
        position: relative;
        overflow: hidden;
    }
    
    .featurs-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        transition: left 0.6s;
    }
    
    .featurs-item:hover::before {
        left: 100%;
    }
    
    .featurs-item:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        border-color: var(--bs-primary);
    }
    
    .featurs-icon {
        transition: all 0.3s ease;
    }
    
    .featurs-item:hover .featurs-icon {
        transform: scale(1.1) rotate(5deg);
    }
    
    .fruite-item {
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .fruite-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }
    
    .fruite-img img {
        transition: transform 0.3s ease;
    }
    
    .fruite-item:hover .fruite-img img {
        transform: scale(1.05);
    }
    
    .service-item {
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .service-item:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }
    
    .service-item img {
        transition: transform 0.3s ease;
    }
    
    .service-item:hover img {
        transform: scale(1.1);
    }
    
    .banner {
        background: linear-gradient(135deg, var(--bs-secondary) 0%, var(--bs-primary) 100%);
        position: relative;
        overflow: hidden;
    }
    
    .banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }
    
    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .text-gradient {
        background: linear-gradient(45deg, var(--bs-primary), var(--bs-success));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .btn-gradient {
        background: linear-gradient(45deg, var(--bs-primary), var(--bs-success));
        border: none;
        transition: all 0.3s ease;
    }
    
    .btn-gradient:hover {
        background: linear-gradient(45deg, var(--bs-success), var(--bs-primary));
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
    
    .location-card {
        transition: all 0.3s ease;
    }
    
    .location-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    
    .stats-counter {
        transition: all 0.3s ease;
    }
    
    .stats-counter:hover {
        transform: scale(1.05);
    }
</style>
    <!-- Hero Start -->
    <div class="container-fluid py-5 mb-5 hero-header">
        <div class="container py-5 hero-content">
            <div class="row g-5 align-items-center">
                <div class="col-md-12 col-lg-7">
                    <div class="floating-animation">
                        <h4 class="mb-3 text-white fw-bold">
                            <i class="fas fa-print me-2"></i>
                            Support Printing Terbaik
                        </h4>
                        <h1 class="mb-4 display-3 hero-title fw-bold">
                            Menerima Jasa Printing dalam Segala Bentuk Halaman
                        </h1>
                        <p class="lead text-white-50 mb-4">
                            Solusi lengkap untuk kebutuhan percetakan Anda dengan kualitas terbaik dan pelayanan profesional
                        </p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="{{ route('shop') }}" class="btn btn-gradient btn-lg rounded-pill px-4 py-3 text-white fw-bold">
                                <i class="fas fa-shopping-cart me-2"></i>
                                Lihat Produk
                            </a>
                            <a href="{{ route('shopCetak') }}" class="btn btn-outline-light btn-lg rounded-pill px-4 py-3 fw-bold">
                                <i class="fas fa-print me-2"></i>
                                Layanan Cetak
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-5">
                    <div id="carouselExampleIndicators" class="carousel slide floating-animation" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            @foreach ($slides as $key)
                                <button type="button" data-bs-target="#carouselExampleIndicators"
                                    data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}"></button>
                            @endforeach
                        </div>
                        <div class="carousel-inner">
                            @foreach ($slides as $key => $images)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $images->path) }}" class="d-block w-100" alt="Slide {{ $key + 1 }}">
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hero End -->


    <!-- Featurs Section Start -->
    <div class="container-fluid featurs py-5">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="display-5 text-gradient fw-bold">Keunggulan Layanan Kami</h2>
                <p class="lead text-muted">Mengapa memilih VIVIA PrintShop? Karena kami memberikan yang terbaik!</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="featurs-item text-center rounded-4 bg-white shadow-sm p-4 h-100">
                        <div class="featurs-icon btn-square rounded-circle bg-primary mb-4 mx-auto">
                            <i class="fas fa-shipping-fast fa-3x text-white"></i>
                        </div>
                        <div class="featurs-content text-center">
                            <h5 class="text-primary fw-bold mb-3">Pengiriman Gratis</h5>
                            <p class="text-muted mb-0">Gratis ongkir untuk pembelian diatas Rp 300.000</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="featurs-item text-center rounded-4 bg-white shadow-sm p-4 h-100">
                        <div class="featurs-icon btn-square rounded-circle bg-success mb-4 mx-auto">
                            <i class="fas fa-shield-alt fa-3x text-white"></i>
                        </div>
                        <div class="featurs-content text-center">
                            <h5 class="text-success fw-bold mb-3">Pembayaran Aman</h5>
                            <p class="text-muted mb-0">100% keamanan pembayaran terjamin</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="featurs-item text-center rounded-4 bg-white shadow-sm p-4 h-100">
                        <div class="featurs-icon btn-square rounded-circle bg-warning mb-4 mx-auto">
                            <i class="fas fa-redo-alt fa-3x text-white"></i>
                        </div>
                        <div class="featurs-content text-center">
                            <h5 class="text-warning fw-bold mb-3">Garansi Revisi</h5>
                            <p class="text-muted mb-0">Garansi revisi gratis jika diperlukan</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="featurs-item text-center rounded-4 bg-white shadow-sm p-4 h-100">
                        <div class="featurs-icon btn-square rounded-circle bg-info mb-4 mx-auto">
                            <i class="fas fa-headset fa-3x text-white"></i>
                        </div>
                        <div class="featurs-content text-center">
                            <h5 class="text-info fw-bold mb-3">Dukungan 24/7</h5>
                            <p class="text-muted mb-0">Dukungan pelanggan setiap saat dengan respon cepat</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Featurs Section End -->


    <!-- Fruits Shop Start-->
    <div class="container-fluid fruite py-5 bg-light">
        <div class="container py-5">
            <div class="tab-class text-center">
                <div class="row g-4 mb-5">
                    <div class="col-lg-6 text-start">
                        <h2 class="display-5 text-gradient fw-bold">Produk Unggulan Kami</h2>
                        <p class="lead text-muted">Temukan berbagai produk berkualitas tinggi untuk kebutuhan Anda</p>
                    </div>
                    <div class="col-lg-6 text-end d-flex align-items-center justify-content-end">
                        <a class="btn btn-gradient btn-lg rounded-pill px-4 py-3 text-white fw-bold" href="{{ route('shop') }}">
                            <i class="fas fa-eye me-2"></i>
                            Lihat Semua Produk
                        </a>
                    </div>
                </div>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane fade show p-0 active">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <div class="row g-4">
                                    @foreach ($products as $row)
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="rounded-4 position-relative fruite-item bg-white shadow-sm overflow-hidden h-100">
                                                <div class="fruite-img position-relative">
                                                    @php
                                                        $image = !empty($row->products->productImages->first())
                                                            ? asset(
                                                                'storage/' .
                                                                    $row->products->productImages->first()->path,
                                                            )
                                                            : asset('images/placeholder.jpg');
                                                    @endphp
                                                    <img src="{{ $image }}" class="img-fluid w-100 rounded-top"
                                                        alt="{{ $row->products->name }}" style="height: 200px; object-fit: cover;">
                                                    <div class="position-absolute top-0 start-0 m-3">
                                                        <span class="badge bg-primary text-white px-3 py-2 rounded-pill">
                                                            {{ $row->categories->name }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="p-4 d-flex flex-column">
                                                    <a href="{{ route('shop-detail', $row->products->id) }}" class="text-decoration-none">
                                                        <h5 class="text-dark fw-bold mb-2">{{ $row->products->name }}</h5>
                                                    </a>
                                                    <p class="text-muted mb-3 flex-grow-1">{{ $row->products->short_description }}</p>
                                                    @if ($row->products->productInventory != null)
                                                        <div class="mb-3">
                                                            <small class="text-success fw-bold">
                                                                <i class="fas fa-check-circle me-1"></i>
                                                                Stok: {{ $row->products->type == 'configurable' ? $row->products->total_stock : ($row->products->productInventory->qty ?? 0) }} tersedia
                                                            </small>
                                                        </div>
                                                    @endif
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <span class="text-primary fs-5 fw-bold">
                                                                Rp {{ number_format($row->products->price) }}
                                                            </span>
                                                        </div>
                                                        <a class="btn btn-outline-primary rounded-pill px-3 py-2 add-to-card"
                                                            href="#" 
                                                            product-id="{{ $row->products->id }}"
                                                            product-type="{{ $row->products->type }}"
                                                            product-slug="{{ $row->products->slug }}"
                                                            title="Tambah ke keranjang">
                                                            <i class="fas fa-cart-plus me-1"></i>
                                                            <span class="d-none d-lg-inline">Tambah</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <!-- Fruits Shop End-->


        <!-- Featurs Start -->
        <div class="container-fluid service py-5">
            <div class="container py-5">
                <div class="text-center mb-5">
                    <h2 class="display-5 text-gradient fw-bold">Layanan Unggulan</h2>
                    <p class="lead text-muted">Jelajahi berbagai layanan terbaik yang kami tawarkan</p>
                </div>
                <div class="row g-4 justify-content-center">
                    <div class="col-md-6 col-lg-4">
                        <a href="#" class="text-decoration-none">
                            <div class="service-item bg-gradient-primary rounded-4 border-0 overflow-hidden shadow-lg">
                                <img src="img/featur-1.jpg" class="img-fluid w-100" alt="ATK Lengkap" style="height: 200px; object-fit: cover;">
                                <div class="p-4">
                                    <div class="service-content bg-white text-center p-4 rounded-3">
                                        <div class="mb-3">
                                            <i class="fas fa-check-circle text-success fa-2x"></i>
                                        </div>
                                        <h5 class="text-success fw-bold mb-2">SIAP TERSEDIA</h5>
                                        <h4 class="text-primary fw-bold mb-0">ATK LENGKAP</h4>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <a href="#" class="text-decoration-none">
                            <div class="service-item bg-gradient-secondary rounded-4 border-0 overflow-hidden shadow-lg">
                                <img src="img/featur-2.jpg" class="img-fluid w-100" alt="Banner Printing" style="height: 200px; object-fit: cover;">
                                <div class="p-4">
                                    <div class="service-content bg-white text-center p-4 rounded-3">
                                        <div class="mb-3">
                                            <i class="fas fa-truck text-warning fa-2x"></i>
                                        </div>
                                        <h5 class="text-warning fw-bold mb-2">Banner Printing</h5>
                                        <h4 class="text-dark fw-bold mb-0">GRATIS PENGIRIMAN</h4>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <a href="#" class="text-decoration-none">
                            <div class="service-item bg-gradient-success rounded-4 border-0 overflow-hidden shadow-lg">
                                <img src="img/featur-3.jpg" class="img-fluid w-100" alt="Cetak Buku" style="height: 200px; object-fit: cover;">
                                <div class="p-4">
                                    <div class="service-content bg-white text-center p-4 rounded-3">
                                        <div class="mb-3">
                                            <i class="fas fa-book text-primary fa-2x"></i>
                                        </div>
                                        <h5 class="text-primary fw-bold mb-2">PROFESIONAL</h5>
                                        <h4 class="text-success fw-bold mb-0">CETAK BUKU</h4>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Featurs End -->


        <!-- Banner Section Start-->
        <div class="container-fluid banner my-5 position-relative">
            <div class="container py-5">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6">
                        <div class="py-4 position-relative" style="z-index: 2;">
                            <div class="mb-4">
                                <span class="badge bg-light text-primary fs-6 px-3 py-2 rounded-pill mb-3">
                                    <i class="fab fa-instagram me-2"></i>
                                    Sosial Media
                                </span>
                            </div>
                            <h1 class="display-4 text-white fw-bold mb-4">
                                Ikuti Sosial Media Kami
                            </h1>
                            <p class="fs-5 text-white-50 mb-4">
                                Kunjungi Instagram kami untuk mendapatkan penawaran menarik serta informasi promo khusus yang tidak ingin Anda lewatkan!
                            </p>
                            <div class="d-flex flex-wrap gap-3">
                                <a href="https://www.instagram.com/vivia_printshop/" target="_blank"
                                    class="btn btn-light btn-lg rounded-pill px-4 py-3 fw-bold">
                                    <i class="fab fa-instagram me-2 text-primary"></i>
                                    Kunjungi Instagram
                                </a>
                                <a href="{{ route('shop') }}"
                                    class="btn btn-outline-light btn-lg rounded-pill px-4 py-3 fw-bold">
                                    <i class="fas fa-shopping-cart me-2"></i>
                                    Belanja Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="bg-white rounded-4 shadow-lg p-4 position-relative" style="z-index: 2;">
                            <div class="text-center mb-3">
                                <h5 class="text-primary fw-bold mb-2">
                                    <i class="fab fa-instagram me-2"></i>
                                    @vivia_printshop
                                </h5>
                                <p class="text-muted small mb-0">Ikuti untuk update terbaru!</p>
                            </div>
                            <blockquote class="instagram-media"
                                data-instgrm-permalink="https://www.instagram.com/vivia_printshop/" data-instgrm-version="12"
                                style=" background:#FFF; border:0; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.1); margin: 1px; max-width:540px; min-width:326px; padding:0; width:100%;">
                                <div style="padding:16px;">
                                    <a id="main_link" href="vivia_printshop/"
                                        style=" background:#FFFFFF; line-height:0; padding:0 0; text-align:center; text-decoration:none; width:100%;"
                                        target="_blank">
                                        <div style=" display: flex; flex-direction: row; align-items: center;">
                                            <div
                                                style="background: linear-gradient(45deg, #405DE6, #5851DB, #833AB4, #C13584, #E1306C, #FD1D1D); border-radius: 50%; flex-grow: 0; height: 40px; margin-right: 14px; width: 40px;">
                                            </div>
                                            <div
                                                style="display: flex; flex-direction: column; flex-grow: 1; justify-content: center;">
                                                <div
                                                    style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; margin-bottom: 6px; width: 100px;">
                                                </div>
                                                <div
                                                    style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; width: 60px;">
                                                </div>
                                            </div>
                                        </div>
                                        <div style="padding: 19% 0;"></div>
                                        <div style="display:block; height:50px; margin:0 auto 12px; width:50px;">
                                            <svg width="50px" height="50px" viewBox="0 0 60 60" version="1.1"
                                                xmlns="https://www.w3.org/2000/svg"
                                                xmlns:xlink="https://www.w3.org/1999/xlink">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <g transform="translate(-511.000000, -20.000000)" fill="#000000">
                                                        <g>
                                                            <path
                                                                d="M556.869,30.41 C554.814,30.41 553.148,32.076 553.148,34.131 C553.148,36.186 554.814,37.852 556.869,37.852 C558.924,37.852 560.59,36.186 560.59,34.131 C560.59,32.076 558.924,30.41 556.869,30.41 M541,60.657 C535.114,60.657 530.342,55.887 530.342,50 C530.342,44.114 535.114,39.342 541,39.342 C546.887,39.342 551.658,44.114 551.658,50 C551.658,55.887 546.887,60.657 541,60.657 M541,33.886 C532.1,33.886 524.886,41.1 524.886,50 C524.886,58.899 532.1,66.113 541,66.113 C549.9,66.113 557.115,58.899 557.115,50 C557.115,41.1 549.9,33.886 541,33.886 M565.378,62.101 C565.244,65.022 564.756,66.606 564.346,67.663 C563.803,69.06 563.154,70.057 562.106,71.106 C561.058,72.155 560.06,72.803 558.662,73.347 C557.607,73.757 556.021,74.244 553.102,74.378 C549.944,74.521 548.997,74.552 541,74.552 C533.003,74.552 532.056,74.521 528.898,74.378 C525.979,74.244 524.393,73.757 523.338,73.347 C521.94,72.803 520.942,72.155 519.894,71.106 C518.846,70.057 518.197,69.06 517.654,67.663 C517.244,66.606 516.755,65.022 516.623,62.101 C516.479,58.943 516.448,57.996 516.448,50 C516.448,42.003 516.479,41.056 516.623,37.899 C516.755,34.978 517.244,33.391 517.654,32.338 C518.197,30.938 518.846,29.942 519.894,28.894 C520.942,27.846 521.94,27.196 523.338,26.654 C524.393,26.244 525.979,25.756 528.898,25.623 C532.057,25.479 533.004,25.448 541,25.448 C548.997,25.448 549.943,25.479 553.102,25.623 C556.021,25.756 557.607,26.244 558.662,26.654 C560.06,27.196 561.058,27.846 562.106,28.894 C563.154,29.942 563.803,30.938 564.346,32.338 C564.756,33.391 565.244,34.978 565.378,37.899 C565.522,41.056 565.552,42.003 565.552,50 C565.552,57.996 565.522,58.943 565.378,62.101 M570.82,37.631 C570.674,34.438 570.167,32.258 569.425,30.349 C568.659,28.377 567.633,26.702 565.965,25.035 C564.297,23.368 562.623,22.342 560.652,21.575 C558.743,20.834 556.562,20.326 553.369,20.18 C550.169,20.033 549.148,20 541,20 C532.853,20 531.831,20.033 528.631,20.18 C525.438,20.326 523.257,20.834 521.349,21.575 C519.376,22.342 517.703,23.368 516.035,25.035 C514.368,26.702 513.342,28.377 512.574,30.349 C511.834,32.258 511.326,34.438 511.181,37.631 C511.035,40.831 511,41.851 511,50 C511,58.147 511.035,59.17 511.181,62.369 C511.326,65.562 511.834,67.743 512.574,69.651 C513.342,71.625 514.368,73.296 516.035,74.965 C517.703,76.634 519.376,77.658 521.349,78.425 C523.257,79.167 525.438,79.673 528.631,79.82 C531.831,79.965 532.853,80.001 541,80.001 C549.148,80.001 550.169,79.965 553.369,79.82 C556.562,79.673 558.743,79.167 560.652,78.425 C562.623,77.658 564.297,76.634 565.965,74.965 C567.633,73.296 568.659,71.625 569.425,69.651 C570.167,67.743 570.674,65.562 570.82,62.369 C570.966,59.17 571,58.147 571,50 C571,41.851 570.966,40.831 570.82,37.631">
                                                            </path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </svg>
                                        </div>
                                        <div style="padding-top: 8px;">
                                            <div
                                                style=" color:#3897f0; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:550; line-height:18px; text-align: center;">
                                                Lihat postingan ini di Instagram</div>
                                        </div>
                                        <div style="padding: 12.5% 0;"></div>
                                        <div
                                            style="display: flex; flex-direction: row; margin-bottom: 14px; align-items: center;">
                                            <div>
                                                <div
                                                    style="background-color: #F4F4F4; border-radius: 50%; height: 12.5px; width: 12.5px; transform: translateX(0px) translateY(7px);">
                                                </div>
                                                <div
                                                    style="background-color: #F4F4F4; height: 12.5px; transform: rotate(-45deg) translateX(3px) translateY(1px); width: 12.5px; flex-grow: 0; margin-right: 14px; margin-left: 2px;">
                                                </div>
                                                <div
                                                    style="background-color: #F4F4F4; border-radius: 50%; height: 12.5px; width: 12.5px; transform: translateX(9px) translateY(-18px);">
                                                </div>
                                            </div>
                                            <div style="margin-left: 8px;">
                                                <div
                                                    style=" background-color: #F4F4F4; border-radius: 50%; flex-grow: 0; height: 20px; width: 20px;">
                                                </div>
                                                <div
                                                    style=" width: 0; height: 0; border-top: 2px solid transparent; border-left: 6px solid #f4f4f4; border-bottom: 2px solid transparent; transform: translateX(16px) translateY(-4px) rotate(30deg)">
                                                </div>
                                            </div>
                                            <div style="margin-left: auto;">
                                                <div
                                                    style=" width: 0px; border-top: 8px solid #F4F4F4; border-right: 8px solid transparent; transform: translateY(16px);">
                                                </div>
                                                <div
                                                    style=" background-color: #F4F4F4; flex-grow: 0; height: 12px; width: 16px; transform: translateY(-4px);">
                                                </div>
                                                <div
                                                    style=" width: 0; height: 0; border-top: 8px solid #F4F4F4; border-left: 8px solid transparent; transform: translateY(-4px) translateX(8px);">
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            style="display: flex; flex-direction: column; flex-grow: 1; justify-content: center; margin-bottom: 24px;">
                                            <div
                                                style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; margin-bottom: 6px; width: 224px;">
                                            </div>
                                            <div
                                                style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; width: 144px;">
                                            </div>
                                        </div>
                                    </a>
                                    <p
                                        style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; line-height:17px; margin-bottom:0; margin-top:8px; overflow:hidden; padding:8px 0 7px; text-align:center; text-overflow:ellipsis; white-space:nowrap;">
                                        <a href="vivia_printshop/"
                                            style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px; text-decoration:none;"
                                            target="_blank">Postingan bersama</a> pada <time
                                            style=" font-family:Arial,sans-serif; font-size:14px; line-height:17px;">Instagram</time>
                                    </p>
                                </div>
                            </blockquote>
                            <script async src="https://www.instagram.com/embed.js"></script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Banner Section End -->

        <!-- Katalog Section Start -->
    <div class="container-fluid py-5 bg-light">
      <div class="container py-5">
        <div class="row g-5 align-items-center">
          <div class="col-lg-6">
            <div class="text-center text-lg-start">
              <div class="mb-4">
                <span class="badge bg-primary text-white fs-6 px-3 py-2 rounded-pill mb-3">
                  <i class="fas fa-book me-2"></i>
                  Katalog Digital
                </span>
              </div>
              <h2 class="display-5 text-gradient fw-bold mb-4">Katalog Produk Lengkap Kami</h2>
              <p class="lead text-muted mb-4">
                Jelajahi rangkaian lengkap produk kami dalam format yang mudah diakses. Anda dapat melihat katalog langsung di browser atau mengunduhnya dalam format PDF untuk referensi kapan saja.
              </p>
              <div class="d-flex flex-wrap gap-3">
                <a href="https://drive.google.com/uc?export=download&id=1G3sq9BUgN4RaRBgVOs6iTSASHrYHB6Ij" 
                   class="btn btn-gradient btn-lg rounded-pill px-4 py-3 text-white fw-bold" target="_blank">
                  <i class="fas fa-download me-2"></i> 
                  Download Katalog PDF
                </a>
                <a href="{{ route('shop') }}" class="btn btn-outline-primary btn-lg rounded-pill px-4 py-3 fw-bold">
                  <i class="fas fa-store me-2"></i>
                  Lihat Toko Online
                </a>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
              <div class="card-header bg-primary text-white text-center py-3">
                <h5 class="mb-0 fw-bold">
                  <i class="fas fa-file-pdf me-2"></i>
                  Preview Katalog Produk VIVIA PrintShop
                </h5>
                <small>Klik untuk melihat detail atau scroll untuk menjelajahi</small>
              </div>
              <div class="card-body p-2">
                <div class="embed-responsive embed-responsive-16by9" style="height: 600px">
                  <iframe class="embed-responsive-item w-100 h-100 rounded" 
                          src="https://drive.google.com/file/d/1G3sq9BUgN4RaRBgVOs6iTSASHrYHB6Ij/preview?usp=sharing" 
                          allow="autoplay"
                          style="border: none;">
                  </iframe>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Katalog Section End -->

        <!-- Stats Section -->
        <div class="container my-5">
          <div class="row">
            <div class="col-12">
              <div class="bg-white rounded-4 shadow-lg p-5">
                <div class="text-center mb-4">
                  <h3 class="text-gradient fw-bold">Statistik Pencapaian Kami</h3>
                  <p class="text-muted">Angka yang membuktikan kepercayaan pelanggan terhadap layanan kami</p>
                </div>
                <div class="row text-center g-4">
                  <div class="col-md-3">
                    <div class="stats-counter p-3 rounded-3" style="background: linear-gradient(135deg, #007bff1a, #007bff0a);">
                      <div class="mb-3">
                        <i class="fas fa-box text-primary fa-2x"></i>
                      </div>
                      <h3 class="text-primary fw-bold mb-1">500+</h3>
                      <p class="text-muted mb-0 fw-semibold">Produk Tersedia</p>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="stats-counter p-3 rounded-3" style="background: linear-gradient(135deg, #198c541a, #198c540a);">
                      <div class="mb-3">
                        <i class="fas fa-tags text-success fa-2x"></i>
                      </div>
                      <h3 class="text-success fw-bold mb-1">50+</h3>
                      <p class="text-muted mb-0 fw-semibold">Kategori Produk</p>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="stats-counter p-3 rounded-3" style="background: linear-gradient(135deg, #ffc1071a, #ffc1070a);">
                      <div class="mb-3">
                        <i class="fas fa-smile text-warning fa-2x"></i>
                      </div>
                      <h3 class="text-warning fw-bold mb-1">1000+</h3>
                      <p class="text-muted mb-0 fw-semibold">Pelanggan Puas</p>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="stats-counter p-3 rounded-3" style="background: linear-gradient(135deg, #0dcaf01a, #0dcaf00a);">
                      <div class="mb-3">
                        <i class="fas fa-clock text-info fa-2x"></i>
                      </div>
                      <h3 class="text-info fw-bold mb-1">24/7</h3>
                      <p class="text-muted mb-0 fw-semibold">Layanan Support</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    <!-- Katalog Section End -->

<!-- Location Section Start -->
<div class="container-fluid py-5 bg-light">
    <div class="container py-5">
        <div class="text-center mb-5">
            <span class="badge bg-primary text-white fs-6 px-3 py-2 rounded-pill mb-3">
                <i class="fas fa-map-marker-alt me-2"></i>
                Lokasi Toko
            </span>
            <h2 class="display-5 text-gradient fw-bold mb-3">Kunjungi Toko Fisik Kami</h2>
            <p class="lead text-muted">Temukan lokasi VIVIA PrintShop dan rasakan pengalaman berbelanja langsung dengan pelayanan terbaik</p>
        </div>
        
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <div class="bg-white rounded-4 shadow-lg p-4 h-100 location-card">
                    <div class="text-center mb-4">
                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-store text-white fa-2x"></i>
                        </div>
                        <h3 class="text-primary fw-bold">Informasi Toko</h3>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="d-flex align-items-start p-4 rounded-4 location-card" style="background: linear-gradient(135deg, #007bff1a, #007bff0a);">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-4 flex-shrink-0" style="width: 60px; height: 60px;">
                                    <i class="fas fa-map-marker-alt text-white fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="text-primary fw-bold mb-2">Alamat Lengkap</h6>
                                    <p class="mb-0 text-muted lh-base">
                                        <strong>VIVIA PrintShop</strong><br>
                                        Tebu Ireng IV Nomor 38, Cukir<br>
                                        Kec. Diwek, Kabupaten Jombang<br>
                                        Jawa Timur 61471
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 rounded-3 location-card" style="background: linear-gradient(135deg, #198c541a, #198c540a);">
                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-clock text-white"></i>
                                </div>
                                <div>
                                    <h6 class="text-success fw-bold mb-1">Jam Operasional</h6>
                                    <p class="mb-0 text-muted small">
                                        <strong>Senin - Sabtu</strong><br>
                                        08:00 - 17:00 WIB
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 rounded-3 location-card" style="background: linear-gradient(135deg, #ffc1071a, #ffc1070a);">
                                <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-phone text-white"></i>
                                </div>
                                <div>
                                    <h6 class="text-warning fw-bold mb-1">Telepon</h6>
                                    <p class="mb-0 text-muted small">+62 123 456 789</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 rounded-3 location-card" style="background: linear-gradient(135deg, #0dcaf01a, #0dcaf00a);">
                                <div class="bg-info rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-envelope text-white"></i>
                                </div>
                                <div>
                                    <h6 class="text-info fw-bold mb-1">Email</h6>
                                    <p class="mb-0 text-muted small">info@viviashop.com</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 rounded-3 location-card" style="background: linear-gradient(135deg, #20c9971a, #20c9970a);">
                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="fab fa-whatsapp text-white"></i>
                                </div>
                                <div>
                                    <h6 class="text-success fw-bold mb-1">WhatsApp</h6>
                                    <p class="mb-0 text-muted small">+62 812 3456 7890</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <div class="d-flex flex-wrap gap-3 justify-content-center">
                            <a href="https://maps.app.goo.gl/adKMKhPKJUWahDeF7" target="_blank" 
                               class="btn btn-gradient btn-lg rounded-pill px-4 py-3 text-white fw-bold">
                                <i class="fas fa-directions me-2"></i>
                                Petunjuk Arah
                            </a>
                            <a href="https://wa.me/6281234567890" target="_blank" 
                               class="btn btn-outline-success btn-lg rounded-pill px-4 py-3 fw-bold">
                                <i class="fab fa-whatsapp me-2"></i>
                                Chat WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="bg-white rounded-4 shadow-lg overflow-hidden h-100 location-card">
                    <div class="bg-gradient text-white text-center py-4" style="background: linear-gradient(135deg, var(--bs-primary), var(--bs-success));">
                        <h5 class="mb-2 fw-bold">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            Lokasi VIVIA PrintShop
                        </h5>
                        <p class="mb-0 small opacity-75">Klik pada peta untuk melihat detail lokasi dan mendapatkan petunjuk arah</p>
                    </div>
                    <div class="position-relative">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3954.6902460456313!2d112.2357296745512!3d-7.608646375209187!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7841556bd5c5bb%3A0x4517452691764b02!2sVIVIA%20PrintShop!5e0!3m2!1sid!2sid!4v1751760890529!5m2!1sid!2sid"
                            width="100%" 
                            height="450" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                        <div class="position-absolute top-0 end-0 m-3">
                            <a href="https://maps.app.goo.gl/adKMKhPKJUWahDeF7" target="_blank" 
                               class="btn btn-light shadow rounded-circle d-flex align-items-center justify-content-center" 
                               title="Buka di Google Maps"
                               style="width: 45px; height: 45px;">
                                <i class="fas fa-external-link-alt text-primary"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Additional Info Section -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="rounded-4 p-5 text-white text-center position-relative overflow-hidden" 
                     style="background: linear-gradient(135deg, var(--bs-primary), var(--bs-success));">
                    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-25" 
                         style="background: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><circle cx=\"50\" cy=\"50\" r=\"40\" fill=\"none\" stroke=\"white\" stroke-width=\"1\" opacity=\"0.3\"/></svg>') repeat;"></div>
                    <div class="row g-4 align-items-center position-relative">
                        <div class="col-md-8">
                            <div class="text-start text-md-start">
                                <h3 class="fw-bold mb-3">
                                    <i class="fas fa-car me-3"></i>
                                    Mudah Dijangkau dengan Segala Kendaraan
                                </h3>
                                <p class="mb-0 lead">
                                    Lokasi strategis dengan akses mudah dari berbagai arah, dekat dengan pusat religi Jombang dan mudah dijangkau dengan kendaraan pribadi maupun transportasi umum.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex justify-content-center gap-3">
                                <div class="bg-white bg-opacity-25 rounded-circle p-4 location-card">
                                    <i class="fas fa-car text-white fa-3x"></i>
                                </div>
                                <div class="bg-white bg-opacity-25 rounded-circle p-4 location-card">
                                    <i class="fas fa-motorcycle text-white fa-3x"></i>
                                </div>
                                <div class="bg-white bg-opacity-25 rounded-circle p-4 location-card">
                                    <i class="fas fa-walking text-white fa-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Location Section End -->

    @push('script-alt')
        <script>
            $('.change-the-class').click(function(e) {
                var idAddress = $('.class-address').attr('id');
                $('.class-change').attr('id', idAddress);
            });
        </script>
    @endpush
@endsection
