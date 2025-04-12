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

// Load initial data
fetchPlans();

// Handle pagination click
$(document).on('click', '.pagination a', function (e) {
    e.preventDefault();
    let page = $(this).attr('href').split('page=')[1];
    fetchPlans(page);
});