@extends('admin.layout.adminApp')
@section('content')
    <main id="content" role="main" class="main">
        <!-- Content -->
        <div class="content container-fluid">
            <div class="col-lg-12">
                <!-- Profile Cover -->
                <div class="profile-cover">
                    <div class="profile-cover-img-wrapper">
                        @if ($member->image)
                            <img class="profile-cover-img" src="{{ asset($member->image) }}" alt="Image Description">
                        @else
                            <img class="profile-cover-img" src="{{ asset('assets/img/1920x400/img1.jpg') }}"
                                alt="Image Description">
                        @endif
                    </div>
                </div>
                <!-- End Profile Cover -->

                <!-- Profile Header -->
                <div class="text-center mb-5">
                    <!-- Avatar -->
                    <div class="avatar avatar-xxl avatar-circle profile-cover-avatar">

                        @if ($member->image)
                            <img class="avatar-img" src="{{ asset($member->image) }}" alt="Image Description">
                        @else
                            <img class="avatar-img" src="{{ asset('assets/img/160x160/images (1).jpg') }}"
                                alt="Image Description">
                        @endif

                        <span class="avatar-status avatar-status-success"></span>
                    </div>
                    <!-- End Avatar -->

                    <h1 class="page-header-title"> {{ $member->name }} <i class="bi-patch-check-fill fs-2 text-primary"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Top endorsed"
                            data-bs-original-title="Top endorsed"></i></h1>

                    <!-- List -->
                    <ul class="list-inline list-px-2">
                        <li class="list-inline-item">
                            <i class="bi-phone me-1"></i>
                            <span>{{ $member->mobile ?? 'N/A' }}</span>
                        </li>

                        <li class="list-inline-item">
                            <i class="bi-envelope me-1"></i>
                            <span>{{ $member->email ?? 'N/A' }}</span>
                        </li>

                        <li class="list-inline-item">
                            <i class="bi-calendar-week me-1"></i>
                            <span>{{ \Carbon\Carbon::parse($member->start_date)->format('d M, Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>


            <!-- End Page Header -->
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body">

                    <div class="row mb-3">
                        <div class="col-6 fw-bold text-start">
                            <i class="fas fa-calendar-day"></i> Birth Date
                        </div>
                        <div class="col-6 ps-5">{{ \Carbon\Carbon::parse($member->birth_date)->format('d M, Y') ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6 fw-bold text-start">
                            <i class="fas fa-cogs"></i> Membership Plan
                        </div>
                        <div class="col-6 ps-5">{{ $member->plan_name ?? 'N/A' }} <span class="badge bg-primary">₹
                                {{ number_format($member->plan_price, 2) }}</span></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6 fw-bold text-start">
                            <i class="fas fa-calendar-check"></i> Expiry Date
                        </div>
                        <div class="col-6 ps-5">
                            {{ \Carbon\Carbon::parse($member->end_date)->format('d M, Y') ?? 'N/A' }}
                            &nbsp;
                            &nbsp;
                            @php
                                $endDate = \Carbon\Carbon::parse($member->end_date);
                                $now = \Carbon\Carbon::now();
                                $remainingDays = $now->isBefore($endDate) ? $now->diffInDays($endDate) : 0;
                            @endphp

                            @if ($endDate->isPast())
                                {{ $endDate->format('d M, Y') }}
                            @else
                                <span class="text-white">
                                    <i class="bi bi-calendar-check"></i>
                                    {{ $remainingDays }} days left
                                </span>
                            @endif
                        </div>

                    </div>

                    <div class="row mb-3">
                        <div class="col-6 fw-bold text-start">
                            <i class="fas fa-user"></i> Trainer Name
                        </div>
                        <div class="col-6 ps-5">{{ $member->trainer_name ?? 'No Trainer Assigned' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6 fw-bold text-start">
                            <i class="fas fa-users"></i> Batch
                        </div>
                        <div class="col-6 ps-5">{{ $member->batch ?? 'N/A' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6 fw-bold text-start">
                            <i class="fas fa-venus-mars"></i> Gender
                        </div>
                        <div class="col-6 ps-5">{{ ucfirst($member->gender) ?? 'N/A' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6 fw-bold text-start">
                            <i class="fas fa-toggle-on"></i> Status
                        </div>
                        <div class="col-6 ps-5">
                            <span
                                class="text-capitalize {{ $member->status == 'active' ? 'text-success' : 'text-danger' }}">
                                {{ ucfirst($member->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6 fw-bold text-start">
                            <i class="fas fa-clock"></i> Created At
                        </div>
                        <div class="col-6 ps-5">{{ \Carbon\Carbon::parse($member->created_at)->format('d M, Y') }}</div>
                    </div>

                </div>

            </div>
            <!-- End Card -->


            <!-- Payment Details Table -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Payment Details</h3>
                </div>
                <div class="card-body table-responsive">
                    @if ($memberPayments->count())
                        <table id="datatable"
                            class="table table-lg table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                                <tr>
                                    {{-- <th>#</th> --}}
                                    <th>Plan Name</th>
                                    <th>Payment Mode</th>
                                    <th>Paid Amount (₹)</th>
                                    <th>Due Amount (₹)</th>
                                    <th>Plan Price (₹)</th>
                                    <th>Payment Type</th>
                                    <th>Payment Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($memberPayments as $index => $payment)
                                    <tr>
                                        {{-- <td>{{ $index + 1 }}</td> --}}
                                        <td>{{ ucfirst($payment->name) }}</td>
                                                                                <td>
                                            @php
                                                $icons = [
                                                    'phone pay' => asset('assets/images/phonepe-icon.png'),
                                                    'google pay' => asset('assets/images/google-pay-icon.png'),
                                                    'cash' => asset('assets/images/euro-notes-color-icon.png'),
                                                    'other' => asset('assets/images/credit-card-color-icon.png'),
                                                    'system' => asset('assets/images/led-television-color-icon.png'),
                                                ];

                                                $mode = strtolower($payment->payment_mode);
                                            @endphp

                                            @if (isset($icons[$mode]))
                                                <img src="{{ $icons[$mode] }}" alt="{{ $payment->payment_mode }}"
                                                    style="width:25px; height:25px; margin-right:5px; vertical-align:middle;">
                                            @endif
                                            {{ ucwords($payment->payment_mode) }}
                                        </td>
                                        <td class="text-success">₹ {{ number_format($payment->amount_paid, 2) }}</td>
                                        <td class="text-danger">₹ {{ number_format($payment->due_amount, 2) }}</td>
                                        <td>
                                            @if ($payment->payment_type != 'Due Payment')
                                                <del>₹ {{ number_format($payment->original_plan_amount, 2) }}</del>
                                            @endif
                                            ₹ {{ number_format($payment->after_discount_amount, 2) }}
                                        </td>
                                        <td>{{ ucfirst($payment->payment_type) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-center text-muted">No payment records found.</p>
                    @endif
                </div>
            </div>

            <!-- Old Membership Table -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Old Membership</h3>
                </div>
                <div class="card-body table-responsive">
                    @if ($oldMembershipPlans->count())
                        <table id="datatable"
                            class="table table-lg table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Plan Name</th>
                                    <th>Plan Price (₹)</th>
                                    <th>Duration</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Batch</th>
                                    <th>Renewed At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($oldMembershipPlans as $index => $plan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ ucfirst($plan->name) }}</td>
                                        <td class="text-success">
                                            <del>₹ {{ number_format($plan->plan_price ?? 0, 2) }}</del>
                                            ₹ {{ number_format($plan->final_price ?? 0, 2) }}
                                        </td>
                                        <td>{{ $plan->duration }} {{ ucfirst($plan->duration_type) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($plan->start_date)->format('d M, Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($plan->end_date)->format('d M, Y') }}</td>
                                        <td>{{ $plan->batch ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($plan->updated_at)->format('d M, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-center text-muted">No old membership plans found.</p>
                    @endif
                </div>

            </div>
        </div>

    </main>
@endsection
