@extends('admin.layout.adminApp')
@section('content')
    <main id="content" role="main" class="main">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-end justify-content-between mb-4">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <h1 class="page-header-title">Wing Details</h1>
                    </div>
                    <div class="col-md-6 text-md-end d-flex flex-wrap gap-2 justify-content-md-end">
                        <select onchange="loadRoomData(this.value)" class="form-select w-auto" id="wingSelect">
                            <option value="">Select Wing</option>
                        </select>

                        <a class="btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                            <i class="bi bi-door-open-fill me-1"></i> Add Room
                        </a>

                        <a class="btn btn-secondary" href="#" data-bs-toggle="modal" data-bs-target="#addWingModal">
                            <i class="bi bi-building-add me-1"></i> Add Wing
                        </a>
                    </div>
                </div>

            </div>
            <div id="roomsLoader" class="text-center my-4" style="display: none;">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2">Loading rooms please wait...</p>
            </div>


            <div id="roomsContainer">
                <p class="text-center">
                    <span class="text-center">Select wing to view rooms.</span>
                </p>
            </div>

        </div>
    </main>
    <!-- Add Wing Modal -->
    <div class="modal fade" id="addWingModal" tabindex="-1" aria-labelledby="addWingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="addWingForm" method="POST" action="#">
                <div class="modal-content">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="addWingModalLabel">Add Wing</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="wingName" class="form-label">
                                Wing Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="wingName" name="wing_name" required
                                placeholder="Enter Wing Name">
                        </div>
                        <div class="mb-3">
                            <label for="floor" class="form-label">
                                Floor <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control" id="floor" name="floor" required min="0"
                                placeholder="Enter Floor Number">
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



    <!-- Add room Modal -->
    <div class="modal fade" id="addRoomModal" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg"> <!-- Bigger Modal -->
            <form id="addRoomForm" method="POST" action="#">
                <div class="modal-content">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="addRoomModalLabel">
                            <i class="bi bi-door-open-fill me-2"></i> Add New Room
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body px-4">
                        <div class="row g-3">
                            <!-- Wing Name -->
                            <div class="col-md-6">
                                <label for="wingSelect2" class="form-label">
                                    Wing Name <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="wingSelect2" name="pg_wing_pid" required>
                                    <option value="">Select Wing</option>
                                    <!-- dynamically added options -->
                                </select>
                                <div class="invalid-feedback">Please select a wing.</div>
                            </div>

                            <!-- Floor -->
                            <div class="col-md-6">
                                <label for="floor" class="form-label">
                                    Floor <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" id="floor2" name="floor" required
                                    min="0" placeholder="e.g., 1">
                                <div class="invalid-feedback">Enter a valid floor number.</div>
                            </div>

                            <!-- Room No -->
                            <div class="col-md-6">
                                <label for="roomNo" class="form-label">
                                    Room No <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="roomNo" name="room_no" required
                                    placeholder="e.g., 101A">
                                <div class="invalid-feedback">Room number is required.</div>
                            </div>

                            <!-- No. of Beds -->
                            <div class="col-md-6">
                                <label for="beds" class="form-label">
                                    No. of Beds <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" id="beds" name="beds" required
                                    min="1" placeholder="e.g., 3">
                                <div class="invalid-feedback">Please enter number of beds.</div>
                            </div>

                            <!-- Room Rent -->
                            <div class="col-md-6">
                                <label for="roomRent" class="form-label">
                                    Room Rent <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" id="roomRent" name="room_rent" required
                                    min="0" placeholder="e.g., 5000">
                                <div class="invalid-feedback">Please enter room rent.</div>
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

    <div class="modal fade" id="editRoomModal" tabindex="-1" aria-labelledby="editRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg"> <!-- Bigger Modal -->
            <form id="editRoomForm" method="POST" action="#">
                <div class="modal-content">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="editRoomModalLabel">
                            <i class="bi bi-door-open-fill me-2"></i> Edit Room
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body px-4">
                        <div class="row g-3">
                            <!-- Wing Name -->
                            <div class="col-md-6">
                                <label for="editWingSelect2" class="form-label">
                                    Wing Name <span class="text-danger">*</span>
                                </label>
                                <select disabled class="form-select" id="editWingSelect2" name="pg_wing_pid" required>
                                    <option value="">Select Wing</option>
                                </select>
                                <div class="invalid-feedback">Please select a wing.</div>
                            </div>

                            <!-- Floor -->
                            <div class="col-md-6">
                                <label for="floor" class="form-label">
                                    Floor <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" id="editFloor2" name="floor" required
                                    min="0" placeholder="e.g., 1">
                                <div class="invalid-feedback">Enter a valid floor number.</div>
                            </div>

                            <!-- Room No -->
                            <div class="col-md-6">
                                <label for="roomNo" class="form-label">
                                    Room No <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="editRoomNo" name="room_no" required
                                    placeholder="e.g., 101A">
                                <div class="invalid-feedback">Room number is required.</div>
                            </div>

                            <!-- No. of Beds -->
                            <div class="col-md-6">
                                <label for="beds" class="form-label">
                                    No. of Beds <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" id="editBeds" name="beds" required
                                    min="1" placeholder="e.g., 3">
                                <div class="invalid-feedback">Please enter number of beds.</div>
                            </div>

                            <!-- Room Rent -->
                            <div class="col-md-6">
                                <label for="editRoomRent" class="form-label">
                                    Room Rent <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" id="editRoomRent" name="room_rent" required
                                    min="0" placeholder="e.g., 5000">
                                <div class="invalid-feedback">Please enter room rent.</div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" class="form-control" id="editRoomId" name="room_id" required>
                    <div class="modal-footer px-4">
                        <button type="submit" class="btn btn-success" id="editRoomSubmitBtn">
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



    <div class="modal fade" id="addTenantModal" tabindex="-1" aria-labelledby="addTenantModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="addTenantForm2" method="POST" action="#">
                <div class="modal-content">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="addTenantModalLabel">
                            <i class="bi bi-person-plus-fill me-2"></i> Add New Tenant
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body px-4">
                        <div class="row g-3">

                            <!-- Full Name -->
                            <div class="col-md-6">
                                <label for="tenantName2" class="form-label">
                                    Full Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="tenantName2" name="full_name" required
                                    placeholder="e.g., John Doe">
                                <div class="invalid-feedback">Full name is required.</div>
                            </div>

                            <!-- Email Address -->
                            <div class="col-md-6">
                                <label for="tenantEmail2" class="form-label">
                                    Email Address <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control" id="tenantEmail2" name="email" required
                                    placeholder="e.g., john@example.com">
                                <div class="invalid-feedback">Valid email is required.</div>
                            </div>

                            <!-- Mobile No -->
                            <div class="col-md-6">
                                <label for="tenantMobile2" class="form-label">
                                    Mobile No <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" id="tenantMobile2" name="mobile" required
                                    placeholder="e.g., 9876543210">
                                <div class="invalid-feedback">Mobile number is required.</div>
                            </div>

                            <!-- Wing Selection -->
                            <div class="col-md-6">
                                <label for="wingSelect2" class="form-label">
                                    Wing <span class="text-danger">*</span>
                                </label>
                                <select disabled class="form-select" id="addWingSelect2" name="wing_id" required>
                                    <option value="">Select Wing</option>
                                    <!-- Dynamically add options -->
                                </select>
                                <div class="invalid-feedback">Please select a wing.</div>
                            </div>

                            <!-- Room Selection -->
                            <div class="col-md-6">
                                <label for="roomSelect2" class="form-label">
                                    Room <span class="text-danger">*</span>
                                </label>
                                <input readonly type="text" class="form-control" id="addRoomSelect2" name="mobile"
                                    required placeholder="e.g., 9876543210">
                                <div class="invalid-feedback">Please select a room.</div>
                            </div>

                            <!-- Joining Date -->
                            <div class="col-md-6">
                                <label for="joiningDate2" class="form-label">
                                    Joining Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control" id="joiningDate2" name="joining_date"
                                    required>
                                <div class="invalid-feedback">Please select a joining date.</div>
                            </div>

                        </div>
                    </div>
                    <input type="hidden" class="form-control" id="room_id" name="room_id" required>
                    <div class="modal-footer px-4">
                        <button type="submit" id="submitTenantBtn2" class="btn btn-success">
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
@endsection
