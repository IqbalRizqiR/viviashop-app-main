

<?php $__env->startSection('content'); ?>
    <div class="container mb-5" style="margin-top: 12rem;">
        <div class="row">
            <div class="col-md-12 box-border rounded">
                <form action="<?php echo e(route('orders.confirmPayment', $order->id)); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo method_field('PUT'); ?>
                    <?php echo csrf_field(); ?>
                    <input type="hidden" value="<?php echo e($order->id); ?>" name="id_order">
                    <div class="form-group mb-3">
                        <input type="file" class="form-control" name="file_bukti" id="image">
                    </div>
                    <div class="form-item mt-4 d-none image-item">
                        <label for="">Preview Image</label>
                        <img src="" alt="" class="img-fluid img-preview">
                    </div>
                    <div class="mx-auto">
                        <button type="submit d-block text-center">Upload Bukti Pembayaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/iqbalrizqi/Desktop/app/laravel/vivia-app/resources/views/admin/orders/confirmPayment.blade.php ENDPATH**/ ?>