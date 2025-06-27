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
        .barcode {
            display: flex;
        }
    </style>

    <div class="barcode">
        @foreach($data as $product)
                {!! DNS1D::getBarcodeHTML($product->barcode, 'C39') !!}
    </div>
</body>
</html>
