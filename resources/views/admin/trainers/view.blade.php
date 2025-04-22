@extends('admin.layout.adminApp')
@section('content')
    <main id="content" role="main" class="main">
        <!-- Content -->
        <div class="content container-fluid">
            <div class="col-lg-12">
                <!-- Profile Cover -->
                <div class="profile-cover">
                    <div class="profile-cover-img-wrapper">
                        <img class="profile-cover-img" src="{{ asset('assets/img/1920x400/img1.jpg') }}"
                            alt="Image Description">
                    </div>
                </div>
                <!-- End Profile Cover -->

                <!-- Profile Header -->
                <div class="text-center mb-5">
                    <!-- Avatar -->
                    <div class="avatar avatar-xxl avatar-circle profile-cover-avatar">

                        @if ($trainer->image)
                            <img class="avatar-img" src="{{ asset($trainer->image) }}" alt="Image Description">
                        @else
                            <img class="avatar-img" src="{{ asset('assets/img/160x160/images (1).jpg') }}"
                                alt="Image Description">
                        @endif

                        <span class="avatar-status avatar-status-success"></span>
                    </div>
                    <!-- End Avatar -->

                    <h1 class="page-header-title"> {{ $trainer->name }} <i class="bi-patch-check-fill fs-2 text-primary"
                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Top endorsed"
                            data-bs-original-title="Top endorsed"></i></h1>

                    <!-- List -->
                    <ul class="list-inline list-px-2">
                        <li class="list-inline-item">
                            <i class="bi-phone me-1"></i>
                            <span>{{ $trainer->phone ?? 'N/A' }}</span>
                        </li>

                        <li class="list-inline-item">
                            <i class="bi-envelope me-1"></i>
                            <a href="#">{{ $trainer->email }}</a>
                        </li>

                        <li class="list-inline-item">
                            <i class="bi-calendar-week me-1"></i>
                            <span>{{ \Carbon\Carbon::parse($trainer->joining_date)->format('d M, Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>


            <!-- End Page Header -->
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body">



                    <div class="row mb-3">
                        <div class="col-6 fw-bold text-start">Gender</div>
                        <div class="col-6 ps-5">{{ $trainer->gender ?? 'N/A' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6 fw-bold text-start">Status</div>
                        <div class="col-6 ps-5">
                            @php
                                $trainer->status = 1;
                            @endphp
                            <span class="badge {{ $trainer->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                {{ $trainer->status == 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6 fw-bold text-start">Monthly Salary</div>
                        <div class="col-6 ps-5">₹ {{ number_format($trainer->monthly_salary, 2) ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6 fw-bold text-start">Created At</div>
                        <div class="col-6 ps-5">{{ \Carbon\Carbon::parse($trainer->created_at)->format('d M, Y') }}</div>
                    </div>
                </div>
            </div>
            <!-- End Card -->

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Member Details</h3>
                </div>
                <div class="card-body table-responsive">
                    @if ($trainerMembers->count())
                        <table id="datatable"
                            class="table table-lg table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Mobile No</th>
                                    <th>Joining Date</th>
                                    <th>Expiry Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trainerMembers as $index => $member)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $member->name }}</td>
                                        <td>{{ $member->mobile }}</td>
                                        <td>{{ \Carbon\Carbon::parse($member->joining_date)->format('d M, Y') }}</td>
                                        <td class="text-danger">
                                            {{ \Carbon\Carbon::parse($member->expiry_date)->format('d M, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-center text-muted">No member records found.</p>
                    @endif
                </div>
            </div>

        </div>


    </main>
@endsection
