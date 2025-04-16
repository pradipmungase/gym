$(document).ready(function () {
    $('#addPlanForm').on('submit', function (e) {
        e.preventDefault(); // prevent default form submission

        let form = $(this);
        let formData = form.serialize();
        let submitBtn = form.find('button[type="submit"]');
        let originalBtnHtml = submitBtn.html();

        // Clear old errors
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').text('');

        // Change button to loader
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');

        $.ajax({
            url: "/plans/store", // your Laravel route
            type: "POST",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                showToast(response.message, 'bg-success');
                form[0].reset();
                $('#addPlanModal').modal('hide');
                fetchPlans();
            },
            error: function (xhr) {
                let errors = xhr.responseJSON.errors;

                $.each(errors, function (field, messages) {
                    let input = $('[name="' + field + '"]');
                    input.addClass('is-invalid');
                    input.next('.invalid-feedback').text(messages[0]);
                });
            },
            complete: function () {
                // Revert button back to original
                submitBtn.prop('disabled', false).html(originalBtnHtml);
            }
        });
    });
});


$(document).ready(function () {
    $('#editPlanForm').on('submit', function (e) {
        e.preventDefault(); // prevent default form submission

        let form = $(this);
        let formData = form.serialize();
        let submitBtn = form.find('button[type="submit"]');
        let originalBtnHtml = submitBtn.html();

        // Clear old errors
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').text('');

        // Change button to loader
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');

        $.ajax({
            url: "/plans/update", // your Laravel route
            type: "POST",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                showToast(response.message, 'bg-success');
                form[0].reset();
                $('#editPlanModal').modal('hide');
                fetchPlans();
            },
            error: function (xhr) {
                let errors = xhr.responseJSON.errors;

                $.each(errors, function (field, messages) {
                    let input = $('[name="' + field + '"]');
                    input.addClass('is-invalid');
                    input.next('.invalid-feedback').text(messages[0]);
                });
            },
            complete: function () {
                // Revert button back to original
                submitBtn.prop('disabled', false).html(originalBtnHtml);
            }
        });
    });
});




$(document).on('click', '.edit-plan-btn', function () {
    const plan = $(this).data('plan'); // Make sure your button has data-trainer='{"id":1,...}'

    $('#editPlanId').val(plan.id);
    $('#editPlanName').val(plan.name);
    $('#editDuration').val(plan.duration);
    $('#editPrice').val(plan.price);

    $('#editPlanModal').modal('show');
});




function fetchPlans(page = 1) {
    $.ajax({
        url: "plans/fetch?page=" + page,
        type: 'GET',
        success: function (data) {
            $('#plans-table-container').html(data);
        },
        error: function () {
            $('#plans-table-container').html('<div class="text-danger text-center">Failed to load plans.</div>');
        }
    });
}


function fetchmembers(page = 1) {
    $.ajax({
        url: "members/fetch?page=" + page,
        type: 'GET',
        success: function (data) {
            $('#members-table-container').html(data);
        },
        error: function () {
            $('#members-table-container').html('<div class="text-danger text-center">Failed to load members.</div>');
        }
    });
}


function fetchTrainers(page = 1) {
    $.ajax({
        url: "trainer/fetch?page=" + page,
        type: 'GET',
        success: function (data) {
            $('#trainers-table-container').html(data);
        },
        error: function () {
            $('#trainers-table-container').html('<div class="text-danger text-center">Failed to load trainers.</div>');
        }
    });
}


function fetchAttendance(page = 1) {
    $.ajax({
        url: "attendance/fetch?page=" + page,
        type: 'GET',
        success: function (data) {
            $('#attendance-table-container').html(data);
        },
        error: function () {
            $('#attendance-table-container').html('<div class="text-danger text-center">Failed to load attendance.</div>');
        }
    }); 
}


if (window.location.pathname === '/plans') {
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        fetchPlans(page);
    });

    fetchPlans();
}
if (window.location.pathname === '/members') {
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        fetchmembers(page);
    });
    fetchmembers();
}

