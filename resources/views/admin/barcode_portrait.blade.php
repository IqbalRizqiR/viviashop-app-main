<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Barcode - Portrait</title>
</head>
<body>
    <style>
        @page {
            margin: 5mm;
            size: A4 portrait;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 10px;
        }

        .barcode-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            align-content: flex-start;
            width: 100%;
            height: calc(100vh - 10mm);
            max-width: 100%;
            gap: 1mm;
            box-sizing: border-box;
        }

        .barcode-item {
            border: 1px solid #333;
            margin: 0;
            padding: 1mm;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            page-break-inside: avoid;
            box-sizing: border-box;
            background: white;
            width: calc(25% - 1mm);
            height: calc(12.5% - 1mm);
            max-height: 25mm;
            min-height: 25mm;
        }

        .product-info {
            font-size: 6pt;
            line-height: 1.1;
            margin-bottom: 1mm;
        }

        .product-name {
            font-weight: bold;
            font-size: 7pt;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 0.5mm;
        }

        .product-sku {
            font-size: 5pt;
            color: #666;
            margin-bottom: 0.5mm;
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
            margin-top: 0.5mm;
        }

        .page-break {
            page-break-before: always;
            width: 100%;
            height: 0;
            margin: 0;
            padding: 0;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>

    <div class="barcode-container">
        @foreach ($data as $index => $produk)
            @if($index > 0 && $index % 32 == 0)
                </div>
                <div class="page-break"></div>
                <div class="barcode-container">
            @endif
            
            <div class="barcode-item">
                <div class="product-info">
                    <div class="product-name" title="{{ $produk->name }}">{{ Str::limit($produk->name, 12) }}</div>
                    <div class="product-sku">{{ Str::limit($produk->sku, 10) }}</div>
                </div>
                <div class="barcode-section">
                    @if($produk->barcode)
                        {!! DNS1D::getBarcodeHTML($produk->barcode, 'C128', 0.9, 12) !!}
                        <div class="barcode-code">{{ $produk->barcode }}</div>
                    @else
                        <div style="font-size: 6pt; color: #999;">No Barcode</div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
