<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
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
        }

        .barcode-item {
            width: 60mm;
            height: 30mm;
            border: 1px solid #ddd;
            padding: 2mm;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            page-break-inside: avoid;
            box-sizing: border-box;
        }

        .barcode-text {
            font-size: 8pt;
            font-weight: bold;
            margin-bottom: 2mm;
        }

        .barcode-code {
            font-family: 'Courier New', monospace;
            font-size: 10pt;
            letter-spacing: 2px;
            margin-top: 2mm;
        }

        .product-name {
            font-size: 7pt;
            margin-top: 1mm;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* 4 items per row in landscape */
        .barcode-item:nth-child(4n+1) {
            clear: left;
        }
    </style>

    <div class="barcode-container">
        @foreach ($data as $produk)
            <div class="barcode-item">
                {{-- Generate barcode using a barcode library --}}
                <div style="margin: 2mm 0;">
                    {!! DNS1D::getBarcodeHTML($produk->barcode, 'C128', 1.5, 20) !!}
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>
