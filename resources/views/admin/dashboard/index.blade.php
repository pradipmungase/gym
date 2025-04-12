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

                        <h1 class="page-header-title">{{ $greeting }}, <span class="user_name">{{ Auth::user()->owner_name }}</span></h1>

                        <p class="page-header-text">Here's what's happening with your store today.</p>
                    </div>
                    <!-- End Col -->
                </div>
            </div>
        </div>
        <!-- End Content -->
    </main>
@endsection
