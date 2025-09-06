@extends('frontend.layouts')
@section('content')
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Checkout</h1>
        <ol class="breadcrumb justify-center mb-0">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Pages</a></li>
            <li class="breadcrumb-item active text-white">Checkout</li>
        </ol>
    </div>


    <!-- Checkout Page Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <h1 class="mb-4">Billing details</h1>
            <form action="{{ route('orders.checkout') }}" method="post" enctype="multipart/form-data" onsubmit="return handleFormSubmit(event)">
                @csrf
                <div class="row g-5">
                    <div class="col-md-12 col-lg-6 col-xl-7">
                        <div class="row">
                            <div class="col-md-12 col-lg-6">
                                <div class="form-item w-100">
                                    <label>Nama <span class="required">*</span></label>
									<input type="text" class="form-control" name="name" value="{{ old('name', auth()->user()->name) }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <label class="form-label my-3">Address <sup>*</sup></label>
							<input type="text" class="form-control" name="address1" value="{{ old('address1', auth()->user()->address1) }}">
                            <br>
							<input type="text" class="form-control" name="address2" value="{{ old('address2', auth()->user()->address2) }}">
                        </div>
                        <div class="form-item">
                            <label>Postcode / Zip <span class="required">*</span></label>
							<input type="text" class="form-control" name="postcode" value="{{ old('postcode', auth()->user()->postcode) }}">
                        </div>
                        <div class="form-item">
                            <label>Phone  <span class="required">*</span></label>
							<input type="text" class="form-control" name="phone" value="{{ old('phone', auth()->user()->phone) }}">
                        </div>
                        <div class="form-item">
                            <label>Email Address </label>
                            <input type="text" class="form-control" name="email" value="{{ old('email', auth()->user()->email) }}">
                        </div>
                                                <div class="form-item">
                            <label>Order Notes </label>
                            <input type="textarea" class="form-control" name="note" value="{{ old('note') }}">
                        </div>
                        <div class="form-item">
                            <label>Order Attachments (if exists) </label>
                            <input type="file" onchange="" id="image" class="form-control" name="attachments">
                        </div>
                        <div class="form-item mt-4 d-none image-item">
                            <label for="">Preview Image</label>
                            <img src="" class="img-preview img-fluid" alt="">
                        </div>
                        <div class="form-item address-fields" style="display: none;">
                            <label>Provinsi<span class="required">*</span></label>
                            <select name="province_id" class="form-control" id="shipping-province">
                                <option value="">-- Pilih Provinsi --</option>
                            </select>
                        </div>
                        <div class="form-item address-fields" style="display: none;">
                            <label>City<span class="required">*</span></label>
                            <select name="shipping_city_id" class="form-control" id="shipping-city">
                                <option value="">-- Pilih Kota --</option>
                            </select>
                        </div>
                        <div class="form-item address-fields" style="display: none;">
                            <label>District<span class="required">*</span></label>
                            <select name="shipping_district_id" class="form-control" id="shipping-district">
                                <option value="">-- Pilih Kecamatan --</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6 col-xl-5">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Products</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                    @php
                                        $product = isset($item->model->parent) ? $item->model->parent : $item->model;
                                        $image = !empty($product->productImages->first()) ? asset('storage/'.$product->productImages->first()->path) : asset('themes/ezone/assets/img/cart/3.jpg')
                                    @endphp
                                        <tr>
                                            <th scope="row">
                                                <div class="d-flex align-items-center mt-2">
                                                    <img src="{{ $image }}" class="img-fluid rounded" style="width: 90px; height: 90px;" alt="">
                                                </div>
                                            </th>
                                            <td class="py-5">{{ $product->name }}</td>
                                            <td class="py-5">Rp. {{ number_format($product->price) }}</td>
                                            <td class="py-5">{{ $item->qty }}</td>
                                            <td class="py-5">Rp. {{ number_format($item->price * $item->qty) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2">The cart is empty! </td>
                                        </tr>
                                    @endforelse
                                    <tr>
                                        <th scope="row">
                                        </th>
                                        <td class="py-5"></td>
                                        <td class="py-5"></td>
                                        <td class="py-5">
                                            <p class="mb-0 text-dark py-3">Subtotal</p>
                                        </td>
                                        <td class="py-5">
                                            <div class="py-3 border-bottom border-top">
                                                <p class="mb-0 text-dark">Rp. {{ Cart::subtotal(0, ",", ".") }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                        </th>
                                        <td class="py-5">
                                            <p class="mb-0 text-dark py-4">Delivery Method</p>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input" id="delivery-self" name="delivery_method" value="self" checked>
                                                <label class="form-check-label" for="delivery-self">
                                                    Self Pickup - Rp. 0 (Same Day)
                                                </label>
                                            </div>
                                            <div class="form-check mt-2">
                                                <input type="radio" class="form-check-input" id="delivery-courier" name="delivery_method" value="courier">
                                                <label class="form-check-label" for="delivery-courier">
                                                    Courier Delivery
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="shipping-row" style="display: none;">
                                        <th scope="row">
                                        </th>
                                        <td class="py-5">
                                            <p class="mb-0 text-dark py-4">Shipping</p>
                                        </td>
                                        <td><select class="form-control" id="shipping-cost-option" name="shipping_service">

										</select></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                        </th>
                                        <td class="py-5">
                                            <p class="mb-0 text-dark py-4">Unique Payment Code</p>
                                        </td>
                                        <td><input type="number" name="unique_code" value="{{ $unique_code }}" class="form-control unique_code" readonly></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                        </th>
                                        <td class="py-5">
                                            <p class="mb-0 text-dark text-uppercase py-3">TOTAL</p>
                                        </td>
                                        <td class="py-5"></td>
                                        <td class="py-5"></td>
                                        <td class="py-5">
                                            <div class="py-3 border-bottom border-top">
                                                <p class="mb-0 text-dark total-amount">{{ number_format((int)Cart::subtotal(0,'','')) }}</p>
                                            </div>
                                            <p>harap tunggu nominal berubah sesuai dengan total sebelum checkout</p>
                                            <!-- Debug buttons for testing -->
                                            <div style="margin-top: 10px;">
                                                <button type="button" id="test-update-total" class="btn btn-sm btn-info">🔄 Test Update Total</button>
                                                <button type="button" id="show-debug" class="btn btn-sm btn-warning">🐛 Show Debug</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row g-4 text-center align-items-center justify-content-center border-bottom py-3">
                            <div class="col-12">
                                <div class="form-check text-start my-3">
                                    <input type="radio" class="form-check-input payment-option bg-primary border-0" id="Transfer-1" name="payment_method" value="manual" checked>
                                    <label class="form-check-label" for="Transfer-1">Direct Bank Transfer</label>
                                </div>
                                <p class="text-start text-dark">You can pay to us via : <br> 1. BCA : 01401840112(Ahmad Sambudi) <br> 2. BCA : 01401840112(Ahmad Sambudi)</p>
                            </div>
                        </div>
                        <div class="row g-4 text-center align-items-center justify-content-center border-bottom py-3">
                            <div class="col-12">
                                <div class="form-check text-start my-3">
                                    <input type="radio" class="form-check-input payment-option bg-primary border-0" id="Automatic-1" name="payment_method" value="automatic">
                                    <label class="form-check-label" for="Automatic-1">Automatic Payment (Midtrans)</label>
                                </div>
                                <p class="text-start text-dark">Pay automatically using Credit Card, E-Wallet, Bank Transfer, or QR Code via Midtrans</p>
                            </div>
                        </div>
                        <div class="row g-4 text-center align-items-center justify-content-center border-bottom py-3">
                            <div class="col-12">
                                <div class="form-check text-start my-3">
                                    <input type="radio" class="form-check-input payment-option bg-primary border-0" id="COD-1" name="payment_method" value="cod">
                                    <label class="form-check-label" for="COD-1">Cash on Delivery (COD)</label>
                                </div>
                                <p class="text-start text-dark">Pay cash when the product is delivered to your location</p>
                            </div>
                        </div>
                        <div class="row g-4 text-center align-items-center justify-content-center border-bottom py-3">
                            <div class="col-12">
                                <div class="form-check text-start my-3">
                                    <input type="radio" class="form-check-input payment-option bg-primary border-0" id="Store-1" name="payment_method" value="toko">
                                    <label class="form-check-label" for="Store-1">Bayar Di Toko</label>
                                </div>
                                <p class="text-start text-dark">Datang langsung ke toko untuk melakukan pembayaran</p>
                            </div>
                        </div>
                        <div class="row g-4 text-center align-items-center justify-content-center border-bottom py-3" id="payment-slip-section" style="display: none;">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="payment-slip" class="form-label">Upload Payment Slip / Screenshot:</label>
                                    <input type="file" class="form-control" id="payment-slip" name="payment_slip" accept="image/*">
                                    <small class="text-muted">Upload your payment slip or transfer screenshot</small>
                                </div>
                            </div>
                        </div>
                        <div class="row g-4 text-center align-items-center justify-content-center pt-4">
                            <input type="hidden" name="total_amount" class="total-amount-input" value="{{ (int)Cart::subtotal(0,'','') }}">
                            <button type="submit" class="btn border-secondary py-3 px-4 text-uppercase w-100 text-primary">Place Order</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Checkout Page End -->
@endsection
@push('script-alt')
    <script>
        function loadProvinces() {
            console.log('Loading provinces...');
            console.log('jQuery available:', typeof $ !== 'undefined');
            console.log('CSRF token:', $('meta[name="csrf-token"]').attr('content'));
            
            var apiUrl = "{{ url('api/provinces') }}" + '?t=' + Date.now();
            console.log('API URL:', apiUrl);
            
            $.ajax({
                url: apiUrl,
                type: 'GET',
                dataType: 'json',
                beforeSend: function(xhr) {
                    console.log('Making request to:', apiUrl);
                    console.log('Request headers will include CSRF token');
                    $('#shipping-province').html('<option value="">Loading provinces...</option>');
                },
                success: function(response) {
                    console.log('Provinces response received:', response);
                    console.log('Response type:', typeof response);
                    console.log('Is array:', Array.isArray(response));
                    
                    var options = '<option value="">-- Pilih Provinsi --</option>';
                    if (response && Array.isArray(response)) {
                        console.log('Processing array response with', response.length, 'items');
                        $.each(response, function(index, province) {
                            var selected = province.id == '{{ auth()->user()->province_id }}' ? 'selected' : '';
                            options += '<option value="' + province.id + '" ' + selected + '>' + province.name + '</option>';
                        });
                    } else if (response && typeof response === 'object') {
                        console.log('Processing object response');
                        $.each(response, function(id, name) {
                            var selected = id == '{{ auth()->user()->province_id }}' ? 'selected' : '';
                            options += '<option value="' + id + '" ' + selected + '>' + name + '</option>';
                        });
                    } else {
                        console.error('Unexpected response format:', response);
                    }
                    
                    $('#shipping-province').html(options);
                    console.log('Province options updated, total options:', $('#shipping-province option').length);
                    
                    var selectedProvinceId = $('#shipping-province').val();
                    if (selectedProvinceId) {
                        console.log('Auto-loading cities for selected province:', selectedProvinceId);
                        loadCities(selectedProvinceId);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error loading provinces:');
                    console.error('Status:', status);
                    console.error('Error:', error);
                    console.error('Response Text:', xhr.responseText);
                    console.error('Status Code:', xhr.status);
                    console.error('Ready State:', xhr.readyState);
                    $('#shipping-province').html('<option value="">Error loading provinces</option>');
                },
                complete: function(xhr, status) {
                    console.log('AJAX request completed with status:', status);
                }
            });
        }

        function loadCities(provinceId) {
            console.log('Loading cities for province:', provinceId);
            var cityUrl = "{{ url('api/cities') }}/" + provinceId + '?t=' + Date.now();
            $.ajax({
                url: cityUrl,
                type: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    console.log('Making request to:', cityUrl);
                    $('#shipping-city').html('<option value="">Loading cities...</option>');
                },
                success: function(response) {
                    console.log('Cities response received:', response);
                    var options = '<option value="">-- Pilih Kota --</option>';
                    if (response && Array.isArray(response)) {
                        console.log('Processing cities array with', response.length, 'items');
                        $.each(response, function(index, city) {
                            var selected = city.id == '{{ auth()->user()->city_id }}' ? 'selected' : '';
                            options += '<option value="' + city.id + '" ' + selected + '>' + city.name + '</option>';
                        });
                    }
                    $('#shipping-city').html(options);
                    console.log('City options updated, total options:', $('#shipping-city option').length);
                    
                    var selectedCityId = $('#shipping-city').val();
                    if (selectedCityId) {
                        console.log('Auto-loading districts for selected city:', selectedCityId);
                        loadDistricts(selectedCityId);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error loading cities:');
                    console.error('Status:', status);
                    console.error('Error:', error);
                    console.error('Response Text:', xhr.responseText);
                    $('#shipping-city').html('<option value="">Error loading cities</option>');
                }
            });
        }

        function loadDistricts(cityId) {
            console.log('Loading districts for city:', cityId);
            var districtUrl = "{{ url('api/districts') }}/" + cityId + '?t=' + Date.now();
            $.ajax({
                url: districtUrl,
                type: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    console.log('Making request to:', districtUrl);
                    $('#shipping-district').html('<option value="">Loading districts...</option>');
                },
                success: function(response) {
                    console.log('Districts response received:', response);
                    var options = '<option value="">-- Pilih Kecamatan --</option>';
                    if (response && Array.isArray(response)) {
                        console.log('Processing districts array with', response.length, 'items');
                        $.each(response, function(index, district) {
                            var selected = district.id == '{{ auth()->user()->district_id }}' ? 'selected' : '';
                            options += '<option value="' + district.id + '" ' + selected + '>' + district.name + '</option>';
                        });
                    }
                    $('#shipping-district').html(options);
                    console.log('District options updated, total options:', $('#shipping-district option').length);
                    
                    var selectedDistrictId = $('#shipping-district').val();
                    if (selectedDistrictId) {
                        console.log('Auto-loading shipping costs for selected district:', selectedDistrictId);
                        var deliveryMethod = $('input[name="delivery_method"]:checked').val();
                        if (deliveryMethod === 'courier') {
                            getShippingCostOptions(selectedDistrictId);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error loading districts:');
                    console.error('Status:', status);
                    console.error('Error:', error);
                    console.error('Response Text:', xhr.responseText);
                    $('#shipping-district').html('<option value="">Error loading districts</option>');
                }
            });
        }

        function getShippingCostOptions(district_id) {
            console.log('Getting shipping costs for district_id:', district_id);
            $('#shipping-cost-option').html('<option value="">Loading shipping costs...</option>');
            
            $.ajax({
                url: "{{ route('orders.shippingCost') }}",
                type: 'POST',
                data: {
                    district_id: district_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('Shipping API response:', response);
                    var options = '<option value="">-- Select Shipping Service --</option>';
                    
                    if (response.results && response.results.length > 0) {
                        $.each(response.results, function(index, result) {
                            var displayName = result.service + ' - Rp. ' + number_format(result.cost) + ' (' + result.etd + ')';
                            var valueData = {
                                service: result.service,
                                cost: result.cost,
                                etd: result.etd,
                                courier: result.courier
                            };
                            var value = JSON.stringify(valueData).replace(/"/g, '&quot;');
                            options += '<option value="' + value + '">' + displayName + '</option>';
                        });
                    } else {
                        console.warn('No shipping results found in response');
                        options += '<option value="">No shipping options available</option>';
                    }
                    
                    $('#shipping-cost-option').html(options);
                    
                    // Update total amount after shipping options are loaded
                    console.log('📦 Shipping options loaded, updating total...');
                    updateTotalAmount();
                    
                    // Force trigger change event to ensure total updates
                    setTimeout(function() {
                        console.log('🔄 Delayed total update...');
                        updateTotalAmount();
                    }, 100);
                },
                error: function(xhr, status, error) {
                    console.error('Shipping API error:', {
                        status: status,
                        error: error,
                        response: xhr.responseText,
                        statusCode: xhr.status
                    });
                    $('#shipping-cost-option').html('<option value="">Error loading shipping costs</option>');
                }
            });
        }

        function number_format(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        function updateTotalAmount() {
            var subtotal = parseInt("{{ (int)Cart::subtotal(0,'','') }}");
            var uniqueCode = parseInt($('.unique_code').val()) || 0;
            var shippingCost = 0;
            
            var deliveryMethod = $('input[name="delivery_method"]:checked').val();
            
            console.log('Updating total - Delivery method:', deliveryMethod);
            console.log('Subtotal:', subtotal);
            console.log('Unique code:', uniqueCode);
            
            if (deliveryMethod === 'self') {
                shippingCost = 0;
                console.log('Self pickup - Shipping cost: 0');
            } else if (deliveryMethod === 'courier') {
                var selectedShipping = $('#shipping-cost-option').val();
                console.log('Selected shipping value:', selectedShipping);
                
                if (selectedShipping) {
                    try {
                        var unescapedShipping = selectedShipping.replace(/&quot;/g, '"');
                        console.log('Unescaped shipping value:', unescapedShipping);
                        var shippingData = JSON.parse(unescapedShipping);
                        shippingCost = parseInt(shippingData.cost) || 0;
                        console.log('Parsed shipping cost:', shippingCost);
                        console.log('Shipping data:', shippingData);
                    } catch (e) {
                        console.error('Error parsing shipping data:', e);
                        console.log('Raw shipping value:', selectedShipping);
                        shippingCost = 0;
                    }
                } else {
                    console.log('No shipping option selected');
                }
            }
            
            var total = subtotal + uniqueCode + shippingCost;
            console.log('Final total calculation:', subtotal, '+', uniqueCode, '+', shippingCost, '=', total);
            
            $('.total-amount').text(number_format(total));
            $('.total-amount-input').val(total);
            console.log('Total updated to:', number_format(total));
        }

        $(document).ready(function(){
            // Setup CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $('#shipping-row').hide();
            $('.address-fields').hide();
            $('#shipping-cost-option').html('<option value="">-- Select Delivery Method First --</option>');
            
            // Disable address dropdowns for self pickup (default)
            $('#shipping-province').prop('disabled', true);
            $('#shipping-city').prop('disabled', true);
            $('#shipping-district').prop('disabled', true);
            
            // Always load provinces on page load if not already loaded
            if ($('#shipping-province option').length <= 1) {
                console.log('Loading provinces on page initialization...');
                loadProvinces();
            }
            
            // Initialize total amount calculation
            updateTotalAmount();
            
            // Debug: Test element accessibility
            console.log('=== CHECKOUT PAGE DEBUG ===');
            console.log('Total amount element exists:', $('.total-amount').length > 0);
            console.log('Total amount current text:', $('.total-amount').text());
            console.log('Unique code element exists:', $('.unique_code').length > 0);
            console.log('Unique code value:', $('.unique_code').val());
            console.log('Shipping cost option element exists:', $('#shipping-cost-option').length > 0);
            console.log('Delivery method elements count:', $('input[name="delivery_method"]').length);
            console.log('Currently selected delivery method:', $('input[name="delivery_method"]:checked').val());
            console.log('============================');
            
            $('#shipping-province').on('change', function() {
                var province_id = $(this).val();
                console.log('Province changed to:', province_id);
                if (province_id) {
                    loadCities(province_id);
                } else {
                    $('#shipping-city').html('<option value="">-- Pilih Kota --</option>');
                    $('#shipping-district').html('<option value="">-- Pilih Kecamatan --</option>');
                }
            });
            
            $('#shipping-city').on('change', function() {
                var city_id = $(this).val();
                console.log('City changed to:', city_id);
                if (city_id) {
                    loadDistricts(city_id);
                } else {
                    $('#shipping-district').html('<option value="">-- Pilih Kecamatan --</option>');
                }
                
                var deliveryMethod = $('input[name="delivery_method"]:checked').val();
                if (deliveryMethod === 'courier') {
                    $('#shipping-cost-option').html('<option value="">-- Select District First --</option>');
                    updateTotalAmount();
                }
            });
            
            $('#shipping-district').on('change', function() {
                var district_id = $(this).val();
                var deliveryMethod = $('input[name="delivery_method"]:checked').val();
                
                if (deliveryMethod === 'courier' && district_id) {
                    getShippingCostOptions(district_id);
                } else if (deliveryMethod === 'courier') {
                    $('#shipping-cost-option').html('<option value="">-- Select District First --</option>');
                    updateTotalAmount();
                }
            });
            
            $('input[name="delivery_method"]').on('change', function() {
                var method = $(this).val();
                console.log('🚚 DELIVERY METHOD CHANGED to:', method);
                
                if (method === 'self') {
                    $('.address-fields').hide();
                    $('#shipping-row').hide();
                    $('#shipping-cost-option').removeAttr('required');
                    
                    $('#shipping-province').removeAttr('required').prop('disabled', true);
                    $('#shipping-city').removeAttr('required').prop('disabled', true);
                    $('#shipping-district').removeAttr('required').prop('disabled', true);
                    
                    updateTotalAmount();
                } else if (method === 'courier') {
                    console.log('Switching to courier delivery mode...');
                    $('.address-fields').show();
                    $('#shipping-row').show();
                    $('#shipping-cost-option').attr('required', 'required');
                    
                    $('#shipping-province').attr('required', 'required').prop('disabled', false);
                    $('#shipping-city').attr('required', 'required').prop('disabled', false);
                    $('#shipping-district').attr('required', 'required').prop('disabled', false);
                    
                    console.log('Checking province options count:', $('#shipping-province option').length);
                    console.log('Loading provinces for courier delivery...');
                    
                    if ($('#shipping-province option').length <= 1) {
                        console.log('Province options <= 1, calling loadProvinces()');
                        loadProvinces();
                    } else {
                        console.log('Provinces already loaded, checking selected province');
                        var selectedProvinceId = $('#shipping-province').val();
                        console.log('Selected province ID:', selectedProvinceId);
                        if (selectedProvinceId) {
                            console.log('Loading cities for existing province:', selectedProvinceId);
                            loadCities(selectedProvinceId);
                        } else {
                            console.log('No province selected, calling loadProvinces() anyway');
                            loadProvinces();
                        }
                    }
                    
                    $('#shipping-cost-option').html('<option value="">-- Select District First --</option>');
                    updateTotalAmount();
                }
            });

            $('#shipping-cost-option').on('change', function() {
                console.log('🚢 SHIPPING COST CHANGED!');
                console.log('New value:', $(this).val());
                updateTotalAmount();
            });

            $('.payment-option').on('change', function() {
                var selectedPayment = $(this).val();
                console.log('Payment method selected:', selectedPayment);
                
                $('.payment-option').not(this).prop('checked', false);
                $(this).prop('checked', true);
                
                if (selectedPayment === 'manual') {
                    $('#payment-slip-section').show();
                } else {
                    $('#payment-slip-section').hide();
                }
            });
            
            // Debug test buttons
            $('#test-update-total').on('click', function() {
                console.log('🧪 MANUAL TOTAL UPDATE TEST');
                updateTotalAmount();
            });
            
            $('#show-debug').on('click', function() {
                console.log('🐛 CURRENT STATE DEBUG:');
                console.log('Subtotal (from Cart):', "{{ (int)Cart::subtotal(0,'','') }}");
                console.log('Unique code element value:', $('.unique_code').val());
                console.log('Selected delivery method:', $('input[name="delivery_method"]:checked').val());
                console.log('Selected shipping option:', $('#shipping-cost-option').val());
                console.log('Current total text:', $('.total-amount').text());
                
                // Test if elements are accessible
                console.log('Element tests:');
                console.log('- .total-amount exists:', $('.total-amount').length);
                console.log('- .unique_code exists:', $('.unique_code').length);
                console.log('- #shipping-cost-option exists:', $('#shipping-cost-option').length);
                console.log('- delivery_method radio buttons:', $('input[name="delivery_method"]').length);
            });

       });
       
       function handleFormSubmit(event) {
           if (!validateForm()) {
               event.preventDefault();
               return false;
           }
           
           var deliveryMethod = $('input[name="delivery_method"]:checked').val();
           console.log('Form submit - delivery method:', deliveryMethod);
           
           if (deliveryMethod === 'self') {
               $('#shipping-province').removeAttr('name');
               $('#shipping-city').removeAttr('name');
               $('#shipping-district').removeAttr('name');
               $('#shipping-cost-option').removeAttr('name');
               console.log('Self pickup - removed address field names');
           }
           
           return true;
       }

       function validateForm() {
           var deliveryMethod = $('input[name="delivery_method"]:checked').val();
           var paymentMethod = $('input[name="payment_method"]:checked').val();
           
           console.log('Validating form...');
           console.log('Delivery method:', deliveryMethod);
           console.log('Payment method:', paymentMethod);
           console.log('Name:', $('input[name="name"]').val());
           console.log('Address1:', $('input[name="address1"]').val());
           console.log('Phone:', $('input[name="phone"]').val());
           console.log('Email:', $('input[name="email"]').val());
           console.log('Postcode:', $('input[name="postcode"]').val());
           
           if (!deliveryMethod) {
               alert('Please select a delivery method');
               return false;
           }
           
           if (!paymentMethod) {
               alert('Please select a payment method');
               return false;
           }
           
           if (deliveryMethod === 'courier') {
               console.log('Province:', $('#shipping-province').val());
               console.log('City:', $('#shipping-city').val());
               console.log('District:', $('#shipping-district').val());
               console.log('Shipping service:', $('#shipping-cost-option').val());
               
               if (!$('#shipping-province').val() || $('#shipping-province').val() === '') {
                   alert('Please select a province for courier delivery');
                   return false;
               }
               if (!$('#shipping-city').val() || $('#shipping-city').val() === '') {
                   alert('Please select a city for courier delivery');
                   return false;
               }
               if (!$('#shipping-district').val() || $('#shipping-district').val() === '') {
                   alert('Please select a district for courier delivery');
                   return false;
               }
               if (!$('#shipping-cost-option').val() || $('#shipping-cost-option').val() === '') {
                   alert('Please select a shipping service for courier delivery');
                   return false;
               }
           } else {
               console.log('Self pickup selected - skipping address validation');
           }
           
           updateTotalAmount();
           
           console.log('Form validation passed');
           console.log('Final total amount:', $('.total-amount-input').val());
           return true;
       }
    </script>

@endpush
