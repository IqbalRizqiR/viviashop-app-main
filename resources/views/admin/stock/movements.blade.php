@extends('layouts.app')

@section('content')
<section class="content pt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-history text-info"></i>
                            Semua Pergerakan Stok
                        </h3>
                        <div class="float-right">
                            <a href="{{ route('admin.stock.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fa fa-arrow-left"></i> Kembali ke Kartu Stok
                            </a>
                            <a href="{{ route('admin.stock.report') }}" class="btn btn-success btn-sm">
                                <i class="fa fa-chart-bar"></i> Laporan Stok
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <div class="h4">{{ $movements->where('movement_type', 'in')->count() }}</div>
                                        <div class="text-xs">Total Transaksi Masuk</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-danger text-white">
                                    <div class="card-body text-center">
                                        <div class="h4">{{ $movements->where('movement_type', 'out')->count() }}</div>
                                        <div class="text-xs">Total Transaksi Keluar</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <div class="h4">{{ $movements->where('movement_type', 'in')->sum('quantity') }}</div>
                                        <div class="text-xs">Total Unit Masuk</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <div class="h4">{{ $movements->where('movement_type', 'out')->sum('quantity') }}</div>
                                        <div class="text-xs">Total Unit Keluar</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-left-info">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-filter text-info"></i>
                                    Filter Pergerakan Stok
                                </h5>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="{{ route('admin.stock.movements') }}" class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="movement_type" class="form-label">Tipe Pergerakan</label>
                                        <select name="movement_type" id="movement_type" class="form-control">
                                            <option value="">Semua Tipe</option>
                                            <option value="in" {{ request('movement_type') == 'in' ? 'selected' : '' }}>Masuk</option>
                                            <option value="out" {{ request('movement_type') == 'out' ? 'selected' : '' }}>Keluar</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="reason" class="form-label">Alasan</label>
                                        <select name="reason" id="reason" class="form-control">
                                            <option value="">Semua Alasan</option>
                                            <option value="order_confirmed" {{ request('reason') == 'order_confirmed' ? 'selected' : '' }}>Order Confirmed</option>
                                            <option value="order_cancelled" {{ request('reason') == 'order_cancelled' ? 'selected' : '' }}>Order Cancelled</option>
                                            <option value="purchase_confirmed" {{ request('reason') == 'purchase_confirmed' ? 'selected' : '' }}>Purchase Confirmed</option>
                                            <option value="purchase_cancelled" {{ request('reason') == 'purchase_cancelled' ? 'selected' : '' }}>Purchase Cancelled</option>
                                            <option value="print_order" {{ request('reason') == 'print_order' ? 'selected' : '' }}>Print Order</option>
                                            <option value="manual_adjustment" {{ request('reason') == 'manual_adjustment' ? 'selected' : '' }}>Manual Adjustment</option>
                                            <option value="inventory_correction" {{ request('reason') == 'inventory_correction' ? 'selected' : '' }}>Inventory Correction</option>
                                            <option value="damage" {{ request('reason') == 'damage' ? 'selected' : '' }}>Damage</option>
                                            <option value="return" {{ request('reason') == 'return' ? 'selected' : '' }}>Return</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="date_from" class="form-label">Tanggal Dari</label>
                                        <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="date_to" class="form-label">Tanggal Sampai</label>
                                        <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-search"></i> Filter
                                        </button>
                                        <a href="{{ route('admin.stock.movements') }}" class="btn btn-secondary">
                                            <i class="fa fa-refresh"></i> Reset
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card border-left-primary mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-table text-primary"></i>
                                    Daftar Pergerakan Stok
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="movements-table" class="table table-bordered table-striped">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Produk & Variant</th>
                                                <th>Tipe</th>
                                                <th>Jumlah</th>
                                                <th>Stok Lama</th>
                                                <th>Stok Baru</th>
                                                <th>Alasan</th>
                                                <th>Referensi</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($movements as $movement)
                                                <tr>
                                                    <td>{{ $loop->iteration + ($movements->currentPage() - 1) * $movements->perPage() }}</td>
                                                    <td>
                                                        <small>{{ $movement->created_at->format('d/m/Y') }}</small><br>
                                                        <small class="text-muted">{{ $movement->created_at->format('H:i:s') }}</small>
                                                    </td>
                                                    <td>
                                                        <div class="mb-1">
                                                            <strong>{{ $movement->variant->product->name }}</strong>
                                                        </div>
                                                        @if($movement->variant && $movement->variant->variantAttributes->count() > 0)
                                                            <span class="badge badge-secondary">
                                                                {{ $movement->variant->variantAttributes->pluck('attribute_value')->implode(' • ') }}
                                                            </span>
                                                        @else
                                                            <span class="badge badge-light">Default</span>
                                                        @endif
                                                        <br>
                                                        <small class="text-muted">SKU: {{ $movement->variant->sku ?? 'N/A' }}</small>
                                                    </td>
                                                    <td>
                                                        @if($movement->movement_type == 'in')
                                                            <span class="badge badge-success badge-lg">
                                                                <i class="fa fa-arrow-up"></i> Masuk
                                                            </span>
                                                        @else
                                                            <span class="badge badge-danger badge-lg">
                                                                <i class="fa fa-arrow-down"></i> Keluar
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <strong class="h6">{{ $movement->quantity }}</strong>
                                                    </td>
                                                    <td class="text-center">{{ $movement->old_stock }}</td>
                                                    <td class="text-center">
                                                        <strong class="text-primary">{{ $movement->new_stock }}</strong>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-info">
                                                            {{ ucfirst(str_replace('_', ' ', $movement->reason)) }}
                                                        </span>
                                                        @if($movement->notes)
                                                            <br><small class="text-muted">{{ $movement->notes }}</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($movement->reference_type && $movement->reference_id)
                                                            <span class="badge badge-warning">
                                                                {{ ucfirst($movement->reference_type) }} #{{ $movement->reference_id }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.stock.product', $movement->variant->product->id) }}" class="btn btn-sm btn-info" title="Lihat Kartu Stok Produk">
                                                            <i class="fa fa-chart-line"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center text-muted">
                                                        <i class="fas fa-info-circle"></i>
                                                        Tidak ada pergerakan stok ditemukan
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                @if($movements->hasPages())
                                    <div class="d-flex justify-content-center mt-3">
                                        {{ $movements->links() }}
                                    </div>
                                @endif
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
<style>
.badge-lg {
    font-size: 0.85em;
    padding: 0.5em 0.75em;
}
</style>
@endpush

@push('script-alt')
<script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $("#movements-table").DataTable({
        "order": [[ 1, "desc" ]],
        "pageLength": 25,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.3/i18n/id.json"
        },
        "columnDefs": [
            { "orderable": false, "targets": [9] }
        ]
    });
});
</script>
@endpush