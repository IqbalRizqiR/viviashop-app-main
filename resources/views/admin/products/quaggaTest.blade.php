<!DOCTYPE html>
<html lang="en">

<head>
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.rawgit.com/serratus/quaggaJS/0420d5e0/dist/quagga.min.js"></script>
    <style>
        /* In order to place the tracking correctly */
        canvas.drawing, canvas.drawingBuffer {
            position: absolute;
            left: 0;
            top: 0;
        }
    </style>
</head>

<body>
    <div class="scanner-container">
        <input type="file" id="barcode-file" accept="image/*" capture="environment" class="form-control mb-2">
        <input type="text" id="barcode-manual" class="form-control" placeholder="Or type barcode manually">
        <button onclick="processBarcode()" class="btn btn-primary mt-2">Submit</button>
    </div>
    // Include QuaggaJS
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

    <script>
    // Handle file input
    document.getElementById('barcode-file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            Quagga.decodeSingle({
                src: URL.createObjectURL(file),
                numOfWorkers: 0,
                inputStream: { size: 800 },
                decoder: { readers: ["code_128_reader", "ean_reader"] }
            }, function(result) {
                if (result && result.codeResult) {
                    document.getElementById('barcode-manual').value = result.codeResult.code;
                    processBarcode();
                } else {
                    alert('No barcode detected. Please type manually.');
                }
            });
        }
    });

    function processBarcode() {
        const barcode = document.getElementById('barcode-manual').value;
        if (!barcode) {
            alert('Please scan or enter a barcode');
            return;
        }

        // Send to your Laravel route
        fetch('/admin/products/find-barcode', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ barcode: barcode })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Product found: ' + data.product.name);
                // Do something with the product
            } else {
                alert('Product not found');
            }
        });
    }
    </script>
</body>

</html>
