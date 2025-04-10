<!DOCTYPE html>
<html lang="en">

    <?php echo $__env->make('frontend.partials.frontend.style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <body>




        <?php echo $__env->make('frontend.partials.frontend.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


        <?php echo $__env->yieldContent('content'); ?>


        <?php echo $__env->make('frontend.partials.frontend.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>



        <?php echo $__env->make('frontend.partials.frontend.script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->yieldPushContent('script-alt'); ?>
    </body>

</html>
<?php /**PATH /Users/iqbalrizqi/Desktop/app/laravel/vivia-app/resources/views/frontend/layouts.blade.php ENDPATH**/ ?>