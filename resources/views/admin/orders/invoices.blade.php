<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->code }}</title>
    <style>
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
    </style>
</head>
<body>
<div class="receipt-container">
    <!-- Header -->
    <div class="header">
        <div class="store-name">VIVIA STORE</div>
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

    <!-- Customer Info -->
    <div class="section customer-info">
        <div class="customer-name">{{ $order->customer_full_name }}</div>
        <div>{{ $order->customer_phone }}</div>
        <div>{{ $order->customer_address1 }}</div>
    </div>

    <div class="divider"></div>

    <!-- Items -->
    <div class="section">
        <div class="items-header">ITEMS</div>
        
        @foreach ($order->orderItems as $item)
            <div class="item">
                <div class="item-name">{{ $item->product_name }}</div>
                <div class="item-details">
                    <div>{{ $item->qty }} x {{ number_format($item->price, 0, ',', '.') }}</div>
                </div>
                <div class="item-total">
                    {{ number_format($item->total, 0, ',', '.') }}
                </div>
            </div>
        @endforeach
    </div>

    <!-- Totals -->
    <div class="totals">
        <div class="total-line">
            <span>Subtotal:</span>
            <span>{{ number_format($order->base_total_price, 0, ',', '.') }}</span>
        </div>
        
        @if($order->tax_amount > 0)
        <div class="total-line">
            <span>Tax:</span>
            <span>{{ number_format($order->tax_amount, 0, ',', '.') }}</span>
        </div>
        @endif
        
        @if($order->shipping_cost > 0)
        <div class="total-line">
            <span>Shipping:</span>
            <span>{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
        </div>
        @endif
        
        <div class="total-line grand-total">
            <span>TOTAL:</span>
            <span>{{ number_format($order->grand_total, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-line">Thank you for your business!</div>
        <div class="footer-line">{{ config('app.name') }}</div>
    </div>
</div>
</body>
</html>