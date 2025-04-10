<?php $__env->startSection('content'); ?>
<div class="breadcrumb-area pt-205 breadcrumb-padding pb-210" style="background-image: url(<?php echo e(asset('themes/ezone/assets/img/bg/breadcrumb.jpg')); ?>)">
	<div class="container-fluid">
		<div class="breadcrumb-content text-center">
			<h2>Login</h2>
			<ul>
				<li><a href="#">home</a></li>
				<li>login</li>
			</ul>
		</div>
	</div>
</div>
<!-- register-area start -->
<div class="register-area ptb-100">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 col-12 col-lg-12 mx-auto col-xl-6 ml-auto mr-auto" style="margin-top: 8rem;">
				<div class="login">
					<div class="login-form-container">
						<div class="login-form">
							<form method="POST" action="<?php echo e(route('login')); ?>">
								<?php echo csrf_field(); ?>
								<div class="form-group row">
									<div class="col-md-12">
										<input id="email" type="email" class="form-control mt-4 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email" autofocus placeholder="<?php echo e(__('E-Mail Address')); ?>">
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
								<div class="form-group row mt-3 mb-4">
									<div class="col-md-12">
										<input id="password" type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password" required autocomplete="current-password" placeholder="<?php echo e(__('Password')); ?>">
										<?php $__errorArgs = ['password'];
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
								<div class="form-group row mb-3">
									<div class="col-md-12">
										<div class="button-box">
                                            <div class="login-toggle-btn mb-3 mt-2">
                                                <input class="form-check-input" type="checkbox" name="remember" id="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                                                <label for="remember"><?php echo e(__('Remember Me')); ?></label>
                                                <a href="<?php echo e(route('password.request')); ?>"><?php echo e(__('Forgot Your Password?')); ?></a>
                                            </div>
											<div class="login-toggle-btn mb-3 mt-2">
                                                <a href="<?php echo e(route('register')); ?>">Create Your Account</a>
                                            </div>
                                            <button type="submit" class="default-btn floatright">Login</button>
                                        </div>
									</div>
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
<?php echo $__env->make('frontend.layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/iqbalrizqi/Desktop/app/laravel/vivia-app/resources/views/frontend/auth/login.blade.php ENDPATH**/ ?>