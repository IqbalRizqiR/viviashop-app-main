@extends('layouts.app')
@section('title', 'Create Order')
@section('content')
    <style>
    #scanner {
        position: relative;
        overflow: hidden;
    }

    #scanner video {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover;
    }

    #scanner canvas {
        position: absolute;
        top: 0;
        left: 0;
    }

    /* Ensure modal is properly sized */
    .modal-lg {
        max-width: 600px;
    }
    </style>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('admin.orders.storeAdmin') }}" method="POST" enctype="multipart/form-data" id="order-form" onsubmit="return handleFormSubmit(event)">
                    @csrf
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Customer Information</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" readonly name="first_name" value="Admin" class="form-control" required>
                            </div>
                            <div class="form-group">
                                 <label for="last_name">Last Name</label>
                                <input type="text" readonly name="last_name" value="Toko" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="address1">Address Line 1</label>
                                <input type="text" readonly name="address1" value="Cukir, Jombang" class="form-control" required>
                            </div>
                            {{--  <div class="form-group">
                                <label for="province_id">Province</label>
                                <select name="province_id" id="province_id" class="form-control" required>
                                    <option value="">Select Province</option>
                                    @foreach($provinces as $province)
                                        <option value={{ $province->id }}>{{ $province->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="city_id">City</label>
                                <select name="city_id" id="city_id" class="form-control" required>
                                    <option value="">Select City</option>
                                    <!-- Cities will be loaded based on selected province -->
                                </select>
                            </div>  --}}
                            <div class="form-group">
                                <label for="postcode">Postcode</label>
                                <input type="text" readonly name="postcode" value="102112" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" readonly name="phone" value="9121240210" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" readonly name="email" value="admin@gmail.com" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Order Items</h3>
                        </div>
                        <div class="box-body">
                            <div>
                                <button type="button"
                                        class="btn btn-primary mb-3"
                                        onclick="addModal()">
                                <i class="fas fa-search"></i> Search & Add Product
                                </button>
                            </div>
                            <!-- Button to open modal -->
                            <div class="">
                                <button type="button" class="btn btn-primary mb-3" onclick="showBarcodeModal()">
                                    <i class="fas fa-barcode"></i> Scan Barcode
                                </button>
                            </div>
                            <div class="d-flex" style="gap: 10px; align-items: center;">
                                <h4>Scan Infrared</h4>
                                <div class="form-group">
                                    <input type="text" id="barcode" class="form-control" placeholder="Barcode">
                                </div>
                                <button type="button" class="btn btn-danger mb-3" onclick="searchBarcodeId()">
                                    <i class="fas fa-barcode"></i> Search Barcode
                                </button>
                            </div>
                            <div id="order-items"></div>
                            <div class="form-group">
                                <input type="text" name="note" class="form-control" placeholder="Notes if exist">
                            </div>
                            <div class="form-group">
                                <label for="payment_method">Payment Method</label>
                                <select name="payment_method" class="form-control" id="payment_method">
                                    <option value="toko">Bayar di Toko</option>
                                    <option value="qris">QRIS</option>
                                    <option value="midtrans">Midtrans Gateway</option>
                                    <option value="transfer">Transfer Bank</option>
                                </select>
                            </div>
                            <div id="payment-gateway-section" style="display: none;">
                                <button type="button" id="pay-button" class="btn btn-success btn-lg">Process Payment</button>
                            </div>
                            <div class="form-group">
                                <input type="file" name="attachments" id="image" class="form-control">
                            </div>
                            <div class="form-group mt-4 d-none image-item">
                                <label for="">Preview Image : </label>
                                <img src="" alt="" class="img-preview img-fluid">
                            </div>
                        </div>
                    </div>

                    {{-- Modal --}}
                    <div class="modal fade" id="modalProduct" tabindex="-1" aria-labelledby="productModalLabel">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Select Product</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <table id="product-table" class="table table-product table-bordered table-hover">
                                        <thead>
                                            <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>SKU</th>
                                            <th>Price</th>
                                            <th width="80">Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-success" id="create-order-btn">Create Order</button>
                    </div>
                    <!-- Barcode Scanner Modal -->
                    <div class="modal fade" id="barcodeModal" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Scan Barcode</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body text-center">
                                    <div id="scanner" style="width: 100%; height: 300px; border: 1px solid #ccc; position: relative;"></div>
                                    <div id="result" class="mt-3"></div>
                                    <div class="mt-3">
                                        <button id="start-scan" class="btn btn-success">Start Scanner</button>
                                        <button id="stop-scan" class="btn btn-danger">Stop Scanner</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </form>
            </div>
        </div>
    </div>
@stop

@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
<script type="text/javascript" 
    @if(config('midtrans.isProduction'))
        src="https://app.midtrans.com/snap/snap.js"
    @else
        src="https://app.sandbox.midtrans.com/snap/snap.js"
    @endif
    data-client-key="{{ config('midtrans.clientKey') }}">
</script>

<script>
let isScanning = false;
function showBarcodeModal() {
    $('#barcodeModal').modal('show');
    $('#barcodeModal').addClass('show');
}

// Initialize when modal is shown
$('#barcodeModal').on('shown.bs.modal', function() {
    console.log('Modal shown, waiting before starting scanner...');
    // Wait for modal animation to complete
    setTimeout(function() {
        initializeScanner();
    }, 500);
});

// Clean up when modal is hidden
$('#barcodeModal').on('hidden.bs.modal', function() {
    console.log('Modal hidden, stopping scanner...');
    stopScanner();
    clearScanner();
});

document.getElementById('start-scan').addEventListener('click', initializeScanner);
document.getElementById('stop-scan').addEventListener('click', stopScanner);

function initializeScanner() {
    if (isScanning) {
        console.log('Already scanning');
        return;
    }

    console.log('Initializing scanner...');
    document.getElementById('result').innerHTML = 'Starting camera...';

    // Clear previous content
    clearScanner();

    Quagga.init({
        inputStream: {
            name: "Live",
            type: "LiveStream",
            target: document.querySelector('#scanner'),
            constraints: {
                width: 640,
                height: 480,
                facingMode: "environment"
            }
        },
        locator: {
            patchSize: "medium",
            halfSample: true
        },
        numOfWorkers: 2,
        decoder: {
            readers: ["code_128_reader", "ean_reader", "code_39_reader"]
        },
        locate: true
    }, function(err) {
        if (err) {
            console.error('Quagga initialization error:', err);
            document.getElementById('result').innerHTML = '<div class="alert alert-danger">Camera error: ' + err.message + '</div>';
            return;
        }

        console.log('Quagga initialized successfully');
        document.getElementById('result').innerHTML = '<div class="alert alert-success">Camera ready! Point at barcode</div>';

        isScanning = true;
        Quagga.start();
    });
}

function stopScanner() {
    if (isScanning) {
        console.log('Stopping scanner...');
        Quagga.stop();
        isScanning = false;
        document.getElementById('result').innerHTML = 'Scanner stopped';
    }
}

function clearScanner() {
    const scannerDiv = document.getElementById('scanner');
    if (scannerDiv) {
        scannerDiv.innerHTML = '';
    }
}

// Handle barcode detection
Quagga.onDetected(function(result) {
    const code = result.codeResult.code;
    console.log('Barcode detected:', code);

    // Stop scanning to prevent multiple detections
    stopScanner();

    // Show detected code
    document.getElementById('result').innerHTML = '<div class="alert alert-info">Found: <strong>' + code + '</strong><br>Looking up product...</div>';

    // Find and add product
    findAndAddProduct(code);
});

function searchBarcodeId() {
    const barcode = document.getElementById('barcode').value;
    if (barcode) {
        findAndAddProduct(barcode);
    } else {
        document.getElementById('result').innerHTML = '<div class="alert alert-warning">Please enter a barcode</div>';
    }
}

function findAndAddProduct(barcode) {
    fetch('/admin/products/find-barcode', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ barcode: barcode })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const product = data.product;
            addProductToOrder({
                id: product.id,
                name: product.name,
                sku: product.sku,
                price: product.price,
                type: product.type || 'simple'
            });
            document.getElementById('result').innerHTML = '<div class="alert alert-success">✓ Product added: <strong>' + product.name + '</strong></div>';

            setTimeout(function() {
                $('#barcodeModal').modal('hide');
            }, 2000);

        } else {
            document.getElementById('result').innerHTML = '<div class="alert alert-warning">✗ Product not found: <strong>' + barcode + '</strong><br><button onclick="initializeScanner()" class="btn btn-primary btn-sm mt-2">Scan Again</button></div>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('result').innerHTML = '<div class="alert alert-danger">Error occurred<br><button onclick="initializeScanner()" class="btn btn-primary btn-sm mt-2">Try Again</button></div>';
    });
}


