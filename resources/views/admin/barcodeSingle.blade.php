<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Barcode - {{ $dataSingle->name }}</title>
</head>
<body>
    <style>
        @page {
            margin: 10mm;
            size: A4 landscape;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .barcode-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            align-items: flex-start;
            gap: 5mm;
            padding: 10mm;
        }

        .barcode-item {
            width: 60mm;
            height: 35mm;
            border: 2px solid #333;
            padding: 3mm;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            page-break-inside: avoid;
            box-sizing: border-box;
            background: white;
        }

        .product-info {
            margin-bottom: 2mm;
        }

        .product-name {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 1mm;
            line-height: 1.2;
        }

        .product-sku {
            font-size: 8pt;
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
            font-size: 8pt;
            letter-spacing: 2px;
            margin-top: 2mm;
            font-weight: bold;
        }
    </style>

    <div class="barcode-container">
        <div class="barcode-item">
            <div class="product-info">
                <div class="product-name">{{ $dataSingle->name }}</div>
                <div class="product-sku">SKU: {{ $dataSingle->sku }}</div>
            </div>
            <div class="barcode-section">
                @if($dataSingle->barcode)
                    {!! DNS1D::getBarcodeHTML($dataSingle->barcode, 'C128', 1.5, 25) !!}
                    <div class="barcode-code">{{ $dataSingle->barcode }}</div>
                @else
                    <div style="font-size: 12pt; color: #999;">Barcode belum dibuat</div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
