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
            <div id="menbers-table-container" class="text-center my-4 table-responsive datatable-custom">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2">Loading menbers please wait...</p>
            </div>

        </div>
    </main>

    <div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
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

                            <!-- Name -->
                            <div class="col-md-6">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required
                                    placeholder="Enter name">
                                <div class="invalid-feedback">Name is required.</div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required
                                    placeholder="Enter email">
                                <div class="invalid-feedback">Valid email is required.</div>
                            </div>

                            <!-- Mobile No -->
                            <div class="col-md-6">
                                <label for="mobile" class="form-label">Mobile No <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="mobile" name="mobile" required
                                    placeholder="Enter mobile number">
                                <div class="invalid-feedback">Mobile number is required.</div>
                            </div>

                            <!-- Joining Date -->
                            <div class="col-md-6">
                                <label for="joining_date" class="form-label">Joining Date <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="joining_date" name="joining_date" required>
                                <div class="invalid-feedback">Joining date is required.</div>
                            </div>



                            <!-- Gender -->
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

                            <!-- Age -->
                            <div class="col-md-3">
                                <label for="age" class="form-label">Age <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="age" name="age" required
                                    placeholder="Enter age">
                                <div class="invalid-feedback">Age is required.</div>
                            </div>

                            <!-- Plan Dropdown -->
                            <div class="col-md-6">
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

                            <!-- Discount Type -->
                            <div class="col-md-3">
                                <label for="discount_type" class="form-label">Discount Type</label>
                                <select class="form-select" id="discount_type" name="discount_type">
                                    <option selected value="Flat">Flat</option>
                                    <option value="Percentage">Percentage</option>
                                </select>
                            </div>

                            <!-- Discount -->
                            <div class="col-md-3">
                                <label for="discount" class="form-label">Discount (%)</label>
                                <input type="number" class="form-control" id="discount" name="discount"
                                    placeholder="e.g., 10">
                            </div>

                            <!-- Plan Price -->
                            <div class="col-md-3">
                                <label class="form-label">Plan Price</label>
                                <input type="text" class="form-control" value="0" name="plan_price" id="plan_price"
                                    readonly>
                            </div>

                            <!-- Final Price -->
                            <div class="col-md-3">
                                <label class="form-label">Final Price</label>
                                <input type="text" class="form-control" value="0" name="final_price" id="final_price"
                                    readonly>
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


    <div class="modal fade" id="editMenberModal" tabindex="-1" aria-labelledby="editMenberModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="editMenberForm" method="POST" action="#">
                <div class="modal-content">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="editMenberModalLabel">
                            <i class="bi bi-person-plus-fill me-2"></i> Edit Menber
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body px-4">
                        <div class="row g-3">
                            <input type="hidden" name="menber_id" id="editMenberId">

                            <!-- Full Name -->
                            <div class="col-md-6">
                                <label for="editPlanName" class="form-label">
                                    Plan Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="editMenberName" name="menber_name"
                                    required placeholder="e.g., Menber Name">
                                <div class="invalid-feedback">Menber name is required.</div>
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
