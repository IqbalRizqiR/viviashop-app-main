@extends('layouts.app')

@section('content')

    <!-- Main content -->
    <section class="content pt-4">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Buat Produk</h3>
                <a href="{{ route('admin.products.index')}}" class="btn btn-success shadow-sm float-right"> <i class="fa fa-arrow-left"></i> Kembali</a>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form method="post" action="{{ route('admin.products.store') }}">
                    @csrf
                    <div class="form-group row border-bottom pb-4">
                        <label for="type" class="col-sm-2 col-form-label">Tipe Kategori</label>
                        <div class="col-sm-10">
                            <select class="form-control product-type" name="type" id="type">
                                @foreach($types as $value => $type)
                                    <option {{ old('type') == $value ? 'selected' : '' }} value="{{ $value }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="sku" class="col-sm-2 col-form-label">SKU</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" placeholder="Dicetak di produk, tercantum di kemasan, dan terbaca oleh POS" name="sku" value="{{ old('sku') }}" id="sku">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="name" class="col-sm-2 col-form-label">Nama Produk</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="name" value="{{ old('name') }}" id="name">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="category_id" class="col-sm-2 col-form-label">Kategori Produk</label>
                        <div class="col-sm-10">
                        <!-- sampai sini -->
                          <select class="form-control select-multiple"  multiple="multiple" name="category_id[]" id="category_id">
                            @foreach($categories as $category)
                              <option {{ old('category_id') == $category->id ? 'selected' : null }} value="{{ $category->id }}"> {{ $category->name }}</option>
                            @endforeach
                          </select>
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="weight" class="col-sm-2 col-form-label">Berat (kg)</label>
                        <div class="col-sm-10">
                          <input type="number" step="0.01" class="form-control" name="weight" value="{{ old('weight') }}" id="weight" placeholder="Contoh: 1.5 (untuk 1.5 kg)">
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label class="col-sm-2 col-form-label">Layanan Cetak</label>
                        <div class="col-sm-10">
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" name="is_print_service" value="1" id="is_print_service" {{ old('is_print_service') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_print_service">
                                    Aktifkan sebagai produk layanan cetak
                                </label>
                            </div>
                            <small class="form-text text-muted">Produk ini dapat digunakan untuk layanan cetak dokumen</small>
                            
                            <div class="form-check mt-2" id="smart_print_section" style="display: none;">
                                <input type="checkbox" class="form-check-input" name="is_smart_print_enabled" value="1" id="is_smart_print_enabled" {{ old('is_smart_print_enabled') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_smart_print_enabled">
                                    Aktifkan Smart Print untuk produk ini
                                </label>
                                <small class="form-text text-muted d-block">Smart Print memungkinkan cetak otomatis tanpa operator</small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="short_description" class="col-sm-2 col-form-label">Deskripsi Singkat</label>
                        <div class="col-sm-10">
                          <textarea class="form-control" name="short_description" id="short_description" cols="30" rows="5" placeholder="Masukkan deskripsi singkat produk...">{{ old('short_description') }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row border-bottom pb-4">
                        <label for="description" class="col-sm-2 col-form-label">Deskripsi Produk</label>
                        <div class="col-sm-10">
                          <textarea class="form-control editor" name="description" id="description" cols="30" rows="5" placeholder="Masukkan deskripsi detail produk...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="configurable-attributes">
                      @if(count($configurable_attributes) > 0)
                        <p class="text-primary mt-4">Konfigurasi Attribute Produk</p>
                        <hr/>
                        @foreach($configurable_attributes as $configurable_attribute)
                          <div class="form-group row border-bottom pb-4">
                              <label for="{{ $configurable_attribute->code }}" class="col-sm-2 col-form-label">{{ $configurable_attribute->name }}</label>
                              <div class="col-sm-10">
                                <div class="row">
                                  <div class="col-md-6">
                                    <label class="form-label">Pilih Varian:</label>
                                    <select class="form-control select-variant" data-attribute-code="{{ $configurable_attribute->code }}">
                                      <option value="">Pilih Varian</option>
                                      @foreach($configurable_attribute->attribute_variants as $variant)
                                        <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                                      @endforeach
                                    </select>
                                  </div>
                                  <div class="col-md-6">
                                    <label class="form-label">Pilih Jenis:</label>
                                    <select class="form-control select-multiple select-options" multiple="multiple" name="{{ $configurable_attribute->code }}[]" data-attribute-code="{{ $configurable_attribute->code }}">
                                      <option value="">Pilih jenis terlebih dahulu varian</option>
                                    </select>
                                  </div>
                                </div>
                              </div>
                          </div>
                        @endforeach
                      @endif
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
                licenseKey: 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3Nzk0OTQzOTksImp0aSI6IjkwZThjMjcwLWY4MTUtNGJiMi1iOGFjLTU1MWQ5MzU2NjcyMSIsInVzYWdlRW5kcG9pbnQiOiJodHRwczovL3Byb3h5LWV2ZW50LmNrZWRpdG9yLmNvbSIsImRpc3RyaWJ1dGlvbkNoYW5uZWwiOlsiY2xvdWQiLCJkcnVwYWwiXSwiZmVhdHVyZXMiOlsiRFJVUCIsIkUyUCIsIkUyVyJdLCJ2YyI6ImQzODM5YWE5In0.-I-GMQkIpP1-tYVtdfTV8o-xdp1rYGe6ew4yUOEX3K-hfC_ZLi8CPTcvwWiQQ-HNBnZRhQYv_UVzsbGQUZJkhA',
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
      
      $('.select-variant').on('change', function() {
          const variantId = $(this).val();
          const attributeCode = $(this).data('attribute-code');
          const optionsSelect = $(this).closest('.col-sm-10').find('.select-options');
          
          if (variantId) {
              $.ajax({
                  url: `/api/attribute-options/0/${variantId}`,
                  method: 'GET',
                  success: function(data) {
                      optionsSelect.empty();
                      data.options.forEach(function(option) {
                          optionsSelect.append(new Option(option.name, option.id));
                      });
                      optionsSelect.trigger('change');
                  }
              });
          } else {
              optionsSelect.empty();
              optionsSelect.append(new Option('Pilih jenis terlebih dahulu varian', ''));
          }
      });
      
      function showHideConfigurableAttributes() {
			var productType = $(".product-type").val();
            console.log(productType);

			if (productType == 'configurable') {
				$(".configurable-attributes").show();
			} else {
				$(".configurable-attributes").hide();
			}
		}
		
		function handlePrintServiceLogic() {
		    var printServiceChecked = $("#is_print_service").is(':checked');
		    var smartPrintSection = $("#smart_print_section");
		    var smartPrintCheckbox = $("#is_smart_print_enabled");
		    
		    if (printServiceChecked) {
		        smartPrintSection.show();
		    } else {
		        smartPrintSection.hide();
		        smartPrintCheckbox.prop('checked', false);
		    }
		}
		
		$(function(){
			showHideConfigurableAttributes();
			$(".product-type").change(function() {
				showHideConfigurableAttributes();
			});
			
			// Initialize print service logic
			handlePrintServiceLogic();
			
			// Handle print service checkbox change
			$("#is_print_service").change(function() {
			    handlePrintServiceLogic();
			});
		});
</script>
@endpush
