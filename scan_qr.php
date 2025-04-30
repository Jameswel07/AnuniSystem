<!DOCTYPE html>
<html lang="en">
<head>
    <title>Scan Book QR Code</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <h2>📷 Scan Book QR Code</h2>

    <div id="qr-reader" style="width: 300px;"></div>
    <input type="text" id="qr-result" class="form-control mt-3" placeholder="Scanned Data" readonly>

    <script>
        function onScanSuccess(decodedText, decodedResult) {
            document.getElementById("qr-result").value = decodedText;
        }

        let html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);
    </script>
</div>

</body>
</html>
