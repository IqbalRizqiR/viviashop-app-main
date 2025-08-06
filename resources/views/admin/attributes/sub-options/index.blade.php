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
                  Sub-Options: {{ $attributeOption->attribute->name }} > {{ $attributeOption->name }}
                  <small class="text-muted">- Level 3</small>
                </h3>
                <a href="{{ route('attributes.options.index', $attributeOption->attribute)}}" 
                   class="btn btn-secondary shadow-sm float-right">
                  <i class="fa fa-arrow-left"></i> Back to Options
                </a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      <a href="{{ route('admin.attributes.index') }}">Attributes</a>
                    </li>
                    <li class="breadcrumb-item">
                      <a href="{{ route('attributes.options.index', $attributeOption->attribute) }}">
                        {{ $attributeOption->attribute->name }} Options
                      </a>
                    </li>
                    <li class="breadcrumb-item active">{{ $attributeOption->name }} Sub-Options</li>
                  </ol>
                </nav>
                
                <!-- Form untuk menambah sub-option baru -->
                <div class="row mb-4">
                  <div class="col-md-6">
                    <div class="card">
                      <div class="card-header">
                        <h5>Add New Sub-Option for "{{ $attributeOption->name }}"</h5>
                      </div>
                      <div class="card-body">
                        <form action="{{ route('attributes.sub-options.store', $attributeOption) }}" method="POST">
                          @csrf
                          <div class="form-group">
                            <label for="name">Sub-Option Name</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   placeholder="e.g., Vinyl, Digital Print, Offset Print" required>
                            <small class="form-text text-muted">
                              Contoh untuk {{ $attributeOption->name }}: Vinyl, Digital Print, Offset Print, UV Print
                            </small>
                          </div>
                          <button type="submit" class="btn btn-success">
                            <i class="fa fa-plus"></i> Add Sub-Option
                          </button>
                        </form>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Info Panel -->
                  <div class="col-md-6">
                    <div class="card">
                      <div class="card-header">
                        <h5>Structure Info</h5>
                      </div>
                      <div class="card-body">
                        <dl class="row">
                          <dt class="col-sm-4">Level 1:</dt>
                          <dd class="col-sm-8">{{ $attributeOption->attribute->name }} ({{ $attributeOption->attribute->code }})</dd>
                          
                          <dt class="col-sm-4">Level 2:</dt>
                          <dd class="col-sm-8">{{ $attributeOption->name }}</dd>
                          
                          <dt class="col-sm-4">Level 3:</dt>
                          <dd class="col-sm-8">
                            <span class="badge badge-info">
                              {{ $subAttributeOptions->count() }} sub-options
                            </span>
                          </dd>
                        </dl>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="table-responsive">
                    <table id="data-table" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Sub-Option Name</th>
                        <th>Full Path</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @forelse($subAttributeOptions as $subOption)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                  <strong>{{ $subOption->name }}</strong>
                                  <br><small class="text-muted">Level 3 Sub-Option</small>
                                </td>
                                <td>
                                  <span class="text-muted">
                                    {{ $attributeOption->attribute->name }} 
                                    <i class="fa fa-angle-right"></i> 
                                    {{ $attributeOption->name }} 
                                    <i class="fa fa-angle-right"></i> 
                                    <strong>{{ $subOption->name }}</strong>
                                  </span>
                                </td>
                                <td>
                                <div class="btn-group btn-group-sm">
                                    <form onclick="return confirm('Are you sure you want to delete this sub-option?')" 
                                          action="{{ route('attributes.sub-options.destroy', $subOption) }}" method="POST">
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
                                <td colspan="4" class="text-center">
                                  No sub-options found for "{{ $attributeOption->name }}". Add some sub-options above.
                                </td>
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
