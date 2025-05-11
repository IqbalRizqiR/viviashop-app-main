@extends('layouts.app')
@section('title', 'Create Order')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('admin.orders.storeAdmin') }}" method="POST">
                    @csrf
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Customer Information</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="address1">Address Line 1</label>
                                <input type="text" name="address1" class="form-control" value="{{ old('address1') }}" required>
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
                                <input type="text" name="postcode" class="form-control" value="{{ old('postcode') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
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
                </form>
            </div>
        </div>
    </div>
@stop

@push('scripts')
<script>
    let table;
$(function(){
  table1 = $('.table-product').DataTable({
    processing: true,
    bSort: false,
    dom: 'Brt',
    columns: [
        {data: 'DT_RowIndex', searchable: false, sortable: false},
        {data: 'name'},
        {data: 'sku'},
        {data: 'price'},
        {data: 'action', searchable: false, sortable: false},
    ]
})
});

function addModal() {
    $('#modalProduct').modal('show');
    $('#modalProduct').addClass('show');
}
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
