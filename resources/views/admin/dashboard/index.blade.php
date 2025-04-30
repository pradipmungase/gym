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
                                $greeting = 'Good Morning';
                                $icon = '🌅'; // Morning icon
                            } elseif ($hour >= 12 && $hour < 17) {
                                $greeting = 'Good Afternoon';
                                $icon = '☀️'; // Afternoon icon
                            } elseif ($hour >= 17 && $hour < 21) {
                                $greeting = 'Good Evening';
                                $icon = '🌇'; // Evening icon
                            } else {
                                $greeting = 'Good Night';
                                $icon = '🌙'; // Night icon
                            }
                        @endphp


                        <h1 class="page-header-title">
                            {{ $icon }} {{ $greeting }}, <span
                                class="user_name">{{ Auth::user()->owner_name }}</span>
                        </h1>
                        <p class="page-header-text">Here's is your dashboard .</p>
                    </div>
                    <!-- End Col -->
                </div>
            </div>
            <div class="row mt-4">
                @foreach($stats as $stat)
                    <div class="col-md-3 mb-3">
                        <div class="card {{ $stat['bg_color'] }} shadow rounded-3">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="h1 mb-1 {{ $stat['text_color'] }}">
                                        {{ $stat['value'] }}
                                    </div>
                                    <span class="{{ $stat['text_color'] }}">{{ $stat['title'] }}</span>
                                </div>
                                <i class="bi {{ $stat['icon'] }} fs-1 {{ $stat['text_color'] }}"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Last 5 Transactions</h3>
                </div>
                <div class="card-body table-responsive">
                    @if ($lastFevTractions->count())
                        <table id="datatable"
                            class="table table-lg table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Member Name</th>
                                    <th>Plan Name</th>
                                    <th>Payment Mode</th>
                                    <th>Amount (Paid / Due)</th>
                                    <th>Payment Type</th>
                                    <th>Payment Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lastFevTractions as $index => $payment)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ ucwords($payment->member_name) }}</td>
                                        <td>{{ ucwords($payment->plan_name) }}</td>
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
                                        <td>
                                            <span class="text-success">Paid: ₹
                                                {{ number_format($payment->amount_paid, 2) }}</span><br>
                                            <span class="text-danger">Due: ₹
                                                {{ number_format($payment->due_amount, 2) }}</span>
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

        </div>
        <!-- End Content -->
    </main>
@endsection
