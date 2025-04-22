@extends('admin.layout.adminApp')
@section('content')
    <main id="content" role="main" class="main">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center justify-content-between mb-4">
                    <div class="col-6">
                        <h1 class="page-header-title mb-0">Membership Plan</h1>
                    </div>
                    <div class="col-6 text-end">
                        <a class="btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#addPlanModal">
                                <i class="bi bi-person-plus-fill me-1"></i> Add Membership Plan
                        </a>
                    </div>
                </div>
            </div>
            <div id="plans-table-container" class="text-center my-4 table-responsive datatable-custom" style="height: 800px">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2">Loading Membership plans please wait...</p>
            </div>

        </div>
    </main>

    <div class="modal fade" id="addPlanModal" tabindex="-1" aria-labelledby="addPlanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="addPlanForm" method="POST" action="#">
                <div class="modal-content">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="addPlanModalLabel">
                            <i class="bi bi-person-plus-fill me-2"></i> Add New Membership Plan
                        </h5>
                        <button type="button" class="clearFromDataWithError btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body px-4">
                        <div class="row g-3">

                            <!-- Full Name -->
                            <div class="col-md-6">
                                <label for="planName" class="form-label">
                                    Plan Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="planName" name="plan_name" required
                                    placeholder="e.g., Plan Name">
                                <div class="invalid-feedback">Plan name is required.</div>
                            </div>

                                                        <div class="col-md-6">
                                <label for="price" class="form-label">
                                    Price <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" id="price" name="price" required
                                    placeholder="e.g., 1000">
                                <div class="invalid-feedback">Price is required.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="duration" class="form-label">
                                    Duration <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" id="duration" name="duration" required
                                    placeholder="e.g., 30">
                                <div class="invalid-feedback">Please select a duration.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="durationType" class="form-label">
                                    Duration Type<span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="durationType" name="duration_type" required>
                                    <option selected disabled value="">Select Duration Type</option>
                                    <option value="days">Days</option>
                                    <option value="weeks">Weeks</option>
                                    <option value="months">Months</option>
                                    <option value="years">Years</option>
                                </select>
                                <div class="invalid-feedback">Please select a duration.</div>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer px-4">
                        <button type="submit" id="submitTenantBtn" class="btn btn-success">
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

    <div class="modal fade" id="editPlanModal" tabindex="-1" aria-labelledby="editPlanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="editPlanForm" method="POST" action="#">
                <div class="modal-content">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="editPlanModalLabel">
                            <i class="bi bi-person-plus-fill me-2"></i> Edit Membership Plan
                        </h5>
                        <button type="button" class="clearFromDataWithError btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body px-4">
                        <div class="row g-3">
                            <input type="hidden" name="plan_id" id="editPlanId">

                            <!-- Full Name -->
                            <div class="col-md-6">
                                <label for="editPlanName" class="form-label">
                                    Plan Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="editPlanName" name="plan_name" required
                                    placeholder="e.g., Plan Name">
                                <div class="invalid-feedback">Plan name is required.</div>
                            </div>

                                                        <div class="col-md-6">
                                <label for="price" class="form-label">
                                    Price <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" id="editPrice" name="price" required
                                    placeholder="e.g., 1000">
                                <div class="invalid-feedback">Price is required.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="editDuration" class="form-label">
                                    Duration <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" id="editDuration" name="duration" required
                                    placeholder="e.g., 30">
                            </div>  

                            <div class="col-md-6">
                                <label for="duration" class="form-label">
                                    Duration <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="editDurationType" name="duration_type" required>
                                    <option selected disabled value="">Select Duration</option>
                                    <option value="days">Days</option>
                                    <option value="weeks">Weeks</option>
                                    <option value="months">Months</option>
                                    <option value="years">Years</option>
                                </select>
                                <div class="invalid-feedback">Please select a duration.</div>
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
@endsection
