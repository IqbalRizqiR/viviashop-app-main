@extends('frontend.layouts')

@section('content')
<div class="breadcrumb-area pt-205 breadcrumb-padding pb-210" style="background-image: url({{ asset('themes/ezone/assets/img/bg/breadcrumb.jpg') }})">
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
							<form method="POST" action="{{ route('login') }}">
								@csrf
								<div class="form-group row">
									<div class="col-md-12">
										<input id="email" type="email" class="form-control mt-4 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('E-Mail Address') }}">
										@error('email')
											<span class="invalid-feedback" role="alert">
												<strong>{{ $message }}</strong>
											</span>
										@enderror
									</div>
								</div>
								<div class="form-group row mt-3 mb-4">
									<div class="col-md-12">
										<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="{{ __('Password') }}">
										@error('password')
											<span class="invalid-feedback" role="alert">
												<strong>{{ $message }}</strong>
											</span>
										@enderror
									</div>
								</div>
								<div class="form-group row mb-3">
									<div class="col-md-12">
										<div class="button-box">
                                            <div class="login-toggle-btn mb-3 mt-2">
                                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <label for="remember">{{ __('Remember Me') }}</label>
                                                <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                                            </div>
											<div class="login-toggle-btn mb-3 mt-2">
                                                <a href="{{ route('register') }}">Create Your Account</a>
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
@endsection