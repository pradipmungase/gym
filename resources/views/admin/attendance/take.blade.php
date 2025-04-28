@extends('admin.layout.adminApp')
@section('content')
    <main id="content" role="main" class="main">
        <div class="content container-fluid">

            <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
            <div style="display: flex;">
                <!-- Webcam Section -->
                <div id="reader" style="width: 50%;">
                    <!-- Webcam stream will appear here -->
                </div>

                <!-- Result Section -->
                <div id="result" style="width: 50%; padding: 20px;">
                    <h2 class="text-center">Scan Result</h2>
                    <p id="scan-output" class="text-center">Waiting for scan...</p>
                </div>
            </div>

        </div>
    </main>
@endsection


<script>
    let lastScannedCode = null;
    let scanCooldown = false;

    function onScanSuccess(decodedText, decodedResult) {
        if (scanCooldown || decodedText === lastScannedCode) {
            return; // Prevent duplicate or too frequent scans
        }

        lastScannedCode = decodedText;
        scanCooldown = true;

        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: '/attendance/mark',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: { id: decodedText },
            success: function(response) {
                if (response.status === 'success') {
                    showToast(response.message, 'bg-success');
                    $('#scan-output').html(`
                        <div class="text-center">
                            <div class="text-success" style="font-size: 100px;">✔</div>
                            <div class="mt-2 fs-4">${response.data.name}</div>
                        </div>
                    `);
                } else if (response.status === 'error') {
                    showToast(response.message, 'bg-danger');
                    $('#scan-output').html(`
                        <div class="text-center">
                            <div class="text-danger" style="font-size: 100px;">✘</div>
                            <div class="mt-2 fs-5">Error marking attendance</div>
                        </div>
                    `);
                } else if (response.status === 'already_marked') {
                    showToast('Attendance already marked', 'bg-info');
                    $('#scan-output').html(`
                        <div class="text-center">
                            <div class="text-warning" style="font-size: 100px;">⚠</div>
                            <div class="mt-2 fs-5">Attendance already marked</div>
                        </div>
                    `);
                }

            },
            error: function(xhr, status, error) {
                showToast('AJAX Error: ' + error, 'bg-danger');
            },
            complete: function() {
                // Cooldown period before next scan is allowed
                setTimeout(function() {
                    scanCooldown = false;
                    lastScannedCode = null;
                    $('#scan-output').html(`
                        <div class="text-center">
                            <p class="text-center">Waiting for scan...</p>
                        </div>
                    `);
                }, 3000); // 3 seconds delay before allowing next scan
            }
        });
    }

    window.addEventListener('load', function () {
        var html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", { fps: 10, qrbox: 1000 });
        html5QrcodeScanner.render(onScanSuccess);
    });
</script>
