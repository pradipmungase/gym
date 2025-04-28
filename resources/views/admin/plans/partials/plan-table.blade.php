<table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
    <thead class="thead-light">
        <tr>
            <th class="table-column-ps-0">#</th>
            <th class="table-column-ps-0">Plan Name</th>
            <th>Duration</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($plans as $plan)
            <tr>
                <td class="table-column-ps-0">{{ $plans->firstItem() + $loop->index }}</td>
                <td>{{ $plan->name }}</td>
                <td>{{ $plan->duration }} {{ ucfirst($plan->duration_type) }}</td>
                <td class="text-success">₹ {{ number_format($plan->price, 2) }}</td>
                <td>
                    <div class="d-flex gap-2 justify-content-center">
                        <!-- Edit Button -->
                        <button type="button" class="btn btn-outline-info btn-sm d-flex align-items-center gap-1"
                            data-bs-toggle="modal" data-bs-target="#editPlanModal"
                            data-plan='@json($plan)' data-bs-toggle="tooltip" title="Edit Plan">
                            <i class="bi bi-pencil-square fs-5"></i> Edit
                        </button>

                        <!-- View Button as Link -->
                        <a href="{{ route('plans.view', encrypt($plan->id)) }}"
                            class="btn btn-outline-success btn-sm d-flex align-items-center gap-1"
                            data-bs-toggle="tooltip" title="View Plan">
                            <i class="bi bi-eye fs-5"></i> View
                        </a>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">
                    <div class="d-flex flex-column align-items-center p-4">
                        <img class="mb-3" src="{{ asset('assets/svg/illustrations/oc-error.svg') }}" alt="No data"
                            style="width: 10rem;" data-hs-theme-appearance="default">
                        <img class="mb-3" src="{{ asset('assets/svg/illustrations-light/oc-error.svg') }}"
                            alt="No data" style="width: 10rem;" data-hs-theme-appearance="dark">
                        <p class="mb-0">No data available.</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

@include('admin.pagination.paginationNumber', ['data' => $plans])
