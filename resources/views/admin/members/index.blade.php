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
                    <div class="col-6">
                        <h1 class="page-header-title mb-0">Member Details</h1>
                    </div>
                    <div class="col-6 text-end">
                        <a class="btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                            <i class="bi bi-person-plus-fill me-1"></i> Add Member
                        </a>
                    </div>
                </div>
            </div>
            <div id="members-table-container" class="text-center my-4 table-responsive datatable-custom"
                style="height: 800px">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2">Loading members please wait...</p>
            </div>

        </div>
    </main>

    <div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <form id="addMemberForm" method="POST" action="#" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="addMemberModalLabel">
                            <i class="bi bi-person-plus-fill me-2"></i> Add New Member
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
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
                                <input type="date" class="form-control birthDate"  id="birth_date" name="birth_date" max="{{ date('Y-m-d') }}">
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option selected disabled value="">Select gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                                <div class="invalid-feedback">Gender is required.</div>
                            </div>

                            <div class="col-md-3 col-lg-2">
                                <label for="addMemberImg" class="form-label">Member Image</label>
                                <div class="image-upload-wrapper" id="triggerUploadadd">
                                    <img id="previewMemberImgAdd" src="{{ asset('assets/img/160x160/images (1).jpg') }}"
                                        alt="Image Preview">
                                    <span class="upload-icon">
                                        <i class="fa fa-camera"></i>
                                    </span>
                                    <input type="file" accept="image/*" name="memberImg" id="addMemberImg">
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
                                    <option selected disabled value="">Select batch</option>
                                    <option value="Morning">Morning</option>
                                    <option value="Afternoon">Afternoon</option>
                                    <option value="Evening">Evening</option>
                                </select>
                                <div class="invalid-feedback">Batch is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="trainer" class="form-label">Trainer</label>
                                <select class="form-select" id="trainer" name="trainer" required>
                                    <option selected disabled value="">Select Trainer</option>
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

                            <div class="col-md-6 col-lg-4">
                                <label for="plan" class="form-label">Plan <span class="text-danger">*</span></label>
                                <select class="form-select" id="plan" name="plan" required>
                                    <option selected disabled value="">Select Plan</option>
                                    @foreach ($plans as $plan)
                                        <option value="{{ $plan->id }}" data-price="{{ $plan->price }}">
                                            {{ $plan->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Plan selection is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="paymentMode" class="form-label">Payment Mode</label>
                                <select class="form-select" id="paymentMode" name="paymentMode">
                                    <option selected disabled value="">Select Payment Mode</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Phone Pay">Phone Pay</option>
                                    <option value="Google Pay">Google Pay</option>
                                    <option value="Other">Other</option>
                                </select>
                                <div class="invalid-feedback">Payment selection is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="admission_fee" class="form-label">Admission Fee</label>
                                <input type="number" class="form-control" id="admission_fee" name="admission_fee"
                                    placeholder="Enter admission fee">
                                <div class="invalid-feedback">Admission fee is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="discount_type" class="form-label">Discount Type</label>
                                <select class="form-select" id="discount_type" name="discount_type">
                                    <option value="" selected>Select Discount Type</option>
                                    <option value="Flat" selected>Flat</option>
                                    <option value="Percentage">Percentage</option>
                                </select>
                                <div class="invalid-feedback">Discount type is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="discount" class="form-label">Discount</label>
                                <input type="number" class="form-control" id="discount" name="discount"
                                    placeholder="e.g., 10">
                                <div class="invalid-feedback">Discount is required.</div>
                            </div>

                            <div class="col-md-4 col-lg-2">
                                <label class="form-label">Plan Price</label>
                                <input type="text" class="form-control" value="0" name="plan_price"
                                    id="plan_price" readonly>
                                <div class="invalid-feedback">The plan price field must be at least 0.</div>
                            </div>

                            <div class="col-md-4 col-lg-2">
                                <label class="form-label">Final Price <span style="font-size: smaller;">(After
                                        Discount)</span></label>
                                <input type="text" class="form-control" value="0" name="final_price"
                                    id="final_price" readonly>
                                <div class="invalid-feedback">The final price field must be at least 0.</div>
                            </div>

                            <div class="col-md-4 col-lg-2">
                                <label class="form-label">Due Amount</label>
                                <input type="text" class="form-control text-danger fw-bold" value="0"
                                    name="due_amount" id="due_amount" readonly>
                                <div class="invalid-feedback">The due amount field must be at least 0.</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer px-4">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> Submit
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="modal fade" id="editmemberModal" tabindex="-1" aria-labelledby="editmemberModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <form id="editmemberForm" method="POST" action="#">
                @csrf
                <div class="modal-content">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="editmemberModalLabel">
                            <i class="bi bi-person-plus-fill me-2"></i> Edit member
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
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
                                <input type="date" class="form-control birthDate" id="editBirthDate" name="birth_date" max="{{ date('Y-m-d') }}">
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="editGender" class="form-label">Gender <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="editGender" name="gender" required>
                                    <option selected disabled value="">Select gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                                <div class="invalid-feedback">Gender is required.</div>
                            </div>
                            <div class="col-md-3 col-lg-2">
                                <label for="editMemberImg" class="form-label">Member Image</label>
                                <div class="image-upload-wrapper" id="triggerUpload">
                                    <img id="previewMemberImg" src="{{ asset('assets/img/160x160/images (1).jpg') }}"
                                        alt="Image Preview">
                                    <span class="upload-icon">
                                        <i class="fa fa-camera"></i>
                                    </span>
                                    <input type="file" accept="image/*" name="memberImg" id="editMemberImg">
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
                                    <option selected disabled value="">Select batch</option>
                                    <option value="Morning">Morning</option>
                                    <option value="Afternoon">Afternoon</option>
                                    <option value="Evening">Evening</option>
                                </select>
                                <div class="invalid-feedback">Batch is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="editTrainer" class="form-label">Trainer</label>
                                <select class="form-select" id="editTrainer" name="trainer" required>
                                    <option selected disabled value="">Select Trainer</option>
                                    @foreach ($trainers as $trainer)
                                        <option value="{{ $trainer->id }}">{{ $trainer->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Trainer selection is required.</div>
                            </div>


                            <!-- 💳 Payment Information -->
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mt-4">💳 Payment Information &nbsp;&nsc;
                                    <small class="text-warning">Note: If you want to change the plan, please select the new plan
                                    and then update the payment information very carefully.</small>
                                </h5>
                                
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="editPlan" class="form-label">Plan <span class="text-danger">*</span></label>
                                <select class="form-select" id="editPlan" name="plan" required>
                                    <option selected disabled value="">Select Plan</option>
                                    @foreach ($plans as $plan)
                                        <option value="{{ $plan->id }}" data-price="{{ $plan->price }}">
                                            {{ $plan->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Plan selection is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="editPaymentMode" class="form-label">Payment Mode</label>
                                <select class="form-select" id="editPaymentMode" name="paymentMode">
                                    <option selected disabled value="">Select Payment Mode</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Phone Pay">Phone Pay</option>
                                    <option value="Google Pay">Google Pay</option>
                                    <option value="Other">Other</option>
                                </select>
                                <div class="invalid-feedback">Payment selection is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="editAdmissionFee" class="form-label">Admission Fee</label>
                                <input type="number" class="form-control" id="editAdmissionFee" name="admission_fee"
                                    placeholder="Enter admission fee">
                                <div class="invalid-feedback">Admission fee is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="editDiscountType" class="form-label">Discount Type <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="editDiscountType" name="discount_type" required>
                                    <option value="Flat" selected>Flat</option>
                                    <option value="Percentage">Percentage</option>
                                </select>
                                <div class="invalid-feedback">Discount type is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <label for="editDiscount" class="form-label">Discount</label>
                                <input type="number" class="form-control" id="editDiscount" name="discount"
                                    placeholder="e.g., 10">
                                <div class="invalid-feedback">Discount is required.</div>
                            </div>

                            <div class="col-md-4 col-lg-2">
                                <label class="form-label">Plan Price</label>
                                <input type="text" class="form-control" value="0" name="plan_price"
                                    id="editPlanPrice" readonly>
                                <div class="invalid-feedback">The plan price field must be at least 0.</div>
                            </div>

                            <div class="col-md-4 col-lg-2">
                                <label class="form-label">Final Price <span style="font-size: smaller;">(After
                                        Discount)</span></label>
                                <input type="text" class="form-control" value="0" name="final_price"
                                    id="editFinal_price" readonly>
                                <div class="invalid-feedback">The final price field must be at least 0.</div>
                            </div>

                            <div class="col-md-4 col-lg-2">
                                <label class="form-label">Due Amount</label>
                                <input type="text" class="form-control text-danger fw-bold" value="0"
                                    name="due_amount" id="editDue_amount" readonly>
                                <div class="invalid-feedback">The due amount field must be at least 0.</div>
                            </div>


                        </div>
                    </div>

                    <div class="modal-footer px-4">
                        <button type="submit" id="editPlanBtn" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> Update
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    
    <div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form id="addPaymentForm" method="POST" action="#">
                @csrf
                <div class="modal-content">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="addPaymentModalLabel">
                            <i class="bi bi-person-plus-fill me-2"></i> Add Payment
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body px-4">
                        <div class="row gy-4">
                            <input type="hidden" value="" name="addPaymentMemberId" id="addPaymentMemberId">
                            <div class="col-md-12 col-lg-12">
                                <label for="addPaymentMember" class="form-label"> Member Name<span class="text-danger">*</span></label>
                                <input type="text" readonly class="form-control" id="addPaymentMember" name="member_name" required
                                    placeholder="e.g., John Doe">
                                <div class="invalid-feedback">Member is required.</div>
                            </div>

                            <div class="col-md-12 col-lg-12">
                                <label for="addPaymentDate" class="form-label"> Payment Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="addPaymentDate" name="payment_date" required>
                                <div class="invalid-feedback">Payment date is required.</div>
                            </div>

                            <div class="col-md-12 col-lg-12">
                                <label for="addPaymentMode" class="form-label"> Payment Mode <span class="text-danger">*</span></label>
                                <select class="form-select" id="addPaymentMode" name="payment_mode" required>
                                    <option selected disabled value="">Select Payment Mode</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Phone Pay">Phone Pay</option>
                                    <option value="Google Pay">Google Pay</option>
                                    <option value="Other">Other</option>
                                </select>
                                <div class="invalid-feedback">Payment mode is required.</div>
                            </div>

                            <div class="col-md-12 col-lg-12">
                                <label for="addPaymentAmount" class="form-label"> Amount <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="addPaymentAmount" name="amount" required
                                    placeholder="e.g., 1000">
                                <div class="invalid-feedback">Amount is required.</div>
                            </div>

                            <input type="hidden" id="currentDueAmount" name="currentDueAmount" value="">
                            <input type="hidden" id="currentPlanId" name="currentPlanId" value="">

                            <div class="col-md-12 col-lg-12">
                                <label for="addPaymentDueAmount" class="form-label"> Due Amount</label>
                                <input type="number" readonly class="form-control text-danger fw-bold" id="addPaymentDueAmount" name="due_amount" required>
                                <div class="invalid-feedback">Due amount is required.</div>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer px-4">
                        <button type="submit" id="editPlanBtn" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> Add Payment
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
