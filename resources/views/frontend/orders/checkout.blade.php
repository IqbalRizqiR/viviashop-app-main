@extends('frontend.layouts')
@section('content')
    <style>
        /* Checkout page scoped styles - pro e-commerce (green theme) */
        .checkout-card { }

    .summary-panel { background:#fbfffb; border-radius:10px; padding:18px; border:1px solid #eef7ef }

        .total-amount {
            font-size: 26px;
            font-weight: 900;
            color: #0f5134;
        }

        .total-amount small { color: #2f7f5a; }

        .payment-option + .form-check-label {
            font-weight: 700;
            color: #0e5136;
        }

        #place-order-btn {
            background: linear-gradient(90deg,#16a34a,#059669);
            color: #fff !important;
            border: 0;
            border-radius: 10px;
            font-weight: 800;
            letter-spacing: 0.6px;
            padding: 16px 20px;
            box-shadow: 0 14px 36px rgba(5,150,105,0.14);
            font-size: 16px;
        }

        #place-order-btn:disabled {
            opacity: 0.6;
        }

        .summary-note {
            background: #f8fffb;
            border-left: 4px solid #d7f6df;
            padding: 12px 14px;
            border-radius: 8px;
            color: #0e5136;
            font-size: 13px;
        }

        .info-badge {
            display:inline-block;
            background:#ecfdf5;
            color:#065f46;
            border-radius:999px;
            padding:6px 12px;
            font-weight:700;
            font-size:13px;
            margin-left:6px;
            border:1px solid #d1fae5;
        }

        /* subtle divider */
        .section-divider { height:1px; background: linear-gradient(90deg, rgba(0,0,0,0), rgba(14,81,54,0.06), rgba(0,0,0,0)); margin:18px 0; }

        /* Responsive tweaks */
        @media (max-width: 991px) {
            .table thead { display: none; }
            .table tbody td, .table tbody th { display: block; width: 100%; }
            .table tbody tr { margin-bottom: 14px; border-bottom: 1px dashed #eef7ef; padding-bottom: 8px; }
            .page-header { padding: 18px 12px; }
            .table tbody tr th img { width: 86px; height: 86px }
            .page-header h1 { font-size: 22px }
            .form-control { font-size: 15px; padding: 12px }
            #place-order-btn { font-size: 15px; padding: 12px }
        }
    </style>

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
            @php
                $subtotal = isset($resumeOrder) && $resumeOrder ? ($resumeOrder->base_total_price ?? 0) : (int)Cart::subtotal(0,'','');
            @endphp
            <h1 class="mb-4">Billing details</h1>
            <form action="{{ route('orders.checkout') }}" method="post" enctype="multipart/form-data" id="checkout-form" onsubmit="return handleFormSubmit(event)">
                @csrf
                @if(isset($resumeOrder) && $resumeOrder)
                    <input type="hidden" name="resume_order_id" value="{{ $resumeOrder->id }}">
                    <div class="alert alert-info">Resuming previous order #{{ $resumeOrder->code }}. You can complete payment or edit details before placing order.</div>
                @endif
                <div class="row g-5">
                    <div class="col-md-12 col-lg-6 col-xl-7">
                        <div class="row">
                            <div class="col-md-12 col-lg-6">
                                <div class="form-item w-100">
                                    <label>Nama <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name', isset($resumeOrder) && $resumeOrder ? $resumeOrder->customer_first_name : auth()->user()->name) }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-item">
                            <label class="form-label my-3">Address <sup>*</sup></label>
                            <input type="text" class="form-control" name="address1" value="{{ old('address1', isset($resumeOrder) && $resumeOrder ? $resumeOrder->customer_address1 : auth()->user()->address1) }}">
                            <br>
                            <input type="text" class="form-control" name="address2" value="{{ old('address2', isset($resumeOrder) && $resumeOrder ? $resumeOrder->customer_address2 : auth()->user()->address2) }}">
                        </div>
                        <div class="form-item">
                            <label>Postcode / Zip <span class="required">*</span></label>
                            <input type="text" class="form-control" name="postcode" value="{{ old('postcode', isset($resumeOrder) && $resumeOrder ? $resumeOrder->customer_postcode : auth()->user()->postcode) }}">
                        </div>
                        <div class="form-item">
                            <label>Phone  <span class="required">*</span></label>
                            <input type="text" class="form-control" name="phone" value="{{ old('phone', isset($resumeOrder) && $resumeOrder ? $resumeOrder->customer_phone : auth()->user()->phone) }}">
                        </div>
                        <div class="form-item">
                            <label>Email Address </label>
                            <input type="text" class="form-control" name="email" value="{{ old('email', isset($resumeOrder) && $resumeOrder ? $resumeOrder->customer_email : auth()->user()->email) }}">
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
                                        if (isset($item->options['type']) && $item->options['type'] === 'configurable') {
                                            $product = \App\Models\Product::find($item->options['product_id']);
                                            $image = !empty($item->options['image']) ? asset('storage/' . $item->options['image']) : asset('themes/ezone/assets/img/cart/3.jpg');
                                            $displayName = $item->name;
                                            if (isset($item->options['attributes']) && !empty($item->options['attributes'])) {
                                                $attributes = [];
                                                foreach ($item->options['attributes'] as $attr => $value) {
                                                    $attributes[] = $attr . ': ' . $value;
                                                }
                                                $displayName .= ' (' . implode(', ', $attributes) . ')';
                                            }
                                        } else {
                                            // For simple products, load from product_id if model is null
                                            $product = $item->model;
                                            if (!$product && isset($item->options['product_id'])) {
                                                $product = \App\Models\Product::find($item->options['product_id']);
                                            }
                                            if (!$product) {
                                                $product = \App\Models\Product::find($item->id);
                                            }
                                            
                                            $image = asset('themes/ezone/assets/img/cart/3.jpg'); // default
                                            if ($product && $product->productImages->isNotEmpty()) {
                                                $image = asset('storage/'.$product->productImages->first()->path);
                                            } elseif (!empty($item->options['image'])) {
                                                $image = asset('storage/' . $item->options['image']);
                                            }
                                            
                                            $displayName = $product ? $product->name : $item->name;
                                        }
                                    @endphp
                                        <tr>
                                            <th scope="row">
                                                <div class="d-flex align-items-center mt-2">
                                                    <img src="{{ $image }}" class="img-fluid rounded" style="width: 90px; height: 90px;" alt="">
                                                </div>
                                            </th>
                                            <td class="py-5">{{ $displayName }}</td>
                                            <td class="py-5">Rp. {{ number_format($item->price) }}</td>
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
                                                <p class="mb-0 text-dark">Rp. {{ number_format($subtotal,0,',','.') }}</p>
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
                                                <p class="mb-0 text-dark total-amount">{{ number_format((int)$subtotal) }}</p>
                                            </div>
                                            <p>harap tunggu nominal berubah sesuai dengan total sebelum checkout</p>
                                            <!-- debug buttons removed for production UI -->
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
                                <p class="text-start text-dark">You can pay to us via : <br> 1. BCA : 01401840112(Ahmad Sambudi) <br> 2. BCA : 01401840112(Ahmad Sambudi)<br><small class="text-muted">You will be able to upload payment proof after order confirmation.</small></p>
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
                        <div class="row g-4 text-center align-items-center justify-content-center pt-4">
                            <input type="hidden" name="total_amount" class="total-amount-input" value="{{ (int)$subtotal }}">
                            <button type="submit" id="place-order-btn" class="btn border-secondary py-3 px-4 text-uppercase w-100 text-primary">Place Order</button>
                            <div id="loading-indicator" style="display: none;" class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Processing...</span>
                                </div>
                                <p class="mt-2">Processing your order...</p>
                            </div>
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
                beforeSend: function() {
                    console.log('Making request to:', apiUrl);
                    $('#shipping-province').html('<option value="">Loading provinces...</option>');
                },
                success: function(response) {
                    console.log('Provinces response:', response);
                    var options = '<option value="">-- Pilih Provinsi --</option>';

                    if (Array.isArray(response)) {
                        response.forEach(function(item) {
                            if (item && (item.id !== undefined && item.name !== undefined)) {
                                var selected = item.id == '{{ auth()->user()->province_id }}' ? 'selected' : '';
                                options += '<option value="' + item.id + '" ' + selected + '>' + item.name + '</option>';
                            }
                        });
                    } else if (response && typeof response === 'object') {
                        for (var id in response) {
                            if (response.hasOwnProperty(id)) {
                                var name = response[id];
                                var selected = id == '{{ auth()->user()->province_id }}' ? 'selected' : '';
                                options += '<option value="' + id + '" ' + selected + '>' + name + '</option>';
                            }
                        }
                    } else {
                        console.error('Unexpected provinces response format');
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
            console.log('🚀 CHECKOUT PAGE INITIALIZED');
            
            // Setup error handlers
            window.addEventListener('error', function(e) {
                console.error('💥 JavaScript Error:', e.error);
                console.error('Message:', e.message);
                console.error('Filename:', e.filename);
                console.error('Line:', e.lineno);
            });
            
            window.addEventListener('unhandledrejection', function(e) {
                console.error('💥 Unhandled Promise Rejection:', e.reason);
            });
            
            // Setup CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            // Ensure form exists
            if ($('#checkout-form').length === 0) {
                console.error('❌ Checkout form not found!');
                return;
            }
            
            console.log('✅ Checkout form found');
            
            // Initialize form state
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
            console.log('Payment method elements count:', $('input[name="payment_method"]').length);
            console.log('Currently selected payment method:', $('input[name="payment_method"]:checked').val());
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
                if (method === 'self') {
                    $('#shipping-row').hide();
                    $('.address-fields').hide();
                    $('#shipping-cost-option').removeAttr('required');
                    $('#shipping-province').prop('disabled', true).removeAttr('required');
                    $('#shipping-city').prop('disabled', true).removeAttr('required');
                    $('#shipping-district').prop('disabled', true).removeAttr('required');
                    updateTotalAmount();
                } else if (method === 'courier') {
                    $('.address-fields').show();
                    $('#shipping-row').show();
                    $('#shipping-cost-option').attr('required', 'required');
                    $('#shipping-province').prop('disabled', false).attr('required', 'required');
                    $('#shipping-city').prop('disabled', false).attr('required', 'required');
                    $('#shipping-district').prop('disabled', false).attr('required', 'required');

                    if ($('#shipping-province option').length <= 1) {
                        loadProvinces();
                    } else {
                        var selectedProvinceId = $('#shipping-province').val();
                        if (selectedProvinceId) loadCities(selectedProvinceId);
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
           // Prevent default immediately to avoid browser form submit (defensive)
           event = event || window.event;
           if (event && event.preventDefault) event.preventDefault();
           else window.event.returnValue = false;

           console.log('🔍 FORM SUBMIT STARTED (defensive)');
           console.log('Form action:', $('#checkout-form').attr('action'));

           // Prevent double submission
           var submitButton = $('#place-order-btn');
           if (submitButton.prop('disabled')) {
               console.log('❌ FORM ALREADY SUBMITTING - preventing double submit');
               return false;
           }

           // Validate form first
           if (!validateForm()) {
               console.log('❌ FORM VALIDATION FAILED - preventing submit');
               return false;
           }

           console.log('✅ FORM VALIDATION PASSED');

           // Disable submit button to prevent double submission
           var submitButton = $('#place-order-btn');
           var loadingIndicator = $('#loading-indicator');

           submitButton.prop('disabled', true).hide();
           loadingIndicator.show();

           var deliveryMethod = $('input[name="delivery_method"]:checked').val();
           console.log('Form submit - delivery method:', deliveryMethod);
           
           if (deliveryMethod === 'self') {
               // Remove address field names for self pickup to avoid validation issues
               $('#shipping-province').removeAttr('name');
               $('#shipping-city').removeAttr('name');
               $('#shipping-district').removeAttr('name');
               $('#shipping-cost-option').removeAttr('name');
               console.log('Self pickup - removed address field names');
           }
           
           // Ensure all required hidden fields exist
           if (!$('input[name="unique_code"]').length) {
               $('<input>').attr({
                   type: 'hidden',
                   name: 'unique_code',
                   value: '0'
               }).appendTo('#checkout-form');
               console.log('Added missing unique_code field');
           }
           
           // Prepare form data for AJAX
           var formData = new FormData($('#checkout-form')[0]);
           console.log('📝 FINAL FORM DATA:');
           for (var pair of formData.entries()) {
               console.log(pair[0] + ': ' + pair[1]);
           }

           // AJAX POST helper
           var ajaxUrl = $('#checkout-form').attr('action');
           var csrfToken = $('meta[name="csrf-token"]').attr('content');

           function handleSuccess(resp) {
               console.log('Checkout AJAX success response:', resp);
               if (resp && resp.success) {
                   if (resp.payment_url) {
                       window.location.href = resp.payment_url;
                       return;
                   }
                   if (resp.redirect) {
                       window.location.href = resp.redirect;
                       return;
                   }
                   window.location.reload();
               } else {
                   alert(resp.message || 'There was an error processing your order');
                   submitButton.prop('disabled', false).show();
                   loadingIndicator.hide();
               }
           }

           function handleError(xhrText) {
               console.error('Checkout failed:', xhrText);
               try {
                   var json = typeof xhrText === 'string' ? JSON.parse(xhrText) : xhrText;
                   alert(json.message || 'An error occurred while processing your order');
               } catch (e) {
                   alert('An error occurred while processing your order. Please try again.');
               }
               submitButton.prop('disabled', false).show();
               loadingIndicator.hide();
           }

           // If jQuery is available, use it (we already set CSRF in $.ajaxSetup earlier)
           if (window.jQuery && $.ajax) {
               $.ajax({
                   url: ajaxUrl,
                   method: 'POST',
                   data: formData,
                   processData: false,
                   contentType: false,
                   dataType: 'json',
                   headers: {
                       'X-Requested-With': 'XMLHttpRequest'
                   },
                   success: function(resp) {
                       handleSuccess(resp);
                   },
                   error: function(xhr, status, err) {
                       handleError(xhr.responseText || status);
                   }
               });
           } else {
               // Fallback to fetch
               var fetchHeaders = {
                   'X-Requested-With': 'XMLHttpRequest'
               };
               if (csrfToken) fetchHeaders['X-CSRF-TOKEN'] = csrfToken;

               fetch(ajaxUrl, {
                   method: 'POST',
                   headers: fetchHeaders,
                   body: formData,
                   credentials: 'same-origin'
               }).then(function(response) {
                   return response.text().then(function(text) {
                       try {
                           var json = text ? JSON.parse(text) : {};
                           if (response.ok) {
                               handleSuccess(json);
                           } else {
                               handleError(text);
                           }
                       } catch (e) {
                           handleError(text);
                       }
                   });
               }).catch(function(err) {
                   handleError(err);
               });
           }

           // Safety fallback re-enable after 15s
           setTimeout(function() {
               submitButton.prop('disabled', false).show();
               loadingIndicator.hide();
           }, 15000);

           // Prevent default (we already did earlier) and do not allow normal submit
           return false;
       }

       function validateForm() {
           var deliveryMethod = $('input[name="delivery_method"]:checked').val();
           var paymentMethod = $('input[name="payment_method"]:checked').val();
           
           console.log('🔍 VALIDATING FORM...');
           console.log('Delivery method:', deliveryMethod);
           console.log('Payment method:', paymentMethod);
           
           var name = $('input[name="name"]').val();
           var address1 = $('input[name="address1"]').val();
           var phone = $('input[name="phone"]').val();
           var email = $('input[name="email"]').val();
           var postcode = $('input[name="postcode"]').val();
           
           console.log('Form data:', {
               name: name,
               address1: address1,
               phone: phone,
               email: email,
               postcode: postcode
           });
           
           // Check for empty or whitespace-only values
           if (!name || name.trim() === '') {
               alert('❌ Please enter your name');
               $('input[name="name"]').focus();
               return false;
           }
           
           if (!address1 || address1.trim() === '') {
               alert('❌ Please enter your address');
               $('input[name="address1"]').focus();
               return false;
           }
           
           if (!phone || phone.trim() === '') {
               alert('❌ Please enter your phone number');
               $('input[name="phone"]').focus();
               return false;
           }
           
           if (!email || email.trim() === '') {
               alert('❌ Please enter your email address');
               $('input[name="email"]').focus();
               return false;
           }
           
           // Simple email validation
           var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
           if (!emailRegex.test(email.trim())) {
               alert('❌ Please enter a valid email address');
               $('input[name="email"]').focus();
               return false;
           }
           
           if (!postcode || postcode.trim() === '') {
               alert('❌ Please enter your postcode');
               $('input[name="postcode"]').focus();
               return false;
           }
           
           if (!deliveryMethod) {
               alert('❌ Please select a delivery method');
               return false;
           }
           
           if (!paymentMethod) {
               alert('❌ Please select a payment method');
               return false;
           }
           
           // Validate courier delivery specific fields
           if (deliveryMethod === 'courier') {
               console.log('Validating courier delivery fields...');
               
               var province = $('#shipping-province').val();
               var city = $('#shipping-city').val();
               var district = $('#shipping-district').val();
               var shippingService = $('#shipping-cost-option').val();
               
               console.log('Courier fields:', {
                   province: province,
                   city: city,
                   district: district,
                   shippingService: shippingService
               });
               
               if (!province || province === '') {
                   alert('❌ Please select a province for courier delivery');
                   $('#shipping-province').focus();
                   return false;
               }
               
               if (!city || city === '') {
                   alert('❌ Please select a city for courier delivery');
                   $('#shipping-city').focus();
                   return false;
               }
               
               if (!district || district === '') {
                   alert('❌ Please select a district for courier delivery');
                   $('#shipping-district').focus();
                   return false;
               }
               
               if (!shippingService || shippingService === '') {
                   alert('❌ Please select a shipping service for courier delivery');
                   $('#shipping-cost-option').focus();
                   return false;
               }
           } else {
               console.log('Self pickup selected - skipping address validation');
           }
           
           // Update total amount one final time
           updateTotalAmount();
           
           console.log('✅ FORM VALIDATION PASSED');
           console.log('Final total amount:', $('.total-amount-input').val());
           
           return true;
       }
    </script>

@endpush
