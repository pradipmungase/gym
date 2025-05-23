<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Title -->
    <title>GYM Management System</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon_io/android-chrome-192x192.png') }}" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon_io/favicon.ico') }}">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="./assets/vendor/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./assets/vendor/hs-img-compare/hs-img-compare.css">

    <!-- CSS Front Template -->

    <link rel="preload" href="./assets/css/theme.min.css" data-hs-appearance="default" as="style">
    <link rel="preload" href="./assets/css/theme-dark.min.css" data-hs-appearance="dark" as="style">

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
        .navbar-brand-logo{
            max-width: 17.5rem !important;
        }
    </style>

    <script src="./assets/js/hs.theme-appearance.js"></script>

    <!-- ========== HEADER ========== -->
    <header id="header"
        class="navbar navbar-expand-lg navbar-center navbar-light bg-white navbar-absolute-top navbar-show-hide"
        data-hs-header-options='{
            "fixMoment": 0,
            "fixEffect": "slide"
          }'>
        <div class="container-lg">
            <nav class="js-mega-menu navbar-nav-wrap">
                <!-- Logo -->
                <a class="navbar-brand" href="{{ url('/dashboard') }}" aria-label="">
                    {{-- Desktop Light Theme --}}
                    <img class="navbar-brand-logo d-none d-md-block" 
                        src="{{ asset('assets/images/BlackFullLogo.png') }}" 
                        alt="Logo" data-hs-theme-appearance="default">

                    {{-- Desktop Dark Theme --}}
                    <img class="navbar-brand-logo d-none d-md-block" 
                        src="{{ asset('assets/images/WhiteFullLogo.png') }}" 
                        alt="Logo" data-hs-theme-appearance="dark">

                    {{-- Mobile Logo (Same for both themes) --}}
                    <img class="navbar-brand-logo-mini d-md-none" style="width: 100px;"
                        src="{{ asset('assets/images/shortLogo.png') }}" 
                        alt="Logo" data-hs-theme-appearance="default">

                    <img class="navbar-brand-logo-mini d-md-none" style="width: 100px;"
                        src="{{ asset('assets/images/shortLogo.png') }}" 
                        alt="Logo" data-hs-theme-appearance="dark">
                </a>
                <!-- End Logo -->

                <!-- Secondary Content -->
                <div class="navbar-nav-wrap-secondary-content">
                    <!-- Style Switcher -->
                    <div class="dropdown">
                        <button type="button" class="btn btn-ghost-secondary btn-icon rounded-circle"
                            id="selectThemeDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                            data-bs-dropdown-animation>

                        </button>

                        <div class="dropdown-menu dropdown-menu-end navbar-dropdown-menu navbar-dropdown-menu-borderless"
                            aria-labelledby="selectThemeDropdown">
                            <a class="dropdown-item" href="#" data-icon="bi-moon-stars" data-value="auto">
                                <i class="bi-moon-stars me-2"></i>
                                <span class="text-truncate" title="Auto (system default)">Auto (system default)</span>
                            </a>
                            <a class="dropdown-item" href="#" data-icon="bi-brightness-high" data-value="default">
                                <i class="bi-brightness-high me-2"></i>
                                <span class="text-truncate" title="Default (light mode)">Default (light mode)</span>
                            </a>
                            <a class="dropdown-item active" href="#" data-icon="bi-moon" data-value="dark">
                                <i class="bi-moon me-2"></i>
                                <span class="text-truncate" title="Dark">Dark</span>
                            </a>
                        </div>
                    </div>

                    <!-- End Style Switcher -->

                    <a class="btn btn-primary navbar-btn" href="{{ url('/login') }}">Get Started</a>
                </div>
                <!-- End Secondary Content -->

                <!-- Toggler -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarContainerNavDropdown" aria-controls="navbarContainerNavDropdown"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-default">
                        <i class="bi-list"></i>
                    </span>
                    <span class="navbar-toggler-toggled">
                        <i class="bi-x"></i>
                    </span>
                </button>
                <!-- End Toggler -->

                <!-- Collapse -->
                <div class="collapse navbar-collapse" id="navbarContainerNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link py-2 py-lg-3" href="{{ url('/') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-2 py-lg-3" href="#">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-2 py-lg-3" href="#">Contact Us</a>
                        </li>
                    </ul>
                </div>
                <!-- End Collapse -->
            </nav>
        </div>
    </header>

    <!-- ========== END HEADER ========== -->

    <!-- ========== MAIN CONTENT ========== -->
    <main id="content" role="main" class="main">
        <!-- Hero -->
        <div class="overflow-hidden gradient-radial-sm-primary">
            <div class="container-lg content-space-t-3 content-space-t-lg-4 content-space-b-2">
                <div class="w-lg-75 text-center mx-lg-auto text-center mx-auto">
                    <!-- Heading -->
                    <div class="mb-7 animated fadeInUp">
                        <h1 class="display-2 mb-3">GYM Management System <span
                                class="text-primary text-highlight-warning">For All GYM Owners!</span></h1>
                        <p class="fs-2">user friendly and highly customizable GYM Management System.</p>
                    </div>
                    <!-- End Heading -->
                </div>

                <!-- Browser Device -->
                <div class="animated fadeInUp">
                    <figure class="js-img-comp device-browser device-browser-lg">
                        <div class="device-browser-header">
                            <div class="device-browser-header-btn-list">
                                <span class="device-browser-header-btn-list-btn"></span>
                                <span class="device-browser-header-btn-list-btn"></span>
                                <span class="device-browser-header-btn-list-btn"></span>
                            </div>
                            <div class="device-browser-header-browser-bar">www.htmlstream.com/front/</div>
                        </div>

                        <div class="position-relative">
                            <!-- Loader -->
                            <div
                                class="js-img-comp-loader position-absolute d-flex align-items-center justify-content-center bg-white w-100 h-100 zi-999">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <!-- End Loader -->

                            <div class="device-browser-frame">
                                <div class="js-img-comp-container hs-img-comp-container">
                                    <img class="hs-img-comp hs-img-comp-a"
                                        src="{{ asset('assets/images/Demo/DarkDashboard.png') }}"
                                        alt="Image Description">

                                    <div class="js-img-comp-wrapper hs-img-comp-wrapper">
                                        <img class="hs-img-comp hs-img-comp-b"
                                            src="{{ asset('assets/images/Demo/WhiteDashboard.png') }}"
                                            alt="Image Description">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </figure>
                </div>
                <!-- End Browser Device -->
            </div>
        </div>
        <!-- End Hero -->

        <!-- Card Grid -->
        <div class="container-lg content-space-t-lg-2 content-space-b-2 content-space-b-lg-3">
            <!-- Heading -->
            <div class="w-lg-75 text-center mx-lg-auto mb-7 mb-md-10">
                <h2 class="display-4">Creative <span class="text-primary">demos</span></h2>
                <p class="lead">Hop in and see Front's power in action in these different layout options.</p>
            </div>
            <!-- End Heading -->

            <div class="row">
                <div class="col-md-6 mb-4">
                    <!-- Card -->
                    <a class="card card-lg card-transition h-100 bg-light border-0 shadow-none overflow-hidden"
                        href="./index.html">
                        <div class="card-body">
                            <h2 class="card-title h1 text-inherit">Vertical Sidebar</h2>
                            <p class="card-text lead">Experience a native pilled-styled sidebar that can be minimized
                                on the fly.</p>
                        </div>
                        <div class="card-footer border-0 pt-0 mb-n4 me-n6">
                            <img class="img-fluid shadow-lg" src="{{ asset('assets/images/Demo/DarkDashboard.png') }}"
                                alt="Image Description" data-hs-theme-appearance="default">
                            <img class="img-fluid shadow-lg"
                                src="{{ asset('assets/images/Demo/WhiteDashboard.png') }}" alt="Image Description"
                                data-hs-theme-appearance="dark">
                        </div>
                    </a>
                    <!-- End Card -->
                </div>
                <!-- End Col -->

                <div class="col-md-6 mb-4">
                    <!-- Card -->
                    <a class="card card-lg card-transition h-100 bg-light border-0 shadow-none overflow-hidden"
                        href="./dashboard-default-dark.html">
                        <div class="card-body">
                            <h2 class="card-title h1 text-inherit">Dark</h2>
                            <p class="card-text lead">Leverage Front's user-friendly and yet powerful dark mode, which
                                adapts to the browser's default mode.</p>
                        </div>
                        <div class="card-footer border-0 pt-0 mb-n4 me-n6">
                            <img class="img-fluid shadow-lg"
                                src="{{ asset('assets/images/Demo/DarkDashboard.png') }}" alt="Image Description">
                        </div>
                    </a>
                    <!-- End Card -->
                </div>
                <!-- End Col -->

                <div class="col-md-6 mb-4">
                    <!-- Card -->
                    <a class="card card-lg card-transition h-100 bg-light border-0 shadow-none overflow-hidden"
                        href="./dashboard-default-dark-sidebar.html">
                        <div class="card-body">
                            <h2 class="card-title h1 text-inherit">Dark Sidebar</h2>
                            <p class="card-text lead">Build a better experience - mix and match dark with light.</p>
                        </div>
                        <div class="card-footer border-0 pt-0 mb-n4 me-n6">
                            <img class="img-fluid shadow-lg"
                                src="{{ asset('assets/images/Demo/DarkDashboard.png') }}" alt="Image Description"
                                data-hs-theme-appearance="default">
                            <img class="img-fluid shadow-lg"
                                src="{{ asset('assets/images/Demo/WhiteDashboard.png') }}" alt="Image Description"
                                data-hs-theme-appearance="dark">
                        </div>
                    </a>
                    <!-- End Card -->
                </div>
                <!-- End Col -->

                <div class="col-md-6 mb-4">
                    <!-- Card -->
                    <a class="card card-lg card-transition h-100 bg-light border-0 shadow-none overflow-hidden"
                        href="./dashboard-default-light-sidebar.html">
                        <div class="card-body">
                            <h2 class="card-title h1 text-inherit">Light Sidebar</h2>
                            <p class="card-text lead">Link content types with a light gray sidebar color palette.</p>
                        </div>
                        <div class="card-footer border-0 pt-0 mb-n4 me-n6">
                            <img class="img-fluid shadow-lg"
                                src="{{ asset('assets/images/Demo/DarkDashboard.png') }}" alt="Image Description"
                                data-hs-theme-appearance="default">
                            <img class="img-fluid shadow-lg"
                                src="{{ asset('assets/images/Demo/WhiteDashboard.png') }}" alt="Image Description"
                                data-hs-theme-appearance="dark">
                        </div>
                    </a>
                    <!-- End Card -->
                </div>
                <!-- End Col -->

                <div class="col-md-6 mb-4">
                    <!-- Card -->
                    <a class="card card-lg card-transition h-100 bg-light border-0 shadow-none overflow-hidden"
                        href="./dashboard-default-double-line.html">
                        <div class="card-body">
                            <h2 class="card-title h1 text-inherit">Double Line</h2>
                            <p class="card-text lead">Present web app in full content with a double line collapsible
                                navigation bar.</p>
                        </div>
                        <div class="card-footer border-0 pt-0 mb-n4 me-n6">
                            <img class="img-fluid shadow-lg"
                                src="{{ asset('assets/images/Demo/DarkDashboard.png') }}" alt="Image Description"
                                data-hs-theme-appearance="default">
                            <img class="img-fluid shadow-lg"
                                src="{{ asset('assets/images/Demo/WhiteDashboard.png') }}" alt="Image Description"
                                data-hs-theme-appearance="dark">
                        </div>
                    </a>
                    <!-- End Card -->
                </div>
                <!-- End Col -->

                <div class="col-md-6 mb-4">
                    <!-- Card -->
                    <a class="card card-lg card-transition h-100 bg-light border-0 shadow-none overflow-hidden"
                        href="./dashboard-default-collapsible-layout.html">
                        <div class="card-body">
                            <h2 class="card-title h1 text-inherit">Collapsible Navbar</h2>
                            <p class="card-text lead">Present web app in full content with a single collapsible
                                navigation bar.</p>
                        </div>
                        <div class="card-footer border-0 pt-0 mb-n4 me-n6">
                            <img class="img-fluid shadow-lg"
                                src="{{ asset('assets/images/Demo/DarkDashboard.png') }}" alt="Image Description"
                                data-hs-theme-appearance="default">
                            <img class="img-fluid shadow-lg"
                                src="{{ asset('assets/images/Demo/WhiteDashboard.png') }}" alt="Image Description"
                                data-hs-theme-appearance="dark">
                        </div>
                    </a>
                    <!-- End Card -->
                </div>
                <!-- End Col -->

                <div class="col-md-6 mb-4 mb-md-0">
                    <!-- Card -->
                    <a class="card card-lg card-transition h-100 bg-light border-0 shadow-none overflow-hidden"
                        href="./dashboard-default-sidebar-detached.html">
                        <div class="card-body">
                            <h2 class="card-title h1 text-inherit">Sidebar Detached</h2>
                            <p class="card-text lead">Choose one of two detached sidebar options to create better
                                navigation options and usability.</p>
                        </div>
                        <div class="card-footer border-0 pt-0 mb-n4 me-n6">
                            <img class="img-fluid shadow-lg"
                                src="{{ asset('assets/images/Demo/DarkDashboard.png') }}" alt="Image Description"
                                data-hs-theme-appearance="default">
                            <img class="img-fluid shadow-lg"
                                src="{{ asset('assets/images/Demo/WhiteDashboard.png') }}" alt="Image Description"
                                data-hs-theme-appearance="dark">
                        </div>
                    </a>
                    <!-- End Card -->
                </div>
                <!-- End Col -->

                <div class="col-md-6">
                    <!-- Card -->
                    <a class="card card-lg card-transition h-100 bg-light border-0 shadow-none overflow-hidden"
                        href="./dashboard-default-sidebar-detached-overlay.html">
                        <div class="card-body">
                            <h2 class="card-title h1 text-inherit">Sidebar Detached Overlay</h2>
                            <p class="card-text lead">Provide more navigation options and usability on page level with
                                overlay sidebar detached.</p>
                        </div>
                        <div class="card-footer border-0 pt-0 mb-n4 me-n6">
                            <img class="img-fluid shadow-lg"
                                src="{{ asset('assets/images/Demo/DarkDashboard.png') }}" alt="Image Description"
                                data-hs-theme-appearance="default">
                            <img class="img-fluid shadow-lg"
                                src="{{ asset('assets/images/Demo/WhiteDashboard.png') }}" alt="Image Description"
                                data-hs-theme-appearance="dark">
                        </div>
                    </a>
                    <!-- End Card -->
                </div>
                <!-- End Col -->
            </div>
            <!-- End Row -->
        </div>
        <!-- End Card Grid -->

        <!-- Testimonials -->
        <div class="container-lg">
            <div class="bg-light content-space-2 rounded-3 px-5">
                <div class="w-md-70 text-center mx-md-auto">
                    <div class="mb-4">
                        <img class="img-fluid mx-auto" src="{{ asset('assets/images/Demo/DarkDashboard.png') }}"
                            alt="Image Description" data-hs-theme-appearance="default" style="max-width: 10rem;">
                        <img class="img-fluid mx-auto" src="{{ asset('assets/images/Demo/WhiteDashboard.png') }}"
                            alt="Image Description" data-hs-theme-appearance="dark" style="max-width: 10rem;">
                    </div>

                    <p class="fs-2 text-dark mb-4"><em>This is a perfect theme for a modern web application. <span
                                class="text-highlight-warning">There was clearly a lot of thought that went into
                                designing</span> all of the components to look coherent and work well together in
                            various grid layouts.</em></p>

                    <h3 class="mb-0">Anton</h3>
                    <p class="fs-4 mb-0">Happy customer</p>
                </div>
            </div>
        </div>
        <!-- End Testimonials -->

        <!-- Card Grid -->
        <div class="container-lg content-space-2 content-space-lg-3">
            <!-- Heading -->
            <div class="w-lg-75 text-center mx-lg-auto mb-7 mb-md-10">
                <h2 class="display-4">Packed with <span class="text-primary">features</span> you already love</h2>
                <p class="lead">The Front features can be flexed according to your needs with dozens of options
                    available and mix-and-match possibilities.</p>
            </div>
            <!-- End Heading -->

            <div class="row">
                <div class="col-md-7 mb-4">
                    <!-- Card -->
                    <div class="card card-lg h-100 bg-light border-0 shadow-none overflow-hidden">
                        <div class="card-body">
                            <h2 class="card-title h1 text-inherit">Calendars</h2>
                            <p class="card-text lead">Front offers all kinds of calendar components for choosing date
                                ranges, dates and times.</p>
                        </div>
                        <div class="card-footer border-0 pt-0 mb-n4 me-n6">
                            <img class="img-fluid" src="{{ asset('assets/images/Demo/DarkDashboard.png') }}"
                                alt="Image Description" data-hs-theme-appearance="default">
                            <img class="img-fluid" src="{{ asset('assets/images/Demo/WhiteDashboard.png') }}"
                                alt="Image Description" data-hs-theme-appearance="dark">
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
                <!-- End Col -->

                <div class="col-md-5 mb-4">
                    <!-- Card -->
                    <div class="card card-lg h-100 bg-light border-0 shadow-none overflow-hidden">
                        <div class="card-body">
                            <h2 class="card-title h1 text-inherit">2 Sidebar menu options</h2>
                            <p class="card-text lead">Choose between pill or tab navigation style on the sidebar.</p>
                        </div>
                        <div class="card-footer border-0 pt-0 mb-n4 me-n6">
                            <img class="img-fluid" src="{{ asset('assets/images/Demo/DarkDashboard.png') }}"
                                alt="Image Description" data-hs-theme-appearance="default">
                            <img class="img-fluid" src="{{ asset('assets/images/Demo/WhiteDashboard.png') }}"
                                alt="Image Description" data-hs-theme-appearance="dark">
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
                <!-- End Col -->

                <div class="col-md-5 mb-4">
                    <!-- Card -->
                    <div class="card card-lg h-100 bg-light border-0 shadow-none overflow-hidden">
                        <div class="card-body">
                            <h2 class="card-title h1 text-inherit">Datatables</h2>
                            <p class="card-text lead">Showcase your latest work with datatable options that provide a
                                powerful portfolio system, beautiful content designs or any other ordered grid content.
                            </p>
                        </div>
                        <div class="card-footer border-0 pt-0 mb-n4 me-n6">
                            <img class="img-fluid" src="{{ asset('assets/images/Demo/DarkDashboard.png') }}"
                                alt="Image Description" data-hs-theme-appearance="default">
                            <img class="img-fluid" src="{{ asset('assets/images/Demo/WhiteDashboard.png') }}"
                                alt="Image Description" data-hs-theme-appearance="dark">
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
                <!-- End Col -->

                <div class="col-md-7 mb-4">
                    <!-- Card -->
                    <div class="card card-lg h-100 bg-light border-0 shadow-none overflow-hidden">
                        <div class="card-body">
                            <h2 class="card-title h1 text-inherit">Chart.js</h2>
                            <p class="card-text lead">Allow cross-functional charts to deliver stunning content, data
                                and all kinds of information faster no matter use cases and devices.</p>
                        </div>
                        <div class="card-footer border-0 pt-0 mb-n4 me-n6">
                            <img class="img-fluid" src="{{ asset('assets/images/Demo/DarkDashboard.png') }}"
                                alt="Image Description" data-hs-theme-appearance="default">
                            <img class="img-fluid" src="{{ asset('assets/images/Demo/WhiteDashboard.png') }}"
                                alt="Image Description" data-hs-theme-appearance="dark">
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
                <!-- End Col -->

                <div class="col-md-7 mb-4 mb-md-0">
                    <!-- Card -->
                    <div class="card card-lg h-100 bg-light border-0 shadow-none overflow-hidden">
                        <div class="card-body">
                            <h2 class="card-title h1 text-inherit">Advanced Forms</h2>
                            <p class="card-text lead">Upload images, videos or any files, copy to clipboard, toggle
                                passwords, search, add fields, count characters and discover more customizable and
                                feature-rich plugins.</p>
                        </div>
                        <div class="card-footer border-0 pt-0 mb-n4 me-n6">
                            <img class="img-fluid" src="{{ asset('assets/images/Demo/DarkDashboard.png') }}"
                                alt="Image Description" data-hs-theme-appearance="default">
                            <img class="img-fluid" src="{{ asset('assets/images/Demo/WhiteDashboard.png') }}"
                                alt="Image Description" data-hs-theme-appearance="dark">
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
                <!-- End Col -->

                <div class="col-md-5">
                    <!-- Card -->
                    <div class="card card-lg h-100 bg-light border-0 shadow-none overflow-hidden">
                        <div class="card-body">
                            <h2 class="card-title h1 text-inherit">Step Forms (Wizards)</h2>
                            <p class="card-text lead">Create multi-step forms, validate and navigate through steps to
                                get more leads and increase engagement.</p>
                        </div>
                        <div class="card-footer border-0 pt-0 mb-n4 me-n6">
                            <img class="img-fluid" src="{{ asset('assets/images/Demo/DarkDashboard.png') }}"
                                alt="Image Description" data-hs-theme-appearance="default">
                            <img class="img-fluid" src="{{ asset('assets/images/Demo/WhiteDashboard.png') }}"
                                alt="Image Description" data-hs-theme-appearance="dark">
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
                <!-- End Col -->
            </div>
            <!-- End Row -->
        </div>
        <!-- End Card Grid -->

        <!-- Features -->
        <div class="container-lg content-space-b-2 content-space-b-lg-3">
            <ul class="list-inline list-py-2 list-px-1 text-center mb-0">
                <li class="list-inline-item">
                    <span class="badge bg-soft-secondary text-dark fs-4 py-2 px-3">Bootstrap Icons</span>
                </li>
            </ul>
        </div>
        <!-- End Features -->

        <!-- Sliding Image -->
        <div class="content-space-b-2">
            <!-- Heading -->
            <div class="container-lg">
                <div class="w-lg-75 text-center mx-lg-auto mb-7 mb-md-10">
                    <h2 class="display-4">Design solutions for any use cases</h2>
                    <p class="lead">Whether you're creating a web application, dashboard, admin panels, or SASS based
                        interface — Front Dashboard helps you create the best possible web application projects.</p>
                </div>
            </div>
            <!-- End Heading -->

            <div class="sliding-img mb-5">
                <div class="sliding-img-frame-to-start"
                    style="background-image: url(./assets/images/Demo/DarkDashboard.png);"
                    data-hs-theme-appearance="default"></div>
                <div class="sliding-img-frame-to-start"
                    style="background-image: url(./assets/images/Demo/WhiteDashboard.png);"
                    data-hs-theme-appearance="dark"></div>
            </div>

            <div class="sliding-img">
                <div class="sliding-img-frame-to-end"
                    style="background-image: url(./assets/images/Demo/DarkDashboard.png);"
                    data-hs-theme-appearance="default"></div>
                <div class="sliding-img-frame-to-end"
                    style="background-image: url(./assets/images/Demo/WhiteDashboard.png);"
                    data-hs-theme-appearance="dark"></div>
            </div>
        </div>
        <!-- End Sliding Image -->

        <!-- Stats -->
        <div class="container-lg content-space-b-2 content-space-b-lg-3">
            <div class="row">
                <div class="col-sm-6 col-lg-3 mb-5 mb-lg-0">
                    <div class="text-center">
                        <span class="display-3 fw-normal text-dark">60+</span>
                        <p class="fs-3 mb-0">Components</p>
                    </div>
                </div>
                <!-- End Col -->

                <div class="col-sm-6 col-lg-3 mb-5 mb-lg-0">
                    <div class="text-center">
                        <span class="display-3 fw-normal text-dark">50+</span>
                        <p class="fs-3 mb-0">Plugins</p>
                    </div>
                </div>
                <!-- End Col -->

                <div class="col-sm-6 col-lg-3 mb-5 mb-sm-0">
                    <div class="text-center">
                        <span class="display-3 fw-normal text-dark">450+</span>
                        <p class="fs-3 mb-0">Combinations</p>
                    </div>
                </div>
                <!-- End Col -->

                <div class="col-sm-6 col-lg-3">
                    <div class="text-center">
                        <span class="display-3 fw-normal text-dark">47k+</span>
                        <p class="fs-3 mb-0">Happy customers</p>
                    </div>
                </div>
                <!-- End Col -->
            </div>
            <!-- End Row -->
        </div>
        <!-- End Stats -->

        <!-- Testimonials -->
        <div class="container-lg">
            <div class="bg-light content-space-2 rounded-3 px-5">
                <div class="w-md-70 text-center mx-md-auto">
                    <div class="mb-4">
                        <img class="img-fluid mx-auto" src="{{ asset('assets/images/Demo/DarkDashboard.png') }}"
                            alt="Image Description" data-hs-theme-appearance="default" style="max-width: 10rem;">
                        <img class="img-fluid mx-auto" src="{{ asset('assets/images/Demo/WhiteDashboard.png') }}"
                            alt="Image Description" data-hs-theme-appearance="dark" style="max-width: 10rem;">
                    </div>

                    <p class="fs-2 text-dark mb-4"><em>The theme has a very professional look, bringing a more modern
                            and clean style to the application. <span class="text-highlight-warning">The documentation
                                is extraordinarily rich and complete</span>, helping implementation.</em></p>

                    <h3 class="mb-0">Marcos</h3>
                    <p class="fs-4 mb-0">Happy customer</p>
                </div>
            </div>
        </div>
        <!-- End Testimonials -->

        <!-- Features -->
        <div class="container-lg content-space-2 content-space-lg-4">
            <!-- Heading -->
            <div class="w-lg-75 text-center mx-lg-auto mb-7 mb-md-10">
                <h2 class="display-4">Applications</h2>
                <p class="lead">Made for everyone, build anything with multiple pre-built applications.</p>
            </div>
            <!-- End Heading -->

            <!-- Card Grid -->
            <div class="row align-items-md-center content-space-b-1 content-space-b-lg-2">
                <div class="col-md-6 order-md-2 mb-10 mb-md-0">
                    <!-- Browser Device -->
                    <figure class="device-browser">
                        <div class="device-browser-header">
                            <div class="device-browser-header-btn-list">
                                <span class="device-browser-header-btn-list-btn"></span>
                                <span class="device-browser-header-btn-list-btn"></span>
                                <span class="device-browser-header-btn-list-btn"></span>
                            </div>
                            <div class="device-browser-header-browser-bar">www.htmlstream.com/front/</div>
                        </div>

                        <div class="device-browser-frame">
                            <img class="img-fluid shadow-lg"
                                src="{{ asset('assets/images/Demo/DarkDashboard.png') }}" alt="Image Description"
                                data-hs-theme-appearance="default">
                            <img class="img-fluid shadow-lg"
                                src="{{ asset('assets/images/Demo/WhiteDashboard.png') }}" alt="Image Description"
                                data-hs-theme-appearance="dark">
                        </div>

                        <div class="position-absolute bottom-0 start-0 w-100 h-100 bg-soft-primary zi-n1 mb-n6 ms-n6">
                        </div>
                    </figure>
                    <!-- End Browser Device -->
                </div>
                <!-- End Col -->

                <div class="col-md-6">
                    <div class="pe-md-7">
                        <div class="mb-5">
                            <div class="mb-5">
                                <span class="badge border border-dark text-dark">Application</span>
                            </div>

                            <h2 class="mb-3">Kanban</h2>
                            <p class="fs-4">A board that visually depicts work at various stages of a process using
                                cards to represent work items and columns to represent each stage of the process.</p>
                        </div>
                        <a class="btn btn-primary" target="_blank" href="./apps-kanban.html">Preview Kanban <i
                                class="bi-box-arrow-up-right ms-2"></i></a>
                    </div>
                </div>
                <!-- End Col -->
            </div>
            <!-- End Card Grid -->

            <!-- Card Grid -->
            <div class="row align-items-md-center content-space-1 content-space-b-lg-2">
                <div class="col-md-6 order-md-2 mb-10 mb-md-0">
                    <!-- Browser Device -->
                    <figure class="device-browser">
                        <div class="device-browser-header">
                            <div class="device-browser-header-btn-list">
                                <span class="device-browser-header-btn-list-btn"></span>
                                <span class="device-browser-header-btn-list-btn"></span>
                                <span class="device-browser-header-btn-list-btn"></span>
                            </div>
                            <div class="device-browser-header-browser-bar">www.htmlstream.com/front/</div>
                        </div>

                        <div class="device-browser-frame">
                            <img class="img-fluid shadow-lg"
                                src="{{ asset('assets/images/Demo/DarkDashboard.png') }}" alt="Image Description"
                                data-hs-theme-appearance="default">
                            <img class="img-fluid shadow-lg"
                                src="{{ asset('assets/images/Demo/WhiteDashboard.png') }}" alt="Image Description"
                                data-hs-theme-appearance="dark">
                        </div>

                        <div class="position-absolute bottom-0 start-0 w-100 h-100 bg-soft-danger zi-n1 mb-n6 ms-n6">
                        </div>
                    </figure>
                    <!-- End Browser Device -->
                </div>
                <!-- End Col -->

                <div class="col-md-6">
                    <div class="pe-md-7">
                        <div class="mb-5">
                            <div class="mb-5">
                                <span class="badge border border-dark text-dark">Application</span>
                            </div>

                            <h2 class="mb-3">Calendar</h2>
                            <p class="fs-4">Multiple views of your day, week and month, guest invites, calendar on
                                the web and more. It allows users to create, edit events, fill in quickly and easily.
                            </p>
                        </div>
                        <a class="btn btn-primary" target="_blank" href="./apps-calendar.html">Preview Calendar <i
                                class="bi-box-arrow-up-right ms-2"></i></a>
                    </div>
                </div>
                <!-- End Col -->
            </div>
            <!-- End Card Grid -->

            <!-- Card Grid -->
            <div class="row align-items-md-center content-space-1 content-space-b-lg-2">
                <div class="col-md-6 order-md-2 mb-10 mb-md-0">
                    <!-- Browser Device -->
                    <figure class="device-browser">
                        <div class="device-browser-header">
                            <div class="device-browser-header-btn-list">
                                <span class="device-browser-header-btn-list-btn"></span>
                                <span class="device-browser-header-btn-list-btn"></span>
                                <span class="device-browser-header-btn-list-btn"></span>
                            </div>
                            <div class="device-browser-header-browser-bar">www.htmlstream.com/front/</div>
                        </div>

                        <div class="device-browser-frame">
                            <img class="img-fluid shadow-lg"
                                src="{{ asset('assets/images/Demo/DarkDashboard.png') }}" alt="Image Description"
                                data-hs-theme-appearance="default">
                            <img class="img-fluid shadow-lg"
                                src="{{ asset('assets/images/Demo/WhiteDashboard.png') }}" alt="Image Description"
                                data-hs-theme-appearance="dark">
                        </div>

                        <div class="position-absolute bottom-0 start-0 w-100 h-100 bg-soft-warning zi-n1 mb-n6 ms-n6">
                        </div>
                    </figure>
                    <!-- End Browser Device -->
                </div>
                <!-- End Col -->

                <div class="col-md-6">
                    <div class="pe-md-7">
                        <div class="mb-5">
                            <div class="mb-5">
                                <span class="badge border border-dark text-dark">Application</span>
                            </div>

                            <h2 class="mb-3">Invoice Generator</h2>
                            <p class="fs-4">Quickly make invoices with Front's attractive invoice template straight
                                from your web browser.</p>
                        </div>
                        <a class="btn btn-primary" target="_blank" href="./apps-invoice-generator.html">Preview
                            Invoice Generator <i class="bi-box-arrow-up-right ms-2"></i></a>
                    </div>
                </div>
                <!-- End Col -->
            </div>
            <!-- End Card Grid -->

            <!-- Card Grid -->
            <div class="row align-items-md-center content-space-t-1">
                <div class="col-md-6 order-md-2 mb-10 mb-md-0">
                    <!-- Browser Device -->
                    <figure class="device-browser">
                        <div class="device-browser-header">
                            <div class="device-browser-header-btn-list">
                                <span class="device-browser-header-btn-list-btn"></span>
                                <span class="device-browser-header-btn-list-btn"></span>
                                <span class="device-browser-header-btn-list-btn"></span>
                            </div>
                            <div class="device-browser-header-browser-bar">www.htmlstream.com/front/</div>
                        </div>

                        <div class="device-browser-frame">
                            <img class="img-fluid shadow-lg"
                                src="{{ asset('assets/images/Demo/DarkDashboard.png') }}" alt="Image Description"
                                data-hs-theme-appearance="default">
                            <img class="img-fluid shadow-lg"
                                src="{{ asset('assets/images/Demo/WhiteDashboard.png') }}" alt="Image Description"
                                data-hs-theme-appearance="dark">
                        </div>

                        <div class="position-absolute bottom-0 start-0 w-100 h-100 bg-soft-success zi-n1 mb-n6 ms-n6">
                        </div>
                    </figure>
                    <!-- End Browser Device -->
                </div>
                <!-- End Col -->

                <div class="col-md-6">
                    <div class="pe-md-7">
                        <div class="mb-5">
                            <div class="mb-5">
                                <span class="badge border border-dark text-dark">Application</span>
                            </div>

                            <h2 class="mb-3">File Manager</h2>
                            <p class="fs-4">Please your visitors with eye-catching and exciting file manager.
                                Different options and settings to manage your site.</p>
                        </div>
                        <a class="btn btn-primary" target="_blank" href="./apps-file-manager.html">Preview File
                            Manager <i class="bi-box-arrow-up-right ms-2"></i></a>
                    </div>
                </div>
                <!-- End Col -->
            </div>
            <!-- End Card Grid -->
        </div>
        <!-- End Features -->

        <!-- Documentation -->
        <div class="container-lg">
            <div class="bg-dark position-relative rounded overflow-hidden pt-4 px-4 pt-sm-10 px-sm-10">
                <!-- Heading -->
                <div class="w-lg-75 text-center mx-lg-auto mb-7 mb-md-10">
                    <h2 class="display-4 text-white">Documentation</h2>
                    <p class="lead text-white-70">Get started with Front - Multipurpose Responsive Template for
                        building responsive, mobile-first sites, with Bootstrap and a template starter page.</p>
                </div>
                <!-- End Heading -->

                <img class="img-fluid" src="{{ asset('assets/images/Demo/DarkDashboard.png') }}"
                    alt="Image Description" data-hs-theme-appearance="default">
                <img class="img-fluid" src="{{ asset('assets/images/Demo/WhiteDashboard.png') }}"
                    alt="Image Description" data-hs-theme-appearance="dark">

                <div class="gradient-y-lg-dark position-absolute bottom-0 start-0 end-0 w-100 d-flex justify-content-center zi-1 pb-8"
                    style="padding-top: 13rem;">
                    <a class="btn btn-primary btn-lg" href="./documentation/index.html">Browse Documentation</a>
                </div>
            </div>
        </div>
        <!-- End Documentation -->
        
        <!-- Testimonials -->
        <div class="container-lg">
            <div class="bg-light content-space-2 rounded-3 px-5">
                <div class="w-md-70 text-center mx-md-auto">
                    <div class="mb-4">
                        <img class="img-fluid mx-auto" src="{{ asset('assets/images/Demo/DarkDashboard.png') }}"
                            alt="Image Description" data-hs-theme-appearance="default" style="max-width: 10rem;">
                        <img class="img-fluid mx-auto" src="{{ asset('assets/images/Demo/WhiteDashboard.png') }}"
                            alt="Image Description" data-hs-theme-appearance="dark" style="max-width: 10rem;">
                    </div>

                    <p class="fs-2 text-dark mb-4"><em>This theme is really great, as back end developer <span
                                class="text-highlight-warning">I was able to build an impressive front end using this
                                theme in plain JavaScript vanilla. The source code is clear and the documentation as
                                well, for me it's the best purchase I made with this team and I am watching
                                evolution.</span> Thank you so much for such quality and price. Keep going!</em></p>

                    <h3 class="mb-0">David</h3>
                    <p class="fs-4 mb-0">Happy customer</p>
                </div>
            </div>
        </div>
        <!-- End Testimonials -->

        <!-- Pricing -->
        <div class="overflow-hidden">
            <div class="container-lg content-space-t-2 content-space-t-lg-3">
                <!-- Heading -->
                <div class="w-lg-75 text-center mx-lg-auto mb-7 mb-md-10">
                    <h2 class="display-4">Pricing</h2>
                    <p class="lead">Whatever your status, our offers evolve according to your needs.</p>
                </div>
                <!-- End Heading -->

                <div class="w-md-75 mx-md-auto">
                    <div class="position-relative">
                        <div class="bg-dark rounded-2 p-5">
                            <div class="row align-items-sm-center">
                                <div class="col">
                                    <h3 class="text-white mb-1">Single</h3>
                                    <span class="d-block text-white-70">Single site</span>
                                </div>
                                <!-- End Col -->

                                <div class="col-sm-7 col-md-5">
                                    <p class="text-white-70 mb-0">Ideal for corporate, portfolio, blog, shop and many
                                        more.</p>
                                </div>
                                <!-- End Col -->

                                <div class="col-12 col-md col-lg-4 text-lg-end mt-3 mt-lg-0">
                                    <div class="d-grid">
                                        <a class="btn btn-primary"
                                            href="https://themes.getbootstrap.com/product/front-admin-dashboard-template/"
                                            target="_blank">Buy for $49</a>
                                    </div>
                                </div>
                                <!-- End Col -->
                            </div>
                            <!-- End Row -->

                            <hr class="bg-soft-light opacity-50">

                            <div class="row align-items-sm-center">
                                <div class="col">
                                    <h3 class="text-white mb-1">Multisite</h3>
                                    <span class="d-block text-white-70">Unlimited sites</span>
                                </div>
                                <!-- End Col -->

                                <div class="col-sm-7 col-md-5">
                                    <p class="text-white-70 mb-0">All the same examples as the Standard License, but
                                        you could build all of them with a single Multisite license.</p>
                                </div>
                                <!-- End Col -->

                                <div class="col-12 col-md col-lg-4 text-lg-end mt-3 mt-lg-0">
                                    <div class="d-grid">
                                        <a class="btn btn-primary"
                                            href="https://themes.getbootstrap.com/product/front-admin-dashboard-template/"
                                            target="_blank">Buy for $149</a>
                                    </div>
                                </div>
                                <!-- End Col -->
                            </div>
                            <!-- End Row -->

                            <hr class="bg-soft-light opacity-50">

                            <div class="row align-items-sm-center">
                                <div class="col">
                                    <h3 class="text-white mb-1">Extended</h3>
                                    <span class="d-block text-white-70">For paying users</span>
                                </div>
                                <!-- End Col -->

                                <div class="col-sm-7 col-md-5">
                                    <p class="text-white-70 mb-0">Best suited for "paid subscribers" and SaaS analytics
                                        applications.</p>
                                </div>
                                <!-- End Col -->

                                <div class="col-12 col-md col-lg-4 text-lg-end mt-3 mt-lg-0">
                                    <div class="d-grid">
                                        <a class="btn btn-primary"
                                            href="https://themes.getbootstrap.com/product/front-admin-dashboard-template/"
                                            target="_blank">Buy for $599</a>
                                    </div>
                                </div>
                                <!-- End Col -->
                            </div>
                            <!-- End Row -->
                        </div>

                        <div class="d-none d-md-block position-absolute bottom-0 start-0">
                            <img class="img-fluid" src="./assets/svg/illustrations/oc-peeking.svg"
                                alt="Image Description" data-hs-theme-appearance="default"
                                style="max-width: 8rem; margin-left: -7.8125rem;">
                            <img class="img-fluid" src="./assets/svg/illustrations-light/oc-peeking.svg"
                                alt="Image Description" data-hs-theme-appearance="dark"
                                style="max-width: 8rem; margin-left: -7.8125rem;">
                        </div>

                        <div class="d-none d-md-block position-absolute top-50 end-0 translate-middle-y">
                            <img class="img-fluid" src="./assets/svg/illustrations/oc-on-the-go.svg"
                                alt="Image Description" data-hs-theme-appearance="default"
                                style="max-width: 15rem; margin-right: -15rem;">
                            <img class="img-fluid" src="./assets/svg/illustrations-light/oc-on-the-go.svg"
                                alt="Image Description" data-hs-theme-appearance="dark"
                                style="max-width: 15rem; margin-right: -15rem;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Pricing -->

        <!-- FAQ -->
        <div class="container-lg content-space-t-2 content-space-t-lg-3">
            <!-- Heading -->
            <div class="w-lg-75 text-center mx-lg-auto mb-7 mb-md-10">
                <h2 class="display-4">Frequently Asked <span class="text-primary">Questions</span></h2>
            </div>
            <!-- End Heading -->

            <div class="w-md-75 mx-md-auto">
                <!-- List -->
                <ul class="list-unstyled list-py-3 mb-0">
                    <li>
                        <h2 class="h1">How can I get a refund?</h2>
                        <p class="fs-4">If you'd like a refund please reach out to us at <a class="link"
                                href="mailto:themes@getbootstrap.com">themes@getbootstrap.com</a>. If you need
                            technical help with the theme before a refund please reach out to us first.</p>
                    </li>

                    <li>
                        <h2 class="h1">How do I get access to a theme I purchased?</h2>
                        <p class="fs-4">If you lose the link for a theme you purchased, don't panic! We've got you
                            covered. You can login to your account, tap your avatar in the upper right corner, and tap
                            Purchases. If you didn't create a <a class="link"
                                href="https://marketplace.getbootstrap.com/signin/" target="_blank">login</a> or can't
                            remember the information, you can use our handy <a class="link"
                                href="https://themes.getbootstrap.com/redownload/" target="_blank">Redownload
                                page</a>, just remember to use the same email you originally made your purchases with.
                        </p>
                    </li>

                    <li>
                        <h2 class="h1">How do I get help with the theme I purchased?</h2>
                        <p class="fs-4">Technical support for each theme is given directly by the creator of the
                            theme. You can contact us <a class="link" href="https://htmlstream.com/contact-us"
                                target="_blank">here</a></p>
                    </li>

                    <li>
                        <h2 class="h1">Is Front Admin available on other web application platforms?</h2>
                        <p class="fs-4">Since the theme is a static HTML template, we do not offer any tutorials or
                            any other materials on how to integrate our templates with any CMS, Web Application
                            framework, or any other similar technology. However, since our templates are static HTML/CSS
                            and JS templates, then they should be compatible with any backend technology.</p>
                    </li>

                    <li>
                        <h2 class="h1">How can I access a Figma or Sketch file?</h2>
                        <p class="fs-4">Unfortunately, the design files are not available. We will consider the
                            possibility of adding this option in the near future. However, we cannot provide any ETA
                            regarding the release.</p>
                    </li>
                </ul>
                <!-- End List -->

                <hr class="my-7">

                <div class="text-center">
                    <h3>Haven't found an answer to your question?</h3>
                    <p><a class="link" href="https://htmlstream.com/contact-us" target="_blank">Send us a
                            message</a> and we'll get back to you.</p>
                </div>
            </div>
        </div>
        <!-- End FAQ -->
    </main>
    <!-- ========== END MAIN CONTENT ========== -->

    <!-- ========== FOOTER ========== -->
    <footer class="container-lg text-center py-10">
        <!-- Socials -->
        <ul class="list-inline mb-3">
            <li class="list-inline-item">
                <a class="btn btn-soft-secondary btn-sm btn-icon rounded-circle"
                    href="https://www.facebook.com/htmlstream">
                    <i class="bi-facebook"></i>
                </a>
            </li>

            <li class="list-inline-item">
                <a class="btn btn-soft-secondary btn-sm btn-icon rounded-circle"
                    href="https://twitter.com/htmlstream">
                    <i class="bi-twitter"></i>
                </a>
            </li>

            <li class="list-inline-item">
                <a class="btn btn-soft-secondary btn-sm btn-icon rounded-circle"
                    href="https://github.com/htmlstreamofficial">
                    <i class="bi-github"></i>
                </a>
            </li>

            <li class="list-inline-item">
                <a class="btn btn-soft-secondary btn-sm btn-icon rounded-circle"
                    href="https://www.instagram.com/htmlstream/">
                    <i class="bi-instagram"></i>
                </a>
            </li>
        </ul>
        <!-- End Socials -->

        <p class="mb-0">&copy; GYM Manager. 2025 GYM Management System. All rights reserved.</p>
    </footer>
    <!-- ========== END FOOTER ========== -->

    <!-- ========== SECONDARY CONTENTS ========== -->


    <!-- Go To -->
    <a class="js-go-to go-to position-fixed" href="javascript:;" style="visibility: hidden;"
        data-hs-go-to-options='{
       "offsetTop": 700,
       "position": {
         "init": {
           "right": "2rem"
         },
         "show": {
           "bottom": "2rem"
         },
         "hide": {
           "bottom": "-2rem"
         }
       }
     }'>
        <i class="bi-chevron-up"></i>
    </a>
    <!-- ========== END SECONDARY CONTENTS ========== -->

    <!-- JS Global Compulsory  -->
    <script src="./assets/vendor/jquery/dist/jquery.min.js"></script>
    <script src="./assets/vendor/jquery-migrate/dist/jquery-migrate.min.js"></script>
    <script src="./assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JS Implementing Plugins -->
    <script src="./assets/vendor/hs-header/dist/hs-header.min.js"></script>
    <script src="./assets/vendor/hs-img-compare/hs-img-compare.js"></script>
    <script src="./assets/vendor/hs-go-to/dist/hs-go-to.min.js"></script>

    <!-- JS Front -->
    <script src="./assets/js/theme.min.js"></script>

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

    <!-- End Style Switcher JS -->
</body>

</html>
