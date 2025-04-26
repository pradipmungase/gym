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
                    <div class="col-md-6">
                        <h1 class="page-header-title mb-0">Members Request</h1>
                    </div>
                </div>
            </div>

            <div id="membersRequest-table-container"
                class="text-center my-4 table-responsive datatable-custom position-relative">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2">Loading...</p>
            </div>

        </div>
    </main>

    <div class="modal fade" id="viewmemberModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="viewmemberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <form id="viewmemberForm" method="POST" action="#">
                @csrf
                <div class="modal-content">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="viewmemberModalLabel">
                            <i class="bi bi-person-plus-fill me-2"></i> View Member Request
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
                            <input type="hidden" value="" name="MembersRequestId" id="MembersRequestId">

                            <div class="col-md-6 col-lg-4">
                                <label for="editName" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="memberRequestName" name="name" required
                                    placeholder="e.g., John Doe">
                                <div class="invalid-feedback">Name is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="editEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="memberRequestEmail" name="email"
                                    placeholder="e.g., johndoe@gmail.com">
                                <div class="invalid-feedback">Valid email is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="editMobile" class="form-label">Mobile No <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="memberRequestMobile" name="mobile" required
                                    placeholder="e.g., 03001234567">
                                <div class="invalid-feedback">Mobile number is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="editBirthDate" class="form-label">Birth Date</label>
                                <input type="date" class="form-control birthDate" id="memberRequestBirthDate"
                                    name="birth_date" max="{{ date('Y-m-d') }}">
                                <div class="invalid-feedback">Birth date must be in the past.</div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="editGender" class="form-label">Gender <span class="text-danger">*</span></label>
                                <select class="form-select" id="memberRequestGender" name="gender" required>
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
                                            src="{{ asset('assets/img/160x160/images (1).jpg') }}" alt="Image Description">
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

                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mt-4">🏋️ Training Details</h5>
                            </div>

                            <!-- 🏋️ Training Details -->
                            <div class="col-md-6 col-lg-4">
                                <label for="editJoiningDate" class="form-label">Joining Date <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="memberRequestJoiningDate"
                                    name="joining_date" required>
                                <div class="invalid-feedback">Joining date is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="editBatch" class="form-label">Batch <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="memberRequestBatch" name="batch" required>
                                    <option selected disabled value="">Select Batch</option>
                                    <option value="Morning">Morning</option>
                                    <option value="Afternoon">Afternoon</option>
                                    <option value="Evening">Evening</option>
                                </select>
                                <div class="invalid-feedback">Batch is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="editTrainer" class="form-label">Trainer</label>
                                <select class="form-select" id="memberRequestTrainer" name="trainer">
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
                                    <label for="plan" class="form-label">Plan <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="memberRequestPlan" name="plan" required>
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
                                    <input type="text" class="form-control" value="0"
                                        name="memberRequestPlanPrice" id="memberRequestPlanPrice" readonly>
                                    <div class="invalid-feedback">The plan price field must be at least 0.</div>
                                </div>

                                <div class="col-md-6 col-lg-3">
                                    <label class="form-label">Final Price <span style="font-size: smaller;">(After
                                            Discount)</span></label>
                                    <input type="text" class="form-control text-success" value="0"
                                        name="memberRequestFinalPrice" id="memberRequestFinalPrice" readonly>
                                    <div class="invalid-feedback">The final price field must be at least 0.</div>
                                </div>

                                <div class="col-md-6 col-lg-3">
                                    <label class="form-label">Due Amount</label>
                                    <input type="text" class="form-control text-danger fw-bold" value="0"
                                        name="memberRequestDueAmount" id="memberRequestDueAmount" readonly>
                                    <div class="invalid-feedback">The due amount field must be at least 0.</div>
                                </div>
                            </div>

                            <!-- Second Row -->
                            <div class="row mt-3">
                                <div class="col-md-6 col-lg-3">
                                    <label for="memberRequestDiscountType" class="form-label">Discount Type</label>
                                    <select class="form-select" id="memberRequestDiscountType" name="discount_type">
                                        <option value="" selected>Select Discount Type</option>
                                        <option value="flat">Flat</option>
                                        <option value="percentage">Percentage</option>
                                    </select>
                                    <div class="invalid-feedback">Discount type is required.</div>
                                </div>

                                <div class="col-md-6 col-lg-3">
                                    <label for="memberRequestDiscount" class="form-label">Discount</label>
                                    <input type="number" class="form-control" id="memberRequestDiscount"
                                        name="discount" placeholder="e.g., 10">
                                    <div class="invalid-feedback">Discount is required.</div>
                                </div>

                                <div class="col-md-6 col-lg-3">
                                    <label for="memberRequestAdmissionFee" class="form-label">Joining Amount
                                    </label>
                                    <input type="number" class="form-control" id="memberRequestAdmissionFee"
                                        name="admission_fee" placeholder="e.g., 1000">
                                    <div class="invalid-feedback">Admission fee is required.</div>
                                </div>

                                <div class="col-md-6 col-lg-3">
                                    <label for="memberRequestPaymentMode" class="form-label">Payment Mode</label>
                                    <select class="form-select" id="memberRequestPaymentMode" name="paymentMode">
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
                        <button type="submit" id="acceptMemberRequestBtn" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> Accept
                        </button>
                        <button type="submit" id="rejectMemberRequestBtn" class="btn btn-danger">
                            <i class="bi bi-check-circle me-1"></i> Reject
                        </button>
                        <button type="button" class="clearFromDataWithError btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
