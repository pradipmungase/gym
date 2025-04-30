@extends('admin.layout.adminApp')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/web-push@3.4.4"></script>
    <style>
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border-width: .2em;
        }
    </style>

    <main id="content" role="main" class="main">
        <!-- Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm">
                        <h1 class="page-header-title">Hello, <span class="user_name">{{ Auth::user()->owner_name }}</span>
                        </h1>
                        <p class="page-header-text">Please allow location and notification permissions for a better
                            experience.</p>
                    </div>
                </div>
            </div>

            <!-- Permissions Cards -->
            <div class="row g-4 mt-4">
                <!-- Location Permission -->
                <div class="col-md-4">
                    <div class="card border-0 shadow h-100 text-center p-4">
                        <div class="card-body">
                            <i class="bi bi-geo-alt-fill fs-1 text-info mb-3"></i>
                            <h5 class="card-title">Enable Location</h5>
                            <p class="card-text">Get location-based suggestions and services.</p>
                            <p id="output"></p>
                            <button class="btn btn-outline-info" onclick="requestLocation()">Allow Location</button>
                        </div>
                    </div>
                </div>

                <!-- Notification Permission -->
                <div class="col-md-4">
                    <div class="card border-0 shadow h-100 text-center p-4">
                        <div class="card-body">
                            <i class="bi bi-bell-fill fs-1 text-warning mb-3"></i>
                            <h5 class="card-title">Enable Notifications</h5>
                            <p class="card-text">Stay updated with important alerts and news.</p>
                            <p id="output2"></p>
                            <button class="btn btn-outline-warning" onclick="subscribeToPush()">Allow Notifications</button>
                        </div>
                    </div>
                </div>

                <!-- Camera Permission -->
                <div class="col-md-4">
                    <div class="card border-0 shadow h-100 text-center p-4">
                        <div class="card-body">
                            <i class="bi bi-camera-video-fill fs-1 text-success mb-3"></i>
                            <h5 class="card-title">Enable Camera</h5>
                            <p class="card-text">Access camera for scanning, video calls, and more.</p>
                            <p id="output3"></p>
                            <button class="btn btn-outline-success" onclick="requestCamera()">Allow Camera</button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- FAQ with Dummy Image -->
            <div class="row mt-5">
                <div class="col">
                    <h4>What You Get After Allowing</h4>
                    <div class="card border-0 shadow text-center p-4">
                        <img src="https://i0.wp.com/izooto.com/wp-content/uploads/2024/04/WPN-on-Desktop-2.png?fit=675%2C387&ssl=1"
                            alt="Notification Example" class="img-fluid mb-3">
                        <p class="text-muted">Here’s an example of the kind of updates and alerts you’ll receive once you
                            enable notifications and location access.</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Content -->
    </main>





    <script>
        function requestLocation() {
            const btn = document.querySelector('[onclick="requestLocation()"]');
            btn.disabled = true;
            btn.innerHTML =
                `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...`;

            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        $.ajax({
                            url: `/saveLatitudeAndLongitude`,
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                latitude: lat,
                                longitude: lng
                            },
                            success: function(response) {
                                $('#output').html(
                                    `<div class="text-success">Location access granted ✅</div>`);
                                showToast(response.message, 'bg-success');
                            },
                            error: function(xhr) {
                                $('#output').html(
                                    `<div class="text-danger">${xhr.responseJSON.message}</div>`);
                                showToast('Failed to save location', 'bg-danger');
                            },
                            complete: function() {
                                btn.disabled = false;
                                btn.innerHTML = 'Allow Location';
                            }
                        });
                    },
                    function(error) {
                        $('#output').html(`
                    <div class="text-center text-danger">
                        Location access denied.<br>Please enable it in your browser settings.
                    </div>
                `);
                        btn.disabled = false;
                        btn.innerHTML = 'Allow Location';
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            } else {
                $('#output').html(`<div class="text-danger">Geolocation not supported by your browser.</div>`);
                btn.disabled = false;
                btn.innerHTML = 'Allow Location';
            }
        }

        async function subscribeToPush() {
            const btn = document.querySelector('[onclick="subscribeToPush()"]');
            const originalText = btn.innerHTML;

            // Show loader
            btn.disabled = true;
            btn.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...`;

            // Basic support check
            if (!('serviceWorker' in navigator) || !('PushManager' in window) || !('Notification' in window)) {
                $('#output2').html(
                    `<div class="text-danger">Push notifications are not supported in your browser.</div>`);
                btn.disabled = false;
                btn.innerHTML = originalText;
                return;
            }

            try {
                // Ask for notification permission
                const permission = await Notification.requestPermission();
                if (permission !== 'granted') {
                    $('#output2').html(
                        `<div class="text-danger">Permission access denied.<br>Please enable it in your browser settings.</div>`
                        );
                    return;
                }

                // Wait for the service worker to be ready
                const registration = await navigator.serviceWorker.ready;

                // Subscribe to push
                const subscription = await registration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: urlBase64ToUint8Array("{{ env('VAPID_PUBLIC_KEY') }}")
                });

                // Send subscription to server
                const response = await fetch('/webpush', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Authorization': 'Bearer ' + localStorage.getItem('auth_token')
                    },
                    body: JSON.stringify(subscription)
                });

                if (!response.ok) {
                    throw new Error('Server response was not OK');
                }

                $('#output2').html(`<div class="text-success">Notifications access granted ✅</div>`);
                showToast('Notifications access granted', 'bg-success');

            } catch (error) {
                console.error('Push error:', error);
                $('#output2').html(`<div class="text-danger">Something went wrong: ${error.message}</div>`);
                showToast('Failed to subscribe to push notifications', 'bg-danger');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }


        function requestCamera() {
            const btn = document.querySelector('[onclick="requestCamera()"]');
            btn.disabled = true;
            btn.innerHTML =
                `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...`;

            navigator.mediaDevices.getUserMedia({
                    video: true
                })
                .then(function(stream) {
                    showToast('Camera access granted ', 'bg-success');
                    $('#output3').html(`<div class="text-success">Camera access granted ✅</div>`);
                    stream.getTracks().forEach(track => track.stop());
                })
                .catch(function(error) {
                    $('#output3').html(`
                <div class="text-center text-danger">
                    Camera access denied.<br>Please enable it in your browser settings.
                </div>
            `);
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = 'Allow Camera';
                });
        }

        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding)
                .replace(/\-/g, '+')
                .replace(/_/g, '/');

            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);

            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        }
    </script>
@endsection
