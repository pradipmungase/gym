@extends('admin.layout.adminApp')
@section('content')
    <style>
        .image-upload-wrapper {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        .image-upload-wrapper input[type="file"] {
            display: none;
        }

        .image-upload-wrapper .upload-icon {
            position: absolute;
            bottom: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.6);
            border-radius: 50%;
            padding: 6px;
            color: white;
            font-size: 16px;
        }

        .image-upload-wrapper img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
    <main id="content" role="main" class="main">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center justify-content-between mb-4">
                    <div class="col-8">
                        <h1 class="page-header-title mb-0">Member Details</h1>
                    </div>
                    <div class="col-4 text-end">
                        <button type="button" class="btn btn-primary btn-sm "
                            data-bs-toggle="modal" data-bs-target="#addMemberModal">
                            <i class="bi bi-plus-circle fs-5"></i> &nbsp; Add
                        </button>
                    </div>
                </div>
            </div>

            <div class="card">
                <!-- Header -->
                <div class="card-header card-header-content-md-between">
                    <div class="mb-2 mb-md-0">
                        <form>
                            <!-- Search -->
                            <div class="input-group input-group-merge input-group-flush">
                                <div class="input-group-prepend input-group-text">
                                    <i class="bi-search"></i>
                                </div>
                                <input id="searchMember" type="search" class="form-control" placeholder="Search members"
                                    aria-label="Search members">
                            </div>
                            <!-- End Search -->
                        </form>
                    </div>

                    <div class="d-grid d-sm-flex justify-content-md-end align-items-sm-center gap-2">
                        <!-- Dropdown -->
                        <div class="dropdown">
                            <button type="button" class="btn btn-white btn-sm dropdown-toggle w-100"
                                id="usersExportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi-download me-2"></i> Export
                            </button>

                            <div class="dropdown-menu dropdown-menu-sm-end" aria-labelledby="usersExportDropdown">
                                <span class="dropdown-header">Options</span>
                                <a id="export-copy" class="dropdown-item" href="javascript:;">
                                    <img class="avatar avatar-xss avatar-4x3 me-2"
                                        src="./assets/svg/illustrations/copy-icon.svg" alt="Image Description">
                                    Copy
                                </a>
                                <a id="export-print" class="dropdown-item" href="javascript:;">
                                    <img class="avatar avatar-xss avatar-4x3 me-2"
                                        src="./assets/svg/illustrations/print-icon.svg" alt="Image Description">
                                    Print
                                </a>
                                <div class="dropdown-divider"></div>
                                <span class="dropdown-header">Download options</span>
                                <a id="export-excel" class="dropdown-item" href="javascript:;">
                                    <img class="avatar avatar-xss avatar-4x3 me-2" src="./assets/svg/brands/excel-icon.svg"
                                        alt="Image Description">
                                    Excel
                                </a>
                                <a id="export-csv" class="dropdown-item" href="javascript:;">
                                    <img class="avatar avatar-xss avatar-4x3 me-2"
                                        src="./assets/svg/components/placeholder-csv-format.svg" alt="Image Description">
                                    .CSV
                                </a>
                                <a id="export-pdf" class="dropdown-item" href="javascript:;">
                                    <img class="avatar avatar-xss avatar-4x3 me-2" src="./assets/svg/brands/pdf-icon.svg"
                                        alt="Image Description">
                                    PDF
                                </a>
                            </div>
                        </div>
                        <!-- End Dropdown -->

                        <!-- Dropdown -->
                        <div class="dropdown">
                            <button type="button" class="btn btn-white btn-sm w-100" id="memberFilterDropdown"
                                data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                <i class="bi-filter me-1"></i> Filter <span
                                    class="badge bg-soft-dark text-dark rounded-circle ms-1"></span>
                            </button>

                            <div class="dropdown-menu dropdown-menu-sm-end dropdown-card card-dropdown-filter-centered"
                                aria-labelledby="memberFilterDropdown" style="min-width: 22rem;">
                                <!-- Card -->
                                <div class="card">
                                    <div class="card-header card-header-content-between">
                                        <h5 class="card-header-title">Filter Members</h5>

                                        <!-- Toggle Button -->
                                        <button type="button"
                                            class="filterCloseBtn  btn btn-ghost-secondary btn-icon btn-sm ms-2">
                                            <i class="bi-x-lg"></i>
                                        </button>
                                        <!-- End Toggle Button -->
                                    </div>

                                    <div class="card-body">
                                        {{-- <form> --}}
                                        <div class="mb-4">
                                            <small class="text-cap text-body">Gender</small>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="All"
                                                            name="gender" id="filterGenderAll" checked>
                                                        <label class="form-check-label" for="filterGenderAll">All</label>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="Male"
                                                            name="gender" id="filterGenderMale">
                                                        <label class="form-check-label"
                                                            for="filterGenderMale">Male</label>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="Female"
                                                            name="gender" id="filterGenderFemale">
                                                        <label class="form-check-label"
                                                            for="filterGenderFemale">Female</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Row -->
                                        </div>

                                        <div class="row">
                                            <div class="col-sm mb-8">
                                                <small class="text-cap text-body">User Status</small>
                                                <select class="form-select form-select-sm" id="filterStatus">
                                                    <option value="">Any</option>
                                                    <option value="Active">Active</option>
                                                    <option value="Inactive">Inactive</option>
                                                </select>
                                            </div>
                                            <!-- End Col -->

                                            <!-- End Col -->
                                        </div>
                                        <!-- End Row -->

                                        <div class="d-grid mt-3">
                                            <button class="btn btn-primary" id="applyFilters">Apply</button>
                                        </div>
                                        {{-- </form> --}}
                                    </div>
                                </div>
                                <!-- End Card -->
                            </div>
                        </div>
                        <!-- End Dropdown -->
                    </div>
                </div>
                <!-- End Header -->
                <div id="members-table-container"
                    class="text-center my-4 table-responsive datatable-custom position-relative">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2">Loading...</p>
                </div>
            </div>

        </div>
    </main>

    <div class="modal fade" id="addMemberModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="addMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <form id="addMemberForm" method="POST" action="#" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="addMemberModalLabel">
                            <i class="bi bi-person-plus-fill me-2"></i> Add New Member
                        </h5>
                        <button type="button" class="clearFromDataWithError btn-close btn-close-white"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-4">
                        <div class="row gy-4">

                            <!-- 🧍 Personal Information -->
                            <div class="col-12">
                                <h5 class="border-bottom pb-2">🧍 Personal Information</h5>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required
                                    placeholder="e.g., John Doe">
                                <div class="invalid-feedback">Name is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="e.g., johndoe@gmail.com">
                                <div class="invalid-feedback">Valid email is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="mobile" class="form-label">Mobile No <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="mobile" name="mobile" required
                                    placeholder="e.g., 03001234567">
                                <div class="invalid-feedback">Mobile number is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="birth_date" class="form-label">Birth Date</label>
                                <input type="date" class="form-control birthDate" id="birth_date" name="birth_date"
                                    max="{{ date('Y-m-d') }}">
                                <div class="invalid-feedback">Birth date is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="gender" class="form-label">Gender <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="gender" name="gender" required>
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
                                                class="js-file-attach form-attachment-btn-label" id="avatarUploader"
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
                                <input type="date" class="form-control" id="joining_date" name="joining_date"
                                    required>
                                <div class="invalid-feedback">Joining date is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="batch" class="form-label">Batch <span class="text-danger">*</span></label>
                                <select class="form-select" id="batch" name="batch" required>
                                    <option selected disabled value="">Select Batch</option>
                                    <option value="Morning">Morning</option>
                                    <option value="Afternoon">Afternoon</option>
                                    <option value="Evening">Evening</option>
                                </select>
                                <div class="invalid-feedback">Batch is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="trainer" class="form-label">Trainer</label>
                                <select class="form-select" id="trainer" name="trainer">
                                    <option selected  value="">Select Trainer</option>
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
                                    <label for="plan" class="form-label">Plan <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="plan" name="plan" required>
                                        <option selected disabled value="">Select Plan</option>
                                        @foreach ($plans as $plan)
                                            <option value="{{ $plan->id }}" data-price="{{ $plan->price }}">
                                                {{ $plan->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Plan selection is required.</div>
                                </div>

                                <div class="col-md-6 col-lg-3">
                                    <label class="form-label">Plan Price</label>
                                    <input type="text" class="form-control" value="0" name="plan_price"
                                        id="plan_price" readonly>
                                    <div class="invalid-feedback">The plan price field must be at least 0.</div>
                                </div>

                                <div class="col-md-6 col-lg-3">
                                    <label class="form-label">Final Price <span style="font-size: smaller;">(After
                                            Discount)</span></label>
                                    <input type="text" class="form-control text-success" value="0"
                                        name="final_price" id="final_price" readonly>
                                    <div class="invalid-feedback">The final price field must be at least 0.</div>
                                </div>

                                <div class="col-md-6 col-lg-3">
                                    <label class="form-label">Due Amount</label>
                                    <input type="text" class="form-control text-danger fw-bold" value="0"
                                        name="due_amount" id="due_amount" readonly>
                                    <div class="invalid-feedback">The due amount field must be at least 0.</div>
                                </div>
                            </div>

                            <!-- Second Row -->
                            <div class="row mt-3">
                                <div class="col-md-6 col-lg-3">
                                    <label for="discount_type" class="form-label">Discount Type</label>
                                    <select class="form-select" id="discount_type" name="discount_type">
                                        <option value="" selected>Select Discount Type</option>
                                        <option value="flat">Flat</option>
                                        <option value="percentage">Percentage</option>
                                    </select>
                                    <div class="invalid-feedback">Discount type is required.</div>
                                </div>

                                <div class="col-md-6 col-lg-3">
                                    <label for="discount" class="form-label">Discount</label>
                                    <input type="number" class="form-control" id="discount" name="discount"
                                        placeholder="e.g., 10">
                                    <div class="invalid-feedback">Discount is required.</div>
                                </div>

                                <div class="col-md-6 col-lg-3">
                                    <label for="admission_fee" class="form-label">Joining Amount
                                    </label>
                                    <input type="number" class="form-control" id="admission_fee" name="admission_fee"
                                        placeholder="e.g., 1000">
                                    <div class="invalid-feedback">Admission fee is required.</div>
                                </div>

                                <div class="col-md-6 col-lg-3">
                                    <label for="paymentMode" class="form-label">Payment Mode</label>
                                    <select class="form-select" id="paymentMode" name="paymentMode">
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
                        <button type="button" class="clearFromDataWithError btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="modal fade" id="editmemberModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="editmemberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <form id="editmemberForm" method="POST" action="#">
                @csrf
                <div class="modal-content">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="editmemberModalLabel">
                            <i class="bi bi-pencil-square me-2"></i> Edit member
                        </h5>
                        <button type="button" class="clearFromDataWithError btn-close btn-close-white"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body px-4">
                        <div class="row gy-4">

                            <!-- 🧍 Personal Information -->
                            <div class="col-12">
                                <h5 class="border-bottom pb-2">🧍 Personal Information</h5>
                            </div>
                            <input type="hidden" value="" name="editMembersId" id="editMembersId">

                            <div class="col-md-6 col-lg-4">
                                <label for="editName" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editName" name="name" required
                                    placeholder="e.g., John Doe">
                                <div class="invalid-feedback">Name is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="editEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="editEmail" name="email"
                                    placeholder="e.g., johndoe@gmail.com">
                                <div class="invalid-feedback">Valid email is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="editMobile" class="form-label">Mobile No <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="editMobile" name="mobile" required
                                    placeholder="e.g., 03001234567">
                                <div class="invalid-feedback">Mobile number is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="editBirthDate" class="form-label">Birth Date</label>
                                <input type="date" class="form-control birthDate" id="editBirthDate"
                                    name="birth_date" max="{{ date('Y-m-d') }}">
                                <div class="invalid-feedback">Birth date must be in the past.</div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="editGender" class="form-label">Gender <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="editGender" name="gender" required>
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
                                        <img id="previewMemberImg" class="avatar-img"
                                            src="{{ asset('assets/img/160x160/images (1).jpg') }}"
                                            alt="Image Description">
                                    </label>

                                    <div class="d-flex gap-3 ms-4">
                                        <div class="form-attachment-btn btn btn-sm btn-primary">Upload photo
                                            <input type="file" accept="image/*" name="memberImg"
                                                class="js-file-attach form-attachment-btn-label" id="avatarUploader"
                                                data-hs-file-attach-options='{"textTarget": "#previewMemberImg","mode": "image","targetAttr": "src","resetTarget": ".js-file-attach-reset-img","resetImg": "../assets/img/160x160/images (1).jpg","allowTypes": [".png", ".jpeg", ".jpg"]}'>
                                        </div>
                                        <!-- End Avatar -->

                                        <button type="button"
                                            class="js-file-attach-reset-img btn btn-outline-danger btn-sm">Delete</button>
                                    </div>
                                </div>
                            </div>


                            <!-- 🏋️ Training Details -->
                            <div class="col-md-6 col-lg-4">
                                <label for="editJoiningDate" class="form-label">Joining Date <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="editJoiningDate" name="joining_date"
                                    required>
                                <div class="invalid-feedback">Joining date is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="editBatch" class="form-label">Batch <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="editBatch" name="batch" required>
                                    <option selected disabled value="">Select Batch</option>
                                    <option value="Morning">Morning</option>
                                    <option value="Afternoon">Afternoon</option>
                                    <option value="Evening">Evening</option>
                                </select>
                                <div class="invalid-feedback">Batch is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="editTrainer" class="form-label">Trainer</label>
                                <select class="form-select" id="editTrainer" name="trainer">
                                    <option selected  value="">Select Trainer</option>
                                    @foreach ($trainers as $trainer)
                                        <option value="{{ $trainer->id }}">{{ $trainer->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Trainer selection is required.</div>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer px-4">
                        <button type="submit" id="editPlanBtn" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> Update
                        </button>
                        <button type="button" class="clearFromDataWithError btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="modal fade" id="addPaymentModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="addPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form id="addPaymentForm" method="POST" action="#">
                @csrf
                <div class="modal-content">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="addPaymentModalLabel">
                            <i class="bi bi-cash-coin me-2"></i> Add Payment
                        </h5>
                        <button type="button" class="clearFromDataWithError btn-close btn-close-white"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body px-4">
                        <div class="row gy-4">
                            <input type="hidden" value="" name="addPaymentMemberId" id="addPaymentMemberId">
                            <div class="col-md-12 col-lg-12">
                                <label for="addPaymentMember" class="form-label"> Member Name<span
                                        class="text-danger">*</span></label>
                                <input type="text" readonly class="form-control" id="addPaymentMember"
                                    name="member_name" required placeholder="e.g., John Doe">
                                <div class="invalid-feedback">Member is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-6 col-6">
                                <label for="addPaymentDate" class="form-label"> Payment Date <span
                                        class="text-danger">*</span></label>
                                <input type="date" value="{{ date('Y-m-d') }}" class="form-control"
                                    id="addPaymentDate" name="payment_date" required>
                                <div class="invalid-feedback">Payment date is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-6 col-6">
                                <label for="addPaymentMode" class="form-label"> Payment Mode <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="addPaymentMode" name="payment_mode" required>
                                    <option selected disabled value="">Select Payment Mode</option>
                                    <option value="cash">Cash</option>
                                    <option value="phone pay">Phone Pay</option>
                                    <option value="google pay">Google Pay</option>
                                    <option value="other">Other</option>
                                </select>
                                <div class="invalid-feedback">Payment mode is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-6 col-6">
                                <label for="addPaymentAmount" class="form-label"> Amount <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="addPaymentAmount" name="amount" required
                                    placeholder="e.g., 1000">
                                <div class="invalid-feedback">Amount is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-6 col-6">
                                <label for="addPaymentDueAmount" class="form-label"> Due Amount</label>
                                <input type="number" readonly class="form-control text-danger fw-bold"
                                    id="addPaymentDueAmount" name="due_amount" required>
                                <div class="invalid-feedback">Due amount is required.</div>
                            </div>

                            <input type="hidden" id="currentDueAmount" name="currentDueAmount" value="">
                            <input type="hidden" id="currentPlanId" name="currentPlanId" value="">



                        </div>
                    </div>

                    <div class="modal-footer px-4">
                        <button type="submit" id="addPaymentBtn" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> Add Payment
                        </button>
                        <button type="button" class="clearFromDataWithError btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="addNoteModel" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="addNoteModelLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form id="addNoteForm" method="POST" action="#">
                @csrf
                <div class="modal-content">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="addNoteModelLabel">
                            <i class="bi bi-pencil-square me-2"></i> Add Note
                        </h5>
                        <button type="button" class="clearFromDataWithError btn-close btn-close-white"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body px-4">
                        <div class="row gy-4">
                            <input type="hidden" value="" name="addNoteMemberId" id="addNoteMemberId">

                            <div class="col-md-12 col-lg-12">
                                <label for="addNote" class="form-label"> Note <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="addNote" name="note" required placeholder="e.g., Note"></textarea>
                                <div class="invalid-feedback">Note is required.</div>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer px-4">
                        <button type="submit" id="addNoteBtn" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> Add Note
                        </button>
                        <button type="button" class="clearFromDataWithError btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="changePlanModel" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="changePlanModelLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <form id="changePlanForm" method="POST" action="#">
                @csrf
                <div class="modal-content">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="changePlanModelLabel">
                            <i class="bi bi-pencil-square me-2"></i> Change Membership Plan
                        </h5>
                        <button type="button" class="clearFromDataWithError btn-close btn-close-white"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body px-4">
                        <div class="row gy-4">
                            <input type="hidden" value="" name="changePlanMemberId" id="changePlanMemberId">

                            <!-- Plan -->
                            <div class="col-md-6 col-lg-6">
                                <label for="changePlan" class="form-label">Membership Plan <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="changePlan" name="plan" required>
                                    @foreach ($plans as $plan)
                                        <option value="{{ $plan->id }}" data-price="{{ $plan->price }}">
                                            {{ $plan->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Membership Plan is required.</div>
                            </div>

                            <!-- Joining Date -->
                            <div class="col-md-6 col-lg-6">
                                <label for="changePlanJoiningDate" class="form-label">Joining Date <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="changePlanJoiningDate"
                                    name="joining_date" required>
                                <div class="invalid-feedback">Joining date is required.</div>
                            </div>

                            <div class="row gx-3 mt-4" id="paymentInfo" style="pointer-events: none; opacity: 0.6;">

                                <!-- Discount Type -->
                                <div class="col-6 col-md-3 col-lg-3">
                                    <label for="changePlanDiscountType" class="form-label">Discount Type</label>
                                    <select class="form-select" id="changePlanDiscountType" name="discount_type">
                                        <option value="" selected>Select Discount Type</option>
                                        <option value="flat">Flat</option>
                                        <option value="percentage">Percentage</option>
                                    </select>
                                    <div class="invalid-feedback">Discount type is required.</div>
                                </div>

                                <!-- Discount -->
                                <div class="col-6 col-md-3 col-lg-3">
                                    <label for="changePlanDiscount" class="form-label">Discount</label>
                                    <input type="number" class="form-control" id="changePlanDiscount" name="discount"
                                        placeholder="e.g., 10">
                                    <div class="invalid-feedback">Discount is required.</div>
                                </div>

                                <!-- Admission Fee -->
                                <div class="col-6 col-md-3 col-lg-3">
                                    <label for="changePlanAdmissionFee" class="form-label">Payment Amount</label>
                                    <input type="number" class="form-control" id="changePlanAdmissionFee"
                                        name="admission_fee" placeholder="e.g., 1000">
                                    <div class="invalid-feedback">Admission fee is required.</div>
                                </div>

                                <!-- Payment Method -->
                                <div class="col-6 col-md-3 col-lg-3">
                                    <label for="changePlanPaymentMode" class="form-label">Payment Mode</label>
                                    <select class="form-select" id="changePlanPaymentMode" name="payment_mode">
                                        <option selected value="">Select Payment Mode</option>
                                        <option value="cash">Cash</option>
                                        <option value="phone pay">Phone Pay</option>
                                        <option value="google pay">Google Pay</option>
                                        <option value="other">Other</option>
                                    </select>
                                    <div class="invalid-feedback">Payment mode is required.</div>
                                </div>
                            </div>

                            <div class="row gx-3 mt-4">
                                <div class="col-6 col-md-2 col-lg-2">
                                    <label for="changeCurrentPlanPrice" class="form-label">Current Plan Price</label>
                                    <input type="text" class="form-control" id="changeCurrentPlanPrice"
                                        name="current_plan_price" readonly>
                                </div>

                                <div class="col-6 col-md-2 col-lg-2">
                                    <label for="changeCurrentPlanDueAmount" class="form-label">Current Due Amount</label>
                                    <input type="text" class="form-control text-danger"
                                        id="changeCurrentPlanDueAmount" name="current_due_amount" readonly>
                                </div>

                                <div class="col-6 col-md-2 col-lg-2">
                                    <label for="changeCurrentPlanPaidAmount" class="form-label">Current Paid
                                        Amount</label>
                                    <input type="text" class="form-control text-success"
                                        id="changeCurrentPlanPaidAmount" name="current_paid_amount" readonly>
                                </div>

                                <div class="col-6 col-md-2 col-lg-2">
                                    <label for="changeNewPlanPrice" class="form-label">New Plan Price</label>
                                    <input type="text" class="form-control" value="0" id="changeNewPlanPrice"
                                        name="new_plan_price" readonly>
                                </div>

                                <div class="col-6 col-md-2 col-lg-2">
                                    <label for="changeNewPlanPriceAfterDiscount" class="form-label">After Discount
                                        Price</label>
                                    <input type="text" class="form-control" value="0"
                                        id="changeNewPlanPriceAfterDiscount" name="new_plan_price_after_discount"
                                        readonly>
                                </div>

                                <div class="col-6 col-md-2 col-lg-2">
                                    <label for="changeNewPlanDueAmount" class="form-label">New Due Amount</label>
                                    <input type="text" class="form-control text-danger" value="0"
                                        id="changeNewPlanDueAmount" name="new_due_amount" readonly>
                                    <div class="invalid-feedback">Member already paid more than due amount.</div>
                                </div>
                            </div>

                            <input type="hidden" name="newDueAmountForValidation" id="newDueAmountForValidation"
                                value="">
                            <input type="hidden" name="memberMembershipsId" id="memberMembershipsId" value="">

                            <!-- Batch -->
                            <div class="col-md-6 col-lg-6">
                                <label for="changePlanBatch" class="form-label">Batch <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="changePlanBatch" name="batch" required>
                                    <option selected disabled value="">Select batch</option>
                                    <option value="Morning">Morning</option>
                                    <option value="Afternoon">Afternoon</option>
                                    <option value="Evening">Evening</option>
                                </select>
                                <div class="invalid-feedback">Batch is required.</div>
                            </div>

                            <!-- Trainer -->
                            <div class="col-md-6 col-lg-6">
                                <label for="changePlanTrainer" class="form-label">Trainer</label>
                                <select class="form-select" id="changePlanTrainer" name="trainer">
                                    <option selected value="">Select Trainer</option>
                                    @foreach ($trainers as $trainer)
                                        <option value="{{ $trainer->id }}">{{ $trainer->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Trainer is required.</div>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer px-4">
                        <button type="submit" id="changePlanBtn" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> Change Plan
                        </button>
                        <button type="button" class="clearFromDataWithError btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="modal fade" id="paymentStatusModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-close">
                    <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="bi-x-lg"></i>
                    </button>
                </div>
                <!-- End Header -->

                <!-- Body -->
                <div class="modal-body p-sm-5">
                    <div class="text-center">
                        <div class="w-50 mx-auto mb-4">
                            <!-- Payment Success Icon -->
                            <img class="img-fluid" src="{{ asset('assets/images/5709755.png') }}" alt="Payment Done"
                                style="max-height: 120px;">
                        </div>

                        <h4 class="h1 text-success">All Payments Received</h4>
                        <p class="text-muted mb-0">We have received the full payment from this member. No outstanding dues
                            remain.</p>
                    </div>
                </div>
                <!-- End Body -->

                <!-- Footer -->
                <div class="modal-footer d-block text-center py-sm-4">
                    <small class="text-muted">
                        Thank you for keeping your payments up to date.
                    </small>
                </div>
                <!-- End Footer -->

            </div>
        </div>
    </div>



    <div class="modal fade" id="renewMembershipModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="renewMembershipModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <form id="renewMembershipForm" method="POST" action="#">
                @csrf
                <div class="modal-content">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="renewMembershipModalLabel">
                            <i class="bi bi-cash-coin me-2"></i> Renew Membership
                            <br>
                            <span class="text-info">Member will be removed from then expiry of current plan and added to
                                new plan.</span>
                        </h5>
                        <button type="button" class="clearFromDataWithError btn-close btn-close-white"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body px-4">
                        <div class="row gy-4">
                            <input type="hidden" value="" name="renewMembershipMemberId"
                                id="renewMembershipMemberId">

                            <!-- Plan -->
                            <div class="col-md-6 col-lg-6">
                                <label for="renewMembershipPlan" class="form-label">Membership Plan <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="renewMembershipPlan" name="plan" required>
                                    <option selected disabled value="">Select Plan</option>
                                    @foreach ($plans as $plan)
                                        <option value="{{ $plan->id }}" data-price="{{ $plan->price }}"
                                            data-duration="{{ $plan->duration }}"
                                            data-duration_type="{{ $plan->duration_type }}">
                                            {{ $plan->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Plan is required.</div>
                            </div>

                            <div class="col-6 col-md-3 col-lg-3">
                                <label for="currentPlanExpiryDate" class="form-label">Current Plan Expiry Date</label>
                                <input type="text" readonly class="form-control" id="currentPlanExpiryDate"
                                    name="current_plan_expiry_date" placeholder="! Month 2025">
                                <div class="invalid-feedback">Current plan expiry date is required.</div>
                            </div>


                            <div class="col-6 col-md-3 col-lg-3">
                                <label for="newPlanExpiryDate" class="form-label">New Plan Expiry Date</label>
                                <input type="text" readonly class="form-control" id="newPlanExpiryDate"
                                    name="new_plan_expiry_date" placeholder="N/A">
                                <div class="invalid-feedback">New plan expiry date is required.</div>
                            </div>

                            <div class="row gx-3 mt-4" id="paymentInfoForRenewMembership"
                                style="pointer-events: none; opacity: 0.6;">

                                <!-- Discount Type -->
                                <div class="col-6 col-md-3 col-lg-3">
                                    <label for="renewMembershipDiscountType" class="form-label">Discount Type</label>
                                    <select class="form-select" id="renewMembershipDiscountType" name="discount_type">
                                        <option value="" selected>Select Discount Type</option>
                                        <option value="flat">Flat</option>
                                        <option value="percentage">Percentage</option>
                                    </select>
                                    <div class="invalid-feedback">Discount type is required.</div>
                                </div>

                                <!-- Discount -->
                                <div class="col-6 col-md-3 col-lg-3">
                                    <label for="renewMembershipDiscount" class="form-label">Discount</label>
                                    <input type="number" class="form-control" id="renewMembershipDiscount"
                                        name="discount" placeholder="e.g., 10">
                                    <div class="invalid-feedback">Discount is required.</div>
                                </div>

                                <!-- Admission Fee -->
                                <div class="col-6 col-md-3 col-lg-3">
                                    <label for="renewMembershipAdmissionFee" class="form-label">Payment Amount</label>
                                    <input type="number" class="form-control" id="renewMembershipAdmissionFee"
                                        name="admission_fee" placeholder="e.g., 1000">
                                    <div class="invalid-feedback">Admission fee is required.</div>
                                </div>

                                <!-- Payment Method -->
                                <div class="col-6 col-md-3 col-lg-3">
                                    <label for="renewMembershipPaymentMode" class="form-label">Amount Mode</label>
                                    <select class="form-select" id="renewMembershipPaymentMode" name="payment_mode">
                                        <option selected value="">Select Payment Mode</option>
                                        <option value="cash">Cash</option>
                                        <option value="phone pay">Phone Pay</option>
                                        <option value="google pay">Google Pay</option>
                                        <option value="other">Other</option>
                                    </select>
                                    <div class="invalid-feedback">Payment mode is required.</div>
                                </div>
                            </div>

                            <div class="row gx-3 mt-4">


                                <div class="col-6 col-md-2 col-lg-2">
                                    <label for="renewMembershipNewPlanPrice" class="form-label">New Plan Price</label>
                                    <input type="text" class="form-control" value="0"
                                        id="renewMembershipNewPlanPrice" name="new_plan_price" readonly>
                                </div>

                                <div class="col-6 col-md-2 col-lg-2">
                                    <label for="renewMembershipNewPlanPriceAfterDiscount" class="form-label">After
                                        Discount
                                        Price</label>
                                    <input type="text" class="form-control" value="0"
                                        id="renewMembershipNewPlanPriceAfterDiscount" name="new_plan_price_after_discount"
                                        readonly>
                                </div>

                                <div class="col-6 col-md-2 col-lg-2">
                                    <label for="changeNewPlanDueAmount" class="form-label">New Due Amount</label>
                                    <input type="text" class="form-control text-danger" value="0"
                                        id="renewMembershipNewPlanDueAmount" name="new_due_amount" readonly>
                                    <div class="invalid-feedback">Member already paid more than due amount.</div>
                                </div>
                            </div>

                            <input type="hidden" name="renewMembershipNewDueAmountForValidation"
                                id="renewMembershipNewDueAmountForValidation" value="">
                            <input type="hidden" name="renewMembershipMemberMembershipsId"
                                id="renewMembershipMemberMembershipsId" value="">


                        </div>
                    </div>

                    <div class="modal-footer px-4">
                        <button type="submit" id="renewMembershipBtn" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> Renew Membership
                        </button>
                        <button type="button" class="clearFromDataWithError btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>




    <div class="modal fade" id="renewMembershipPaymentNotReceivedModal" tabindex="-1" data-bs-backdrop="static"
        data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-close">
                    <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="bi-x-lg"></i>
                    </button>
                </div>
                <!-- End Header -->

                <!-- Body -->
                <div class="modal-body p-sm-5">
                    <div class="text-center">
                        <div class="w-50 mx-auto mb-4">
                            <!-- Payment Success Icon -->
                            <img class="img-fluid" src="{{ asset('assets/images/pending-icon-512x504-9zrlrc78.png') }}"
                                alt="Payment Done" style="max-height: 120px;">
                        </div>

                        <h4 class="h1 text-danger">All Payments Not Received</h4>
                        <p class="text-muted mb-0">We have not received the full payment from this member. Outstanding dues
                            remain.</p>
                    </div>
                </div>
                <!-- End Body -->

                <!-- Footer -->
                <div class="modal-footer d-block text-center py-sm-4">
                    <small class="text-muted">
                        Please collect the outstanding dues from this member.
                    </small>
                </div>
                <!-- End Footer -->

            </div>
        </div>
    </div>
@endsection