</script>
<script>
    function addModal() {
        $('#modalProduct').modal('show');
        $('#modalProduct').addClass('show');
    }
    let table;
    let url = "{{ route('admin.products.data') }}";
$(function(){
  table1 = $('.table-product').DataTable({
    processing: true,
    responsive: true,
    serverSide: true,
    autoWidth: false,
    ajax : {
        url: "{{ route('admin.products.data') }}",
    },
    columns: [
        {data: 'DT_RowIndex', searchable: false, sortable: false},
        {data: 'name'},
        {data: 'sku'},
        {data: 'price'},
        {data: 'action', searchable: false, sortable: false},
    ]
})
});
$('#product-table').on('click', '.select-product', function(){
    const id   = $(this).data('id'),
          sku  = $(this).data('sku'),
          name = $(this).data('name'),
          type = $(this).data('type'),
          price = $(this).data('price');

    addProductToOrder({id, name, sku, price, type});
    $('#modalProduct').modal('hide');
});

function loadAttributesForProduct(productId, itemIndex) {
    $.ajax({
        url: `/admin/products/${productId}/attributes`,
        type: 'GET',
        success: function(response) {
            if (response.attributes && response.attributes.length > 0) {
                renderAttributeOptions(response.attributes, itemIndex);
            } else {
                $(`#attribute-section-${itemIndex}`).html('<p class="text-muted"><small>No attributes available for this product</small></p>');
            }
        },
        error: function() {
            $(`#attribute-section-${itemIndex}`).html('<p class="text-danger"><small>Error loading attributes</small></p>');
        }
    });
}