if (window.location.pathname === '/trainer') {
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        fetchTrainers(page);
    });
    fetchTrainers();
}

if (window.location.pathname === '/attendance') {
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        fetchAttendance(page);
    });
    fetchAttendance();
}



$(document).ready(function () {
    $('#addMemberForm').on('submit', function (e) {
        e.preventDefault();

        // Clear previous errors
        $('#addMemberForm .is-invalid').removeClass('is-invalid');
        $('#addMemberForm .invalid-feedback').hide().text('');

        const form = $(this);
        const formData = form.serialize();
        const submitBtn = form.find('button[type="submit"]');

        // Save original button text and show loading
        const originalBtnHtml = submitBtn.html();
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Submitting...');

        $.ajax({
            url: "/members/store", // Make sure this route is correct
            method: 'POST',
            data: formData,
            success: function (response) {
                if (response.status === 'success') {
                    $('#addMemberModal').modal('hide');
                    form[0].reset();
                    fetchmembers();
                    showToast(response.message, 'bg-success');
                } else {
                    showToast(response.message, 'bg-danger');
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, messages) {
                        const field = $(`#${key}`);
                        field.addClass('is-invalid');
                        field.siblings('.invalid-feedback').text(messages[0]).show();
                    });
                } else {
                    showToast('Something went wrong. Please try again.', 'bg-danger');
                }
            },
            complete: function () {
                // Re-enable button and reset its content
                submitBtn.prop('disabled', false).html(originalBtnHtml);
            }
        });
    });
});


if (window.location.pathname === '/members') {

    document.addEventListener("DOMContentLoaded", function () {
        const planSelect = document.getElementById('plan');
        const discountInput = document.getElementById('discount');
        const discountTypeSelect = document.getElementById('discount_type');
        const planPriceField = document.getElementById('plan_price');
        const finalPriceField = document.getElementById('final_price');
        const admissionFeeField = document.getElementById('admission_fee');
        const dueAmountField = document.getElementById('due_amount');

        function calculateFinalPrice() {
            const selectedOption = planSelect.options[planSelect.selectedIndex];
            const planPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            const discount = parseFloat(discountInput.value) || 0;
            const discountType = discountTypeSelect.value;
            const admissionFee = parseFloat(admissionFeeField.value) || 0;

            let finalPrice = planPrice;

            if (discount && discountType) {
                if (discountType === 'Percentage') {
                    finalPrice = planPrice - (planPrice * discount / 100);
                } else if (discountType === 'Flat') {
                    finalPrice = planPrice - discount;
                }
            }

            const dueAmount = finalPrice - admissionFee;

            planPriceField.value = planPrice.toFixed(2);
            finalPriceField.value = finalPrice > 0 ? finalPrice.toFixed(2) : '0.00';
            dueAmountField.value = dueAmount > 0 ? dueAmount.toFixed(2) : '0.00';
        }

        // Event listeners
        planSelect.addEventListener('change', calculateFinalPrice);
        discountInput.addEventListener('input', calculateFinalPrice);
        discountTypeSelect.addEventListener('change', calculateFinalPrice);
        admissionFeeField.addEventListener('input', calculateFinalPrice); // admission fee input change
    });


}



$(document).ready(function () {
    $('#addTrainerForm').on('submit', function (e) {
        e.preventDefault();

        // Clear previous errors
        $('#addTrainerForm .is-invalid').removeClass('is-invalid');
        $('#addTrainerForm .invalid-feedback').hide().text('');

        const form = $(this)[0];
        const formData = new FormData(form); // Use FormData instead of serialize()
        const submitBtn = $(this).find('button[type="submit"]');

        const originalBtnHtml = submitBtn.html();
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Submitting...');

        $.ajax({
            url: "/trainer/store", // Make sure this matches your Laravel route
            method: 'POST',
            data: formData,
            processData: false,   // Important for FormData
            contentType: false,   // Important for FormData
            success: function (response) {
                if (response.status === 'success') {
                    $('#addTrainerModal').modal('hide');
                    $('#addTrainerForm')[0].reset();
                    fetchTrainers();
                    showToast(response.message, 'bg-success');
                } else {
                    showToast(response.message || 'Submission failed.', 'bg-danger');
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, messages) {
                        const field = $(`[name="${key}"]`);
                        field.addClass('is-invalid');

                        // Show the invalid-feedback directly below the input
                        const feedback = field.closest('.form-group, .mb-3, .col-md-6').find('.invalid-feedback');
                        feedback.text(messages[0]).show();
                    });
                } else {
                    showToast('Something went wrong. Please try again.', 'bg-danger');
                }
            },
            complete: function () {
                submitBtn.prop('disabled', false).html(originalBtnHtml);
            }
        });
    });
});


