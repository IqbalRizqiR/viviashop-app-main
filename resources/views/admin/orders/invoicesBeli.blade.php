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
    size: A4 portrait;
    margin: 10mm;            /* small page margins */
}

body {
    margin: 0;
    padding: 0;
    background: #fff;
    color: #222;             /* dark text for high contrast */
    font-family: Arial, sans-serif;
    font-size: 12pt;
    line-height: 1.4;
}

.invoice-container {
    width: 100%;
    margin: 0 auto;
    padding: 5mm;            /* reduce padding to fit more */
    box-sizing: border-box;
}

.invoice-header {
    background: #f0f0f0;      /* very light gray */
    padding: 8mm 5mm;
    border-bottom: 1px solid #ccc;
}

.company-logo {
    font-size: 18pt;
    font-weight: bold;
    color: #111;
}

.invoice-title h1 {
    margin: 0;
    font-size: 24pt;
    font-weight: normal;
    color: #111;
}

.invoice-number {
    font-size: 10pt;
    color: #555;
    margin-top: 2mm;
}

.invoice-details {
    margin-top: 5mm;
    display: flex;
    justify-content: space-between;
    font-size: 10pt;
    color: #333;
}

.invoice-body {
    padding: 5mm 0;
}

.bill-info {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5mm;
    font-size: 10pt;
}

.bill-from, .bill-to {
    width: 48%;
    background: #fafafa;
    padding: 4mm;
    border: 1px solid #ddd;
    box-sizing: border-box;
}

.bill-from h3, .bill-to h3 {
    margin: 0 0 2mm;
    font-size: 12pt;
    color: #333;
    border-bottom: 1px solid #ccc;
    padding-bottom: 2mm;
}

.invoice-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 5mm;
    font-size: 10pt;
}

.invoice-table thead th {
    background: #e8e8e8;
    color: #111;
    padding: 4mm 2mm;
    border: 1px solid #ccc;
    text-align: left;
}

.invoice-table tbody td {
    padding: 3mm 2mm;
    border: 1px solid #ddd;
    color: #222;
}

.invoice-table tbody tr:nth-child(even) {
    background: #fafafa;
}

.invoice-totals {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 5mm;
}

.totals-section {
    width: 40%;
    background: #fafafa;
    padding: 4mm;
    border: 1px solid #ddd;
    font-size: 10pt;
    box-sizing: border-box;
}

.total-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 2mm;
}

.total-row.final-total {
    font-size: 12pt;
    font-weight: bold;
    border-top: 1px solid #ccc;
    padding-top: 2mm;
}

.invoice-footer {
    border-top: 1px solid #ccc;
    padding-top: 3mm;
    font-size: 10pt;
    color: #555;
}

.status-paid {
    font-size: 30px;
}

@media print {
    body {
        background: #fff !important;
    }
    .invoice-container {
        padding: 0;
        box-shadow: none;
    }
    .no-print {
        display: none;
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
            </div>
        </div>

        <div class="invoice-details">
            <div>
                <p><strong>Issue Date:</strong> {{ $pembelian->waktu }}</p>
            </div>
        </div>
    </div>

    <!-- Body -->
    <div class="invoice-body">
        <div class="bill-info">
            <div class="bill-from">
                <h3>From</h3>
                <p><strong>{{ $pembelian->supplier->nama }}</strong></p>
                <p>{{ $pembelian->supplier->alamat }}</p>
                <p>Phone: {{ $pembelian->supplier->telepon }}</p>
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
                @if ($pembelian_detail->count() > 1)
                    @foreach ($pembelian_detail as $item)
                        <tr>
                            <td class="">
                                <div class="item-description">
                                    {{ $item->products->name }}
                                </div>
                            </td>
                            <td class="text-right">{{ $item->jumlah }}</td>
                            <td class="text-right">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="">
                            <div class="item-description">
                                {{ $pembelian_detail[0]->products->name }}
                            </div>
                        </td>
                        <td class="text-right">{{ $pembelian_detail[0]->jumlah }}</td>
                        <td class="text-right">Rp {{ number_format($pembelian_detail[0]->harga_beli, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($pembelian_detail[0]->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Totals -->
        <div class="invoice-totals">
            <div class="totals-section">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>Rp {{ number_format($pembelian->total_harga) }}</span>
                </div>
                <div class="total-row">
                    <span>Total Item:</span>
                    <span>Rp {{ number_format($pembelian->total_item) }}</span>
                </div>
                <div class="total-row final-total">
                    <span>Total Bayar:</span>
                    <span>Rp {{ number_format($pembelian->bayar) }}</span>
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