function renderAttributeOptions(attributes, itemIndex) {
    let attributeHtml = '<div class="border-top pt-3"><h6>Product Options:</h6>';
    
    attributes.forEach(attribute => {
        attributeHtml += `
            <div class="form-group mb-3">
                <label class="font-weight-bold">${attribute.name}:</label>
                <div class="row">
                    <div class="col-md-6">
                        <label>Pilih Varian:</label>
                        <select class="form-control variant-select" data-attribute-code="${attribute.code}" data-item-index="${itemIndex}">
                            <option value="">Pilih Varian</option>
        `;
        
        attribute.attribute_variants.forEach(variant => {
            attributeHtml += `<option value="${variant.id}">${variant.name}</option>`;
        });
        
        attributeHtml += `
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Pilih Jenis:</label>
                        <select class="form-control option-select" name="attributes[${itemIndex}][${attribute.code}]" data-attribute-code="${attribute.code}" data-item-index="${itemIndex}">
                            <option value="">Pilih jenis terlebih dahulu varian</option>
                        </select>
                    </div>
                </div>
            </div>
        `;
    });
    
    attributeHtml += '</div>';
    
    $(`#attribute-section-${itemIndex}`).html(attributeHtml);
    
    $(`.variant-select[data-item-index="${itemIndex}"]`).on('change', function() {
        const variantId = $(this).val();
        const attributeCode = $(this).data('attribute-code');
        const optionSelect = $(this).closest('.row').find('.option-select');
        
        if (variantId) {
            $.ajax({
                url: `/api/attribute-options/0/${variantId}`,
                method: 'GET',
                success: function(data) {
                    optionSelect.empty();
                    optionSelect.append('<option value="">Pilih Jenis</option>');
                    data.options.forEach(function(option) {
                        optionSelect.append(new Option(option.name, option.id));
                    });
                }
            });
        } else {
            optionSelect.empty();
            optionSelect.append('<option value="">Pilih jenis terlebih dahulu varian</option>');
        }
    });
}