$(document).on('click', '.btn-edit-trainer', function () {
    const trainer = $(this).data('trainer'); // Make sure your button has data-trainer='{"id":1,...}'

    $('#editTrainerId').val(trainer.id);
    $('#editTrainerName').val(trainer.name);
    $('#editTrainerEmail').val(trainer.email);
    $('#editTrainerPhone').val(trainer.phone);
    $('#editTrainerGender').val(trainer.gender);
    $('#editTrainerAddress').val(trainer.address);
    $('#editJoiningDate').val(trainer.joining_date);
    $('#editMonthlySalary').val(trainer.monthly_salary);

    $('#editTrainerModal').modal('show');
});



$(document).ready(function () {
    $('#editTrainerForm').on('submit', function (e) {
        e.preventDefault();

        // Clear previous errors
        $('#editTrainerForm .is-invalid').removeClass('is-invalid');
        $('#editTrainerForm .invalid-feedback').hide().text('');

        const form = $(this)[0];
        const formData = new FormData(form);
        const submitBtn = $(this).find('button[type="submit"]');

        const originalBtnHtml = submitBtn.html();
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Updating...');

        $.ajax({
            url: "/trainer/update", // Ensure this matches your Laravel update route
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status === 'success') {
                    $('#editTrainerModal').modal('hide');
                    $('#editTrainerForm')[0].reset();
                    fetchTrainers(); // Refresh the list
                    showToast(response.message, 'bg-success');
                } else {
                    showToast(response.message || 'Update failed.', 'bg-danger');
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, messages) {
                        const field = $(`#editTrainerForm [name="${key}"]`);
                        field.addClass('is-invalid');

                        // Show the invalid-feedback near the input
                        const feedback = field.closest('.form-group, .mb-3, .col-md-6').find('.invalid-feedback');
                        feedback.text(messages[0]).show();
                    });
                } else {
                    showToast('Something went wrong. Please try again.', 'bg-danger');
                }
            },
            complete: function () {
                submitBtn.prop('disabled', false).html(originalBtnHtml);
            }
        });
    });
});



$(document).ready(function () {
    $('#addExpenseForm').on('submit', function (e) {
        e.preventDefault(); // prevent default form submission

        let form = $(this);
        let formData = form.serialize();
        let submitBtn = form.find('button[type="submit"]');
        let originalBtnHtml = submitBtn.html();

        // Clear old errors
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').text('');

        // Show loader in button
        submitBtn.html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...').prop('disabled', true);

        $.ajax({
            url: "/expenses/store",
            type: "POST",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                showToast(response.message, 'bg-success');
                form[0].reset();
                $('#addExpenseModal').modal('hide');
                fetchExpenses();
            },
            error: function (xhr) {
                let errors = xhr.responseJSON.errors;
                $.each(errors, function (field, messages) {
                    let input = $('[name="' + field + '"]');
                    input.addClass('is-invalid');
                    input.next('.invalid-feedback').text(messages[0]);
                });
            },
            complete: function () {
                // Revert button after response
                submitBtn.html(originalBtnHtml).prop('disabled', false);
            }
        });
    });
});


function fetchExpenses(page = 1) {
    $.ajax({
        url: "expenses/fetch?page=" + page,
        type: 'GET',
        success: function (data) {
            $('#expenses-table-container').html(data);
        },
        error: function () {
            $('#expenses-table-container').html('<div class="text-danger text-center">Failed to load expenses.</div>');
        }
    });
}
 

