<?php $__env->startSection('content'); ?>
	<div class="breadcrumb-area pt-205 breadcrumb-padding pb-210" style="background-image: url(<?php echo e(asset('themes/ezone/assets/img/bg/breadcrumb.jpg')); ?>)">
		<div class="container-fluid">
			<div class="breadcrumb-content text-center">
				<h2>My Order</h2>
				<ul>
					<li><a href="<?php echo e(url('/')); ?>">home</a></li>
					<li>my order</li>
				</ul>
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
					<div class="d-flex justify-content-between">
						<h2 class="text-dark font-weight-medium">Order ID #<?php echo e($order->code); ?></h2>
						
					</div>
					<div class="row pt-5">
						<div class="col-xl-4 col-lg-4">
							<p class="text-dark mb-2" style="font-weight: normal; font-size:16px; text-transform: uppercase;">Billing Address</p>
							<address>
								<?php echo e($order->customer_first_name); ?> <?php echo e($order->customer_last_name); ?>

								<br> <?php echo e($order->customer_address1); ?>

								<br> <?php echo e($order->customer_address2); ?>

								<br> Email: <?php echo e($order->customer_email); ?>

								<br> Phone: <?php echo e($order->customer_phone); ?>

								<br> Postcode: <?php echo e($order->customer_postcode); ?>

							</address>
						</div>
						<?php if($order->shipment != null): ?>
							<div class="col-xl-4 col-lg-4">
								<p class="text-dark mb-2" style="font-weight: normal; font-size:16px; text-transform: uppercase;">Shipment Address</p>
								<address>
									<?php echo e($order->shipment->first_name); ?> <?php echo e($order->shipment->last_name); ?>

									<br> <?php echo e($order->shipment->address1); ?>

									<br> <?php echo e($order->shipment->address2); ?>

									<br> Email: <?php echo e($order->shipment->email); ?>

									<br> Phone: <?php echo e($order->shipment->phone); ?>

									<br> Postcode: <?php echo e($order->shipment->postcode); ?>

								</address>
							</div>
						<?php endif; ?>
						<div class="col-xl-4 col-lg-4">
							<p class="text-dark mb-2" style="font-weight: normal; font-size:16px; text-transform: uppercase;">Details</p>
							<address>
								ID: <span class="text-dark">#<?php echo e($order->code); ?></span>
								<br> <?php echo e(date('d M Y', strtotime($order->order_date))); ?>

								<br> Status: <?php echo e($order->status); ?> <?php echo e($order->isCancelled() ? '('. date('d M Y', strtotime($order->cancelled_at)) .')' : null); ?>

								<?php if($order->isCancelled()): ?>
									<br> Cancellation Note : <?php echo e($order->cancellation_note); ?>

								<?php endif; ?>
								<br> Payment Status: <?php echo e($order->payment_status); ?>

								<br> Shipped by: <?php echo e($order->shipping_service_name); ?>

								<?php
									$resi = \App\Models\Shipment::where('order_id', $order->id)->pluck('track_number')->first();
								?>
								<br> Tracking Number: <?php echo e($resi); ?>

							</address>
						</div>
					</div>
					<div class="table-content table-responsive">
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>#</th>
									<th>Item</th>
									<th>Description</th>
									<th>Quantity</th>
									<th>Unit Cost</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>
								<?php 
									function showAttributes($jsonAttributes)
									{
										$jsonAttr = (string) $jsonAttributes;
										$attributes = json_decode($jsonAttr, true);
										$showAttributes = '';
										if ($attributes) {
											$showAttributes .= '<ul class="item-attributes">';
											foreach ($attributes as $key => $attribute) {
												if(count($attribute) != 0){
													foreach($attribute as $value => $attr){
														$showAttributes .= '<li>'.$value . ': <span>' . $attr . '</span><li>';
													}
												}else {
													$showAttributes .= '<li><span> - </span></li>';
												}
											}
											$showAttributes .= '</ul>';
										}
										return $showAttributes;
									}
								?>
								<?php $__empty_1 = true; $__currentLoopData = $order->orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
									<tr>
										<td><?php echo e($item->sku); ?></td>
										<td><?php echo e($item->name); ?></td>
										<td><?php echo showAttributes($item->attributes); ?></td>
										<td><?php echo e($item->qty); ?></td>
										<td><?php echo e(number_format($item->base_price, 0, ",", ".")); ?></td>
										<td><?php echo e(number_format($item->sub_total, 0, ",", ".")); ?></td>
									</tr>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
									<tr>
										<td colspan="6">Order item not found!</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
						<?php if($order->isDelivered()): ?>
							<a href="#" class="btn btn-block mt-2 btn-lg btn-success btn-pill" onclick="event.preventDefault();
							document.getElementById('complete-form-<?php echo e($order->id); ?>').submit();"> Mark as Completed</a>		
							<form class="d-none" method="POST" action="<?php echo e(route('admin.orders.complete', $order)); ?>" id="complete-form-<?php echo e($order->id); ?>">
								<?php echo csrf_field(); ?>
							</form>				
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/iqbalrizqi/Desktop/app/laravel/vivia-app/resources/views/frontend/orders/show.blade.php ENDPATH**/ ?>