<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Title -->
    <title>Reset Password - Gym Manager</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.ico') }}" />

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons/font/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/hs-img-compare/hs-img-compare.css') }}">

    <!-- CSS Front Template -->

    <link rel="preload" href="{{ asset('assets/css/theme.min.css') }}" data-hs-appearance="default" as="style">
    <link rel="preload" href="{{ asset('assets/css/theme-dark.min.css') }}" data-hs-appearance="dark" as="style">
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
                "src": "../src",
                "dist": "../dist",
                "build": "../build"
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
            style="height: 32rem; background-image: url(../assets/svg/components/card-6.svg);">
            <!-- Shape -->
            <div class="shape shape-bottom zi-1">
                <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                    viewBox="0 0 1921 273">
                    <polygon fill="#fff" points="0,273 1921,273 1921,0 " />
                </svg>
            </div>
            <!-- End Shape -->
        </div>

        <!-- Content -->
        <div class="container py-5 py-sm-7">
            <a class="d-flex justify-content-center mb-5" href="../index.html">
                <img class="zi-2" src="../assets/svg/logos/logo.svg" alt="Image Description" style="width: 8rem;">
            </a>

            <div class="mx-auto" style="max-width: 30rem;">
                <!-- Card -->
                <div class="card card-lg mb-5">
                    <div class="card-body">

                        <!-- Form -->
                        <form method="POST" action="#" id="resetPasswordForm">
                            @csrf
                            <div class="text-center">
                                <div class="mb-5">
                                    <h1 class="display-5">Reset Password</h1>
                                </div>
                            </div>
                            <!-- Form -->
                            <div class="mb-4">
                                <input type="hidden" name="id" value="{{ $user->id }}">
                                <label class="form-label" for="signinSrEmail">Your mobile number</label>
                                <input type="tel" readonly class="form-control form-control-lg" name="mobile"
                                    id="signinSrEmail" tabindex="1" placeholder="Mobile Number"
                                    aria-label="Mobile Number" required value="{{ $user->mobile }}">
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="signupSrPassword">New Password</label>

                                <div class="input-group input-group-merge" data-hs-validation-validate-class>
                                    <input type="password" class="js-toggle-password form-control form-control-lg"
                                        name="password" id="signupSrPassword" placeholder="8+ characters required"
                                        aria-label="8+ characters required" required minlength="8"
                                        data-hs-toggle-password-options='{
                        "target": "#newPassTarget",
                        "defaultClass": "bi-eye-slash",
                        "showClass": "bi-eye",
                        "classChangeTarget": "#newPassIcon"
                        }'>
                                    <a id="newPassTarget" class="input-group-append input-group-text"
                                        href="javascript:;">
                                        <i id="newPassIcon" class="bi-eye"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="signupSrpassword_confirmation">Confirm Password</label>

                                <div class="input-group input-group-merge" data-hs-validation-validate-class>
                                    <input type="password" class="js-toggle-password form-control form-control-lg"
                                        name="password_confirmation" id="signupSrpassword_confirmation"
                                        placeholder="8+ characters required" aria-label="8+ characters required"
                                        required minlength="8"
                                        data-hs-toggle-password-options='{
                        "target": "#confirmPassTarget",
                        "defaultClass": "bi-eye-slash",
                        "showClass": "bi-eye",
                        "classChangeTarget": "#confirmPassIcon"
                        }'>
                                    <a id="confirmPassTarget" class="input-group-append input-group-text"
                                        href="javascript:;">
                                        <i id="confirmPassIcon" class="bi-eye"></i>
                                    </a>
                                </div>
                            </div>




                            <!-- End Form -->

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                            </div>
                        </form>
                        <!-- End Form -->
                    </div>
                </div>
                <!-- End Card -->
            </div>
        </div>
        <!-- End Content -->
    </main>
    <!-- ========== END MAIN CONTENT ========== -->
      <!-- ========== FOOTER ========== -->
    <footer class="container-lg text-center py-10"
        style="padding-top: 0.5rem !important;padding-bottom: 0.5rem !important;">
        <p class="mb-0">&copy; Gym Manager. All rights reserved.</p>
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

    <script src="{{ asset('My.js') }}"></script>

    <!-- JS Plugins Init. -->
    <script>
        (function() {
            // INITIALIZATION OF NAVBAR
            // =======================================================
            new HSHeader('#header').init()


            // INITIALIZATION OF GO TO
            // =======================================================
            new HSGoTo('.js-go-to')


            // TRANSFORMATION
            // =======================================================
            const $figure = document.querySelector('.js-img-comp')

            if (window.pageYOffset) {
                $figure.style.transform =
                    `rotateY(${-18 + window.pageYOffset}deg) rotateX(${window.pageYOffset / 5}deg)`
            }

            let y = -18 + window.pageYOffset,
                x = 55 - window.pageYOffset

            const figureTransformation = function() {
                if (-18 + window.pageYOffset / 5 > 0) {
                    y = 0
                }

                if (55 - window.pageYOffset / 3 < 0) {
                    x = 0
                }

                y = -18 + window.pageYOffset / 5 < 0 ? -18 + window.pageYOffset / 5 : y
                x = 55 - window.pageYOffset / 3 > 0 ? 55 - window.pageYOffset / 3 : x
                $figure.style.transform = `rotateY(${y}deg) rotateX(${x}deg)`
            }

            figureTransformation()
            window.addEventListener('scroll', figureTransformation)
        })()
    </script>

    <!-- Style Switcher JS -->

    <script>
        (function() {
            // STYLE SWITCHER
            // =======================================================
            const $dropdownBtn = document.getElementById('selectThemeDropdown') // Dropdowon trigger
            const $variants = document.querySelectorAll(
                `[aria-labelledby="selectThemeDropdown"] [data-icon]`) // All items of the dropdown

            // Function to set active style in the dorpdown menu and set icon for dropdown trigger
            const setActiveStyle = function() {
                $variants.forEach($item => {
                    if ($item.getAttribute('data-value') === HSThemeAppearance.getOriginalAppearance()) {
                        $dropdownBtn.innerHTML = `<i class="${$item.getAttribute('data-icon')}" />`
                        return $item.classList.add('active')
                    }

                    $item.classList.remove('active')
                })
            }

            // Add a click event to all items of the dropdown to set the style
            $variants.forEach(function($item) {
                $item.addEventListener('click', function() {
                    HSThemeAppearance.setAppearance($item.getAttribute('data-value'))
                })
            })

            // Call the setActiveStyle on load page
            setActiveStyle()

            // Add event listener on change style to call the setActiveStyle function
            window.addEventListener('on-hs-appearance-change', function() {
                setActiveStyle()
            })
        })()
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
    </script>

    <!-- End Style Switcher JS -->
</body>

</html>
