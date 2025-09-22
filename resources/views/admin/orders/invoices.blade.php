<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->code }}</title>
    {{-- <style>
        @page {
            size: 80mm auto;
            margin: 0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            margin: 0;
            padding: 0;
            background: #fff;
            color: #000;
            font-family: 'Courier New', monospace;
            font-size: 9pt;
            line-height: 1.2;
            width: 80mm;
        }

        .receipt-container {
            width: 100%;
            max-width: 72mm; /* Lebih kecil untuk margin yang aman */
            margin: 0 auto;
            padding: 3mm 4mm; /* Padding kiri-kanan yang lebih besar */
        }

        .header {
            text-align: center;
            margin-bottom: 3mm;
        }

        .store-name {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 1mm;
        }

        .invoice-title {
            font-size: 10pt;
            margin-bottom: 1mm;
        }

        .invoice-code {
            font-size: 9pt;
            margin-bottom: 2mm;
        }

        .status {
            text-align: center;
            font-weight: bold;
            font-size: 10pt;
            margin: 2mm 0;
            padding: 2mm;
            border: 1px solid #000;
        }

        .divider {
            border-bottom: 1px dashed #000;
            margin: 2mm 0;
            width: 100%;
        }

        .section {
            margin-bottom: 3mm;
            font-size: 8pt;
            width: 100%;
        }

        .order-info {
            margin-bottom: 2mm;
        }

        .customer-info {
            margin-bottom: 2mm;
        }

        .customer-name {
            font-weight: bold;
            margin-bottom: 1mm;
        }

        .items-header {
            font-weight: bold;
            margin-bottom: 2mm;
            text-align: center;
        }

        .item {
            margin-bottom: 2mm;
            width: 100%;
        }

        .item-name {
            font-weight: bold;
            margin-bottom: 1mm;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .item-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .item-qty-price {
            text-align: right;
            white-space: nowrap;
        }

        .item-total {
            text-align: right;
            font-weight: bold;
            margin-top: 1mm;
        }

        .totals {
            margin-top: 3mm;
            border-top: 1px dashed #000;
            padding-top: 2mm;
            width: 100%;
        }

        .total-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1mm;
            width: 100%;
        }

        .total-line span {
            display: inline-block;
        }

        .total-line span:first-child {
            flex: 1;
            text-align: left;
        }

        .total-line span:last-child {
            text-align: right;
            white-space: nowrap;
        }

        .grand-total {
            border-top: 1px solid #000;
            padding-top: 2mm;
            margin-top: 2mm;
            font-weight: bold;
            font-size: 10pt;
        }

        .footer {
            text-align: center;
            margin-top: 4mm;
            border-top: 1px dashed #000;
            padding-top: 2mm;
            font-size: 7pt;
        }

        .footer-line {
            margin-bottom: 1mm;
        }

        /* Khusus untuk printer thermal - margin ekstra */
        .safe-margin {
            margin-left: 2mm;
            margin-right: 2mm;
        }

        /* Print styles */
        @media print {
            body {
                margin: 0 !important;
                padding: 0 !important;
            }
            
            .receipt-container {
                margin: 0 auto !important;
                padding: 2mm 5mm !important; /* Padding kiri yang lebih besar untuk print */
            }
        }
    </style> --}}
    <style>
        @page {
            size: auto;
            margin: 0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            margin: 0;
            padding: 0;
            background: #fff;
            color: #000;
            font-family: 'Courier New', monospace;
            line-height: 1.2;
        }

        .receipt-container {
            margin: 0 auto;
            padding: 2mm 3mm;
        }

        /* Default untuk 80mm */
        .receipt-container {
            width: 80mm;
            max-width: 72mm;
            font-size: 9pt;
        }

        /* Auto-detect untuk 58mm */
        @media (max-width: 60mm) {
            .receipt-container {
                width: 58mm;
                max-width: 52mm;
                font-size: 7pt;
                padding: 2mm;
            }
            
            .store-name {
                font-size: 10pt !important;
            }
            
            .invoice-title {
                font-size: 8pt !important;
            }
            
            .status {
                font-size: 8pt !important;
                padding: 1mm !important;
            }
            
            .section {
                font-size: 6pt !important;
            }
            
            .grand-total {
                font-size: 8pt !important;
            }
        }

        /* Print styles untuk 58mm */
        @media print and (max-width: 60mm) {
            .receipt-container {
                padding: 1mm 2mm !important;
                max-width: 50mm !important;
            }
        }

        /* Print styles untuk 80mm */
        @media print and (min-width: 61mm) {
            .receipt-container {
                padding: 2mm 4mm !important;
                max-width: 70mm !important;
            }
        }

        .header {
            text-align: center;
            margin-bottom: 3mm;
        }

        .store-name {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 1mm;
        }

        .invoice-title {
            font-size: 10pt;
            margin-bottom: 1mm;
        }

        .invoice-code {
            font-size: 9pt;
            margin-bottom: 2mm;
        }

        .status {
            text-align: center;
            font-weight: bold;
            font-size: 10pt;
            margin: 2mm 0;
            padding: 2mm;
            border: 1px solid #000;
        }

        .divider {
            border-bottom: 1px dashed #000;
            margin: 2mm 0;
            width: 100%;
        }

        .section {
            margin-bottom: 3mm;
            font-size: 8pt;
            width: 100%;
        }

        .customer-name {
            font-weight: bold;
            margin-bottom: 1mm;
        }

        .items-header {
            font-weight: bold;
            margin-bottom: 2mm;
            text-align: center;
        }

        .item {
            margin-bottom: 2mm;
            width: 100%;
        }

        .item-name {
            font-weight: bold;
            margin-bottom: 1mm;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .item-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .item-total {
            text-align: right;
            font-weight: bold;
            margin-top: 1mm;
        }

        .totals {
            margin-top: 3mm;
            border-top: 1px dashed #000;
            padding-top: 2mm;
            width: 100%;
        }

        .total-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1mm;
            width: 100%;
        }

        .grand-total {
            border-top: 1px solid #000;
            padding-top: 2mm;
            margin-top: 2mm;
            font-weight: bold;
            font-size: 10pt;
        }

        .footer {
            text-align: center;
            margin-top: 4mm;
            border-top: 1px dashed #000;
            padding-top: 2mm;
            font-size: 7pt;
        }

        .footer-line {
            margin-bottom: 1mm;
        }
    </style>
