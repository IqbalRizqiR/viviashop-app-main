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

    /* Ensure modal is properly sized and visible */
    .modal-lg {
        max-width: 600px;
    }
    
    /* Fix modal display issues */
    #modalProduct {
        z-index: 1050;
    }
    
    #modalProduct .modal-dialog {
        margin: 30px auto;
    }
    
    #modalProduct .modal-content {
        background-color: #fff;
        border: 1px solid #999;
        border-radius: 6px;
        box-shadow: 0 3px 9px rgba(0,0,0,.5);
    }
    
    .modal-backdrop {
        z-index: 1040;
    }

    /* Enhanced UI Styling */
    .box {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    .box-header {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border-bottom: 2px solid #dee2e6;
    }

    .box-title {
        font-weight: 600;
        color: #495057;
    }

    .box-primary .box-header {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
    }

    .box-primary .box-title {
        color: white;
    }

    .box-success .box-header {
        background: linear-gradient(135deg, #28a745, #1e7e34);
        color: white;
    }

    .box-success .box-title {
        color: white;
    }

    .box-info .box-header {
        background: linear-gradient(135deg, #17a2b8, #117a8b);
        color: white;
    }

    .box-info .box-title {
        color: white;
    }

    /* Button enhancements */
    .btn {
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .btn-primary {
        background: linear-gradient(135deg, #007bff, #0056b3);
        border: none;
    }

    .btn-success {
        background: linear-gradient(135deg, #28a745, #1e7e34);
        border: none;
    }

    .btn-danger {
        background: linear-gradient(135deg, #dc3545, #c82333);
        border: none;
    }

    /* Order item styling */
    .order-item {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        background: #ffffff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .order-item:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    /* Form enhancements */
    .form-control {
        border-radius: 6px;
        border: 1px solid #ced4da;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }

    /* Pricing summary styling */
    #pricing-summary .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #495057;
        border-top: none;
    }

    #pricing-summary .table td {
        vertical-align: middle;
    }

    .bg-success {
        background-color: #d4edda !important;
    }

    .bg-success td {
        color: #155724 !important;
        font-size: 16px;
    }

    /* Action buttons section */
    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .action-buttons .btn {
        margin-bottom: 10px;
    }

    /* Responsive improvements */
    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons .btn {
            width: 100%;
        }
        
        .d-flex {
            flex-direction: column !important;
        }
        
        .d-flex h4 {
            margin-bottom: 10px;
        }
    }

    /* Alert styling */
    .alert {
        border-radius: 6px;
        border: none;
    }

    /* Table improvements */
    .table-responsive {
        border-radius: 6px;
        overflow: hidden;
    }

    .table-bordered {
        border: 1px solid #dee2e6;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* Input group styling */
    .input-group {
        margin-bottom: 15px;
    }

    /* Icon improvements */
    .fa, .fas {
        margin-right: 8px;
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
                            <!-- Action Buttons Section -->
                            <div class="action-buttons">
                                <button type="button"
                                        class="btn btn-primary"
                                        id="searchProductBtn"
                                        onclick="addModal()">
                                    <i class="fas fa-search"></i> Search & Add Product
                                </button>
                                
                                <button type="button" class="btn btn-info" onclick="showBarcodeModal()">
                                    <i class="fas fa-barcode"></i> Scan Barcode
                                </button>
                            </div>
                            
                            <!-- Infrared Scanner Section -->
                            <div class="row" style="margin-bottom: 20px;">
                                <div class="col-md-3">
                                    <h5 class="text-primary">
                                        <i class="fas fa-scan"></i> Scan Infrared
                                    </h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" id="barcode" class="form-control" placeholder="Enter or scan barcode here...">
                                        <div class="input-group-btn">
                                            <button type="button" class="btn btn-warning" onclick="searchBarcodeId()">
                                                <i class="fas fa-search"></i> Search
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="order-items"></div>
                            
                            <!-- Pricing Summary Section -->
                            <div id="pricing-summary" class="box box-info" style="display: none; margin-top: 20px;">
                                <div class="box-header with-border">
                                    <h3 class="box-title">
                                        <i class="fa fa-calculator"></i> Order Summary
                                    </h3>
                                </div>
                                <div class="box-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th width="40%">Item</th>
                                                    <th width="15%" class="text-center">Qty</th>
                                                    <th width="20%" class="text-right">Price</th>
                                                    <th width="25%" class="text-right">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody id="pricing-items">
                                                <!-- Items will be populated here -->
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8"></div>
                                        <div class="col-md-4">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <tr>
                                                        <td class="text-right"><strong>Total Items:</strong></td>
                                                        <td class="text-right" id="total-items">0</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-right"><strong>Total Quantity:</strong></td>
                                                        <td class="text-right" id="total-quantity">0</td>
                                                    </tr>
                                                    <tr class="bg-success">
                                                        <td class="text-right"><strong>TOTAL TO PAY:</strong></td>
                                                        <td class="text-right"><strong id="grand-total">Rp 0</strong></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
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
                    <div class="modal fade" id="modalProduct" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title" id="productModalLabel">Select Product</h4>
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
                                    <button type="button" class="close" data-dismiss="modal" onclick="closeBarcodeModal()" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
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
}

function closeBarcodeModal() {
    $('#barcodeModal').modal('hide');
    $('#barcodeModal').removeClass('show');
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
    stopScanner();
    clearScanner();
}

// Initialize when modal is shown
$('#barcodeModal').on('shown', function() {
    console.log('Modal shown, waiting before starting scanner...');
    // Wait for modal animation to complete
    setTimeout(function() {
        initializeScanner();
    }, 500);
});

// Clean up when modal is hidden
$('#barcodeModal').on('hidden', function() {
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
                type: product.type || 'simple',
                total_stock: product.total_stock || 0
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
        console.log('addModal function called');
        try {
            // Clean up any existing modal states
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
            $('#modalProduct').removeClass('fade in').removeAttr('aria-hidden').removeAttr('style');
            
            // Show modal directly
            $('#modalProduct').modal({
                backdrop: true,
                keyboard: true,
                show: true
            });
            
            console.log('Modal show called');
            
        } catch(error) {
            console.error('Error in addModal:', error);
        }
    }
    
    function closeProductModal() {
        console.log('closeProductModal called');
        $('#modalProduct').modal('hide');
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
    }
    
    // Initialize when document is ready
    $(document).ready(function() {
        console.log('Document ready');
        
        // Modal show handler
        $('#modalProduct').on('shown.bs.modal', function() {
            console.log('Modal shown event triggered');
            
            // Destroy existing DataTable if any
            if ($.fn.DataTable.isDataTable('#product-table')) {
                $('#product-table').DataTable().destroy();
            }
            
            // Initialize DataTable
            $('#product-table').DataTable({
                processing: true,
                responsive: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('admin.products.data') }}",
                },
                columns: [
                    {data: 'DT_RowIndex', searchable: false, sortable: false},
                    {data: 'name'},
                    {data: 'sku'},
                    {data: 'price'},
                    {data: 'action', searchable: false, sortable: false},
                ]
            });
        });
        
        // Debug modal events
        $('#modalProduct').on('show.bs.modal', function() {
            console.log('Modal show event');
        });
        
        $('#modalProduct').on('hide.bs.modal', function() {
            console.log('Modal hide event');
        });
        
        $('#modalProduct').on('hidden.bs.modal', function() {
            console.log('Modal hidden event');
        });
    });
    
    let table;
    let url = "{{ route('admin.products.data') }}";

// Handle product selection
$('#product-table').on('click', '.select-product', function(){
    const id   = $(this).data('id'),
          sku  = $(this).data('sku'),
          name = $(this).data('name'),
          type = $(this).data('type'),
          price = $(this).data('price'),
          total_stock = $(this).data('stock') || $(this).data('total-stock') || 0;

    addProductToOrder({id, name, sku, price, type, total_stock});
    $('#modalProduct').modal('hide');
});

function loadAttributesForProduct(productId, itemIndex) {
    console.log('Loading variants for product:', productId);
    
    // Fetch variants using the updated API
    fetch(`/admin/products/${productId}/all-variants`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Variants API response:', data);
        if (data.success && data.data.length > 0) {
            console.log('Using frontend-style variant selection with', data.data.length, 'variants');
            renderFrontendStyleVariants(data.data, data.variantOptions, itemIndex, productId);
        } else {
            console.log('No variants found or not configurable');
            $(`#attribute-section-${itemIndex}`).html('<p class="text-muted"><small>No variants available for this product</small></p>');
        }
    })
    .catch(error => {
        console.error('Error loading variants:', error);
        $(`#attribute-section-${itemIndex}`).html('<p class="text-danger"><small>Error loading variants</small></p>');
    });
}

function loadSimpleProductVariant(productId, itemIndex, productData = null) {
    console.log('Loading price for simple product:', productId);
    
    if (productData && productData.price) {
        console.log('Using provided product data');
        const price = parseFloat(productData.price);
        const stock = parseInt(productData.total_stock || productData.stock || 0);
        const sku = productData.sku || 'N/A';
        
        const variantHtml = `
            <div id="attribute-section-${itemIndex}" class="attribute-section mt-3" style="display: none;">
                <input type="hidden" 
                       name="variant_id[${itemIndex}]" 
                       value="simple_${productId}" 
                       data-item-index="${itemIndex}" 
                       data-product-id="${productId}" 
                       data-price="${price}" 
                       data-sku="${sku}" 
                       data-stock="${stock}">
            </div>
        `;
        
        $(`#order-items`).find(`[data-index="${itemIndex}"]`).append(variantHtml);
        
        const priceFormatted = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(price);
        $(`#price-display-${itemIndex}`).html(`<strong class="text-success">${priceFormatted}</strong>`);
        
        const qtyInput = $(`#qty-${itemIndex}`);
        if (qtyInput.length) {
            qtyInput.attr('max', stock);
            if (stock <= 0) {
                qtyInput.val(0).prop('disabled', true);
                $(`#price-display-${itemIndex}`).html(`<span class="text-danger">Out of Stock</span>`);
            }
        }
        
        updatePricingSummary();
        
    } else {
        console.log('No product data provided, fetching from API');
        fetch(`/admin/products/${productId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.product) {
                const product = data.product;
                loadSimpleProductVariant(productId, itemIndex, product);
            } else {
                $(`#price-display-${itemIndex}`).html(`<span class="text-warning">Price not available</span>`);
            }
        })
        .catch(error => {
            console.error('Error loading simple product:', error);
            $(`#price-display-${itemIndex}`).html(`<span class="text-danger">Error loading price</span>`);
        });
    }
}

function renderFrontendStyleVariants(variants, variantOptions, itemIndex, productId) {
    if (!variants || variants.length === 0) {
        $(`#attribute-section-${itemIndex}`).html('<p class="text-muted"><small>No variants available for this product</small></p>');
        return;
    }

    let variantHtml = '<div class="border-top pt-3"><h6>Select Product Variant:</h6>';
    
    // Create variant option buttons like frontend
    if (variantOptions && Object.keys(variantOptions).length > 0) {
        Object.entries(variantOptions).forEach(([attributeName, options]) => {
            variantHtml += `
                <div class="variant-group mb-3">
                    <label class="form-label fw-bold">${attributeName.charAt(0).toUpperCase() + attributeName.slice(1)}:</label>
                    <div class="variant-options d-flex flex-wrap gap-2" data-attribute="${attributeName}">
            `;
            
            options.forEach(option => {
                variantHtml += `
                    <button type="button" 
                            class="btn btn-outline-secondary variant-option" 
                            data-attribute="${attributeName}" 
                            data-value="${option}"
                            data-item-index="${itemIndex}">
                        ${option}
                    </button>
                `;
            });
            
            variantHtml += `
                    </div>
                </div>
            `;
        });
        
        variantHtml += `
            <div id="variant-info-${itemIndex}" class="mt-3" style="display: none;">
                <div class="alert alert-success">
                    <h6 class="mb-2"><i class="fas fa-check-circle"></i> <strong>Varian Terpilih:</strong></h6>
                    <div class="variant-name mb-2">
                        <strong>Nama:</strong> <span id="variant-name-${itemIndex}" class="text-dark">-</span>
                    </div>
                    <div class="variant-attributes mb-2">
                        <strong>Spesifikasi:</strong> <span id="variant-attributes-${itemIndex}" class="text-muted">-</span>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <small><strong>SKU:</strong> <span id="variant-sku-${itemIndex}">-</span></small>
                        </div>
                        <div class="col-4">
                            <small><strong>Stok:</strong> <span id="variant-stock-${itemIndex}">-</span></small>
                        </div>
                        <div class="col-4">
                            <small><strong>Harga:</strong> <span id="variant-price-${itemIndex}">-</span></small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="selection-message-${itemIndex}" class="alert alert-warning mt-2">
                <i class="fas fa-info-circle"></i> Pilih varian untuk melanjutkan
            </div>
            
            <input type="hidden" name="variant_id[${itemIndex}]" id="selected-variant-id-${itemIndex}" value="">
        `;
    } else {
        // Fallback to simple dropdown if no variant options
        variantHtml += renderSimpleVariantDropdown(variants, itemIndex);
    }
    
    variantHtml += '</div>';
    
    $(`#attribute-section-${itemIndex}`).html(variantHtml);
    
    // Initialize variant system for this item
    initializeVariantSystem(itemIndex, variants, variantOptions);
}

function renderSimpleVariantDropdown(variants, itemIndex) {
    let html = `
        <div class="form-group mb-3">
            <label class="font-weight-bold">Available Variants:</label>
            <select class="form-control variant-select" 
                    name="variant_id[${itemIndex}]" 
                    data-item-index="${itemIndex}" 
                    required>
                <option value="">Select Variant</option>
    `;
    
    variants.forEach(variant => {
        let variantLabel = variant.sku;
        if (variant.variant_attributes && variant.variant_attributes.length > 0) {
            const attrs = variant.variant_attributes.map(attr => `${attr.attribute_name}: ${attr.attribute_value}`).join(', ');
            variantLabel += ` (${attrs})`;
        }
        variantLabel += ` - Rp ${new Intl.NumberFormat('id-ID').format(variant.price)}`;
        if (variant.stock !== undefined) {
            variantLabel += ` (Stock: ${variant.stock})`;
        }
        
        html += `<option value="${variant.id}" data-price="${variant.price}" data-sku="${variant.sku}" data-stock="${variant.stock || 0}">${variantLabel}</option>`;
    });
    
    html += `
            </select>
        </div>
    `;
    
    return html;
}

function initializeVariantSystem(itemIndex, allVariants, variantOptions) {
    if (!variantOptions || Object.keys(variantOptions).length === 0) {
        // Simple dropdown mode
        $(`.variant-select[data-item-index="${itemIndex}"]`).on('change', function() {
            const selectedOption = $(this).find('option:selected');
            if (selectedOption.val()) {
                updatePriceField(itemIndex, selectedOption.data('price'));
            }
        });
        return;
    }
    
    // Frontend-style variant selection
    let selectedAttributes = {};
    
    $(`.variant-option[data-item-index="${itemIndex}"]`).on('click', function() {
        const attributeName = $(this).data('attribute');
        const value = $(this).data('value');
        const currentItemIndex = $(this).data('item-index');
        
        if ($(this).hasClass('disabled')) return;
        
        // Toggle selection
        if (selectedAttributes[attributeName] === value) {
            delete selectedAttributes[attributeName];
            $(this).removeClass('btn-primary').addClass('btn-outline-secondary');
        } else {
            // Clear other selections for this attribute
            $(`.variant-option[data-attribute="${attributeName}"][data-item-index="${currentItemIndex}"]`)
                .removeClass('btn-primary').addClass('btn-outline-secondary');
            
            selectedAttributes[attributeName] = value;
            $(this).removeClass('btn-outline-secondary').addClass('btn-primary');
        }
        
        updateAvailableOptions(currentItemIndex, selectedAttributes, allVariants, variantOptions);
    });
}

function updateAvailableOptions(itemIndex, selectedAttributes, allVariants, variantOptions) {
    const possibleVariants = filterPossibleVariants(selectedAttributes, allVariants);
    const newAvailableOptions = extractAvailableOptions(possibleVariants, variantOptions);
    
    // Update button states
    Object.keys(variantOptions).forEach(attributeName => {
        const buttons = $(`.variant-option[data-attribute="${attributeName}"][data-item-index="${itemIndex}"]`);
        buttons.each(function() {
            const value = $(this).data('value');
            const isAvailable = newAvailableOptions[attributeName]?.includes(value);
            const isSelected = selectedAttributes[attributeName] === value;
            
            $(this).prop('disabled', !isAvailable && !isSelected);
            
            if (!isAvailable && !isSelected) {
                $(this).removeClass('btn-outline-secondary btn-primary').addClass('btn-outline-danger');
                $(this).attr('title', 'Tidak tersedia untuk kombinasi yang dipilih');
            } else if (!isSelected) {
                $(this).removeClass('btn-primary btn-outline-danger').addClass('btn-outline-secondary');
                $(this).removeAttr('title');
            }
        });
    });
    
    // Check for exact variant match
    const exactVariant = findExactVariant(selectedAttributes, allVariants);
    
    if (exactVariant) {
        updateVariantDisplay(itemIndex, exactVariant);
        updatePriceField(itemIndex, exactVariant.price);
    } else {
        resetVariantDisplay(itemIndex);
    }
}

function filterPossibleVariants(selectedAttributes, allVariants) {
    return allVariants.filter(variant => {
        return Object.entries(selectedAttributes).every(([attrName, attrValue]) => {
            return variant.variant_attributes.some(attr => 
                attr.attribute_name === attrName && attr.attribute_value === attrValue
            );
        });
    });
}

function extractAvailableOptions(variants, variantOptions) {
    const options = {};
    Object.keys(variantOptions).forEach(attributeName => {
        options[attributeName] = [...new Set(
            variants.flatMap(variant => 
                variant.variant_attributes
                    .filter(attr => attr.attribute_name === attributeName)
                    .map(attr => attr.attribute_value)
            )
        )];
    });
    return options;
}

function findExactVariant(selectedAttributes, allVariants) {
    if (Object.keys(selectedAttributes).length === 0) {
        return null;
    }
    
    return allVariants.find(variant => {
        return Object.entries(selectedAttributes).every(([attrName, attrValue]) => {
            return variant.variant_attributes.some(attr => 
                attr.attribute_name === attrName && attr.attribute_value === attrValue
            );
        });
    });
}

function updateVariantDisplay(itemIndex, variant) {
    $(`#variant-name-${itemIndex}`).text(variant.name);
    $(`#variant-sku-${itemIndex}`).text(variant.sku);
    $(`#variant-stock-${itemIndex}`).text(variant.stock);
    $(`#variant-price-${itemIndex}`).text(`Rp ${new Intl.NumberFormat('id-ID').format(variant.price)}`);
    
    const attributesList = variant.variant_attributes
        .map(attr => `${attr.attribute_name}: ${attr.attribute_value}`)
        .join(', ');
    $(`#variant-attributes-${itemIndex}`).text(attributesList);
    
    $(`#selected-variant-id-${itemIndex}`).val(variant.id);
    $(`#selected-variant-id-${itemIndex}`).data('price', variant.price);
    $(`#selected-variant-id-${itemIndex}`).data('sku', variant.sku);
    $(`#selected-variant-id-${itemIndex}`).data('stock', variant.stock);
    
    const priceFormatted = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(variant.price);
    if (typeof variant.stock !== 'undefined' && parseInt(variant.stock) <= 0) {
        $(`#price-display-${itemIndex}`).html(`<span class="text-danger">Out of Stock</span>`);
    } else {
        $(`#price-display-${itemIndex}`).html(`<strong class="text-success">${priceFormatted}</strong>`);
    }
    
    const qtyInput = $(`#qty-${itemIndex}`);
    if (qtyInput.length) {
        if (typeof variant.stock !== 'undefined') {
            qtyInput.attr('max', variant.stock);
            if (parseInt(variant.stock) <= 0) {
                qtyInput.val(0).prop('disabled', true);
            } else {
                qtyInput.prop('disabled', false);
                if (parseInt(qtyInput.val() || '0') > parseInt(variant.stock)) {
                    qtyInput.val(variant.stock);
                }
            }
        }
    }
    
    $(`#variant-info-${itemIndex}`).show();
    $(`#selection-message-${itemIndex}`).hide();
    updatePricingSummary();
}

function resetVariantDisplay(itemIndex) {
    $(`#selected-variant-id-${itemIndex}`).val('');
    $(`#selected-variant-id-${itemIndex}`).removeData('price');
    $(`#selected-variant-id-${itemIndex}`).removeData('sku');
    $(`#selected-variant-id-${itemIndex}`).removeData('stock');
    $(`#variant-info-${itemIndex}`).hide();
    $(`#selection-message-${itemIndex}`).show();
    $(`#price-display-${itemIndex}`).html('<span class="text-muted">Select variant to see price</span>');
    updatePricingSummary();
}

function updatePriceField(itemIndex, price) {
    // Update any price fields if they exist
    const priceField = $(`#price-${itemIndex}`);
    if (priceField.length) {
        priceField.val(price);
    }
}

function renderVariantDropdown(variants, itemIndex, productId) {
    if (!variants || variants.length === 0) {
        $(`#attribute-section-${itemIndex}`).html('<p class="text-muted"><small>No variants available for this product</small></p>');
        return;
    }

    let variantHtml = '<div class="border-top pt-3"><h6>Select Product Variant:</h6>';
    
    variantHtml += `
        <div class="form-group mb-3">
            <label class="font-weight-bold">Available Variants (Select One Only):</label>
            <select class="form-control variant-select" 
                    name="variant_id[${itemIndex}]" 
                    data-item-index="${itemIndex}" 
                    data-product-id="${productId}" 
                    required>
                <option value="">Pilih Variant</option>
    `;
    
    variants.forEach(variant => {
        let variantLabel = variant.sku;
        if (variant.variant_attributes && variant.variant_attributes.length > 0) {
            const attrs = variant.variant_attributes.map(attr => `${attr.attribute_name}: ${attr.attribute_value}`).join(', ');
            variantLabel += ` (${attrs})`;
        }
        variantLabel += ` - Rp ${new Intl.NumberFormat('id-ID').format(variant.price)}`;
        if (variant.stock !== undefined) {
            variantLabel += ` (Stock: ${variant.stock})`;
        }
        
        const isDisabled = selectedVariants.has(variant.id) ? 'disabled' : '';
        const disabledText = selectedVariants.has(variant.id) ? ' (Already Selected)' : '';
        
        variantHtml += `<option value="${variant.id}" data-price="${variant.price}" data-sku="${variant.sku}" data-stock="${variant.stock || 0}" ${isDisabled}>${variantLabel}${disabledText}</option>`;
    });
    
    variantHtml += `
            </select>
            <small class="text-muted">Note: Only one variant can be selected per order item.</small>
        </div>
        <div class="variant-info mt-3" id="variant-info-${itemIndex}" style="display: none;">
            <div class="alert alert-info">
                <strong>Selected Variant:</strong><br>
                <span id="variant-details-${itemIndex}"></span>
            </div>
        </div>
    </div>`;
    
    $(`#attribute-section-${itemIndex}`).html(variantHtml);
    
    $(`.variant-select[data-item-index="${itemIndex}"]`).on('change', function() {
        updateSelectedVariant(itemIndex);
    });
}

function updateSelectedVariant(itemIndex) {
    const variantSelect = $(`.variant-select[data-item-index="${itemIndex}"]`);
    const selectedOption = variantSelect.find('option:selected');
    const previousVariantId = variantSelect.data('previous-variant-id');
    
    if (previousVariantId) {
        selectedVariants.delete(previousVariantId);
    }
    
    if (selectedOption.val()) {
        const variantId = selectedOption.val();
        const price = selectedOption.data('price');
        const sku = selectedOption.data('sku');
        const stock = selectedOption.data('stock');
        
        selectedVariants.add(variantId);
        variantSelect.data('previous-variant-id', variantId);
        
        $(`#variant-details-${itemIndex}`).html(`
            SKU: ${sku}<br>
            Price: Rp ${new Intl.NumberFormat('id-ID').format(price)}<br>
            Stock: ${stock}
        `);
        $(`#variant-info-${itemIndex}`).show();
        
        // Update price display
        const priceFormatted = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(price);
        $(`#price-display-${itemIndex}`).html(`<strong class="text-success">${priceFormatted}</strong>`);
        
        const qtyInput = $(`#qty-${itemIndex}`);
        if (qtyInput.length) {
            qtyInput.attr('max', stock);
            if (stock === 0) {
                qtyInput.val(0).prop('disabled', true);
            } else {
                qtyInput.prop('disabled', false);
                if (qtyInput.val() > stock) {
                    qtyInput.val(stock);
                }
            }
        }
        
        updateAllVariantDropdowns();
        updatePricingSummary(); // Update pricing when variant changes
    } else {
        variantSelect.removeData('previous-variant-id');
        $(`#variant-info-${itemIndex}`).hide();
        $(`#price-display-${itemIndex}`).html('<span class="text-muted">Select variant to see price</span>');
        const qtyInput = $(`#qty-${itemIndex}`);
        if (qtyInput.length) {
            qtyInput.removeAttr('max').prop('disabled', false);
        }
        
        updateAllVariantDropdowns();
        updatePricingSummary(); // Update pricing when variant is deselected
    }
}

function resetVariantSelections() {
    selectedVariants.clear();
    $('.variant-select').each(function() {
        const itemIndex = $(this).data('item-index');
        $(this).val('').trigger('change');
        $(`#variant-info-${itemIndex}`).hide();
    });
    $('.variant-attribute-select').each(function() {
        const itemIndex = $(this).data('item-index');
        $(this).val('').trigger('change');
        $(`#variant-info-${itemIndex}`).hide();
        $(`#variant-id-${itemIndex}`).val('');
    });
}

function updateAllVariantDropdowns() {
    $('.variant-select').each(function() {
        const currentSelect = $(this);
        const currentValue = currentSelect.val();
        
        currentSelect.find('option').each(function() {
            const option = $(this);
            const variantId = option.val();
            
            if (variantId && variantId !== currentValue) {
                if (selectedVariants.has(variantId)) {
                    option.prop('disabled', true);
                    if (!option.text().includes('(Already Selected)')) {
                        option.text(option.text() + ' (Already Selected)');
                    }
                } else {
                    option.prop('disabled', false);
                    option.text(option.text().replace(' (Already Selected)', ''));
                }
            }
        });
    });
}

// ===== PRICING CALCULATION FUNCTIONS =====
function updatePricingSummary() {
    const orderItems = $('#order-items .order-item');
    const pricingItems = $('#pricing-items');
    
    if (orderItems.length === 0) {
        $('#pricing-summary').hide();
        return;
    }
    
    pricingItems.empty();
    let totalItems = 0;
    let totalQuantity = 0;
    let grandTotal = 0;
    
    orderItems.each(function(index) {
        const item = $(this);
        const itemIndex = item.data('index');
        const productName = item.find('input[name="product_id[]"]').siblings('input[type="text"]').val() || 'Unknown Product';
        const qty = parseInt(item.find(`#qty-${itemIndex}`).val()) || 0;
        
        let price = 0;
        let variantInfo = '';
        
        // Get price from variant selection (for configurable products)
        const variantSelect = item.find('.variant-select');
        if (variantSelect.length && variantSelect.val()) {
            const selectedOption = variantSelect.find('option:selected');
            price = parseFloat(selectedOption.data('price')) || 0;
            variantInfo = selectedOption.text().split(' - ')[0] || '';
        } else {
            // Get price from hidden input (for simple products)
            const hiddenVariantInput = item.find('input[name^="variant_id"]');
            if (hiddenVariantInput.length) {
                price = parseFloat(hiddenVariantInput.data('price')) || 0;
                variantInfo = hiddenVariantInput.data('sku') || '';
            }
        }
        
        const subtotal = price * qty;
        
        if (price > 0 && qty > 0) {
            totalItems++;
            totalQuantity += qty;
            grandTotal += subtotal;
            
            const priceFormatted = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(price);
            
            const subtotalFormatted = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(subtotal);
            
            const row = `
                <tr>
                    <td>
                        <strong>${productName.split(' (')[0]}</strong>
                        ${variantInfo ? `<br><small class="text-muted">${variantInfo}</small>` : ''}
                    </td>
                    <td class="text-center">${qty}</td>
                    <td class="text-right">${priceFormatted}</td>
                    <td class="text-right"><strong>${subtotalFormatted}</strong></td>
                </tr>
            `;
            pricingItems.append(row);
        }
    });
    
    const grandTotalFormatted = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(grandTotal);
    
    $('#total-items').text(totalItems);
    $('#total-quantity').text(totalQuantity);
    $('#grand-total').text(grandTotalFormatted);
    
    if (totalItems > 0) {
        $('#pricing-summary').show();
    } else {
        $('#pricing-summary').hide();
    }
}

function attachPricingListeners(itemIndex) {
    // Listen for quantity changes
    $(`#qty-${itemIndex}`).on('input change', function() {
        updatePricingSummary();
    });
    
    // Listen for variant selection changes
    $(`.variant-select[data-item-index="${itemIndex}"]`).on('change', function() {
        updatePricingSummary();
    });
}

function updateVariantSelection(itemIndex, productId) {
    const attributeSelects = $(`.variant-attribute-select[data-item-index="${itemIndex}"]`);
    const selectedAttributes = {};
    let allSelected = true;
    
    attributeSelects.each(function() {
        const attributeName = $(this).data('attribute');
        const value = $(this).val();
        
        if (value) {
            selectedAttributes[attributeName] = value;
        } else {
            allSelected = false;
        }
    });
    
    if (allSelected && Object.keys(selectedAttributes).length > 0) {
        fetch(`/api/products/${productId}/variant-by-attributes`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                attributes: selectedAttributes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                const variant = data.data;
                $(`#variant-details-${itemIndex}`).html(`
                    SKU: ${variant.sku}<br>
                    Price: Rp ${variant.formatted_price}<br>
                    Stock: ${variant.stock}
                `);
                $(`#variant-id-${itemIndex}`).val(variant.id);
                $(`#variant-info-${itemIndex}`).show();
            } else {
                $(`#variant-info-${itemIndex}`).hide();
                $(`#variant-id-${itemIndex}`).val('');
            }
        })
        .catch(error => {
            console.error('Error finding variant:', error);
            $(`#variant-info-${itemIndex}`).hide();
            $(`#variant-id-${itemIndex}`).val('');
        });
    } else {
        $(`#variant-info-${itemIndex}`).hide();
        $(`#variant-id-${itemIndex}`).val('');
    }
}

