<div class="row">
    <div class="col-md-2">
        <div class="form-group border-bottom pb-4">
            <label for="price" class="form-label">Harga Jual Produk</label>
            <input type="number" class="form-control" name="price" value="{{ old('price', $product->price) }}" id="price">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group border-bottom pb-4">
            <label for="harga_beli" class="form-label">Harga Beli Produk</label>
            <input type="number" class="form-control" name="harga_beli" value="{{ old('harga_beli', $product->harga_beli) }}" id="harga_beli">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group border-bottom pb-4">
            <label for="weight" class="form-label">Berat Produk</label>
            <input type="number" class="form-control" name="weight" value="{{ old('weight', $product->weight) }}" placeholder="gr" id="weight">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group border-bottom pb-4">
            <label for="qty" class="form-label">Jumlah Produk</label>
            <input type="number" class="form-control" name="qty" value="{{ old('qty', $product->productInventory ? $product->productInventory->qty : null) }}" id="qty">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group border-bottom pb-4">
            <label for="length" class="form-label">Panjang</label>
            <input type="number" class="form-control" placeholder="cm" name="length" value="{{ old('length', $product->length) }}" id="length">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group border-bottom pb-4">
            <label for="width" class="form-label">Lebar</label>
            <input type="number" class="form-control" placeholder="cm" name="width" value="{{ old('width', $product->width) }}" id="width">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group border-bottom pb-4">
            <label for="height" class="form-label">Tinggi</label>
            <input type="number" class="form-control" placeholder="cm" name="height" value="{{ old('height', $product->height) }}" id="height">
        </div>
    </div>
</div>
