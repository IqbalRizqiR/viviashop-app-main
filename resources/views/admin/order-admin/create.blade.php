@extends('layouts.app')
@section('title', 'Create Order')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('admin.orders.storeAdmin') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Customer Information</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" readonly name="first_name" value="Admin" class="form-control" value="{{ old('first_name') }}" required>
                            </div>
                            <div class="form-group">

                                 <label for="last_name">Last Name</label>
                                <input type="text" readonly name="last_name" value="Toko" class="form-control" value="{{ old('last_name') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="address1">Address Line 1</label>
                                <input type="text" readonly name="address1" value="Cukir, Jombang" class="form-control" value="{{ old('address1') }}" required>
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
                                <input type="text" readonly name="postcode" value="102112" class="form-control" value="{{ old('postcode') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" readonly name="phone" value="9121240210" class="form-control" value="{{ old('phone') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" readonly name="email" value="admin@gmail.com" class="form-control" value="{{ old('email') }}" required>
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
                                <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#barcodeModal">
                                    <i class="fas fa-barcode"></i> Scan Barcode
                                </button>
                            </div>
                            <div id="order-items"></div>
                            <div class="form-group">
                                <input type="text" name="note" class="form-control" placeholder="Notes if exist">
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
                        <button type="submit" class="btn btn-success">Create Order</button>
                    </div>
                    <!-- Barcode Scanner Modal -->
                    <div class="modal fade" id="barcodeModal" tabindex="-1" aria-labelledby="barcodeModalLabel">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="barcodeModalLabel">Scan Barcode</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <div id="scanner" style="width: 100%; max-width: 400px; height: 300px; border: 1px solid #ccc; margin: 0 auto;"></div>
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

<script>
let isScanning = false;

// Start scanner AFTER modal is fully shown
$('#barcodeModal').on('shown.bs.modal', function() {
    setTimeout(function() {
        startBarcodeScanner();
    }, 300); // Small delay to ensure modal is rendered
});

// Stop scanner when modal closes
$('#barcodeModal').on('hidden.bs.modal', function() {
    stopBarcodeScanner();
    // Clear the scanner div
    document.getElementById('scanner').innerHTML = '';
});

document.getElementById('start-scan').addEventListener('click', startBarcodeScanner);
document.getElementById('stop-scan').addEventListener('click', stopBarcodeScanner);

function startBarcodeScanner() {
    if (isScanning) return;

    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        alert('Camera not supported in this browser');
        return;
    }

    // Clear any existing content
    document.getElementById('scanner').innerHTML = '';
    document.getElementById('result').innerHTML = 'Initializing camera...';

    Quagga.init({
        inputStream: {
            name: "Live",
            type: "LiveStream",
            target: document.querySelector('#scanner'),
            constraints: {
                width: { min: 400 },
                height: { min: 300 },
                facingMode: "environment"
            }
        },
        locator: {
            patchSize: "medium",
            halfSample: true
        },
        decoder: {
            readers: ["code_128_reader", "ean_reader", "code_39_reader"]
        },
        locate: true
    }, function(err) {
        if (err) {
            console.error('Quagga init error:', err);
            document.getElementById('result').innerHTML = '<span style="color: red;">Camera error: ' + err.message + '</span>';
            return;
        }

        console.log('Scanner initialized successfully');
        document.getElementById('result').innerHTML = 'Camera ready - point at barcode';
        isScanning = true;
        Quagga.start();
    });
}

function stopBarcodeScanner() {
    if (isScanning) {
        Quagga.stop();
        isScanning = false;
        document.getElementById('scanner').innerHTML = '';
        console.log('Scanner stopped');
    }
}

// When barcode is detected
Quagga.onDetected(function(result) {
    const code = result.codeResult.code;
    console.log('Barcode detected:', code);

    // Stop scanning immediately to prevent multiple detections
    stopBarcodeScanner();

    // Find and add product
    findAndAddProduct(code);
});

function findAndAddProduct(barcode) {
    document.getElementById('result').innerHTML = '<strong>Looking up product: ' + barcode + '</strong>';

    fetch('{{ route("admin.products.find-barcode") }}', {
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
            addProductToOrder(data.product);
            document.getElementById('result').innerHTML = '<strong style="color: green;">✓ Product added: ' + data.product.name + '</strong>';

            setTimeout(function() {
                $('#barcodeModal').modal('hide');
            }, 1500);
        } else {
            document.getElementById('result').innerHTML = '<strong style="color: red;">✗ Product not found: ' + barcode + '</strong>';

            // Restart scanner after 2 seconds
            setTimeout(function() {
                startBarcodeScanner();
            }, 2000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('result').innerHTML = '<strong style="color: red;">Error occurred</strong>';
        setTimeout(function() {
            startBarcodeScanner();
        }, 2000);
    });
}

function addProductToOrder(product) {
    const orderItems = document.getElementById('order-items');
    const itemIndex = orderItems.children.length;

    const productHtml = `
        <div class="order-item card mb-2 p-3">
            <div class="row">
                <div class="col-md-6">
                    <label>Product</label>
                    <input type="text" class="form-control" value="${product.name} (${product.sku})" readonly>
                    <input type="hidden" name="products[${itemIndex}][id]" value="${product.id}">
                </div>
                <div class="col-md-3">
                    <label>Qty</label>
                    <input type="number" name="products[${itemIndex}][qty]" class="form-control" value="1" min="1" required>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-item">Remove</button>
                </div>
            </div>
        </div>
    `;

    orderItems.insertAdjacentHTML('beforeend', productHtml);
}

// Remove item functionality
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-item')) {
        e.target.closest('.order-item').remove();
    }
});
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
          name = $(this).data('name');

    // append to your form (example)
    $('#order-items').append(`
      <div class="order-item">
        <div class="form-group">
            <input type="hidden" name="product_id[]" value="${id}">
            <label>${name} (${sku})</label>
            <input type="number" name="qty[]" class="form-control" value="1" min="1">
            <button type="button" class="remove-item btn btn-danger">x</button>
        </div>
      </div>
    `);

    $('#productModal').modal('hide');
  });

  // remove item
  $('#order-items').on('click','.remove-item', function(){
    $(this).closest('.order-item').remove();
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
</script>
@endpush
