@extends('layouts.app')

@section('content')

    <!-- Main content -->
    <section class="content pt-4">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Edit Settings Toko</h3>
                <a href="{{ route('admin.dashboard')}}" class="btn btn-success shadow-sm float-right"> <i class="fa fa-arrow-left"></i> Kembali</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
        @if(isset($setting) && $setting)
        <form method="post" enctype="multipart/form-data" action="{{ route('admin.setting.update', optional($setting)->id) }}">
          @csrf
          @method('put')
                    <div class="form-group row border-bottom pb-4">
                        <label for="nama_toko" class="col-sm-2 col-form-label">Nama Toko</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="nama_toko" value="{{ old('nama_toko', optional($setting)->nama_toko) }}" id="sku">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="alamat" class="col-sm-2 col-form-label">Alamat Toko</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="alamat" value="{{ old('alamat', optional($setting)->alamat) }}" id="name">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="email" class="col-sm-2 col-form-label">Email Toko</label>
                        <div class="col-sm-10">
                          <input type="email" class="form-control" name="email" value="{{ old('email', optional($setting)->email) }}" id="email">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="instagram" class="col-sm-2 col-form-label">Instagram</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" placeholder="Optional" name="instagram" value="{{ old('instagram', optional($setting)->instagram) }}" id="instagram">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="facebook" class="col-sm-2 col-form-label">Facebook</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" placeholder="Optional" name="facebook" value="{{ old('facebook', optional($setting)->facebook) }}" id="facebook">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="twitter" class="col-sm-2 col-form-label">Twitter</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" placeholder="Optional" name="twitter" value="{{ old('twitter', optional($setting)->twitter) }}" id="twitter">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="youtube" class="col-sm-2 col-form-label">Youtube</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" placeholder="Optional" name="youtube" value="{{ old('youtube', optional($setting)->youtube) }}" id="youtube">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="maps_url" class="col-sm-2 col-form-label">Maps URL</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="maps_url" value="{{ old('maps_url', optional($setting)->maps_url) }}" id="maps_url">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="telepon" class="col-sm-2 col-form-label">Telepon Toko</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="telepon" value="{{ old('telepon', optional($setting)->telepon) }}" id="name">
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
        @else
          <div class="alert alert-warning">No settings found. Please create a setting record first.</div>
        @endif
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
