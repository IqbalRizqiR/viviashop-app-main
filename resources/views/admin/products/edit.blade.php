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
                <a href="{{ route('admin.products.index')}}" class="btn btn-success shadow-sm float-right"> <i class="fa fa-arrow-left"></i> Kembali</a>
                <a href="{{ route('admin.products.product_images.index', $product)}}" class="btn btn-success shadow-sm mr-2 float-right"> <i class="fa fa-image"></i> Upload Gambar Produk</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form method="post" action="{{ route('admin.products.update', $product) }}">
                    @csrf
                    @method('put')
                    <div class="form-group row border-bottom pb-4">
                        <label for="type" class="col-sm-2 col-form-label">Tipe Kategori</label>
                        <div class="col-sm-10">
                            <input type="text" name="type" readonly value="simple" placeholder="Simple" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="sku" class="col-sm-2 col-form-label">SKU</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="sku" value="{{ old('sku', $product->sku) }}" id="sku">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="name" class="col-sm-2 col-form-label">Nama Produk</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="name" value="{{ old('name', $product->name) }}" id="name">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="category_id" class="col-sm-2 col-form-label">Kategori Produk</label>
                        <div class="col-sm-10">
                        <!-- sampai sini -->
                          <select class="form-control select-multiple"  multiple="multiple" name="category_id[]" id="category_id">
                            @foreach($categories as $category)
                              <option {{ in_array($category->id, $product->categories->pluck('id')->toArray()) ? 'selected' : null }} value="{{ $category->id }}"> {{ $category->name }}</option>
                            @endforeach
                          </select>
                        </div>
                    </div>
                    @if(!empty($configurable_attributes) && empty($product))
                      <p class="text-primary mt-4">Konfigurasi Attribute Produk</p>
                      <hr/>
                      @foreach($configurable_attributes as $configurable_attribute)
                        <div class="form-group row border-bottom pb-4">
                            <label for="{{ $configurable_attribute->code }}" class="col-sm-2 col-form-label">{{ $configurable_attribute->code }}</label>
                            <div class="col-sm-10">
                            <!-- sampai sini -->
                              <select class="form-control select-multiple"  multiple="multiple" name="{{ $configurable_attribute->code }}[]" id="{{ $configurable_attribute->code }}">
                                @foreach($configurable_attribute->attribute_options as $attribute_option)
                                  <option value="{{ $attribute_option->id }}"> {{ $attribute_option->name }}</option>
                                @endforeach
                              </select>
                            </div>
                        </div>
                      @endforeach
                    @endif
                    @if ($product)
                        @if ($product->type == 'configurable')
                            @include('admin.products.configurable')
                        @else
                            @include('admin.products.simple')
                        @endif

                        <div class="form-group row border-bottom pb-4">
                            <label for="short_description" class="col-sm-2 col-form-label">Deskripsi Singkat</label>
                            <div class="col-sm-10">
                              <textarea class="form-control" name="short_description" id="short_description" cols="30" rows="5">{{ old('short_description', $product->short_description) }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row border-bottom pb-4">
                            <label for="description" class="col-sm-2 col-form-label">Deskripsi Produk</label>
                            <div class="col-sm-10">
                              <textarea class="form-control editor" name="description" id="description" cols="30" rows="5">{{ old('description', $product->description) }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row border-bottom pb-4">
                            <label for="status" class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-10">
                              <select class="form-control" name="status" id="status">
                                @foreach($statuses as $value => $status)
                                  <option {{ old('status', $product->status) == $value ? 'selected' : null }} value="{{ $value }}"> {{ $status }}</option>
                                @endforeach
                              </select>
                            </div>
                        </div>
                    @endif
                    <div class="form-group row border-bottom pb-4">
                        <label for="name" class="col-sm-2 col-form-label">Link redirect Product</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="link1" value="{{ $product->link1 }}" id="link1">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="name" class="col-sm-2 col-form-label">Link redirect Product</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="link2" value="{{ $product->link2 }}" id="link2">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="name" class="col-sm-2 col-form-label">Link redirect Product</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="link3" value="{{ $product->link3 }}" id="link3">
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
    <script>
        const {
            ClassicEditor,
            Autoformat,
            Font,
            Bold,
            Italic,
            Underline,
            BlockQuote,
            Base64UploadAdapter,
            CloudServices,
            Essentials,
            Heading,
            Image,
            ImageCaption,
            ImageResize,
            ImageStyle,
            ImageToolbar,
            ImageUpload,
            PictureEditing,
            Indent,
            IndentBlock,
            Link,
            List,
            MediaEmbed,
            Mention,
            Paragraph,
            PasteFromOffice,
            Table,
            TableColumnResize,
            TableToolbar,
            TextTransformation
        } = CKEDITOR;
        const div = document.querySelector('.editor');
        ClassicEditor.create(div, {
                licenseKey: 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3Nzg0NTc1OTksImp0aSI6ImUzOTNmNGFjLTU5NmUtNDZiMy05NzljLWQ4OTY3MWI1ZmZiZSIsImxpY2Vuc2VkSG9zdHMiOlsidml2aWFzaG9wLmNvbSJdLCJ1c2FnZUVuZHBvaW50IjoiaHR0cHM6Ly9wcm94eS1ldmVudC5ja2VkaXRvci5jb20iLCJkaXN0cmlidXRpb25DaGFubmVsIjpbImNsb3VkIiwiZHJ1cGFsIl0sImZlYXR1cmVzIjpbIkRSVVAiXSwidmMiOiIxNWNhNGM1NiJ9.kclrvOcI4ObdmuuKxhY74CEN8pxYmxFBP_ucNNwfLpamfmoVtuNGiXdNbO8NyEnGYmCNvfFUye2QH3KZ19seCA',
                plugins: [ Autoformat,
                            BlockQuote,
                            Bold,
                            CloudServices,
                            Essentials,
                            Heading,
                            Image,
                            ImageCaption,
                            ImageResize,
                            ImageStyle,
                            ImageToolbar,
                            ImageUpload,
                            Base64UploadAdapter,
                            Indent,
                            IndentBlock,
                            Italic,
                            Link,
                            List,
                            MediaEmbed,
                            Mention,
                            Paragraph,
                            PasteFromOffice,
                            PictureEditing,
                            Table,
                            TableColumnResize,
                            TableToolbar,
                            TextTransformation,
                            Underline,
                            Font, Essentials ],
                toolbar: [
                    'undo',
                    'redo',
                    '|',
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'underline',
                    '|',
                    'link',
                    'insertTable',
                    'blockQuote',
                    '|',
                    'bulletedList',
                    'numberedList',
                    '|',
                    'outdent',
                    'indent'
                ]
            } )
            .then( /* ... */ )
            .catch( /* ... */ );
    </script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
      $('.select-multiple').select2();
</script>
@endpush
