<?php $__env->startSection('content'); ?>

    <!-- Main content -->
    <section class="content pt-4">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Tambah Post IG</h3>
                <a href="<?php echo e(route('admin.instagram.index')); ?>" class="btn btn-success shadow-sm float-right"> <i class="fa fa-arrow-left"></i> Kembali</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form method="post" action="<?php echo e(route('admin.instagram.store')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="form-group row border-bottom pb-4">
                        <label for="caption" class="col-sm-2 col-form-label">Caption</label>
                        <textarea id="caption" name="caption" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="col-sm-10">
                        <input type="file" class="form-control" name="image" value="<?php echo e(old('path')); ?>" accept="image/*" required id="path">
                      </div>
                      <div class="form-group mt-4 d-none image-item">
                          <label for="">Preview Image : </label>
                          <img src="" alt="" class="img-preview img-fluid">
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


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/iqbalrizqi/Desktop/app/laravel/vivia-app/resources/views/instagram/create.blade.php ENDPATH**/ ?>