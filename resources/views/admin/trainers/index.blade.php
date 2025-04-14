@extends('admin.layout.adminApp')
@section('content')
    <main id="content" role="main" class="main">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center justify-content-between mb-4">
                    <div class="col-6">
                        <h1 class="page-header-title mb-0">Trainer Details</h1>
                    </div>
                    <div class="col-6 text-end">
                        <a class="btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#addTrainerModal">
                            <i class="bi bi-person-plus-fill me-1"></i> Add Trainer
                        </a>
                    </div>
                </div>
            </div>
            
            <div id="trainers-table-container" class="text-center my-4 table-responsive datatable-custom">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2">Loading trainers please wait...</p>
            </div>

        </div>
    </main>

<div class="modal fade" id="addTrainerModal" tabindex="-1" aria-labelledby="addTrainerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="addTrainerForm" method="POST" action="#" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header text-white">
                    <h5 class="modal-title" id="addTrainerModalLabel">
                        <i class="bi bi-person-plus-fill me-2"></i> Add New Trainer
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-4">
                    <div class="row g-3">

                        <!-- Trainer Name -->
                        <div class="col-md-6">
                            <label for="trainerName" class="form-label">Trainer Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="trainerName" name="name" required placeholder="e.g., John Doe">
                            <div class="invalid-feedback">Trainer name is required.</div>   
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="trainerEmail" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="trainerEmail" name="email" required placeholder="e.g., john@example.com">
                            <div class="invalid-feedback">Email is required.</div>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6">
                            <label for="trainerPhone" class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="trainerPhone" name="phone" required placeholder="e.g., 9876543210">
                            <div class="invalid-feedback">Phone is required.</div>
                        </div>

                        <!-- Gender -->
                        <div class="col-md-6">
                            <label for="trainerGender" class="form-label">Gender <span class="text-danger">*</span></label>
                            <select class="form-select" id="trainerGender" name="gender" required>
                                <option selected disabled value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                            <div class="invalid-feedback">Gender is required.</div>
                        </div>

                        <!-- Address -->
                        <div class="col-md-12">
                            <label for="trainerAddress" class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="trainerAddress" name="address" rows="2" required></textarea>
                            <div class="invalid-feedback">Address is required.</div>
                        </div>

                        <!-- Image -->
                        <div class="col-md-6">
                            <label for="trainerImage" class="form-label">Image</label>
                            <input type="file" class="form-control" id="trainerImage" name="image" accept="image/*">
                            <div class="invalid-feedback">Image is required.</div>
                        </div>

                        <!-- Joining Date -->
                        <div class="col-md-6">
                            <label for="joiningDate" class="form-label">Joining Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="joiningDate" name="joining_date" required>
                            <div class="invalid-feedback">Joining date is required.</div>
                        </div>

                        <!-- Monthly Salary -->
                        <div class="col-md-6">
                            <label for="monthlySalary" class="form-label">Monthly Salary <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="monthlySalary" name="monthly_salary" required placeholder="e.g., 30000">
                            <div class="invalid-feedback">Monthly salary is required.</div>
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

<div class="modal fade" id="editTrainerModal" tabindex="-1" aria-labelledby="editTrainerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="editTrainerForm" method="POST" action="#" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="trainer_id" id="editTrainerId">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <h5 class="modal-title" id="editTrainerModalLabel">
                        <i class="bi bi-pencil-square me-2"></i> Edit Trainer
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-4">
                    <div class="row g-3">

                        <!-- Trainer Name -->
                        <div class="col-md-6">
                            <label for="editTrainerName" class="form-label">Trainer Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editTrainerName" name="name" required>
                            <div class="invalid-feedback">Trainer name is required.</div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="editTrainerEmail" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="editTrainerEmail" name="email" required>
                            <div class="invalid-feedback">Email is required.</div>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6">
                            <label for="editTrainerPhone" class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editTrainerPhone" name="phone" required>
                            <div class="invalid-feedback">Phone is required.</div>
                        </div>

                        <!-- Gender -->
                        <div class="col-md-6">
                            <label for="editTrainerGender" class="form-label">Gender <span class="text-danger">*</span></label>
                            <select class="form-select" id="editTrainerGender" name="gender" required>
                                <option selected disabled value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                            <div class="invalid-feedback">Gender is required.</div>
                        </div>

                        <!-- Address -->
                        <div class="col-md-12">
                            <label for="editTrainerAddress" class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="editTrainerAddress" name="address" rows="2" required></textarea>
                            <div class="invalid-feedback">Address is required.</div>
                        </div>

                        <!-- Image -->
                        <div class="col-md-6">
                            <label for="editTrainerImage" class="form-label">Image</label>
                            <input type="file" class="form-control" id="editTrainerImage" name="image" accept="image/*">
                            <div class="invalid-feedback">Image is required.</div>
                        </div>

                        <!-- Joining Date -->
                        <div class="col-md-6">
                            <label for="editJoiningDate" class="form-label">Joining Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="editJoiningDate" name="joining_date" required>
                            <div class="invalid-feedback">Joining date is required.</div>
                        </div>

                        <!-- Monthly Salary -->
                        <div class="col-md-6">
                            <label for="editMonthlySalary" class="form-label">Monthly Salary <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="editMonthlySalary" name="monthly_salary" required>
                            <div class="invalid-feedback">Monthly salary is required.</div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer px-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Update
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
