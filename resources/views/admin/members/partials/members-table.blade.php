<div class="card">
    <!-- Table -->
    <div class="table-responsive datatable-custom">
        <table id="datatable" class="table table-borderless table-thead-bordered table-nowrap card-table"'>
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Membership Plan</th>
                    <th>Joining Data & Expiry Date</th>
                    <th>Due Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($members as $member)
                    <tr>
                        <td class="">{{ $members->firstItem() + $loop->index }}</td>
                        <td class="table-column-ps-0">
                            <a class="d-flex align-items-center"
                                href="{{ route('members.view', encrypt($member->member_id)) }}">
                                <div class="avatar avatar-circle">
                                    @if ($member->image)
                                        <img class="avatar-img" src="{{ asset($member->image) }}"
                                            alt="Image Description">
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
                                    <span style="float: inline-start;">{{ $member->mobile }}</span>
                                </div>
                            </a>
                        </td>

                        <td>{{ $member->plan_name }}</td>
                        <td>
                            <span class="text-success">
                                {{ \Carbon\Carbon::parse($member->start_date)->format('d M, Y') }} 
                            </span>
                            To
                            @php
                                $endDate = \Carbon\Carbon::parse($member->end_date);
                                $now = \Carbon\Carbon::now();
                                $remainingDays = $now->isBefore($endDate) ? $now->diffInDays($endDate) : 0;
                            @endphp

                            <span class="text-danger">
                                {{ $endDate->format('d M, Y') }}
                            </span>
                            <br>
                            <span class="text-white">
                                <i class="bi bi-calendar-check"></i>
                                {{ $remainingDays+1 }} days left
                            </span>
                        </td>

                        <td class="text-danger">₹
                            {{ $member->due_amount ? number_format($member->due_amount, 2) : number_format($member->final_price, 2) }}
                        </td>
                        <td>
                            <div class="form-check form-switch form-switch-sm">
                                <input class="form-check-input" onclick="updateUserStatus({{ $member->member_id }})"
                                    type="checkbox" role="switch" @if ($member->member_status == 'active') checked @endif
                                    data-member-id="{{ $member->member_id }}">
                            </div>
                        </td>
                        <td>
                            <!-- Unfold -->
                            <div class="hs-unfold">
                                <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm rounded-circle"
                                    id="settingsDropdown1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi-three-dots-vertical"></i>
                                </button>

                                <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="settingsDropdown1">

                                    <a class="dropdown-item edit-member-btn" href="#" data-bs-toggle="modal"
                                        data-bs-target="#editmemberModal" data-member='@json($member)'>
                                        <i class="bi bi-pencil me-2"></i> Edit
                                    </a>
                                    <a class="dropdown-item add-payment-btn" href="#"  data-member='@json($member)'>
                                        <i class="bi bi-cash-coin me-2"></i> Add Payment
                                    </a>
                                    <a class="dropdown-item renew-membership-btn" href="#" data-member='@json($member)'>
                                        <i class="bi bi-arrow-repeat me-2"></i> Renew Membership
                                    </a>
                                    <a class="dropdown-item change-plan-btn" href="#" data-bs-toggle="modal"
                                        data-bs-target="#changePlanModel" data-member='@json($member)'>
                                        <i class="bi bi-sliders2-vertical me-2"></i> Change Plan
                                    </a>
                                    <a class="dropdown-item add-note-btn" href="#" data-bs-toggle="modal"
                                        data-bs-target="#addNoteModel" data-member='@json($member)'>
                                        <i class="bi bi-journal-text me-2"></i> Add Note
                                    </a>
                                    <a class="dropdown-item"
                                        href="{{ route('members.view', encrypt($member->member_id)) }}">
                                        <i class="bi bi-eye me-2"></i> View Details
                                    </a>
                                    <a class="dropdown-item" href="https://wa.me/{{ $member->mobile }}?text=hi"
                                        target="_blank">
                                        <i class="bi bi-whatsapp me-2"></i> Whatsapp
                                    </a>
                                    <a class="dropdown-item" href="tel:{{ $member->mobile }}">
                                        <i class="bi bi-telephone me-2"></i> Call
                                    </a>
                                    <a class="dropdown-item text-danger" href="javascript:void(0)"
                                        onclick="deleteMember({{ $member->member_id }})">
                                        <i class="bi bi-trash me-2"></i> Delete
                                    </a>
                                </div>
                            </div>
                            <!-- End Unfold -->
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="d-flex flex-column align-items-center p-4">
                                <img class="mb-3" src="./assets/svg/illustrations/oc-error.svg"
                                    alt="Image Description" style="width: 10rem;" data-hs-theme-appearance="default">
                                <img class="mb-3" src="./assets/svg/illustrations-light/oc-error.svg"
                                    alt="Image Description" style="width: 10rem;" data-hs-theme-appearance="dark">
                                <p class="mb-0">No data to show</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- End Table -->

</div>

@include('admin.pagination.paginationNumber', ['data' => $members])
