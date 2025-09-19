@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard Pemilik</h1>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>Rp {{ number_format($totalRevenue['today'], 0, ',', '.') }}</h3>
                        <p>Pendapatan Hari Ini</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>Rp {{ number_format($totalRevenue['month'], 0, ',', '.') }}</h3>
                        <p>Pendapatan Bulan Ini</p>
                        <small class="text-light">
                            @if($totalRevenue['growth_percentage'] >= 0)
                                <i class="fas fa-arrow-up"></i> {{ $totalRevenue['growth_percentage'] }}%
                            @else
                                <i class="fas fa-arrow-down"></i> {{ abs($totalRevenue['growth_percentage']) }}%
                            @endif
                        </small>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>Rp {{ number_format($totalRevenue['net_profit'], 0, ',', '.') }}</h3>
                        <p>Keuntungan Bersih</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>Rp {{ number_format($totalRevenue['pending_payments'], 0, ',', '.') }}</h3>
                        <p>Pembayaran Tertunda</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $orderMetrics['today'] }}</h3>
                        <p>Pesanan Hari Ini</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3>{{ $orderMetrics['conversion_rate'] }}%</h3>
                        <p>Tingkat Konversi</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-indigo">
                    <div class="inner">
                        <h3>Rp {{ number_format($orderMetrics['average_value'], 0, ',', '.') }}</h3>
                        <p>Rata-rata Nilai Pesanan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calculator"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-orange">
                    <div class="inner">
                        <h3>{{ $inventoryMetrics['low_stock_count'] }}</h3>
                        <p>Stok Rendah</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tren Pendapatan (7 Hari Terakhir)</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="revenueChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Status Pesanan</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="orderStatusChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Kinerja Karyawan Terbaik</h3>
                    </div>
                    <div class="card-body">
                        @if($employeeMetrics['top_employee'])
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>{{ $employeeMetrics['top_employee']->employee_name }}</h4>
                                    <p class="text-muted">Performer Terbaik Bulan Ini</p>
                                    <h5>Rp {{ number_format($employeeMetrics['top_employee']->total_revenue, 0, ',', '.') }}</h5>
                                </div>
                                <div class="col-md-6">
                                    <h5>Total Tim: Rp {{ number_format($employeeMetrics['team_revenue'], 0, ',', '.') }}</h5>
                                    <h6>Karyawan Aktif: {{ $employeeMetrics['active_employees']->count() }}</h6>
                                </div>
                            </div>
                        @else
                            <p>Belum ada data kinerja karyawan.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Status Inventori</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info"><i class="fas fa-box"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Produk</span>
                                        <span class="info-box-number">{{ $inventoryMetrics['total_products'] }}</span>
                                        <small>Aktif: {{ $inventoryMetrics['active_products'] }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success"><i class="fas fa-money-bill"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Nilai Stok</span>
                                        <span class="info-box-number">Rp {{ number_format($inventoryMetrics['stock_value'], 0, ',', '.') }}</span>
                                        <small>Dead Stock: {{ $inventoryMetrics['dead_stock_count'] }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($inventoryMetrics['top_selling_product'])
                            <div class="mt-3">
                                <h6>Barang Cepat Laku:</h6>
                                <p><strong>{{ $inventoryMetrics['top_selling_product']->product->name ?? 'N/A' }}</strong></p>
                                <small>Terjual: {{ $inventoryMetrics['top_selling_product']->total_sold }} unit</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Metode Pengiriman</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="shippingMethodChart" style="min-height: 200px; height: 200px; max-height: 200px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Kinerja Kategori</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Kategori</th>
                                        <th>Pendapatan</th>
                                        <th>Unit Terjual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categoryPerformance->take(5) as $category)
                                    <tr>
                                        <td>{{ $category->name }}</td>
                                        <td>Rp {{ number_format($category->revenue, 0, ',', '.') }}</td>
                                        <td>{{ $category->units_sold }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-exclamation-triangle text-warning"></i>
                            Peringatan Stok Rendah
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Stok</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($lowStockProducts->take(5) as $item)
                                    <tr>
                                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-warning">{{ $item->qty }} unit</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-danger">Pesan Ulang</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada stok rendah</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-line text-success"></i>
                            Produk Terlaris
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Terjual</th>
                                        <th>Pendapatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topProducts->take(5) as $item)
                                    <tr>
                                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                                        <td>{{ $item->total_sold }} unit</td>
                                        <td>Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Aktivitas Terbaru</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Kode Order</th>
                                        <th>Customer</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentActivities as $order)
                                    <tr>
                                        <td>{{ $order->code }}</td>
                                        <td>{{ $order->customer_first_name }} {{ $order->customer_last_name }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($order->status == 'completed') badge-success
                                                @elseif($order->status == 'cancelled') badge-danger
                                                @elseif($order->status == 'confirmed') badge-info
                                                @else badge-warning
                                                @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($deadStockProducts->count() > 0)
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-archive text-danger"></i>
                            Stok Mati (>90 Hari)
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Stok</th>
                                        <th>Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($deadStockProducts->take(10) as $item)
                                    <tr>
                                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                                        <td>{{ $item->qty }} unit</td>
                                        <td><span class="badge badge-info">Likuidasi</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pusat Aksi Cepat</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <a href="{{ route('admin.laporan') }}" class="btn btn-primary btn-block">
                                    <i class="fas fa-chart-bar"></i> Generate Laporan
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.products.index') }}" class="btn btn-success btn-block">
                                    <i class="fas fa-boxes"></i> Update Inventori
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.employee-performance.index') }}" class="btn btn-info btn-block">
                                    <i class="fas fa-users"></i> Review Karyawan
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-warning btn-block">
                                    <i class="fas fa-shopping-cart"></i> Kelola Pesanan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script-alt')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: @json($chartData['labels']),
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: @json($chartData['revenue']),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value, index, values) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
    new Chart(orderStatusCtx, {
        type: 'doughnut',
        data: {
            labels: @json(array_keys($orderMetrics['status_counts'])),
            datasets: [{
                data: @json(array_values($orderMetrics['status_counts'])),
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    const shippingCtx = document.getElementById('shippingMethodChart').getContext('2d');
    new Chart(shippingCtx, {
        type: 'pie',
        data: {
            labels: @json($shippingMethodStats->pluck('shipping_service_name')),
            datasets: [{
                data: @json($shippingMethodStats->pluck('count')),
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});
</script>
@endpush
@endsection