function addProductToOrder(product) {
    const orderItems = document.getElementById('order-items');
    const itemIndex = orderItems.children.length;
    
    let attributeSection = '';
    let priceDisplayText = 'Loading price...';
    
    if (product.type === 'configurable') {
        attributeSection = `
            <div id="attribute-section-${itemIndex}" class="attribute-section mt-3">
                <p class="text-info"><small>Loading attributes...</small></p>
            </div>
        `;
        priceDisplayText = 'Select variant to see price';
    } else {
        // For simple products, show loading initially
        priceDisplayText = 'Loading price...';
    }

    const productHtml = `
        <div class="order-item card mb-2 p-3" data-index="${itemIndex}">
            <div class="row">
                <div class="col-md-5">
                    <label><i class="fas fa-box"></i> Product</label>
                    <input type="text" class="form-control" value="${product.name} (${product.sku})" readonly>
                    <input type="hidden" name="product_id[]" value="${product.id}">
                    <input type="hidden" name="product_type[]" value="${product.type || 'simple'}">
                </div>
                <div class="col-md-2">
                    <label><i class="fas fa-hashtag"></i> Qty</label>
                    <input type="number" name="qty[]" id="qty-${itemIndex}" class="form-control" value="1" min="1" required>
                </div>
                <div class="col-md-3">
                    <label><i class="fas fa-tag"></i> Price Info</label>
                    <div id="price-display-${itemIndex}" class="form-control-static text-muted">
                        ${priceDisplayText}
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-item">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
            </div>
            ${attributeSection}
        </div>
    `;

    orderItems.insertAdjacentHTML('beforeend', productHtml);
    
    // Attach pricing listeners
    attachPricingListeners(itemIndex);
    
    if (product.type === 'configurable') {
        loadAttributesForProduct(product.id, itemIndex);
    } else {
        // For simple products, load the default variant directly
        loadSimpleProductVariant(product.id, itemIndex, product);
    }
    
    // Update pricing summary
    updatePricingSummary();
}

  $('#order-items').on('click','.remove-item', function(){
    const orderItem = $(this).closest('.order-item');
    const variantSelect = orderItem.find('.variant-select');
    
    if (variantSelect.length) {
        const variantId = variantSelect.val();
        if (variantId) {
            selectedVariants.delete(variantId);
        }
    }
    
    orderItem.remove();
    updateAllVariantDropdowns();
    updatePricingSummary(); // Update pricing when item is removed
  });

  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-item')) {
        const orderItem = e.target.closest('.order-item');
        const variantSelect = orderItem.querySelector('.variant-select');
        
        if (variantSelect && variantSelect.value) {
            selectedVariants.delete(variantSelect.value);
        }
        
        orderItem.remove();
        updateAllVariantDropdowns();
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
    let selectedVariants = new Set();

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
    
    const selectedVariantIds = [];
    const variantSelects = document.querySelectorAll('.variant-select');
    
    for (let select of variantSelects) {
        const variantId = select.value;
        if (variantId) {
            if (selectedVariantIds.includes(variantId)) {
                alert('You cannot select the same product variant multiple times. Please choose different variants.');
                return false;
            }
            selectedVariantIds.push(variantId);
        }
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
