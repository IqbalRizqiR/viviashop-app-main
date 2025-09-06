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
										<div class="col-md-4">
                                            <label>Provinsi<span class="required">*</span></label>
                                            <select class="form-control" name="province_id" id="shipping-province">
                                                <option value="">-- Pilih Provinsi --</option>
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
										<div class="col-md-4">
                                            <label>City<span class="required">*</span></label>
                                            <select class="form-control" name="city_id" id="shipping-city">
                                                <option value="">-- Pilih Kota --</option>
                                                @if($cities)
                                                    @foreach($cities as $id => $city)
                                                        <option value="{{ $id }}" {{ $id == auth()->user()->city_id ? 'selected' : '' }}>{{ $city }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('city_id')
												<span class="invalid-feedback" role="alert">
													<strong>{{ $message }}</strong>
												</span>
											@enderror
										</div>
										<div class="col-md-4">
                                            <label>District<span class="required">*</span></label>
                                            <select class="form-control" name="district_id" id="shipping-district">
                                                <option value="">-- Pilih Kecamatan --</option>
                                                @if($districts)
                                                    @foreach($districts as $id => $district)
                                                        <option value="{{ $id }}" {{ $id == auth()->user()->district_id ? 'selected' : '' }}>{{ $district }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('district_id')
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
		$(document).ready(function(){
			console.log('Profile page initialized');
			
			// Setup CSRF token for all AJAX requests
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			
			// Remove any existing event handlers to prevent duplicates
			$('#shipping-province').off('change');
			$('#shipping-city').off('change');
			
			// Auto-load cities if province is selected
			var userProvinceId = "{{ auth()->user()->province_id }}";
			console.log('User province ID:', userProvinceId);
			
			if (userProvinceId) {
				// Set the province dropdown value first
				$('#shipping-province').val(userProvinceId);
				// Then trigger change to load cities
				$('#shipping-province').trigger('change');
			}
		});

		$(document).on('change', '#shipping-province', function() {
			var province_id = $(this).val();
			console.log('Province changed to:', province_id);
			
			if (province_id) {
				var cityUrl = "{{ url('api/cities') }}/" + province_id + '?t=' + Date.now();
				console.log('Fetching cities from:', cityUrl);
				
				$.ajax({
					url: cityUrl,
					type: 'GET',
					success: function(response) {
						console.log('Cities loaded:', response.length, 'cities');
						var options = '<option value="">-- Pilih Kota --</option>';
						
						if (response && Array.isArray(response)) {
							$.each(response, function(index, city) {
								var selected = city.id == '{{ auth()->user()->city_id }}' ? 'selected' : '';
								options += '<option value="' + city.id + '" ' + selected + '>' + city.name + '</option>';
							});
						}
						
						$('#shipping-city').html(options);
						$('#shipping-district').html('<option value="">-- Pilih Kecamatan --</option>');
						
						// Auto-load districts if city is selected
						var selectedCityId = $('#shipping-city').val();
						if (selectedCityId) {
							console.log('Auto-loading districts for city:', selectedCityId);
							loadDistricts(selectedCityId);
						}
					},
					error: function(xhr, status, error) {
						console.error('Error loading cities:', error);
						$('#shipping-city').html('<option value="">Error loading cities</option>');
					}
				});
			} else {
				$('#shipping-city').html('<option value="">-- Pilih Kota --</option>');
				$('#shipping-district').html('<option value="">-- Pilih Kecamatan --</option>');
			}
		});
		
		$(document).on('change', '#shipping-city', function() {
			var city_id = $(this).val();
			console.log('City changed to:', city_id);
			loadDistricts(city_id);
		});
		
		function loadDistricts(city_id) {
			if (city_id) {
				var districtUrl = "{{ url('api/districts') }}/" + city_id + '?t=' + Date.now();
				console.log('Loading districts from:', districtUrl);
				
				$.ajax({
					url: districtUrl,
					type: 'GET',
					success: function(response) {
						console.log('Districts loaded:', response.length, 'districts');
						var options = '<option value="">-- Pilih Kecamatan --</option>';
						
						if (response && Array.isArray(response)) {
							$.each(response, function(index, district) {
								var selected = district.id == '{{ auth()->user()->district_id }}' ? 'selected' : '';
								options += '<option value="' + district.id + '" ' + selected + '>' + district.name + '</option>';
							});
						}
						$('#shipping-district').html(options);
					},
					error: function(xhr, status, error) {
						console.error('Error loading districts:', error);
						$('#shipping-district').html('<option value="">Error loading districts</option>');
					}
				});
			} else {
				$('#shipping-district').html('<option value="">-- Pilih Kecamatan --</option>');
			}
		}
	</script>
@endpush
