<?php $__env->startSection('content'); ?>
	<!-- header end -->
	<div class="breadcrumb-area pt-205 breadcrumb-padding pb-210" style="margin-top: 12rem;">
		<div class="container">
			<div class="breadcrumb-content text-center">
				<h2>Order Received</h2>
		</div>
	</div>
	<!-- checkout-area start -->
	<div class="cart-main-area  ptb-100">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php if(session()->has('message')): ?>
                    <div class="content-header mb-0 pb-0">
                        <div class="container-fluid">
                            <div class="mb-0 alert alert-<?php echo e(session()->get('alert-type')); ?> alert-dismissible fade show" role="alert">
                                <strong><?php echo e(session()->get('message')); ?></strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div><!-- /.container-fluid -->
                    </div>
                <?php endif; ?>
					<h1 class="cart-heading">Your Order:</h4>
					<div class="row">
						<div class="col-xl-3 col-lg-4">
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
						<div class="col-xl-3 col-lg-4">
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
						<div class="col-xl-3 col-lg-4">
							<p class="text-dark mb-2" style="font-weight: normal; font-size:16px; text-transform: uppercase;">Details</p>
							<address>
								Invoice ID:
								<span class="text-dark">#<?php echo e($order->code); ?></span>
								<br> <?php echo e($order->order_date); ?>

								<br> Status: <?php echo e($order->status); ?>

								<br> Payment Status: <?php echo e($order->payment_status); ?>

								<br> Shipped by: <?php echo e($order->shipping_service_name); ?>

								<?php if($order->tracking_number != null): ?>
                                    <br> Shipping Number: <?php echo e($order->tracking_number); ?>

                                <?php endif; ?>
								<br> Payment Method : <?php echo e($order->payment_method); ?>

							</address>
						</div>
					</div>
					<div class="table-content table-responsive">
						<table class="table mt-3 table-striped table-responsive table-responsive-large" style="width:100%">
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
											$showAttributes .= '<ul class="item-attributes list-unstyled">';
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
										<td>Rp<?php echo e(number_format($item->base_price,0,",",".")); ?></td>
										<td>Rp<?php echo e(number_format($item->sub_total,0,",",".")); ?></td>
									</tr>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
									<tr>
										<td colspan="6">Order item not found!</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
					<div class="row">
						<div class="col-md-5 ml-auto">
							<div class="cart-page-total">
								<ul>
									<li> Subtotal
										<span>Rp<?php echo e(number_format($order->base_total_price, 0 ,",", ".")); ?></span>
									</li>
									<li>Tax (10%)
										<span>Rp<?php echo e(number_format($order->tax_amount,0,",",".")); ?></span>
									</li>
									<li>Shipping Cost
										<span>Rp<?php echo e(number_format($order->shipping_cost,0,",",".")); ?></span>
									</li>
									<li>Unique Code
										<span>Rp<?php echo e(number_format(($order->grand_total - ($order->base_total_price + $order->shipping_cost)),0,",",".")); ?></span>
									</li>
									<li>Total
										<span>Rp<?php echo e(number_format($order->grand_total, 0,",", ".")); ?></span>
									</li>
								</ul>
								<?php if(!$order->isPaid() && $order->payment_method == 'automatic'): ?>
									<button class="btn btn-success mt-3 d-none" id="pay-button">Proceed to payment</button>
								<?php elseif(!$order->isPaid() && $order->payment_method == 'manual'): ?>
									<a class="btn btn-success mt-3" href="<?php echo e(route('orders.confirmation_payment', $order->id)); ?>">Proceed to payment</a>
                                <?php elseif(!$order->isPaid() && $order->payment_method == 'qris'): ?>
									<a class="btn btn-success mt-3" href="<?php echo e(route('orders.confirmation_payment', $order->id)); ?>">Proceed to payment</a>
								<?php elseif(!$order->isPaid() && $order->payment_method == 'cod'): ?>
									<h1 class="text-center">Silahkan Lakukan Pembayaran ke Toko</h1>
									<a href="<?php echo e(route('orders.index')); ?>" class="btn btn-primary">Kembali</a>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-alt'); ?>
	<?php if($order->payment_method == 'automatic' && $order->payment_status == 'unpaid' && !empty($order->payment_token)): ?>
		<!-- Load Midtrans JS library -->
		<script type="text/javascript" src="<?php echo e($paymentData['snapUrl']); ?>"
				data-client-key="<?php echo e($paymentData['midtransClientKey']); ?>"></script>

		<script type="text/javascript">
			document.addEventListener('DOMContentLoaded', function() {
				// Show payment button
				const payButton = document.getElementById('pay-button');
				if (payButton) {
					payButton.classList.remove('d-none');

					// Handle button click to open Snap payment page
					payButton.addEventListener('click', function() {
						snap.pay('<?php echo e($order->payment_token); ?>', {
							onSuccess: function(result) {
								console.log('Payment success:', result);
								window.location.href = '<?php echo e(route('payment.finish')); ?>?order_id=<?php echo e($order->code); ?>';
							},
							onPending: function(result) {
								console.log('Payment pending:', result);
								window.location.href = '<?php echo e(route('payment.unfinish')); ?>?order_id=<?php echo e($order->code); ?>';
							},
							onError: function(result) {
								console.log('Payment error:', result);
								window.location.href = '<?php echo e(route('payment.error')); ?>?order_id=<?php echo e($order->code); ?>';
							},
							onClose: function() {
								console.log('Customer closed the payment window');
							}
						});
					});
				}
			});
		</script>
	<?php endif; ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('frontend.layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/iqbalrizqi/Desktop/app/laravel/vivia-app/resources/views/frontend/orders/received.blade.php ENDPATH**/ ?>