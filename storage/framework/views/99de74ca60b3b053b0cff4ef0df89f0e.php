<div class="modal fade" id="modal-supplier" tabindex="-1" role="dialog" aria-labelledby="modal-supplier">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Upload Excel</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <a href="<?php echo e(route('admin.downloadTemplate')); ?>" class="btn btn-primary">Download Template Excel</a>
                <form enctype="multipart/form-data" action="<?php echo e(route('admin.products.imports')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <input type="file" name="excelFile" class="form-control" id="">

                    <button class="mt-5 btn btn-primary" type="submit">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /Users/iqbalrizqi/Desktop/app/laravel/vivia-app/resources/views/admin/products/form.blade.php ENDPATH**/ ?>