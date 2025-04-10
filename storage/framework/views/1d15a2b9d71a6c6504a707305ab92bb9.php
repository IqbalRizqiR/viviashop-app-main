<?php $__env->startSection('content'); ?>
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Shop Detail</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Pages</a></li>
            <li class="breadcrumb-item active text-white">Shop Detail</li>
        </ol>
    </div>

    <div class="container-fluid py-5 mt-5">
        <div class="container py-5">
            <div class="row g-4 mb-5">
                <div class="col-lg-8 col-xl-9">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="border rounded">
                                <?php if($product->products->productImages->count() > 0): ?>
                                    <?php if($product->products->productImages->count() > 1): ?>
                                        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                                            <div class="carousel-indicators">
                                                <?php $__currentLoopData = $product->products->productImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    
                                                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?php echo e($loop->index); ?>" class="active"></button>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            <div class="carousel-inner">
                                                <?php $__currentLoopData = $product->products->productImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $images): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="carousel-item <?php echo e($key == 0 ? 'active' : ''); ?>">
                                                    <img src="<?php echo e(asset('storage/'. $images->path)); ?>" class="d-block w-100"  alt="...">
                                                </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Next</span>
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <img src="<?php echo e(asset('storage/'. $product->products->productImages->first()->path)); ?>" class="img-fluid rounded" alt="Image">
                                    <?php endif; ?>
                                <?php else: ?>
                                <img src="<?php echo e(asset('images/placeholder.jpg')); ?>" class="img-fluid rounded" alt="Image">
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h4 class="fw-bold mb-3"><?php echo e($product->products->name); ?></h4>
                            <p class="mb-3">Category: <?php echo e($product->categories->name); ?></p>
                            <p class="mb-3">Stock: <?php echo e($product->products->productInventory->qty); ?></p>
                            <h5 class="fw-bold mb-3">Rp. <?php echo e(number_format($product->products->price)); ?></h5>
                            <div class="d-flex mb-4">
                            </div>
                            <b class="mb-4"><?php echo e($product->products->short_description); ?></b>
                            <?php if($product->products->productInventory != null): ?>
                                <p>Stok : <?php echo e($product->products->productInventory->qty); ?></p>
                            <?php endif; ?>
                            <p class="mb-4"><?php echo $product->products->description; ?></p>
                            <div class="input-group quantity mb-5" style="width: 100px;">
                            </div>
                            <a class="btn border border-secondary rounded-pill px-4 py-2 mb-4 text-primary add-to-card" product-id="<?php echo e($product->products->id); ?>" product-type="<?php echo e($product->products->type); ?>" product-slug="<?php echo e($product->products->slug); ?>">
                                <i class="fa fa-shopping-bag me-2 text-primary"></i>
                                Add to cart
                            </a>
                        </div>
                        <div class="col-lg-12">
                            <nav>
                                <div class="nav nav-tabs mb-3">
                                    <button class="nav-link border-white border-bottom-0" type="button" role="tab"
                                        id="nav-about-tab" data-bs-toggle="tab" data-bs-target="#nav-about"
                                        aria-controls="nav-about" aria-selected="true">Description</button>
                                    <button class="nav-link border-white border-bottom-0" type="button" role="tab"
                                        id="nav-links-tab" data-bs-toggle="tab" data-bs-target="#nav-links"
                                        aria-controls="nav-links" aria-selected="true">Link Product</button>
                                </div>
                            </nav>
                            <div class="tab-content mb-5">
                                <div class="tab-pane active" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                                    <b><?php echo e($product->products->short_description); ?> </b>
                                    <?php if($product->products->productInventory != null): ?>
                                        <p>Stok : <?php echo e($product->products->productInventory->qty); ?></p>
                                    <?php endif; ?>
                                    <p><?php echo $product->products->description; ?></p>
                                    <div class="px-2">
                                        <div class="row g-4">
                                            <div class="col-6">
                                                <div class="row bg-light align-items-center text-center justify-content-center py-2">
                                                    <div class="col-6">
                                                        <p class="mb-0">Weight</p>
                                                    </div>
                                                    <div class="col-6">
                                                        <p class="mb-0"><?php echo e($product->products->weight); ?> Kg</p>
                                                    </div>
                                                </div>
                                                <div class="row bg-light text-center align-items-center justify-content-center py-2">
                                                    <div class="col-6">
                                                        <p class="mb-0">Quality</p>
                                                    </div>
                                                    <div class="col-6">
                                                        <p class="mb-0">Good Quality</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="nav-links" role="tabpanel" aria-labelledby="nav-links-tab">
                                    <a href="<?php echo e($product->products->link1); ?>"><p>Product Link 1 : <?php echo e($product->products->link1); ?> </p></a>
                                    <a href="<?php echo e($product->products->link2); ?>"><p>Product Link 2 : <?php echo e($product->products->link2); ?></p></a>
                                    <a href="<?php echo e($product->products->link3); ?>"><p>Product Link 3 : <?php echo e($product->products->link3); ?></p></a>
                                </div>
                                <div class="tab-pane" id="nav-mission" role="tabpanel" aria-labelledby="nav-mission-tab">
                                    <div class="d-flex">
                                        <img src="img/avatar.jpg" class="img-fluid rounded-circle p-3" style="width: 100px; height: 100px;" alt="">
                                        <div class="">
                                            <p class="mb-2" style="font-size: 14px;">April 12, 2024</p>
                                            <div class="d-flex justify-content-between">
                                                <h5>Jason Smith</h5>
                                                <div class="d-flex mb-3">
                                                    <i class="fa fa-star text-secondary"></i>
                                                    <i class="fa fa-star text-secondary"></i>
                                                    <i class="fa fa-star text-secondary"></i>
                                                    <i class="fa fa-star text-secondary"></i>
                                                    <i class="fa fa-star"></i>
                                                </div>
                                            </div>
                                            <p>The generated Lorem Ipsum is therefore always free from repetition injected humour, or non-characteristic
                                                words etc. Susp endisse ultricies nisi vel quam suscipit </p>
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <img src="img/avatar.jpg" class="img-fluid rounded-circle p-3" style="width: 100px; height: 100px;" alt="">
                                        <div class="">
                                            <p class="mb-2" style="font-size: 14px;">April 12, 2024</p>
                                            <div class="d-flex justify-content-between">
                                                <h5>Sam Peters</h5>
                                                <div class="d-flex mb-3">
                                                    <i class="fa fa-star text-secondary"></i>
                                                    <i class="fa fa-star text-secondary"></i>
                                                    <i class="fa fa-star text-secondary"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                </div>
                                            </div>
                                            <p class="text-dark">The generated Lorem Ipsum is therefore always free from repetition injected humour, or non-characteristic
                                                words etc. Susp endisse ultricies nisi vel quam suscipit </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="nav-vision" role="tabpanel">
                                    <p class="text-dark">Tempor erat elitr rebum at clita. Diam dolor diam ipsum et tempor sit. Aliqu diam
                                        amet diam et eos labore. 3</p>
                                    <p class="mb-0">Diam dolor diam ipsum et tempor sit. Aliqu diam amet diam et eos labore.
                                        Clita erat ipsum et lorem et sit</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xl-3">
                    <div class="row g-4 fruite">
                        <div class="col-lg-12">
                            <div class="position-relative">
                                <img src="img/banner-fruits.jpg" class="img-fluid w-100 rounded" alt="">
                                <div class="position-absolute" style="top: 50%; right: 10px; transform: translateY(-50%);">
                                    <h3 class="text-secondary fw-bold">Fresh <br> Fruits <br> Banner</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/iqbalrizqi/Desktop/app/laravel/vivia-app/resources/views/frontend/shop/detail.blade.php ENDPATH**/ ?>