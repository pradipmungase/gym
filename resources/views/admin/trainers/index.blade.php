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
                        <h1 class="page-header-title mb-0">Trainer Details</h1>
                    </div>
                    <div class="col-6 text-end">
                        <a class="btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#addTrainerModal">
                            <i class="bi bi-person-plus-fill me-1"></i> Add Trainer
                        </a>
                    </div>
                </div>
            </div>

            <div id="trainers-table-container" class="text-center my-4 table-responsive datatable-custom"
                style="height: 800px">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2">Loading...</p>
            </div>

        </div>
    </main>

    <div class="modal fade" id="addTrainerModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="addTrainerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="addTrainerForm" method="POST" action="#" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="addTrainerModalLabel">
                            <i class="bi bi-person-plus-fill me-2"></i> Add New Trainer
                        </h5>
                        <button type="button" class="clearFromDataWithError btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body px-4">
                        <div class="row g-3">

                            <!-- Trainer Name -->
                            <div class="col-md-6">
                                <label for="trainerName" class="form-label">Trainer Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="trainerName" name="name" required
                                    placeholder="e.g., John Doe">
                                <div class="invalid-feedback">Trainer name is required.</div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="trainerEmail" class="form-label">Email <span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="trainerEmail" name="email" required
                                    placeholder="e.g., john@example.com">
                                <div class="invalid-feedback">Email is required.</div>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <label for="trainerPhone" class="form-label">Phone <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="trainerPhone" name="phone" required
                                    placeholder="e.g., 9876543210">
                                <div class="invalid-feedback">Phone is required.</div>
                            </div>

                            <!-- Gender -->
                            <div class="col-md-6">
                                <label for="trainerGender" class="form-label">Gender <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="trainerGender" name="gender" required>
                                    <option selected disabled value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                                <div class="invalid-feedback">Gender is required.</div>
                            </div>

                            <!-- Address -->
                            <div class="col-md-12">
                                <label for="trainerAddress" class="form-label">Address <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" id="trainerAddress" name="address" rows="2" required></textarea>
                                <div class="invalid-feedback">Address is required.</div>
                            </div>


                            <!-- Joining Date -->
                            <div class="col-md-6">
                                <label for="joiningDate" class="form-label">Joining Date <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="joiningDate" name="joining_date" required>
                                <div class="invalid-feedback">Joining date is required.</div>
                            </div>

                            <!-- Monthly Salary -->
                            <div class="col-md-6">
                                <label for="monthlySalary" class="form-label">Monthly Salary <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="monthlySalary" name="monthly_salary"
                                    required placeholder="e.g., 30000">
                                <div class="invalid-feedback">Monthly salary is required.</div>
                            </div>

                            <div class="col-md-6 col-lg-6">
                                <div class="d-flex align-items-center">
                                    <!-- Avatar -->
                                    <label class="avatar avatar-xl avatar-circle" for="avatarUploader">
                                        <img id="avatarImgTrainer" class="avatar-img"
                                            src="{{ asset('assets/img/160x160/images (1).jpg') }}"
                                            alt="Image Description">
                                    </label>

                                    <div class="d-flex gap-3 ms-4">
                                        <div class="form-attachment-btn btn btn-sm btn-primary">Upload photo
                                            <input type="file" accept="image/*" name="trainerImage"
                                                class="js-file-attach form-attachment-btn-label" id="avatarUploader"
                                                data-hs-file-attach-options='{"textTarget": "#avatarImgTrainer","mode": "image","targetAttr": "src","resetTarget": ".js-file-attach-reset-img","resetImg": "../assets/img/160x160/images (1).jpg","allowTypes": [".png", ".jpeg", ".jpg"]}'>
                                        </div>
                                        <!-- End Avatar -->

                                        <button type="button"
                                            class="js-file-attach-reset-img btn btn-outline-danger btn-sm">Delete</button>
                                    </div>
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

    <div class="modal fade" id="editTrainerModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="editTrainerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="editTrainerForm" method="POST" action="#" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="trainer_id" id="editTrainerId">
                <div class="modal-content">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="editTrainerModalLabel">
                            <i class="bi bi-pencil-square me-2"></i> Edit Trainer
                        </h5>
                        <button type="button" class="clearFromDataWithError btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body px-4">
                        <div class="row g-3">

                            <!-- Trainer Name -->
                            <div class="col-md-6">
                                <label for="editTrainerName" class="form-label">Trainer Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editTrainerName" name="name" required>
                                <div class="invalid-feedback">Trainer name is required.</div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="editTrainerEmail" class="form-label">Email <span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="editTrainerEmail" name="email"
                                    required>
                                <div class="invalid-feedback">Email is required.</div>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <label for="editTrainerPhone" class="form-label">Phone <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editTrainerPhone" name="phone"
                                    required>
                                <div class="invalid-feedback">Phone is required.</div>
                            </div>

                            <!-- Gender -->
                            <div class="col-md-6">
                                <label for="editTrainerGender" class="form-label">Gender <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="editTrainerGender" name="gender" required>
                                    <option selected disabled value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                                <div class="invalid-feedback">Gender is required.</div>
                            </div>

                            <!-- Address -->
                            <div class="col-md-12">
                                <label for="editTrainerAddress" class="form-label">Address <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" id="editTrainerAddress" name="address" rows="2" required></textarea>
                                <div class="invalid-feedback">Address is required.</div>
                            </div>


                                                        <!-- Joining Date -->
                            <div class="col-md-6">
                                <label for="editJoiningDate" class="form-label">Joining Date <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="editJoiningDate" name="joining_date" placeholder="e.g., 2025-01-01"
                                    required>
                                <div class="invalid-feedback">Joining date is required.</div>
                            </div>

                            <!-- Monthly Salary -->
                            <div class="col-md-6">
                                <label for="editMonthlySalary" class="form-label">Monthly Salary <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="editMonthlySalary" name="monthly_salary" placeholder="e.g., 30000"
                                    required>
                                <div class="invalid-feedback">Monthly salary is required.</div>
                            </div>



                     
                                                        <div class="col-md-6 col-lg-6">
                                <div class="d-flex align-items-center">
                                    <!-- Avatar -->
                                    <label class="avatar avatar-xl avatar-circle" for="avatarUploader">
                                        <img id="previewEditTrainerImg" class="avatar-img"
                                            src="{{ asset('assets/img/160x160/images (1).jpg') }}"
                                            alt="Image Description">
                                    </label>

                                    <div class="d-flex gap-3 ms-4">
                                        <div class="form-attachment-btn btn btn-sm btn-primary">Upload photo
                                            <input type="file" accept="image/*" name="editTrainerImage"
                                                class="js-file-attach form-attachment-btn-label" id="avatarUploader"
                                                data-hs-file-attach-options='{"textTarget": "#previewEditTrainerImg","mode": "image","targetAttr": "src","resetTarget": ".js-file-attach-reset-img","resetImg": "../assets/img/160x160/images (1).jpg","allowTypes": [".png", ".jpeg", ".jpg"]}'>
                                        </div>
                                        <!-- End Avatar -->

                                        <button type="button"
                                            class="js-file-attach-reset-img btn btn-outline-danger btn-sm">Delete</button>
                                    </div>
                                </div>
                            </div>



                        </div>
                    </div>

                    <div class="modal-footer px-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Update
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
