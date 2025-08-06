@extends('layouts.app')

@section('content')

    <!-- Main content -->
    <section class="content pt-4">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  Attribute Options: {{ $attribute->name }} ({{ $attribute->code }})
                  <small class="text-muted">- Level 2</small>
                </h3>
                <a href="{{ route('admin.attributes.index')}}" class="btn btn-secondary shadow-sm float-right">
                  <i class="fa fa-arrow-left"></i> Back to Attributes
                </a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                
                <!-- Form untuk menambah option baru -->
                <div class="row mb-4">
                  <div class="col-md-6">
                    <div class="card">
                      <div class="card-header">
                        <h5>Add New Option</h5>
                      </div>
                      <div class="card-body">
                        <form action="{{ route('attributes.options.store', $attribute) }}" method="POST">
                          @csrf
                          <div class="form-group">
                            <label for="name">Option Name</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   placeholder="e.g., 100gr, 120gr, 150gr" required>
                            <small class="form-text text-muted">Contoh: 100gr, 120gr, 150gr, 200gr</small>
                          </div>
                          <button type="submit" class="btn btn-success">
                            <i class="fa fa-plus"></i> Add Option
                          </button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="table-responsive">
                    <table id="data-table" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Option Name</th>
                        <th>Sub-Options Count</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @forelse($attributeOptions as $option)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                  <strong>{{ $option->name }}</strong>
                                  <br><small class="text-muted">Level 2 Option</small>
                                </td>
                                <td>
                                  <span class="badge badge-info">
                                    {{ $option->sub_attribute_options->count() }} sub-options
                                  </span>
                                </td>
                                <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('attributes.sub-options.index', $option) }}" 
                                       class="btn btn-sm btn-warning" title="Manage Sub-Options">
                                        <i class="fa fa-cogs"></i> Sub-Options
                                    </a>
                                    <form onclick="return confirm('Are you sure? This will also delete all sub-options!')" 
                                          action="{{ route('attributes.options.destroy', $option) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" type="submit">
                                          <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No options found. Add some options above.</td>
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

@endsection

@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection

@section('scripts')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>

    <script>
        $(function () {
            $("#data-table").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
            });
        });
    </script>
@endsection
