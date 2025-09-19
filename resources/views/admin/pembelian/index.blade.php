@extends('layouts.app')

@section('title')
    Daftar Pembelian
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Pembelian</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm()" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Transaksi Baru</button>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-pembelian">
                    <thead>
                        <th width="5%">No</th>
                        <th>Tanggal Barang Datang</th>
                        <th>Supplier</th>
                        <th>Total Item</th>
                        <th>Total Harga</th>
                        <th>Diskon</th>
                        <th>Total Bayar</th>
                        <th>Waktu Faktur Dibuat</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('admin.pembelian.supplier')
@includeIf('admin.pembelian.detail')
@endsection

@push('scripts')
<script>
    let table, table1;

    $(function () {
        table = $('.table-pembelian').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('admin.pembelian.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'tanggal'},
                {data: 'supplier'},
                {data: 'total_item'},
                {data: 'total_harga'},
                {data: 'diskon'},
                {data: 'bayar'},
                {data: 'waktu'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $('.table-supplier').DataTable({
            responsive: true,
            paging: false,
            searching: true,
            info: false,
            ordering: false
        });

        table1 = $('.table-detail').DataTable({
            processing: true,
            bSort: false,
            dom: 'Brt',
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_produk'},
                {data: 'name'},
                {data: 'harga_beli'},
                {data: 'jumlah'},
                {data: 'subtotal'},
            ]
        });
    });

    function addForm() {
        $('#modal-supplier').show();
        $('body').addClass('modal-open');
        if ($('.modal-backdrop').length === 0) {
            $('body').append('<div class="modal-backdrop"></div>');
        }
    }

    function showDetail(url) {
        $('#modal-detail').show();
        $('body').addClass('modal-open');
        if ($('.modal-backdrop').length === 0) {
            $('body').append('<div class="modal-backdrop"></div>');
        }

        table1.ajax.url(url);
        table1.ajax.reload();
    }

    $(document).on('click', '[data-dismiss="modal"]', function() {
        $('.modal').hide();
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    });

    $(document).on('click', '.modal-backdrop', function() {
        $('.modal').hide();
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    });

    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload();
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }
</script>
@endpush
