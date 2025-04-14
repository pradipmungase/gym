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
                <td class="table-column-ps-0">{{ $loop->iteration }}</td>
                <td>{{ $plan->name }}</td>
                <td>{{ $plan->duration }}</td>
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
                                        data-plan='@json($plan)'>Edit</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('plans.view', encrypt($plan->id)) }}">View Details</a></li>
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

{{-- Laravel pagination --}}
<div class="d-flex justify-content-end mt-3">
    @if ($plans->lastPage() > 1)
        <nav>
            <ul class="pagination justify-content-end">
                {{-- Previous --}}
                <li class="page-item {{ $plans->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $plans->previousPageUrl() ?? '#' }}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                {{-- Page Numbers --}}
                @for ($page = 1; $page <= $plans->lastPage(); $page++)
                    <li class="page-item {{ $page == $plans->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $plans->url($page) }}">{{ $page }}</a>
                    </li>
                @endfor

                {{-- Next --}}
                <li class="page-item {{ !$plans->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $plans->nextPageUrl() ?? '#' }}" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    @endif
</div>
