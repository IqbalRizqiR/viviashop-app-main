@extends('layouts.app')

@section('title')
    Transaksi Pembelian
@endsection

@push('css')
<style>
    .tampil-bayar {
        font-size: 5em;
        text-align: center;
        height: 100px;
    }

    .tampil-terbilang {
        padding: 10px;
        background: #f0f0f0;
    }

    .table-pembelian tbody tr:last-child {
        display: none;
    }

    @media(max-width: 768px) {
        .tampil-bayar {
            font-size: 3em;
            height: 70px;
            padding-top: 5px;
        }
    }
</style>
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Transaksi Pembelian</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <table>
                    <tr>
                        <td>Supplier</td>
                        <td>: {{ $supplier->nama }}</td>
                    </tr>
                    <tr>
                        <td>Telepon</td>
                        <td>: {{ $supplier->telepon }}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>: {{ $supplier->alamat }}</td>
                    </tr>
                </table>
            </div>
            <div class="box-body">

                <form class="form-produk">
                    @csrf
                    <div class="form-group row">
                        <label for="kode_produk" class="col-lg-2">Kode Produk</label>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <input type="hidden" name="id_pembelian" id="id_pembelian" value="{{ $id_pembelian }}">
                                <input type="hidden" name="id_produk" id="id_produk">
                                <input type="hidden" name="variant_id" id="variant_id">
                                <input type="text" class="form-control" name="kode_produk" id="kode_produk">
                                <span class="input-group-btn">
                                    <button onclick="tampilProduk()" class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>

                <table class="table table-stiped table-bordered table-pembelian">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th width="15%">Jumlah</th>
                        <th>Subtotal</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="tampil-bayar bg-primary"></div>
                        <div class="tampil-terbilang"></div>
                        
                        <div class="total d-none">0</div>
                        <div class="total_item d-none">0</div>
                    </div>
                    <div class="col-lg-4">
                        <form action="{{ route('admin.pembelian.store') }}" class="form-pembelian" method="post">
                            @csrf
                            <input type="hidden" name="id_pembelian" value="{{ $id_pembelian }}">
                            <input type="hidden" name="total" id="total">
                            <input type="hidden" name="total_item" id="total_item">
                            <input type="hidden" name="bayar" id="bayar">

                            <div class="form-group row">
                                <label for="totalrp" class="col-lg-2 control-label">Total</label>
                                <div class="col-lg-8">
                                    <input type="text" id="totalrp" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="totalrp" class="col-lg-2 control-label">Tanggal Faktur Dibuat</label>
                                <div class="col-lg-8">
                                    <input type="date" name="waktu" id="totalrp" class="form-control waktu">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="diskon" class="col-lg-2 control-label">Diskon</label>
                                <div class="col-lg-8">
                                    <input type="number" name="diskon" id="diskon" class="form-control" value="{{ $diskon }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="payment_method" class="col-lg-2 control-label">Metode Bayar</label>
                                <div class="col-lg-8">
                                    <select name="payment_method" id="payment_method" class="form-control">
                                        <option value="cash">Tunai</option>
                                        <option value="bank_transfer">Transfer Bank</option>
                                        <option value="credit">Kredit/Tempo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="bayar" class="col-lg-2 control-label">Bayar</label>
                                <div class="col-lg-8">
                                    <input type="text" id="bayarrp" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="notes" class="col-lg-2 control-label">Catatan</label>
                                <div class="col-lg-8">
                                    <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Catatan pembelian (opsional)"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-sm btn-flat pull-right btn-simpan"><i class="fa fa-floppy-o"></i> Simpan Transaksi</button>
            </div>
        </div>
    </div>
</div>

@include('admin.pembelian_detail.produk')
@endsection

