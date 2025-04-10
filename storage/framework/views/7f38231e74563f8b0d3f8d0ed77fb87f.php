<?php $__env->startSection('content'); ?>

    <!-- Main content -->
    <section class="content pt-4">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Upload Gambar Produk</h3>
                <a href="<?php echo e(route('admin.products.product_images.index', $product)); ?>" class="btn btn-success shadow-sm float-right"> <i class="fa fa-arrow-left"></i> Kembali</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form method="post" action="<?php echo e(route('admin.products.product_images.store', $product)); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?> 
                    <div class="form-group row border-bottom pb-4">
                        <label for="path" class="col-sm-2 col-form-label">Path</label>
                        <div class="col-sm-10">
                          <input type="file" class="form-control" name="path" value="<?php echo e(old('path')); ?>" id="path">
                        </div>
                        <div class="form-group mt-4 d-none image-item">
                            <label for="">Preview Image : </label>
                            <img src="" alt="" class="img-preview img-fluid">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Save</button>
                </form>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
<?php $__env->stopSection(); ?>

<?php $__env->startPush('style-alt'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script-alt'); ?>
<script
        src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU="
        crossorigin="anonymous"
    >
    </script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
      $('.select-multiple').select2();
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/iqbalrizqi/Desktop/app/laravel/vivia-app/resources/views/admin/product_images/create.blade.php ENDPATH**/ ?>