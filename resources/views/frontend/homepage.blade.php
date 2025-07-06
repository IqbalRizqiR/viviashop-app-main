@extends('frontend.layouts')
@section('content')
<style>
    .hero-header {
        background: linear-gradient(rgba(248, 223, 173, 0.1), rgba(248, 223, 173, 0.1)), url('{{ asset('atkah.jpg') }}') !important;
        background-position: center !important;
        background-size: cover !important;
        background-repeat: no-repeat !important;

    }
</style>
    <!-- Hero Start -->
    <div class="container-fluid py-5 mb-5 hero-header">
        <div class="container py-5">
            <div class="row g-5 align-items-center">
                <div class="col-md-12 col-lg-7">
                    <h4 class="mb-3 text-white">Support Printing Terbaik</h4>
                    <h1 class="mb-5 display-3 text-white">Menerima jasa printing dalam segala bentuk halaman</h1>
                </div>
                <div class="col-md-12 col-lg-5">
                    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            @foreach ($slides as $key)
                                {{--  <?php echo $key; ?>  --}}
                                <button type="button" data-bs-target="#carouselExampleIndicators"
                                    data-bs-slide-to="{{ $loop->index }}" class="active"></button>
                            @endforeach
                        </div>
                        <div class="carousel-inner">
                            @foreach ($slides as $key => $images)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $images->path) }}" class="d-block w-100" alt="...">
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
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="featurs-item text-center rounded bg-light p-4">
                        <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                            <i class="fas fa-car-side fa-3x text-white"></i>
                        </div>
                        <div class="featurs-content text-center">
                            <h5>Free Shipping</h5>
                            <p class="mb-0">Free on order over $300</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="featurs-item text-center rounded bg-light p-4">
                        <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                            <i class="fas fa-user-shield fa-3x text-white"></i>
                        </div>
                        <div class="featurs-content text-center">
                            <h5>Security Payment</h5>
                            <p class="mb-0">100% security payment</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="featurs-item text-center rounded bg-light p-4">
                        <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                            <i class="fas fa-exchange-alt fa-3x text-white"></i>
                        </div>
                        <div class="featurs-content text-center">
                            <h5>Revision Warranty</h5>
                            <p class="mb-0">Free revision warranty if needed</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="featurs-item text-center rounded bg-light p-4">
                        <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                            <i class="fa fa-phone-alt fa-3x text-white"></i>
                        </div>
                        <div class="featurs-content text-center">
                            <h5>24/7 Support</h5>
                            <p class="mb-0">Support every time fast</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Featurs Section End -->


    <!-- Fruits Shop Start-->
    <div class="container-fluid fruite py-5">
        <div class="container py-5">
            <div class="tab-class text-center">
                <div class="row g-4">
                    <div class="col-lg-4 text-start">
                        <h1>Our Organic Products</h1>
                    </div>
                    <div class="col-lg-8 text-end">
                        <ul class="nav nav-pills mb-4">
                            <li class="nav-item">
                                <a class="nav-link bg-success text-white" href="{{ route('shop') }}">
                                    Lihat Semua
                                </a>
                            </li>
                    </div>
                </div>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane fade show p-0 active">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <div class="row g-4">
                                    @foreach ($products as $row)
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="rounded position-relative fruite-item">
                                                <div class="fruite-img">
                                                    @php
                                                        $image = !empty($row->products->productImages->first())
                                                            ? asset(
                                                                'storage/' .
                                                                    $row->products->productImages->first()->path,
                                                            )
                                                            : asset('images/placeholder.jpg');
                                                    @endphp
                                                    <img src="{{ $image }}" class="img-fluid w-100 rounded-top"
                                                        alt="">
                                                </div>
                                                <div class="text-white bg-secondary px-3 py-1 rounded position-absolute"
                                                    style="top: 10px; left: 10px;">{{ $row->categories->name }}</div>
                                                <div class="p-3 border border-secondary border-top-0 rounded-bottom">
                                                    <a href="{{ route('shop-detail', $row->products->id) }}">
                                                        <h4>{{ $row->products->name }}</h4>
                                                    </a>
                                                    <b>{{ $row->products->short_description }}</b>
                                                    @if ($row->products->productInventory != null)
                                                        <p>Stok : {{ $row->products->productInventory->qty }}</p>
                                                    @endif
                                                    <div class="d-flex justify-content-center flex-lg-wrap">
                                                        <p class="text-dark fs-5 fw-bold mb-2">Rp.
                                                            {{ number_format($row->products->price) }}</p>
                                                        <a class="btn border add-to-card border-secondary rounded-pill px-3 text-primary"
                                                            href="" product-id="{{ $row->products->id }}"
                                                            product-type="{{ $row->products->type }}"
                                                            product-slug="{{ $row->products->slug }}">
                                                            <i class="fa fa-shopping-bag me-2 text-primary"></i>
                                                            Add to cart
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
        <!-- Fruits Shop End-->


        <!-- Featurs Start -->
        <div class="container-fluid service py-5">
            <div class="container py-5">
                <div class="row g-4 justify-content-center">
                    <div class="col-md-6 col-lg-4">
                        <a href="#">
                            <div class="service-item bg-secondary rounded border border-secondary">
                                <img src="img/featur-1.jpg" class="img-fluid rounded-top w-100" alt="">
                                <div class="px-4 rounded-bottom">
                                    <div class="service-content bg-primary text-center p-4 rounded">
                                        <h5 class="text-white">READY</h5>
                                        <h3 class="mb-0">ATK LENGKAP</h3>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <a href="#">
                            <div class="service-item bg-dark rounded border border-dark">
                                <img src="img/featur-2.jpg" class="img-fluid rounded-top w-100" alt="">
                                <div class="px-4 rounded-bottom">
                                    <div class="service-content bg-light text-center p-4 rounded">
                                        <h5 class="text-primary">Banner Printing</h5>
                                        <h3 class="mb-0">Free delivery</h3>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <a href="#">
                            <div class="service-item bg-primary rounded border border-primary">
                                <img src="img/featur-3.jpg" class="img-fluid rounded-top w-100" alt="">
                                <div class="px-4 rounded-bottom">
                                    <div class="service-content bg-secondary text-center p-4 rounded">
                                        <h5 class="text-white">MELAYANI</h5>
                                        <h3 class="mb-0">CETAK BUKU</h3>
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
        <div class="container-fluid banner bg-secondary my-5">
            <div class="container py-5">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-6">
                        <div class="py-4">
                            <h1 class="display-3 text-white">Follow Sosial Media Kami</h1>
                            <p class="fw-normal display-3 text-dark mb-4">in Our Store</p>
                            <p class="mb-4 text-dark">Kunjungi Instagram kami untuk mendapatkan penawaran serta harga
                                potongan khusus.</p>
                            <a href="#"
                                class="banner-btn btn border-2 border-white rounded-pill text-dark py-3 px-5">BUY</a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <blockquote class="instagram-media"
                            data-instgrm-permalink="https://www.instagram.com/vivia_printshop/" data-instgrm-version="12"
                            style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:540px; min-width:326px; padding:0; width:99.375%; width:undefinedpx;height:undefinedpx;max-height:100%; width:undefinedpx;">
                            <div style="padding:16px;">
                                <a id="main_link" href="vivia_printshop/"
                                    style=" background:#FFFFFF; line-height:0; padding:0 0; text-align:center; text-decoration:none; width:100%;"
                                    target="_blank">
                                    <div style=" display: flex; flex-direction: row; align-items: center;">
                                        <div
                                            style="background-color: #F4F4F4; border-radius: 50%; flex-grow: 0; height: 40px; margin-right: 14px; width: 40px;">
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
                                            style=" color:#3897f0; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:550; line-height:18px;">
                                            View this post on Instagram</div>
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
                                        target="_blank">Shared post</a> on <time
                                        style=" font-family:Arial,sans-serif; font-size:14px; line-height:17px;">Time</time>
                                </p>
                            </div>
                        </blockquote>
                        <script src="https://www.instagram.com/embed.js"></script>
                    </div>
                    <style>
                        .boxes3 {
                            height: 175px;
                            width: 153px;
                        }

                        #n img {
                            max-height: none !important;
                            max-width: none !important;
                            background: none !important
                        }

                        #inst i {
                            max-height: none !important;
                            max-width: none !important;
                            background: none !important
                        }
                    </style>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- Banner Section End -->

        <!-- Katalog Section Start -->
    <div class="container-fluid py-5">
      <div class="container py-5">
        <div class="row g-5 align-items-center">
          <div class="col-lg-6">
            <div class="text-center text-lg-start">
              <h1 class="display-4">Katalog Produk Kami</h1>
              <p class="mb-4">Jelajahi rangkaian lengkap produk kami. Anda dapat melihatnya langsung di sini atau mengunduhnya dalam format PDF untuk referensi Anda di lain waktu.</p>
              <a href="https://drive.google.com/uc?export=download&id=1G3sq9BUgN4RaRBgVOs6iTSASHrYHB6Ij" class="btn btn-primary rounded-pill py-3 px-5" target="_blank"> <i class="fa fa-download me-2"></i> Download Katalog </a>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="card shadow-lg">
              <div class="card-body p-2">
                <div class="embed-responsive embed-responsive-16by9" style="height: 600px">
                  <iframe class="embed-responsive-item w-100 h-100" src="https://drive.google.com/file/d/1G3sq9BUgN4RaRBgVOs6iTSASHrYHB6Ij/preview?usp=sharing" allow="autoplay"> </iframe>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Katalog Section End -->

        <!-- Stats Section -->
        <div class="row mt-5">
          <div class="col-12">
            <div class="bg-white rounded-4 shadow-sm p-4">
              <div class="row text-center g-4">
                <div class="col-md-3">
                  <div class="border-end border-2">
                    <h3 class="text-primary mb-1">500+</h3>
                    <p class="text-muted mb-0">Produk Tersedia</p>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="border-end border-2">
                    <h3 class="text-success mb-1">50+</h3>
                    <p class="text-muted mb-0">Kategori Produk</p>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="border-end border-2">
                    <h3 class="text-warning mb-1">1000+</h3>
                    <p class="text-muted mb-0">Pelanggan Puas</p>
                  </div>
                </div>
                <div class="col-md-3">
                  <h3 class="text-info mb-1">24/7</h3>
                  <p class="text-muted mb-0">Layanan Support</p>
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
            <h1 class="display-4 text-primary">Kunjungi Toko Kami</h1>
            <p class="lead">Temukan lokasi fisik VIVIA PrintShop dan rasakan pengalaman berbelanja langsung</p>
        </div>
        
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <div class="bg-white rounded-4 shadow-lg p-4 h-100">
                    <h3 class="text-primary mb-4">
                        <i class="fas fa-store me-2"></i>
                        Informasi Toko
                    </h3>
                    
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 rounded-3" style="background-color: #f8f9fa;">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 65px; height: 50px;">
                                    <i class="fas fa-map-marker-alt text-white fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-primary fw-bold">Alamat Lengkap</h6>
                                    <p class="mb-0 text-muted">VIVIA PrintShop<br>Tebu Ireng IV Nomor 38, Cukir, Kec. Diwek, Kabupaten Jombang, Jawa Timur 61471</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 rounded-3" style="background-color: #f8f9fa;">
                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-clock text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-success fw-bold">Jam Operasional</h6>
                                    <p class="mb-0 text-muted small">Senin - Sabtu<br>08:00 - 17:00 WIB</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 rounded-3" style="background-color: #f8f9fa;">
                                <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-phone text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-warning fw-bold">Kontak</h6>
                                    <p class="mb-0 text-muted small">+62 123 456 789</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 rounded-3" style="background-color: #f8f9fa;">
                                <div class="bg-info rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-envelope text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-info fw-bold">Email</h6>
                                    <p class="mb-0 text-muted small">info@viviashop.com</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 rounded-3" style="background-color: #f8f9fa;">
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="fab fa-whatsapp text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-secondary fw-bold">WhatsApp</h6>
                                    <p class="mb-0 text-muted small">+62 812 3456 7890</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="https://maps.app.goo.gl/adKMKhPKJUWahDeF7" target="_blank" class="btn btn-primary rounded-pill py-3 px-5 me-3">
                            <i class="fas fa-directions me-2"></i>
                            Petunjuk Arah
                        </a>
                        <a href="https://wa.me/6281234567890" target="_blank" class="btn btn-success rounded-pill py-3 px-5">
                            <i class="fab fa-whatsapp me-2"></i>
                            Chat WhatsApp
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="bg-white rounded-4 shadow-lg overflow-hidden h-100">
                    <div class="bg-primary text-white text-center py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            Lokasi VIVIA PrintShop
                        </h5>
                        <small>Klik pada peta untuk melihat detail lokasi</small>
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
                               class="btn btn-sm btn-light shadow-sm rounded-circle" 
                               title="Buka di Google Maps"
                               style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Additional Info Section -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="bg-primary rounded-4 p-4 text-white text-center">
                    <div class="row g-4 align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-2">
                                <i class="fas fa-car me-2"></i>
                                Mudah Dijangkau dengan Kendaraan
                            </h4>
                            <p class="mb-0">Lokasi strategis dengan akses mudah, dekat dengan pusat religi Jombang</p>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex justify-content-center">
                                <div class="bg-white rounded-circle p-3 me-3">
                                    <i class="fas fa-car text-primary fa-2x"></i>
                                </div>
                                <div class="bg-white rounded-circle p-3">
                                    <i class="fas fa-motorcycle text-primary fa-2x"></i>
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
