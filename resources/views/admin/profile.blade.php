@extends('admin.layout.adminApp')
@section('content')
    <main id="content" role="main" class="main">
        <!-- Content -->
        <div class="content container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-grid gap-3 gap-lg-5">
                        <!-- Card -->
                        <div class="card">
                            <!-- Profile Cover -->
                            <div class="profile-cover">
                                <div class="profile-cover-img-wrapper">
                                    <img id="profileCoverImg" class="profile-cover-img"
                                        src="@if (Auth::user()->profile_picture) {{ asset(Auth::user()->profile_picture) }}@else{{ asset('assets/img/1920x400/img2.jpg') }} @endif"
                                        alt="Image Description">

                                    <!-- Custom File Cover -->
                                    <div class="profile-cover-content profile-cover-uploader p-3">
                                        <input type="file" accept="image/*"
                                            class="js-file-attach profile-cover-uploader-input" id="profileCoverUplaoder"
                                            data-hs-file-attach-options='{
                                "textTarget": "#profileCoverImg",
                                "mode": "image",
                                "targetAttr": "src",
                                "allowTypes": [".png", ".jpeg", ".jpg"]
                             }'>
                                        <label class="profile-cover-uploader-label btn btn-sm btn-white"
                                            for="profileCoverUplaoder">
                                            <i class="bi-camera-fill"></i>
                                            <span class="d-none d-sm-inline-block ms-1">Upload header</span>
                                        </label>
                                    </div>
                                    <!-- End Custom File Cover -->
                                </div>
                            </div>
                            <!-- End Profile Cover -->

                            <!-- Avatar -->
                            <label class="avatar avatar-xxl avatar-circle avatar-uploader profile-cover-avatar"
                                for="editAvatarUploaderModal">
                                <img id="editAvatarImgModal" class="avatar-img"
                                    src="@if (Auth::user()->profile_picture) {{ asset(Auth::user()->profile_picture) }}@else{{ asset('assets/img/160x160/images (1).jpg') }} @endif"
                                    alt="Image Description">

                                <input type="file" accept="image/*" class="js-file-attach avatar-uploader-input"
                                    id="editAvatarUploaderModal"
                                    data-hs-file-attach-options='{
                            "textTarget": "#editAvatarImgModal",
                            "mode": "image",
                            "targetAttr": "src",
                            "allowTypes": [".png", ".jpeg", ".jpg"]
                         }'>

                                <span class="avatar-uploader-trigger">
                                    <i class="bi-pencil-fill avatar-uploader-icon shadow-sm"></i>
                                </span>
                            </label>
                            <!-- End Avatar -->

                        </div>
                        <!-- End Card -->

                        <!-- Card -->
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title h4">Basic information</h2>
                            </div>

                            <!-- Body -->
                            <div class="card-body">
                                <!-- Form -->
                                <form action="{{ route('profile.update') }}" method="POST">
                                    @csrf

                                    <!-- GYM Name -->
                                    <div class="row mb-4">
                                        <label for="gymName" class="col-sm-3 col-form-label form-label">GYM name <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-sm-vertical">
                                                <input type="text"
                                                    class="form-control @error('gymName') is-invalid @enderror"
                                                    name="gymName" id="gymName" placeholder="GYM name"
                                                    value="{{ old('gymName', Auth::user()->gym_name) }}">
                                                @error('gymName')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Owner Name -->
                                    <div class="row mb-4">
                                        <label for="ownerName" class="col-sm-3 col-form-label form-label">Owner name <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-sm-vertical">
                                                <input type="text"
                                                    class="form-control @error('ownerName') is-invalid @enderror"
                                                    name="ownerName" id="ownerName" placeholder="Owner name"
                                                    value="{{ old('ownerName', Auth::user()->owner_name) }}">
                                                @error('ownerName')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="row mb-4">
                                        <label for="emailLabel" class="col-sm-3 col-form-label form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                name="email" id="emailLabel" placeholder="Email"
                                                value="{{ old('email', Auth::user()->email) }}">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Mobile -->
                                    <div class="row mb-4">
                                        <label for="mobileLabel" class="col-sm-3 col-form-label form-label">Mobile <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control @error('mobile') is-invalid @enderror"
                                                name="mobile" id="mobileLabel" placeholder="Mobile"
                                                value="{{ old('mobile', Auth::user()->mobile) }}">
                                            @error('mobile')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Gym Address -->
                                    <div class="row mb-4">
                                        <label for="gymAddress" class="col-sm-3 col-form-label form-label">Gym
                                            address</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control @error('gymAddress') is-invalid @enderror" name="gymAddress" id="gymAddress"
                                                placeholder="Gym address">{{ old('gymAddress', Auth::user()->gym_address) }}</textarea>
                                            @error('gymAddress')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>

                                <!-- End Form -->
                            </div>
                            <!-- End Body -->
                        </div>
                        <!-- End Card -->


                        <!-- Card -->
                        <div id="passwordSection" class="card">
                            <div class="card-header">
                                <h4 class="card-title">Change your password</h4>
                            </div>

                            <!-- Body -->
                            <div class="card-body">
                                <!-- Form -->
                                <!-- Add Font Awesome for the eye icon -->
                                <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
                                    rel="stylesheet">

                                <form id="changePasswordForm" method="POST"
                                    action="{{ route('profile.changePassword') }}">
                                    @csrf

                                    <div class="row mb-4">
                                        <label for="currentPassword" class="col-sm-3 col-form-label form-label">Current
                                            password <span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="position-relative">
                                                <input type="password"
                                                    class="form-control pe-5 @error('currentPassword') is-invalid @enderror"
                                                    name="currentPassword" id="currentPassword"
                                                    placeholder="Enter current password">
                                                <i class="fa-solid fa-eye-slash  toggle-password position-absolute"
                                                    data-target="#currentPassword"
                                                    style="top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer;"></i>
                                            </div>
                                            @error('currentPassword')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label for="newPassword" class="col-sm-3 col-form-label form-label">New password
                                            <span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="position-relative">
                                                <input type="password"
                                                    class="form-control pe-5 @error('newPassword') is-invalid @enderror"
                                                    name="newPassword" id="newPassword" placeholder="Enter new password">
                                                <i class="fa-solid fa-eye-slash toggle-password position-absolute"
                                                    data-target="#newPassword"
                                                    style="top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer;"></i>
                                            </div>
                                            @error('newPassword')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label for="confirmNewPassword" class="col-sm-3 col-form-label form-label">Confirm
                                            new password <span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="position-relative">
                                                <input type="password"
                                                    class="form-control pe-5 @error('confirmNewPassword') is-invalid @enderror"
                                                    name="confirmNewPassword" id="confirmNewPassword"
                                                    placeholder="Confirm new password">
                                                <i class="fa-solid fa-eye-slash toggle-password position-absolute"
                                                    data-target="#confirmNewPassword"
                                                    style="top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer;"></i>
                                            </div>
                                            @error('confirmNewPassword')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>

                                <!-- End Form -->
                            </div>
                            <!-- End Body -->
                        </div>
                        <!-- End Card -->


                        <!-- Card -->
                        <!-- Delete Account Section -->
                        <div id="deleteAccountSection" class="card">
                            <div class="card-header">
                                <h4 class="card-title">Delete your account</h4>
                            </div>

                            <!-- Body -->
                            <div class="card-body">
                                <p class="card-text">When you delete your account, you lose access to GYM Manager services,
                                    and we permanently delete your personal data.</p>

                                <div class="mb-4">
                                    <!-- Form Check -->
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="deleteAccountCheckbox">
                                        <label class="form-check-label" for="deleteAccountCheckbox">
                                            Confirm that I want to delete my account.
                                        </label>
                                    </div>
                                    <!-- End Form Check -->
                                </div>

                                <div class="d-flex justify-content-end gap-3">
                                    <a class="btn btn-white" href="#">Learn more</a>
                                    <button type="button" id="deleteAccountBtn" class="btn btn-danger">Delete</button>
                                </div>
                            </div>
                            <!-- End Body -->
                        </div>

                        <!-- Bootstrap Modal for confirmation -->
                        <div class="modal fade" id="confirmDeleteModal" tabindex="-1"
                            aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Account Deletion</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete your account? This action cannot be undone!
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">No</button>
                                        <form id="deleteAccountForm" method="POST"
                                            action="{{ route('profile.deleteAccount') }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <!-- End Card -->
                    </div>

                    <!-- Sticky Block End Point -->
                    <div id="stickyBlockEndPoint"></div>
                </div>
            </div>
            <!-- End Row -->
        </div>
        <!-- End Content -->
    </main>
    <script>
        document.querySelectorAll('.toggle-password').forEach(icon => {
            icon.addEventListener('click', function() {
                const input = document.querySelector(this.getAttribute('data-target'));
                if (input.getAttribute('type') === 'password') {
                    input.setAttribute('type', 'text');
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                } else {
                    input.setAttribute('type', 'password');
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                }
            });
        });
        document.getElementById('deleteAccountBtn').addEventListener('click', function() {
            const checkbox = document.getElementById('deleteAccountCheckbox');
            if (!checkbox.checked) {
                alert('Please confirm that you want to delete your account by checking the box.');
                return;
            }

            // Show the confirmation modal
            const myModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            myModal.show();
        });
    </script>
@endsection
