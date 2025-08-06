@extends('layouts.app')

@section('content')
<section class="content pt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Edit Attribute Variant untuk {{ $attribute->name }}</h3>
                        <a href="{{ route('admin.attributes.edit', $attribute) }}" class="btn btn-success shadow-sm float-right">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('admin.attributes.attribute_variants.update', [$attribute, $attribute_variant]) }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Nama Variant</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           name="name" value="{{ old('name', $attribute_variant->name) }}" id="name" 
                                           placeholder="Contoh: Vinyl, Berwarna, dll">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
