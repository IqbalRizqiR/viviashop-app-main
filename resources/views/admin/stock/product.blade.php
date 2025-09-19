@extends('layouts.app')

@section('content')
<section class="content pt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Kartu Stok - {{ $product->name }}</h3>
                        <div class="float-right">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fa fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-lg-4">
                                <div class="card border-left-primary shadow h-100">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-2">
                                            Informasi Produk
                                        </div>
                                        <div class="mb-2">
                                            <strong>Nama:</strong><br>
                                            <span class="text-gray-800">{{ $product->name }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <strong>SKU:</strong><br>
                                            <span class="text-gray-600">{{ $product->sku }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <strong>Tipe:</strong><br>
                                            @if($product->type == 'simple')
                                                <span class="badge badge-primary">Simple Product</span>
                                            @else
                                                <span class="badge badge-info">Configurable Product</span>
                                            @endif
                                        </div>
                                        <div class="mb-2">
                                            <strong>Harga Jual:</strong><br>
                                            <span class="text-success h6">Rp {{ number_format($product->price) }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <strong>Harga Beli:</strong><br>
                                            <span class="text-info h6">Rp {{ number_format($product->harga_beli) }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <strong>Margin:</strong><br>
                                            @php
                                                $margin = $product->price - $product->harga_beli;
                                                $marginPercent = $product->harga_beli > 0 ? ($margin / $product->harga_beli) * 100 : 0;
                                            @endphp
                                            <span class="text-warning h6">
                                                Rp {{ number_format($margin) }} 
                                                ({{ number_format($marginPercent, 1) }}%)
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-8">
                                <div class="card border-left-success shadow h-100">
                                    <div class="card-body">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-2">
                                            Ringkasan Stok
                                        </div>
                                        @if($variants->count() > 0)
                                            <div class="row">
                                                <div class="col-12 mb-3">
                                                    <div class="alert alert-info">
                                                        <i class="fas fa-info-circle"></i>
                                                        <strong>Total Stok Keseluruhan: {{ $variants->sum('stock') }}</strong>
                                                        <span class="float-right">{{ $variants->count() }} Variant</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                @foreach($variants as $variant)
                                                    <div class="col-lg-6 col-md-12 mb-3">
                                                        <div class="card border-left-warning">
                                                            <div class="card-body p-3">
                                                                <div class="row no-gutters align-items-center">
                                                                    <div class="col mr-2">
                                                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                                            Variant {{ $loop->iteration }}
                                                                        </div>
                                                                        <div class="text-sm mb-1">
                                                                            @if($variant->variantAttributes->count() > 0)
                                                                                <strong>{{ $variant->variantAttributes->pluck('attribute_value')->implode(' • ') }}</strong>
                                                                            @else
                                                                                <strong>Default Variant</strong>
                                                                            @endif
                                                                        </div>
                                                                        <div class="text-xs text-gray-600 mb-1">
                                                                            SKU: {{ $variant->sku ?? 'N/A' }}
                                                                        </div>
                                                                        <div class="h5 mb-0 font-weight-bold">
                                                                            <span class="text-gray-800">{{ $variant->stock }}</span>
                                                                            <small class="text-muted">unit</small>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        @if($variant->stock > 10)
                                                                            <i class="fas fa-check-circle fa-2x text-success"></i>
                                                                        @elseif($variant->stock > 0)
                                                                            <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                                                                        @else
                                                                            <i class="fas fa-times-circle fa-2x text-danger"></i>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="alert alert-warning text-center">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                <strong>Tidak ada variant untuk produk ini</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-left-info shadow">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-history text-info"></i>
                                    Riwayat Pergerakan Stok
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($movements->count() > 0)
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <div class="card bg-primary text-white">
                                                <div class="card-body text-center">
                                                    <div class="h4">{{ $movements->where('movement_type', 'in')->count() }}</div>
                                                    <div class="text-xs">Transaksi Masuk</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card bg-danger text-white">
                                                <div class="card-body text-center">
                                                    <div class="h4">{{ $movements->where('movement_type', 'out')->count() }}</div>
                                                    <div class="text-xs">Transaksi Keluar</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card bg-success text-white">
                                                <div class="card-body text-center">
                                                    <div class="h4">{{ $movements->where('movement_type', 'in')->sum('quantity') }}</div>
                                                    <div class="text-xs">Total Masuk</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card bg-warning text-white">
                                                <div class="card-body text-center">
                                                    <div class="h4">{{ $movements->where('movement_type', 'out')->sum('quantity') }}</div>
                                                    <div class="text-xs">Total Keluar</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="movements-table">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Variant</th>
                                                <th>Tipe</th>
                                                <th>Jumlah</th>
                                                <th>Stok Lama</th>
                                                <th>Stok Baru</th>
                                                <th>Keterangan</th>
                                                <th>Referensi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($movements as $movement)
                                                <tr>
                                                    <td>
                                                        <small>{{ $movement->created_at->format('d/m/Y') }}</small><br>
                                                        <small class="text-muted">{{ $movement->created_at->format('H:i:s') }}</small>
                                                    </td>
                                                    <td>
                                                        @if($movement->variant && $movement->variant->variantAttributes->count() > 0)
                                                            <span class="badge badge-secondary">
                                                                {{ $movement->variant->variantAttributes->pluck('attribute_value')->implode(' • ') }}
                                                            </span>
                                                        @else
                                                            <span class="badge badge-light">Default</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($movement->movement_type == 'in')
                                                            <span class="badge badge-success">
                                                                <i class="fa fa-arrow-up"></i> Masuk
                                                            </span>
                                                        @else
                                                            <span class="badge badge-danger">
                                                                <i class="fa fa-arrow-down"></i> Keluar
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <strong>{{ $movement->quantity }}</strong>
                                                    </td>
                                                    <td>{{ $movement->old_stock }}</td>
                                                    <td>
                                                        <strong class="text-primary">{{ $movement->new_stock }}</strong>
                                                    </td>
                                                    <td>
                                                        <small>{{ $movement->reason }}</small>
                                                    </td>
                                                    <td>
                                                        @if($movement->reference_type && $movement->reference_id)
                                                            <span class="badge badge-info">
                                                                {{ ucfirst($movement->reference_type) }} #{{ $movement->reference_id }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center text-muted">
                                                        <i class="fas fa-info-circle"></i>
                                                        Belum ada pergerakan stok untuk produk ini
                                                    </td>
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
</section>
@endsection

@push('style-alt')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css">
@endpush

@push('script-alt')
<script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $("#movements-table").DataTable({
        "order": [[ 0, "desc" ]],
        "pageLength": 25,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.3/i18n/id.json"
        }
    });
});
</script>
@endpush