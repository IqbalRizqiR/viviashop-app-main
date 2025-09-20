<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Courier New', monospace;
            font-size: 8pt;
            line-height: 1.2;
            width: 100%; /* full 80mm */
        }

        .receipt-container {
            width: 100%;
            padding: 2mm;
            margin: 0 auto;
        }

        .center {
            text-align: center;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 2mm 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 1mm 0;
            vertical-align: top;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="center bold">
            Nama Kantin<br>
            <small>Alamat / Kontak</small>
        </div>

        <div class="line"></div>
        <table>
            <tr>
                <td>Invoice</td>
                <td class="right">#{{ $order->id }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td class="right">{{ $order->created_at->format('d-m-Y H:i') }}</td>
            </tr>
        </table>
        <div class="line"></div>

        <table>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }} (x{{ $item->quantity }})</td>
                    <td class="right">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </table>

        <div class="line"></div>
        <table>
            <tr>
                <td class="bold">Total</td>
                <td class="right bold">{{ number_format($order->total, 0, ',', '.') }}</td>
            </tr>
        </table>
        <div class="line"></div>

        <div class="center">
            Terima Kasih<br>
            ~ Selamat Menikmati ~
        </div>
    </div>
</body>
</html>
