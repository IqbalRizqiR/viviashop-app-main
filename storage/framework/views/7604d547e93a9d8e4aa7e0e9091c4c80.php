<?php $__env->startSection('content'); ?>
	<div class="breadcrumb-area pt-205 breadcrumb-padding pb-210" style="margin-top: 12rem;">
		<div class="container-fluid">
			<div class="breadcrumb-content text-center">
				<h2>My Order</h2>
			</div>
		</div>
	</div>
	<div class="shop-page-wrapper shop-page-padding ptb-100">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-3">
					<?php echo $__env->make('frontend.partials.user_menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
				</div>
				<div class="col-lg-9">
					<div class="shop-product-wrapper res-xl">
						<div class="table-content table-responsive">
							<table class="table table-bordered table-striped">
								<thead>
									<th>Order ID</th>
									<th>Grand Total</th>
									<th>Nomer Resi</th>
									<th>Status</th>
									<th>Payment Status</th>
									<th>Payment Method</th>
									<th>Action</th>
								</thead>
								<tbody>
									<?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
										<tr>
											<td>
												<?php echo e($order->code); ?><br>
												<span style="font-size: 12px; font-weight: normal"> <?php echo e(date('d M Y', strtotime($order->order_date))); ?></span>
											</td>
											<td>Rp<?php echo e(number_format($order->grand_total, 0, ",", ".")); ?></td>
											<?php if($order->shipment != NULL): ?>
												<?php if($order->shipment->track_number != NULL): ?>
													<td><?php echo e($order->shipment->track_number); ?></td>
												<?php else: ?>
													<td>Belum ada resi</td>
												<?php endif; ?>
											<?php else: ?>
												<td>Belum ada resi</td>
											<?php endif; ?>
											<td><?php echo e($order->status); ?></td>
											<td><?php echo e($order->payment_status); ?></td>
											<td><?php echo e($order->payment_method); ?></td>
											<td>
												<?php if($order->payment_method == 'manual' || $order->payment_method == 'qris'): ?>
													<a href="<?php echo e(url('orders/'. $order->id)); ?>" class="btn btn-info btn-sm">details</a>
													<?php if($order->payment_status == 'unpaid'): ?>
													<a href="<?php echo e(route('orders.confirmation_payment', $order->id)); ?>" class="btn btn-info btn-sm">confirm payment</a>
													<?php endif; ?>
												<?php else: ?>
													<a href="<?php echo e(url('orders/'. $order->id)); ?>" class="btn btn-info btn-sm">details</a>
												<?php endif; ?>
											</td>
										</tr>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
										<tr>
											<td colspan="5">No records found</td>
										</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/iqbalrizqi/Desktop/app/laravel/vivia-app/resources/views/frontend/orders/index.blade.php ENDPATH**/ ?>