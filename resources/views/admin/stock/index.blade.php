@extends('layouts.app')

@section('content')
<section class="content pt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Kartu Stok Global</h3>
                        <div class="float-right">
                            <a href="{{ route('admin.stock.movements') }}" class="btn btn-info btn-sm">
                                <i class="fa fa-list"></i> Lihat Semua Pergerakan
                            </a>
                            <a href="{{ route('admin.stock.report') }}" class="btn btn-success btn-sm">
                                <i class="fa fa-chart-bar"></i> Laporan Stok
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <label for="per_page" class="form-label mr-2 mb-0">Tampilkan:</label>
                                    <select id="per_page" class="form-control form-control-sm" style="width: auto;" onchange="changePerPage(this.value)">
                                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                        <option value="12" {{ request('per_page', 12) == 12 ? 'selected' : '' }}>12</option>
                                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                                        <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30</option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                    </select>
                                    <span class="ml-2 text-muted">kartu per halaman</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-right">
                                    <small class="text-muted">
                                        Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            @forelse($products as $product)
                                <div class="col-lg-6 col-xl-4 mb-4">
                                    <div class="card border-left-primary shadow h-100 py-2">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                        {{ $product->name }}
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="text-xs mb-1">
                                                                <strong>SKU:</strong> {{ $product->sku }}
                                                            </div>
                                                            <div class="text-xs mb-1">
                                                                <strong>Tipe:</strong> 
                                                                @if($product->type == 'simple')
                                                                    <span class="badge badge-primary badge-sm">Simple</span>
                                                                @else
                                                                    <span class="badge badge-info badge-sm">Config</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="text-xs mb-1">
                                                                <strong>Variants:</strong> {{ $product->productVariants->count() }}
                                                            </div>
                                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                                Stok: 
                                                                @if($product->productVariants->count() > 0)
                                                                    {{ $product->productVariants->sum('stock') }}
                                                                @elseif($product->productInventory)
                                                                    {{ $product->productInventory->qty }}
                                                                @else
                                                                    0
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    @if($product->productVariants->count() > 0)
                                                        <div class="mt-2">
                                                            <div class="text-xs font-weight-bold text-secondary mb-1">Stok per Variant:</div>
                                                            <div class="row">
                                                                @foreach($product->productVariants->take(4) as $variant)
                                                                    <div class="col-6 mb-1">
                                                                        <div class="text-xs bg-light p-1 rounded">
                                                                            <strong>
                                                                                @if($variant->variantAttributes->count() > 0)
                                                                                    {{ Str::limit($variant->variantAttributes->pluck('attribute_value')->implode(', '), 15) }}
                                                                                @else
                                                                                    Default
                                                                                @endif
                                                                            </strong>
                                                                            <br>
                                                                            <span class="text-success">{{ $variant->stock }}</span>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                                @if($product->productVariants->count() > 4)
                                                                    <div class="col-12">
                                                                        <div class="text-xs text-muted">
                                                                            +{{ $product->productVariants->count() - 4 }} variant lainnya
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                    
                                                    <div class="mt-3">
                                                        <a href="{{ route('admin.stock.product', $product->id) }}" class="btn btn-info btn-sm btn-block">
                                                            <i class="fa fa-chart-line"></i> Lihat Kartu Stok
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-box fa-2x text-gray-300"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-info text-center">
                                        <i class="fa fa-info-circle"></i> Tidak ada data produk
                                    </div>
                                </div>
                            @endforelse
                        </div>
                        
                        @if($products->hasPages())
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-center mb-4">
                                        {{ $products->appends(request()->query())->links() }}
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Tabel Lengkap Stok Produk</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="stock-table" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Produk</th>
                                                        <th>SKU</th>
                                                        <th>Tipe</th>
                                                        <th>Total Variants</th>
                                                        <th>Total Stok</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($products as $product)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $product->name }}</td>
                                                            <td>{{ $product->sku }}</td>
                                                            <td>
                                                                @if($product->type == 'simple')
                                                                    <span class="badge badge-primary">Simple</span>
                                                                @else
                                                                    <span class="badge badge-info">Configurable</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ $product->productVariants->count() }}</td>
                                                            <td>
                                                                @if($product->productVariants->count() > 0)
                                                                    {{ $product->productVariants->sum('stock') }}
                                                                @elseif($product->productInventory)
                                                                    {{ $product->productInventory->qty }}
                                                                @else
                                                                    0
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('admin.stock.product', $product->id) }}" class="btn btn-sm btn-info" title="Lihat Kartu Stok">
                                                                    <i class="fa fa-chart-line"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="7" class="text-center">Tidak ada data produk</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
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

@push('style-alt')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css">
@endpush

@push('script-alt')
<script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $("#stock-table").DataTable({
        "order": [[ 5, "desc" ]],
        "pageLength": 25
    });
});

function changePerPage(perPage) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', perPage);
    url.searchParams.delete('page');
    window.location.href = url.toString();
}
</script>
@endpush