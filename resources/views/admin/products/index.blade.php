@extends('layouts.app')

@section('content')

    <!-- Main content -->
    <section class="content pt-4">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Data Produk</h3>
                <a href="{{ route('admin.products.create')}}" class="btn btn-info shadow-sm float-right"> <i class="fa fa-plus"></i> Tambah </a>
                <button onclick="addForm();" class="btn btn-success shadow-sm float-right"> <i class="fa fa-plus"></i> Excel </button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="table-responsive">
                    <table id="data-table" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>SKU</th>
                        <th>Tipe</th>
                        <th>Nama Produk</th>
                        <th>Harga Jual</th>
                        <th>Harga Beli</th>
                        <th>Status</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $product->sku }}</td>
                                <td>{{ $product->type }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ number_format($product->price) }}</td>
                                <td>{{ number_format($product->harga_beli) }}</td>
                                <td>{{ $product->statusLabel() }}</td>
                                @if ($product->productInventory != null)
                                  <td>{{ $product->productInventory->qty }}</td>
                                @else
                                  <td>No quantity</td>
                                @endif
                                <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-primary">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form onclick="return confirm('are you sure !')" action="{{ route('admin.products.destroy', $product) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" type="submit"><i class="fa fa-trash"></i></button>
                                    </form>
                                </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Data Kosong !</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    @includeIf('admin.products.form')
@endsection

@push('style-alt')
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css">
@endpush

@push('script-alt')
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script>

    $("#data-table").DataTable();
    function addForm() {
        $('#modal-supplier').modal('show');
        $('#modal-supplier').addClass('show');
    }
    </script>
@endpush
