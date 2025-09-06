<!DOCTYPE html>
<html>
<head>
    <title>Test Admin Product Edit</title>
</head>
<body>
    <h1>Admin Product Edit Test</h1>
    <p>Product ID: {{ $product->id }}</p>
    <p>Product Name: {{ $product->name }}</p>
    <p>Product Type: {{ $product->type }}</p>
    
    @if($product->type === 'configurable')
        <h2>Product Variants</h2>
        @if($product->productVariants && $product->productVariants->count() > 0)
            <p>Variants Count: {{ $product->productVariants->count() }}</p>
            @foreach($product->productVariants as $variant)
                <div>
                    <strong>SKU:</strong> {{ $variant->sku }}<br>
                    <strong>Name:</strong> {{ $variant->name }}<br>
                    <strong>Price:</strong> {{ $variant->price }}<br>
                    <strong>Stock:</strong> {{ $variant->stock }}<br>
                    @if($variant->variantAttributes)
                        <strong>Attributes:</strong>
                        @foreach($variant->variantAttributes as $attr)
                            {{ $attr->attribute_name }}: {{ $attr->attribute_value }}
                        @endforeach
                    @endif
                </div>
                <hr>
            @endforeach
        @else
            <p>No variants found</p>
        @endif
    @endif
    
    <h2>Form Data</h2>
    <p>Categories: {{ $categories->count() }}</p>
    <p>Brands: {{ $brands->count() }}</p>
    <p>Types: {{ count($types) }}</p>
    <p>Statuses: {{ count($statuses) }}</p>
</body>
</html>
