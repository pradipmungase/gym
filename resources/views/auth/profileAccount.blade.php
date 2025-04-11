@extends('admin.layout.adminApp')
@section('content')
    <main id="content" role="main" class="main">
        <!-- Content -->
        <div class="content container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-grid gap-3 gap-lg-5">
                        <!-- Display success message -->
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Display error message -->
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <!-- Card -->
                        <div id="passwordSection" class="card">
                            <div class="card-header">
                                <h4 class="card-title">Change your password</h4>
                            </div>

                            <!-- Body -->
                            <div class="card-body">
                                <!-- Form -->
                                <form method="POST" id="changePasswordForm" action="{{ route('profileAccount') }}">
                                    @csrf
                                    <!-- Current Password -->
                                    <div class="row mb-4">
                                        <label for="currentPasswordLabel" class="col-sm-3 col-form-label form-label">Current
                                            password</label>
                                        <div class="col-sm-9">
                                            <input type="password"
                                                class="form-control @error('currentPassword') is-invalid @enderror" required
                                                name="currentPassword" id="currentPasswordLabel"
                                                placeholder="Enter current password" aria-label="Enter current password"
                                                oninput="checkPasswordStrength(this, 'currentPasswordStrengthMessage')">
                                            <div id="currentPasswordStrengthMessage" class="mt-2"></div>
                                            @error('currentPassword')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>


                                    <!-- New Password -->
                                    <div class="row mb-4">
                                        <label for="newPassword" class="col-sm-3 col-form-label form-label">New
                                            password</label>
                                        <div class="col-sm-9">
                                            <input type="password"
                                                class="form-control @error('newPassword') is-invalid @enderror" required
                                                name="newPassword" id="newPassword" placeholder="Enter new password"
                                                aria-label="Enter new password"
                                                oninput="checkPasswordStrength(this, 'newPasswordStrengthMessage')">
                                            <div id="newPasswordStrengthMessage" class="mt-2"></div>
                                            @error('newPassword')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Confirm New Password -->
                                    <div class="row mb-4">
                                        <label for="confirmNewPasswordLabel"
                                            class="col-sm-3 col-form-label form-label">Confirm new password</label>
                                        <div class="col-sm-9">
                                            <input type="password"
                                                class="form-control @error('confirmNewPassword') is-invalid @enderror"
                                                required name="confirmNewPassword" id="confirmNewPassword"
                                                placeholder="Confirm your new password"
                                                aria-label="Confirm your new password"
                                                oninput="checkPasswordStrength(this, 'confirmPasswordStrengthMessage')">
                                            <div id="confirmPasswordStrengthMessage" class="mt-2"></div>
                                            @error('confirmNewPassword')
                                                <div class="invalid-feedback">{{ $message }}</div>
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
                    </div>
                    <!-- Sticky Block End Point -->
                </div>
            </div>
            <!-- End Row -->
        </div>
        <!-- End Content -->
    </main>
    <script>
        function checkPasswordStrength(inputElement, targetElementId) {
            const strengthMessage = document.getElementById(targetElementId);
            let strength = '';
            let color = '';
            let borderColor = '';

            if (inputElement.value.length < 6) {
                strength = 'Too short';
                color = 'red';
                borderColor = 'red';
            } else {
                const hasUpperCase = /[A-Z]/.test(inputElement.value);
                const hasLowerCase = /[a-z]/.test(inputElement.value);
                const hasNumbers = /[0-9]/.test(inputElement.value);
                const hasSpecialChars = /[!@#$%^&*(),.?":{}|<>]/.test(inputElement.value);
                const conditionsMet = [hasUpperCase, hasLowerCase, hasNumbers, hasSpecialChars].filter(Boolean).length;

                if (conditionsMet < 2) {
                    strength = 'Weak';
                    color = 'orange';
                    borderColor = 'orange';
                } else if (conditionsMet === 2 || conditionsMet === 3) {
                    strength = 'Medium';
                    color = 'blue';
                    borderColor = 'blue';
                } else if (conditionsMet === 4) {
                    strength = 'Strong';
                    color = 'green';
                    borderColor = 'green';
                }
            }

            // Update the message
            strengthMessage.textContent = strength;
            strengthMessage.style.color = color;

            // Update the border color
            inputElement.style.borderColor = borderColor;
        }
    </script>
@endsection
