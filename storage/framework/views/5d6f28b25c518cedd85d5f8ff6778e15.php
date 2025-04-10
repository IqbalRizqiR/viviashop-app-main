<?php $__env->startSection('content'); ?>

    <!-- Main content -->
    <section class="content pt-4">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Data Order</h3>
                <a href="<?php echo e(route('admin.orders.trashed')); ?>" class="btn btn-danger shadow-sm float-right"> <i class="fa fa-trash"></i> Trash </a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              <form action="" class="input-daterange form-inline mb-4">
                <div class="form-group mb-2">
                  <input type="text" class="form-control input-block" name="q" value="<?php echo e(!empty(request()->input('q')) ? request()->input('q') : ''); ?>" placeholder="Type code or name"> 
                </div>
                <div class="form-group mx-sm-3 mb-2">
                  <input type="text" class="form-control datepicker" readonly="" value="<?php echo e(!empty(request()->input('start')) ? request()->input('start') : ''); ?>" name="start" placeholder="from">
                </div>
                <div class="form-group mx-sm-3 mb-2">
                  <input type="text" class="form-control datepicker" readonly="" value="<?php echo e(!empty(request()->input('end')) ? request()->input('end') : ''); ?>" name="end" placeholder="to">
                </div>
                <div class="form-group mx-sm-3 mb-2">
                  <select class="form-control input-block" name="status" id="status">
                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <option value="<?php echo e($value); ?>"><?php echo e($status); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </select>
                </div>
                <div class="form-group mx-sm-3 mb-2">
                  <button type="submit" class="btn btn-success shadow-sm float-right">Cari</button>
                </div>
                </form>
                <div class="table-responsive">
                    <table id="data-table" class="table table-bordered table-striped">
                        <thead>
                            <th>Order ID</th>
                            <th>Grand Total</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>    
                                    <td>
                                        <?php echo e($order->code); ?><br>
                                        <span style="font-size: 12px; font-weight: normal"> <?php echo e($order->order_date); ?></span>
                                    </td>
                                    <td>Rp<?php echo e(number_format($order->grand_total,0,",", ".")); ?></td>
                                    <td>
                                        <?php echo e($order->customer_full_name); ?><br>
                                        <span style="font-size: 12px; font-weight: normal"> <?php echo e($order->customer_email); ?></span>
                                    </td>
                                    <td><?php echo e($order->status); ?></td>
                                    <td><?php echo e($order->payment_status); ?></td>
                                    <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo e(route('admin.orders.show', $order->id)); ?>" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> </a>
                                        <form onclick="return confirm('are you sure !')" action="<?php echo e(route('admin.orders.destroy', $order)); ?>"
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
                                    <td colspan="6">No records found</td>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('style-alt'); ?>
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
		$('.datepicker').datepicker({
			format: 'yyyy-mm-dd'
		});
		$(".delete").on("submit", function () {
			return confirm("Do you want to remove this?");
		});
		$("a.delete").on("click", function () {
			event.preventDefault();
			var orderId = $(this).attr('order-id');
			if (confirm("Do you want to remove this?")) {
				document.getElementById('delete-form-' + orderId ).submit();
			}
		});
		$(".restore").on("click", function () {
			return confirm("Do you want to restore this?");
		});
	</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/iqbalrizqi/Desktop/app/laravel/vivia-app/resources/views/admin/orders/index.blade.php ENDPATH**/ ?>