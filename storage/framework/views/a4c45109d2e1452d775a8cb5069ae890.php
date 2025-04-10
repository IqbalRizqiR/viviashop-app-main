<!-- Back to Top -->
        <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>   

        
    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo e(asset('lib/easing/easing.min.js')); ?>"></script>
    <script src="<?php echo e(asset('frontend/lib/waypoints/waypoints.min.js')); ?>"></script>
    <script src="<?php echo e(asset('themes/ezone/assets/js/app.js')); ?>"></script>
    <script src="<?php echo e(asset('frontend/lib/lightbox/js/lightbox.min.js')); ?>"></script>
    <script src="<?php echo e(asset('frontend/lib/owlcarousel/owl.carousel.min.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(".delete").on("click", function () {
            return confirm("Do you want to remove this?");
        });
    </script>
    <?php echo $__env->yieldPushContent('script-alt'); ?>

    <!-- Template Javascript -->
    <script src="<?php echo e(asset('frontend/js/main.js')); ?>"></script><?php /**PATH /Users/iqbalrizqi/Desktop/app/laravel/vivia-app/resources/views/frontend/partials/frontend/script.blade.php ENDPATH**/ ?>