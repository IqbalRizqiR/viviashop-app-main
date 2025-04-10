@extends('layouts.app')

@section('content')

    <!-- Main content -->
    <section class="content pt-4">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Edit Produk</h3>
                <a href="{{ route('admin.dashboard')}}" class="btn btn-success shadow-sm float-right"> <i class="fa fa-arrow-left"></i> Kembali</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form method="post" enctype="multipart/form-data" action="{{ route('admin.setting.update', $setting->id) }}">
                    @csrf
                    @method('put')
                    <div class="form-group row border-bottom pb-4">
                        <label for="nama_toko" class="col-sm-2 col-form-label">Nama Toko</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="nama_toko" value="{{ old('nama_toko', $setting->nama_toko) }}" id="sku">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="alamat" class="col-sm-2 col-form-label">Alamat Toko</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="alamat" value="{{ old('alamat', $setting->alamat) }}" id="name">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="telepon" class="col-sm-2 col-form-label">Telepon Toko</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="telepon" value="{{ old('telepon', $setting->telepon) }}" id="name">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="logo" class="col-sm-2 col-form-label">Logo Toko</label>
                        <div class="col-sm-10">
                          <input class="form-control" type="file" name="path_logo" id="logo">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Save</button>
                </form>
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

@push('style-alt')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('script-alt')
<script
        src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU="
        crossorigin="anonymous"
    >
    </script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
      $('.select-multiple').select2();
</script>
@endpush
