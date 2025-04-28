@extends('admin.layout.adminApp')
@section('content')
    <main id="content" role="main" class="main">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center justify-content-between mb-4">
                    <div class="col-6">
                        <h1 class="page-header-title mb-0">Today's Attendance Details</h1>
                    </div>
                    <div class="col-6 text-end">
                        <button class="btn btn-primary" href="{{ url('attendance/take') }}">
                            <i class="bi bi-plus-circle me-1"></i> &nbsp; Take Attendance
                        </button>
                    </div>
                </div>
            </div>
            <div id="attendance-table-container" class="text-center my-4 table-responsive datatable-custom">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2">Loading...</p>
            </div>

        </div>
    </main>
@endsection
