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


// function fetchmembers(page = 1) {
//     $.ajax({
//         url: "members/fetch?page=" + page,
//         type: 'GET',
//         success: function (data) {
//             $('#members-table-container').html(data);
//         },
//         error: function () {
//             $('#members-table-container').html('<div class="text-danger text-center">Failed to load members.</div>');
//         }
//     });
// }


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
                    }else{
                        showToast(response.message, 'bg-success');
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
                        // Capitalize first letter of the key
                        const capitalizedKey = key.charAt(0).toUpperCase() + key.slice(1);
                        const field = $(`#edit${capitalizedKey}`);

                        // If field is readonly, allow error display
                        field.addClass('is-invalid');
                        field.siblings('.invalid-feedback').text(messages[0]).show();

                        // Focus first error field
                        if (!firstErrorField) {
                            firstErrorField = field;
                        }
                    });

                    // Set focus after all error messages are applied
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
                if (discountType === 'Percentage') {
                    finalPrice = planPrice - (planPrice * discount / 100);
                } else if (discountType === 'Flat') {
                    finalPrice = planPrice - discount;
                }
            }

            const dueAmount = finalPrice - admissionFee;

            planPriceField.value = planPrice.toFixed(2);
            finalPriceField.value = finalPrice > 0 ? finalPrice.toFixed(2) : finalPrice.toFixed(2);
            dueAmountField.value = dueAmount > 0 ? dueAmount.toFixed(2) : dueAmount.toFixed(2);
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
                if (discountType === 'Percentage') {
                    finalPrice = planPrice - (planPrice * discount / 100);
                } else if (discountType === 'Flat') {
                    finalPrice = planPrice - discount;
                }
            }

            const dueAmount = finalPrice - admissionFee;

            planPriceField.value = planPrice.toFixed(2);
            finalPriceField.value = finalPrice > 0 ? finalPrice.toFixed(2) : finalPrice.toFixed(2);
            dueAmountField.value = dueAmount > 0 ? dueAmount.toFixed(2) : dueAmount.toFixed(2);
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
    $('#editJoiningDate').val(members.joining_date);

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
    $('#addPaymentDueAmount').val(members.due_amount);
    $('#currentDueAmount').val(members.due_amount);
    $('#currentPlanId').val(members.plan_id);
    $('#addPaymentModal').modal('show');
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

                    // Set focus on first invalid field
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
    var status = document.querySelector(`[data-member-id="${memberId}"]`).checked ? 'Active' : 'Inactive';

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
