<?php $__env->startSection('content'); ?>

    <!-- Main content -->
    <section class="content pt-4">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Buat Kategori</h3>
                <a href="<?php echo e(route('admin.categories.index')); ?>" class="btn btn-success shadow-sm float-right"> <i class="fa fa-arrow-left"></i> Kembali</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form method="post" action="<?php echo e(route('admin.categories.store')); ?>">
                    <?php echo csrf_field(); ?> 
                    <div class="form-group row border-bottom pb-4">
                        <label for="name" class="col-sm-2 col-form-label">Nama Kategori</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="name" value="<?php echo e(old('name')); ?>" id="name">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="parent_id" class="col-sm-2 col-form-label">Kategori Utama</label>
                        <div class="col-sm-10">
                          <select class="form-control" name="parent_id" id="parent_id">
                            <option value="">Atur sebagai Kategori Utama</option>
                            <?php $__currentLoopData = $main_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $main_category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <option <?php echo e(old('parent_id') == $main_category->id ? 'selected' : null); ?> value="<?php echo e($main_category->id); ?>"> <?php echo e($main_category->name); ?></option>
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/iqbalrizqi/Desktop/app/laravel/vivia-app/resources/views/admin/categories/create.blade.php ENDPATH**/ ?>