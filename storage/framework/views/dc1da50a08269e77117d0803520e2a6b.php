<?php $__env->startSection('content'); ?>

    <!-- Main content -->
    <section class="content pt-4">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Data Gambar Produk</h3>
                <a href="<?php echo e(route('admin.products.edit', $product)); ?>" class="btn btn-success shadow-sm float-right"> <i class="fa fa-arrow-left"></i> Kembali </a>
                <a href="<?php echo e(route('admin.products.product_images.create', $product)); ?>" class="btn btn-success shadow-sm float-right mr-2"> <i class="fa fa-upload"></i> Upload </a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="table-responsive">
                    <table id="data-table" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $product->productImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $productImage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td>
                                  <a href="<?php echo e(asset('/storage/'. $productImage->path)); ?>" target="_blank">
                                    <img width="100" src="<?php echo e(asset('/storage/'. $productImage->path)); ?>" alt="">
                                  </a>
                                </td>
                                <td>
                                <div class="btn-group btn-group-sm">
                                    <form onclick="return confirm('are you sure !')" action="<?php echo e(route('admin.products.product_images.destroy', [$product, $productImage])); ?>"
                                        method="POST">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button class="btn btn-sm btn-danger" type="submit"><i class="fa fa-trash"></i></button>
                                    </form>
                                </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center">Data Kosong !</td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
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
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script-alt'); ?> 
    <script
        src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU="
        crossorigin="anonymous"
    >
    </script>
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script>
    $("#data-table").DataTable();
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/iqbalrizqi/Desktop/app/laravel/vivia-app/resources/views/admin/product_images/index.blade.php ENDPATH**/ ?>