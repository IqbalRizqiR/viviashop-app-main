<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Barcode Preview - Landscape Mode</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }

        .header {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-info h2 {
            margin: 0;
            color: #28a745;
            font-size: 24px;
        }

        .header-info p {
            margin: 5px 0 0 0;
            color: #666;
        }

        .header-buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-back {
            background: #6c757d;
            color: white;
        }

        .btn-back:hover {
            background: #5a6268;
        }

        .btn-print {
            background: #28a745;
            color: white;
        }

        .btn-print:hover {
            background: #218838;
        }

        .page-preview {
            background: white;
            margin-bottom: 30px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 297mm;
            min-height: 210mm;
            transform-origin: top left;
            transform: scale(0.6);
            margin-left: 0;
        }

        .page-info {
            background: #28a745;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-weight: bold;
            width: fit-content;
        }

        .barcode-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            align-content: flex-start;
            gap: 1mm;
            width: 100%;
            height: 100%;
        }

        .barcode-item {
            border: 1px solid #333;
            padding: 2mm;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-sizing: border-box;
            background: white;
            width: calc(20% - 1mm);
            height: 35mm;
        }

        .product-info {
            font-size: 8pt;
            line-height: 1.1;
            margin-bottom: 2mm;
        }

        .product-name {
            font-weight: bold;
            font-size: 9pt;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 1mm;
        }

        .product-sku {
            font-size: 7pt;
            color: #666;
            margin-bottom: 1mm;
        }

        .barcode-section {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .barcode-code {
            font-family: 'Courier New', monospace;
            font-size: 6pt;
            letter-spacing: 0.5px;
            margin-top: 1mm;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-info">
            <h2>🖨️ Barcode Preview - Landscape Mode</h2>
            <p>{{ $data->count() }} products | Layout: 5×5 = 25 items per page | A4 Landscape</p>
        </div>
        <div class="header-buttons">
            <a href="{{ route('admin.barcode.preview') }}" class="btn btn-back">
                ← Back to Menu
            </a>
            <a href="{{ route('admin.barcode.print.landscape') }}" class="btn btn-print" target="_blank">
                🖨️ Print Landscape
            </a>
        </div>
    </div>

    @php
        $chunks = collect($data)->chunk(25);
    @endphp

    @foreach($chunks as $pageIndex => $pageData)
        <div class="page-info">
            Page {{ $pageIndex + 1 }} of {{ $chunks->count() }} (Landscape - 25 items)
        </div>
        <div class="page-preview">
            <div class="barcode-container">
                @foreach($pageData as $produk)
                    <div class="barcode-item">
                        <div class="product-info">
                            <div class="product-name" title="{{ $produk->name }}">{{ Str::limit($produk->name, 15) }}</div>
                            <div class="product-sku">{{ Str::limit($produk->sku, 12) }}</div>
                        </div>
                        <div class="barcode-section">
                            @if($produk->barcode)
                                {!! DNS1D::getBarcodeHTML($produk->barcode, 'C128', 0.8, 12) !!}
                                <div class="barcode-code">{{ $produk->barcode }}</div>
                            @else
                                <div style="font-size: 8pt; color: #999;">No Barcode</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</body>
</html>
