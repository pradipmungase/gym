<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Attendance By Location</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body class="bg-light">
    @php
        $error_message = null;
        if (!$gym) {
            $error_message = 'Gym not found. Please contact admin.';
        } elseif (!$member) {
            $error_message = 'Member not found. Please contact admin.';
        } elseif ($member->status !== 'active') {
            $error_message = 'Your membership is inactive. Please contact gym staff.';
        } elseif ($member->deleted_at !== null) {
            $error_message = 'Your membership is deleted. Please contact gym staff.';
        } elseif (is_null($gym->latitude) || is_null($gym->longitude)) {
            $error_message = 'Gym location is not set. Please contact gym staff.';
        }
    @endphp

    @if ($error_message)
        <div class="container-fluid px-0 py-5">
            <div class="row justify-content-center m-0">
                <div class="col-12 col-md-8 col-lg-6 px-3 px-md-0">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-body text-center p-5">
                            <h1 class="fw-bold text-danger mb-3">Error</h1>
                            <p class="text-muted">{{ $error_message }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- If no error, show location fetching -->
        <div class="container-fluid px-0 py-5">
            <div class="row justify-content-center m-0">
                <div class="col-12 col-md-8 col-lg-6 px-3 px-md-0">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-body text-center p-5">
                            <h1 class="fw-bold mb-3">Welcome to <span class="text-primary">{{ $gym->gym_name }}</span></h1>
                            <p class="text-muted mb-4">Please allow location access to mark your attendance.</p>

                            <div id="scan-output">
                                <div class="d-flex justify-content-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Fetching location...</span>
                                    </div>
                                </div>
                                <p class="mt-4">Fetching location...</p>

                                <!-- Hidden inputs -->
                                <input type="hidden" id="gymIDAndMemberID" value="{{ $gymIDAndMemberID }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        $(document).ready(function () {
            function requestLocation() {
                if ("geolocation" in navigator) {
                    navigator.geolocation.getCurrentPosition(
                        function (position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;

                            const gymIDAndMemberID = $('#gymIDAndMemberID').val();

                            $.ajax({
                                url: `/markAttendanceByLatLong/${gymIDAndMemberID}`,
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    latitude: lat,
                                    longitude: lng
                                },
                                success: function (response) {
                                    const statusConfig = {
                                        success: {
                                            icon: 'bi-check-circle-fill',
                                            color: 'text-success',
                                            heading: response.member_name || 'Member'
                                        },
                                        already_marked: {
                                            icon: 'bi-exclamation-triangle-fill',
                                            color: 'text-warning',
                                            heading: response.member_name || 'Member'
                                        },
                                        error: {
                                            icon: 'bi-x-circle-fill',
                                            color: 'text-danger',
                                            heading: 'Error'
                                        },
                                        location_error: {
                                            icon: 'bi-geo-alt-fill',
                                            color: 'text-info',
                                            heading: 'Location Error'
                                        }
                                    };

                                    const status = response.status;
                                    const message = response.message || 'Something went wrong.';
                                    const config = statusConfig[status] || {
                                        icon: 'bi-question-circle-fill',
                                        color: 'text-secondary',
                                        heading: 'Unknown Status'
                                    };

                                    $('#scan-output').html(`
                                        <div class="text-center">
                                            <div class="${config.color}" style="font-size: 100px;">
                                                <i class="bi ${config.icon}"></i>
                                            </div>
                                            <div class="mt-2 fs-4">${config.heading}</div>
                                            <p class="text-muted">${message}</p>
                                        </div>
                                    `);
                                },
                                error: function (xhr, status, error) {
                                    $('#scan-output').html(`
                                        <div class="text-center">
                                            <div class="text-danger" style="font-size: 100px;">
                                                <i class="bi bi-x-circle-fill"></i>
                                            </div>
                                            <div class="mt-2 fs-5">AJAX Error: ${error}</div>
                                        </div>
                                    `);
                                }
                            });
                        },
                        function (error) {
                            let errorMsg = 'Location access denied. Please enable it.';
                            if (error.code === error.PERMISSION_DENIED) {
                                errorMsg = 'Location permission denied. Please allow it in browser settings.';
                            } else if (error.code === error.POSITION_UNAVAILABLE) {
                                errorMsg = 'Location information is unavailable.';
                            } else if (error.code === error.TIMEOUT) {
                                errorMsg = 'Location request timed out.';
                            }

                            $('#scan-output').html(`
                                <div class="text-center">
                                    <div class="text-danger" style="font-size: 100px;">
                                        <i class="bi bi-geo-alt-slash-fill"></i>
                                    </div>
                                    <div class="mt-2 fs-5">${errorMsg}</div>
                                </div>
                            `);
                        },
                        {
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

            requestLocation();

            $(document).on('click', '#retry-location-btn', function () {
                requestLocation();
            });
        });
        </script>
    @endif
</body>


</html>
