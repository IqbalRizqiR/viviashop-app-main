@extends('frontend.layouts')
@section('content')
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Shop</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Pages</a></li>
            <li class="breadcrumb-item active text-white">Shop</li>
        </ol>
    </div>

    <div class="container-fluid fruite py-5">
        <div class="container py-5">
            <h1 class="mb-4">Katalog Produk</h1>
            <div class="row g-4">
                <div class="col-lg-12">
                    <div class="row g-4">
                        <div class="col-xl-3">
                            <form action="{{ route('shop') }}" method="GET">
                            <div class="input-group w-100 mx-auto d-flex">
                                    <input type="text" name="search" class="form-control p-3" placeholder="keywords" aria-describedby="search-icon-1">
                                    <button type="submit" id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></button>
                                </div>
                            </form>
                        </div>
                        <div class="col-xl-9 d-flex justify-content-end">
                            @if (request()->has('search'))
                                <a href="{{ route('shop') }}" class="btn btn-info text-white text-center">Search Reset</a>
                            @endif
                            @if (Request::is('shopCategory*'))
                                <a href="{{ route('shop') }}" class="btn btn-info text-white text-center">Semua Produk</a>
                            @endif
                        </div>
                    </div>
                    <div class="row g-4 mt-5">
                        <div class="col-lg-3">
                            <div class="row g-4">
                                <div class="col-lg-12">
                                    <div class="mb-5">
                                        <h4>Categories</h4>
                                        <ul class="list-unstyled fruite-categorie">
                                            @foreach ($categories as $item)
                                                <li>
                                                    <div class="d-flex justify-content-between fruite-name">
                                                        <a href="{{ route('shopCategory', $item->slug) }}" class="fw-bold">{{ $item->name }}</a>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="row g-4 justify-content-center">
                                @forelse ($products as $row)
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <div class="rounded position-relative fruite-item">
                                        @if (request()->has('search'))
                                            <div class="fruite-img">
                                                @php
                                                    $image = !empty($row->products->productImages->first()) ? asset('storage/'.$row->products->productImages->first()->path) : asset('images/placeholder.jpg');
                                                @endphp
                                                <img src="{{ $image }}" class="img-fluid w-100 rounded-top" alt="">
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">{{ $row->categories->name }}</div>
                                            <div class="p-3 border border-secondary border-top-0 rounded-bottom">
                                                <a href="{{ route('shop-detail', $row->products->id) }}"><h4>{{ $row->products->name }}</h4></a>
                                                <b style="color: black; font-weight: bold;">{{ $row->products->short_description }}</b>
                                                @if ($row->products->productInventory != null)
                                                    <p class="mt-4">Stok : {{ $row->products->productInventory->qty }}</p>
                                                @endif
                                                <div class="d-flex justify-content-center flex-lg-wrap">
                                                    <p class="text-dark fs-5 fw-bold mb-2">Rp. {{ number_format($row->products->price) }}</p>
                                                    <a href="" class="btn border border-secondary rounded-pill px-3 text-primary add-to-card" product-id="{{ $row->products->id }}" product-type="{{ $row->products->type }}" product-slug="{{ $row->products->slug }}"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                                </div>
                                            </div>
                                        @else
                                            <div class="fruite-img">
                                                @php
                                                    $image = !empty($row->products->productImages->first()) ? asset('storage/'.$row->products->productImages->first()->path) : asset('images/placeholder.jpg');
                                                @endphp
                                                <img src="{{ $image }}" class="img-fluid w-100 rounded-top" alt="">
                                                    {{--  <img src="{{ asset('storage/'.$row->products->productImages->first()->path) }}" class="img-fluid w-100 rounded-top" alt="">  --}}
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">{{ $row->categories->name }}</div>
                                            <div class="p-3 border border-secondary border-top-0 rounded-bottom">
                                                <a href="{{ route('shop-detail', $row->products->id) }}"><h4>{{ $row->products->name }}</h4></a>
                                            <b style="color: black; font-weight: bold;">{{ $row->products->short_description }}</b>
                                            @if ($row->products->productInventory != null)
                                                <p class="mt-4">Stok : {{ $row->products->productInventory->qty }}</p>
                                            @endif
                                            <div class="d-flex justify-content-center flex-lg-wrap">
                                                <p class="text-dark fs-5 fw-bold mb-2">Rp. {{ number_format($row->products->price) }}</p>
                                                <a href="" class="btn border border-secondary rounded-pill px-3 text-primary add-to-card" product-id="{{ $row->products->id }}" product-type="{{ $row->products->type }}" product-slug="{{ $row->products->slug }}"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                            </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @empty
                                    <div class="col-md-12">
                                        <div class="alert alert-danger text-center">
                                            <h4>Data tidak ditemukan</h4>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
