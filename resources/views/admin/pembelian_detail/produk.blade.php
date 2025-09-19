<div class="modal" id="modal-produk" tabindex="-1" role="dialog" aria-labelledby="modal-produk">
    <div class="modal-dialog modal-lg" role="document" style="width: 90%; max-width: 1100px;">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title text-white">
                    <i class="fa fa-shopping-cart"></i> Pilih Produk
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 15px;">
                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="input-group input-group-sm">
                            <div class="input-group-addon">
                                <i class="fa fa-search"></i>
                            </div>
                            <input type="text" class="form-control" id="search-produk" placeholder="Cari produk...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <select class="form-control input-sm" id="filter-type">
                            <option value="">Semua Tipe</option>
                            <option value="simple">Simple</option>
                            <option value="configurable">Configurable</option>
                        </select>
                    </div>
                </div>
                <div style="height: 400px; overflow-y: auto; border: 1px solid #ddd;">
                    <table class="table table-striped table-bordered table-produk table-hover table-condensed" style="margin-bottom: 0; font-size: 12px;">
                        <thead class="bg-gray" style="position: sticky; top: 0; z-index: 10;">
                            <tr>
                                <th style="width: 40px; text-align: center;">No</th>
                                <th style="width: 60px; text-align: center;">ID</th>
                                <th style="width: 200px;">Nama Produk</th>
                                <th style="width: 80px; text-align: center;">Type</th>
                                <th style="width: 100px; text-align: right;">H. Beli</th>
                                <th style="width: 100px; text-align: right;">H. Jual</th>
                                <th style="width: 60px; text-align: center;">Stok</th>
                                <th style="width: 80px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produk as $key => $item)
                                <tr data-type="{{ $item->type }}" data-name="{{ strtolower(strip_tags($item->name)) }}" data-product-id="{{ $item->id }}" style="height: 35px;">
                                    <td style="text-align: center; vertical-align: middle; padding: 4px;">{{ $key+1 }}</td>
                                    <td style="text-align: center; vertical-align: middle; padding: 4px;">
                                        <span class="label label-success" style="font-size: 9px;">{{ $item->id }}</span>
                                    </td>
                                    <td style="vertical-align: middle; padding: 4px;">
                                        <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ strip_tags($item->name) }}">
                                            <strong style="font-size: 11px;">{{ strip_tags(Str::limit($item->name, 25)) }}</strong>
                                        </div>
                                    </td>
                                    <td style="text-align: center; vertical-align: middle; padding: 4px;">
                                        @if($item->type == 'configurable')
                                            <span class="label label-info" style="font-size: 9px;">Config</span>
                                        @else
                                            <span class="label label-primary" style="font-size: 9px;">Simple</span>
                                        @endif
                                    </td>
                                    <td style="text-align: right; vertical-align: middle; padding: 4px;">
                                        <small style="font-size: 10px;">{{ number_format($item->harga_beli ?? 0, 0, ',', '.') }}</small>
                                    </td>
                                    <td style="text-align: right; vertical-align: middle; padding: 4px;">
                                        @if($item->type == 'configurable')
                                            @php
                                                $minPrice = $item->productVariants->min('price');
                                                $maxPrice = $item->productVariants->max('price');
                                            @endphp
                                            <small style="font-size: 10px;">
                                                @if($minPrice == $maxPrice)
                                                    {{ number_format($minPrice, 0, ',', '.') }}
                                                @else
                                                    {{ number_format($minPrice, 0, ',', '.') }}-{{ number_format($maxPrice, 0, ',', '.') }}
                                                @endif
                                            </small>
                                        @else
                                            <small style="font-size: 10px;">{{ number_format($item->price ?? 0, 0, ',', '.') }}</small>
                                        @endif
                                    </td>
                                    <td style="text-align: center; vertical-align: middle; padding: 4px;">
                                        @if($item->type == 'configurable')
                                            @php $totalStock = $item->productVariants->sum('stock'); @endphp
                                            <span class="badge {{ $totalStock > 10 ? 'badge-success' : ($totalStock > 0 ? 'badge-warning' : 'badge-danger') }}" style="font-size: 9px;">
                                                {{ $totalStock }}
                                            </span>
                                        @else
                                            @php $stock = $item->productInventory->qty ?? 0; @endphp
                                            <span class="badge {{ $stock > 10 ? 'badge-success' : ($stock > 0 ? 'badge-warning' : 'badge-danger') }}" style="font-size: 9px;">
                                                {{ $stock }}
                                            </span>
                                        @endif
                                    </td>
                                    <td style="text-align: center; vertical-align: middle; padding: 4px;">
                                        @if($item->type == 'configurable')
                                            <button type="button" class="btn btn-info btn-xs btn-flat btn-variant"
                                                style="font-size: 9px; padding: 2px 6px;"
                                                data-id="{{ $item->id }}"
                                                onclick="showVariants('{{ $item->id }}')"
                                                {{ $item->productVariants->sum('stock') <= 0 ? 'disabled' : '' }}>
                                                <i class="fa fa-list"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-primary btn-xs btn-flat btn-pilih"
                                                style="font-size: 9px; padding: 2px 6px;"
                                                data-id="{{ $item->id }}"
                                                onclick="pilihProduk('{{ $item->id }}', null)"
                                                {{ ($item->productInventory->qty ?? 0) <= 0 ? 'disabled' : '' }}>
                                                <i class="fa fa-check"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="dataTables_info">
                            Menampilkan <span id="showing-start">1</span> sampai <span id="showing-end">8</span> 
                            dari <span id="total-products">{{ count($produk) }}</span> produk
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="dataTables_paginate" style="text-align: right;">
                            <button class="paginate_button" id="prev-btn" onclick="changePage(-1)">
                                <i class="fa fa-angle-left"></i> Previous
                            </button>
                            <span id="page-numbers"></span>
                            <button class="paginate_button" id="next-btn" onclick="changePage(1)">
                                Next <i class="fa fa-angle-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">
                    <i class="fa fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal-variant" tabindex="-1" role="dialog" aria-labelledby="modal-variant">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title text-white">
                    <i class="fa fa-cogs"></i> Pilih Variant Produk
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="variant-content">
                    <div class="text-center">
                        <i class="fa fa-spinner fa-spin fa-2x"></i>
                        <p class="mt-2">Memuat data variant...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">
                    <i class="fa fa-arrow-left"></i> Kembali
                </button>
            </div>
        </div>
    </div>
</div>