@push('scripts')
<script>
    const date = new Date();
    const today = date.toISOString().substring(0, 10);
    console.log(today);
    document.querySelector('.waktu').value = today;
    let table, table2;

    $(function () {
        $('body').addClass('sidebar-collapse');
        
        // Ensure modal backdrop is properly configured
        $('.modal').on('show.bs.modal', function () {
            $('body').addClass('modal-open');
        });
        
        $('.modal').on('hidden.bs.modal', function () {
            $('body').removeClass('modal-open');
        });

        table = $('.table-pembelian').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('admin.pembelian_detail.data', $id_pembelian) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_produk'},
                {data: 'nama_produk'},
                {data: 'harga_beli'},
                {data: 'harga_jual'},
                {data: 'jumlah'},
                {data: 'subtotal'},
                {data: 'aksi', searchable: false, sortable: false},
            ],
            dom: 'Brt',
            bSort: false,
            paginate: false
        });
        
        table.on('draw.dt', function () {
            loadForm($('#diskon').val());
            
            const data = table.ajax.json();
            if (data && data.total !== undefined) {
                $('.total').text(data.total);
                $('.total_item').text(data.total_item);
            }
        });
        
        $(document).on('input', '.quantity', function () {
            let id = $(this).data('id');
            let jumlah = parseInt($(this).val());

            if (jumlah < 1) {
                $(this).val(1);
                alert('Jumlah tidak boleh kurang dari 1');
                return;
            }
            if (jumlah > 10000) {
                $(this).val(10000);
                alert('Jumlah tidak boleh lebih dari 10000');
                return;
            }

            $.post(`{{ url('/admin/pembelian_detail') }}/${id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'put',
                    'jumlah': jumlah
                })
                .done(response => {
                    $(this).on('mouseout', function () {
                        console.log(id);
                        table.ajax.reload(() => {
                            loadForm($('#diskon').val());
                            fetchRealtimeStock();
                        });
                    });
                })
                .fail(errors => {
                    alert('Tidak dapat menyimpan data');
                    return;
                });
        });
        $(document).on('input', '.harga_jual', function () {
            let id = $(this).data('id');
            let harga_jual = parseInt($(this).val());
            let id_pembelian_detail = $(this).data('uid');
            let jumlah = parseInt($('.quantity').val());
            console.log(id_pembelian_detail);

            if (harga_jual < 1) {
                $(this).val(1);
                alert('Harga tidak boleh kurang dari Rp. 1');
                return;
            }

            $.post(`{{ url('/admin/updateHargaJual') }}/${id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'put',
                    'harga_jual': harga_jual,
                    'id_pembayaran_detail': id_pembelian_detail,
                    'jumlah': jumlah
                })
                .done(response => {
                    $(this).on('mouseout', function () {
                        table.ajax.reload(() => {
                            loadForm($('#diskon').val());
                            fetchRealtimeStock();
                        });
                    });
                })
                .fail(errors => {
                    alert('Tidak dapat menyimpan data');
                    return;
                });
        });
        $(document).on('input', '.harga_beli', function () {
            let id = $(this).data('id');
	        console.log($(this).data('id'));
            let id_pembelian_detail = $(this).data('uid');
            console.log(id_pembelian_detail);
            let harga_beli = parseInt($(this).val());
            let jumlah = parseInt($('.quantity').val());

            if (harga_beli < 1) {
                $(this).val(1);
                alert('Harga tidak boleh kurang dari Rp. 1');
                return;
            }

            $.post(`{{ url('/admin/updateHargaBeli') }}/${id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'put',
                    'harga_beli': harga_beli,
                    'id_pembayaran_detail': id_pembelian_detail,
                    'jumlah': jumlah
                })
                .done(response => {
                    $(this).on('mouseout', function () {
                        table.ajax.reload(() => {
                            loadForm($('#diskon').val());
                            fetchRealtimeStock();
                        });
                    });
                })
                .fail(errors => {
                    alert('Tidak dapat menyimpan data');
                    return;
                });
        });

        $(document).on('input', '#diskon', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }

            loadForm($(this).val());
        });

        $('.btn-simpan').on('click', function () {
            $('.form-pembelian').submit();
        });
        
        // Event handlers for modal close buttons
        $(document).on('click', '#modal-produk .close, #modal-produk [data-dismiss="modal"]', function() {
            console.log('Product modal close button clicked');
            $('#modal-produk').modal('hide');
        });
        
        $(document).on('click', '#modal-variant .close, #modal-variant [data-dismiss="modal"]', function() {
            console.log('Variant modal close button clicked');
            $('#modal-variant').modal('hide');
        });
        
        // Handle modal backdrop clicks
        $(document).on('click', '#modal-produk', function(e) {
            if (e.target === this) {
                console.log('Product modal backdrop clicked');
                $('#modal-produk').modal('hide');
            }
        });
        
        $(document).on('click', '#modal-variant', function(e) {
            if (e.target === this) {
                console.log('Variant modal backdrop clicked');
                $('#modal-variant').modal('hide');
            }
        });
        
        // Ensure proper modal cleanup on hidden
        $('#modal-produk').on('hidden.bs.modal', function() {
            console.log('Product modal fully hidden');
            $(this).removeClass('show');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });
        
        $('#modal-variant').on('hidden.bs.modal', function() {
            console.log('Variant modal fully hidden');
            $(this).removeClass('show');
            if (!$('#modal-produk').hasClass('show')) {
                $('body').removeClass('modal-open');
            }
            $('.modal-backdrop').remove();
        });
        
        $(document).on('input', '#search-produk', function() {
            const searchTerm = $(this).val().toLowerCase();
            filterProducts();
        });
        
        $(document).on('change', '#filter-type', function() {
            filterProducts();
        });
        
        if ($('#modal-produk').length > 0) {
            console.log('Modal found and initialized');
        } else {
            console.error('Modal not found in DOM');
        }
    });

    function filterProducts() {
        const searchTerm = $('#search-produk').val().toLowerCase();
        const filterType = $('#filter-type').val();
        
        $('.table-produk tbody tr').each(function() {
            const row = $(this);
            const productName = row.data('name') || '';
            const productType = row.data('type') || '';
            
            let showRow = true;
            
            if (searchTerm && !productName.includes(searchTerm)) {
                showRow = false;
            }
            
            if (filterType && productType !== filterType) {
                showRow = false;
            }
            
            if (showRow) {
                row.show();
            } else {
                row.hide();
            }
        });
        
        currentPage = 1;
        updateRowNumbers();
    }
    
    function updateRowNumbers() {
        let visibleIndex = 1;
        $('.table-produk tbody tr:visible').each(function() {
            $(this).find('td:first').text(visibleIndex++);
        });
        updatePagination();
    }
    
    let currentPage = 1;
    const itemsPerPage = 8;
    let realtimeStockData = {};
    
    function updatePagination() {
        const visibleRows = $('.table-produk tbody tr:visible');
        const totalItems = visibleRows.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        
        // Hide all rows first
        visibleRows.hide();
        
        // Show only current page rows
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        visibleRows.slice(startIndex, endIndex).show();
        
        // Update info
        const showingStart = totalItems > 0 ? startIndex + 1 : 0;
        const showingEnd = Math.min(endIndex, totalItems);
        $('#showing-start').text(showingStart);
        $('#showing-end').text(showingEnd);
        $('#total-products').text(totalItems);
        
        // Update pagination buttons
        $('#prev-btn').toggleClass('disabled', currentPage <= 1);
        $('#next-btn').toggleClass('disabled', currentPage >= totalPages);
        
        // Update page numbers
        let pageNumbers = '';
        for (let i = 1; i <= totalPages; i++) {
            if (i === currentPage) {
                pageNumbers += `<button class="paginate_button current" onclick="goToPage(${i})">${i}</button>`;
            } else {
                pageNumbers += `<button class="paginate_button" onclick="goToPage(${i})">${i}</button>`;
            }
        }
        $('#page-numbers').html(pageNumbers);
    }
    
    function fetchRealtimeStock() {
        const pembelianId = {{ $id_pembelian }};
        
        console.log('Fetching realtime stock for purchase ID:', pembelianId);
        console.log('Modal state:', $('#modal-produk').hasClass('show') ? 'open' : 'closed');
        
        $.get(`{{ url('/admin/pembelian_detail/realtime-stock') }}/${pembelianId}`)
            .done(function(data) {
                console.log('Realtime stock data received:', data);
                realtimeStockData = data;
                updateStockDisplay();
                console.log('Stock display updated');
            })
            .fail(function(xhr, status, error) {
                console.error('Failed to fetch realtime stock data:', error);
                console.error('Response:', xhr.responseText);
                console.error('Status:', status);
                console.error('XHR:', xhr);
            });
    }
    
    function updateStockDisplay() {
        console.log('updateStockDisplay called');
        console.log('realtimeStockData:', realtimeStockData);
        
        let updatedCount = 0;
        
        $('.table-produk tbody tr').each(function() {
            const row = $(this);
            const productId = row.data('product-id');
            
            console.log('Processing row for product ID:', productId);
            
            if (realtimeStockData[productId]) {
                const stockData = realtimeStockData[productId];
                const stockCell = row.find('td:nth-child(7)');
                
                console.log('Found stock data for product', productId, ':', stockData);
                
                if (stockData.type === 'simple') {
                    const projectedStock = stockData.projected_stock || stockData.available_stock;
                    const purchasedQty = stockData.purchased_qty || stockData.reserved_qty;
                    const originalStock = stockData.original_stock;
                    
                    console.log(`Product ${productId} - Original: ${originalStock}, Purchased: ${purchasedQty}, Projected: ${projectedStock}`);
                    
                    let badgeClass = 'badge-success';
                    if (projectedStock <= 0) badgeClass = 'badge-danger';
                    else if (projectedStock <= 10) badgeClass = 'badge-warning';
                    
                    let stockDisplay = `<span class="badge ${badgeClass}" style="font-size: 9px;">${projectedStock}</span>`;
                    if (purchasedQty > 0) {
                        stockDisplay += `<br><small class="text-success" style="font-size: 8px;">+${purchasedQty} pembelian</small>`;
                    }
                    
                    stockCell.html(stockDisplay);
                    updatedCount++;
                    
                    // No need to disable buttons for purchase - we're buying, not selling
                    const actionButton = row.find('.btn-pilih');
                    actionButton.prop('disabled', false);
                    
                } else if (stockData.type === 'configurable') {
                    const totalProjectedStock = stockData.total_projected_stock || stockData.total_available_stock;
                    const totalPurchasedQty = stockData.total_purchased_qty || stockData.total_reserved_qty;
                    const totalOriginalStock = stockData.total_original_stock;
                    
                    console.log(`Product ${productId} (configurable) - Original: ${totalOriginalStock}, Purchased: ${totalPurchasedQty}, Projected: ${totalProjectedStock}`);
                    
                    let badgeClass = 'badge-success';
                    if (totalProjectedStock <= 0) badgeClass = 'badge-danger';
                    else if (totalProjectedStock <= 10) badgeClass = 'badge-warning';
                    
                    let stockDisplay = `<span class="badge ${badgeClass}" style="font-size: 9px;">${totalProjectedStock}</span>`;
                    if (totalPurchasedQty > 0) {
                        stockDisplay += `<br><small class="text-success" style="font-size: 8px;">+${totalPurchasedQty} pembelian</small>`;
                    }
                    
                    stockCell.html(stockDisplay);
                    updatedCount++;
                    
                    // No need to disable buttons for purchase
                    const actionButton = row.find('.btn-variant');
                    actionButton.prop('disabled', false);
                }
            } else {
                console.log('No stock data found for product ID:', productId);
            }
        });
        
        console.log(`Updated ${updatedCount} products with realtime stock data`);
    }
    
    function changePage(direction) {
        const visibleRows = $('.table-produk tbody tr:visible');
        const totalItems = visibleRows.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        
        const newPage = currentPage + direction;
        if (newPage >= 1 && newPage <= totalPages) {
            currentPage = newPage;
            updatePagination();
        }
    }
    
    function goToPage(page) {
        const visibleRows = $('.table-produk tbody tr:visible');
        const totalItems = visibleRows.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            updatePagination();
        }
    }

        function tampilProduk() {
        console.log('tampilProduk called');
        
        if (typeof $ === 'undefined') {
            console.error('jQuery not loaded');
            alert('jQuery not loaded. Please refresh the page.');
            return;
        }
        
        const modal = $('#modal-produk');
        if (modal.length === 0) {
            console.error('Modal element not found');
            alert('Modal element not found. Please refresh the page.');
            return;
        }
        
        console.log('Showing modal...');
        
        try {
            // Clean any existing modal state
            modal.removeClass('show');
            modal.css('display', 'none');
            
            // Show modal with bootstrap method
            modal.modal('show');
            
            // Additional styling to ensure proper display
            modal.css({
                'display': 'block',
                'z-index': '10050'
            });
            modal.addClass('show');
            
            // Fetch and update realtime stock immediately and after delay
            console.log('Fetching realtime stock immediately...');
            fetchRealtimeStock();
            
            setTimeout(function() {
                console.log('Fetching realtime stock after delay...');
                fetchRealtimeStock();
            }, 500);
            
            console.log('Modal display commands executed');
            
        } catch (error) {
            console.error('Error showing modal:', error);
            alert('Error showing modal. Please refresh the page.');
        }
    }

    function hideProduk() {
        console.log('hideProduk called');
        const modal = $('#modal-produk');
        modal.modal('hide');
        modal.removeClass('show');
        modal.css('display', 'none');
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
    }

    function pilihProduk(id, variantId = null) {
        $('#id_produk').val(id);
        $('#variant_id').val(variantId);
        hideProduk();
        hideVariant();
        tambahProduk();
    }

    function showVariants(productId) {
        console.log('showVariants called for product:', productId);
        
        $('#variant-content').html('<p class="text-center">Loading...</p>');
        
        const variantModal = $('#modal-variant');
        if (variantModal.length === 0) {
            console.error('Variant modal not found');
            alert('Variant modal not found. Please refresh the page.');
            return;
        }
        
        // Clean any existing modal state
        variantModal.removeClass('show');
        variantModal.css('display', 'none');
        
        // Show the modal properly
        variantModal.modal('show');
        variantModal.css({
            'display': 'block',
            'z-index': '10100'
        });
        variantModal.addClass('show');
        
        console.log('Variant modal display set');
        
        $.get(`{{ url('/admin/pembelian_detail/variants') }}/${productId}`)
            .done(response => {
                console.log('Variants loaded:', response);
                let html = `
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h5><i class="fa fa-info-circle"></i> Produk: <strong>${response.product.name}</strong></h5>
                                <p class="mb-0">Silakan pilih variant yang diinginkan dari tabel di bawah ini.</p>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead class="bg-gray">
                                <tr>
                                    <th width="8%">No</th>
                                    <th>Variant</th>
                                    <th>Harga Beli</th>
                                    <th>Harga Jual</th>
                                    <th>Margin</th>
                                    <th>Stok</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                `;
                
                response.variants.forEach((variant, index) => {
                    const hargaBeli = Number(variant.harga_beli || 0);
                    const hargaJual = Number(variant.price || 0);
                    const margin = hargaJual - hargaBeli;
                    const marginPercent = hargaBeli > 0 ? ((margin / hargaBeli) * 100).toFixed(1) : 0;
                    
                    // For purchase (pembelian), we ADD to stock, not subtract
                    const currentPurchased = realtimeStockData[response.product.id] && realtimeStockData[response.product.id].variants && realtimeStockData[response.product.id].variants[variant.id] ? (realtimeStockData[response.product.id].variants[variant.id].purchased_qty || realtimeStockData[response.product.id].variants[variant.id].reserved_qty) : 0;
                    const originalStock = variant.stock;
                    const projectedStock = originalStock + currentPurchased; // ADD purchased quantity
                    const stockClass = projectedStock > 10 ? 'success' : (projectedStock > 0 ? 'warning' : 'danger');
                    
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>
                                <strong>${variant.attributes || 'Default'}</strong>
                                ${variant.sku ? `<br><small class="text-muted">SKU: ${variant.sku}</small>` : ''}
                            </td>
                            <td>
                                <span class="text-success">
                                    <strong>Rp. ${hargaBeli.toLocaleString('id-ID')}</strong>
                                </span>
                            </td>
                            <td>
                                <span class="text-primary">
                                    <strong>Rp. ${hargaJual.toLocaleString('id-ID')}</strong>
                                </span>
                            </td>
                            <td>
                                <span class="text-${margin >= 0 ? 'success' : 'danger'}">
                                    <strong>Rp. ${margin.toLocaleString('id-ID')}</strong>
                                    <small>(${marginPercent}%)</small>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-${stockClass}">
                                    ${projectedStock} unit
                                </span>
                                ${currentPurchased > 0 ? `<br><small class="text-success">+${currentPurchased} pembelian</small>` : ''}
                            </td>
                            <td>
                                <button type="button" 
                                    class="btn btn-primary btn-sm btn-flat btn-block"
                                    onclick="pilihProduk('${response.product.id}', '${variant.id}')">
                                    <i class="fa fa-check-circle"></i>
                                    Pilih
                                </button>
                            </td>
                        </tr>
                    `;
                });
                
                html += `
                        </tbody>
                    </table>
                    </div>
                `;
                $('#variant-content').html(html);
            })
            .fail((xhr, status, error) => {
                console.error('Error loading variants:', xhr.responseText);
                $('#variant-content').html(`
                    <div class="alert alert-danger">
                        <h5><i class="fa fa-exclamation-triangle"></i> Error</h5>
                        <p>Gagal memuat data variant: ${error}</p>
                        <button type="button" class="btn btn-danger btn-sm" onclick="showVariants('${productId}')">
                            <i class="fa fa-refresh"></i> Coba Lagi
                        </button>
                    </div>
                `);
            });
    }

    function hideVariant() {
        console.log('hideVariant called');
        const modal = $('#modal-variant');
        modal.modal('hide');
        modal.removeClass('show');
        modal.css('display', 'none');
        
        // Only remove modal-open class if no other modal is open
        if (!$('#modal-produk').hasClass('show')) {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
        }
    }

    function tambahProduk() {
        console.log('tambahProduk called');
        $.post('{{ route('admin.pembelian_detail.store') }}', $('.form-produk').serialize())
            .done(response => {
                console.log('Product added successfully:', response);
                $('#kode_produk').val('').focus();
                $('#id_produk').val('');
                $('#variant_id').val('');
                table.ajax.reload(() => {
                    console.log('Table reloaded after adding product');
                    loadForm($('#diskon').val());
                    setTimeout(() => {
                        console.log('Calling fetchRealtimeStock after adding product');
                        fetchRealtimeStock();
                    }, 1000);
                });
            })
            .fail(xhr => {
                console.error('Failed to add product:', xhr);
                let errorMsg = 'Tidak dapat menyimpan data';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    errorMsg = xhr.responseText;
                }
                alert(errorMsg);
                return;
            });
    }

    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload(() => {
                        loadForm($('#diskon').val());
                        fetchRealtimeStock();
                    });
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }

    function loadForm(diskon = 0) {
        let total = parseInt($('.total').text()) || 0;
        let totalItem = parseInt($('.total_item').text()) || 0;
        
        $('#total').val(total);
        $('#total_item').val(totalItem);

        $.get(`{{ url('/admin/pembelian_detail/loadform') }}/${diskon}/${total}`)
            .done(response => {
                $('#totalrp').val('Rp. '+ response.totalrp);
                $('#bayarrp').val('Rp. '+ response.bayarrp);
                $('#bayar').val(response.bayar);
                $('.tampil-bayar').text('Rp. '+ response.bayarrp);
                $('.tampil-terbilang').text(response.terbilang);
            })
            .fail(errors => {
                alert('Tidak dapat menampilkan data');
                return;
            })
    }
</script>
@endpush
