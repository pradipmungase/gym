<script>
    const user = JSON.parse(localStorage.getItem('user'));
    if (user) {
        window.location.href = '/dashboard'; // redirect if user exists
    }
</script>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Title -->
    <title>Welcome to - GYM Manager Admin!</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon_io/android-chrome-192x192.png') }}" />

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons/font/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/hs-img-compare/hs-img-compare.css') }}">

    <!-- CSS Front Template -->

    <link rel="preload" href="{{ asset('assets/css/theme.min.css') }}" data-hs-appearance="default" as="style">
    <link rel="preload" href="{{ asset('assets/css/theme-dark.min.css') }}" data-hs-appearance="dark" as="style">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style data-hs-appearance-onload-styles>
        * {
            transition: unset !important;
        }

        body {
            opacity: 0;
        }
    </style>

    <script>
        window.hs_config = {
            "autopath": "@@autopath",
            "deleteLine": "hs-builder:delete",
            "deleteLine:build": "hs-builder:build-delete",
            "deleteLine:dist": "hs-builder:dist-delete",
            "previewMode": false,
            "startPath": "/index.html",
            "vars": {
                "themeFont": "https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap",
                "version": "?v=1.0"
            },
            "layoutBuilder": {
                "extend": {
                    "switcherSupport": true
                },
                "header": {
                    "layoutMode": "default",
                    "containerMode": "container-fluid"
                },
                "sidebarLayout": "default"
            },
            "themeAppearance": {
                "layoutSkin": "default",
                "sidebarSkin": "default",
                "styles": {
                    "colors": {
                        "primary": "#377dff",
                        "transparent": "transparent",
                        "white": "#fff",
                        "dark": "132144",
                        "gray": {
                            "100": "#f9fafc",
                            "900": "#1e2022"
                        }
                    },
                    "font": "Inter"
                }
            },
            "languageDirection": {
                "lang": "en"
            },
            "skipFilesFromBundle": {
                "dist": ["assets/js/hs.theme-appearance.js", "assets/js/hs.theme-appearance-charts.js",
                    "assets/js/demo.js"
                ],
                "build": ["assets/css/theme.css",
                    "assets/vendor/hs-navbar-vertical-aside/dist/hs-navbar-vertical-aside-mini-cache.js",
                    "assets/js/demo.js", "assets/css/theme-dark.css", "assets/css/docs.css",
                    "assets/vendor/icon-set/style.css", "assets/js/hs.theme-appearance.js",
                    "assets/js/hs.theme-appearance-charts.js",
                    "node_modules/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js",
                    "assets/js/demo.js"
                ]
            },
            "minifyCSSFiles": ["assets/css/theme.css", "assets/css/theme-dark.css"],
            "copyDependencies": {
                "dist": {
                    "*assets/js/theme-custom.js": ""
                },
                "build": {
                    "*assets/js/theme-custom.js": "",
                    "node_modules/bootstrap-icons/font/*fonts/**": "assets/css"
                }
            },
            "buildFolder": "",
            "replacePathsToCDN": {},
            "directoryNames": {
                "src": "./src",
                "dist": "./dist",
                "build": "./build"
            },
            "fileNames": {
                "dist": {
                    "js": "theme.min.js",
                    "css": "theme.min.css"
                },
                "build": {
                    "css": "theme.min.css",
                    "js": "theme.min.js",
                    "vendorCSS": "vendor.min.css",
                    "vendorJS": "vendor.min.js"
                }
            },
            "fileTypes": "jpg|png|svg|mp4|webm|ogv|json"
        }
        window.hs_config.gulpRGBA = (p1) => {
            const options = p1.split(',')
            const hex = options[0].toString()
            const transparent = options[1].toString()

            var c;
            if (/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)) {
                c = hex.substring(1).split('');
                if (c.length == 3) {
                    c = [c[0], c[0], c[1], c[1], c[2], c[2]];
                }
                c = '0x' + c.join('');
                return 'rgba(' + [(c >> 16) & 255, (c >> 8) & 255, c & 255].join(',') + ',' + transparent + ')';
            }
            throw new Error('Bad Hex');
        }
        window.hs_config.gulpDarken = (p1) => {
            const options = p1.split(',')

            let col = options[0].toString()
            let amt = -parseInt(options[1])
            var usePound = false

            if (col[0] == "#") {
                col = col.slice(1)
                usePound = true
            }
            var num = parseInt(col, 16)
            var r = (num >> 16) + amt
            if (r > 255) {
                r = 255
            } else if (r < 0) {
                r = 0
            }
            var b = ((num >> 8) & 0x00FF) + amt
            if (b > 255) {
                b = 255
            } else if (b < 0) {
                b = 0
            }
            var g = (num & 0x0000FF) + amt
            if (g > 255) {
                g = 255
            } else if (g < 0) {
                g = 0
            }
            return (usePound ? "#" : "") + (g | (b << 8) | (r << 16)).toString(16)
        }
        window.hs_config.gulpLighten = (p1) => {
            const options = p1.split(',')

            let col = options[0].toString()
            let amt = parseInt(options[1])
            var usePound = false

            if (col[0] == "#") {
                col = col.slice(1)
                usePound = true
            }
            var num = parseInt(col, 16)
            var r = (num >> 16) + amt
            if (r > 255) {
                r = 255
            } else if (r < 0) {
                r = 0
            }
            var b = ((num >> 8) & 0x00FF) + amt
            if (b > 255) {
                b = 255
            } else if (b < 0) {
                b = 0
            }
            var g = (num & 0x0000FF) + amt
            if (g > 255) {
                g = 255
            } else if (g < 0) {
                g = 0
            }
            return (usePound ? "#" : "") + (g | (b << 8) | (r << 16)).toString(16)
        }
    </script>
