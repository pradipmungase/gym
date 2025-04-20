<table class="table">
    <thead>
        <tr>
            <th class="table-column-ps-0">Sr No</th>
            <th>Name & Email</th>
            <th>Mobile No</th>
            <th>Joining Date</th>
            <th>Expiry Date</th>
            <th>Due Amount</th>
            <th>Membership Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($members as $member)
            <tr>
                <td class="table-column-ps-0">{{ $members->firstItem() + $loop->index }}</td>
                <td class="table-column-ps-0">
                    <a class="d-flex align-items-center" href="{{ route('members.view', encrypt($member->member_id)) }}">  
                        <div class="avatar avatar-circle">
                            @if ($member->image)
                             <img class="avatar-img" src="{{ asset('uploads/members/' . $member->image) }}" alt="Image Description">
                            @else
                                <img class="avatar-img" src="{{ asset('assets/img/160x160/images (1).jpg') }}" alt="Image Description">
                            @endif
                        </div>
                        <div class="ms-3">
                            <div class="d-flex align-items-center">
                                <span class="h5 text-inherit mb-0 me-1">{{ $member->name }}</span>
                                <i class="bi-patch-check-fill text-primary" data-bs-toggle="tooltip"
                                    data-bs-placement="top" aria-label="Top endorsed"
                                    data-bs-original-title="Top endorsed"></i>
                            </div>
                            <span class="d-block fs-5 text-body h5  mb-0 me-1">{{ $member->email }}</span>
                        </div>
                    </a>
                </td>

                <td>{{ $member->mobile }}</td>
                <td>{{ \Carbon\Carbon::parse($member->joining_date)->format('d M, Y') }}</td>
                <td class="text-danger">{{ \Carbon\Carbon::parse($member->expiry_date)->format('d M, Y') }}</td>
                <td>{{ $member->due_amount }}</td>
                <td>
                    @if (\Carbon\Carbon::parse($member->expiry_date)->isPast())
                        <span class="badge bg-danger">Expired</span>
                    @else
                        <span class="badge bg-success">Active</span>
                    @endif
                </td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Options
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item edit-member-btn" href="#" data-bs-toggle="modal"
                                    data-bs-target="#editmemberModal"
                                    data-member='@json($member)'>Edit</a>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('members.view', encrypt($member->member_id)) }}">View Details</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">
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

@include('admin.pagination.paginationNumber',['data'=>$members])