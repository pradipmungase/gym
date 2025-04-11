@extends('admin.layout.adminApp')
@section('content')
    <main id="content" role="main" class="main">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-end justify-content-between mb-4">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <h1 class="page-header-title">Add Member</h1>
                    </div>
                </div>
            </div>

            <!-- Normal Inline Form Starts Here -->
            <div class="card">
                <div class="card-body">
                    <form id="addMenberForm" method="POST" action="{{ route('addMenberPOST') }}">
                        @csrf
                        <div class="row g-3">
                            <!-- Full Name -->
                            <div class="col-md-6">
                                <label for="menberFirstName" class="form-label">
                                    First Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                    id="menberFirstName" name="first_name" value="{{ old('first_name') }}" required
                                    placeholder="e.g., John Doe">
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="menberLastName" class="form-label">
                                    Last Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                    id="menberLastName" name="last_name" value="{{ old('last_name') }}" required
                                    placeholder="e.g., John Doe">
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email Address -->
                            <div class="col-md-6">
                                <label for="menberEmail" class="form-label">
                                    Email Address <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="menberEmail" name="email" value="{{ old('email') }}" required
                                    placeholder="e.g., john@example.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Mobile No -->
                            <div class="col-md-6">
                                <label for="menberMobile" class="form-label">
                                    Mobile No <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('mobile') is-invalid @enderror"
                                    id="menberMobile" name="mobile" value="{{ old('mobile') }}" required
                                    placeholder="e.g., 9876543210">
                                @error('mobile')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Joining Date -->
                            <div class="col-md-6">
                                <label for="joiningDate" class="form-label">
                                    Joining Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control @error('joining_date') is-invalid @enderror"
                                    id="joiningDate" name="joining_date" value="{{ old('joining_date') }}" required>
                                @error('joining_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-4 d-flex justify-content-center">
                            <button type="submit" id="submitTenantBtn" class="btn btn-success me-2">
                                <i class="bi bi-check-circle me-1"></i> Submit
                            </button>
                            <a href="#" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i> Cancel
                            </a>
                        </div>

                    </form>

                </div>
            </div>
            <!-- End Form -->
        </div>
    </main>
@endsection