</head>

<body>
    <style type="text/css">
        @media (min-width: 1400px) {
            .container-lg {
                max-width: 1140px;
            }
        }

        .mt-2 {
            margin-top: 0.5rem !important;
            color: red;
            list-style: none;
            left: 0 !important;
        }

        ul {
            margin-left: 0px;
            padding-left: 0px;
        }

        ul li {
            display: list-item;
            text-align: left;
        }

        .content-space-t-lg-4 {
            padding-top: 7rem !important;
        }

        .content-space-b-2 {
            padding-bottom: 2rem !important;
        }
    </style>

    <script src="{{ asset('assets/vendor/hs-toggle-password/dist/js/hs-toggle-password.js') }}"></script>
    <script src="{{ asset('assets/js/hs.theme-appearance.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new HSTogglePassword('.js-toggle-password')
        });
    </script>

    <!-- ========== MAIN CONTENT ========== -->
    <main id="content" role="main" class="main">
        <div class="position-fixed top-0 end-0 start-0 bg-img-start"
            style="height: 100rem; background-image: url(./assets/svg/components/card-6.svg);">
        </div>

        <!-- Content -->
        <div class="container py-5 py-sm-7">
            <a class="d-flex justify-content-center mb-5" href="{{ url('/') }}">
                <img class="zi-2" src="{{ asset('assets/images/blackFullLogo.png') }}" alt="Image Description" style="width: 18rem;">
            </a>

            <div class="mx-auto" style="max-width: 30rem;">
                <!-- Card -->
                <div class="card card-lg mb-5">
                    <div class="card-body">
                        <!-- Form -->
                        <form id="loginForm" action="/checkLogin" method="POST">
                            @csrf
                            <div class="text-center">
                                <div class="mb-5">
                                    <h1 class="display-5">Sign in</h1>
                                    <p>Don't have an account yet? <a class="link" href="#"
                                            data-bs-toggle="modal" data-bs-target="#signupModal">Sign up here</a></p>
                                </div>
                            </div>
                            <!-- Email Field -->
                            <div class="mb-4">
                                <label class="form-label" for="signinSrEmail">Your Mobile Number</label>
                                <input value="7028143227" type="text" class="form-control form-control-lg"
                                    name="mobile_for_login" id="signinSrEmail" tabindex="1" placeholder="9876543210"
                                    required>
                                <span class="invalid-feedback">Please enter a valid mobile number.</span>
                            </div>

                            <!-- Password Field -->
                            <div class="mb-4">
                                <label class="form-label w-100" for="signupSrPassword">
                                    <span class="d-flex justify-content-between align-items-center">
                                        <span>Password</span>
                                        <a class="form-label-link mb-0" href="{{ url('forgotPassword') }}">Forgot
                                            Password?</a>
                                    </span>
                                </label>
                                <div class="input-group input-group-merge">
                                    <input value="Admin@123" type="password" class="form-control form-control-lg"
                                        name="password" id="signupSrPassword" placeholder="8+ characters required"
                                        required>
                                    <a id="changePassTarget" class="input-group-append input-group-text"
                                        href="javascript:;">
                                        <i id="changePassIcon" class="bi-eye-slash"></i>
                                    </a>
                                </div>

                                <span class="invalid-feedback">Please enter a valid password.</span>
                            </div>

                            <!-- Remember Me Checkbox -->
                            <div class="form-check mb-4">
                                <input checked class="form-check-input" type="checkbox" name="remember"
                                    id="termsCheckbox">
                                <label class="form-check-label" for="termsCheckbox">Remember me</label>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg" id="loginBtn">Sign in</button>
                            </div>


                        </form>
                        <!-- End Form -->
                    </div>
                </div>
                <!-- End Card -->
            </div>
        </div>
        <!-- End Content -->

        <div class="modal fade" id="signupModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="signupModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <form id="register-form" class="register-form" method="POST" action="#">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header text-white">
                            <h5 class="modal-title" id="signupModalLabel">
                                <i class="bi bi-person-plus-fill me-2"></i> Sign Up
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body px-4">
                            <div class="row gy-4">
                                <div class="col-12">
                                    <label for="gymName" class="form-label">GYM Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="gymName" name="gym_name"
                                        required placeholder="e.g., FitZone Gym">
                                    <div class="invalid-feedback">Gym name is required.</div>
                                </div>

                                <div class="col-12">
                                    <label for="ownerName" class="form-label">Owner Full Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="ownerName" name="owner_name"
                                        required placeholder="e.g., John Doe">
                                    <div class="invalid-feedback">Owner name is required.</div>
                                </div>

                                <div class="col-12">
                                    <label for="mobile" class="form-label">Mobile Number <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="mobile" name="mobile"
                                        required placeholder="e.g., 9876543210">
                                    <div class="invalid-feedback">Mobile number is required.</div>
                                </div>

                                <div class="col-12">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control pe-5" id="password" name="password" required placeholder="Enter password">
                                        <i class="bi bi-eye-slash position-absolute top-50 end-0 translate-middle-y me-3" id="togglePassword" style="cursor: pointer;"></i>
                                        <div class="invalid-feedback">Password is required.</div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer px-4">
                            <button type="submit" id="signupBtn" class="btn btn-success register-btn">
                                <span class="btn-text"><i class="bi bi-check-circle me-1"></i> Sign Up</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status"
                                    aria-hidden="true"></span>
                            </button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i> Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>




    </main>
    <!-- ========== END MAIN CONTENT ========== -->

    <!-- ========== FOOTER ========== -->
    <footer class="container-lg text-center py-10"
        style="padding-top: 0.5rem !important;padding-bottom: 0.5rem !important;">
        <p class="fs-6 mb-0">&copy; GYM Manager. <span class="d-none d-sm-inline-block">2025-2026 GYM
                Manager.</span></p>
    </footer>
    <!-- ========== END FOOTER ========== -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
        <!-- Toast -->
        <div id="dynamicToast" class="toast text-white bg-primary border-0 rounded-3 shadow-sm fade" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex align-items-center">
                <div class="toast-body d-flex align-items-center gap-2" id="toastMessage">
                    <i class="bi bi-info-circle-fill fs-5"></i> <!-- Bootstrap icon (optional) -->
                    <!-- Message will be inserted here -->
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- JS Global Compulsory  -->
    <script src="{{ asset('assets/vendor/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-migrate/dist/jquery-migrate.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

    <!-- JS Implementing Plugins -->
    <script src="{{ asset('assets/vendor/hs-header/dist/hs-header.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/hs-img-compare/hs-img-compare.js') }}"></script>
    <script src="{{ asset('assets/vendor/hs-go-to/dist/hs-go-to.min.js') }}"></script>

    <!-- JS Front -->
    <script src="{{ asset('assets/js/theme.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('My.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('My.js') }}"></script>

    <script>
        $('#changePassTarget').on('click', function() {
            const passwordInput = $('#signupSrPassword');
            const icon = $('#changePassIcon');

            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.removeClass('bi-eye-slash').addClass('bi-eye');
            } else {
                passwordInput.attr('type', 'password');
                icon.removeClass('bi-eye').addClass('bi-eye-slash');
            }
        });
    </script>
    <script>
        function showToast(message, typeClass) {
            const toastEl = document.getElementById('dynamicToast');
            const toastBody = document.getElementById('toastMessage');

            // Update message
            toastBody.innerHTML = message;

            // Remove old bg-* classes and add new one
            toastEl.className = 'toast align-items-center text-white border-0 ' + typeClass;

            // Show toast
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
        @if (session()->has('success'))
            showToast(@json(session('success')), 'bg-success');
        @elseif (session()->has('error'))
            showToast(@json(session('error')), 'bg-danger');
        @endif


        $('#togglePassword').on('click', function() {
            const passwordInput = $('#password');
            const icon = $('#passwordIcon');
            const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';

            passwordInput.attr('type', type);
            icon.toggleClass('bi-eye');
            icon.toggleClass('bi-eye-slash');
        });
    </script>
</body>

</html>
