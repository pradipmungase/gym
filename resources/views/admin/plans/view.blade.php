@extends('admin.layout.adminApp')
@section('content')
    <main id="content" role="main" class="main">
        <!-- Content -->
        <div class="content container-fluid">
            <!-- End Page Header -->
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body">

                    <div class="row mb-3">
                        <div class="col-6 fw-bold text-start">Name</div>
                        <div class="col-6 ps-5">{{ $plan->name ?? 'N/A' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6 fw-bold text-start">Price</div>
                        <div class="col-6 ps-5 text-success">₹ {{ number_format($plan->price, 2) ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6 fw-bold text-start">Duration</div>
                        <div class="col-6 ps-5">{{ $plan->duration ?? 'N/A' }} {{ $plan->duration_type ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6 fw-bold text-start">Created At</div>
                        <div class="col-6 ps-5">{{ \Carbon\Carbon::parse($plan->created_at)->format('d M, Y') }}</div>
                    </div>
                </div>
            </div>
            <!-- End Card -->

            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Member Details</h3>
                </div>
                <div class="card-body table-responsive">
                    @if ($members->count())
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
                                @foreach ($members as $index => $member)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $member->name }}</td>
                                        <td>{{ $member->mobile }}</td>
                                        <td>{{ \Carbon\Carbon::parse($member->start_date)->format('d M, Y') }}</td>
                                        <td class="text-danger">
                                            {{ \Carbon\Carbon::parse($member->end_date)->format('d M, Y') }}</td>
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
