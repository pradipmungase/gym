@extends('admin.layout.adminApp')
@section('content')
    <main id="content" role="main" class="main">
        <div class="content container-fluid">
            <div class="container py-5">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card shadow rounded-4 border-0">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-person-circle fs-1 text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="card-title mb-0">Owner Account</h5>
                                        <small class="text-muted">Account Information</small>
                                    </div>
                                    <div class="col-md-6 text-md-end d-flex flex-wrap gap-2 justify-content-md-end">
                                        <a class="btn btn-sm btn-success" href="#" data-bs-toggle="modal"
                                            data-bs-target="#addPGModal">
                                            <i class="bi bi-building-add me-1"></i> Add PG
                                        </a>
                                    </div>
                                </div>

                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <strong>Name:</strong>
                                        <span id="ownerName">Loading...</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <strong>Email:</strong>
                                        <span id="ownerEmail">Loading...</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <strong>Mobile:</strong>
                                        <span id="ownerMobile">Loading...</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <strong>Status:</strong>
                                        <span id="ownerStatus" class="badge">Loading...</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="pgsLoader" class="text-center my-4" style="display: none;">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2">Loading PGs please wait...</p>
            </div>

            <div id="pgsContainer"></div>

        </div>
    </main>
    <!-- Add Wing Modal -->
    <div class="modal fade" id="addPGModal" tabindex="-1" aria-labelledby="addPGModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="addPGForm" method="POST" action="#">
                <div class="modal-content">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="addPGModalLabel">Add PG</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body row">
                        <div class="mb-3 col-md-6">
                            <label for="pgName" class="form-label">PG Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pgName" name="pg_name"
                                placeholder="Enter PG Name">
                            <div class="text-danger" id="pgName_error"></div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="pgAddress" class="form-label">PG Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pgAddress" name="pg_address"
                                placeholder="Enter PG Address">
                            <div class="text-danger" id="pgAddress_error"></div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="pgContact" class="form-label">Contact Number <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pgContact" name="pg_contact"
                                placeholder="Enter 10-digit Contact Number">
                            <div class="text-danger" id="pgContact_error"></div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="pgArea" class="form-label">Area <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pgArea" name="pg_area"
                                placeholder="Enter Area">
                            <div class="text-danger" id="pgArea_error"></div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="pgCity" class="form-label">City <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pgCity" name="pg_city"
                                placeholder="Enter City">
                            <div class="text-danger" id="pgCity_error"></div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="pgState" class="form-label">State <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pgState" name="pg_state"
                                placeholder="Enter State">
                            <div class="text-danger" id="pgState_error"></div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="pgPincode" class="form-label">Pincode <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pgPincode" name="pg_pincode"
                                placeholder="Enter 6-digit Pincode">
                            <div class="text-danger" id="pgPincode_error"></div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="pgLandmark" class="form-label">Landmark <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pgLandmark" name="pg_landmark"
                                placeholder="Enter Landmark">
                            <div class="text-danger" id="pgLandmark_error"></div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="pgType" class="form-label">PG Type <span class="text-danger">*</span></label>
                            <select class="form-select" name="pg_type" id="pgType">
                                <option value="">Select PG Type</option>
                                <option value="Boys">Boys</option>
                                <option value="Girls">Girls</option>
                                <option value="Both">Both</option>
                            </select>
                            <div class="text-danger" id="pgType_error"></div>
                        </div>

                        <div class="mb-3 col-md-12">
                            <div id="tnc-rules2" class="mb-3">
                                <p>Loading Terms & Conditions...</p>
                            </div>
                        </div>
                        <div class="mb-3 col-md-12">
                            <div class="d-flex">
                                <input type="text" id="new-rule-text2" class="form-control me-2"
                                    placeholder="Enter new rule">
                                <button type="button" id="add-rule-btn2" class="btn btn-primary">Add</button>
                            </div>
                            <small id="rule-error" class="text-danger mt-1 d-block" style="display: none;"></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle-fill"></i> Submit
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle-fill"></i> Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
