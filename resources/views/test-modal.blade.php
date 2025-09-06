<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Modal Test</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap.min.css">
</head>
<body>
    <div class="container" style="margin-top: 50px;">
        <h2>Modal Test Page</h2>
        <button type="button" class="btn btn-primary" id="add-product-btn">
            <i class="fa fa-search"></i> Search & Add Product
        </button>
        
        <div class="modal fade" id="modalProduct" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Select Product</h4>
                    </div>
                    <div class="modal-body">
                        <table id="product-table" class="table table-bordered table-hover">
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
    </div>

    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap.min.js"></script>
    
    <script>
    $(document).ready(function() {
        console.log('Document ready');
        console.log('jQuery version:', $.fn.jquery);
        console.log('DataTable available:', typeof $.fn.DataTable);
        console.log('Bootstrap modal available:', typeof $.fn.modal);
        
        $('#add-product-btn').on('click', function() {
            console.log('Add product button clicked');
            $('#modalProduct').modal('show');
            console.log('Modal show called');
            
            setTimeout(function() {
                if (!$.fn.DataTable.isDataTable('#product-table')) {
                    console.log('Initializing DataTable...');
                    var table1 = $('#product-table').DataTable({
                        processing: true,
                        responsive: true,
                        serverSide: true,
                        autoWidth: false,
                        ajax : {
                            url: "{{ route('test.products.data') }}",
                        },
                        columns: [
                            {data: 'DT_RowIndex', searchable: false, sortable: false},
                            {data: 'name'},
                            {data: 'sku'},
                            {data: 'price'},
                            {data: 'action', searchable: false, sortable: false},
                        ]
                    });
                    console.log('DataTable initialized');
                }
            }, 500);
        });
    });
    </script>
</body>
</html>