if (window.location.pathname === '/expenses') {
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        fetchExpenses(page);
    }); 
    fetchExpenses();
}


$(document).on('click', '.edit-expense-btn', function () {
    const expense = $(this).data('expense'); // Make sure your button has data-trainer='{"id":1,...}'

    $('#editExpenseId').val(expense.id);
    $('#editName').val(expense.name);
    $('#editAmount').val(expense.amount);
    $('#editDate').val(expense.date);
    $('#editDescription').val(expense.description);

    $('#editExpenseModal').modal('show');
});

$(document).on('click', '.view-expense-btn', function () {
    const expense = $(this).data('expense'); // Make sure your button has data-trainer='{"id":1,...}'

    $('#viewExpenseId').val(expense.id);
    $('#viewName').val(expense.name);
    $('#viewAmount').val(expense.amount);
    $('#viewDate').val(expense.date);
    $('#viewDescription').val(expense.description);

    $('#viewExpenseModal').modal('show');
});


$(document).ready(function () {
    $('#editExpenseForm').on('submit', function (e) {
        e.preventDefault();


        let form = $(this);
        let formData = form.serialize();
        let submitBtn = form.find('button[type="submit"]');
        let originalBtnHtml = submitBtn.html();
        
        // Clear old errors
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').text('');

        // Show loader in button
        submitBtn.html('<span class="spinner-border spinner-border-sm me-1"></span> Updating...').prop('disabled', true);
        

        $.ajax({
            url: "/expenses/update",
            type: "POST",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                showToast(response.message, 'bg-success');
                form[0].reset();
                $('#editExpenseModal').modal('hide');
                fetchExpenses();
            },
            error: function (xhr) {
                let errors = xhr.responseJSON.errors;
                $.each(errors, function (field, messages) {
                    let input = $('[editName="' + field + '"]');
                    input.addClass('is-invalid');
                    input.next('.invalid-feedback').text(messages[0]);
                });
            },
            complete: function () {
                submitBtn.html(originalBtnHtml).prop('disabled', false);    
            }
        });
    });
});



$(document).ready(function () {

    $('#forgotPasswordForm').on('submit', function (e) {
        e.preventDefault();
        // Add your forgot password logic here
        let form = $(this);
        let formData = form.serialize();
        let submitBtn = form.find('button[type="submit"]');
        let originalBtnHtml = submitBtn.html();

        // Clear old errors 
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').text('');

        // Show loader in button
        submitBtn.html('<span class="spinner-border spinner-border-sm me-1"></span> Submitting...').prop('disabled', true); 
        
        $.ajax({
            url: "/forgotPassword",
            type: "POST",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },  
            success: function (response) {
                $('#verifyOtpModal').modal('show');
                showToast(response.message, 'bg-success');

                // Wait until modal is fully shown before starting countdown
                $('#verifyOtpModal').on('shown.bs.modal', function () {
                    startResendOtpCountdown();
                });
            },
            error: function (xhr) {
                showToast(xhr.responseJSON.message, 'bg-danger');
            },
            complete: function () {
                submitBtn.html(originalBtnHtml).prop('disabled', false);
            }
        }); 
    });
});



$(document).ready(function () {
    $('#resetPasswordForm').on('submit', function (e) {
        e.preventDefault();
        // Add your reset password logic here
        let form = $(this);
        let formData = form.serialize();
        let submitBtn = form.find('button[type="submit"]');
        let originalBtnHtml = submitBtn.html();

        // Clear old errors 
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').text('');

        // Show loader in button
        submitBtn.html('<span class="spinner-border spinner-border-sm me-1"></span> Submitting...').prop('disabled', true);

        $.ajax({
            url: "/resetPassword",
            type: "POST",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.status == 'success') {
                    showToast(response.message, 'bg-success');  
                    setTimeout(function() {
                        window.location.href = '/login';
                    }, 2000);
                } else {
                    showToast(response.message, 'bg-danger');
                }
            },
            error: function (xhr) {
                showToast(xhr.responseJSON.message, 'bg-danger');
            },
            complete: function () {
                submitBtn.html(originalBtnHtml).prop('disabled', false);    
            }
        });
    });
}); 


