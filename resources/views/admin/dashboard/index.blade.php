@extends('admin.layout.adminApp')
@section('content')
    <main id="content" role="main" class="main">
        <!-- Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm mb-2 mb-sm-0">
                        @php
                            $hour = now()->format('H');
                            if ($hour >= 5 && $hour < 12) {
                                $greeting = 'Good morning';
                            } elseif ($hour >= 12 && $hour < 17) {
                                $greeting = 'Good afternoon';
                            } elseif ($hour >= 17 && $hour < 21) {
                                $greeting = 'Good evening';
                            } else {
                                $greeting = 'Good night';
                            }
                        @endphp

                        <h1 class="page-header-title">{{ $greeting }}, <span
                                class="user_name">{{ Auth::user()->owner_name }}</span></h1>

                        <p class="page-header-text">Here's is your dashboard .</p>
                    </div>
                    <!-- End Col -->
                </div>
            </div>

            <!-- Row 1 -->
            <div class="row mt-4">
                <!-- Card 1 -->
                <div class="col-md-3">
                    <div class="card text-white bg-primary shadow rounded-3 mb-2">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Total Users</h5>
                                <h3>1234</h3>
                            </div>
                            <i class="bi bi-people-fill fs-1"></i>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="col-md-3">
                    <div class="card text-white bg-success shadow rounded-3 mb-2">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Active Businesses</h5>
                                <h3>456</h3>
                            </div>
                            <i class="bi bi-briefcase-fill fs-1"></i>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="col-md-3">
                    <div class="card text-white bg-primary shadow rounded-3 mb-2">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Consumers</h5>
                                <h3>789</h3>
                            </div>
                            <i class="bi bi-person-badge-fill fs-1"></i>
                        </div>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="col-md-3">
                    <div class="card text-white bg-danger shadow rounded-3 mb-2">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Reports</h5>
                                <h3>32</h3>
                            </div>
                            <i class="bi bi-flag-fill fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 2 -->
            <div class="row mt-2">
                <!-- Card 5 -->
                <div class="col-md-3">
                    <div class="card text-white bg-info shadow rounded-3 mb-2">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">New Signups</h5>
                                <h3>78</h3>
                            </div>
                            <i class="bi bi-person-plus-fill fs-1"></i>
                        </div>
                    </div>
                </div>

                <!-- Card 6 -->
                <div class="col-md-3">
                    <div class="card text-white bg-secondary shadow rounded-3 mb-2">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Pending Requests</h5>
                                <h3>24</h3>
                            </div>
                            <i class="bi bi-hourglass-split fs-1"></i>
                        </div>
                    </div>
                </div>

                <!-- Card 7 -->
                <div class="col-md-3">
                    <div class="card text-white bg-success shadow rounded-3 mb-2">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Total Categories</h5>
                                <h3>112</h3>
                            </div>
                            <i class="bi bi-tags-fill fs-1"></i>
                        </div>
                    </div>
                </div>

                <!-- Card 8 -->
                <div class="col-md-3">
                    <div class="card text-white bg-light text-dark shadow rounded-3 mb-2">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Subscriptions</h5>
                                <h3>54</h3>
                            </div>
                            <i class="bi bi-card-checklist fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>



        </div>
        <!-- End Content -->
    </main>
@endsection
