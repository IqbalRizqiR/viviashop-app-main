<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Barcode Preview - All Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }

        .header {
            background: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .download-btn {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }

        .download-btn:hover {
            background: #0056b3;
        }

        .page-preview {
            background: white;
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            width: 297mm;
            min-height: 210mm;
            transform-origin: top left;
            transform: scale(0.5);
            margin-left: 0;
        }

        .page-preview.portrait-page {
            width: 210mm;
            min-height: 297mm;
        }

        .barcode-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            align-content: flex-start;
            width: 100%;
            height: auto;
            max-width: 100%;
            gap: 1mm;
        }

        .barcode-container.portrait-container {
            justify-content: flex-start;
        }

        .barcode-item {
            border: 1px solid #333;
            margin: 0;
            padding: 2mm;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-sizing: border-box;
            background: white;
        }

        .barcode-item.landscape-preview {
            width: calc(20% - 1mm);
            height: 32mm;
        }

        .barcode-item.portrait-preview {
            width: calc(25% - 1mm);
            height: 22mm;
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
            font-size: 7pt;
            letter-spacing: 0.5px;
            margin-top: 1mm;
        }

        .page-info {
            text-align: center;
            color: #666;
            font-size: 12px;
            margin-bottom: 10px;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .header {
                display: none;
            }
            .page-preview {
                transform: none;
                margin: 0;
                box-shadow: none;
                page-break-after: always;
            }
            .page-info {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h2>Barcode Preview - All Products</h2>
            <p>Total Products: {{ $data->count() }} | Landscape: 5x5=25 per page | Portrait: 8x4=32 per page</p>
        </div>
        <div>
            <button onclick="window.print()" class="download-btn" style="background: #28a745;">🖨️ Print / Save as PDF</button>
        </div>
    </div>

    @php
        $landscapeChunks = collect($data)->chunk(25);
        $portraitChunks = collect($data)->chunk(32);
    @endphp

    <div style="margin-bottom: 30px;">
        <h3>Landscape Layout Preview (5x5 = 25 items per page)</h3>
        @foreach($landscapeChunks as $pageIndex => $pageData)
            <div class="page-info">Page {{ $pageIndex + 1 }} of {{ $landscapeChunks->count() }}</div>
            <div class="page-preview">
                <div class="barcode-container">
                    @foreach($pageData as $produk)
                        <div class="barcode-item landscape-preview">
                            <div class="product-info">
                                <div class="product-name" title="{{ $produk->name }}">{{ Str::limit($produk->name, 15) }}</div>
                                <div class="product-sku">{{ Str::limit($produk->sku, 12) }}</div>
                            </div>
                            <div class="barcode-section">
                                @if($produk->barcode)
                                    {!! DNS1D::getBarcodeHTML($produk->barcode, 'C128', 0.8, 10) !!}
                                    <div class="barcode-code">{{ $produk->barcode }}</div>
                                @else
                                    <div style="font-size: 6pt; color: #999;">No Barcode</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <div style="margin-top: 40px;">
        <h3>Portrait Layout Preview (8x4 = 32 items per page)</h3>
        @foreach($portraitChunks as $pageIndex => $pageData)
            <div class="page-info">Page {{ $pageIndex + 1 }} of {{ $portraitChunks->count() }}</div>
            <div class="page-preview portrait-page">
                <div class="barcode-container portrait-container">
                    @foreach($pageData as $produk)
                        <div class="barcode-item portrait-preview">
                            <div class="product-info">
                                <div class="product-name" title="{{ $produk->name }}">{{ Str::limit($produk->name, 12) }}</div>
                                <div class="product-sku">{{ Str::limit($produk->sku, 10) }}</div>
                            </div>
                            <div class="barcode-section">
                                @if($produk->barcode)
                                    {!! DNS1D::getBarcodeHTML($produk->barcode, 'C128', 0.6, 8) !!}
                                    <div class="barcode-code">{{ $produk->barcode }}</div>
                                @else
                                    <div style="font-size: 5pt; color: #999;">No Barcode</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>