</head>
<body>
<div class="receipt-container">
    <!-- Header -->
    <div class="header">
        <div class="store-name">VIVIA PRINTSHOP</div>
        <div class="invoice-title">INVOICE</div>
        <div class="invoice-code">{{ $order->code }}</div>
        <div class="status">{{ strtoupper($order->payment_status) }}</div>
    </div>

    <div class="divider"></div>

    <!-- Order Info -->
    <div class="section order-info">
        <div>Date: {{ date('d/m/Y H:i', strtotime($order->order_date)) }}</div>
        <div>Payment: {{ $order->payment_method }}</div>
    </div>
    

    <div class="divider"></div>

    <!-- Items -->
    <div class="section">
        <div class="items-header">ITEMS</div>

        @if(isset($order->orderItems) && $order->orderItems->count() > 1)
            @foreach($order->orderItems as $item)
                <div class="item-row">
                    <div class="item-name">
                        <strong style="display:block; text-transform:uppercase;">{{ $item->product_name ?? $item->name }}</strong>
                        <small style="color:#555; display:block; margin-top:4px;">
                            {{ $item->qty }} x Rp {{ number_format($item->price ?? ($item->base_price ?? 0), 0, ',', '.') }}
                        </small>
                    </div>
                    <div class="item-qty-price">{{ $item->qty }}</div>
                    <div class="item-qty-price">Rp {{ number_format($item->total ?? ($item->qty * ($item->price ?? ($item->base_price ?? 0))), 0, ',', '.') }}</div>
                </div>
            @endforeach
        @elseif(isset($order->orderItems) && $order->orderItems->count() == 1)
            @php $singleItem = $order->orderItems->first(); @endphp
            <div class="item-row">
                <div class="item-name">
                    <strong style="display:block; text-transform:uppercase;">{{ $singleItem->name ?? $singleItem->product_name }}</strong>
                    <small style="color:#555; display:block; margin-top:4px;">
                        {{ $singleItem->qty ?? 0 }} x Rp {{ number_format($singleItem->base_price ?? $singleItem->price ?? 0, 0, ',', '.') }}
                    </small>
                </div>
                <div class="item-qty-price">{{ $singleItem->qty ?? 0 }}</div>
                <div class="item-qty-price">Rp {{ number_format($singleItem->sub_total ?? $singleItem->total ?? (($singleItem->qty ?? 0) * ($singleItem->base_price ?? $singleItem->price ?? 0)), 0, ',', '.') }}</div>
            </div>
        @else
            <div class="item-row">
                <div class="item-name">No items</div>
                <div class="item-qty-price">-</div>
                <div class="item-qty-price">-</div>
            </div>
        @endif
    </div>

    <!-- Totals -->
    <div class="totals">
        <div class="total-line">
            <span>Subtotal:</span>
            <span>{{ number_format($order->base_total_price, 0, ',', '.') }} Rp</span>
        </div>
        
        @if($order->tax_amount > 0)
        <div class="total-line">
            <span>Tax:</span>
            <span>{{ number_format($order->tax_amount, 0, ',', '.') }} Rp</span>
        </div>
        @endif
        
        @if($order->shipping_cost > 0)
        <div class="total-line">
            <span>Shipping:</span>
            <span>{{ number_format($order->shipping_cost, 0, ',', '.') }} Rp</span>
        </div>
        @endif
        
        <div class="total-line grand-total">
            <span>TOTAL:</span>
            <span>{{ number_format($order->grand_total, 0, ',', '.') }} Rp</span>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-line">Terima kasih atas kepercayaan Anda!</div>
        <div class="footer-line">Hormat kami, Vivia PrintShop</div>
    </div>
</div>
</body>
</html>