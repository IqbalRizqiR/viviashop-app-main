<p class="text-primary mt-4">Product Variants</p>
<hr/>
 @foreach ($product->productVariants as $variant)
    <div class="row mb-3">
    <input type="hidden" value="{{ $variant->id }}" name="variants[{{ $variant->id }}][id]">
        <div class="col-md-3">
            <div class="form-group border-bottom pb-4">
                <label for="sku" class="form-label">SKU</label>
                <input type="text" class="form-control" name="variants[{{ $variant->id }}][sku]" value="{{ old('sku', $variant->sku) }}" id="sku">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group border-bottom pb-4">
                <label for="name" class="form-label">Nama Produk</label>
                <input type="text" class="form-control" name="variants[{{ $variant->id }}][name]" value="{{ old('name', $variant->name) }}" id="name">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group border-bottom pb-4">
                <label for="price" class="form-label">Harga Jual</label>
                <input type="number" class="form-control" name="variants[{{ $variant->id }}][price]" value="{{ old('price', $variant->price) }}" id="price">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group border-bottom pb-4">
                <label for="harga_beli" class="form-label">Harga Beli</label>
                <input type="number" class="form-control" name="variants[{{ $variant->id }}][harga_beli]" value="{{ old('harga_beli', $variant->harga_beli) }}" id="harga_beli">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group border-bottom pb-4">
                <label for="qty" class="form-label">Jumlah</label>
                <input type="number" class="form-control" name="variants[{{ $variant->id }}][stock]" value="{{ old('stock', $variant->stock) }}" id="qty">
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="form-group border-bottom pb-4">
                <label for="weight" class="form-label">Berat (kg)</label>
                <input type="number" step="0.01" class="form-control" name="variants[{{ $variant->id }}][weight]" value="{{ old('weight', $variant->weight) }}" id="weight">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group border-bottom pb-4">
                <label for="length" class="form-label">Panjang (cm)</label>
                <input type="number" class="form-control" name="variants[{{ $variant->id }}][length]" value="{{ old('length', $variant->length) }}" id="length">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group border-bottom pb-4">
                <label for="width" class="form-label">Lebar (cm)</label>
                <input type="number" class="form-control" name="variants[{{ $variant->id }}][width]" value="{{ old('width', $variant->width) }}" id="width">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group border-bottom pb-4">
                <label for="height" class="form-label">Tinggi (cm)</label>
                <input type="number" class="form-control" name="variants[{{ $variant->id }}][height]" value="{{ old('height', $variant->height) }}" id="height">
            </div>
        </div>
    </div>
@endforeach
<hr/>
