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



            <div class="row">
                <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
                    <a class="card h-100 text-decoration-none border-0 shadow-sm transition-3d-hover" href="#"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border-radius: 1rem;">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="js-counter h1 fw-bold mb-1"
                                    data-hs-counter-options='{"isCommaSeparated": true}'>52147</div>
                                <span class="text-white-50">Total Members</span>
                            </div>
                            <div>
                                <i class="bi bi-people-fill fs-1"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
                    <a class="card h-100 text-decoration-none border-0 shadow-sm transition-3d-hover" href="#"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border-radius: 1rem;">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="js-counter h1 fw-bold mb-1"
                                    data-hs-counter-options='{"isCommaSeparated": true}'>52147</div>
                                <span class="text-white-50">Total Members</span>
                            </div>
                            <div>
                                <i class="bi bi-people-fill fs-1"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
                    <a class="card h-100 text-decoration-none border-0 shadow-sm transition-3d-hover" href="#"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border-radius: 1rem;">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="js-counter h1 fw-bold mb-1"
                                    data-hs-counter-options='{"isCommaSeparated": true}'>52147</div>
                                <span class="text-white-50">Total Members</span>
                            </div>
                            <div>
                                <i class="bi bi-people-fill fs-1"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
                    <a class="card h-100 text-decoration-none border-0 shadow-sm transition-3d-hover" href="#"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border-radius: 1rem;">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="js-counter h1 fw-bold mb-1"
                                    data-hs-counter-options='{"isCommaSeparated": true}'>52147</div>
                                <span class="text-white-50">Total Members</span>
                            </div>
                            <div>
                                <i class="bi bi-people-fill fs-1"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
                    <a class="card h-100 text-decoration-none border-0 shadow-sm transition-3d-hover" href="#"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border-radius: 1rem;">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="js-counter h1 fw-bold mb-1"
                                    data-hs-counter-options='{"isCommaSeparated": true}'>52147</div>
                                <span class="text-white-50">Total Members</span>
                            </div>
                            <div>
                                <i class="bi bi-people-fill fs-1"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
                    <a class="card h-100 text-decoration-none border-0 shadow-sm transition-3d-hover" href="#"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border-radius: 1rem;">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="js-counter h1 fw-bold mb-1"
                                    data-hs-counter-options='{"isCommaSeparated": true}'>52147</div>
                                <span class="text-white-50">Total Members</span>
                            </div>
                            <div>
                                <i class="bi bi-people-fill fs-1"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
                    <a class="card h-100 text-decoration-none border-0 shadow-sm transition-3d-hover" href="#"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border-radius: 1rem;">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="js-counter h1 fw-bold mb-1"
                                    data-hs-counter-options='{"isCommaSeparated": true}'>52147</div>
                                <span class="text-white-50">Total Members</span>
                            </div>
                            <div>
                                <i class="bi bi-people-fill fs-1"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
                    <a class="card h-100 text-decoration-none border-0 shadow-sm transition-3d-hover" href="#"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border-radius: 1rem;">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="js-counter h1 fw-bold mb-1"
                                    data-hs-counter-options='{"isCommaSeparated": true}'>52147</div>
                                <span class="text-white-50">Total Members</span>
                            </div>
                            <div>
                                <i class="bi bi-people-fill fs-1"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>



            {{-- <!-- Row 1 -->
            <div class="row mt-4">
                <!-- Card 1 -->
                <div class="col-md-3">
                    <div class="card text-white bg-primary shadow rounded-3 mb-2">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="js-counter h1 mb-1" data-hs-counter-options='{"isCommaSeparated": true}'>52147
                                </div>
                                <span>Total Members</span>
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
                                <div class="js-counter h1 mb-1"
                                    data-hs-counter-options='{
           "isCommaSeparated": true
         }'>52147</div>
                                <span>Total Members</span>
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
                                <div class="js-counter h1 mb-1"
                                    data-hs-counter-options='{
           "isCommaSeparated": true
         }'>52147</div>
                                <span>Total Members</span>
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
                                <div class="js-counter h1 mb-1"
                                    data-hs-counter-options='{
           "isCommaSeparated": true
         }'>52147</div>
                                <span>Total Members</span>
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
                                <div class="js-counter h1 mb-1"
                                    data-hs-counter-options='{
           "isCommaSeparated": true
         }'>52147</div>
                                <span>Total Members</span>
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
                                <div class="js-counter h1 mb-1"
                                    data-hs-counter-options='{
           "isCommaSeparated": true
         }'>52147</div>
                                <span>Total Members</span>
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
                                <div class="js-counter h1 mb-1"
                                    data-hs-counter-options='{
           "isCommaSeparated": true
         }'>52147</div>
                                <span>Total Members</span>
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
                                <div class="js-counter h1 mb-1"
                                    data-hs-counter-options='{
           "isCommaSeparated": true
         }'>52147</div>
                                <span>Total Members</span>
                            </div>
                            <i class="bi bi-card-checklist fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
 --}}


        </div>
        <!-- End Content -->
    </main>
@endsection
