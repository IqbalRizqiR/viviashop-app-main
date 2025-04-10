<?php $__env->startSection('content'); ?>

    <!-- Main content -->
    <section class="content pt-4">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Tambah Slide</h3>
                <a href="<?php echo e(route('admin.slides.index')); ?>" class="btn btn-success shadow-sm float-right"> <i class="fa fa-arrow-left"></i> Kembali</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form method="post" action="<?php echo e(route('admin.slides.store')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="form-group row border-bottom pb-4">
                        <label for="title" class="col-sm-2 col-form-label">Title</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="title" value="<?php echo e(old('title')); ?>" id="title">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="url" class="col-sm-2 col-form-label">Url</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="url" value="<?php echo e(old('url')); ?>" id="url">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="path" class="col-sm-2 col-form-label">Gambar</label>
                        <div class="col-sm-10">
                          <input type="file" class="form-control" name="path" value="<?php echo e(old('path')); ?>" id="path">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="body" class="col-sm-2 col-form-label">Body</label>
                        <div class="col-sm-10">
                            <input type="text" name="body" value="null" hidden>
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                            <label for="status" class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-10">
                              <select class="form-control" name="status" id="status">
                                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option <?php echo e(old('status') == $value ? 'selected' : null); ?> value="<?php echo e($value); ?>"> <?php echo e($status); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              </select>
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/iqbalrizqi/Desktop/app/laravel/vivia-app/resources/views/admin/slides/create.blade.php ENDPATH**/ ?>