<?php $__env->startSection('content'); ?>

    <!-- Main content -->
    <section class="content pt-4">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Buat Produk</h3>
                <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-success shadow-sm float-right"> <i class="fa fa-arrow-left"></i> Kembali</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form method="post" action="<?php echo e(route('admin.products.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="form-group row border-bottom pb-4">
                        <label for="type" class="col-sm-2 col-form-label">Tipe Kategori</label>
                        <div class="col-sm-10">
                            <input type="text" name="type" readonly value="simple" placeholder="Simple" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="sku" class="col-sm-2 col-form-label">SKU</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" placeholder="Dicetak di produk, tercantum di kemasan, dan terbaca oleh POS" name="sku" value="<?php echo e(old('sku')); ?>" id="sku">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="name" class="col-sm-2 col-form-label">Nama Produk</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="name" value="<?php echo e(old('name')); ?>" id="name">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="category_id" class="col-sm-2 col-form-label">Kategori Produk</label>
                        <div class="col-sm-10">
                        <!-- sampai sini -->
                          <select class="form-control select-multiple"  multiple="multiple" name="category_id[]" id="category_id">
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <option <?php echo e(old('category_id') == $category->id ? 'selected' : null); ?> value="<?php echo e($category->id); ?>"> <?php echo e($category->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          </select>
                        </div>
                    </div>
                    <div class="configurable-attributes">
                      <?php if(count($configurable_attributes) > 0): ?>
                        <p class="text-primary mt-4">Konfigurasi Attribute Produk</p>
                        <hr/>
                        <?php $__currentLoopData = $configurable_attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $configurable_attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <div class="form-group row border-bottom pb-4">
                              <label for="<?php echo e($configurable_attribute->code); ?>" class="col-sm-2 col-form-label"><?php echo e($configurable_attribute->code); ?></label>
                              <div class="col-sm-10">
                              <!-- sampai sini -->
                                <select class="form-control select-multiple"  multiple="multiple" name="<?php echo e($configurable_attribute->code); ?>[]" id="<?php echo e($configurable_attribute->code); ?>">
                                  <?php $__currentLoopData = $configurable_attribute->attribute_options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attribute_option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($attribute_option->id); ?>"> <?php echo e($attribute_option->name); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                              </div>
                          </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      <?php endif; ?>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
      $('.select-multiple').select2();
      function showHideConfigurableAttributes() {
			var productType = $(".product-type").val();
            console.log(productType);

			if (productType == 'configurable') {
				$(".configurable-attributes").show();
			} else {
				$(".configurable-attributes").hide();
			}
		}
		$(function(){
			showHideConfigurableAttributes();
			$(".product-type").change(function() {
				showHideConfigurableAttributes();
			});
		});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/iqbalrizqi/Desktop/app/laravel/vivia-app/resources/views/admin/products/create.blade.php ENDPATH**/ ?>