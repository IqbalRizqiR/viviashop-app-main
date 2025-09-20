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
        }

        body {
            margin: 0;
            padding: 0;
            background: #fff;
            color: #000;
            font-family: 'Courier New', monospace;
            font-size: 8pt;
            line-height: 1.2;
            width: 76mm;              /* lebih kecil dari 80mm supaya aman */
            margin-left: auto;
            margin-right: auto;
        }

        .receipt-container {
            width: 80mm;
            
            
        }

        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 2mm;
            margin-bottom: 2mm;
        }

        .store-name {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 1mm;
        }

        .invoice-info {
            font-size: 8pt;
            margin-bottom: 1mm;
        }

        .status {
            text-align: center;
            font-weight: bold;
            font-size: 8pt;
            margin: 1mm 0;
            padding: 1mm;
            border: 1px solid #000;
        }

        .order-details,
        .customer-info,
        .items-section,
        .totals-section,
        .footer {
            font-size: 7pt;
            line-height: 1.3;
        }

        .customer-info {
            margin-bottom: 2mm;
            border-bottom: 1px dashed #000;
            padding-bottom: 2mm;
        }

        .items-section {
            margin-bottom: 2mm;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1mm;
        }

        .item-name {
            flex: 1;
            margin-right: 2mm;
            word-wrap: break-word;
            overflow-wrap: break-word;
            max-width: 50mm;
        }

        .item-qty-price {
            text-align: right;
            white-space: nowrap;
            min-width: 20mm;
        }

        .totals-section {
            border-top: 1px dashed #000;
            padding-top: 2mm;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1mm;
        }

        .final-total {
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 1mm;
            margin-top: 1mm;
            font-size: 9pt;
        }

        .footer {
            text-align: center;
            margin-top: 3mm;
            border-top: 1px dashed #000;
            padding-top: 2mm;
            font-size: 6pt;
        }
    </style>
</head>
<body>
<div class="receipt-container">
    <!-- Header -->
    <div class="header">
        <div class="store-name">VIVIA STORE</div>
        <div class="invoice-info">INVOICE</div>
        <div class="invoice-info">{{ $order->code }}</div>
        <div class="status">{{ strtoupper($order->payment_status) }}</div>
    </div>

    <!-- Order Details -->
    <div class="order-details">
        <div>Date: {{ date('d/m/Y H:i', strtotime($order->order_date)) }}</div>
        <div>Payment: {{ $order->payment_method }}</div>
    </div>

    <!-- Customer Info -->
    <div class="customer-info">
        <div><strong>{{ $order->customer_full_name }}</strong></div>
        <div>{{ $order->customer_phone }}</div>
        <div>{{ $order->customer_address1 }}</div>
    </div>

    <!-- Items -->
    <div class="items-section">
        @foreach ($order->orderItems as $item)
            <div class="item-row">
                <div class="item-name">{{ $item->product_name }}</div>
                <div class="item-qty-price">{{ $item->qty }}x{{ number_format($item->price, 0, ',', '.') }}</div>
            </div>
            <div class="item-row">
                <div class="item-name"></div>
                <div class="item-qty-price">{{ number_format($item->total, 0, ',', '.') }}</div>
            </div>
        @endforeach
    </div>

    <!-- Totals -->
    <div class="totals-section">
        <div class="total-row">
            <span>Subtotal:</span>
            <span>{{ number_format($order->base_total_price, 0, ',', '.') }}</span>
        </div>
        @if($order->tax_amount > 0)
        <div class="total-row">
            <span>Tax:</span>
            <span>{{ number_format($order->tax_amount, 0, ',', '.') }}</span>
        </div>
        @endif
        @if($order->shipping_cost > 0)
        <div class="total-row">
            <span>Shipping:</span>
            <span>{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
        </div>
        @endif
        <div class="total-row final-total">
            <span>TOTAL:</span>
            <span>{{ number_format($order->grand_total, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div>Thank you for your business!</div>
        <div>{{ config('app.name') }}</div>
    </div>
</div>
</body>
</html>
