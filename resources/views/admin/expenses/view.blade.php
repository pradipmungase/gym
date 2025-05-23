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

                   <img class="avatar-img" src="{{ asset('assets/img/160x160/images (1).jpg') }}"
                                alt="Image Description">
                        <span class="avatar-status avatar-status-success"></span>
                    </div>
                    <!-- End Avatar -->

                    <h1 class="page-header-title"> {{ $plan->name }} <i
                            class="bi-patch-check-fill fs-2 text-primary" data-bs-toggle="tooltip" data-bs-placement="top"
                            aria-label="Top endorsed" data-bs-original-title="Top endorsed"></i></h1>

                    <!-- List -->
                    <ul class="list-inline list-px-2">
                        <li class="list-inline-item">
                            <i class="bi-phone me-1"></i>
                            <span>{{ $plan->duration ?? 'N/A' }}</span>
                        </li>

                        <li class="list-inline-item">
                            <i class="bi-envelope me-1"></i>
                            <span>{{ $plan->price ?? 'N/A' }}</span>
                        </li>

                        <li class="list-inline-item">
                            <i class="bi-calendar-week me-1"></i>
                                <span>{{ \Carbon\Carbon::parse($plan->created_at)->format('d M, Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>


            <!-- End Page Header -->
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body">

                    <div class="row mb-3">
                        <div class="col-6 fw-bold text-start">Gender</div>
                        <div class="col-6 ps-5">{{ $plan->duration ?? 'N/A' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6 fw-bold text-start">Status</div>
                        <div class="col-6 ps-5">
                            @php
                            $plan->status = 1;
                            @endphp
                            <span class="badge {{ $plan->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                {{ $plan->status == 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6 fw-bold text-start">Created At</div>
                        <div class="col-6 ps-5">{{ \Carbon\Carbon::parse($plan->created_at)->format('d M, Y') }}</div>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>

    </main>
@endsection