function addProductToOrder(product) {
    const orderItems = document.getElementById('order-items');
    const itemIndex = orderItems.children.length;
    
    let attributeSection = '';
    if (product.type === 'configurable') {
        attributeSection = `
            <div id="attribute-section-${itemIndex}" class="attribute-section mt-3">
                <p class="text-info"><small>Loading attributes...</small></p>
            </div>
        `;
    }

    const productHtml = `
        <div class="order-item card mb-2 p-3" data-index="${itemIndex}">
            <div class="row">
                <div class="col-md-6">
                    <label>Product</label>
                    <input type="text" class="form-control" value="${product.name} (${product.sku})" readonly>
                    <input type="hidden" name="product_id[]" value="${product.id}">
                    <input type="hidden" name="product_type[]" value="${product.type || 'simple'}">
                </div>
                <div class="col-md-3">
                    <label>Qty</label>
                    <input type="number" name="qty[]" class="form-control" value="1" min="1" required>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                </div>
            </div>
            ${attributeSection}
        </div>
    `;

    orderItems.insertAdjacentHTML('beforeend', productHtml);
    
    if (product.type === 'configurable') {
        loadAttributesForProduct(product.id, itemIndex);
    }
}

  $('#order-items').on('click','.remove-item', function(){
    $(this).closest('.order-item').remove();
  });

  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-item')) {
        e.target.closest('.order-item').remove();
    }
  });
</script>
<script>


$("#image").on("change", function () {
    const item = $(".image-item").removeClass("d-none");
    const image = $("#image");
    const imgPreview = $(".img-preview").addClass("d-block");
    const oFReader = new FileReader();
    var inputFiles = this.files;
    var inputFile = inputFiles[0];
    // console.log(inputFile);
    oFReader.readAsDataURL(inputFile);

    // var render = new FileReader();
    oFReader.onload = function (oFREvent) {
        console.log(oFREvent.target.result);
        $(".img-preview").attr("src", oFREvent.target.result);
    };
});

$(document).ready(function() {
    let productIndex = 1;

    // Initialize button visibility based on default payment method
    const initialPaymentMethod = $('#payment_method').val();
    if (initialPaymentMethod === 'qris' || initialPaymentMethod === 'midtrans') {
        $('#payment-gateway-section').show();
        $('#create-order-btn').hide();
    } else {
        $('#payment-gateway-section').hide();
        $('#create-order-btn').show();
    }

    $('.add-item').click(function() {
        const newItem = `
            <div class="form-group">
                <label for="products">Product</label>
                <select name="products[${productIndex}][id]" class="form-control product-select" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})</option>
                    @endforeach
                </select>
                <input type="number" name="products[${productIndex}][qty]" class="form-control" placeholder="Quantity" required>
            </div>
        `;
        $('#order-items').append(newItem);
        productIndex++;
    });

    $('#province_id').change(function() {
        var provinceId = $(this).val();
        $.ajax({
            url: '/admin/orders/cities',
            type: 'GET',
            data: { province_id: provinceId },
            success: function(data) {
                var cityOptions = '<option value="">Select City</option>';
                $.each(data.cities, function(index, city) {
                    cityOptions += '<option value="' + city.id + '">' + city.name + '</option>';
                });
                $('#city_id').html(cityOptions);
            }
        });
    });
});

