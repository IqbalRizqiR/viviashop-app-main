@extends('layouts.app')

@section('content')
<section class="content pt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-magic mr-2"></i>Smart Print Converter Tool
                        </h3>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary float-right">
                            <i class="fa fa-arrow-left"></i> Kembali ke Produk
                        </a>
                    </div>
                    
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h5><i class="icon fas fa-info-circle"></i> Tentang Tool Ini</h5>
                            Tool ini akan mengkonversi produk biasa menjadi <strong>Smart Print Product</strong> yang bisa muncul di 
                            <a href="{{ route('admin.print-service.stock') }}" target="_blank">Stock Management Print Service</a>.
                            <br><br>
                            <strong>Yang akan dilakukan:</strong>
                            <ul class="mb-0">
                                <li>✅ Set is_print_service = true</li>
                                <li>✅ Set is_smart_print_enabled = true</li>
                                <li>✅ Auto-create variants BW dan Color (jika belum ada)</li>
                                <li>✅ Produk langsung muncul di Stock Management</li>
                            </ul>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <h5><i class="icon fas fa-ban"></i> Error!</h5>
                                {{ session('error') }}
                            </div>
                        @endif

                        <!-- Search Form -->
                        <form method="GET" action="{{ route('admin.smart-print-converter.index') }}">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" 
                                               placeholder="Cari produk berdasarkan nama atau SKU..." 
                                               value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-search"></i> Cari
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <select name="filter" class="form-control" onchange="this.form.submit()">
                                        <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>Semua Produk</option>
                                        <option value="not_smart_print" {{ request('filter') == 'not_smart_print' ? 'selected' : '' }}>Belum Smart Print</option>
                                        <option value="already_smart_print" {{ request('filter') == 'already_smart_print' ? 'selected' : '' }}>Sudah Smart Print</option>
                                    </select>
                                </div>
                            </div>
                        </form>

                        <!-- Products Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">ID</th>
                                        <th width="20%">Nama Produk</th>
                                        <th width="15%">SKU</th>
                                        <th width="10%">Harga</th>
                                        <th width="15%">Status Smart Print</th>
                                        <th width="10%">Variants</th>
                                        <th width="25%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($products as $product)
                                        <tr>
                                            <td>{{ $product->id }}</td>
                                            <td>
                                                <strong>{{ $product->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                            </td>
                                            <td>
                                                <code>{{ $product->sku }}</code>
                                            </td>
                                            <td>
                                                @if($product->price)
                                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($product->is_print_service && $product->is_smart_print_enabled)
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check"></i> Smart Print
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">
                                                        <i class="fas fa-times"></i> Produk Biasa
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ $product->product_variants_count }} variants
                                                </span>
                                            </td>
                                            <td>
                                                @if($product->is_print_service && $product->is_smart_print_enabled)
                                                    <span class="text-success">
                                                        <i class="fas fa-check-circle"></i> Sudah Smart Print
                                                    </span>
                                                @else
                                                    <form method="POST" action="{{ route('admin.smart-print-converter.convert', $product->id) }}" 
                                                          style="display: inline-block;" 
                                                          onsubmit="return confirm('Yakin ingin convert {{ $product->name }} menjadi Smart Print Product?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-warning btn-sm">
                                                            <i class="fas fa-magic"></i> Convert to Smart Print
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                <a href="{{ route('admin.products.edit', $product->id) }}" 
                                                   class="btn btn-info btn-sm" target="_blank">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">
                                                <i class="fas fa-box-open fa-3x mb-3"></i>
                                                <br>Tidak ada produk ditemukan
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $products->appends(request()->query()) }}
                        </div>

                        <!-- Statistics -->
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3>{{ $stats['total'] }}</h3>
                                        <p>Total Produk</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-box"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>{{ $stats['smart_print'] }}</h3>
                                        <p>Smart Print Products</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-magic"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>{{ $stats['regular'] }}</h3>
                                        <p>Produk Biasa</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-box-open"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-refresh success message
    @if(session('success'))
        setTimeout(function() {
            $('.alert-success').fadeOut();
        }, 5000);
    @endif
});
</script>
@endpush