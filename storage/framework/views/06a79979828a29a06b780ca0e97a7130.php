<?php $__env->startSection('content'); ?>

    <!-- Main content -->
    <section class="content pt-4">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Buat Attribute</h3>
                <a href="<?php echo e(route('admin.attributes.index')); ?>" class="btn btn-success shadow-sm float-right"> <i class="fa fa-arrow-left"></i> Kembali</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form method="post" action="<?php echo e(route('admin.attributes.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <fieldset class="form-group">
                        <div class="row">
                            <div class="col-lg-12">
                                <legend class="col-form-label pt-0 text-green">General</legend>
                                <div class="form-group row border-bottom pb-4">
                                    <label for="code" class="col-sm-2 col-form-label">Code (jika terdapat spasi, wajib diberi _) contoh : Jenis bahan = Jenis_Bahan</label>
                                    <div class="col-sm-10">
                                      <input type="text" class="form-control" name="code" value="<?php echo e(old('code')); ?>" id="code">
                                    </div>
                                </div>
                                <div class="form-group row border-bottom pb-4">
                                    <label for="name" class="col-sm-2 col-form-label">Name</label>
                                    <div class="col-sm-10">
                                      <input type="text" class="form-control" name="name" value="<?php echo e(old('name')); ?>" id="name">
                                    </div>
                                </div>
                                <div class="form-group row border-bottom pb-4">
                                    <label for="type" class="col-sm-2 col-form-label">type</label>
                                    <div class="col-sm-10">
                                      <select class="form-control" name="type" id="type">
                                          <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php echo e(old('type') == $type ? 'selected' : null); ?> value="<?php echo e($type); ?>"><?php echo e($type); ?></option>
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                      </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="form-group">
                        <div class="row">
                            <div class="col-lg-12">
                                <legend class="col-form-label pt-0 text-green">Validasi</legend>
                                <div class="form-group row border-bottom pb-4">
                                    <label for="is_required" class="col-sm-2 col-form-label">Harus Di isi ?</label>
                                    <div class="col-sm-10">
                                      <select class="form-control" name="is_required" id="is_required">
                                          <?php $__currentLoopData = $booleanOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $no => $booleanOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php echo e(old('is_required') == $no ? 'selected' : null); ?> value="<?php echo e($no); ?>"><?php echo e($booleanOption); ?></option>
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                      </select>
                                    </div>
                                </div>
                                <div class="form-group row border-bottom pb-4">
                                    <label for="is_unique" class="col-sm-2 col-form-label">Harus Unik ?</label>
                                    <div class="col-sm-10">
                                      <select class="form-control" name="is_unique" id="is_unique">
                                          <?php $__currentLoopData = $booleanOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $no => $booleanOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php echo e(old('is_unique') == $no ? 'selected' : null); ?> value="<?php echo e($no); ?>"><?php echo e($booleanOption); ?></option>
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                      </select>
                                    </div>
                                </div>
                                <div class="form-group row border-bottom pb-4">
                                    <label for="validation" class="col-sm-2 col-form-label">Validasi</label>
                                    <div class="col-sm-10">
                                      <select class="form-control" name="validation" id="validation">
                                          <?php $__currentLoopData = $validations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $validation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php echo e(old('validation') == $validation ? 'selected' : null); ?> value="<?php echo e($validation); ?>"><?php echo e($validation); ?></option>
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                      </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="form-group">
                        <div class="row">
                            <div class="col-lg-12">
                                <legend class="col-form-label pt-0 text-green">Konfigurasi</legend>
                                <div class="form-group row border-bottom pb-4">
                                    <label for="is_configurable" class="col-sm-2 col-form-label">Konfigurasi Produk ?</label>
                                    <div class="col-sm-10">
                                      <select class="form-control" name="is_configurable" id="is_configurable">
                                          <?php $__currentLoopData = $booleanOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $no => $booleanOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php echo e(old('is_configurable') == $no ? 'selected' : null); ?> value="<?php echo e($no); ?>"><?php echo e($booleanOption); ?></option>
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                      </select>
                                    </div>
                                </div>
                                <div class="form-group row border-bottom pb-4">
                                    <label for="is_filterable" class="col-sm-2 col-form-label">Filter Produk ?</label>
                                    <div class="col-sm-10">
                                      <select class="form-control" name="is_filterable" id="is_filterable">
                                          <?php $__currentLoopData = $booleanOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $no => $booleanOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php echo e(old('is_filterable') == $no ? 'selected' : null); ?> value="<?php echo e($no); ?>"><?php echo e($booleanOption); ?></option>
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                      </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <button type="submit" class="btn btn-success">Simpan</button>
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/iqbalrizqi/Desktop/app/laravel/vivia-app/resources/views/admin/attributes/create.blade.php ENDPATH**/ ?>