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
    <div id="scanner" style="width: 100%; max-width: 400px; height: 300px; border: 1px solid #ccc;"></div>
    <button id="start-scan" class="btn btn-primary mt-2">Start Scanner</button>
    <button id="stop-scan" class="btn btn-danger mt-2">Stop Scanner</button>
    <div id="result" class="mt-2"></div>
</div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

    <script>
    let isScanning = false;

    document.getElementById('start-scan').addEventListener('click', function() {
        if (isScanning) return;

        // Check if camera is available
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert('Camera not supported in this browser');
            return;
        }

        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector('#scanner'), // Use the div, not video
                constraints: {
                    width: 400,
                    height: 300,
                    facingMode: "environment" // Back camera
                }
            },
            decoder: {
                readers: ["code_128_reader", "ean_reader", "code_39_reader"]
            }
        }, function(err) {
            if (err) {
                console.error(err);
                alert('Camera error: ' + err.message);
                return;
            }
            console.log('Scanner started');
            isScanning = true;
            Quagga.start();
        });
    });

    document.getElementById('stop-scan').addEventListener('click', function() {
        if (isScanning) {
            Quagga.stop();
            isScanning = false;
            console.log('Scanner stopped');
        }
    });

    // When barcode is detected
    Quagga.onDetected(function(result) {
        const code = result.codeResult.code;
        document.getElementById('result').innerHTML = '<strong>Found: ' + code + '</strong>';

        alert(code);

        console.log('Barcode detected:', code);

        // Stop scanning after detection
        Quagga.stop();
        isScanning = false;
    });
    </script>
</body>

</html>
