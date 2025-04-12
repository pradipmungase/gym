$(document).ready(function () {
    $('#addPlanForm').on('submit', function (e) {
        e.preventDefault(); // prevent default form submission

        let form = $(this);
        let formData = form.serialize();

        // Clear old errors
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').text('');

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
                fetchPlans()
            },
            error: function (xhr) {
                let errors = xhr.responseJSON.errors;

                $.each(errors, function (field, messages) {
                    let input = $('[name="' + field + '"]');
                    input.addClass('is-invalid');
                    input.next('.invalid-feedback').text(messages[0]);
                });
            }
        });
    });
});


$(document).ready(function () {
    $('#editPlanForm').on('submit', function (e) {
        e.preventDefault(); // prevent default form submission

        let form = $(this);
        let formData = form.serialize();

        // Clear old errors
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').text('');

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
                fetchPlans()
            },
            error: function (xhr) {
                let errors = xhr.responseJSON.errors;

                $.each(errors, function (field, messages) {
                    let input = $('[name="' + field + '"]');
                    input.addClass('is-invalid');
                    input.next('.invalid-feedback').text(messages[0]);
                });
            }
        });
    });
});




document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".edit-plan-btn").forEach(button => {
        button.addEventListener("click", function () {
            const plan = JSON.parse(this.getAttribute("data-plan"));

            // Set values in modal form
            document.getElementById("editPlanName").value = plan.name;
            document.getElementById("editDuration").value = plan.duration;

            document.getElementById("editPrice").value = plan.price;
            document.getElementById("editPlanId").value = plan.id;

            // Optional: update the form action if needed
            // document.getElementById("editPlanForm").action = `/plans/${plan.id}/update`;
        });
    });
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


function fetchMenbers(page = 1) {
    $.ajax({
        url: "menbers/fetch?page=" + page,
        type: 'GET',
        success: function (data) {
            $('#menbers-table-container').html(data);
        },
        error: function () {
            $('#menbers-table-container').html('<div class="text-danger text-center">Failed to load menbers.</div>');
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
if (window.location.pathname === '/menbers') {
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        fetchMenbers(page);
    });
    fetchMenbers();
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
            url: "/menbers/store", // Make sure this route is correct
            method: 'POST',
            data: formData,
            success: function (response) {
                if (response.status === 'success') {
                    $('#addMemberModal').modal('hide');
                    form[0].reset();
                    fetchMenbers();
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




document.addEventListener("DOMContentLoaded", function () {
    const planSelect = document.getElementById('plan');
    const discountInput = document.getElementById('discount');
    const discountTypeSelect = document.getElementById('discount_type');
    const planPriceField = document.getElementById('plan_price');
    const finalPriceField = document.getElementById('final_price');

    function calculateFinalPrice() {
        const selectedOption = planSelect.options[planSelect.selectedIndex];
        const planPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const discount = parseFloat(discountInput.value) || 0;
        const discountType = discountTypeSelect.value;

        let finalPrice = planPrice;

        if (discount && discountType) {
            if (discountType === 'Percentage') {
                finalPrice = planPrice - (planPrice * discount / 100);
            } else if (discountType === 'Flat') {
                finalPrice = planPrice - discount;
            }
        }

        planPriceField.value = planPrice.toFixed(2);
        finalPriceField.value = finalPrice > 0 ? finalPrice.toFixed(2) : 0;
    }

    // Event listeners
    planSelect.addEventListener('change', calculateFinalPrice);
    discountInput.addEventListener('input', calculateFinalPrice);
    discountTypeSelect.addEventListener('change', calculateFinalPrice);
});