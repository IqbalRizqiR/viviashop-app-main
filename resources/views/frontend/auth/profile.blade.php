@extends('frontend.layouts')

@section('content')
	<div class="breadcrumb-area pt-205 breadcrumb-padding pb-210" style="background-image: url({{ asset('themes/ezone/assets/img/bg/breadcrumb.jpg') }}); margin-top: 12rem;">
	</div>
	<div class="shop-page-wrapper shop-page-padding ptb-100">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-3">
					@include('frontend.partials.user_menu')
				</div>
				<div class="col-lg-9">
                    @if(session()->has('message'))
                        <div class="content-header mb-3 pb-0">
                            <div class="container-fluid">
                                <div class="mb-0 alert alert-{{ session()->get('alert-type') }} alert-dismissible fade show" role="alert">
                                    <strong>{{ session()->get('message') }}</strong>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div><!-- /.container-fluid -->
                        </div>
                    @endif
					<div class="login">
						<div class="login-form-container">
							<div class="login-form">
                                    <form action="{{ url('profile') }}" method="post">
									@csrf
                                    @method('put')
									<div class="form-group row mb-4">
										<div class="col-md-6">
                                            <div class="checkout-form-list">
                                                <label>Nama <span class="required">*</span></label>
                                                <input type="text" class="form-control" name="name" value="{{ old('name', auth()->user()->name) }}">
                                            </div>
                                            @error('last_name')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
											@enderror
										</div>
									</div>

									<div class="form-group row mb-4">
										<div class="col-md-12">
                                            <div class="checkout-form-list">
                                                <label>Address <span class="required">*</span></label>
                                                <input class="form-control" type="text" name="address1" value="{{ old('address1', auth()->user()->address1) }}">
                                            </div>
                                            @error('address1')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
											@enderror
										</div>
									</div>

									<div class="form-group row mb-4">
										<div class="col-md-12">
                                            <div class="checkout-form-list">
                                                <input class="form-control" type="text" name="address2" value="{{ old('address2', auth()->user()->address2) }}">
                                            </div>
                                            @error('address2')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
											@enderror
										</div>
									</div>

									<div class="form-group row mb-4">
										<div class="col-md-6">
                                            <label>Provinsi<span class="required">*</span></label>
                                            <select class="form-control" name="province_id" id="shipping-provinces">
                                                @foreach($provinces as $id => $province)
                                                    <option value="{{ $id }}" {{ $id == auth()->user()->province_id ? 'selected' : '' }}>{{ $province }}</option>
                                                @endforeach
                                            </select>
                                            @error('province_id')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
											@enderror
										</div>
										<div class="col-md-6">
                                            <label>City<span class="required">*</span></label>
                                            <select class="form-control" name="city_id" id="shipping-cities">
                                                @foreach($cities as $id => $city)
                                                    <option value="{{ $id }}" {{ $id == auth()->user()->city_id ? 'selected' : '' }}>{{ $city }}</option>
                                                @endforeach
                                            </select>
                                            @error('city_id')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
											@enderror
										</div>
									</div>

									<div class="form-group row mb-4">
										<div class="col-md-6">
                                            <div class="checkout-form-list">
                                                <label>Postcode / Zip <span class="required">*</span></label>
                                                <input class="form-control" type="text" name="postcode" value="{{ old('postcode', auth()->user()->postcode) }}">
                                            </div>
                                            @error('postcode')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
											@enderror
										</div>
										<div class="col-md-6">
                                            <div class="checkout-form-list">
                                                <label>Phone  <span class="required">*</span></label>
                                                <input class="form-control" type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}">
                                            </div>
											@error('phone')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
											@enderror
										</div>
									</div>

									<div class="form-group row mb-4">
										<div class="col-md-12">
                                            <input class="form-control" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" class="form-control" placeholder="Email">
											@error('email')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
											@enderror
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
@endsection

@push('script-alt')
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
@endpush
