<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Title -->
    <title>GYM Manager</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('logo/5.jpg') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{ asset('') }}assets/vendor/bootstrap-icons/font/bootstrap-icons.css">

    <link rel="stylesheet" href="{{ asset('') }}assets/vendor/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="{{ asset('') }}assets/vendor/jsvectormap/dist/css/jsvectormap.min.css">
    <link rel="stylesheet" href="{{ asset('') }}assets/vendor/tom-select/dist/css/tom-select.bootstrap5.css">

    <!-- CSS Front Template -->

    <link rel="preload" href="{{ asset('') }}assets/css/theme.min.css" data-hs-appearance="default" as="style">
    <link rel="preload" href="{{ asset('') }}assets/css/theme-dark.min.css" data-hs-appearance="dark"
        as="style">
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="{{ asset('') }}assets/vendor/jquery/dist/jquery.min.js"></script>
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <!-- Optional: jQuery (Toastr uses it) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

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
            "startPath": "#",
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
                "src": "{{ asset('') }}src",
                "dist": "{{ asset('') }}dist",
                "build": "{{ asset('') }}build"
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

<body class="has-navbar-vertical-aside navbar-vertical-aside-show-xl   footer-offset">


    <script src="{{ asset('') }}assets/js/hs.theme-appearance.js"></script>

    <script src="{{ asset('') }}assets/vendor/hs-navbar-vertical-aside/dist/hs-navbar-vertical-aside-mini-cache.js">
    </script>

    <!-- ========== HEADER ========== -->

    <header id="header"
        class="navbar navbar-expand-lg navbar-fixed navbar-height navbar-container navbar-bordered bg-white">
        <div class="navbar-nav-wrap">
            <!-- Logo -->
            <a class="navbar-brand" href="#" aria-label="Front">
                <h3 class="navbar-brand-logo" data-hs-theme-appearance="default">GYM Manager</h3>
                <h3 class="navbar-brand-logo" data-hs-theme-appearance="dark">GYM Manager</h3>
                <h3 class="navbar-brand-logo-mini" data-hs-theme-appearance="default">GM</h3>
                <h3 class="navbar-brand-logo-mini" data-hs-theme-appearance="dark">GM</h3>
            </a>
            <!-- End Logo -->

            <div class="navbar-nav-wrap-content-start">
                <!-- Navbar Vertical Toggle -->
                <button type="button" class="js-navbar-vertical-aside-toggle-invoker navbar-aside-toggler">
                    <i class="bi-arrow-bar-left navbar-toggler-short-align"
                        data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                        data-bs-toggle="tooltip" data-bs-placement="right" title="Collapse"></i>
                    <i class="bi-arrow-bar-right navbar-toggler-full-align"
                        data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                        data-bs-toggle="tooltip" data-bs-placement="right" title="Expand"></i>
                </button>

                <!-- Search Form -->
<!-- Search Bar with Dropdown -->
<div class="dropdown ms-2">
  <!-- Input Group -->
  <div class="d-none d-lg-block">
    <div
      class="input-group input-group-merge input-group-borderless input-group-hover-light navbar-input-group">
      <div class="input-group-prepend input-group-text">
        <i class="bi-search"></i>
      </div>

      <input type="search" class="js-form-search form-control" placeholder="Search Member & Trainer"
        aria-label="Search Member & Trainer"
        data-hs-form-search-options='{
          "clearIcon": "#clearSearchResultsIcon",
          "dropMenuElement": "#searchDropdownMenu",
          "dropMenuOffset": 20,
          "toggleIconOnFocus": true,
          "activeClass": "focus"
        }'>
      <a class="input-group-append input-group-text" href="javascript:;">
        <i id="clearSearchResultsIcon" class="bi-x-lg" style="display: none;"></i>
      </a>
    </div>
  </div>

  <button
    class="js-form-search js-form-search-mobile-toggle btn btn-ghost-secondary btn-icon rounded-circle d-lg-none"
    type="button"
    data-hs-form-search-options='{
      "clearIcon": "#clearSearchResultsIcon",
      "dropMenuElement": "#searchDropdownMenu",
      "dropMenuOffset": 20,
      "toggleIconOnFocus": true,
      "activeClass": "focus"
    }'>
    <i class="bi-search"></i>
  </button>

  <!-- Search Dropdown Results -->
  <div id="searchDropdownMenu"
    class="hs-form-search-menu-content dropdown-menu dropdown-menu-form-search navbar-dropdown-menu-borderless">
    <div class="card">
      <div class="card-body-height"></div>
      <a class="card-footer text-center" href="javascript:;">
        See all results <i class="bi-chevron-right small"></i>
      </a>
    </div>
  </div>
