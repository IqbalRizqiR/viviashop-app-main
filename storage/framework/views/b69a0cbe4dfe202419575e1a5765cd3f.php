<?php $__env->startSection('content'); ?>
<div class="content pt-4">
	<div class="row">
		<div class="col-lg-6">
			<div class="card card-default">
				<div class="card-header card-header-border-bottom">
					<h2>Order Shipment #<?php echo e($shipment->order->code); ?></h2>
				</div>
				<div class="card-body">
                    <form action="<?php echo e(route('admin.shipments.update', $shipment)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('put'); ?>
                        <div class="form-group">
                            <label for="track_number">Nomer Resi</label>
                            <input type="text" name="track_number" class="form-control">
                        </div>
                        <div class="form-footer pt-5 border-top">
                            <button type="submit" class="btn btn-success">Save</button>
                            <a href="<?php echo e(url('admin/orders/'. $shipment->order->id)); ?>" class="btn bg-dark">Kembali</a>
                        </div>
                    </form>
				</div>
			</div>  
		</div>
		<div class="col-lg-6">
			<div class="card card-default">
				<div class="card-header card-header-border-bottom">
					<h2>Detail Order</h2>
				</div>
				<div class="card-body">
					<div class="row mb-2">
						<div class="col-xl-6 col-lg-6">
							<p class="text-dark mb-2" style="font-weight: normal; font-size:16px; text-transform: uppercase;">Billing Address</p>
							<address>
								<?php echo e($shipment->order->customer_first_name); ?> <?php echo e($shipment->order->customer_last_name); ?>

								<?php echo e($shipment->order->customer_address1); ?>

								<?php echo e($shipment->order->customer_address2); ?>

								<br> Email: <?php echo e($shipment->order->customer_email); ?>

								<br> Phone: <?php echo e($shipment->order->customer_phone); ?>

								<br> Postcode: <?php echo e($shipment->order->customer_postcode); ?>

							</address>
						</div>
						<div class="col-xl-6 col-lg-6">
							<p class="text-dark mb-2" style="font-weight: normal; font-size:16px; text-transform: uppercase;">Details</p>
							<address>
								ID: <span class="text-dark">#<?php echo e($shipment->order->code); ?></span>
								<br> <?php echo e($shipment->order->order_date); ?>

								<br> Status: <?php echo e($shipment->order->status); ?>

								<br> Payment Status: <?php echo e($shipment->order->payment_status); ?>

								<br> Shipped by: <?php echo e($shipment->order->shipping_service_name); ?>

							</address>
						</div>
					</div>
					<div class="table-responsive">
                        <table id="data-table" class="table mt-3 table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $shipment->order->orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($item->sku); ?></td>
                                        <td><?php echo e($item->name); ?></td>
                                        <td><?php echo e($item->qty); ?></td>
                                        <td><?php echo e($item->sub_total); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6">Order item not found!</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
					<div class="row justify-content-end">
						<div class="col-lg-5 col-xl-6 col-xl-3 ml-sm-auto">
							<ul class="list-unstyled mt-4">
								<li class="mid pb-3 text-dark">Subtotal
									<span class="d-inline-block float-right text-default"><?php echo e($shipment->order->base_total_price); ?></span>
								</li>
								<li class="mid pb-3 text-dark">Tax(10%)
									<span class="d-inline-block float-right text-default"><?php echo e($shipment->order->tax_amount); ?></span>
								</li>
								<li class="mid pb-3 text-dark">Shipping Cost
									<span class="d-inline-block float-right text-default"><?php echo e($shipment->order->shipping_cost); ?></span>
								</li>
								<li class="pb-3 text-dark">Total
									<span class="d-inline-block float-right"><?php echo e($shipment->order->grand_total); ?></span>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/iqbalrizqi/Desktop/app/laravel/vivia-app/resources/views/admin/shipments/edit.blade.php ENDPATH**/ ?>