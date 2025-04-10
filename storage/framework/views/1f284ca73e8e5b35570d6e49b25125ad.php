
<?php $__env->startSection('content'); ?>
    <!-- Single Page Header start -->
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Cart</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Pages</a></li>
            <li class="breadcrumb-item active text-white">Cart</li>
        </ol>
    </div>
    <!-- Single Page Header End -->


    <!-- Cart Page Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <?php if(session()->has('message')): ?>
				<div class="content-header mb-0 pb-0">
					<div class="container-fluid">
						<div class="mb-0 alert alert-<?php echo e(session()->get('alert-type')); ?> alert-dismissible fade show" role="alert">
							<strong><?php echo e(session()->get('message')); ?></strong>
						</div> 
				</div>
			<?php endif; ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Products</th>
                        <th scope="col">Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Total</th>
                        <th scope="col">Handle</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $product = $item->model;
                                $image = !empty($product->productImages->first()) ? asset('storage/'.$product->productImages->first()->path) : asset('themes/ezone/assets/img/cart/3.jpg');
                            ?>
                            <tr>
                                <th scope="row">
                                    <div class="d-flex align-items-center">
                                        <img src="<?php echo e($image); ?>" class="img-fluid me-5 rounded" style="width: 80px; height: 80px;" alt="">
                                    </div>
                                </th>
                                <td>
                                    <p class="mb-0 mt-4"><?php echo e($product->name); ?></p>
                                </td>
                                <td>
                                    <p class="mb-0 mt-4">Rp. <?php echo e(number_format($product->price)); ?></p>
                                </td>
                                <td>
                                    <input type="number" className="form-control" id="change-qty" value="<?php echo e($item->qty); ?>" data-productId="<?php echo e($item->rowId); ?>" min="1" max="<?php echo e($product->productInventory->qty); ?>">
                                </td>
                                <td>
                                    <p class="mb-0 mt-4">Rp. <?php echo e(number_format($item->price * $item->qty, 0, ",", ".")); ?></p>
                                </td>
                                <td>
                                    <a href="<?php echo e(url('carts/remove/'. $item->rowId)); ?>" class="btn delete btn-md rounded-circle bg-light border mt-4" >
                                        <i class="fa fa-times text-danger"></i>
                                    </a>
                                </td>
                            
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <td colspan="6">The cart is empty!</td>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="row g-4 justify-content-end">
                <div class="col-8"></div>
                <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4">
                    <div class="bg-light rounded">
                        <div class="p-4">
                            <h1 class="display-6 mb-4">Cart <span class="fw-normal">Total</span></h1>
                            <div class="d-flex justify-content-between mb-4">
                                <h5 class="mb-0 me-4">Subtotal:</h5>
                                <p class="mb-0">Rp. <?php echo e(Cart::subtotal(0, ",", ".")); ?></p>
                            </div>
                        </div>
                        <div class="py-4 mb-4 border-top border-bottom d-flex justify-content-between">
                            <h5 class="mb-0 ps-4 me-4">Total</h5>
                            <p class="mb-0 pe-4">Rp. <?php echo e(Cart::subtotal(0, ",", ".")); ?></p>
                        </div>
                        <a href="<?php echo e(url('orders/checkout')); ?>" class="btn border-secondary rounded-pill px-4 py-3 text-primary text-uppercase mb-4 ms-4" type="button">Proceed Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cart Page End -->
<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-alt'); ?>
<script>
	$(document).on("change", function (e) {
		var qty = e.target.value;
		var productId = e.target.attributes['data-productid'].value;

        $.ajax({
            type: "POST",
            url: "/carts/update",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                productId,
                qty
            },
            success: function (response) {
				location.reload(true);
				Swal.fire({
                        title: "Jumlah Produk",
                        text: "Berhasil di ganti !",
                        icon: "success",
                        confirmButtonText: "Close",
                    });
            },
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('frontend.layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/iqbalrizqi/Desktop/app/laravel/vivia-app/resources/views/frontend/carts/index.blade.php ENDPATH**/ ?>