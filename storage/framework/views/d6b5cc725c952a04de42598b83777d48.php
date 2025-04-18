<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
            <a href="<?php echo e(route('admin.profile.show')); ?>" class="d-block"><?php echo e(auth()->user()->first_name . auth()->user()->last_name); ?></a>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
            data-accordion="false">
            <li class="nav-item">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link">
                    <i class="nav-icon fas fa-th"></i>
                    <p>
                        <?php echo e(__('Dashboard')); ?>

                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="<?php echo e(route('admin.users.index')); ?>" class="nav-link">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        <?php echo e(__('Users')); ?>

                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="<?php echo e(route('admin.slides.index')); ?>" class="nav-link">
                    <i class="nav-icon fa fa-image"></i>
                    <p>
                        <?php echo e(__('Slide')); ?>

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
                        <a href="<?php echo e(route('admin.categories.index')); ?>" class="nav-link">
                            <i class="fa fa-plus nav-icon"></i>
                            <p>Kategori</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.attributes.index')); ?>" class="nav-link">
                            <i class="fa fa-plus nav-icon"></i>
                            <p>Attribute</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.products.index')); ?>" class="nav-link">
                            <i class="fa fa-plus nav-icon"></i>
                            <p>Produk</p>
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
                        <a href="<?php echo e(route('admin.supplier.index')); ?>" class="nav-link">
                            <i class="fa fa-plus nav-icon"></i>
                            <p>Supplier</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.instagram.index')); ?>" class="nav-link">
                            <i class="fa fa-plus nav-icon"></i>
                            <p>Instagram</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.setting.index')); ?>" class="nav-link">
                            <i class="fa fa-plus nav-icon"></i>
                            <p>Setting</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.pembelian.index')); ?>" class="nav-link">
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
                        <a href="<?php echo e(route('admin.orders.index')); ?>" class="nav-link">
                            <i class="fa fa-plus nav-icon"></i>
                            <p>Order</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.orders.checkPage')); ?>" class="nav-link">
                            <i class="fa fa-plus nav-icon"></i>
                            <p>Buat Order</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.shipments.index')); ?>" class="nav-link">
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
                        <a href="<?php echo e(route('admin.laporan')); ?>" class="nav-link">
                            <i class="fa fa-plus nav-icon"></i>
                            <p>Transaksi</p>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
<?php /**PATH /Users/iqbalrizqi/Desktop/app/laravel/vivia-app/resources/views/layouts/navigation.blade.php ENDPATH**/ ?>