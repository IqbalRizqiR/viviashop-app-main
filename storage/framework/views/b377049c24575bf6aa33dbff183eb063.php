<?php $__env->startSection('content'); ?>
	<div class="breadcrumb-area pt-205 breadcrumb-padding pb-210" style="background-image: url(<?php echo e(asset('themes/ezone/assets/img/bg/breadcrumb.jpg')); ?>); margin-top: 12rem;">
	</div>
	<div class="shop-page-wrapper shop-page-padding ptb-100">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-3">
					<?php echo $__env->make('frontend.partials.user_menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
					<form action="<?php echo e(route('logout')); ?>" method="post">
						<?php echo csrf_field(); ?>
						<button class="btn btn-primary">Logout</button>
					</form>
				</div>
				<div class="col-lg-9">
                    <?php if(session()->has('message')): ?>
                        <div class="content-header mb-3 pb-0">
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
					<div class="login">
						<div class="login-form-container">
							<div class="login-form">
                                    <form action="<?php echo e(url('profile')); ?>" method="post">
									<?php echo csrf_field(); ?>
                                    <?php echo method_field('put'); ?>
									<div class="form-group row mb-4">
										<div class="col-md-6">
                                            <div class="checkout-form-list">
                                                <label>Nama <span class="required">*</span></label>
                                                <input type="text" class="form-control" name="name" value="<?php echo e(old('name', auth()->user()->name)); ?>">
                                            </div>
                                            <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
												<span class="invalid-feedback" role="alert">
													<strong><?php echo e($message); ?></strong>
												</span>
											<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
										</div>
									</div>

									<div class="form-group row mb-4">
										<div class="col-md-12">
                                            <div class="checkout-form-list">
                                                <label>Address <span class="required">*</span></label>
                                                <input class="form-control" type="text" name="address1" value="<?php echo e(old('address1', auth()->user()->address1)); ?>">
                                            </div>
                                            <?php $__errorArgs = ['address1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
												<span class="invalid-feedback" role="alert">
													<strong><?php echo e($message); ?></strong>
												</span>
											<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
										</div>
									</div>

									<div class="form-group row mb-4">
										<div class="col-md-12">
                                            <div class="checkout-form-list">
                                                <input class="form-control" type="text" name="address2" value="<?php echo e(old('address2', auth()->user()->address2)); ?>">
                                            </div>
                                            <?php $__errorArgs = ['address2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
												<span class="invalid-feedback" role="alert">
													<strong><?php echo e($message); ?></strong>
												</span>
											<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
										</div>
									</div>

									<div class="form-group row mb-4">
										<div class="col-md-6">
                                            <label>Provinsi<span class="required">*</span></label>
                                            <select class="form-control" name="province_id" id="shipping-provinces">
                                                <?php $__currentLoopData = $provinces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $province): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($id); ?>" <?php echo e($id == auth()->user()->province_id ? 'selected' : ''); ?>><?php echo e($province); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <?php $__errorArgs = ['province_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
												<span class="invalid-feedback" role="alert">
													<strong><?php echo e($message); ?></strong>
												</span>
											<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
										</div>
										<div class="col-md-6">
                                            <label>City<span class="required">*</span></label>
                                            <select class="form-control" name="city_id" id="shipping-cities">
                                                <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($id); ?>" <?php echo e($id == auth()->user()->city_id ? 'selected' : ''); ?>><?php echo e($city); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <?php $__errorArgs = ['city_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
												<span class="invalid-feedback" role="alert">
													<strong><?php echo e($message); ?></strong>
												</span>
											<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
										</div>
									</div>

									<div class="form-group row mb-4">
										<div class="col-md-6">
                                            <div class="checkout-form-list">
                                                <label>Postcode / Zip <span class="required">*</span></label>
                                                <input class="form-control" type="text" name="postcode" value="<?php echo e(old('postcode', auth()->user()->postcode)); ?>">
                                            </div>
                                            <?php $__errorArgs = ['postcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
												<span class="invalid-feedback" role="alert">
													<strong><?php echo e($message); ?></strong>
												</span>
											<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
										</div>
										<div class="col-md-6">
                                            <div class="checkout-form-list">
                                                <label>Phone  <span class="required">*</span></label>
                                                <input class="form-control" type="text" name="phone" value="<?php echo e(old('phone', auth()->user()->phone)); ?>">
                                            </div>
											<?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
												<span class="invalid-feedback" role="alert">
													<strong><?php echo e($message); ?></strong>
												</span>
											<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
										</div>
									</div>

									<div class="form-group row mb-4">
										<div class="col-md-12">
                                            <input class="form-control" name="email" type="email" value="<?php echo e(old('email', auth()->user()->email)); ?>" class="form-control" placeholder="Email">
											<?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
												<span class="invalid-feedback" role="alert">
													<strong><?php echo e($message); ?></strong>
												</span>
											<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
										</div>
									</div>
									<div class="button-box">
										<button type="submit" class="default-btn floatright">Update Profile</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- register-area end -->
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-alt'); ?>
	<script>
		$("#shipping-provinces").on("change", function (e) {
			var province_id = e.target.value;

			$("#loader").show();
			$.get("/orders/cities?province_id=" + province_id, function (data) {
				console.log(data);
				if (data) {
					$("#loader").hide();
				}
				$("#shipping-cities").empty();
				$("#shipping-cities").append(
					"<option value>- Please Select -</option>"
				);

				$.each(data.cities, function (city_id, city) {
					$("#shipping-cities").append(
						'<option value="' + city_id + '">' + city + "</option>"
					);
				});
			});
		});
	</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('frontend.layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/iqbalrizqi/Desktop/app/laravel/vivia-app/resources/views/frontend/auth/profile.blade.php ENDPATH**/ ?>