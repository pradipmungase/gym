@extends('admin.layout.adminApp')
@section('content')
    <main id="content" role="main" class="main">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-end justify-content-between mb-4">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <h1 class="page-header-title">Tenant Details</h1>
                    </div>
                    <div class="col-md-6 text-md-end d-flex flex-wrap gap-2 justify-content-md-end">
                        <a class="btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#addTenantModal">
                            <i class="bi bi-person-plus-fill me-1"></i> Add Tenant
                        </a>
                    </div>
                </div>

            </div>
                <div class="table-responsive datatable-custom">
                    <table id="datatable"
                        class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                        <thead class="thead-light">
                            <tr>
                                <th class="table-column-ps-0">Sr no</th>
                                <th class="table-column-ps-0">Full name</th>
                                <th>Rent Due</th>
                                <th>Wing Name</th>
                                <th>Room No</th>
                                <th>Admission_Date</th>
                            </tr>
                        </thead>

                        <tbody id="userTableBody"></tbody>

                        {{-- <tbody>
                            <tr>
                                <td class="table-column-ps-0">1</td>
                                <td class="table-column-ps-0">
                                    <a class="d-flex align-items-center" href="./user-profile.html">
                                        <div class="flex-shrink-0">
                                            <div class="avatar avatar-sm avatar-circle">
                                                <img class="avatar-img" src="./assets/img/160x160/img10.jpg"
                                                    alt="Image Description">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="text-inherit mb-0">Amanda Harvey <i
                                                    class="bi-patch-check-fill text-primary" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Top endorsed"></i></h5>
                                        </div>
                                    </a>
                                </td>
                                <td>
                                    <span class="legend-indicator bg-success"></span>Successful
                                </td>
                                <td>Unassigned</td>
                                <td>amanda@site.com</td>
                                <td>1 year ago</td>
                                <td>67989</td>
                            </tr>
                        </tbody> --}}
                    </table>
                </div>

        </div>
    </main>

    <!-- Add room Modal -->
<div class="modal fade" id="addTenantModal" tabindex="-1" aria-labelledby="addTenantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="addTenantForm" method="POST" action="#">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <h5 class="modal-title" id="addTenantModalLabel">
                        <i class="bi bi-person-plus-fill me-2"></i> Add New Tenant
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-4">
                    <div class="row g-3">

                        <!-- Full Name -->
                        <div class="col-md-6">
                            <label for="tenantName" class="form-label">
                                Full Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="tenantName" name="full_name" required placeholder="e.g., John Doe">
                            <div class="invalid-feedback">Full name is required.</div>
                        </div>

                        <!-- Email Address -->
                        <div class="col-md-6">
                            <label for="tenantEmail" class="form-label">
                                Email Address <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control" id="tenantEmail" name="email" required placeholder="e.g., john@example.com">
                            <div class="invalid-feedback">Valid email is required.</div>
                        </div>

                        <!-- Mobile No -->
                        <div class="col-md-6">
                            <label for="tenantMobile" class="form-label">
                                Mobile No <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control" id="tenantMobile" name="mobile" required placeholder="e.g., 9876543210">
                            <div class="invalid-feedback">Mobile number is required.</div>
                        </div>

                        <!-- Wing Selection -->
                        <div class="col-md-6">
                            <label for="wingSelect" class="form-label">
                                Wing <span class="text-danger">*</span>
                            </label>
                            <select onchange="loadRoomDataForDropdown(this.value)" class="form-select" id="wingSelect" name="wing_id" required>
                                <option value="">Select Wing</option>
                                <!-- Dynamically add options -->
                            </select>
                            <div class="invalid-feedback">Please select a wing.</div>
                        </div>

                        <!-- Room Selection -->
                        <div class="col-md-6">
                            <label for="roomSelect" class="form-label">
                                Room <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="roomSelect" name="room_id" required>
                                <option value="">Select Room</option>
                            </select>
                            <div class="invalid-feedback">Please select a room.</div>
                        </div>

                        <!-- Joining Date -->
                        <div class="col-md-6">
                            <label for="joiningDate" class="form-label">
                                Joining Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control" id="joiningDate" name="joining_date" required>
                            <div class="invalid-feedback">Please select a joining date.</div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer px-4">
                    <button type="submit" id="submitTenantBtn" class="btn btn-success">
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
