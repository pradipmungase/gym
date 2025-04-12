<table class="table">
    <thead>
        <tr>
            <th class="table-column-ps-0">Sr No</th>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile No</th>
            <th>Joining Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($menbers as $menber)
            <tr>
                <td class="table-column-ps-0">{{ $loop->iteration }}</td>
                <td>{{ $menber->name }}</td>
                <td>{{ $menber->email }}</td>
                <td>{{ $menber->mobile }}</td>
                <td>{{ $menber->joining_date }}</td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Options
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item edit-menber-btn" href="#" data-bs-toggle="modal"
                                    data-bs-target="#editMenberModal"
                                    data-menber='@json($menber)'>Edit</a>
                            </li>
                            <li><a class="dropdown-item" href="#">Delete</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">
                    <div class="d-flex flex-column align-items-center p-4">
                        <img class="mb-3" src="./assets/svg/illustrations/oc-error.svg" alt="Image Description"
                            style="width: 10rem;" data-hs-theme-appearance="default">
                        <img class="mb-3" src="./assets/svg/illustrations-light/oc-error.svg" alt="Image Description"
                            style="width: 10rem;" data-hs-theme-appearance="dark">
                        <p class="mb-0">No data to show</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- Laravel pagination links --}}
<div class="d-flex justify-content-end mt-3">
    @if ($menbers->lastPage() > 1)
        <nav>
            <ul class="pagination justify-content-end">
                <li class="page-item {{ $menbers->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $menbers->previousPageUrl() ?? '#' }}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                @for ($page = 1; $page <= $menbers->lastPage(); $page++)
                    <li class="page-item {{ $page == $menbers->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $menbers->url($page) }}">{{ $page }}</a>
                    </li>
                @endfor

                <li class="page-item {{ !$menbers->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $menbers->nextPageUrl() ?? '#' }}" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    @endif
</div>
