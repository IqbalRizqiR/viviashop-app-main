<?php $__env->startSection('content'); ?>
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
                            <form action="<?php echo e(route('shop')); ?>" method="GET">
                            <div class="input-group w-100 mx-auto d-flex">
                                    <input type="text" name="search" class="form-control p-3" placeholder="keywords" aria-describedby="search-icon-1">
                                    <button type="submit" id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></button>
                                </div>
                            </form>
                        </div>
                        <div class="col-6"></div>
                    </div>
                    <div class="row g-4 mt-5">
                        <div class="col-lg-3">
                            <div class="row g-4">
                                <div class="col-lg-12">
                                    <div class="mb-5">
                                        <h4>Categories</h4>
                                        <ul class="list-unstyled fruite-categorie">
                                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li>
                                                    <div class="d-flex justify-content-between fruite-name">
                                                        <a href="#"><i class="fas fa-apple-alt me-2"></i><?php echo e($item->name); ?></a>
                                                    </div>
                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="row g-4 justify-content-center">
                                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <div class="rounded position-relative fruite-item">
                                        <?php if(request()->has('search')): ?>
                                            <div class="fruite-img">
                                                <?php
                                                    $image = !empty($row->productImages->first()) ? asset('storage/'.$row->productImages->first()->path) : asset('images/placeholder.jpg');
                                                ?>
                                                <img src="<?php echo e($image); ?>" class="img-fluid w-100 rounded-top" alt="">
                                            </div>
                                            <?php if(count($row->categories) <= 1): ?>
                                                <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;"><?php echo e($row->categories[0]->name); ?></div>
                                            <?php else: ?>
                                                <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;"><?php echo e($row->categories->name); ?></div>
                                            <?php endif; ?>
                                            <div class="p-3 border border-secondary border-top-0 rounded-bottom">
                                                <?php if($row->products == NULL): ?>
                                                    <a href="<?php echo e(route('shop-detail', $row->id)); ?>"><h4><?php echo e($row->name); ?></h4></a>
                                                    <b><?php echo e($row->short_description); ?></b>
                                                    <?php if($row->productInventory != null): ?>
                                                        <p>Stok : <?php echo e($row->productInventory->qty); ?></p>
                                                    <?php endif; ?>
                                                    <div class="d-flex justify-content-center flex-lg-wrap">
                                                        <p class="text-dark fs-5 fw-bold mb-2">Rp. <?php echo e(number_format($row->price)); ?></p>
                                                        <a  class="btn border border-secondary rounded-pill px-3 text-primary add-to-card"  product-id="<?php echo e($row->id); ?>" product-type="<?php echo e($row->type); ?>" product-slug="<?php echo e($row->slug); ?>"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                                    </div>
                                                <?php else: ?>
                                                    <a href="<?php echo e(route('shop-detail', $row->products->id)); ?>"><h4><?php echo e($row->products->name); ?></h4></a>
                                                    <b><?php echo e($row->products->short_description); ?></b>
                                                    <?php if($row->products->productInventory != null): ?>
                                                        <p>Stok : <?php echo e($row->products->productInventory->qty); ?></p>
                                                    <?php endif; ?>
                                                    <div class="d-flex justify-content-center flex-lg-wrap">
                                                        <p class="text-dark fs-5 fw-bold mb-2">Rp. <?php echo e(number_format($row->products->price)); ?></p>
                                                        <a class="btn border border-secondary rounded-pill px-3 text-primary add-to-card" product-id="<?php echo e($row->products->id); ?>" product-type="<?php echo e($row->products->type); ?>" product-slug="<?php echo e($row->products->slug); ?>"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                                    </div>
                                                <?php endif; ?>
                                        <?php else: ?>
                                            <div class="fruite-img">
                                                <?php
                                                    $image = !empty($row->products->productImages->first()) ? asset('storage/'.$row->products->productImages->first()->path) : asset('images/placeholder.jpg');
                                                ?>
                                                <img src="<?php echo e($image); ?>" class="img-fluid w-100 rounded-top" alt="">
                                                    
                                            </div>
                                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;"><?php echo e($row->categories->name); ?></div>
                                            <div class="p-3 border border-secondary border-top-0 rounded-bottom">
                                                <a href="<?php echo e(route('shop-detail', $row->products->id)); ?>"><h4><?php echo e($row->products->name); ?></h4></a>
                                            <b style="color: black; font-weight: bold;"><?php echo e($row->products->short_description); ?></b>
                                            <?php if($row->products->productInventory != null): ?>
                                                <p class="mt-4">Stok : <?php echo e($row->products->productInventory->qty); ?></p>
                                            <?php endif; ?>
                                            <div class="d-flex justify-content-center flex-lg-wrap">
                                                <p class="text-dark fs-5 fw-bold mb-2">Rp. <?php echo e(number_format($row->products->price)); ?></p>
                                                <a href="" class="btn border border-secondary rounded-pill px-3 text-primary add-to-card" product-id="<?php echo e($row->products->id); ?>" product-type="<?php echo e($row->products->type); ?>" product-slug="<?php echo e($row->products->slug); ?>"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                            </div>
                                        <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/iqbalrizqi/Desktop/app/laravel/vivia-app/resources/views/frontend/shop/index.blade.php ENDPATH**/ ?>