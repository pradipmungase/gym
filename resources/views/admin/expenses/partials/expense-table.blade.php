<table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
    <thead class="thead-light">
        <tr>
            <th class="table-column-ps-0">#</th>
            <th class="table-column-ps-0">Expense Name</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($expenses as $expense)
            <tr>
                <td class="table-column-ps-0">{{ $expenses->firstItem() + $loop->index }}</td>
                <td>{{ $expense->name }}</td>
                <td class="text-warning">₹ {{ number_format($expense->amount, 2) }}</td>
                <td>{{ \Carbon\Carbon::parse($expense->date)->format('d M, Y') }}</td>
                <td>{{ $expense->description }}</td>
                <td>
                    <div>
                        <div class="dropdown">
                            <button class="btn btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Options
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item edit-expense-btn" href="#" data-bs-toggle="modal"
                                        data-bs-target="#editExpenseModal"
                                        data-expense='@json($expense)'>
                                        <i class="bi bi-pencil me-2"></i> Edit
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item view-expense-btn" href="#" data-bs-toggle="modal"
                                        data-bs-target="#viewExpenseModal"
                                        data-expense='@json($expense)'>
                                        <i class="bi bi-eye me-2"></i> View Details
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">
                    <div class="d-flex flex-column align-items-center p-4">
                        <img class="mb-3" src="{{ asset('assets/svg/illustrations/oc-error.svg') }}" alt="No data"
                            style="width: 10rem;" data-hs-theme-appearance="default">
                        <img class="mb-3" src="{{ asset('assets/svg/illustrations-light/oc-error.svg') }}" alt="No data"
                            style="width: 10rem;" data-hs-theme-appearance="dark">
                        <p class="mb-0">No data available.</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

@include('admin.pagination.paginationNumber',['data'=>$expenses])