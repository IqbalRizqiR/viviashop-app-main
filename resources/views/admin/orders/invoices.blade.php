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
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    .invoice-container {
        max-width: 800px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        font-family: 'Inter', sans-serif;
    }

    .invoice-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px;
        position: relative;
    }

    .invoice-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(30px, -30px);
    }

    .company-info {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 30px;
    }

    .company-logo {
        font-size: 28px;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    .invoice-title {
        text-align: right;
    }

    .invoice-title h1 {
        font-size: 36px;
        font-weight: 300;
        margin: 0;
        letter-spacing: -1px;
    }

    .invoice-number {
        font-size: 14px;
        opacity: 0.9;
        margin-top: 5px;
    }

    .invoice-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        margin-top: 30px;
    }

    .invoice-body {
        padding: 40px;
    }

    .bill-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        margin-bottom: 40px;
    }

    .bill-to, .bill-from {
        background: #f8fafc;
        padding: 25px;
        border-radius: 8px;
        border-left: 4px solid #667eea;
    }

    .bill-to h3, .bill-from h3 {
        margin: 0 0 15px 0;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #667eea;
    }

    .bill-to p, .bill-from p {
        margin: 5px 0;
        color: #64748b;
        line-height: 1.6;
    }

    .invoice-table {
        width: 100%;
        border-collapse: collapse;
        margin: 30px 0;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    .invoice-table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .invoice-table th {
        padding: 20px 15px;
        text-align: left;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .invoice-table td {
        padding: 18px 15px;
        border-bottom: 1px solid #e2e8f0;
        color: #475569;
    }

    .invoice-table tbody tr:hover {
        background: #f8fafc;
        transition: background 0.3s ease;
    }

    .invoice-table .text-right {
        text-align: right;
    }

    .invoice-table .item-description {
        font-weight: 500;
        color: #1e293b;
    }

    .invoice-totals {
        margin-top: 30px;
        display: flex;
        justify-content: flex-end;
    }

    .totals-section {
        min-width: 300px;
        background: #f8fafc;
        border-radius: 8px;
        padding: 25px;
        border: 1px solid #e2e8f0;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        padding-bottom: 8px;
    }

    .total-row:not(.final-total) {
        border-bottom: 1px solid #e2e8f0;
    }

    .final-total {
        border-top: 2px solid #667eea;
        padding-top: 15px;
        margin-top: 15px;
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
    }

    .invoice-footer {
        background: #f1f5f9;
        padding: 30px 40px;
        border-top: 1px solid #e2e8f0;
    }

    .footer-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
    }

    .payment-terms h4, .notes h4 {
        margin: 0 0 10px 0;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .payment-terms p, .notes p {
        color: #6b7280;
        line-height: 1.6;
        font-size: 14px;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-paid {
        background: #dcfce7;
        text-transform: uppercase
        color: #166534;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-overdue {
        background: #fee2e2;
        color: #991b1b;
    }

    @media print {
        body {
            background: white !important;
        }

        .invoice-container {
            box-shadow: none;
            border-radius: 0;
        }

        .no-print {
            display: none !important;
        }
    }

    @media (max-width: 768px) {
        .invoice-container {
            margin: 10px;
            border-radius: 8px;
        }

        .invoice-header {
            padding: 25px;
        }

        .invoice-body {
            padding: 25px;
        }

        .company-info {
            flex-direction: column;
            gap: 20px;
        }

        .invoice-title {
            text-align: left;
        }

        .invoice-details,
        .bill-info,
        .footer-info {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .invoice-table {
            font-size: 14px;
        }

        .invoice-table th,
        .invoice-table td {
            padding: 12px 8px;
        }
    }

    /* Animation for smooth transitions */
    .invoice-container {
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    </style>
<div class="invoice-container">
    <!-- Header -->
    <div class="invoice-header">
        <div class="company-info">
            <div class="company-logo">
                VIVIA STORE
            </div>
            <div class="invoice-title">
                <h1>INVOICE</h1>
                <div class="invoice-number">{{ $order->code }}</div>
                <span class="status-badge status-paid">{{ $order->payment_status }}</span>
            </div>
        </div>

        <div class="invoice-details">
            <div>
                <p><strong>Issue Date:</strong> {{ $order->order_date }}</p>
            </div>
            <div style="text-align: right;">
                <p><strong>Payment Method:</strong> {{ $order->payment_method }}</p>
            </div>
        </div>
    </div>

    <!-- Body -->
    <div class="invoice-body">
        <div class="bill-info">
            <div class="bill-from">
                <h3>From</h3>
                <p><strong>{{ $order->customer_full_name }}</strong></p>
                <p>{{ $order->customer_address1 }}</p>
                <p>Phone: {{ $order->customer_phone }}</p>
                <p>Email: {{ $order->customer_email }}</p>
            </div>

            {{-- <div class="bill-to">
                <h3>Bill To</h3>
                <p><strong>John Doe</strong></p>
                <p>Jl. Customer Street 456</p>
                <p>Surabaya, Jawa Timur 60111</p>
                <p>Phone: +62 987 654 321</p>
                <p>Email: john@example.com</p>
            </div> --}}
        </div>

        <!-- Items Table -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderItems as $item)
                    <tr>
                        <td class="">
                            <div class="item-description">
                                {{ $item->product_name }}
                            </div>
                        </td>
                        <td class="text-right">{{ $item->qty }}</td>
                        <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="invoice-totals">
            <div class="totals-section">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>Rp {{ number_format($order->base_total_price,0,",",".") }}</span>
                </div>
                <div class="total-row">
                    <span>Tax (10%):</span>
                    <span>Rp {{ number_format($order->tax_amount,0,",",".") }}</span>
                </div>
                <div class="total-row">
                    <span>Shipping:</span>
                    <span>Rp {{ number_format($order->shipping_cost,0,",",".") }}</span>
                </div>
                <div class="total-row">
                    <span>Unique Code:</span>
                    <span>Rp {{ number_format(($order->grand_total - ($order->base_total_price + $order->shipping_cost)),0,",",".") }}</span>
                </div>
                <div class="total-row final-total">
                    <span>Total:</span>
                    <span>Rp {{ number_format($order->grand_total,0,",",".") }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="invoice-footer">
        <div class="footer-info">
            <div class="notes">
                <h4>Notes</h4>
                <p>Thank you for your business! If you have any questions, please contact us.</p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
