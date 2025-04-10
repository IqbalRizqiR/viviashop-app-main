@extends('layouts.app')

@section('content')

    <!-- Main content -->
    <section class="content pt-4">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Edit Pengeluaran</h3>
                <a href="{{ route('admin.pengeluaran.index')}}" class="btn btn-success shadow-sm float-right"> <i class="fa fa-arrow-left"></i> Kembali</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form method="post" action="{{ route('admin.pengeluaran.update', $pengeluaran) }}">
                    @csrf
                    @method('put')
                    <div class="form-group row border-bottom pb-4">
                        <label for="name" class="col-sm-2 col-form-label">Deskripsi Pengeluaran</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="deskripsi" value="{{ old('deskripsi', $category->deskripsi) }}" id="deskripsi">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="name" class="col-sm-2 col-form-label">Nominal Pengeluaran</label>
                        <div class="col-sm-10">
                          <input type="number" class="form-control" name="nominal" value="{{ old('nominal', $category->nominal) }}" id="nominal">
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
