<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice - {{ $order->code }}</title>
    <style>
        @page {
            size: 58mm auto; /* Lebar fix 58mm, tinggi menyesuaikan isi */
            margin: 2mm;
        }

        html, body {
            margin: 0;
            padding: 0;
            background: #fff;
            color: #000;
            font-family: 'Courier New', monospace;
            font-size: 8pt;
            line-height: 1.2;
            width: 58mm;
            max-width: 58mm;
        }

        .receipt-container {
            width: 58mm;
            max-width: 58mm;
            padding: 1mm;
            box-sizing: border-box;
            page-break-before: avoid;
            page-break-after: avoid;
            page-break-inside: avoid;
        }

        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 2mm;
            margin-bottom: 2mm;
        }

        .store-name {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 1mm;
        }

        .invoice-info {
            font-size: 7pt;
            margin-bottom: 1mm;
        }

        .status {
            text-align: center;
            font-weight: bold;
            font-size: 8pt;
            margin: 1mm 0;
        }

        .order-details {
            margin-bottom: 2mm;
            font-size: 7pt;
        }

        .customer-info {
            margin-bottom: 2mm;
            font-size: 7pt;
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
            font-size: 7pt;
            page-break-inside: avoid; /* jangan split item */
        }

        .item-name {
            flex: 1;
            margin-right: 2mm;
        }

        .item-qty-price {
            text-align: right;
            white-space: nowrap;
        }

        .totals-section {
            border-top: 1px dashed #000;
            padding-top: 2mm;
            font-size: 7pt;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1mm;
            page-break-inside: avoid;
        }

        .final-total {
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 1mm;
            margin-top: 1mm;
        }

        .footer {
            text-align: center;
            font-size: 6pt;
            margin-top: 2mm;
            border-top: 1px dashed #000;
            padding-top: 2mm;
        }

        @media print {
            body { background: #fff !important; }
            .receipt-container { padding: 0; }
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
        <div>{{ Str::limit($order->customer_address1, 25) }}</div>
    </div>

    <!-- Items -->
    <div class="items-section">
        @if ($order->orderItems->count() > 1)
            @foreach ($order->orderItems as $item)
                <div class="item-row">
                    <div class="item-name">{{ Str::limit($item->product_name, 15) }}</div>
                    <div class="item-qty-price">{{ $item->qty }}x{{ number_format($item->price, 0, ',', '.') }}</div>
                </div>
                <div class="item-row">
                    <div class="item-name"></div>
                    <div class="item-qty-price">{{ number_format($item->total, 0, ',', '.') }}</div>
                </div>
            @endforeach
        @else
            <div class="item-row">
                <div class="item-name">{{ Str::limit($order->orderItems[0]->name ?? $order->orderItems[0]->product_name, 15) }}</div>
                <div class="item-qty-price">{{ $order->orderItems[0]->qty }}x{{ number_format($order->orderItems[0]->base_price ?? $order->orderItems[0]->price, 0, ',', '.') }}</div>
            </div>
            <div class="item-row">
                <div class="item-name"></div>
                <div class="item-qty-price">{{ number_format($order->orderItems[0]->sub_total ?? $order->orderItems[0]->total, 0, ',', '.') }}</div>
            </div>
        @endif
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
        <div>Thank you!</div>
    </div>
</div>
</body>
</html>
