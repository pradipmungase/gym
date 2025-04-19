@extends('admin.layout.adminApp')
@section('content')
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
            <form id="addMemberForm" method="POST" action="#">
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
                        <div class="row g-3">

                            <!-- 🧍 Personal Information -->
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mt-3">🧍 Personal Information</h5>
                            </div>

                            <div class="col-md-3">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required
                                    placeholder="e.g., John Doe">
                                <div class="invalid-feedback">Name is required.</div>
                            </div>

                            <div class="col-md-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="e.g., johndoe@gmail.com">
                                <div class="invalid-feedback">Valid email is required.</div>
                            </div>

                            <div class="col-md-3">
                                <label for="mobile" class="form-label">Mobile No <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="mobile" name="mobile" required
                                    placeholder="e.g., 03001234567">
                                <div class="invalid-feedback">Mobile number is required.</div>
                            </div>

                            <div class="col-md-3">
                                <label for="birth_date" class="form-label">Birth Date</label>
                                <input type="date" class="form-control" id="birth_date" name="birth_date">
                            </div>

                            <div class="col-md-3">
                                <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option selected disabled value="">Select gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                                <div class="invalid-feedback">Gender is required.</div>
                            </div>

                            <div class="col-md-3">
                                <label for="menberImg" class="form-label">Member Image</label>
                                <input type="file" class="form-control" name="menberImg" id="menberImg">
                                <div class="invalid-feedback">Gender is required.</div>
                            </div>

                            <!-- 🏋️ Training Details -->
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mt-4">🏋️ Training Details</h5>
                            </div>

                            <div class="col-md-3">
                                <label for="joining_date" class="form-label">Joining Date <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="joining_date" name="joining_date"
                                    required>
                                <div class="invalid-feedback">Joining date is required.</div>
                            </div>

                            <div class="col-md-3">
                                <label for="batch" class="form-label">Batch <span class="text-danger">*</span></label>
                                <select class="form-select" id="batch" name="batch" required>
                                    <option selected disabled value="">Select batch</option>
                                    <option value="Morning">Morning</option>
                                    <option value="Afternoon">Afternoon</option>
                                    <option value="Evening">Evening</option>
                                </select>
                                <div class="invalid-feedback">Batch is required.</div>
                            </div>

                            <div class="col-md-3">
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

                            <div class="col-md-3">
                                <label for="plan" class="form-label">Plan <span class="text-danger">*</span></label>
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


                            <div class="col-md-3">
                                <label for="paymentMode" class="form-label">Payment Mode <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="paymentMode" name="paymentMode" required>
                                    <option selected disabled value="">Select Payment Mode</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Phone Pay">Phone Pay</option>
                                    <option value="Google Pay">Google Pay</option>
                                    <option value="Other">Other</option>
                                </select>
                                <div class="invalid-feedback">Payment selection is required.</div>
                            </div>

                            <div class="col-md-3">
                                <label for="admission_fee" class="form-label">Admission Fee <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="admission_fee" name="admission_fee"
                                    required placeholder="Enter admission fee">
                                <div class="invalid-feedback">Admission fee is required.</div>
                            </div>

                            <div class="col-md-3">
                                <label for="discount_type" class="form-label">Discount Type <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="discount_type" name="discount_type" required>
                                    <option value="Flat" selected>Flat</option>
                                    <option value="Percentage">Percentage</option>
                                </select>
                                <div class="invalid-feedback">Discount type is required.</div>
                            </div>

                            <div class="col-md-3">
                                <label for="discount" class="form-label">Discount (%)</label>
                                <input type="number" class="form-control" id="discount" name="discount"
                                    placeholder="e.g., 10">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Plan Price</label>
                                <input type="text" class="form-control" value="0" name="plan_price"
                                    id="plan_price" readonly>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Final Price</label>
                                <input type="text" class="form-control" value="0" name="final_price"
                                    id="final_price" readonly>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Due Amount</label>
                                <input type="text" class="form-control text-danger fw-bold" value="0"
                                    name="due_amount" id="due_amount" readonly>
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
        <div class="modal-dialog modal-lg">
            <form id="editmemberForm" method="POST" action="#">
                <div class="modal-content">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="editmemberModalLabel">
                            <i class="bi bi-person-plus-fill me-2"></i> Edit member
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body px-4">
                        <div class="row g-3">
                            <input type="hidden" name="member_id" id="editmemberId">

                            <!-- Full Name -->
                            <div class="col-md-6">
                                <label for="editPlanName" class="form-label">
                                    Plan Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="editmemberName" name="member_name"
                                    required placeholder="e.g., member Name">
                                <div class="invalid-feedback">member name is required.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="duration" class="form-label">
                                    Duration <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="editDuration" name="duration" required>
                                    <option selected disabled value="">Select Duration</option>
                                    <option value="1 week">1 Week</option>
                                    <option value="1 month">1 Month</option>
                                    <option value="6 month">6 Month</option>
                                    <option value="1 year">1 Year</option>
                                </select>
                                <div class="invalid-feedback">Please select a duration.</div>
                            </div>


                            <!-- Mobile No -->
                            <div class="col-md-6">
                                <label for="price" class="form-label">
                                    Price <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" id="editPrice" name="price" required
                                    placeholder="e.g., 1000">
                                <div class="invalid-feedback">Price is required.</div>
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
@endsection
