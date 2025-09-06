<!DOCTYPE html>
<html>
<head>
    <title>Admin Product Edit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <h1>Edit Product: {{ $product->name }}</h1>
        
        <form method="POST" action="{{ route('admin.products.update', $product->id) }}">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Product Type</label>
                        <select class="form-control" name="type">
                            @foreach($types as $value => $type)
                                <option {{ old('type', $product->type) == $value ? 'selected' : '' }} value="{{ $value }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Categories</label>
                        <select class="form-control" multiple name="category_id[]">
                            @foreach($categories as $category)
                                <option {{ in_array($category->id, $product->categories->pluck('id')->toArray()) ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
            @if($product->type === 'configurable')
                <div class="product-variants-section mt-4">
                    <h3>Product Variants Management</h3>
                    
                    @if($product->productVariants && $product->productVariants->count() > 0)
                        <div class="existing-variants">
                            <h5>Existing Variants ({{ $product->productVariants->count() }})</h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>SKU</th>
                                            <th>Name</th>
                                            <th>Attributes</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($product->productVariants as $variant)
                                            <tr>
                                                <td>{{ $variant->sku }}</td>
                                                <td>{{ $variant->name }}</td>
                                                <td>
                                                    @foreach($variant->variantAttributes as $attr)
                                                        <span class="badge bg-secondary">{{ $attr->attribute_name }}: {{ $attr->attribute_value }}</span>
                                                    @endforeach
                                                </td>
                                                <td>Rp {{ number_format($variant->price, 0, ',', '.') }}</td>
                                                <td>{{ $variant->stock }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $variant->is_active ? 'success' : 'secondary' }}">
                                                        {{ $variant->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="no-variants">
                            <p class="text-muted">No variants created yet for this configurable product.</p>
                        </div>
                    @endif
                </div>
            @endif
            
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">Update Product</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
