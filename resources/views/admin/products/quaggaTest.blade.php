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
    <video id="video" width="300" height="200" autoplay></video>
    <button id="start-scan" class="btn btn-primary">Start Scanner</button>
    <button id="stop-scan" class="btn btn-danger">Stop Scanner</button>
    <div id="result" class="mt-2"></div>
</div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

<script>
let isScanning = false;

document.getElementById('start-scan').addEventListener('click', function() {
    if (isScanning) return;

    Quagga.init({
        inputStream: {
            name: "Live",
            type: "LiveStream",
            target: document.querySelector('#video'),
            constraints: {
                width: 640,
                height: 480,
                facingMode: "environment"
            }
        },
        decoder: {
            readers: ["code_128_reader", "ean_reader", "code_39_reader"]
        }
    }, function(err) {
        if (err) {
            alert('Camera not available: ' + err);
            return;
        }
        isScanning = true;
        Quagga.start();
    });
});

document.getElementById('stop-scan').addEventListener('click', function() {
    if (isScanning) {
        Quagga.stop();
        isScanning = false;
    }
});

// When barcode is detected
Quagga.onDetected(function(result) {
    const code = result.codeResult.code;
    document.getElementById('result').innerHTML = 'Found: ' + code;

    // Send to Laravel
    fetch('/admin/products/find-barcode', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ barcode: code })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Product: ' + data.product.name);
            Quagga.stop();
            isScanning = false;
        }
    });
});
</script>
</body>

</html>
