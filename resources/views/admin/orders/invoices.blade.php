<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice - {{ $order->code }}</title>
</head>
<body>
<style>
    @page {
        size: 80mm auto;
        margin: 5mm;
    }

    * {
        box-sizing: border-box;
        page-break-inside: avoid;
    }

    body {
        margin: 0;
        padding: 0;
        background: #fff;
        color: #000;
        font-family: 'Courier New', monospace;
        font-size: 10pt;
        line-height: 1.3;
        width: 80mm;
    }

    .receipt-container {
        width: 100%;
        padding: 2mm;
        min-height: auto;
    }

    .header {
        text-align: center;
        border-bottom: 2px dashed #000;
        padding-bottom: 3mm;
        margin-bottom: 3mm;
    }

    .store-name {
        font-size: 14pt;
        font-weight: bold;
        margin-bottom: 2mm;
    }

    .invoice-info {
        font-size: 10pt;
        margin-bottom: 1mm;
    }

    .order-details {
        margin-bottom: 3mm;
        font-size: 9pt;
        line-height: 1.4;
    }

    .customer-info {
        margin-bottom: 3mm;
        font-size: 9pt;
        border-bottom: 1px dashed #000;
        padding-bottom: 3mm;
        line-height: 1.4;
    }

    .customer-info div {
        word-wrap: break-word;
        overflow-wrap: break-word;
        hyphens: auto;
    }

    .items-section {
        margin-bottom: 3mm;
    }

    .item-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1mm;
        font-size: 9pt;
        align-items: flex-start;
    }

    .item-name {
        flex: 1;
        margin-right: 3mm;
        word-wrap: break-word;
        overflow-wrap: break-word;
        hyphens: auto;
        max-width: 50mm;
    }

    .item-qty-price {
        text-align: right;
        white-space: nowrap;
        min-width: 20mm;
    }

    .totals-section {
        border-top: 2px dashed #000;
        padding-top: 3mm;
        font-size: 9pt;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1mm;
    }

    .final-total {
        font-weight: bold;
        border-top: 2px solid #000;
        padding-top: 2mm;
        margin-top: 2mm;
        font-size: 11pt;
    }

    .footer {
        text-align: center;
        font-size: 8pt;
        margin-top: 5mm;
        border-top: 1px dashed #000;
        padding-top: 3mm;
    }

    .status {
        text-align: center;
        font-weight: bold;
        font-size: 10pt;
        margin: 1mm 0;
        padding: 2mm;
        border: 1px solid #000;
    }

    /* Ensure no page breaks */
    .receipt-container,
    .header,
    .order-details,
    .customer-info,
    .items-section,
    .totals-section,
    .footer {
        page-break-inside: avoid;
        break-inside: avoid;
    }

    @media print {
        body { 
            background: #fff !important;
            -webkit-print-color-adjust: exact;
        }
        
        * {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
    }
</style>

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
        @if ($order->orderItems->count() > 1)
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
        @else
            @php
                $singleItem = $order->orderItems[0];
            @endphp
            <div class="item-row">
                <div class="item-name">{{ $singleItem->name ?? $singleItem->product_name }}</div>
                <div class="item-qty-price">{{ $singleItem->qty }}x{{ number_format($singleItem->base_price ?? $singleItem->price, 0, ',', '.') }}</div>
            </div>
            <div class="item-row">
                <div class="item-name"></div>
                <div class="item-qty-price">{{ number_format($singleItem->sub_total ?? $singleItem->total, 0, ',', '.') }}</div>
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
        <div>Thank you for your business!</div>
        <div>{{ config('app.name') }}</div>
    </div>
</div>

</body>
</html>