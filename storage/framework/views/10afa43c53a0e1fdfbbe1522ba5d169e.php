<?php $__env->startSection('content'); ?>

    <!-- Main content -->
    <section class="content pt-4">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Data Produk</h3>
                <a href="<?php echo e(route('admin.products.create')); ?>" class="btn btn-success shadow-sm float-right"> <i class="fa fa-plus"></i> Tambah </a>
                <button onclick="addForm();" class="btn btn-success shadow-sm float-right"> <i class="fa fa-plus"></i> Excel </button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="table-responsive">
                    <table id="data-table" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>SKU</th>
                        <th>Tipe</th>
                        <th>Nama Produk</th>
                        <th>Harga Jual</th>
                        <th>Harga Beli</th>
                        <th>Status</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td><?php echo e($product->sku); ?></td>
                                <td><?php echo e($product->type); ?></td>
                                <td><?php echo e($product->name); ?></td>
                                <td><?php echo e(number_format($product->price)); ?></td>
                                <td><?php echo e(number_format($product->harga_beli)); ?></td>
                                <td><?php echo e($product->statusLabel()); ?></td>
                                <?php if($product->productInventory != null): ?>
                                  <td><?php echo e($product->productInventory->qty); ?></td>
                                <?php else: ?>
                                  <td>No quantity</td>
                                <?php endif; ?>
                                <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?php echo e(route('admin.products.edit', $product)); ?>" class="btn btn-sm btn-primary">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form onclick="return confirm('are you sure !')" action="<?php echo e(route('admin.products.destroy', $product)); ?>"
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
                        </tbody>
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
    <?php if ($__env->exists('admin.products.form')) echo $__env->make('admin.products.form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('style-alt'); ?>
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script-alt'); ?>
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script>

    $("#data-table").DataTable();
    function addForm() {
        $('#modal-supplier').modal('show');
        $('#modal-supplier').addClass('show');
    }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/iqbalrizqi/Desktop/app/laravel/vivia-app/resources/views/admin/products/index.blade.php ENDPATH**/ ?>