<!DOCTYPE html>
<html>
<head>
    <title>Scan QR Code</title>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body>

    <h2>Scan QR Code using Webcam</h2>

    <div id="reader" style="width: 300px;"></div>

    <div>
        <strong>Scanned Text:</strong>
        <p id="result"></p>
    </div>

    <script>
        // function onScanSuccess(decodedText, decodedResult) {
        //     document.getElementById('result').innerText = decodedText;
        //     html5QrcodeScanner.clear();
        // }

        // var html5QrcodeScanner = new Html5QrcodeScanner(
        //     "reader", { fps: 10, qrbox: 250 });
        // html5QrcodeScanner.render(onScanSuccess);
    </script>

<script>
if ("geolocation" in navigator) {
    navigator.geolocation.getCurrentPosition(
        function(position) {
            let lat = position.coords.latitude;
            let lng = position.coords.longitude;
            console.log("Latitude:", lat, "Longitude:", lng);
        },
        function(error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    alert("User denied the request for Geolocation.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    alert("The request to get user location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("An unknown error occurred.");
                    break;
            }
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }
    );
} else {
    alert("Geolocation is not supported by this browser.");
}

// Latitude: 19.8705152 Longitude: 75.3270784
// Latitude: 19.8705152 Longitude: 75.3270784

</script>

</body>
</html>
