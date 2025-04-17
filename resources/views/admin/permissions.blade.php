@extends('admin.layout.adminApp')
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/web-push@3.4.4"></script>
    <main id="content" role="main" class="main">
        <!-- Content -->
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm">
                <h1 class="page-header-title">Hello, <span class="user_name">{{ Auth::user()->owner_name }}</span></h1>
                <p class="page-header-text">Please allow location and notification permissions for a better experience.</p>
            </div>
        </div>
    </div>

    <!-- Permissions Cards -->
    <div class="row g-4 mt-4">
        <!-- Location Permission -->
        <div class="col-md-6">
            <div class="card border-0 shadow h-100 text-center p-4">
                <div class="card-body">
                    <i class="bi bi-geo-alt-fill fs-1 text-info mb-3"></i>
                    <h5 class="card-title">Enable Location</h5>
                    <p class="card-text">Get location-based suggestions and services.</p>
                    <button class="btn btn-outline-info" onclick="requestLocation()">Allow Location</button>
                </div>
            </div>
        </div>

        <!-- Notification Permission -->
        <div class="col-md-6">
            <div class="card border-0 shadow h-100 text-center p-4">
                <div class="card-body">
                    <i class="bi bi-bell-fill fs-1 text-warning mb-3"></i>
                    <h5 class="card-title">Enable Notifications</h5>
                    <p class="card-text">Stay updated with important alerts and news.</p>
                    <button class="btn btn-outline-warning" onclick="subscribeToPush()">Allow Notifications</button>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ with Dummy Image -->
    <div class="row mt-5">
        <div class="col">
            <h4>What You Get After Allowing</h4>
            <div class="card border-0 shadow text-center p-4">
                <img src="https://i0.wp.com/izooto.com/wp-content/uploads/2024/04/WPN-on-Desktop-2.png?fit=675%2C387&ssl=1" alt="Notification Example" class="img-fluid mb-3">
                <p class="text-muted">Here’s an example of the kind of updates and alerts you’ll receive once you enable notifications and location access.</p>
            </div>
        </div>
    </div>
</div>



        <!-- End Content -->
    </main>


<script>
        // Store the push subscription globally
        window.pushSubscription = null;
        window.isPushSupported = 'serviceWorker' in navigator && 'PushManager' in window;

        // Initialize service worker
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/serviceworker.js')
                .then(function(registration) {
                    console.log('ServiceWorker registration successful');
                })
                .catch(function(err) {
                    console.log('ServiceWorker registration failed: ', err);
                });
        }

    async function subscribeToPush() {
        if (!window.isPushSupported) {
            alert('Push notifications are not supported in your browser');
            return;
        }

        try {
            const registration = await navigator.serviceWorker.ready;
            const subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array("{{ env('VAPID_PUBLIC_KEY') }}")
            });

            // Send subscription to server with auth token
            const response = await fetch('/webpush', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Authorization': 'Bearer ' + localStorage.getItem('auth_token') // If using API auth
                },
                body: JSON.stringify(subscription)
            });

            if (response.ok) {
                alert('Successfully subscribed to push notifications!');
            } else {
                throw new Error('Failed to save subscription');
            }
        } catch (error) {
            console.error(error);
            alert('Failed to subscribe to push notifications');
        }
    }
        // Helper function to convert VAPID key
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

                        function requestLocation() {
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
                                        if (response.success) {
                                            showToast(response.message, 'bg-success');
                                        } else {
                                            showToast(response.message, 'bg-danger');
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        showToast(xhr.responseJSON.message, 'bg-danger');
                                    }
                                });
                            },
                            function(error) {
                                $('#scan-output').html(`
                                <div class="text-center">
                                    <div class="text-danger" style="font-size: 100px;">
                                        <i class="bi bi-geo-alt-slash-fill"></i>
                                    </div>
                                    <div class="mt-2 fs-5">
                                        Location access denied.<br>
                                        Please enable it in your browser settings.
                                    </div>
                                </div>
                            `);
                            }, {
                                enableHighAccuracy: true,
                                timeout: 10000,
                                maximumAge: 0
                            }
                        );
                    } else {
                        $('#scan-output').html(`
                        <div class="text-center">
                            <div class="text-danger" style="font-size: 100px;">
                                <i class="bi bi-geo-alt-slash-fill"></i>
                            </div>
                            <div class="mt-2 fs-5">Geolocation is not supported by this browser.</div>
                        </div>
                    `);
                    }
                }

                // Retry button handler
                $(document).on('click', '#retry-location-btn', function() {
                    requestLocation();
                });

                // Optional: detect if permission is permanently denied
                if (navigator.permissions) {
                    navigator.permissions.query({
                        name: 'geolocation'
                    }).then(function(result) {
                        if (result.state === 'denied') {
                            showToast(
                                'You have permanently denied location access. Please enable it from your browser settings.',
                                'bg-danger');
                        }
                    });
                }
</script>


        <script>
            $(document).ready(function() {


                // Initial location request
                // requestLocation();


            });
        </script>
@endsection
