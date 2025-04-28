@extends('admin.layout.adminApp')
@section('content')
    <main id="content" role="main" class="main">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center justify-content-between mb-4">
                    <div class="col-8">
                        <h1 class="page-header-title mb-0">Announcement Details</h1>
                    </div>
                    <div class="col-4 text-end">
                        <a class="btn btn-primary" href="#" data-bs-toggle="modal"
                            data-bs-target="#addAnnouncementModal">
                            <i class="bi bi-plus-circle me-1"></i> &nbsp; Add
                        </a>
                    </div>
                </div>
            </div>
            <div id="announcement-table-container" class="text-center my-4 table-responsive datatable-custom">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2">Loading...</p>
            </div>

        </div>
    </main>

    <div class="modal fade" id="addAnnouncementModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="addAnnouncementModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <form id="addAnnouncementForm" method="POST" action="#">
                <div class="modal-content">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="addAnnouncementModalLabel">
                            <i class="bi bi-plus-circle me-2"></i> Add New Announcement
                        </h5>
                        <button type="button" class="clearFromDataWithError btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body px-4">
                        <div class="row g-3">

                            <!-- Full Name -->
                            <div class="col-md-4">
                                <label for="title" class="form-label">
                                    Title <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="title" name="title" required
                                    placeholder="e.g., Title">
                                <div class="invalid-feedback">Title is required.</div>
                            </div>




                            <div class="col-md-4">
                                <label for="date" class="form-label">
                                    Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control" id="date" name="date" required min="{{ date('Y-m-d') }}">
                                <div class="invalid-feedback">Date is required.</div>
                            </div>
                            <div class="col-md-4">
                                <label for="for" class="form-label">
                                    For <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" id="for" name="for" required>
                                    <option value="" selected disabled>Select For</option>
                                    <option value="all">All</option>
                                    <option value="members">Members</option>
                                    <option value="trainers">Trainers</option>
                                </select>
                                <div class="invalid-feedback">For is required.</div>
                            </div>

                            <div class="col-md-12">
                                <label for="description" class="form-label">
                                    Description <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" placeholder="Enter Description" id="description" name="description" required></textarea>
                                <div class="invalid-feedback">Description is required.</div>
                            </div>
                        </div>
                        <br><br>
                        <div class="row">   
                            <div class="col-12">
                                <p class="text-warning">Note: Announcement will be sent to all members and trainers as soon as you submit.</p>
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
@endsection
