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
                @forelse ($memberRequests as $memberRequest)
                    <tr>
                        <td class="">{{ $memberRequests->firstItem() + $loop->index }}</td>
                        <td class="table-column-ps-0">
                            <a class="d-flex align-items-center"
                                href="{{ route('members.view', encrypt($memberRequest->member_id)) }}">
                                <div class="avatar avatar-circle">
                                    @if ($memberRequest->image)
                                        <img class="avatar-img" src="{{ asset($memberRequest->image) }}"
                                            alt="Image Description">
                                    @else
                                        <img class="avatar-img" src="{{ asset('assets/img/160x160/images (1).jpg') }}"
                                            alt="Image Description">
                                    @endif
                                </div>
                                <div class="ms-3">
                                    <div class="d-flex align-items-center">
                                        <span class="h5 text-inherit mb-0 me-1">{{ $memberRequest->name }}</span>
                                        <i class="bi-patch-check-fill text-primary" data-bs-toggle="tooltip"
                                            data-bs-placement="top" aria-label="Top endorsed"
                                            data-bs-original-title="Top endorsed"></i>
                                    </div>
                                    <span class="d-block fs-5 text-body h5  mb-0 me-1">{{ $memberRequest->email }}</span>
                                    <span style="float: inline-start;">{{ $memberRequest->mobile_number }}</span>
                                </div>
                            </a>
                        </td>

                        <td>{{ $memberRequest->plan_name }}</td>
                        <td>
                            <span class="text-success">
                                {{ \Carbon\Carbon::parse($memberRequest->joining_date)->format('d M, Y') }} 
                            </span>
                            To
                            @php
                                $endDate = \Carbon\Carbon::parse($memberRequest->joining_date);
                                $now = \Carbon\Carbon::now();
                                $remainingDays = $now->isBefore($memberRequest->end_date) ? $now->diffInDays($memberRequest->end_date) : 0;
                            @endphp

                            <span class="text-danger">
                                {{ \Carbon\Carbon::parse($memberRequest->end_date)->format('d M, Y') }} 
                            </span>
                            <br>
                            <span class="text-white">
                                <i class="bi bi-calendar-check"></i>
                                {{ $remainingDays }} days left
                            </span>
                        </td>

                        <td class="text-danger">₹
                            {{ number_format($memberRequest->due_amount, 2) }}
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'pending' => 'info',  // yellow
                                    'approved' => 'success', // green
                                    'rejected' => 'danger',  // red
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$memberRequest->member_status] ?? 'secondary' }}">{{ ucwords($memberRequest->member_status) }}</span>
                            </div>
                        </td>
                        <td>
                            <a href="#"
                                class="btn btn-outline-success btn-sm d-flex align-items-center gap-1 view-member-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#viewmemberModal" data-member='@json($memberRequest)' title="View Plan">
                                <i class="bi bi-eye fs-5"></i> View
                            </a>
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

@include('admin.pagination.paginationNumber', ['data' => $memberRequests])
