<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Title -->
    <title>Welcome to - GYM Manager Admin!</title>

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

    <script src="{{ asset('assets/js/hs.theme-appearance.js') }}"></script>
    <style>
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1) !important;
        }
    </style>

    <!-- ========== MAIN CONTENT ========== -->
    <main id="content" role="main" class="main">
        <!-- Content -->
        <div class="content container-fluid">
            <div class="row justify-content-sm-center text-center py-10">
                <div class="col-sm-7 col-md-5">
                    <img class="img-fluid mb-5" src="{{ asset('assets/svg/illustrations/oc-collaboration.svg') }}"
                        alt="Image Description" data-hs-theme-appearance="default">
                    <img class="img-fluid mb-5" src="{{ asset('assets/svg/illustrations-light/oc-collaboration.svg') }}"
                        alt="Image Description" data-hs-theme-appearance="dark">

                    <h1>Hello, nice to see you!</h1>
                    <p>You are now minutes away from creativity than ever before. Enjoy!</p>
                    <p>Don't have an account yet? <a class="link" href="#" data-bs-toggle="modal"
                            data-bs-target="#memberRegstration">Register here</a></p>
                </div>
            </div>
            <!-- End Row -->
        </div>
        <!-- End Content -->

        <div class="modal fade" id="memberRegstration" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
            aria-labelledby="memberRegstrationLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <form id="memberRegstrationForm" method="POST" action="#" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header text-white">
                            <h5 class="modal-title" id="memberRegstrationLabel">
                                <i class="bi bi-person-plus-fill me-2"></i> Register Member
                            </h5>
                            <button type="button" class="clearFromDataWithError btn-close btn-close-white"
                                data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-4">
                            <div class="row gy-4">
                                <input type="hidden" id="gymId" value="{{ decrypt(request()->segment(2)) }}">
                                <!-- 🧍 Personal Information -->
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2">🧍 Personal Information</h5>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label for="name" class="form-label">Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="registration_name" name="registration_name" required
                                        placeholder="e.g., John Doe">
                                    <div class="invalid-feedback">Name is required.</div>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label for="email" class="form-label">Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="registration_email" name="registration_email" required
                                        placeholder="e.g., johndoe@gmail.com">
                                    <div class="invalid-feedback">Valid email is required.</div>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label for="mobile" class="form-label">Mobile No <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="registration_mobile" name="registration_mobile" required
                                        placeholder="e.g., 03001234567">
                                    <div class="invalid-feedback">Mobile number is required.</div>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label for="birth_date" class="form-label">Birth Date <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control birthDate" id="registration_birth_date"
                                        name="registration_birth_date" required max="{{ date('Y-m-d') }}">
                                    <div class="invalid-feedback">Birth date is required.</div>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label for="gender" class="form-label">Gender <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="registration_gender" name="registration_gender" required>
                                        <option selected disabled value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                    <div class="invalid-feedback">Gender is required.</div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="d-flex align-items-center">
                                        <!-- Avatar -->
                                        <label class="avatar avatar-xl avatar-circle" for="avatarUploader">
                                            <img id="avatarImg" class="avatar-img"
                                                src="{{ asset('assets/img/160x160/images (1).jpg') }}"
                                                alt="Image Description">
                                        </label>

                                        <div class="d-flex gap-3 ms-4">
                                            <div class="form-attachment-btn btn btn-sm btn-primary">Upload photo
                                                <input type="file" accept="image/*" name="memberImg"
                                                    class="js-file-attach form-attachment-btn-label"
                                                    id="avatarUploader"
                                                    data-hs-file-attach-options='{"textTarget": "#avatarImg","mode": "image","targetAttr": "src","resetTarget": ".js-file-attach-reset-img","resetImg": "../assets/img/160x160/images (1).jpg","allowTypes": [".png", ".jpeg", ".jpg"]}'>
                                            </div>
                                            <!-- End Avatar -->

                                            <button type="button"
                                                class="js-file-attach-reset-img btn btn-outline-danger btn-sm">Delete</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- 🏋️ Training Details -->
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mt-4">🏋️ Training Details</h5>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label for="joining_date" class="form-label">Joining Date <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="registration_joining_date" name="registration_joining_date"
                                        required>
                                    <div class="invalid-feedback">Joining date is required.</div>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label for="batch" class="form-label">Batch <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="registration_batch" name="registration_batch" required>
                                        <option selected disabled value="">Select Batch</option>
                                        <option value="Morning">Morning</option>
                                        <option value="Afternoon">Afternoon</option>
                                        <option value="Evening">Evening</option>
                                    </select>
                                    <div class="invalid-feedback">Batch is required.</div>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label for="registration_trainer" class="form-label">Trainer</label>
                                    <select class="form-select" id="registration_trainer" name="registration_trainer">
                                        <option selected value="">Select Trainer</option>
                                        @foreach ($trainers as $trainer)
                                            <option value="{{ $trainer->id }}">{{ $trainer->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Trainer selection is required.</div>
                                </div>

                                <!-- 💳 Payment Information -->
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mt-4">💳 Payment Information</h5>
                                </div>

                                <!-- First Row -->
                                <div class="row">
                                    <div class="col-md-6 col-lg-3">
                                        <label for="plan" class="form-label">Membership Plan <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" id="registration_plan" name="registration_plan" required>
                                            <option selected disabled value="">Select Plan</option>
                                            @foreach ($plans as $plan)
                                                <option value="{{ $plan->id }}"
                                                    data-price="{{ $plan->price }}">
                                                    {{ $plan->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">Plan selection is required.</div>
                                    </div>

                                    <div class="col-md-6 col-lg-3">
                                        <label class="form-label">Plan Price</label>
                                        <input type="text" class="form-control" value="0" name="registration_plan_price"
                                            id="registration_plan_price" readonly>
                                        <div class="invalid-feedback">The plan price field must be at least 0.</div>
                                    </div>

                                    <div class="col-md-6 col-lg-3">
                                        <label class="form-label">Final Price <span style="font-size: smaller;">(After
                                                Discount)</span></label>
                                        <input type="text" class="form-control text-success" value="0"
                                            name="registration_final_price" id="registration_final_price" readonly>
                                        <div class="invalid-feedback">The final price field must be at least 0.</div>
                                    </div>

                                    <div class="col-md-6 col-lg-3">
                                        <label class="form-label">Due Amount</label>
                                        <input type="text" class="form-control text-danger fw-bold" value="0"
                                            name="registration_due_amount" id="registration_due_amount" readonly>
                                        <div class="invalid-feedback">The due amount field must be at least 0.</div>
                                    </div>
                                </div>

                                <!-- Second Row -->
                                <div class="row mt-3">
                                    <div class="col-md-6 col-lg-3">
                                        <label for="registration_discount_type" class="form-label">Discount Type</label>
                                        <select class="form-select" id="registration_discount_type" name="registration_discount_type">
                                            <option value="" selected disabled>Select Discount Type</option>
                                            <option value="flat" disabled>Flat</option>
                                            <option value="percentage" disabled>Percentage</option>
                                        </select>
                                        <div class="invalid-feedback">Discount type is required.</div>
                                    </div>

                                    <div class="col-md-6 col-lg-3">
                                        <label for="registration_discount" class="form-label">Discount</label>
                                        <input type="number" readonly class="form-control" id="registration_discount"
                                            name="registration_discount" placeholder="e.g., 10">
                                        <div class="invalid-feedback">Discount is required.</div>
                                    </div>

                                    <div class="col-md-6 col-lg-3">
                                        <label for="registration_admission_fee" class="form-label">Joining Amount
                                        </label>
                                        <input type="number" class="form-control" id="registration_admission_fee"
                                            name="registration_admission_fee" placeholder="e.g., 1000">
                                        <div class="invalid-feedback">Admission fee is required.</div>
                                    </div>

                                    <div class="col-md-6 col-lg-3">
                                        <label for="registration_payment_mode" class="form-label">Payment Mode</label>
                                        <select class="form-select" id="registration_payment_mode" name="registration_payment_mode">
                                            <option selected value="">Select Payment Mode</option>
                                            <option value="cash">Cash</option>
                                            <option value="phone pay">Phone Pay</option>
                                            <option value="google pay">Google Pay</option>
                                            <option value="other">Other</option>
                                        </select>
                                        <div class="invalid-feedback">Payment selection is required.</div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer px-4">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-1"></i> Submit
                            </button>
                            <button type="button" class="clearFromDataWithError btn btn-secondary"
                                data-bs-dismiss="modal">
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
    <script src="{{ asset('') }}assets/vendor/hs-file-attach/dist/hs-file-attach.min.js"></script>

    <script src="{{ asset('My.js') }}"></script>

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

        $(document).ready(function() {
            $('#memberRegstration').modal('show');
        });

        (function() {
            // INITIALIZATION OF FILE ATTACH
            // =======================================================
            new HSFileAttach('.js-file-attach')
        })();
    </script>
</body>

</html>
