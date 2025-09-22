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
                                                                <td>Rp {{ number_format( ((float) ($variant->price ?? 0) ) > 0 ? $variant->price : ($product->price ?? 0), 0, ',', '.') }}</td>
                                                                <td>Rp {{ number_format( ((float) ($variant->harga_beli ?? 0) ) > 0 ? $variant->harga_beli : ($product->harga_beli ?? 0), 0, ',', '.') }}</td>
                                                                <td>{{ $variant->stock ?? ($product->productInventory ? $product->productInventory->qty : 0) }}</td>
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
                                            <button type="button" class="btn btn-success" id="addNewVariant" onclick="showProperVariantModal();">
                                                <i class="fa fa-plus"></i> Add New Variant
                                            </button>
                                        </div>
                                    @else
                                        <div class="no-variants">
                                            <p class="text-muted">No variants created yet for this configurable product.</p>
                                            <button type="button" class="btn btn-success" id="addFirstVariant" onclick="showProperVariantModal();">
                                                <i class="fa fa-plus"></i> Create First Variant
                                            </button>
                                            
                                            <script>
                                            function showProperVariantModal() {
                                                // Get product info (from Blade)
                                                var isSmartPrint = {{ $product->is_smart_print_enabled && $product->is_print_service ? 'true' : 'false' }};
                                                var modalTitle = isSmartPrint ? 'Add Smart Print Variant' : 'Add Product Variant';
                                                var productId = {{ $product->id }};
                                                
                                                // Create modal overlay
                                                var overlay = document.createElement('div');
                                                overlay.id = 'variantModal';
                                                overlay.style.cssText = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 99999; display: flex; align-items: center; justify-content: center; overflow-y: auto;';
                                                
                                                // Create modal content
                                                var modalHtml = '<div style="background: white; border-radius: 8px; width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">';
                                                
                                                // Header
                                                var headerStyle = isSmartPrint ? 'background: linear-gradient(135deg, #f8f9ff 0%, #e3f2fd 100%); border-bottom: 2px solid #007bff;' : 'background: #f8f9fa; border-bottom: 1px solid #dee2e6;';
                                                var titleStyle = isSmartPrint ? 'color: #007bff; font-weight: 600;' : 'color: #495057;';
                                                
                                                modalHtml += '<div style="padding: 20px; ' + headerStyle + '">';
                                                modalHtml += '<div style="display: flex; justify-content: space-between; align-items: center;">';
                                                modalHtml += '<h4 style="margin: 0; ' + titleStyle + '">' + modalTitle + '</h4>';
                                                modalHtml += '<button onclick="closeVariantModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6c757d;">&times;</button>';
                                                modalHtml += '</div>';
                                                modalHtml += '</div>';
                                                
                                                // Body
                                                modalHtml += '<div style="padding: 30px;">';
                                                modalHtml += '<form id="variantForm">';
                                                
                                                // Basic Info Row
                                                modalHtml += '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">';
                                                modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Variant Name *</label>';
                                                modalHtml += '<input type="text" name="name" required style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
                                                modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">SKU *</label>';
                                                modalHtml += '<input type="text" name="sku" required style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
                                                modalHtml += '</div>';
                                                
                                                // Price Row
                                                modalHtml += '<div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">';
                                                modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Harga Jual *</label>';
                                                modalHtml += '<input type="number" name="price" step="0.01" required style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
                                                modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Harga Beli</label>';
                                                modalHtml += '<input type="number" name="harga_beli" step="0.01" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
                                                modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Stock *</label>';
                                                modalHtml += '<input type="number" name="stock" required style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
                                                modalHtml += '</div>';
                                                
                                                // Dimensions Row
                                                modalHtml += '<div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">';
                                                modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Berat (kg)</label>';
                                                modalHtml += '<input type="number" name="weight" step="0.01" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
                                                modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Panjang (cm)</label>';
                                                modalHtml += '<input type="number" name="length" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
                                                modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Lebar (cm)</label>';
                                                modalHtml += '<input type="number" name="width" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
                                                modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Tinggi (cm)</label>';
                                                modalHtml += '<input type="number" name="height" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
                                                modalHtml += '</div>';
                                                
                                                // Divider
                                                modalHtml += '<hr style="margin: 30px 0; border: none; border-top: 2px solid #e9ecef;">';
                                                
                                                // Attributes Section
                                                modalHtml += '<h5 style="margin-bottom: 15px; color: #495057;">Variant Attributes</h5>';
                                                
                                                // Smart Print vs Regular Info
                                                if (isSmartPrint) {
                                                    modalHtml += '<div style="background: #e3f2fd; border: 1px solid #1976d2; border-radius: 4px; padding: 15px; margin-bottom: 20px;">';
                                                    modalHtml += '<div style="display: flex; align-items: center; color: #1976d2;"><i class="fas fa-info-circle" style="margin-right: 8px;"></i>';
                                                    modalHtml += '<strong>Smart Print Service:</strong> paper_size dan print_type fields sudah dioptimalkan untuk layanan print</div>';
                                                    modalHtml += '</div>';
                                                } else {
                                                    modalHtml += '<div style="background: #f8f9fa; border: 1px solid #6c757d; border-radius: 4px; padding: 15px; margin-bottom: 20px;">';
                                                    modalHtml += '<div style="display: flex; align-items: center; color: #6c757d;"><i class="fas fa-box" style="margin-right: 8px;"></i>';
                                                    modalHtml += '<strong>Regular Product:</strong> konfigurasi atribut fleksibel untuk produk umum</div>';
                                                    modalHtml += '</div>';
                                                }
                                                
                                                // Attributes Container
                                                modalHtml += '<div id="attributeContainer">';
                                                
                                                if (isSmartPrint) {
                                                    // Smart Print - readonly paper_size and print_type
                                                    modalHtml += '<div class="attribute-row" style="display: grid; grid-template-columns: 1fr 1fr 100px; gap: 10px; margin-bottom: 10px; align-items: end;">';
                                                    modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Attribute Name</label>';
                                                    modalHtml += '<input type="text" name="attribute_names[]" value="paper_size" readonly style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px; background-color: #f8f9fa;"></div>';
                                                    modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Attribute Value</label>';
                                                    modalHtml += '<input type="text" name="attribute_values[]" placeholder="e.g. A4, A3, Letter" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
                                                    modalHtml += '<div><button type="button" onclick="removeAttribute(this)" style="padding: 10px 15px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">Remove</button></div>';
                                                    modalHtml += '</div>';
                                                    
                                                    modalHtml += '<div class="attribute-row" style="display: grid; grid-template-columns: 1fr 1fr 100px; gap: 10px; margin-bottom: 10px; align-items: end;">';
                                                    modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Attribute Name</label>';
                                                    modalHtml += '<input type="text" name="attribute_names[]" value="print_type" readonly style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px; background-color: #f8f9fa;"></div>';
                                                    modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Attribute Value</label>';
                                                    modalHtml += '<input type="text" name="attribute_values[]" placeholder="e.g. bw, color, grayscale" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
                                                    modalHtml += '<div><button type="button" onclick="removeAttribute(this)" style="padding: 10px 15px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">Remove</button></div>';
                                                    modalHtml += '</div>';
                                                } else {
                                                    // Regular Product - flexible attributes
                                                    modalHtml += '<div class="attribute-row" style="display: grid; grid-template-columns: 1fr 1fr 100px; gap: 10px; margin-bottom: 10px; align-items: end;">';
                                                    modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Attribute Name</label>';
                                                    modalHtml += '<input type="text" name="attribute_names[]" placeholder="e.g. Size, Color, Material" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
                                                    modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Attribute Value</label>';
                                                    modalHtml += '<input type="text" name="attribute_values[]" placeholder="Attribute Value" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
                                                    modalHtml += '<div><button type="button" onclick="removeAttribute(this)" style="padding: 10px 15px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">Remove</button></div>';
                                                    modalHtml += '</div>';
                                                }
                                                
                                                modalHtml += '</div>';
                                                
                                                // Add Attribute Button
                                                modalHtml += '<button type="button" onclick="addAttribute(' + isSmartPrint + ')" style="padding: 8px 16px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; margin-top: 10px;">+ Add Attribute</button>';
                                                
                                                modalHtml += '</form>';
                                                modalHtml += '</div>';
                                                
                                                // Footer
                                                modalHtml += '<div style="padding: 20px; background: #f8f9fa; border-top: 1px solid #dee2e6; display: flex; justify-content: flex-end; gap: 10px;">';
                                                modalHtml += '<button onclick="closeVariantModal()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;">Cancel</button>';
                                                modalHtml += '<button onclick="saveVariant(' + productId + ')" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Save Variant</button>';
                                                modalHtml += '</div>';
                                                
                                                modalHtml += '</div>';
                                                
                                                overlay.innerHTML = modalHtml;
                                                document.body.appendChild(overlay);
                                            }

                                            // Ensure global wrapper can call this concrete implementation
                                            try {
                                                if (typeof showProperVariantModal === 'function') {
                                                    window._internal_showProperVariantModal = showProperVariantModal;
                                                }
                                            } catch (e) {
                                                console.warn('Could not bind internal showProperVariantModal to window:', e);
                                            }
                                            
                                            function closeVariantModal() {
                                                var modal = document.getElementById('variantModal');
                                                if (modal) {
                                                    modal.remove();
                                                }
                                                try { document.body.classList.remove('modal-open'); } catch(e) {}
                                            }
                                            
                                            function addAttribute(isSmartPrint) {
                                                var container = document.getElementById('attributeContainer');
                                                var row = document.createElement('div');
                                                row.className = 'attribute-row';
                                                row.style.cssText = 'display: grid; grid-template-columns: 1fr 1fr 100px; gap: 10px; margin-bottom: 10px; align-items: end;';
                                                
                                                var namePlaceholder = isSmartPrint ? 'e.g. material, finish, binding' : 'e.g. Size, Color, Material';
                                                
                                                row.innerHTML = '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Attribute Name</label>' +
                                                    '<input type="text" name="attribute_names[]" placeholder="' + namePlaceholder + '" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>' +
                                                    '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Attribute Value</label>' +
                                                    '<input type="text" name="attribute_values[]" placeholder="Attribute Value" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>' +
                                                    '<div><button type="button" onclick="removeAttribute(this)" style="padding: 10px 15px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">Remove</button></div>';
                                                
                                                container.appendChild(row);
                                            }
                                            
                                            function removeAttribute(button) {
                                                button.closest('.attribute-row').remove();
                                            }

                                                    // Global showProperVariantModal so Add New Variant works when product has existing variants
                                                    function showProperVariantModal() {
                                                        var isSmartPrint = {{ $product->is_smart_print_enabled && $product->is_print_service ? 'true' : 'false' }};
                                                        var modalTitle = isSmartPrint ? 'Add Smart Print Variant' : 'Add Product Variant';
                                                        var productId = {{ $product->id }};

                                                        var overlay = document.createElement('div');
                                                        overlay.id = 'variantModal';
                                                        overlay.style.cssText = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 99999; display: flex; align-items: center; justify-content: center; overflow-y: auto;';

                                                        var modalHtml = '<div style="background: white; border-radius: 8px; width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">';

                                                        var headerStyle = isSmartPrint ? 'background: linear-gradient(135deg, #f8f9ff 0%, #e3f2fd 100%); border-bottom: 2px solid #007bff;' : 'background: #f8f9fa; border-bottom: 1px solid #dee2e6;';
                                                        var titleStyle = isSmartPrint ? 'color: #007bff; font-weight: 600;' : 'color: #495057;';

                                                        modalHtml += '<div style="padding: 20px; ' + headerStyle + '">';
                                                        modalHtml += '<div style="display: flex; justify-content: space-between; align-items: center;">';
                                                        modalHtml += '<h4 style="margin: 0; ' + titleStyle + '">' + modalTitle + '</h4>';
                                                        modalHtml += '<button onclick="closeVariantModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6c757d;">&times;</button>';
                                                        modalHtml += '</div>';
                                                        modalHtml += '</div>';

                                                        modalHtml += '<div style="padding: 30px;">';
                                                        modalHtml += '<form id="variantForm">';
                                                        modalHtml += '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">';
                                                        modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Variant Name *</label>';
                                                        modalHtml += '<input type="text" name="name" required style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
                                                        modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">SKU *</label>';
                                                        modalHtml += '<input type="text" name="sku" required style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
                                                        modalHtml += '</div>';

                                                        modalHtml += '<div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">';
                                                        modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Harga Jual *</label>';
                                                        modalHtml += '<input type="number" name="price" step="0.01" required style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
                                                        modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Harga Beli</label>';
                                                        modalHtml += '<input type="number" name="harga_beli" step="0.01" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
                                                        modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Stock *</label>';
                                                        modalHtml += '<input type="number" name="stock" required style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
                                                        modalHtml += '</div>';

                                                        modalHtml += '<div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">';
                                                        modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Berat (kg)</label>';
                                                        modalHtml += '<input type="number" name="weight" step="0.01" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
                                                        modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Panjang (cm)</label>';
                                                        modalHtml += '<input type="number" name="length" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
                                                        modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Lebar (cm)</label>';
                                                        modalHtml += '<input type="number" name="width" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
                                                        modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Tinggi (cm)</label>';
                                                        modalHtml += '<input type="number" name="height" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
                                                        modalHtml += '</div>';

                                                        modalHtml += '<hr style="margin: 30px 0; border: none; border-top: 2px solid #e9ecef;">';
                                                        modalHtml += '<h5 style="margin-bottom: 15px; color: #495057;">Variant Attributes</h5>';

                                                        if (isSmartPrint) {
                                                            modalHtml += '<div style="background: #e3f2fd; border: 1px solid #1976d2; border-radius: 4px; padding: 15px; margin-bottom: 20px;">';
                                                            modalHtml += '<div style="display: flex; align-items: center; color: #1976d2;"><i class="fas fa-info-circle" style="margin-right: 8px;"></i>';
                                                            modalHtml += '<strong>Smart Print Service:</strong> paper_size dan print_type fields sudah dioptimalkan untuk layanan print</div>';
                                                            modalHtml += '</div>';
                                                        } else {
                                                            modalHtml += '<div style="background: #f8f9fa; border: 1px solid #6c757d; border-radius: 4px; padding: 15px; margin-bottom: 20px;">';
                                                            modalHtml += '<div style="display: flex; align-items: center; color: #6c757d;"><i class="fas fa-box" style="margin-right: 8px;"></i>';
                                                            modalHtml += '<strong>Regular Product:</strong> konfigurasi atribut fleksibel untuk produk umum</div>';
                                                            modalHtml += '</div>';
                                                        }

                                                        modalHtml += '<div id="attributeContainer">';
                                                        if (isSmartPrint) {
                                                            modalHtml += '<div class="attribute-row" style="display: grid; grid-template-columns: 1fr 1fr 100px; gap: 10px; margin-bottom: 10px; align-items: end;">';
                                                            modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Attribute Name</label>';
                                                            modalHtml += '<input type="text" name="attribute_names[]" value="paper_size" readonly style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px; background-color: #f8f9fa;"></div>';
                                                            modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Attribute Value</label>';
                                                            modalHtml += '<input type="text" name="attribute_values[]" placeholder="e.g. A4, A3, Letter" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
                                                            modalHtml += '<div><button type="button" onclick="removeAttribute(this)" style="padding: 10px 15px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">Remove</button></div>';
                                                            modalHtml += '</div>';
                                                            modalHtml += '<div class="attribute-row" style="display: grid; grid-template-columns: 1fr 1fr 100px; gap: 10px; margin-bottom: 10px; align-items: end;">';
                                                            modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Attribute Name</label>';
                                                            modalHtml += '<input type="text" name="attribute_names[]" value="print_type" readonly style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px; background-color: #f8f9fa;"></div>';
                                                            modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Attribute Value</label>';
                                                            modalHtml += '<input type="text" name="attribute_values[]" placeholder="e.g. bw, color, grayscale" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
                                                            modalHtml += '<div><button type="button" onclick="removeAttribute(this)" style="padding: 10px 15px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">Remove</button></div>';
                                                            modalHtml += '</div>';
                                                        } else {
                                                            modalHtml += '<div class="attribute-row" style="display: grid; grid-template-columns: 1fr 1fr 100px; gap: 10px; margin-bottom: 10px; align-items: end;">';
                                                            modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Attribute Name</label>';
                                                            modalHtml += '<input type="text" name="attribute_names[]" placeholder="e.g. Size, Color, Material" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
                                                            modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Attribute Value</label>';
                                                            modalHtml += '<input type="text" name="attribute_values[]" placeholder="Attribute Value" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
                                                            modalHtml += '<div><button type="button" onclick="removeAttribute(this)" style="padding: 10px 15px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">Remove</button></div>';
                                                            modalHtml += '</div>';
                                                        }

                                                        modalHtml += '</div>';
                                                        modalHtml += '<button type="button" onclick="addAttribute(' + isSmartPrint + ')" style="padding: 8px 16px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; margin-top: 10px;">+ Add Attribute</button>';
                                                        modalHtml += '</form>';
                                                        modalHtml += '</div>';
                                                        modalHtml += '<div style="padding: 20px; background: #f8f9fa; border-top: 1px solid #dee2e6; display: flex; justify-content: flex-end; gap: 10px;">';
                                                        modalHtml += '<button onclick="closeVariantModal()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;">Cancel</button>';
                                                        modalHtml += '<button onclick="saveVariant(' + productId + ')" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Save Variant</button>';
                                                        modalHtml += '</div>';
                                                        modalHtml += '</div>';

                                                        overlay.innerHTML = modalHtml;
                                                        document.body.appendChild(overlay);
                                                    }
                                            
                                            function saveVariant(productId) {
                                                var form = document.getElementById('variantForm');

                                                // Build FormData manually to avoid sending duplicate or unexpected fields
                                                var formData = new FormData();


                                                // Collect attribute name/value pairs from the form
                                                var nameInputs = Array.from(form.querySelectorAll('input[name="attribute_names[]"]'));
                                                var valueInputs = Array.from(form.querySelectorAll('input[name="attribute_values[]"]'));

                                                // Client-side validation: required fields
                                                var requiredFields = [
                                                    { name: 'name', label: 'Variant Name' },
                                                    { name: 'sku', label: 'SKU' },
                                                    { name: 'price', label: 'Harga Jual' },
                                                    { name: 'stock', label: 'Stock' }
                                                ];
                                                for (var f of requiredFields) {
                                                    var el = form.querySelector('[name="' + f.name + '"]');
                                                    if (!el || !String(el.value).trim()) {
                                                        alert(f.label + ' is required.');
                                                        if (el) el.focus();
                                                        return;
                                                    }
                                                }

                                                // Ensure at least one attribute is present (controller requires attributes array)
                                                if (nameInputs.length === 0) {
                                                    alert('Please add at least one attribute for this variant.');
                                                    return;
                                                }

                                                // Ensure each attribute has both name and value
                                                var attrErrors = [];
                                                for (var i = 0; i < nameInputs.length; i++) {
                                                    var aName = nameInputs[i] ? String(nameInputs[i].value).trim() : '';
                                                    var aValue = valueInputs[i] ? String(valueInputs[i].value).trim() : '';
                                                    if (!aName) attrErrors.push('Attribute #' + (i+1) + ': name is required');
                                                    if (!aValue) attrErrors.push('Attribute #' + (i+1) + ': value is required');
                                                }
                                                if (attrErrors.length) {
                                                    alert('Please fix attribute errors:\n' + attrErrors.join('\n'));
                                                    return;
                                                }

                                                // Append attributes in the expected nested format: attributes[0][attribute_name], attributes[0][attribute_value]
                                                for (var i = 0; i < nameInputs.length; i++) {
                                                    var aName = (nameInputs[i] && nameInputs[i].value) ? nameInputs[i].value : '';
                                                    var aValue = (valueInputs[i] && valueInputs[i].value) ? valueInputs[i].value : '';
                                                    formData.append('attributes[' + i + '][attribute_name]', aName);
                                                    formData.append('attributes[' + i + '][attribute_value]', aValue);
                                                }

                                                // Append main fields explicitly to avoid collisions
                                                ['name','sku','price','harga_beli','stock','weight','length','width','height'].forEach(function(k){
                                                    var el = form.querySelector('[name="' + k + '"]');
                                                    if (el) formData.append(k, el.value);
                                                });

                                                formData.append('product_id', productId);

                                                // Show loading (robustly determine the button)
                                                var saveBtn = (typeof event !== 'undefined' && event && event.target) ? event.target : null;
                                                if (saveBtn && saveBtn.tagName !== 'BUTTON') saveBtn = null;
                                                if (saveBtn) {
                                                    saveBtn.disabled = true;
                                                    saveBtn.textContent = 'Saving...';
                                                }

                                                // Debug: log important form data keys
                                                console.log('Sending form data (summary):');
                                                ['name','sku','price','harga_beli','stock','weight','length','width','height','product_id'].forEach(function(k){
                                                    console.log(k, formData.get(k));
                                                });
                                                // Log attributes
                                                for (let pair of formData.entries()) {
                                                    if (pair[0].startsWith('attributes')) console.log(pair[0], pair[1]);
                                                }

                                                fetch('/admin/variants/create', {
                                                    method: 'POST',
                                                    body: formData,
                                                    headers: {
                                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                                        'X-Requested-With': 'XMLHttpRequest'
                                                    }
                                                })
                                                .then(response => {
                                                    console.log('Response status:', response.status);
                                                    const contentType = response.headers.get('content-type') || '';
                                                    if (!contentType.includes('application/json')) {
                                                        // Try to read text for debugging
                                                        return response.text().then(text => {
                                                            throw new Error('Server returned non-JSON response. Status: ' + response.status + '\n' + text);
                                                        });
                                                    }
                                                    return response.json().then(json => ({ status: response.status, json }));
                                                })
                                                .then(({ status, json }) => {
                                                    console.log('Parsed JSON response:', json);
                                                    if (status === 422) {
                                                        // Validation errors
                                                        var errors = json.errors || {};
                                                        var messages = [];
                                                        Object.keys(errors).forEach(function(k) {
                                                            if (Array.isArray(errors[k])) {
                                                                messages = messages.concat(errors[k]);
                                                            } else if (typeof errors[k] === 'string') {
                                                                messages.push(errors[k]);
                                                            }
                                                        });
                                                        var msg = messages.length ? messages.join('\n') : (json.message || 'Validation failed');
                                                        console.error('Validation errors from server:', json);
                                                        alert('Validation failed:\n' + msg + '\n\nFull response:\n' + JSON.stringify(json, null, 2));
                                                        if (saveBtn) { saveBtn.disabled = false; saveBtn.textContent = 'Save Variant'; }
                                                        return;
                                                    }

                                                    if (json.success || json.message && json.message.toLowerCase().includes('created')) {
                                                        alert('Variant saved successfully!');
                                                        closeVariantModal();
                                                        location.reload();
                                                    } else {
                                                        alert('Error: ' + (json.message || 'Failed to save variant'));
                                                        if (saveBtn) { saveBtn.disabled = false; saveBtn.textContent = 'Save Variant'; }
                                                    }
                                                })
                                                .catch(error => {
                                                    console.error('Save error:', error);
                                                    alert('Error: ' + error.message + '\n\nCheck browser console for details.');
                                                    if (saveBtn) { saveBtn.disabled = false; saveBtn.textContent = 'Save Variant'; }
                                                });
                                            }
                                            </script>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($product)
                        <div class="product-type-section" id="product-type-section">
                            @if ($product->type == 'configurable')
                                <div class="configurable-section">
                                    <div class="form-group row border-bottom pb-4">
                                        <div class="col-12">
                                            <p class="text-primary mt-4">Data Produk Induk (Opsional - untuk auto-fill saat switch ke Simple)</p>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="parent_price">Harga Jual Produk Induk</label>
                                                        <input type="number" step="0.01" class="form-control" name="price" id="parent_price" 
                                                               value="{{ old('price', $product->price) }}" placeholder="Harga jual (opsional)">
                                                        <small class="text-muted">Akan digunakan sebagai default saat switch ke simple</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="parent_harga_beli">Harga Beli Produk Induk</label>
                                                        <input type="number" step="0.01" class="form-control" name="harga_beli" id="parent_harga_beli"
                                                               value="{{ old('harga_beli', $product->harga_beli) }}" placeholder="Harga beli (opsional)">
                                                        <small class="text-muted">Akan digunakan sebagai default saat switch ke simple</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="parent_weight">Berat (kg)</label>
                                                        <input type="number" step="0.01" class="form-control" name="weight" id="parent_weight"
                                                               value="{{ old('weight', $product->weight) }}" placeholder="Berat (opsional)">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="parent_qty">Qty Produk Induk</label>
                                                        <input type="number" class="form-control" name="qty" id="parent_qty"
                                                               value="{{ old('qty', $product->productInventory ? $product->productInventory->qty : null) }}" placeholder="Qty (opsional)">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="parent_length">Panjang (cm)</label>
                                                        <input type="number" step="0.01" class="form-control" name="length" id="parent_length"
                                                               value="{{ old('length', $product->length) }}" placeholder="Panjang">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="parent_width">Lebar (cm)</label>
                                                        <input type="number" step="0.01" class="form-control" name="width" id="parent_width"
                                                               value="{{ old('width', $product->width) }}" placeholder="Lebar">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
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
                                                            <input type="checkbox" class="form-check-input" name="is_print_service" value="1" id="is_print_service_config" {{ old('is_print_service', $product->is_print_service) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="is_print_service_config">
                                                                Aktifkan sebagai produk layanan cetak
                                                            </label>
                                                        </div>
                                                        <small class="form-text text-muted">Produk ini dapat digunakan untuk layanan cetak dokumen</small>
                                                        
                                                        <div class="form-check mt-2" id="smart_print_section_config" style="display: {{ old('is_print_service', $product->is_print_service) ? 'block' : 'none' }};">
                                                            <input type="checkbox" class="form-check-input" name="is_smart_print_enabled" value="1" id="is_smart_print_enabled_config" {{ old('is_smart_print_enabled', $product->is_smart_print_enabled) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="is_smart_print_enabled_config">
                                                                Aktifkan Smart Print untuk produk ini
                                                            </label>
                                                            <small class="form-text text-muted d-block">Smart Print memungkinkan cetak otomatis tanpa operator</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="simple-section">
                                    @include('admin.products.simple')
                                    
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" name="is_print_service" value="1" id="is_print_service_simple" {{ old('is_print_service', $product->is_print_service) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_print_service_simple">
                                                    Aktifkan sebagai produk layanan cetak
                                                </label>
                                                <small class="form-text text-muted d-block">Produk ini akan tersedia untuk layanan pencetakan</small>
                                            </div>
                                            
                                            <div class="form-check mt-2" id="smart_print_section_simple" style="display: {{ old('is_print_service', $product->is_print_service) ? 'block' : 'none' }};">
                                                <input type="checkbox" class="form-check-input" name="is_smart_print_enabled" value="1" id="is_smart_print_enabled_simple" {{ old('is_smart_print_enabled', $product->is_smart_print_enabled) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_smart_print_enabled_simple">
                                                    Aktifkan Smart Print untuk produk ini
                                                </label>
                                                <small class="form-text text-muted d-block">Smart Print memungkinkan cetak otomatis tanpa operator</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

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
                // Use the new proper modal implementation that supports smart-print and attributes
                if (typeof showProperVariantModal === 'function') {
                    showProperVariantModal();
                } else {
                    showVariantModal();
                }
            });
			
            $(document).on('click', '.edit-variant', function() {
                var variantId = $(this).data('variant-id');
                editVariant(variantId);
            });

            $(document).on('click', '.delete-variant', function() {
                var variantId = $(this).data('variant-id');
                var variantName = $(this).data('variant-name');
                deleteVariant(variantId, variantName);
            });
		});
		
        
		
        function editVariant(variantId) {
            // Load variant data and open edit modal (build modal after data arrives)
            $.get('/admin/variants/' + variantId)
                .done(function(res) {
                    var variant = (res && res.variant) ? res.variant : res;
                    if (!variant || !variant.id) {
                        alert('Failed to load variant data');
                        return;
                    }

                    var isSmartPrint = {{ $product->is_smart_print_enabled && $product->is_print_service ? 'true' : 'false' }};
                    var modalTitle = 'Edit Variant';
                    var headerStyle = isSmartPrint ? 'background: linear-gradient(135deg, #f8f9ff 0%, #e3f2fd 100%); border-bottom: 2px solid #007bff;' : 'background: #f8f9fa; border-bottom: 1px solid #dee2e6;';
                    var titleStyle = isSmartPrint ? 'color: #007bff; font-weight: 600;' : 'color: #495057;';

                    // Build modal
                    var modal = '<div class="modal fade show" id="editVariantModal" tabindex="-1" style="display: block; background-color: rgba(0,0,0,0.5);">';
                    modal += '<div class="modal-dialog modal-lg">';
                    modal += '<div class="modal-content">';
                    modal += '<div class="modal-header" style="' + headerStyle + '">';
                    modal += '<h5 class="modal-title" style="' + titleStyle + '">' + modalTitle + '</h5>';
                    modal += '<button type="button" class="close" onclick="closeEditVariantModal()"><span>&times;</span></button>';
                    modal += '</div>';
                    modal += '<div class="modal-body">';
                    modal += '<form id="editVariantForm">';
                    modal += '<input type="hidden" id="edit_variant_id" value="' + variant.id + '">';

                    modal += '<div class="row">';
                    modal += '<div class="col-md-6"><div class="form-group"><label>Variant Name</label><input type="text" class="form-control" name="name" value="' + (variant.name || '') + '" required></div></div>';
                    modal += '<div class="col-md-6"><div class="form-group"><label>SKU</label><input type="text" class="form-control" name="sku" value="' + (variant.sku || '') + '" required></div></div>';
                    modal += '</div>';

                    modal += '<div class="row">';
                    modal += '<div class="col-md-4"><div class="form-group"><label>Harga Jual</label><input type="number" class="form-control" name="price" value="' + (variant.price !== null ? variant.price : '') + '" step="0.01" required></div></div>';
                    modal += '<div class="col-md-4"><div class="form-group"><label>Harga Beli</label><input type="number" class="form-control" name="harga_beli" value="' + (variant.harga_beli !== null ? variant.harga_beli : '') + '" step="0.01"></div></div>';
                    modal += '<div class="col-md-4"><div class="form-group"><label>Stock</label><input type="number" class="form-control" name="stock" value="' + (variant.stock !== null ? variant.stock : '') + '" required></div></div>';
                    modal += '</div>';

                    modal += '<div class="row">';
                    modal += '<div class="col-md-3"><div class="form-group"><label>Berat (kg)</label><input type="number" step="0.01" class="form-control" name="weight" value="' + (variant.weight !== null ? variant.weight : ({{ $product->weight ?? 0 }})) + '"></div></div>';
                    modal += '<div class="col-md-3"><div class="form-group"><label>Panjang (cm)</label><input type="number" class="form-control" name="length" value="' + (variant.length !== null ? variant.length : ({{ $product->length ?? 0 }})) + '"></div></div>';
                    modal += '<div class="col-md-3"><div class="form-group"><label>Lebar (cm)</label><input type="number" class="form-control" name="width" value="' + (variant.width !== null ? variant.width : ({{ $product->width ?? 0 }})) + '"></div></div>';
                    modal += '<div class="col-md-3"><div class="form-group"><label>Tinggi (cm)</label><input type="number" class="form-control" name="height" value="' + (variant.height !== null ? variant.height : ({{ $product->height ?? 0 }})) + '"></div></div>';
                    modal += '</div>';

                    modal += '<hr style="margin: 20px 0;">';
                    modal += '<h6 style="color: #495057; margin-bottom: 15px; font-weight: 600;">Variant Attributes</h6>';
                    modal += '<div id="editAttributeContainer">';

                    // Build attribute rows from returned variant attributes if present
                    if (variant.variant_attributes && variant.variant_attributes.length > 0) {
                        variant.variant_attributes.forEach(function(attr) {
                            var isReadonly = isSmartPrint && (attr.attribute_name === 'paper_size' || attr.attribute_name === 'print_type');
                            var readonlyAttr = isReadonly ? 'readonly style="background-color: #f8f9fa;"' : '';
                            modal += '<div class="attribute-row row mb-2">';
                            modal += '<div class="col-md-5"><input type="text" class="form-control" name="attribute_names[]" value="' + (attr.attribute_name || '') + '" placeholder="Attribute Name" ' + readonlyAttr + '></div>';
                            modal += '<div class="col-md-5"><input type="text" class="form-control" name="attribute_values[]" value="' + (attr.attribute_value || '') + '" placeholder="Attribute Value"></div>';
                            modal += '<div class="col-md-2"><button type="button" class="btn btn-sm btn-danger remove-attribute">Remove</button></div>';
                            modal += '</div>';
                        });
                    } else if (isSmartPrint) {
                        modal += '<div class="attribute-row row mb-2">';
                        modal += '<div class="col-md-5"><input type="text" class="form-control" name="attribute_names[]" value="paper_size" readonly style="background-color: #f8f9fa;"></div>';
                        modal += '<div class="col-md-5"><input type="text" class="form-control" name="attribute_values[]" placeholder="e.g. A4, A3, Letter"></div>';
                        modal += '<div class="col-md-2"><button type="button" class="btn btn-sm btn-danger remove-attribute">Remove</button></div>';
                        modal += '</div>';
                        modal += '<div class="attribute-row row mb-2">';
                        modal += '<div class="col-md-5"><input type="text" class="form-control" name="attribute_names[]" value="print_type" readonly style="background-color: #f8f9fa;"></div>';
                        modal += '<div class="col-md-5"><input type="text" class="form-control" name="attribute_values[]" placeholder="e.g. bw, color"></div>';
                        modal += '<div class="col-md-2"><button type="button" class="btn btn-sm btn-danger remove-attribute">Remove</button></div>';
                        modal += '</div>';
                    } else {
                        modal += '<div class="attribute-row row mb-2">';
                        modal += '<div class="col-md-5"><input type="text" class="form-control" name="attribute_names[]" placeholder="Attribute Name (e.g. Size, Color)"></div>';
                        modal += '<div class="col-md-5"><input type="text" class="form-control" name="attribute_values[]" placeholder="Attribute Value"></div>';
                        modal += '<div class="col-md-2"><button type="button" class="btn btn-sm btn-danger remove-attribute">Remove</button></div>';
                        modal += '</div>';
                    }

                    modal += '</div>';
                    modal += '<button type="button" class="btn btn-sm btn-secondary" id="addEditAttribute">Add Attribute</button>';
                    modal += '</form>';
                    modal += '</div>';
                    modal += '<div class="modal-footer">';
                    modal += '<button type="button" class="btn btn-secondary" onclick="closeEditVariantModal()">Cancel</button>';
                    modal += '<button type="button" class="btn btn-primary" id="updateVariantBtn">Update Variant</button>';
                    modal += '</div>';
                    modal += '</div></div></div>';

                    // Append and show
                    $('body').append(modal);
                    document.body.classList.add('modal-open');

                    // Debug: inspect returned variant in console
                    console.log('editVariant loaded variant:', variant);

                    // Helper: normalize values to avoid locale-format issues (comma vs dot)
                    function parseNumberFrom(v) {
                        if (v === null || v === undefined) return '';
                        var s = String(v);
                        // Remove currency symbols and spaces
                        s = s.replace(/[^0-9,.-]/g, '');
                        // If comma is used as decimal separator and dot as thousand, normalize by
                        // converting commas to dots and removing other non-digit/point characters.
                        // First convert comma to dot
                        s = s.replace(/,/g, '.');
                        // Remove any extra dots beyond the first (thousands separators)
                        var parts = s.split('.');
                        if (parts.length > 2) {
                            var first = parts.shift();
                            s = first + '.' + parts.join('');
                        }
                        // Trim leading/trailing non-number
                        s = s.replace(/^[^0-9.-]+|[^0-9.-]+$/g, '');
                        return s;
                    }

                    // Populate main fields defensively (some APIs may return formatted strings)
                    try {
                        $('#editVariantModal input[name="name"]').val(variant.name || '');
                        $('#editVariantModal input[name="sku"]').val(variant.sku || '');

                        // Price: prefer variant.price if present and >0, otherwise fallback to parent product price
                        var rawPrice = variant.price ?? variant.price_formatted ?? variant.price_formatted_display ?? null;
                        var parsedPrice = parseFloat(parseNumberFrom(rawPrice));
                        if (!parsedPrice || parsedPrice <= 0) {
                            // Try to read live parent product input first, then fallback to blade value
                            var parentPriceVal = (typeof $ !== 'undefined' && $('#parent_price').length) ? $('#parent_price').val() : null;
                            parsedPrice = parseFloat(parseNumberFrom(parentPriceVal !== null ? parentPriceVal : ({{ $product->price ?? 0 }}))) || '';
                        }
                        $('#editVariantModal input[name="price"]').val(parsedPrice !== '' ? parsedPrice : '');

                        // Harga Beli (cost) fallback logic
                        var rawHarga = variant.harga_beli ?? variant.hargaBeli ?? variant.cost ?? null;
                        var parsedHarga = parseFloat(parseNumberFrom(rawHarga));
                        if (!parsedHarga || parsedHarga <= 0) {
                            var parentHargaVal = (typeof $ !== 'undefined' && $('#parent_harga_beli').length) ? $('#parent_harga_beli').val() : null;
                            parsedHarga = parseFloat(parseNumberFrom(parentHargaVal !== null ? parentHargaVal : ({{ $product->harga_beli ?? 0 }}))) || '';
                        }
                        $('#editVariantModal input[name="harga_beli"]').val(parsedHarga !== '' ? parsedHarga : '');

                        // Stock
                        var rawStock = variant.stock ?? variant.qty ?? variant.quantity ?? 0;
                        $('#editVariantModal input[name="stock"]').val(parseNumberFrom(rawStock) !== '' ? parseNumberFrom(rawStock) : '');

                        // Dimensions / weight (use product defaults if missing)
                        var parentWeight = (typeof $ !== 'undefined' && $('#parent_weight').length) ? $('#parent_weight').val() : ({{ $product->weight ?? 0 }});
                        var parentLength = (typeof $ !== 'undefined' && $('#parent_length').length) ? $('#parent_length').val() : ({{ $product->length ?? 0 }});
                        var parentWidth = (typeof $ !== 'undefined' && $('#parent_width').length) ? $('#parent_width').val() : ({{ $product->width ?? 0 }});
                        var parentHeight = (typeof $ !== 'undefined' && $('#parent_height').length) ? $('#parent_height').val() : ({{ $product->height ?? 0 }});

                        // For dimensions and weight: if variant value is missing or <= 0, fallback to parent value
                        var vWeight = parseFloat(parseNumberFrom(variant.weight));
                        if (isNaN(vWeight) || vWeight <= 0) {
                            $('#editVariantModal input[name="weight"]').val(parseNumberFrom(parentWeight));
                        } else {
                            $('#editVariantModal input[name="weight"]').val(vWeight);
                        }

                        var vLength = parseFloat(parseNumberFrom(variant.length));
                        if (isNaN(vLength) || vLength <= 0) {
                            $('#editVariantModal input[name="length"]').val(parseNumberFrom(parentLength));
                        } else {
                            $('#editVariantModal input[name="length"]').val(vLength);
                        }

                        var vWidth = parseFloat(parseNumberFrom(variant.width));
                        if (isNaN(vWidth) || vWidth <= 0) {
                            $('#editVariantModal input[name="width"]').val(parseNumberFrom(parentWidth));
                        } else {
                            $('#editVariantModal input[name="width"]').val(vWidth);
                        }

                        var vHeight = parseFloat(parseNumberFrom(variant.height));
                        if (isNaN(vHeight) || vHeight <= 0) {
                            $('#editVariantModal input[name="height"]').val(parseNumberFrom(parentHeight));
                        } else {
                            $('#editVariantModal input[name="height"]').val(vHeight);
                        }
                    } catch (e) {
                        console.warn('Failed to populate edit variant fields:', e);
                    }

                    // Handlers
                    $('#addEditAttribute').on('click', function() {
                        var newRow = '<div class="attribute-row row mb-2">';
                        newRow += '<div class="col-md-5"><input type="text" class="form-control" name="attribute_names[]" placeholder="Attribute Name"></div>';
                        newRow += '<div class="col-md-5"><input type="text" class="form-control" name="attribute_values[]" placeholder="Attribute Value"></div>';
                        newRow += '<div class="col-md-2"><button type="button" class="btn btn-sm btn-danger remove-attribute">Remove</button></div>';
                        newRow += '</div>';
                        $('#editAttributeContainer').append(newRow);
                    });

                    $(document).on('click', '#editVariantModal .remove-attribute', function() {
                        $(this).closest('.attribute-row').remove();
                    });

                    $(document).on('click', '#updateVariantBtn', function(e) {
                        updateVariant(variant.id);
                    });
                })
                .fail(function() {
                    alert('Failed to load variant data');
                });
        }
		
		function closeEditVariantModal() {
			$('#editVariantModal').remove();
			document.body.classList.remove('modal-open');
		}
		
        function updateVariant(variantId) {
            var form = document.getElementById('editVariantForm');
            var fd = new FormData();

            // Required fields validation
            var required = ['name','sku','price','stock'];
            for (var i=0;i<required.length;i++){
                var el = form.querySelector('[name="'+required[i]+'"]');
                if (!el || !String(el.value).trim()){
                    alert(required[i] + ' is required');
                    if (el) el.focus();
                    return;
                }
            }

            // Append main fields
            ['name','sku','price','harga_beli','stock','weight','length','width','height'].forEach(function(k){
                var el = form.querySelector('[name="'+k+'"]');
                if (el) fd.append(k, el.value);
            });

            // Attributes
            var nameInputs = Array.from(form.querySelectorAll('input[name="attribute_names[]"]'));
            var valueInputs = Array.from(form.querySelectorAll('input[name="attribute_values[]"]'));
            if (nameInputs.length === 0) {
                alert('Please add at least one attribute for the variant');
                return;
            }
            for (var i=0;i<nameInputs.length;i++){
                var n = nameInputs[i] ? String(nameInputs[i].value).trim() : '';
                var v = valueInputs[i] ? String(valueInputs[i].value).trim() : '';
                if (!n || !v) {
                    alert('Each attribute must have a name and value');
                    return;
                }
                fd.append('attributes['+i+'][attribute_name]', n);
                fd.append('attributes['+i+'][attribute_value]', v);
            }

            // Method override for PUT
            fd.append('_method','PUT');

            // CSRF header
            var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            $.ajax({
                url: '/admin/variants/' + variantId,
                method: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest'
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
                    } else if (xhr.responseText) {
                        try { var j = JSON.parse(xhr.responseText); if (j && j.message) message = j.message; } catch(e){}
                    }
                    alert(message + '\n\nFull response:\n' + (xhr.responseText || JSON.stringify(xhr)));
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
		
		function handlePrintServiceLogic() {
		    var productType = $('.product-type').val();
		    var printServiceCheckbox, smartPrintSection, smartPrintCheckbox;
		    
		    if (productType === 'configurable') {
		        printServiceCheckbox = $("#is_print_service_config");
		        smartPrintSection = $("#smart_print_section_config");
		        smartPrintCheckbox = $("#is_smart_print_enabled_config");
		    } else {
		        printServiceCheckbox = $("#is_print_service_simple");
		        smartPrintSection = $("#smart_print_section_simple");
		        smartPrintCheckbox = $("#is_smart_print_enabled_simple");
		    }
		    
		    var printServiceChecked = printServiceCheckbox.is(':checked');
		    
		    if (printServiceChecked) {
		        smartPrintSection.show();
		    } else {
		        smartPrintSection.hide();
		        smartPrintCheckbox.prop('checked', false);
		    }
		}
		
		function updateFormForProductType() {
		    var productType = $('.product-type').val();
		    
		    if (productType === 'configurable') {
		        $('.configurable-section').show();
		        $('.simple-section').hide();
		    } else {
		        $('.configurable-section').hide();
		        $('.simple-section').show();
		    }
		    
		    handlePrintServiceLogic();
		}
		
		$(document).ready(function() {
		    updateFormForProductType();
		    
		    $('.product-type').change(function() {
		        updateFormForProductType();
		    });
		    
		    $("#is_print_service_config, #is_print_service_simple").change(function() {
		        handlePrintServiceLogic();
		    });
		});

// Defensive handlers: ensure Add buttons always open the variant modal and
// remove any leftover modal overlay that might block clicks.
$(function() {
    // Remove any stale modal overlay left in DOM (prevents click-blocking)
    if (document.getElementById('variantModal')) {
        document.getElementById('variantModal').remove();
        try { document.body.classList.remove('modal-open'); } catch (e) {}
    }

    // Delegated click handler so the Add buttons work even if inline onclick
    // was not bound or got removed. This also cleans up any lingering overlay
    // before opening a fresh modal.
    $(document).on('click', '#addNewVariant, #addFirstVariant', function(e) {
        e.preventDefault();
        // Clean up existing overlay if present
        $('#variantModal').remove();
        try { document.body.classList.remove('modal-open'); } catch (e) {}

        if (typeof showProperVariantModal === 'function') {
            showProperVariantModal();
        } else {
            console.error('showProperVariantModal is not defined');
            alert('Cannot open variant modal: helper function missing. See console for details.');
        }
    });
});

// Ensure global function names exist even if earlier conditional script blocks
// didn't define them (this prevents Uncaught ReferenceError on button onclicks).
if (typeof window.showProperVariantModal === 'undefined') {
    window.showProperVariantModal = function() {
        // Prefer the concrete internal implementation if available
        if (typeof window._internal_showProperVariantModal === 'function') {
            return window._internal_showProperVariantModal();
        }

        // Fallback: show a clear debug modal (do not attempt to call other wrappers to avoid recursion)
        console.warn('showProperVariantModal: internal implementation not available. Showing debug modal.');
        var overlay = document.getElementById('variantModal');
        if (overlay) overlay.remove();
        try { document.body.classList.remove('modal-open'); } catch (e) {}
        overlay = document.createElement('div');
        overlay.id = 'variantModal';
        overlay.style.cssText = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 99999; display: flex; align-items: center; justify-content: center;';
        overlay.innerHTML = '<div style="background: white; padding: 20px; border-radius: 8px;">Debug: variant modal missing. Please check console for details.<br><br><button onclick="(function(){document.getElementById(\'variantModal\').remove(); try{document.body.classList.remove(\'modal-open\');}catch(e){} })()">Close</button></div>';
        document.body.appendChild(overlay);
    };
}

if (typeof window.showVariantModal === 'undefined') {
    window.showVariantModal = function() {
        // Prefer the concrete internal implementation if available
        if (typeof window._internal_showProperVariantModal === 'function') {
            return window._internal_showProperVariantModal();
        }

        // Fallback: debug modal (do not call other wrappers to avoid recursion)
        console.warn('showVariantModal: internal implementation not available. Showing debug modal.');
        var overlay = document.getElementById('variantModal');
        if (overlay) overlay.remove();
        try { document.body.classList.remove('modal-open'); } catch (e) {}
        overlay = document.createElement('div');
        overlay.id = 'variantModal';
        overlay.style.cssText = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 99999; display: flex; align-items: center; justify-content: center;';
        overlay.innerHTML = '<div style="background: white; padding: 20px; border-radius: 8px;">Debug: variant modal missing. Please check console for details.<br><br><button onclick="(function(){document.getElementById(\'variantModal\').remove(); try{document.body.classList.remove(\'modal-open\');}catch(e){} })()">Close</button></div>';
        document.body.appendChild(overlay);
    };
}

// Extra defensive binding: if internal implementation exists under another name,
// expose it so the delegated handler above can always call it.
(function() {
    try {
        if (typeof window.showProperVariantModal === 'function') {
            // Avoid binding the debug/wrapper implementation to the internal name (this would cause recursion).
            var fnStr = String(window.showProperVariantModal || '');
            if (fnStr.indexOf('Debug: variant modal missing') === -1 && fnStr.indexOf('no implementation found') === -1) {
                window._internal_showProperVariantModal = window.showProperVariantModal;
            }
        }
    } catch (e) {
        console.warn('Defensive bind failed', e);
    }

    // Ensure add buttons always open modal (covers edge cases where inline onclick may be stripped)
    $(document).off('click', '#addNewVariant, #addFirstVariant').on('click', '#addNewVariant, #addFirstVariant', function(e){
        e.preventDefault();
        $('#variantModal').remove();
        try { document.body.classList.remove('modal-open'); } catch(e) {}
        if (typeof window._internal_showProperVariantModal === 'function') {
            return window._internal_showProperVariantModal();
        }
        if (typeof window.showProperVariantModal === 'function') {
            return window.showProperVariantModal();
        }
        console.error('No variant modal implementation available');
    });
})();
</script>
@include('admin.products.partials.variant-modal-script')
@endpush
