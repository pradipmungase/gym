<table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
    <thead class="thead-light">
        <tr>
            <th class="table-column-ps-0">Sr no</th>
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
                <td>{{ $plan->price }}</td>
                <td>
                    <div>
                        <div class="dropdown">
                            <button class="btn btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Options
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item edit-plan-btn" href="#" data-bs-toggle="modal"
                                        data-bs-target="#editPlanModal"
                                        data-plan='@json($plan)'>
                                        <i class="bi bi-pencil me-2"></i> Edit
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('plans.view', encrypt($plan->id)) }}">
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

@include('admin.pagination.paginationNumber',['data'=>$plans])