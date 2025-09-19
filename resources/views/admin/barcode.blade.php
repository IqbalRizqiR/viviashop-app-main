<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>All Products Barcode</title>
</head>
<body>
    <style>
        @page {
            margin: 5mm;
            size: A4;
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
            height: 100vh;
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
            font-size: 5pt;
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

        @media print and (orientation: landscape) {
            @page {
                margin: 5mm;
                size: A4 landscape;
            }
            
            .barcode-container {
                height: calc(100vh - 10mm);
                max-height: calc(100vh - 10mm);
            }
            
            .barcode-item {
                width: calc(20% - 1mm);
                height: calc(20% - 1mm);
                max-height: 30mm;
                min-height: 30mm;
            }
        }

        @media print and (orientation: portrait) {
            @page {
                margin: 5mm;
                size: A4 portrait;
            }
            
            .barcode-container {
                height: calc(100vh - 10mm);
                max-height: calc(100vh - 10mm);
            }
            
            .barcode-item {
                width: calc(25% - 1mm);
                height: calc(12.5% - 1mm);
                max-height: 25mm;
                min-height: 25mm;
            }
        }

        @media screen {
            .barcode-item {
                width: calc(20% - 1mm);
                height: 30mm;
            }
        }
    </style>
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
            font-size: 5pt;
            letter-spacing: 0.5px;
            margin-top: 0.5mm;
        }

        .page-break {
            page-break-before: always;
            width: 100%;
        }

        @media screen {
            .landscape-layout .barcode-item {
                width: calc(20% - 3mm);
            }
            
            .portrait-layout .barcode-item {
                width: calc(25% - 3mm);
            }
        }
    </style>

    <div class="barcode-container">
        @foreach ($data as $index => $produk)
            @if($index > 0 && $index % 25 == 0)
                </div>
                <div class="page-break"></div>
                <div class="barcode-container">
            @endif
            
            <div class="barcode-item">
                <div class="product-info">
                    <div class="product-name" title="{{ $produk->name }}">{{ Str::limit($produk->name, 15) }}</div>
                    <div class="product-sku">{{ Str::limit($produk->sku, 12) }}</div>
                </div>
                <div class="barcode-section">
                    @if($produk->barcode)
                        {!! DNS1D::getBarcodeHTML($produk->barcode, 'C128', 0.8, 8) !!}
                        <div class="barcode-code">{{ $produk->barcode }}</div>
                    @else
                        <div style="font-size: 6pt; color: #999;">No Barcode</div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <script>
        window.addEventListener('beforeprint', function() {
            const containers = document.querySelectorAll('.barcode-container');
            const items = document.querySelectorAll('.barcode-item');
            const pageBreaks = document.querySelectorAll('.page-break');
            
            const isLandscape = window.matchMedia('(orientation: landscape)').matches;
            
            if (isLandscape) {
                let itemCount = 0;
                let containerIndex = 0;
                
                containers.forEach((container, idx) => {
                    container.style.display = 'flex';
                    container.style.height = 'calc(100vh - 10mm)';
                    container.style.maxHeight = 'calc(100vh - 10mm)';
                });
                
                items.forEach((item, index) => {
                    const containerIdx = Math.floor(index / 25);
                    const itemIdx = index % 25;
                    
                    if (itemIdx < 25) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
                
                pageBreaks.forEach((pageBreak, index) => {
                    if ((index + 1) * 25 < items.length) {
                        pageBreak.style.display = 'block';
                    } else {
                        pageBreak.style.display = 'none';
                    }
                });
                
            } else {
                let currentContainer = containers[0];
                let currentIndex = 0;
                let itemsInContainer = 0;
                
                containers.forEach(container => {
                    container.style.display = 'none';
                });
                
                pageBreaks.forEach(pageBreak => {
                    pageBreak.style.display = 'none';
                });
                
                if (containers[0]) {
                    containers[0].style.display = 'flex';
                    containers[0].style.height = 'calc(100vh - 10mm)';
                    containers[0].style.maxHeight = 'calc(100vh - 10mm)';
                }
                
                items.forEach((item, index) => {
                    if (index > 0 && index % 32 === 0) {
                        currentIndex++;
                        itemsInContainer = 0;
                        
                        if (containers[currentIndex]) {
                            containers[currentIndex].style.display = 'flex';
                            containers[currentIndex].style.height = 'calc(100vh - 10mm)';
                            containers[currentIndex].style.maxHeight = 'calc(100vh - 10mm)';
                        }
                        
                        if (pageBreaks[currentIndex - 1]) {
                            pageBreaks[currentIndex - 1].style.display = 'block';
                        }
                    }
                    
                    if (itemsInContainer < 32) {
                        item.style.display = 'flex';
                        itemsInContainer++;
                    } else {
                        item.style.display = 'none';
                    }
                });
            }
        });
    </script>
</body>
</html>