$(document).ready(function () {
    $('#addAnnouncementForm').on('submit', function (e) {
        e.preventDefault();

        let form = $(this);
        let formData = form.serialize();
        let submitBtn = form.find('button[type="submit"]');
        let originalBtnHtml = submitBtn.html();

        // Clear old errors
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').text('');

        // Show loader in button
        submitBtn.html('<span class="spinner-border spinner-border-sm me-1"></span> Submitting...').prop('disabled', true);

        $.ajax({
            url: "/announcement/store",
            type: "POST",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                showToast(response.message, 'bg-success');
                form[0].reset();
                $('#addAnnouncementModal').modal('hide');   
                fetchAnnouncement();
            },
            error: function (xhr) {
                let errors = xhr.responseJSON.errors;
                $.each(errors, function (field, messages) {
                    let input = $('[name="' + field + '"]');
                        input.addClass('is-invalid');
                    input.next('.invalid-feedback').text(messages[0]);
                });
            },
            complete: function () {
                submitBtn.html(originalBtnHtml).prop('disabled', false);
            }
        });
    });
});



function fetchAnnouncement(page = 1) {
    $.ajax({
        url: "announcement/fetch?page=" + page,
        type: 'GET',
        success: function (data) {
            $('#announcement-table-container').html(data);
        },
        error: function () {
            $('#announcement-table-container').html('<div class="text-danger text-center">Failed to load announcements.</div>');
        }
    });
}

if (window.location.pathname === '/announcement') {     
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        fetchAnnouncement(page);
    });
    fetchAnnouncement();
}

function resendOtp(){
    showToast("Resending OTP...", 'bg-info');

    $.ajax({
        url: "/resendOtp",
        type: "POST",
        data: { mobile: $('#mobile').val() },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {
            showToast(res.message, 'bg-success');
            startResendOtpCountdown(); // Restart countdown
        },
        error: function (xhr) {
            showToast(xhr.responseJSON.message, 'bg-danger');
        }
    });
}



function startResendOtpCountdown() {
    let countdown = 5;
    let $timer = $('#resendOtpTimer');
    let $resendBtn = $('#resendOtpBtn');

    // Reset UI
    $resendBtn.addClass('d-none').off('click');
    $timer.removeClass('d-none').text(`Resend OTP in ${countdown}s`);

    let interval = setInterval(function () {
        countdown--;
        $timer.text(`Resend OTP in ${countdown}s`);

        if (countdown <= 0) {
            clearInterval(interval);
            $timer.addClass('d-none');
            $resendBtn.removeClass('d-none');
        }
    }, 1000);
}




$(document).ready(function () {
    $('#verifyOtpForm').on('submit', function (e) {
        e.preventDefault();
        let form = $(this);
        let formData = form.serialize();
        let submitBtn = form.find('button[type="submit"]');
        let originalBtnHtml = submitBtn.html();

        // Convert button to loading state
        submitBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Verifying...')
            .prop('disabled', true);

        // Clear old errors
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').text('');

        $.ajax({
            url: "/verifyOtp",
            type: "POST",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.status == 'success') {
                    $('#otpErrorMsg').hide().text('');
                    $('#otp').removeClass('is-invalid');

                    showToast(response.message, 'bg-success');
                    setTimeout(function () {
                        window.location.href = response.link;
                    }, 2000);
                } else if (response.status == 'error') {
                    $('#otp').addClass('is-invalid');
                    $('#otpErrorMsg').text(response.message).show();
                    showToast(response.message, 'bg-danger');
                }
            },
            error: function (xhr) {
                $('#otp').addClass('is-invalid');
                $('#otpErrorMsg').text(xhr.responseJSON.message).show();
                showToast(xhr.responseJSON.message, 'bg-danger');
            },
            complete: function () {
                // Restore original button state
                submitBtn.html(originalBtnHtml).prop('disabled', false);
            }
        });
    });
});

