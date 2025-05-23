@extends('admin.layout.adminApp')
@section('content')
    <main id="content" role="main" class="main">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center justify-content-between mb-4">
                    <div class="col-6">
                        <h1 class="page-header-title mb-0">Expenses Details</h1>
                    </div>
                    <div class="col-6 text-end">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
                            <i class="bi bi-plus-circle me-1"></i> &nbsp; Add
                        </button>
                    </div>
                </div>
            </div>
            <div id="expenses-table-container" class="text-center my-4 table-responsive datatable-custom">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2">Loading...</p>
            </div>

        </div>
    </main>

    <div class="modal fade" id="addExpenseModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="addExpenseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="addExpenseForm" method="POST" action="#">
                <div class="modal-content">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="addExpenseModalLabel">
                            <i class="bi bi-plus-circle me-2"></i> Add New Expense
                        </h5>
                        <button type="button" class="clearFromDataWithError btn-close btn-close-white"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body px-4">
                        <div class="row g-3">

                            <!-- Full Name -->
                            <div class="col-md-6">
                                <label for="name" class="form-label">
                                    Expense Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="name" name="name" required
                                    placeholder="e.g., Expense Name">
                                <div class="invalid-feedback">Expense name is required.</div>
                            </div>


                            <!-- Mobile No -->
                            <div class="col-md-6">
                                <label for="amount" class="form-label">
                                    Amount <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" id="amount" name="amount" required
                                    placeholder="e.g., 1000">
                                <div class="invalid-feedback">Amount is required.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="date" class="form-label">
                                    Date <span class="text-danger">*</span>
                                </label>
                                <input type="text" required class="js-flatpickr form-control flatpickr-custom" placeholder="Date" id="date" name="date" data-hs-flatpickr-options='{"dateFormat": "d/m/Y"}'>
                                <div class="invalid-feedback">Date is required.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="description" class="form-label">
                                    Description <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" id="description" name="description" required placeholder="e.g., Description"></textarea>
                                <div class="invalid-feedback">Description is required.</div>
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

    <div class="modal fade" id="editExpenseModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="editExpenseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="editExpenseForm" method="POST" action="#">
                <div class="modal-content">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="editExpenseModalLabel">
                            <i class="bi bi-pencil-square me-2"></i> Edit Expense
                        </h5>
                        <button type="button" class="clearFromDataWithError btn-close btn-close-white"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body px-4">
                        <div class="row g-3">
                            <input type="hidden" name="expense_id" id="editExpenseId">

                            <!-- Full Name -->
                            <div class="col-md-6">
                                <label for="editName" class="form-label">
                                    Expense Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="editName" name="name" required
                                    placeholder="e.g., Expense Name">
                                <div class="invalid-feedback">Expense name is required.</div>
                            </div>

                            <!-- Amount -->
                            <div class="col-md-6">
                                <label for="editAmount" class="form-label">
                                    Amount <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" id="editAmount" name="amount" required
                                    placeholder="e.g., 1000">
                                <div class="invalid-feedback">Amount is required.</div>
                            </div>

                            <!-- Date -->
                            <div class="col-md-6">
                                <label for="editDate" class="form-label">
                                    Date <span class="text-danger">*</span>
                                </label>
                                <input type="text" required class="js-flatpickr form-control flatpickr-custom" placeholder="Date" id="editDate" name="date" data-hs-flatpickr-options='{"dateFormat": "d/m/Y"}'>
                                <div class="invalid-feedback">Date is required.</div>
                            </div>

                            <!-- Description -->
                            <div class="col-md-6">
                                <label for="editDescription" class="form-label">
                                    Description
                                </label>
                                <textarea class="form-control" id="editDescription" name="description" placeholder="e.g., Description"></textarea>
                                <div class="invalid-feedback">Description is required.</div>
                            </div>


                        </div>
                    </div>

                    <div class="modal-footer px-4">
                        <button type="submit" id="editExpenseBtn" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> Update
                        </button>
                        <button type="button" class="clearFromDataWithError btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="viewExpenseModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="viewExpenseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="viewExpenseForm" method="POST" action="#">
                <div class="modal-content">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="viewExpenseModalLabel">
                            <i class="bi bi-eye me-2"></i> View Expense
                        </h5>
                        <button type="button" class="clearFromDataWithError btn-close btn-close-white"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body px-4">
                        <div class="row g-3">
                            <input type="hidden" name="expense_id" id="editExpenseId">

                            <!-- Full Name -->
                            <div class="col-md-6">
                                <label for="viewName" class="form-label">
                                    Expense Name <span class="text-danger">*</span>
                                </label>
                                <input readonly type="text" class="form-control" id="viewName" name="viewName"
                                    required placeholder="e.g., Expense Name">
                            </div>

                            <div class="col-md-6">
                                <label for="duration" class="form-label">
                                    Amount <span class="text-danger">*</span>
                                </label>
                                <input readonly type="number" class="form-control" id="viewAmount" name="amount"
                                    required placeholder="e.g., 1000">
                            </div>


                            <!-- Mobile No -->
                            <div class="col-md-6">
                                <label for="date" class="form-label">
                                    Date <span class="text-danger">*</span>
                                </label>
                                <input readonly type="text" required class="js-flatpickr form-control flatpickr-custom" placeholder="Date" id="viewDate" name="date" data-hs-flatpickr-options='{"dateFormat": "d/m/Y"}'>
                            </div>

                            <div class="col-md-6">
                                <label for="description" class="form-label">
                                    Description <span class="text-danger">*</span>
                                </label>
                                <textarea readonly class="form-control" id="viewDescription" name="description" required placeholder="e.g., Description"></textarea>
                                <div class="invalid-feedback">Description is required.</div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection
