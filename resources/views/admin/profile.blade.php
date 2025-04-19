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
                  <img id="profileCoverImg" class="profile-cover-img" src="@if(Auth::user()->profile_picture){{ asset(Auth::user()->profile_picture) }}@else{{ asset('assets/img/1920x400/img2.jpg') }}@endif" alt="Image Description">

                  <!-- Custom File Cover -->
                  <div class="profile-cover-content profile-cover-uploader p-3">
                    <input type="file" accept="image/*" class="js-file-attach profile-cover-uploader-input" id="profileCoverUplaoder" data-hs-file-attach-options='{
                                "textTarget": "#profileCoverImg",
                                "mode": "image",
                                "targetAttr": "src",
                                "allowTypes": [".png", ".jpeg", ".jpg"]
                             }'>
                    <label class="profile-cover-uploader-label btn btn-sm btn-white" for="profileCoverUplaoder">
                      <i class="bi-camera-fill"></i>
                      <span class="d-none d-sm-inline-block ms-1">Upload header</span>
                    </label>
                  </div>
                  <!-- End Custom File Cover -->
                </div>
              </div>
              <!-- End Profile Cover -->

              <!-- Avatar -->
              <label class="avatar avatar-xxl avatar-circle avatar-uploader profile-cover-avatar" for="editAvatarUploaderModal">
                <img id="editAvatarImgModal" class="avatar-img" src="@if(Auth::user()->profile_picture){{ asset(Auth::user()->profile_picture) }}@else{{ asset('assets/img/160x160/images (1).jpg') }}@endif" alt="Image Description">

                <input type="file" accept="image/*" class="js-file-attach avatar-uploader-input" id="editAvatarUploaderModal" data-hs-file-attach-options='{
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
                <form>
                  <!-- Form -->
                  <div class="row mb-4">
                    <label for="gymName" class="col-sm-3 col-form-label form-label">GYM name</label>
                    <div class="col-sm-9">
                      <div class="input-group input-group-sm-vertical">
                        <input type="text" class="form-control" name="gymName" id="gymName" placeholder="GYM name" aria-label="GYM name" value="{{ Auth::user()->gym_name }}">
                      </div>
                    </div>
                  </div>
                  <!-- End Form -->

                  <!-- Form -->
                  <div class="row mb-4">
                    <label for="ownerName" class="col-sm-3 col-form-label form-label">Owner name</label>
                    <div class="col-sm-9">
                      <div class="input-group input-group-sm-vertical">
                        <input type="text" class="form-control" name="ownerName" id="ownerName" placeholder="Owner name" aria-label="Owner name" value="{{ Auth::user()->owner_name }}">
                      </div>
                    </div>
                  </div>
                  <!-- End Form -->

                  <!-- Form -->
                  <div class="row mb-4">
                    <label for="emailLabel" class="col-sm-3 col-form-label form-label">Email</label>

                    <div class="col-sm-9">
                      <input type="email" class="form-control" name="email" id="emailLabel" placeholder="Email" aria-label="Email" value="{{ Auth::user()->email }}">
                    </div>
                  </div>
                  <!-- End Form -->

                  <!-- Form -->
                  <div class="row mb-4">
                    <label for="mobileLabel" class="col-sm-3 col-form-label form-label">Gym address <span class="form-label-secondary"></span></label>

                    <div class="col-sm-9">
                      <textarea class="form-control" name="gymAddress" id="gymAddress" placeholder="Gym address" aria-label="Gym address" value="{{ Auth::user()->gym_address }}"></textarea>
                    </div>
                  </div>
                  <!-- End Form -->
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
            <div id="emailSection" class="card">
              <div class="card-header">
                <h4 class="card-title">Mobile number</h4>
              </div>

              <!-- Body -->
              <div class="card-body">
                <p>Your current mobile number is <span class="fw-semibold">{{ Auth::user()->mobile }}</span></p>

                <!-- Form -->
                <form>
                  <!-- Form -->
                  <div class="row mb-4">
                    <label for="newMobileLabel" class="col-sm-3 col-form-label form-label">New mobile number</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" name="newMobile" id="newMobileLabel" placeholder="Enter new mobile number" aria-label="Enter new mobile number">
                    </div>
                  </div>
                  <!-- End Form -->

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
                <form id="changePasswordForm">
                  <!-- Form -->
                  <div class="row mb-4">
                    <label for="currentPasswordLabel" class="col-sm-3 col-form-label form-label">Current password</label>

                    <div class="col-sm-9">
                      <input type="password" class="form-control" name="currentPassword" id="currentPasswordLabel" placeholder="Enter current password" aria-label="Enter current password">
                    </div>
                  </div>
                  <!-- End Form -->

                  <!-- Form -->
                  <div class="row mb-4">
                    <label for="newPassword" class="col-sm-3 col-form-label form-label">New password</label>

                    <div class="col-sm-9">
                      <input type="password" class="form-control" name="newPassword" id="newPassword" placeholder="Enter new password" aria-label="Enter new password">
                    </div>
                  </div>
                  <!-- End Form -->

                  <!-- Form -->
                  <div class="row mb-4">
                    <label for="confirmNewPasswordLabel" class="col-sm-3 col-form-label form-label">Confirm new password</label>

                    <div class="col-sm-9">
                      <div class="mb-3">
                        <input type="password" class="form-control" name="confirmNewPassword" id="confirmNewPasswordLabel" placeholder="Confirm your new password" aria-label="Confirm your new password">
                      </div>

                      <h5>Password requirements:</h5>

                      <p class="fs-6 mb-2">Ensure that these requirements are met:</p>

                      <ul class="fs-6">
                        <li>Minimum 8 characters long - the more, the better</li>
                        <li>At least one lowercase character</li>
                        <li>At least one uppercase character</li>
                        <li>At least one number, symbol, or whitespace character</li>
                      </ul>
                    </div>
                  </div>
                  <!-- End Form -->

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
            <div id="deleteAccountSection" class="card">
              <div class="card-header">
                <h4 class="card-title">Delete your account</h4>
              </div>

              <!-- Body -->
              <div class="card-body">
                <p class="card-text">When you delete your account, you lose access to Front account services, and we permanently delete your personal data. You can cancel the deletion for 14 days.</p>

                <div class="mb-4">
                  <!-- Form Check -->
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="deleteAccountCheckbox">
                    <label class="form-check-label" for="deleteAccountCheckbox">
                      Confirm that I want to delete my account.
                    </label>
                  </div>
                  <!-- End Form Check -->
                </div>

                <div class="d-flex justify-content-end gap-3">
                  <a class="btn btn-white" href="#">Learn more</a>
                  <button type="submit" class="btn btn-danger">Delete</button>
                </div>
              </div>
              <!-- End Body -->
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
@endsection
