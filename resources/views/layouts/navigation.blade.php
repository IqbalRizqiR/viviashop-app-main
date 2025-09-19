<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
            <a href="{{ route('admin.profile.show') }}" class="d-block">{{ auth()->check() ? (auth()->user()->first_name ?? '') . ' ' . (auth()->user()->last_name ?? '') : 'Guest' }}</a>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
            data-accordion="false">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                    <i class="nav-icon fas fa-th"></i>
                    <p>
                        {{ __('Dashboard') }}
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        {{ __('Users') }}
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.slides.index') }}" class="nav-link">
                    <i class="nav-icon fa fa-image"></i>
                    <p>
                        {{ __('Slide') }}
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-circle nav-icon"></i>
                    <p>
                        Managemen Produk
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        <a href="{{ route('admin.categories.index') }}" class="nav-link">
                            <i class="fa fa-plus nav-icon"></i>
                            <p>Kategori</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.attributes.index') }}" class="nav-link">
                            <i class="fa fa-plus nav-icon"></i>
                            <p>Attribute</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.products.index') }}" class="nav-link">
                            <i class="fa fa-plus nav-icon"></i>
                            <p>Produk</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.smart-print-converter.index') }}" class="nav-link">
                            <i class="fa fa-magic nav-icon"></i>
                            <p>Smart Print Converter</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-circle nav-icon"></i>
                    <p>
                        Managemen Toko
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        <a href="{{ route('admin.supplier.index') }}" class="nav-link">
                            <i class="fa fa-plus nav-icon"></i>
                            <p>Supplier</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.instagram.index') }}" class="nav-link">
                            <i class="fa fa-plus nav-icon"></i>
                            <p>Instagram</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.setting.index') }}" class="nav-link">
                            <i class="fa fa-plus nav-icon"></i>
                            <p>Setting</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.pembelian.index') }}" class="nav-link">
                            <i class="fa fa-plus nav-icon"></i>
                            <p>Pembelian</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-circle nav-icon"></i>
                    <p>
                        Managemen Order
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        <a href="{{ route('admin.orders.index') }}" class="nav-link">
                            <i class="fa fa-plus nav-icon"></i>
                            <p>Order</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.orders.checkPage') }}" class="nav-link">
                            <i class="fa fa-plus nav-icon"></i>
                            <p>Buat Order</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.shipments.index') }}" class="nav-link">
                            <i class="fa fa-plus nav-icon"></i>
                            <p>Pengiriman</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-circle nav-icon"></i>
                    <p>
                        Managemen Report
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview" style="display: none;">
                    <li class="nav-item">
                        <a href="{{ route('admin.laporan') }}" class="nav-link">
                            <i class="fa fa-plus nav-icon"></i>
                            <p>Transaksi</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.employee-performance.index') }}" class="nav-link">
                    <i class="nav-icon fas fa-chart-line"></i>
                    <p>
                        Employee Performance
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.print-service.index') }}" class="nav-link">
                    <i class="nav-icon fas fa-print"></i>
                    <p>
                        Smart Print Service
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.smart-print-variant.index') }}" class="nav-link">
                    <i class="nav-icon fas fa-magic"></i>
                    <p>
                        Smart Print Variant Manager
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.stock.index') }}" class="nav-link">
                    <i class="nav-icon fas fa-chart-area"></i>
                    <p>
                        Kartu Stok
                    </p>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
