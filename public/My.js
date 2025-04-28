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
    $('#editDurationType').val(plan.duration_type);
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


function fetchmembers(page = 1, query = '', genders = [], status = '') {
    $.ajax({
        url: "members/fetch",
        type: 'GET',
        data: {
            page: page,
            query: query,
            genders: genders,
            status: status
        },
        success: function (data) {
            $('#members-table-container').html(data);
        },
        error: function () {
            $('#members-table-container').html('<div class="text-danger text-center">Failed to load members.</div>');
        }
    });
}


$('#searchMember').on('keyup', function () {
    let query = $(this).val();
    fetchmembers(1, query);
});


$('#applyFilters').on('click', function () {
    let query = $('#searchMember').val();
    let genders = [];

    // Gather checked gender checkboxes
    $('input[name="gender"]:checked').each(function () {
        genders.push($(this).val());
    });

    let status = $('#filterStatus').val();
    $('#memberFilterDropdown').click();

    fetchmembers(1, query, genders, status);
});

$('.filterCloseBtn').on('click', function () {
    $('#memberFilterDropdown').click();
});




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

        const form = $(this)[0]; // Get the DOM element
        const formData = new FormData(form); // Use FormData to include files
        const submitBtn = $(this).find('button[type="submit"]');

        // Save original button text and show loading
        const originalBtnHtml = submitBtn.html();
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Submitting...');

        $.ajax({
            url: "/members/store", // Ensure this matches your route
            method: 'POST',
            data: formData,
            contentType: false, // Required for FormData
            processData: false, // Required for FormData
            success: function (response) {
                if (response.status === 'success') {
                    $('#addMemberModal').modal('hide');
                    $('#addMemberForm')[0].reset();
                    $('.js-file-attach-reset-img').click();
                    fetchmembers();
                    showToast(response.message, 'bg-success');
                } else {
                    if (response.expiry_date == 'expiry_date') {
                        $('#joining_date').addClass('is-invalid');
                        $('#joining_date').siblings('.invalid-feedback').text(response.message).show();
                    } else {
                        showToast(response.message, 'bg-danger');
                    }
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let firstErrorField = null;

                    $.each(errors, function (key, messages) {
                        const field = $(`#${key}`);
                        field.addClass('is-invalid');
                        field.siblings('.invalid-feedback').text(messages[0]).show();

                        // Store the first invalid field to focus
                        if (!firstErrorField) {
                            firstErrorField = field;
                        }
                    });

                    // Focus the first error field
                    if (firstErrorField) {
                        firstErrorField.focus();
                    }
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
    $('#editmemberForm').on('submit', function (e) {
        e.preventDefault();

        // Clear previous errors
        $('#editmemberForm .is-invalid').removeClass('is-invalid');
        $('#editmemberForm .invalid-feedback').hide().text('');

        const form = $(this)[0]; // Get the DOM element
        const formData = new FormData(form); // Use FormData to include files
        const submitBtn = $(this).find('button[type="submit"]');

        // Save original button text and show loading
        const originalBtnHtml = submitBtn.html();
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Submitting...');

        $.ajax({
            url: "/members/update", // Ensure this matches your route
            method: 'POST',
            data: formData,
            contentType: false, // Required for FormData
            processData: false, // Required for FormData
            success: function (response) {
                if (response.status === 'success') {
                    $('#editmemberModal').modal('hide');
                    $('#editmemberForm')[0].reset();
                    fetchmembers();
                    showToast(response.message, 'bg-success');
                } else {
                    if (response.expiry_date == 'expiry_date') {
                        $('#editJoiningDate').addClass('is-invalid');
                        $('#editJoiningDate').siblings('.invalid-feedback').text(response.message).show();
                    } else {
                        showToast(response.message, 'bg-success');
                    }
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;

                    // Clear previous validation states
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('').hide();

                    let firstErrorField = null;

                    $.each(errors, function (key, messages) {
                        // Convert snake_case to camelCase and capitalize first letter
                        const capitalizedKey = key.replace(/_([a-z])/g, g => g[1].toUpperCase());
                        const field = $(`#edit${capitalizedKey.charAt(0).toUpperCase() + capitalizedKey.slice(1)}`);

                        field.addClass('is-invalid');
                        field.siblings('.invalid-feedback').text(messages[0]).show();

                        if (!firstErrorField) {
                            firstErrorField = field;
                        }
                    });

                    if (firstErrorField) {
                        firstErrorField.focus();
                    }
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
                if (discountType === 'percentage') {
                    finalPrice = planPrice - (planPrice * discount / 100);
                } else if (discountType === 'flat') {
                    finalPrice = planPrice - discount;
                }
            }

            const dueAmount = finalPrice - admissionFee;

            planPriceField.value = planPrice.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            finalPriceField.value = finalPrice > 0 ? finalPrice.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : finalPrice.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            dueAmountField.value = dueAmount > 0 ? dueAmount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : finalPrice.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        }

        // Event listeners
        planSelect.addEventListener('change', calculateFinalPrice);
        discountInput.addEventListener('input', calculateFinalPrice);
        discountTypeSelect.addEventListener('change', calculateFinalPrice);
        admissionFeeField.addEventListener('input', calculateFinalPrice);
    });



    document.addEventListener("DOMContentLoaded", function () {
        const planSelect = document.getElementById('editPlan');
        const discountInput = document.getElementById('editDiscount');
        const discountTypeSelect = document.getElementById('editDiscountType');
        const planPriceField = document.getElementById('editPlanPrice');
        const finalPriceField = document.getElementById('editFinal_price');
        const admissionFeeField = document.getElementById('editAdmissionFee');
        const dueAmountField = document.getElementById('editDue_amount');

        function calculateFinalPriceEdit() {
            const selectedOption = planSelect.options[planSelect.selectedIndex];
            const planPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            const discount = parseFloat(discountInput.value) || 0;
            const discountType = discountTypeSelect.value;
            const admissionFee = parseFloat(admissionFeeField.value) || 0;

            let finalPrice = planPrice;

            if (discount && discountType) {
                if (discountType === 'percentage') {
                    finalPrice = planPrice - (planPrice * discount / 100);
                } else if (discountType === 'flat') {
                    finalPrice = planPrice - discount;
                }
            }

            const dueAmount = finalPrice - admissionFee;

            planPriceField.value = planPrice.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            finalPriceField.value = finalPrice > 0 ? finalPrice.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : finalPrice.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            dueAmountField.value = dueAmount > 0 ? dueAmount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : finalPrice.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        // Trigger calculation once on page load (for edit)
        calculateFinalPriceEdit();

        // Event listeners for dynamic updates
        planSelect.addEventListener('change', calculateFinalPriceEdit);
        discountInput.addEventListener('input', calculateFinalPriceEdit);
        discountTypeSelect.addEventListener('change', calculateFinalPriceEdit);
        admissionFeeField.addEventListener('input', calculateFinalPriceEdit);
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
                    $('.js-file-attach-reset-img').click();
                    fetchTrainers();
                    showToast(response.message, 'bg-success');
                } else {
                    showToast(response.message || 'Submission failed.', 'bg-danger');
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;

                    // Clear previous validation states
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').text('').hide();

                    let firstErrorField = null;

                    $.each(errors, function (key, messages) {
                        const field = $(`[name="${key}"]`);
                        field.addClass('is-invalid');

                        // Show the invalid-feedback directly below the input
                        const feedback = field.closest('.form-group, .mb-3, .col-md-6').find('.invalid-feedback');
                        feedback.text(messages[0]).show();

                        if (!firstErrorField) {
                            firstErrorField = field;
                        }
                    });

                    // Focus the first field with an error
                    if (firstErrorField) {
                        firstErrorField.focus();
                    }

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
    $('#editDateOfBirth').val(trainer.birth_date);

    const fallbackImg = "/assets/img/160x160/images (1).jpg";
    if (trainer.image) {
        $('#previewEditTrainerImg').attr('src', trainer.image);
    } else {
        $('#previewEditTrainerImg').attr('src', fallbackImg);
    }

    $('#editTrainerModal').modal('show');
});


$('#editMemberImg').on('change', function (event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            $('#previewMemberImg').attr('src', e.target.result);
        };

        reader.readAsDataURL(file);
    }
});


$('#menberImg').on('change', function (event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            $('#previewMemberImgAdd').attr('src', e.target.result);
        };

        reader.readAsDataURL(file);
    }
});

$(document).on('click', '.js-file-attach-reset-img', function () {
    // Reset image to default
    $('#previewMemberImg').attr('src', 'assets/img/160x160/images (1).jpg');
    $('#previewEditTrainerImg').attr('src', 'assets/img/160x160/images (1).jpg');
    // Clear the file input
    $('#avatarUploader').val('');
});


$(document).on('click', '.edit-member-btn', function () {
    const members = $(this).data('member'); // Make sure this is a valid JS object

    // Populate form fields
    $('#editMembersId').val(members.member_id);
    $('#editName').val(members.name);
    $('#editEmail').val(members.email);
    $('#editMobile').val(members.mobile);
    $('#editBirthDate').val(members.birth_date);
    const fallbackImg = "/assets/img/160x160/images (1).jpg";
    if (members.image) {
        $('#previewMemberImg').attr('src', members.image);
    } else {
        $('#previewMemberImg').attr('src', fallbackImg);
    }
    $('#editGender').val($.trim(members.gender));
    $('#editJoiningDate').val(members.start_date);

    $('#editBatch').val($.trim(members.batch));
    $('#editTrainer').val($.trim(members.trainer_id));
    $('#editPlan').val(members.plan_id).trigger('change'); // In case you have JS bound on change
    $('#editPaymentMode').val(members.payment_mode);
    $('#editAdmissionFee').val(members.admission_fee);
    $('#editDiscountType').val(members.discount_type);
    $('#editDiscount').val(members.discount_inpute);
    $('#editPlanPrice').val(members.plan_price);
    $('#editFinalPrice').val(members.after_discount_price);
    $('#editDueAmount').val(members.due_amount);

    $('#editmemberModal').modal('show');
});


$(document).on('click', '.add-payment-btn', function () {
    const members = $(this).data('member'); // Make sure this is a valid JS object

    // Populate form fields
    $('#addPaymentMemberId').val(members.member_id);
    $('#addPaymentMember').val(members.name);
    $('#addPaymentDueAmount').val(members.due_amount ? members.due_amount : members.final_price);
    $('#currentDueAmount').val(members.due_amount ? members.due_amount : members.final_price);
    $('#currentPlanId').val(members.plan_id);
    if (members.due_amount != 0) {
        $('#addPaymentModal').modal('show');
    } else {
        $('#paymentStatusModal').modal('show');
    }
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

                    // Clear previous validation states within the form
                    $('#editTrainerForm .is-invalid').removeClass('is-invalid');
                    $('#editTrainerForm .invalid-feedback').text('').hide();

                    let firstErrorField = null;

                    $.each(errors, function (key, messages) {
                        const field = $(`#editTrainerForm [name="${key}"]`);
                        field.addClass('is-invalid');

                        // Show the invalid-feedback near the input
                        const feedback = field.closest('.form-group, .mb-3, .col-md-6').find('.invalid-feedback');
                        feedback.text(messages[0]).show();

                        // Set focus on the first invalid field
                        if (!firstErrorField) {
                            firstErrorField = field;
                        }
                    });

                    if (firstErrorField) {
                        firstErrorField.focus();
                    }

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
                    setTimeout(function () {
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

function resendOtp() {
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
    let countdown = 60;
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

// Shared logic for both cover and avatar upload
function handleProfileImageUpload(file) {
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            // Update both previews
            $('#profileCoverImg').attr('src', e.target.result);
            $('#editAvatarImgModal').attr('src', e.target.result);

            const formData = new FormData();
            formData.append('profile_picture', file); // Single field used

            $.ajax({
                url: '/updateProfilePicture',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    showToast(response.message, 'bg-success');
                },
                error: function (xhr) {
                    const res = xhr.responseJSON;
                    const message = res?.message || 'An error occurred while uploading the image.';
                    showToast(message, 'bg-danger');
                }
            });
        };
        reader.readAsDataURL(file);
    }
}

// Bind to both inputs
$('#profileCoverUplaoder, #editAvatarUploaderModal').on('change', function (e) {
    const file = e.target.files[0];
    handleProfileImageUpload(file);
});


$(document).ready(function () {
    let $input = $('.js-form-search');
    let $resultsBox = $('#searchDropdownMenu');
    let $resultsBody = $resultsBox.find('.card-body-height');
    let loaderHtml = `<div class="text-center my-4"><div class="spinner-border text-primary" role="status"></div></div>`;

    // Search input
    $input.on('input', function () {
        let keyword = $(this).val().trim();
        $resultsBody.html('');
        if (keyword.length < 2) {
            $resultsBody.html('');
            return;
        }

        $resultsBody.html(loaderHtml);

        $.ajax({
            url: "/search",
            type: "POST",
            data: { keyword: keyword },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                let html = '';

                // Trainers Section
                if (response.trainers && response.trainers.length) {
                    html += `<span class="dropdown-header">Trainers</span>`;
                    response.trainers.forEach(trainer => {
                        html += `
              <a class="dropdown-item" href="/trainer/view/${trainer.encrypted_id}">
                <div class="d-flex align-items-center">
                  <span class="icon icon-soft-dark icon-xs icon-circle me-2">
                    <i class="bi-person-badge"></i>
                  </span>
                  <div class="flex-grow-1 text-truncate">
                    <span>${trainer.name} (${trainer.phone})</span>
                  </div>
                </div>
              </a>`;
                    });
                    html += `<div class="dropdown-divider"></div>`;
                }

                // Members Section
                if (response.members && response.members.length) {
                    html += `<span class="dropdown-header">Members</span>`;
                    response.members.forEach(member => {
                        const imageSrc = member.image ? `${member.image}` : '/assets/img/160x160/images (1).jpg';
                        html += `
                <a class="dropdown-item" href="/members/view/${member.encrypted_id}">
                    <div class="d-flex align-items-center">
                    <img class="avatar avatar-xs avatar-circle me-2" src="${imageSrc}" alt="">
                    <div class="flex-grow-1 text-truncate">
                        <span>${member.name} (${member.mobile})</span>
                    </div>
                    </div>
                </a>`;
                    });
                }

                if (!response.trainers?.length && !response.members?.length) {
                    html += `<div class="text-center py-4"><em>No results found.</em></div>`;
                }

                $resultsBody.html(html);
            },
            error: function () {
                $resultsBody.html(`<div class="text-danger text-center my-3">Search failed. Please try again.</div>`);
            }
        });
    });

    // Show/hide clear icon
    $input.on('input', function () {
        $('#clearSearchResultsIcon').toggle($(this).val().length > 0);
    });

    $('#clearSearchResultsIcon').on('click', function () {
        $input.val('').trigger('input');
        $(this).hide();
    });

});


$(document).ready(function () {
    let $input = $('.mobileViewSearch');
    let $resultsBody = $('#mobileViewSearchResults');
    // let $resultsBody = $resultsBox.find('.card-body-height');
    let loaderHtml = `<div class="text-center my-4"><div class="spinner-border text-primary" role="status"></div></div>`;

    // Search input
    $input.on('input', function () {
        let keyword = $(this).val().trim();
        $resultsBody.html('');
        $('.searchesDisplayHere').html('');
        if (keyword.length < 2) {
            $resultsBody.html('');
            return;
        }

        $resultsBody.html(loaderHtml);

        $.ajax({
            url: "/search",
            type: "POST",
            data: { keyword: keyword },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                let html = '';

                // Trainers Section
                if (response.trainers && response.trainers.length) {
                    html += `<span class="dropdown-header">Trainers</span>`;
                    response.trainers.forEach(trainer => {
                        html += `
              <a class="dropdown-item" href="/trainer/view/${trainer.encrypted_id}">
                <div class="d-flex align-items-center">
                  <span class="icon icon-soft-dark icon-xs icon-circle me-2">
                    <i class="bi-person-badge"></i>
                  </span>
                  <div class="flex-grow-1 text-truncate">
                    <span>${trainer.name} (${trainer.phone})</span>
                  </div>
                </div>
              </a>`;
                    });
                    html += `<div class="dropdown-divider"></div>`;
                }

                // Members Section
                if (response.members && response.members.length) {
                    html += `<span class="dropdown-header">Members</span>`;
                    response.members.forEach(member => {
                        const imageSrc = member.image ? `${member.image}` : '/assets/img/160x160/images (1).jpg';
                        html += `
                <a class="dropdown-item" href="/members/view/${member.encrypted_id}">
                    <div class="d-flex align-items-center">
                    <img class="avatar avatar-xs avatar-circle me-2" src="${imageSrc}" alt="">
                    <div class="flex-grow-1 text-truncate">
                        <span>${member.name} (${member.mobile})</span>
                    </div>
                    </div>
                </a>`;
                    });
                }

                if (!response.trainers?.length && !response.members?.length) {
                    html += `<div class="text-center py-4"><em>No results found.</em></div>`;
                }
                $resultsBody.html(html);
            },
            error: function () {
                $resultsBody.html(`<div class="text-danger text-center my-3">Search failed. Please try again.</div>`);
            }
        });
    });


});


function deleteMember(memberId) {
    if (confirm('Are you sure you want to delete this member?')) {
        $.ajax({
            url: `/members/delete/${memberId}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                showToast(response.message, 'bg-success');
                fetchmembers();
            },
            error: function (xhr) {
                showToast(xhr.responseJSON.message, 'bg-danger');
            }
        });
    }
}


if (window.location.pathname === '/members') {
    $(document).ready(function () {
        const amountInput = $('#addPaymentAmount');

        amountInput.on('input', function () {
            const enteredAmount = parseFloat($(this).val()) || 0;
            const dueAmountInput = $('#addPaymentDueAmount');
            const currentDueAmount = parseFloat($('#currentDueAmount').val()) || 0;
            let newDueAmount = currentDueAmount - enteredAmount;
            dueAmountInput.val(newDueAmount.toFixed(2));
        });
    });
}




$(document).ready(function () {
    $('#addPaymentForm').on('submit', function (e) {
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
            url: "/members/addPayment", // your Laravel route
            type: "POST",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.status == 'success') {
                    showToast(response.message, 'bg-success');
                    form[0].reset();
                    $('#addPaymentModal').modal('hide');
                    fetchmembers();
                } else {
                    showToast(response.message, 'bg-danger');
                }
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

$(window).on('load', function () {
    $('#loader-wrapper').fadeOut('slow');
});

$(document).on('submit', '.register-form', function (e) {
    e.preventDefault();

    // Clear previous validation errors
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').hide().text('');

    // Disable button and show loader
    let $btn = $('#signupBtn');
    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Processing...');

    $.ajax({
        url: '/register',
        type: 'POST',
        data: {
            gym_name: $('#gymName').val(),
            owner_name: $('#ownerName').val(),
            mobile: $('#mobile').val(),
            password: $('#password').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (response.status === 'success') {
                window.location.href = '/dashboard';
            } else {
                showToast(response.message, 'bg-danger');
                $btn.prop('disabled', false).html('<i class="bi bi-check-circle me-1"></i> Sign Up');
            }
        },
        error: function (xhr) {
            // Re-enable button in any case
            $btn.prop('disabled', false).html('<i class="bi bi-check-circle me-1"></i> Sign Up');

            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;

                // Clear previous validation
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('').hide();

                let firstErrorField = null;

                $.each(errors, function (key, messages) {
                    const field = $(`[name="${key}"]`);
                    field.addClass('is-invalid');

                    // Display error
                    field.siblings('.invalid-feedback').text(messages[0]).show();

                    // If password field error, adjust eye icon position
                    if (key === 'password') {
                        $('#togglePassword').css('margin-top', '-10px');
                    } else {
                        $('#togglePassword').css('margin-top', '0px');
                    }

                    // Set focus on first invalid field
                    if (!firstErrorField) {
                        firstErrorField = field;
                    }
                });

                if (firstErrorField) {
                    firstErrorField.focus();
                }
            }
        },
    });
});


$('.clearFromDataWithError').on('click', function () {
    resetAllModals();
});


function resetAllModals() {
    // Close all open modals
    $('.modal').modal('hide');

    // Clear form inputs and errors inside each modal
    $('.modal').each(function () {
        const modal = $(this);

        // Reset forms
        modal.find('form').trigger('reset');

        // Remove validation error classes
        modal.find('.is-invalid').removeClass('is-invalid');
        modal.find('.invalid-feedback').text('').hide();

        // Optional: Clear file input previews or custom fields if needed
        modal.find('input[type="file"]').val('');
        modal.find('select').val('').trigger('change'); // for select2
        modal.find('textarea').val('');
        $('.js-file-attach-reset-img').click();
    });
}



function updateUserStatus(memberId) {
    var status = document.querySelector(`[data-member-id="${memberId}"]`).checked ? 'active' : 'inactive';

    // Send an AJAX request to update the user's status
    $.ajax({
        url: '/members/updateStatus', // Make sure this is the correct route
        type: 'POST',
        data: {
            member_id: memberId,
            status: status
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
        },
        error: function (xhr, status, error) {
            showToast(xhr.responseJSON.message, 'bg-danger');
        }
    });
}

$(document).on('submit', '#addNoteForm', function (e) {
    e.preventDefault();

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
        url: "/members/addNote",
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            showToast(response.message, 'bg-success');
            form[0].reset();
            $('#addNoteModel').modal('hide');
            fetchmembers();
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
            submitBtn.prop('disabled', false).html(originalBtnHtml);
        }
    });
});

$(document).on('click', '.add-note-btn', function () {
    let member = $(this).data('member');
    $('#addNoteMemberId').val(member.member_id);
    $('#addNote').val(member.note);
    $('#addNoteModel').modal('show');
});

$(document).on('click', '.change-plan-btn', function () {
    let member = $(this).data('member');
    $('#changePlanMemberId').val(member.member_id);
    $('#changePlan').val(member.plan_id);
    $('#changePlan option').prop('disabled', false);
    $('#changePlan option:selected').prop('disabled', true);
    $('#changeCurrentPlanPrice').val(formatNumber(member.final_price));


    let dueAmount = member.due_amount ? member.due_amount : member.final_price;
    let paidAmount = member.final_price - dueAmount;

    // Set formatted values
    $('#changeCurrentPlanDueAmount').val(formatNumber(dueAmount));
    $('#changeCurrentPlanPaidAmount').val(formatNumber(paidAmount));


    $('#changePlanPaymentMode').val(member.payment_mode);
    $('#changePlanBatch').val(member.batch);
    $('#changePlanTrainer').val(member.trainer_id);

    $('#changeNewPlanPrice').val(0);
    $('#changeNewPlanPriceAfterDiscount').val(0);
    $('#changeNewPlanDueAmount').val(0);
    $('#changePlanJoiningDate').val(member.start_date);
    // $('#changePlanDiscountType').val(member.discount_type);
    // $('#changePlanDiscount').val(parseInt(member.discount_value));

    // $('#changePlanAdmissionFee').val(paidAmount);
    $('#memberMembershipsId').val(member.member_memberships_id);
    $('#changePlanPaymentMode').val(member.payment_mode);


    $('#paymentInfo').css('pointer-events', 'none');
    $('#paymentInfo').css('opacity', '0.6');

    $('#changePlanModel').modal('show');
});




function formatNumber(amount) {
    return parseFloat(amount).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}



if (window.location.pathname === '/members') {

    document.addEventListener("DOMContentLoaded", function () {
        const planSelect = document.getElementById('changePlan');
        const newPlanPriceField = document.getElementById('changeNewPlanPrice');
        const newPlanPriceAfterDiscountField = document.getElementById('changeNewPlanPriceAfterDiscount');
        const newDueAmountField = document.getElementById('changeNewPlanDueAmount');
        const newDueAmountForValidation = document.getElementById('newDueAmountForValidation');

        function formatINR(value) {
            return value.toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function calculateNewPlan() {
            const paymentInfo = document.getElementById('paymentInfo');
            paymentInfo.style.pointerEvents = 'auto';
            paymentInfo.style.opacity = '1';

            const selectedOption = planSelect.options[planSelect.selectedIndex];
            let planPrice = parseFloat(selectedOption?.getAttribute('data-price')) || 0;
            const originalPlanPrice = planPrice;

            const currentPlanPrice = parseFloat($('#changeCurrentPlanPrice').val().replace(/,/g, '')) || 0;
            const currentPlanDueAmount = parseFloat($('#changeCurrentPlanDueAmount').val().replace(/,/g, '')) || 0;

            const admissionFee = parseFloat($('#changePlanAdmissionFee').val()) || 0;
            const discount = parseFloat($('#changePlanDiscount').val()) || 0;
            const discountType = $('#changePlanDiscountType').val();

            // Apply discount
            if (discountType === 'flat') {
                planPrice = planPrice - discount;
            } else if (discountType === 'percentage') {
                planPrice = planPrice - (planPrice * discount / 100);
            }


            // Calculate paid amount and new due amount
            const currentPaidAmount = currentPlanPrice - currentPlanDueAmount;
            let newDueAmount = planPrice - currentPaidAmount - admissionFee;

            // Save raw due amount for validations
            newDueAmountForValidation.value = newDueAmount;

            // Display values
            newPlanPriceField.value = formatINR(originalPlanPrice);
            newPlanPriceAfterDiscountField.value = formatINR(planPrice);
            // newDueAmountField.value = formatINR(Math.abs(newDueAmount));
            newDueAmountField.value = (newDueAmount < 0 ? '+' : '') + formatINR(Math.abs(newDueAmount));

            if (parseFloat(newDueAmount) < 0) {
                $('#changeNewPlanDueAmount').addClass('is-invalid');
                $('#changeNewPlanDueAmount').removeClass('text-danger');
                $('#changeNewPlanDueAmount').addClass('text-success');
                $('#changeNewPlanDueAmount').next('.invalid-feedback').text('Member already paid more than due amount.');
                $('#changeNewPlanDueAmount').next('.invalid-feedback').show();
            } else {
                $('#changeNewPlanDueAmount').removeClass('is-invalid');
                $('#changeNewPlanDueAmount').addClass('text-danger');
                $('#changeNewPlanDueAmount').next('.invalid-feedback').text('');
                $('#changeNewPlanDueAmount').next('.invalid-feedback').hide();
            }


        }

        // Attach event listeners
        planSelect.addEventListener('change', calculateNewPlan);
        $('#changePlanAdmissionFee, #changePlanDiscount').on('keyup', calculateNewPlan);
        $('#changePlanDiscountType').on('change', calculateNewPlan);
    });

}




$(document).on('submit', '#changePlanForm', function (e) {
    e.preventDefault();

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
        url: "/members/changePlan",
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (response.status === 'success') {
                $('#changePlanModel').modal('hide');
                $('#changePlanForm')[0].reset();
                fetchmembers();
                showToast(response.message, 'bg-success');
            } else {
                if (response.expiry_date == 'expiry_date') {
                    $('#changePlanJoiningDate').addClass('is-invalid');
                    $('#changePlanJoiningDate').siblings('.invalid-feedback').text(response.message).show();
                } else {
                    showToast(response.message, 'bg-danger');
                }
            }
        },
        error: function (xhr) {
            let errors = xhr.responseJSON.errors;
            $.each(errors, function (field, messages) {
                if (field == 'newDueAmountForValidation') {
                    // Target the new_due_amount field's invalid-feedback div
                    $('#changeNewPlanDueAmount').addClass('is-invalid');
                    $('#changeNewPlanDueAmount').next('.invalid-feedback').text(messages[0]);
                } else {
                    // Default behavior for other fields
                    let input = $('[name="' + field + '"]');
                    input.addClass('is-invalid');
                    input.next('.invalid-feedback').text(messages[0]);
                }
            });
        },
        complete: function () {
            submitBtn.prop('disabled', false).html(originalBtnHtml);
        }
    });
});



$(document).on('click', '.renew-membership-btn', function () {
    let member = $(this).data('member');
    $('#renewMembershipMemberId').val(member.member_id);
    // $('#renewMembershipPlan').val(member.plan_id);
    const originalDate = member.end_date; // e.g., "2025-04-16"
    const dateObj = new Date(originalDate);

    // Format: 16 Apr, 2025
    const options = { day: '2-digit', month: 'short', year: 'numeric' };
    const formattedDate = dateObj.toLocaleDateString('en-GB', options).replace(/ /g, ' ');

    $('#currentPlanExpiryDate').val(formattedDate);

    $('#paymentInfoForRenewMembership').css('pointer-events', 'none');
    $('#paymentInfoForRenewMembership').css('opacity', '0.6');

    if (member.due_amount != 0) {
        $('#renewMembershipPaymentNotReceivedModal').modal('show');
    } else {
        $('#renewMembershipModal').modal('show');
    }
});



if (window.location.pathname === '/members') {

    document.addEventListener("DOMContentLoaded", function () {
        const planSelect = document.getElementById('renewMembershipPlan');
        const newPlanPriceInput = document.getElementById('renewMembershipNewPlanPrice');
        const afterDiscountInput = document.getElementById('renewMembershipNewPlanPriceAfterDiscount');
        const dueAmountInput = document.getElementById('renewMembershipNewPlanDueAmount');

        const discountTypeSelect = document.getElementById('renewMembershipDiscountType');
        const discountInput = document.getElementById('renewMembershipDiscount');
        const paymentAmountInput = document.getElementById('renewMembershipAdmissionFee');

        const newPlanExpiryInput = document.getElementById('newPlanExpiryDate');
        const currentExpiryInput = document.getElementById('currentPlanExpiryDate');
        const paymentInfoDiv = document.getElementById('paymentInfoForRenewMembership');

        function formatINR(value) {
            return Number(value).toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function calculateNewPlan() {
            const selectedOption = planSelect.options[planSelect.selectedIndex];
            const duration = parseInt(selectedOption.getAttribute('data-duration'));
            const durationType = selectedOption.getAttribute('data-duration_type');
            const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;

            // Show Plan Price
            newPlanPriceInput.value = formatINR(price);
            afterDiscountInput.value = formatINR(price);
            dueAmountInput.value = formatINR(price);

            // Show payment section
            paymentInfoDiv.style.pointerEvents = 'auto';
            paymentInfoDiv.style.opacity = '1';

            // Expiry Date Calculation
            let startDateStr = currentExpiryInput.value;
            let startDate = startDateStr ? new Date(startDateStr) : new Date();
            if (isNaN(startDate.getTime())) startDate = new Date();

            let newExpiry = new Date(startDate);
            if (durationType === 'days') {
                newExpiry.setDate(newExpiry.getDate() + duration);
            } else if (durationType === 'months') {
                newExpiry.setMonth(newExpiry.getMonth() + duration);
            } else if (durationType === 'years') {
                newExpiry.setFullYear(newExpiry.getFullYear() + duration);
            }

            const options = { day: '2-digit', month: 'short', year: 'numeric' };
            newPlanExpiryInput.value = newExpiry.toLocaleDateString('en-GB', options).replace(/,/g, '');

            calculateDiscountAndDue();
        }

        function calculateDiscountAndDue() {
            const planPrice = parseFloat(planSelect.options[planSelect.selectedIndex].getAttribute('data-price')) || 0;
            const discountType = discountTypeSelect.value;
            const discount = parseFloat(discountInput.value) || 0;
            const paymentAmount = parseFloat(paymentAmountInput.value) || 0;

            let afterDiscount = planPrice;

            if (discountType === 'flat') {
                afterDiscount = planPrice - discount;
            } else if (discountType === 'percentage') {
                afterDiscount = planPrice - (planPrice * (discount / 100));
            }

            // if (afterDiscount < 0) afterDiscount = 0;

            let dueAmount = afterDiscount - paymentAmount;
            // if (dueAmount < 0) dueAmount = 0;

            afterDiscountInput.value = formatINR(afterDiscount);
            dueAmountInput.value = formatINR(dueAmount);

            // Hidden input for validation
            document.getElementById('renewMembershipNewDueAmountForValidation').value = dueAmount;
        }

        // Attach listeners
        planSelect.addEventListener('change', calculateNewPlan);
        discountTypeSelect.addEventListener('change', calculateDiscountAndDue);
        discountInput.addEventListener('input', calculateDiscountAndDue);
        paymentAmountInput.addEventListener('input', calculateDiscountAndDue);
    });
}



$(document).on('submit', '#renewMembershipForm', function (e) {
    e.preventDefault();

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
        url: "/members/renewMembership",
        type: "POST",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            showToast(response.message, 'bg-success');
            form[0].reset();
            $('#renewMembershipModal').modal('hide');
            fetchmembers();
        },
        error: function (xhr) {
            let errors = xhr.responseJSON.errors;
            $.each(errors, function (field, messages) {
                if (field == 'renewMembershipNewDueAmountForValidation') {
                    // Target the new_due_amount field's invalid-feedback div
                    $('#renewMembershipNewPlanDueAmount').addClass('is-invalid');
                    $('#renewMembershipNewPlanDueAmount').next('.invalid-feedback').text(messages[0]);
                } else {
                    // Default behavior for other fields
                    let input = $('[name="' + field + '"]');
                    input.addClass('is-invalid');
                    input.next('.invalid-feedback').text(messages[0]);
                }
            });
        },
        complete: function () {
            submitBtn.prop('disabled', false).html(originalBtnHtml);
        }
    });
});


$(document).ready(function () {
    $('#changePlan').on('change', function () {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').hide();
    });
});



$(document).ready(function () {
    // When Plan changes
    $('#registration_plan').on('change', function () {
        var selectedPrice = $('option:selected', this).data('price') || 0;

        $('#registration_plan_price').val(formatNumber(selectedPrice));
        $('#registration_final_price').val(formatNumber(selectedPrice));
        $('#registration_due_amount').val(formatNumber(selectedPrice));
    });

    // When Joining Amount changes
    $('#registration_admission_fee').on('input', function () {
        sanitizeAndCalculate();
    });

    function sanitizeAndCalculate() {
        var admissionFeeInput = $('#registration_admission_fee').val();

        // Remove non-numeric except dot
        var sanitizedInput = admissionFeeInput.replace(/[^0-9.]/g, '');

        // Update sanitized value back to input
        $('#registration_admission_fee').val(sanitizedInput);

        calculateDueAmount();
    }

    function calculateDueAmount() {
        var finalPrice = parseFloat($('#registration_final_price').val().replace(/,/g, '')) || 0;
        var admissionFee = parseFloat($('#registration_admission_fee').val().replace(/,/g, '')) || 0;
        var dueAmount = finalPrice - admissionFee;

        // Make sure dueAmount is never negative
        dueAmount = dueAmount < 0 ? 0 : dueAmount;

        $('#registration_due_amount').val(formatNumber(dueAmount));
        $('#memberRequestFinalPrice').val(formatNumber(admissionFee));

    }

    function formatNumber(number) {
        return Number(number).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }
});





$(document).ready(function () {
    // Remove previous submit handler if any, then attach a new one
    $(document).off('submit', '#memberRegstrationForm').on('submit', '#memberRegstrationForm', function (e) {
        e.preventDefault();

        // Clear previous errors
        $('#memberRegstrationForm .is-invalid').removeClass('is-invalid');
        $('#memberRegstrationForm .invalid-feedback').hide().text('');

        const form = this; // No need for $(this)[0]
        const formData = new FormData(form);
        const submitBtn = $(form).find('button[type="submit"]');

        // Save original button text and show loading
        const originalBtnHtml = submitBtn.html();
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Submitting...');

        $.ajax({
            url: "/memberRegistration/store/" + $('#gymId').val(),
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.status === 'success') {
                    $('#memberRegstration').modal('hide');
                    form.reset();
                    $('.js-file-attach-reset-img').click();
                    showToast(response.message, 'bg-success');
                } else {
                    if (response.expiry_date === 'expiry_date') {
                        $('#registration_joining_date').addClass('is-invalid');
                        $('#registration_joining_date').siblings('.invalid-feedback').text(response.message).show();
                    } else {
                        showToast(response.message, 'bg-danger');
                    }
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let firstErrorField = null;

                    $.each(errors, function (key, messages) {
                        const field = $(`#${key}`);
                        field.addClass('is-invalid');
                        field.siblings('.invalid-feedback').text(messages[0]).show();

                        if (!firstErrorField) {
                            firstErrorField = field;
                        }
                    });

                    if (firstErrorField) {
                        firstErrorField.focus();
                    }
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




function fetchmembersRequest(page = 1, query = '', genders = [], status = '') {
    $.ajax({
        url: "memberRequest/fetch",
        type: 'GET',
        data: {
            page: page,
            query: query,
            genders: genders,
            status: status
        },
        success: function (data) {
            $('#membersRequest-table-container').html(data);
        },
        error: function () {
            $('#membersRequest-table-container').html('<div class="text-danger text-center">Failed to load members.</div>');
        }
    });
}


$('#searchMemberRequest').on('keyup', function () {
    let query = $(this).val();
    fetchmembersRequest(1, query);
});


if (window.location.pathname === '/memberRequest') {
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        fetchmembersRequest(page);
    });
    fetchmembersRequest();
}



$(document).on('click', '.view-member-btn', function () {
    const members = $(this).data('member'); // Make sure this is a valid JS object

    // Populate form fields
    $('#MembersRequestId').val(members.member_id);
    $('#memberRequestName').val(members.name);
    $('#memberRequestEmail').val(members.email);
    $('#memberRequestMobile').val(members.mobile_number);
    $('#memberRequestBirthDate').val(members.birth_date);
    const fallbackImg = "/assets/img/160x160/images (1).jpg";
    if (members.image) {
        $('#previewMemberImg').attr('src', members.image);
    } else {
        $('#previewMemberImg').attr('src', fallbackImg);
    }
    $('#memberRequestGender').val($.trim(members.gender));
    $('#memberRequestJoiningDate').val(members.joining_date);

    $('#memberRequestBatch').val($.trim(members.batch));
    $('#memberRequestTrainer').val($.trim(members.trainer_id));
    $('#memberRequestPlan').val(members.plan_id).trigger('change'); // In case you have JS bound on change
    $('#memberRequestPaymentMode').val(members.payment_mode);
    $('#memberRequestAdmissionFee').val(members.admission_fee);
    $('#memberRequestDiscountType').val(members.discount_type);
    $('#memberRequestDiscount').val(members.discount_input);
    $('#memberRequestPlanPrice').val(members.plan_price);
    $('#memberRequestFinalPrice').val(members.final_price_after_discount);
    $('#memberRequestDueAmount').val(members.due_amount);

    $('#viewmemberModal').modal('show');
});



$(document).ready(function () {
    // When Plan changes
    $('#memberRequestPlan').on('change', function () {
        var selectedPrice = $('option:selected', this).data('price') || 0;

        $('#memberRequestPlanPrice').val(formatNumber(selectedPrice));
        $('#memberRequestFinalPrice').val(formatNumber(selectedPrice));
        $('#memberRequestDueAmount').val(formatNumber(selectedPrice));

        calculateDueAmount();
    });

    // When Discount Type or Discount Amount changes
    $('#memberRequestDiscountType, #memberRequestDiscount').on('input change', function () {
        calculateDueAmount();
    });

    // When Joining Amount changes
    $('#memberRequestAdmissionFee').on('input', function () {
        sanitizeJoiningAmount();
        calculateDueAmount();
    });

    function sanitizeJoiningAmount() {
        var inputVal = $('#memberRequestAdmissionFee').val();
        var sanitized = inputVal.replace(/[^0-9.]/g, '');
        $('#memberRequestAdmissionFee').val(sanitized);
    }

    function calculateDueAmount() {
        var planPrice = parseFloat($('#memberRequestPlanPrice').val().replace(/,/g, '')) || 0;
        var discountType = $('#memberRequestDiscountType').val();
        var discountValue = parseFloat($('#memberRequestDiscount').val()) || 0;
        var admissionFee = parseFloat($('#memberRequestAdmissionFee').val().replace(/,/g, '')) || 0;

        var finalPrice = planPrice;

        // Apply Discount
        if (discountType && discountValue) {
            if (discountType === 'flat') {
                finalPrice = planPrice - discountValue;
            } else if (discountType === 'percentage') {
                finalPrice = planPrice - (planPrice * (discountValue / 100));
            }
        }

        // Make sure finalPrice is never negative
        finalPrice = finalPrice < 0 ? 0 : finalPrice;

        $('#memberRequestFinalPrice').val(formatNumber(finalPrice));

        // Now calculate Due Amount
        if (admissionFee > finalPrice) {
            admissionFee = finalPrice;
            $('#memberRequestAdmissionFee').val(formatNumber(admissionFee));
        }

        var dueAmount = finalPrice - admissionFee;

        // Never negative
        dueAmount = dueAmount < 0 ? 0 : dueAmount;

        $('#memberRequestDueAmount').val(formatNumber(dueAmount));
    }

    function formatNumber(number) {
        return Number(number).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }
});





$('#rejectMemberRequestBtn').on('click', function (e) {
    e.preventDefault();  // Prevent the default form submit

    const memberId = $('#MembersRequestId').val();
    const originalBtnHtml = $(this).html();  // Get the HTML of the clicked button
    $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Submitting...');  // Disable and change text

    $.ajax({
        url: "memberRequest/reject/" + memberId,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $('#viewmemberModal').modal('hide');
            showToast(response.message, 'bg-success');
            fetchmembersRequest();
        },
        error: function () {
            showToast('Something went wrong. Please try again.', 'bg-danger');
        },
        complete: function () {
            // Re-enable the button and restore the original button text
            $('#rejectMemberRequestBtn').prop('disabled', false).html(originalBtnHtml);
        }
    });
});





$('#acceptMemberRequestBtn').on('click', function (e) {
    e.preventDefault();

    const originalBtnHtml = $(this).html();
    $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Submitting...');

    // ✨ Clear previous errors
    $('#viewmemberForm .is-invalid').removeClass('is-invalid');
    $('#viewmemberForm .invalid-feedback').hide().text('');

    const formData = new FormData($('#viewmemberForm')[0]);

    $.ajax({
        url: "/members/store",
        method: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            if (response.status === 'success') {
                $('#viewmemberModal').modal('hide');
                $('#viewmemberForm')[0].reset();
                $('.js-file-attach-reset-img').click();
                fetchmembersRequest();
                showToast(response.message, 'bg-success');
            } else {
                if (response.expiry_date == 'expiry_date') {
                    $('#memberRequestJoiningDate').addClass('is-invalid');
                    $('#memberRequestJoiningDate').siblings('.invalid-feedback').text(response.message).show();
                } else {
                    showToast(response.message, 'bg-danger');
                }
            }
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                let firstErrorField = null;

                const errorFieldMapping = {
                    birth_date: 'memberRequestBirthDate',
                    gender: 'memberRequestGender',
                    plan_price: 'memberRequestPlanPrice',
                    final_price: 'memberRequestFinalPrice',
                    due_amount: 'memberRequestDueAmount',
                    admission_fee: 'memberRequestAdmissionFee',
                    payment_mode: 'memberRequestPaymentMode',
                    discount: 'memberRequestDiscount',
                    discount_type: 'memberRequestDiscountType',
                    name: 'memberRequestName',
                    email: 'memberRequestEmail',
                    mobile: 'memberRequestMobile',
                    batch: 'memberRequestBatch',
                    trainer: 'memberRequestTrainer',
                    joining_date: 'memberRequestJoiningDate',
                    menberImg: 'memberRequestMenberImg',
                    plan: 'memberRequestPlan',
                    paymentMode: 'memberRequestPaymentMode',
                };

                $.each(errors, function (key, messages) {
                    const fieldId = errorFieldMapping[key] || key;
                    const field = $(`#${fieldId}`);

                    field.addClass('is-invalid');
                    field.siblings('.invalid-feedback').text(messages[0]).show();

                    if (!firstErrorField) {
                        firstErrorField = field;
                    }
                });

                if (firstErrorField) {
                    firstErrorField.focus();
                }
            } else {
                showToast('Something went wrong. Please try again.', 'bg-danger');
            }
        },
        complete: function () {
            $('#acceptMemberRequestBtn').prop('disabled', false).html(originalBtnHtml);
        }
    });
});

