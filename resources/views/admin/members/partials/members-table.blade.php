<table id="datatable"
    class="table table-lg table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
    <thead class="thead-light">
        <tr>
            <th class="">Sr No</th>
            <th>Name & Email</th>
            <th>Mobile No</th>
            <th>Joining Date</th>
            <th>Expiry Date</th>
            <th>Due Amount</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($members as $member)
            <tr>
                <td class="">{{ $members->firstItem() + $loop->index }}</td>
                <td class="table-column-ps-0">
                    <a class="d-flex align-items-center" href="{{ route('members.view', encrypt($member->member_id)) }}">
                        <div class="avatar avatar-circle">
                            @if ($member->image)
                                <img class="avatar-img" src="{{ asset($member->image) }}" alt="Image Description">
                            @else
                                <img class="avatar-img" src="{{ asset('assets/img/160x160/images (1).jpg') }}"
                                    alt="Image Description">
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
                @if (\Carbon\Carbon::parse($member->expiry_date)->isPast())
                    <td class="text-danger">
                        {{ \Carbon\Carbon::parse($member->expiry_date)->format('d M, Y') }}
                    </td>
                @else
                    <td class="text-success">
                        {{ \Carbon\Carbon::parse($member->expiry_date)->format('d M, Y') }}
                    </td>
                @endif
                <td class="text-danger">{{ $member->due_amount_payment }}</td>

                <td>
                    <div class="dropdown">
                        <button class="btn btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Options
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item edit-member-btn" href="#" data-bs-toggle="modal"
                                    data-bs-target="#editmemberModal" data-member='@json($member)'>
                                    <i class="bi bi-pencil me-2"></i> Edit
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item add-payment-btn" href="#" data-bs-toggle="modal"
                                    data-bs-target="#addPaymentModal" data-member='@json($member)'>
                                    <i class="bi bi-cash-coin me-2"></i> Add Payment
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('members.view', encrypt($member->member_id)) }}">
                                    <i class="bi bi-arrow-repeat me-2"></i> Renew Membership
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('members.view', encrypt($member->member_id)) }}">
                                    <i class="bi bi-sliders2-vertical me-2"></i> Change Plan
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('members.view', encrypt($member->member_id)) }}">
                                    <i class="bi bi-journal-text me-2"></i> Add Note
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('members.view', encrypt($member->member_id)) }}">
                                    <i class="bi bi-eye me-2"></i> View Details
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="https://wa.me/{{ $member->mobile }}?text=hi"
                                    target="_blank">
                                    <i class="bi bi-whatsapp me-2"></i> Whatsapp
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="tel:{{ $member->mobile }}">
                                    <i class="bi bi-telephone me-2"></i> Call
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="javascript:void(0)"
                                    onclick="deleteMember({{ $member->member_id }})">
                                    <i class="bi bi-trash me-2"></i> Delete
                                </a>
                            </li>
                        </ul>

                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8">
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

@include('admin.pagination.paginationNumber', ['data' => $members])