</div>

                <!-- End Search Form -->
            </div>

            <div class="navbar-nav-wrap-content-end">
                <!-- Navbar -->
                <ul class="navbar-nav">
                    <li class="nav-item d-none d-sm-inline-block">
                        <!-- Notification -->
                        <div class="dropdown">
                            <button type="button" class="btn btn-ghost-secondary btn-icon rounded-circle"
                                id="navbarNotificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                                data-bs-auto-close="outside" data-bs-dropdown-animation>
                                <i class="bi-bell"></i>
                                <span class="btn-status btn-sm-status btn-status-danger"></span>
                            </button>

                            <div class="dropdown-menu dropdown-menu-end dropdown-card navbar-dropdown-menu navbar-dropdown-menu-borderless"
                                aria-labelledby="navbarNotificationsDropdown" style="width: 25rem;">
                                <div class="card">
                                    <!-- Header -->
                                    <div class="card-header card-header-content-between">
                                        <h4 class="card-title mb-0">Notifications</h4>
                                    </div>
                                    <!-- End Header -->

                                    <!-- Body -->
                                    <div class="card-body-height">
                                        <!-- Tab Content -->
                                        <div class="tab-content" id="notificationTabContent">
                                            <div class="tab-pane fade show active" id="notificationNavOne"
                                                role="tabpanel" aria-labelledby="notificationNavOne-tab">
                                                <!-- List Group -->
                                                <ul class="list-group list-group-flush navbar-card-list-group">
                                                    <!-- Item -->
                                                    <li class="list-group-item form-check-select">
                                                        <div class="row">
                                                            <div class="col-auto">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input"
                                                                            type="checkbox" value=""
                                                                            id="notificationCheck1" checked>
                                                                        <label class="form-check-label"
                                                                            for="notificationCheck1"></label>
                                                                        <span class="form-check-stretched-bg"></span>
                                                                    </div>
                                                                    <img class="avatar avatar-sm avatar-circle"
                                                                        src="@if (Auth::user()->profile_picture) {{ asset(Auth::user()->profile_picture) }}@else{{ asset('assets/img/1920x400/img2.jpg') }} @endif"
                                                                        alt="Image Description">
                                                                </div>
                                                            </div>
                                                            <!-- End Col -->

                                                            <div class="col ms-n2">
                                                                <h5 class="mb-1">Brian Warner</h5>
                                                                <p class="text-body fs-5">changed an issue from "In
                                                                    Progress" to <span
                                                                        class="badge bg-success">Review</span></p>
                                                            </div>
                                                            <!-- End Col -->

                                                            <small class="col-auto text-muted text-cap">2hr</small>
                                                            <!-- End Col -->
                                                        </div>
                                                        <!-- End Row -->

                                                        <a class="stretched-link" href="#"></a>
                                                    </li>
                                                    <!-- End Item -->
                                                </ul>
                                                <!-- End List Group -->
                                            </div>
                                        </div>
                                        <!-- End Tab Content -->
                                    </div>
                                    <!-- End Body -->

                                    <!-- Card Footer -->
                                    <a class="card-footer text-center" href="#">
                                        View all notifications <i class="bi-chevron-right"></i>
                                    </a>
                                    <!-- End Card Footer -->
                                </div>
                            </div>
                        </div>
                        <!-- End Notification -->
                    </li>


                    <li class="nav-item">
                        <!-- Account -->
                        <div class="dropdown">
                            <a class="navbar-dropdown-account-wrapper" href="javascript:;" id="accountNavbarDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside"
                                data-bs-dropdown-animation>
                                <div class="avatar avatar-sm avatar-circle">

                                    <img class="avatar-img"
                                        src="@if (Auth::user()->profile_picture) {{ asset(Auth::user()->profile_picture) }}@else{{ asset('assets/img/1920x400/img2.jpg') }} @endif"
                                        alt="Image Description">
                                    <span class="avatar-status avatar-sm-status avatar-status-success"></span>
                                </div>
                            </a>

                            <div class="dropdown-menu dropdown-menu-end navbar-dropdown-menu navbar-dropdown-menu-borderless navbar-dropdown-account"
                                aria-labelledby="accountNavbarDropdown" style="width: 16rem;">
                                <div class="dropdown-item-text">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm avatar-circle">
                                            <img class="avatar-img"
                                                src="@if (Auth::user()->profile_picture) {{ asset(Auth::user()->profile_picture) }}@else{{ asset('assets/img/1920x400/img2.jpg') }} @endif"
                                                alt="Image Description">
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="mb-0 loginUserName">{{ Auth::user()->owner_name }}</h5>
                                            <p class="card-text text-body loginUserMobileNumber">
                                                {{ Auth::user()->mobile }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="{{ url('profile') }}">
                                    <i class="fas fa-user me-2"></i> Profile &amp; account
                                </a>

                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cog me-2"></i> Settings
                                </a>

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" id="logoutBtn" href="{{ route('logout') }}">
                                    <i class="fas fa-sign-out-alt me-2"></i> Log out
                                </a>

                            </div>
                            <!-- End Account -->
                    </li>
                </ul>
                <!-- End Navbar -->
            </div>
        </div>
    </header>

    <aside
        class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered bg-white  ">
        <div class="navbar-vertical-container">
            <div class="navbar-vertical-footer-offset">
                <!-- Logo -->

                <a class="navbar-brand" href="#" aria-label="Front">
                    <h3 class="navbar-brand-logo" data-hs-theme-appearance="default">GYM Manager</h3>
                    <h3 class="navbar-brand-logo" data-hs-theme-appearance="dark">GYM Manager</h3>
                    <h3 class="navbar-brand-logo-mini" data-hs-theme-appearance="default">GM</h3>
                    <h3 class="navbar-brand-logo-mini" data-hs-theme-appearance="dark">GM</h3>
                </a>

                <!-- End Logo -->

                <!-- Navbar Vertical Toggle -->
                <button type="button" class="js-navbar-vertical-aside-toggle-invoker navbar-aside-toggler">
                    <i class="bi-arrow-bar-left navbar-toggler-short-align"
                        data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                        data-bs-toggle="tooltip" data-bs-placement="right" title="Collapse"></i>
                    <i class="bi-arrow-bar-right navbar-toggler-full-align"
                        data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                        data-bs-toggle="tooltip" data-bs-placement="right" title="Expand"></i>
                </button>

                <!-- End Navbar Vertical Toggle -->

                <!-- Content -->

                <div class="navbar-vertical-content">
                    <div id="navbarVerticalMenu" class="nav nav-pills nav-vertical card-navbar-nav">



                        <!-- Dashboard -->
                        <div class="nav-item">
                            <a class="nav-link" href="{{ url('dashboard') }}" role="button">
                                <i class="bi-speedometer2 nav-icon"></i>
                                <span class="nav-link-title">Dashboard</span>
                            </a>
                        </div>


                        <div class="nav-item">
                            <a class="nav-link dropdown-toggle " href="#navbarVerticalMenuDashboards" role="button"
                                data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuDashboards"
                                aria-expanded="false" aria-controls="navbarVerticalMenuDashboards">
                                <i class="bi-house-door nav-icon"></i>
                                <span class="nav-link-title">Set-Up</span>
                            </a>

                            <div id="navbarVerticalMenuDashboards" class="nav-collapse collapse "
                                data-bs-parent="#navbarVerticalMenu">
                                <a class="nav-link " href="{{ url('plans') }}"> <i
                                        class="bi-clipboard-check nav-icon"></i>Membership Plan</a>
                                <a class="nav-link " href="{{ url('trainer') }}"> <i
                                        class="bi-person-badge nav-icon"></i> Trainers</a>
                                <a class="nav-link " href="{{ url('permissions') }}"> <i
                                        class="bi-person-badge nav-icon"></i> Permissions</a>
                            </div>
                        </div>


                        <!-- Members -->
                        <div class="nav-item">
                            <a class="nav-link" href="{{ url('members') }}" role="button">
                                <i class="bi-people nav-icon"></i>
                                <span class="nav-link-title">Members</span>
                            </a>
                        </div>

                        <!-- Attendance -->
                        <div class="nav-item">
                            <a class="nav-link" href="{{ url('attendance') }}" role="button">
                                <i class="bi-calendar-check nav-icon"></i>
                                <span class="nav-link-title">Attendance</span>
                            </a>
                        </div>

                        <!-- Expenses -->
                        <div class="nav-item">
                            <a class="nav-link" href="{{ url('expenses') }}" role="button">
                                <i class="bi-cash-stack nav-icon"></i>
                                <span class="nav-link-title">Expenses</span>
                            </a>
                        </div>

                        <!-- announcement -->
                        <div class="nav-item">
                            <a class="nav-link" href="{{ url('announcement') }}" role="button">
                                <i class="bi-megaphone nav-icon"></i>
                                <span class="nav-link-title">Announcement</span>
                            </a>
                        </div>

                        <!-- Reports -->
                        <div class="nav-item">
                            <a class="nav-link" href="#" role="button">
                                <i class="bi-bar-chart-line nav-icon"></i>
                                <span class="nav-link-title">Reports</span>
                            </a>
                        </div>

                        <!-- Settings -->
                        <div class="nav-item">
                            <a class="nav-link" href="{{ url('settings') }}" role="button">
                                <i class="bi-gear nav-icon"></i>
                                <span class="nav-link-title">Settings</span>
                            </a>
                        </div>

                        <!-- Support -->

                        <div class="nav-item">
                            <a class="nav-link" href="{{ url('support') }}" role="button">
                                <i class="bi-question-circle nav-icon"></i>
                                <span class="nav-link-title">Support</span>
                            </a>
                        </div>

                        <!-- Logout -->
                        <div class="nav-item">
                            <a class="nav-link text-danger" href="{{ url('logout') }}" role="button">
                                <i class="bi-box-arrow-right nav-icon text-danger"></i>

                                <span class="nav-link-title">Log out</span>
                            </a>
                        </div>
                    </div>

                </div>
                <!-- End Content -->

                <!-- Footer -->
                <div class="navbar-vertical-footer">
                    <ul class="navbar-vertical-footer-list">
                        <li class="navbar-vertical-footer-list-item">
                            <!-- Style Switcher -->
                            <div class="dropdown dropup">
                                <button type="button" class="btn btn-ghost-secondary btn-icon rounded-circle"
                                    id="selectThemeDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                                    data-bs-dropdown-animation>
                                </button>

                                <div class="dropdown-menu navbar-dropdown-menu navbar-dropdown-menu-borderless"
                                    aria-labelledby="selectThemeDropdown">
                                    <a class="dropdown-item" href="#" data-icon="bi-moon-stars"
                                        data-value="auto">
                                        <i class="bi-moon-stars me-2"></i>
                                        <span class="text-truncate" title="Auto (system default)">Auto (system
                                            default)</span>
                                    </a>
                                    <a class="dropdown-item" href="#" data-icon="bi-brightness-high"
                                        data-value="default">
                                        <i class="bi-brightness-high me-2"></i>
                                        <span class="text-truncate" title="Default (light mode)">Default (light
                                            mode)</span>
                                    </a>
                                    <a class="dropdown-item active" href="#" data-icon="bi-moon"
                                        data-value="dark">
                                        <i class="bi-moon me-2"></i>
                                        <span class="text-truncate" title="Dark">Dark</span>
                                    </a>
                                </div>
                            </div>
                        </li>

                    </ul>
                </div>
                <!-- End Footer -->
            </div>
        </div>
    </aside>
    @yield('content')

    <!-- Footer -->
    <div class="footer">
        <div class="row justify-content-between align-items-center">
            <div class="col">
                <p class="fs-6 mb-0">&copy; GYM Manager. <span class="d-none d-sm-inline-block">2025 GYM
                        Manager.</span></p>
            </div>
            <!-- End Col -->

            <div class="col-auto">
                <div class="d-flex justify-content-end">
                    <!-- List Separator -->
                    <ul class="list-inline list-separator">
                        <ul class="list-inline">
                            <!-- Make in India Label -->
                            <li class="list-inline-item d-flex align-items-center gap-1">
                                <i class="bi bi-heart-fill text-danger"></i>
                                <span class="fw-semibold">Make in India</span>
                            </li>
                        </ul>
                        <!-- End List Separator -->
                </div>
            </div>
            <!-- End Col -->
        </div>
        <!-- End Row -->
    </div>
    <!-- End Footer -->

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
    <script src="{{ asset('') }}assets/vendor/jquery-migrate/dist/jquery-migrate.min.js"></script>
    <script src="{{ asset('') }}assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JS Implementing Plugins -->
    <script src="{{ asset('') }}assets/vendor/hs-navbar-vertical-aside/dist/hs-navbar-vertical-aside.min.js"></script>
    <script src="{{ asset('') }}assets/vendor/hs-form-search/dist/hs-form-search.min.js"></script>

    <script src="{{ asset('') }}assets/vendor/daterangepicker/moment.min.js"></script>
    <script src="{{ asset('') }}assets/vendor/daterangepicker/daterangepicker.js"></script>
    <script src="{{ asset('') }}assets/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('') }}assets/vendor/chartjs-chart-matrix/dist/chartjs-chart-matrix.min.js"></script>
    <script src="{{ asset('') }}assets/vendor/jsvectormap/dist/js/jsvectormap.min.js"></script>
    <script src="{{ asset('') }}assets/vendor/jsvectormap/dist/maps/world.js"></script>
    <script src="{{ asset('') }}assets/vendor/tom-select/dist/js/tom-select.complete.min.js"></script>

    <!-- JS Front -->
    <script src="{{ asset('') }}assets/js/theme.min.js"></script>
    <script src="{{ asset('') }}assets/js/hs.theme-appearance-charts.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('My.js') }}"></script>

    <!-- JS Plugins Init. -->
    <script>
        (function() {
            window.onload = function() {


                // INITIALIZATION OF NAVBAR VERTICAL ASIDE
                // =======================================================
                new HSSideNav('.js-navbar-vertical-aside').init()


                // INITIALIZATION OF FORM SEARCH
                // =======================================================
                new HSFormSearch('.js-form-search')


                // INITIALIZATION OF BOOTSTRAP DROPDOWN
                // =======================================================
                HSBsDropdown.init()


                // INITIALIZATION OF CHARTJS
                // =======================================================
                HSCore.components.HSChartJS.init('.js-chart')


                // INITIALIZATION OF VECTOR MAP
                // =======================================================
                setTimeout(() => {
                    HSCore.components.HSJsVectorMap.init('.js-jsvectormap', {
                        backgroundColor: HSThemeAppearance.getAppearance() === 'dark' ? '#25282a' :
                            '#ffffff'
                    })

                    const vectorMap = HSCore.components.HSJsVectorMap.getItem(0)

                    window.addEventListener('on-hs-appearance-change', e => {
                        vectorMap.setBackgroundColor(e.detail === 'dark' ? '#25282a' : '#ffffff')
                    })
                }, 300)


                // INITIALIZATION OF SELECT
                // =======================================================
                HSCore.components.HSTomSelect.init('.js-select')
            }
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