function handleFormSubmit(event) {
    const paymentMethod = $('#payment_method').val();
    
    if (paymentMethod === 'qris' || paymentMethod === 'midtrans') {
        event.preventDefault();
        processPaymentGateway();
        return false;
    }
    
    return validateForm();
}

function validateForm() {
    const orderItems = document.getElementById('order-items');
    if (orderItems.children.length === 0) {
        alert('Please add at least one product to the order');
        return false;
    }
    return true;
}

$('#payment_method').change(function() {
    const paymentMethod = $(this).val();
    if (paymentMethod === 'qris' || paymentMethod === 'midtrans') {
        $('#payment-gateway-section').show();
        $('#create-order-btn').hide();
    } else {
        $('#payment-gateway-section').hide();
        $('#create-order-btn').show();
    }
});

$('#pay-button').click(function() {
    const paymentMethod = $('#payment_method').val();
    
    if (paymentMethod === 'qris' || paymentMethod === 'midtrans') {
        processPaymentGateway();
    }
});

function processPaymentGateway() {
    // Validate that products are added
    const orderItems = document.getElementById('order-items');
    if (orderItems.children.length === 0) {
        alert('Please add at least one product to the order');
        return;
    }
    
    // Show loading state
    const payButton = $('#pay-button');
    const originalText = payButton.text();
    payButton.prop('disabled', true).text('Processing...');
    
    const formData = new FormData($('#order-form')[0]);
    
    console.log('Form data being sent:');
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }
    
    console.log('Making AJAX request to:', '{{ route("admin.orders.storeAdmin") }}');
    
    $.ajax({
        url: '{{ route("admin.orders.storeAdmin") }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log('AJAX Response:', response);
            
            // Reset button state
            const payButton = $('#pay-button');
            payButton.prop('disabled', false).text('Process Payment');
            
            if (response.success) {
                if (response.payment_token) {
                    // Payment gateway order created, process payment immediately
                    let orderCode = response.order_code || 'ORD-' + response.order_id;
                    
                    // Add a small delay to ensure the page is ready
                    setTimeout(function() {
                        if (typeof snap === 'undefined') {
                            alert('Payment gateway not loaded. Please refresh the page and try again.');
                            window.location.reload();
                            return;
                        }
                        
                        snap.pay(response.payment_token, {
                            onSuccess: function(result) {
                                alert('Payment successful!');
                                window.location.href = '{{ route("admin.payment.finish") }}?order_id=' + orderCode;
                            },
                            onPending: function(result) {
                                alert('Payment pending. Please complete your payment.');
                                window.location.href = '{{ route("admin.payment.unfinish") }}?order_id=' + orderCode;
                            },
                            onError: function(result) {
                                alert('Payment failed. Please try again.');
                                window.location.href = '{{ route("admin.payment.error") }}?order_id=' + orderCode;
                            },
                            onClose: function() {
                                console.log('Payment window closed');
                                window.location.href = '{{ route("admin.orders.show", ":id") }}'.replace(':id', response.order_id);
                            }
                        });
                    }, 100);
                } else {
                    // Regular order created without payment
                    alert('Order created successfully!');
                    window.location.href = '{{ route("admin.orders.show", ":id") }}'.replace(':id', response.order_id);
                }
            } else {
                alert('Error: ' + (response.message || 'Unknown error occurred'));
            }
        },
        error: function(xhr) {
            console.error('AJAX Error:', xhr);
            
            // Reset button state
            const payButton = $('#pay-button');
            payButton.prop('disabled', false).text('Process Payment');
            
            let errorMessage = 'Error creating order. Please try again.';
            
            if (xhr.responseJSON) {
                if (xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON.errors) {
                    const errors = Object.values(xhr.responseJSON.errors).flat();
                    errorMessage = errors.join(', ');
                }
            } else if (xhr.responseText) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.message) {
                        errorMessage = response.message;
                    }
                } catch (e) {
                    console.log('Could not parse error response');
                }
            }
            
            alert(errorMessage);
        }
    });
}
</script>
@endpush
