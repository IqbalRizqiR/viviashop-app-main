<?php $__env->startSection('content'); ?>

    <!-- Main content -->
    <section class="content pt-4">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Edit Produk</h3>
                <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-success shadow-sm float-right"> <i class="fa fa-arrow-left"></i> Kembali</a>
                <a href="<?php echo e(route('admin.products.product_images.index', $product)); ?>" class="btn btn-success shadow-sm mr-2 float-right"> <i class="fa fa-image"></i> Upload Gambar Produk</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form method="post" action="<?php echo e(route('admin.products.update', $product)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('put'); ?>
                    <div class="form-group row border-bottom pb-4">
                        <label for="type" class="col-sm-2 col-form-label">Tipe Kategori</label>
                        <div class="col-sm-10">
                            <input type="text" name="type" readonly value="simple" placeholder="Simple" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="sku" class="col-sm-2 col-form-label">SKU</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="sku" value="<?php echo e(old('sku', $product->sku)); ?>" id="sku">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="name" class="col-sm-2 col-form-label">Nama Produk</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="name" value="<?php echo e(old('name', $product->name)); ?>" id="name">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="category_id" class="col-sm-2 col-form-label">Kategori Produk</label>
                        <div class="col-sm-10">
                        <!-- sampai sini -->
                          <select class="form-control select-multiple"  multiple="multiple" name="category_id[]" id="category_id">
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <option <?php echo e(in_array($category->id, $product->categories->pluck('id')->toArray()) ? 'selected' : null); ?> value="<?php echo e($category->id); ?>"> <?php echo e($category->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          </select>
                        </div>
                    </div>
                    <?php if(!empty($configurable_attributes) && empty($product)): ?>
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
                    <?php if($product): ?>
                        <?php if($product->type == 'configurable'): ?>
                            <?php echo $__env->make('admin.products.configurable', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php else: ?>
                            <?php echo $__env->make('admin.products.simple', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php endif; ?>

                        <div class="form-group row border-bottom pb-4">
                            <label for="short_description" class="col-sm-2 col-form-label">Deskripsi Singkat</label>
                            <div class="col-sm-10">
                              <textarea class="form-control" name="short_description" id="short_description" cols="30" rows="5"><?php echo e(old('short_description', $product->short_description)); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row border-bottom pb-4">
                            <label for="description" class="col-sm-2 col-form-label">Deskripsi Produk</label>
                            <div class="col-sm-10">
                              <textarea class="form-control editor" name="description" id="description" cols="30" rows="5"><?php echo e(old('description', $product->description)); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row border-bottom pb-4">
                            <label for="status" class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-10">
                              <select class="form-control" name="status" id="status">
                                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option <?php echo e(old('status', $product->status) == $value ? 'selected' : null); ?> value="<?php echo e($value); ?>"> <?php echo e($status); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              </select>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="form-group row border-bottom pb-4">
                        <label for="name" class="col-sm-2 col-form-label">Link redirect Product</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="link1" value="<?php echo e($product->link1); ?>" id="link1">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="name" class="col-sm-2 col-form-label">Link redirect Product</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="link2" value="<?php echo e($product->link2); ?>" id="link2">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="name" class="col-sm-2 col-form-label">Link redirect Product</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="link3" value="<?php echo e($product->link3); ?>" id="link3">
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
    <script>
        const {
            ClassicEditor,
            Autoformat,
            Font,
            Bold,
            Italic,
            Underline,
            BlockQuote,
            Base64UploadAdapter,
            CloudServices,
            Essentials,
            Heading,
            Image,
            ImageCaption,
            ImageResize,
            ImageStyle,
            ImageToolbar,
            ImageUpload,
            PictureEditing,
            Indent,
            IndentBlock,
            Link,
            List,
            MediaEmbed,
            Mention,
            Paragraph,
            PasteFromOffice,
            Table,
            TableColumnResize,
            TableToolbar,
            TextTransformation
        } = CKEDITOR;
        const div = document.querySelector('.editor');
        ClassicEditor.create(div, {
                licenseKey: 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3NzU4NjU1OTksImp0aSI6ImI5NzM2NTIwLTVjZDEtNDk3Yi1iYjIwLTk0ODY4YmNiY2ZmNiIsImxpY2Vuc2VkSG9zdHMiOlsidml2aWEudmludG5uLm15LmlkIl0sInVzYWdlRW5kcG9pbnQiOiJodHRwczovL3Byb3h5LWV2ZW50LmNrZWRpdG9yLmNvbSIsImRpc3RyaWJ1dGlvbkNoYW5uZWwiOlsiY2xvdWQiLCJkcnVwYWwiXSwiZmVhdHVyZXMiOlsiRFJVUCJdLCJ2YyI6ImU2MWIxYjAwIn0.3USUA2RcuPamY_sXqKynuq4yzbamp_FZnXZBA2dQvnjyOvXdtTd3KABEqhloXT_Cnc1v8qHEIlFk1wFeMGeQ6w',
                plugins: [ Autoformat,
                            BlockQuote,
                            Bold,
                            CloudServices,
                            Essentials,
                            Heading,
                            Image,
                            ImageCaption,
                            ImageResize,
                            ImageStyle,
                            ImageToolbar,
                            ImageUpload,
                            Base64UploadAdapter,
                            Indent,
                            IndentBlock,
                            Italic,
                            Link,
                            List,
                            MediaEmbed,
                            Mention,
                            Paragraph,
                            PasteFromOffice,
                            PictureEditing,
                            Table,
                            TableColumnResize,
                            TableToolbar,
                            TextTransformation,
                            Underline,
                            Font, Essentials ],
                toolbar: [
                    'undo',
                    'redo',
                    '|',
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'underline',
                    '|',
                    'link',
                    'insertTable',
                    'blockQuote',
                    '|',
                    'bulletedList',
                    'numberedList',
                    '|',
                    'outdent',
                    'indent'
                ]
            } )
            .then( /* ... */ )
            .catch( /* ... */ );
    </script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
      $('.select-multiple').select2();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/iqbalrizqi/Desktop/app/laravel/vivia-app/resources/views/admin/products/edit.blade.php ENDPATH**/ ?>