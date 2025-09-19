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
                            <select class="form-control product-type" name="type" id="type">
                                @foreach($types as $value => $type)
                                    <option {{ old('type', $product->type) == $value ? 'selected' : '' }} value="{{ $value }}">{{ $type }}</option>
                                @endforeach
                            </select>
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
                    <!-- New Multi-Variant System -->
                    @if($product->type === 'configurable')
                        <div class="product-variants-section">
                            <div class="row">
                                <div class="col-12">
                                    <p class="text-primary mt-4">Product Variants Management</p>
                                    <hr/>
                                    
                                    @if(isset($productVariants) && $productVariants->count() > 0)
                                        <div class="existing-variants">
                                            <h5>Existing Variants ({{ $product->productVariants->count() }} total, showing {{ $productVariants->count() }} per page)</h5>
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>SKU</th>
                                                            <th>Name</th>
                                                            <th>Attributes</th>
                                                            <th>Price</th>
                                                            <th>Harga Beli</th>
                                                            <th>Stock</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($productVariants as $variant)
                                                            <tr>
                                                                <td><small>{{ $variant->sku }}</small></td>
                                                                <td>{{ $variant->name }}</td>
                                                                <td>
                                                                    @foreach($variant->variantAttributes as $attr)
                                                                        <span class="badge badge-secondary">{{ $attr->attribute_name }}: {{ $attr->attribute_value }}</span>
                                                                    @endforeach
                                                                </td>
                                                                <td>Rp {{ number_format($variant->price, 0, ',', '.') }}</td>
                                                                <td>Rp {{ number_format($variant->harga_beli ?? 0, 0, ',', '.') }}</td>
                                                                <td>{{ $variant->stock }}</td>
                                                                <td>
                                                                    <span class="badge badge-{{ $variant->is_active ? 'success' : 'secondary' }}">
                                                                        {{ $variant->is_active ? 'Active' : 'Inactive' }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <button type="button" class="btn btn-sm btn-primary edit-variant" data-variant-id="{{ $variant->id }}">
                                                                        <i class="fa fa-edit"></i>
                                                                    </button>
                                                                    <button type="button" class="btn btn-sm btn-danger delete-variant" data-variant-id="{{ $variant->id }}" data-variant-name="{{ $variant->name }}">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            
                                            @if($productVariants->hasPages())
                                                <div class="d-flex justify-content-center mt-3">
                                                    {{ $productVariants->appends(request()->query())->links() }}
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="mt-3">
                                            <button type="button" class="btn btn-success" id="addNewVariant">
                                                <i class="fa fa-plus"></i> Add New Variant
                                            </button>
                                        </div>
                                    @else
                                        <div class="no-variants">
                                            <p class="text-muted">No variants created yet for this configurable product.</p>
                                            <button type="button" class="btn btn-success" id="addFirstVariant">
                                                <i class="fa fa-plus"></i> Create First Variant
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($product)
                        @if ($product->type == 'configurable')
                            <!-- CRUD Produk Induk untuk Configurable Product -->
                            <div class="form-group row border-bottom pb-4">
                                <div class="col-12">
                                    <p class="text-primary mt-4">Data Produk Induk</p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="parent_price">Harga Dasar Produk Induk</label>
                                                <input type="number" step="0.01" class="form-control" name="price" id="parent_price" 
                                                       value="{{ old('price', $product->price) }}" placeholder="Harga dasar produk">
                                                <small class="text-muted">Harga dasar untuk produk induk (opsional)</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="parent_harga_beli">Harga Beli Produk Induk</label>
                                                <input type="number" step="0.01" class="form-control" name="harga_beli" id="parent_harga_beli"
                                                       value="{{ old('harga_beli', $product->harga_beli) }}" placeholder="Harga beli produk">
                                                <small class="text-muted">Harga beli dasar untuk produk induk (opsional)</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="parent_weight">Berat (kg)</label>
                                                <input type="number" step="0.01" class="form-control" name="weight" id="parent_weight"
                                                       value="{{ old('weight', $product->weight) }}" placeholder="Berat">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="parent_length">Panjang (cm)</label>
                                                <input type="number" step="0.01" class="form-control" name="length" id="parent_length"
                                                       value="{{ old('length', $product->length) }}" placeholder="Panjang">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="parent_width">Lebar (cm)</label>
                                                <input type="number" step="0.01" class="form-control" name="width" id="parent_width"
                                                       value="{{ old('width', $product->width) }}" placeholder="Lebar">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="parent_height">Tinggi (cm)</label>
                                                <input type="number" step="0.01" class="form-control" name="height" id="parent_height"
                                                       value="{{ old('height', $product->height) }}" placeholder="Tinggi">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" class="form-check-input" name="is_print_service" value="1" id="is_print_service" {{ old('is_print_service', $product->is_print_service) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="is_print_service">
                                                        Aktifkan sebagai produk layanan cetak
                                                    </label>
                                                </div>
                                                <small class="form-text text-muted">Produk ini dapat digunakan untuk layanan cetak dokumen</small>
                                                
                                                <div class="form-check mt-2" id="smart_print_section_edit" style="display: {{ old('is_print_service', $product->is_print_service) ? 'block' : 'none' }};">
                                                    <input type="checkbox" class="form-check-input" name="is_smart_print_enabled" value="1" id="is_smart_print_enabled" {{ old('is_smart_print_enabled', $product->is_smart_print_enabled) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="is_smart_print_enabled">
                                                        Aktifkan Smart Print untuk produk ini
                                                    </label>
                                                    <small class="form-text text-muted d-block">Smart Print memungkinkan cetak otomatis tanpa operator</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif ($product->type == 'simple')
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
      
      // Function to load options for a variant and select the saved option
      function loadOptionsForVariant(variantSelect, preselectedOptionId = null) {
          const variantId = variantSelect.val();
          const attributeCode = variantSelect.data('attribute-code');
          const optionsSelect = variantSelect.closest('.col-sm-10').find('.select-options');
          
          if (variantId) {
              $.ajax({
                  url: `/api/attribute-options/0/${variantId}`,
                  method: 'GET',
                  success: function(data) {
                      optionsSelect.empty();
                      data.options.forEach(function(option) {
                          const isSelected = preselectedOptionId && option.id == preselectedOptionId;
                          const optionElement = new Option(option.name, option.id, isSelected, isSelected);
                          optionsSelect.append(optionElement);
                      });
                      optionsSelect.trigger('change');
                  }
              });
          } else {
              optionsSelect.empty();
              optionsSelect.append(new Option('Pilih jenis terlebih dahulu varian', ''));
          }
      }
      
      $('.select-variant').on('change', function() {
          loadOptionsForVariant($(this));
      });
      
      // Load options for pre-selected variants on page load
      $(document).ready(function() {
          const selectedAttributes = @json($selected_attributes ?? []);
          
          $('.select-variant').each(function() {
              const variantSelect = $(this);
              const attributeCode = variantSelect.data('attribute-code');
              
              if (selectedAttributes[attributeCode] && variantSelect.val()) {
                  const preselectedOptionId = selectedAttributes[attributeCode].option_id;
                  loadOptionsForVariant(variantSelect, preselectedOptionId);
              }
          });
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
		$(function(){
			showHideConfigurableAttributes();
			$(".product-type").change(function() {
				showHideConfigurableAttributes();
			});
			
			$('#addFirstVariant, #addNewVariant').on('click', function() {
				showVariantModal();
			});
			
			$('.edit-variant').on('click', function() {
				var variantId = $(this).data('variant-id');
				editVariant(variantId);
			});
			
			$('.delete-variant').on('click', function() {
				var variantId = $(this).data('variant-id');
				var variantName = $(this).data('variant-name');
				deleteVariant(variantId, variantName);
			});
		});
		
		function showVariantModal() {
			var modal = `
				<div class="modal fade show" id="variantModal" tabindex="-1" style="display: block; background-color: rgba(0,0,0,0.5);">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Add Product Variant</h5>
								<button type="button" class="close" onclick="closeVariantModal()">
									<span>&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form id="variantForm">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label>Variant Name</label>
												<input type="text" class="form-control" name="name" required>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label>SKU</label>
												<input type="text" class="form-control" name="sku" required>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label>Harga Jual</label>
												<input type="number" class="form-control" name="price" step="0.01" required>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>Harga Beli</label>
												<input type="number" class="form-control" name="harga_beli" step="0.01">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>Stock</label>
												<input type="number" class="form-control" name="stock" required>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label>Berat (kg)</label>
												<input type="number" step="0.01" class="form-control" name="weight">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label>Panjang (cm)</label>
												<input type="number" class="form-control" name="length">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label>Lebar (cm)</label>
												<input type="number" class="form-control" name="width">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label>Tinggi (cm)</label>
												<input type="number" class="form-control" name="height">
											</div>
										</div>
									</div>
									<hr style="margin: 20px 0;">
									<h6 style="color: #495057; margin-bottom: 15px; font-weight: 600;">Variant Attributes</h6>
									<div id="attributeContainer">
										<div class="attribute-row row mb-3" style="align-items: end;">
											<div class="col-md-5">
												<label style="font-size: 14px; color: #495057;">Attribute Name</label>
												<input type="text" class="form-control" name="attribute_names[]" placeholder="Attribute Name (e.g. Color, Size)" style="border: 1px solid #ced4da;">
											</div>
											<div class="col-md-5">
												<label style="font-size: 14px; color: #495057;">Attribute Value</label>
												<input type="text" class="form-control" name="attribute_values[]" placeholder="Attribute Value (e.g. Red, XL)" style="border: 1px solid #ced4da;">
											</div>
											<div class="col-md-2">
												<button type="button" class="btn btn-sm btn-danger remove-attribute" style="margin-top: 5px;">Remove</button>
											</div>
										</div>
									</div>
									<button type="button" class="btn btn-sm btn-secondary" id="addAttribute" style="margin-bottom: 15px;">Add Attribute</button>
								</form>
							</div>
							<div class="modal-footer" style="background-color: #f8f9fa; border-top: 1px solid #dee2e6; padding: 15px 20px;">
								<button type="button" class="btn btn-secondary" onclick="closeVariantModal()" style="margin-right: 10px;">Cancel</button>
								<button type="button" class="btn btn-primary" id="saveVariant">Save Variant</button>
							</div>
						</div>
					</div>
				</div>
			`;
			
			$('body').append(modal);
			document.body.classList.add('modal-open');
			
			$('#addAttribute').on('click', function() {
				var newRow = `
					<div class="attribute-row row mb-3" style="align-items: end;">
						<div class="col-md-5">
							<label style="font-size: 14px; color: #495057;">Attribute Name</label>
							<input type="text" class="form-control" name="attribute_names[]" placeholder="Attribute Name" style="border: 1px solid #ced4da;">
						</div>
						<div class="col-md-5">
							<label style="font-size: 14px; color: #495057;">Attribute Value</label>
							<input type="text" class="form-control" name="attribute_values[]" placeholder="Attribute Value" style="border: 1px solid #ced4da;">
						</div>
						<div class="col-md-2">
							<button type="button" class="btn btn-sm btn-danger remove-attribute" style="margin-top: 5px;">Remove</button>
						</div>
					</div>
				`;
				$('#attributeContainer').append(newRow);
			});
			
			$(document).on('click', '.remove-attribute', function() {
				$(this).closest('.attribute-row').remove();
			});
			
			$('#saveVariant').on('click', function() {
				saveVariant();
			});
		}
		
		function closeVariantModal() {
			$('#variantModal').remove();
			document.body.classList.remove('modal-open');
		}
		
		function saveVariant() {
			var formData = {
				product_id: {{ $product->id }},
				name: $('#variantModal input[name="name"]').val(),
				sku: $('#variantModal input[name="sku"]').val(),
				price: $('#variantModal input[name="price"]').val(),
				harga_beli: $('#variantModal input[name="harga_beli"]').val(),
				stock: $('#variantModal input[name="stock"]').val(),
				weight: $('#variantModal input[name="weight"]').val() || {{ $product->weight ?? 0 }},
				length: $('#variantModal input[name="length"]').val() || {{ $product->length ?? 0 }},
				width: $('#variantModal input[name="width"]').val() || {{ $product->width ?? 0 }},
				height: $('#variantModal input[name="height"]').val() || {{ $product->height ?? 0 }},
				attributes: []
			};
			
			$('#variantModal .attribute-row').each(function() {
				var name = $(this).find('input[name="attribute_names[]"]').val();
				var value = $(this).find('input[name="attribute_values[]"]').val();
				if (name && value) {
					formData.attributes.push({
						attribute_name: name,
						attribute_value: value
					});
				}
			});
			
			if (formData.attributes.length === 0) {
				alert('Please add at least one attribute for the variant');
				return;
			}
			
			$.ajax({
				url: '/admin/variants/create',
				method: 'POST',
				data: formData,
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				success: function(response) {
					closeVariantModal();
					var currentUrl = new URL(window.location.href);
					var variantPage = currentUrl.searchParams.get('variant_page') || 1;
					window.location.href = currentUrl.pathname + '?variant_page=' + variantPage;
				},
				error: function(xhr) {
					var message = 'Error creating variant';
					if (xhr.responseJSON && xhr.responseJSON.message) {
						message = xhr.responseJSON.message;
					}
					alert(message);
				}
			});
		}
		
		function editVariant(variantId) {
			$.get('/admin/variants/' + variantId, function(response) {
				var variant = response.variant;
				
				var modal = `
					<div class="modal fade show" id="editVariantModal" tabindex="-1" style="display: block; background-color: rgba(0,0,0,0.5);">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title">Edit Product Variant</h5>
									<button type="button" class="close" onclick="closeEditVariantModal()">
										<span>&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<form id="editVariantForm">
										<input type="hidden" id="edit_variant_id" value="${variant.id}">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>Variant Name</label>
													<input type="text" class="form-control" name="name" value="${variant.name}" required>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>SKU</label>
													<input type="text" class="form-control" name="sku" value="${variant.sku}" required>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label>Harga Jual</label>
													<input type="number" class="form-control" name="price" value="${variant.price}" step="0.01" required>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label>Harga Beli</label>
													<input type="number" class="form-control" name="harga_beli" value="${variant.harga_beli || ''}" step="0.01">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label>Stock</label>
													<input type="number" class="form-control" name="stock" value="${variant.stock}" required>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-3">
												<div class="form-group">
													<label>Berat (kg)</label>
													<input type="number" step="0.01" class="form-control" name="weight" value="${variant.weight || ''}">
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label>Panjang (cm)</label>
													<input type="number" class="form-control" name="length" value="${variant.length || ''}">
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label>Lebar (cm)</label>
													<input type="number" class="form-control" name="width" value="${variant.width || ''}">
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label>Tinggi (cm)</label>
													<input type="number" class="form-control" name="height" value="${variant.height || ''}">
												</div>
											</div>
										</div>
										<h6>Variant Attributes</h6>
										<div id="editAttributeContainer">
								`;
								
				if (variant.variant_attributes && variant.variant_attributes.length > 0) {
					variant.variant_attributes.forEach(function(attr, index) {
						modal += `
							<div class="attribute-row row mb-2">
								<div class="col-md-5">
									<input type="text" class="form-control" name="attribute_names[]" value="${attr.attribute_name}" placeholder="Attribute Name">
								</div>
								<div class="col-md-5">
									<input type="text" class="form-control" name="attribute_values[]" value="${attr.attribute_value}" placeholder="Attribute Value">
								</div>
								<div class="col-md-2">
									<button type="button" class="btn btn-sm btn-danger remove-attribute">Remove</button>
								</div>
							</div>
						`;
					});
				} else {
					modal += `
						<div class="attribute-row row mb-2">
							<div class="col-md-5">
								<input type="text" class="form-control" name="attribute_names[]" placeholder="Attribute Name">
							</div>
							<div class="col-md-5">
								<input type="text" class="form-control" name="attribute_values[]" placeholder="Attribute Value">
							</div>
							<div class="col-md-2">
								<button type="button" class="btn btn-sm btn-danger remove-attribute">Remove</button>
							</div>
						</div>
					`;
				}
				
				modal += `
										</div>
										<button type="button" class="btn btn-sm btn-secondary" id="addEditAttribute">Add Attribute</button>
									</form>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" onclick="closeEditVariantModal()">Cancel</button>
									<button type="button" class="btn btn-primary" id="updateVariant">Update Variant</button>
								</div>
							</div>
						</div>
					</div>
				`;
				
				$('body').append(modal);
				document.body.classList.add('modal-open');
				
				$('#addEditAttribute').on('click', function() {
					var newRow = `
						<div class="attribute-row row mb-2">
							<div class="col-md-5">
								<input type="text" class="form-control" name="attribute_names[]" placeholder="Attribute Name">
							</div>
							<div class="col-md-5">
								<input type="text" class="form-control" name="attribute_values[]" placeholder="Attribute Value">
							</div>
							<div class="col-md-2">
								<button type="button" class="btn btn-sm btn-danger remove-attribute">Remove</button>
							</div>
						</div>
					`;
					$('#editAttributeContainer').append(newRow);
				});
				
				$(document).on('click', '.remove-attribute', function() {
					$(this).closest('.attribute-row').remove();
				});
				
				$('#updateVariant').on('click', function() {
					updateVariant();
				});
			}).fail(function() {
				alert('Failed to load variant data');
			});
		}
		
		function closeEditVariantModal() {
			$('#editVariantModal').remove();
			document.body.classList.remove('modal-open');
		}
		
		function updateVariant() {
			var variantId = $('#edit_variant_id').val();
			var formData = {
				name: $('#editVariantModal input[name="name"]').val(),
				sku: $('#editVariantModal input[name="sku"]').val(),
				price: $('#editVariantModal input[name="price"]').val(),
				harga_beli: $('#editVariantModal input[name="harga_beli"]').val(),
				stock: $('#editVariantModal input[name="stock"]').val(),
				weight: $('#editVariantModal input[name="weight"]').val() || {{ $product->weight ?? 0 }},
				length: $('#editVariantModal input[name="length"]').val() || {{ $product->length ?? 0 }},
				width: $('#editVariantModal input[name="width"]').val() || {{ $product->width ?? 0 }},
				height: $('#editVariantModal input[name="height"]').val() || {{ $product->height ?? 0 }},
				attributes: []
			};
			
			$('#editVariantModal .attribute-row').each(function() {
				var name = $(this).find('input[name="attribute_names[]"]').val();
				var value = $(this).find('input[name="attribute_values[]"]').val();
				if (name && value) {
					formData.attributes.push({
						attribute_name: name,
						attribute_value: value
					});
				}
			});
			
			if (formData.attributes.length === 0) {
				alert('Please add at least one attribute for the variant');
				return;
			}
			
			$.ajax({
				url: '/admin/variants/' + variantId,
				method: 'PUT',
				data: formData,
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				success: function(response) {
					closeEditVariantModal();
					var currentUrl = new URL(window.location.href);
					var variantPage = currentUrl.searchParams.get('variant_page') || 1;
					window.location.href = currentUrl.pathname + '?variant_page=' + variantPage;
				},
				error: function(xhr) {
					var message = 'Error updating variant';
					if (xhr.responseJSON && xhr.responseJSON.message) {
						message = xhr.responseJSON.message;
					}
					alert(message);
				}
			});
		}
		
		function deleteVariant(variantId, variantName) {
			if (confirm('Are you sure you want to delete variant "' + variantName + '"? This action cannot be undone.')) {
				$.ajax({
					url: '/admin/variants/' + variantId,
					method: 'DELETE',
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					success: function(response) {
						alert('Variant deleted successfully');
						location.reload();
					},
					error: function(xhr) {
						var message = 'Error deleting variant';
						if (xhr.responseJSON && xhr.responseJSON.message) {
							message = xhr.responseJSON.message;
						}
						alert(message);
					}
				});
			}
		}
		
		function handlePrintServiceLogicEdit() {
		    var printServiceChecked = $("#is_print_service").is(':checked');
		    var smartPrintSection = $("#smart_print_section_edit");
		    var smartPrintCheckbox = $("#is_smart_print_enabled");
		    
		    if (printServiceChecked) {
		        smartPrintSection.show();
		    } else {
		        smartPrintSection.hide();
		        smartPrintCheckbox.prop('checked', false);
		    }
		}
		
		$(document).ready(function() {
		    // Initialize print service logic
		    handlePrintServiceLogicEdit();
		    
		    // Handle print service checkbox change
		    $("#is_print_service").change(function() {
		        handlePrintServiceLogicEdit();
		    });
		});
</script>
@endpush
