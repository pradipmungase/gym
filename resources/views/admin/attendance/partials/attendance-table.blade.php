<table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
    <thead class="thead-light">
        <tr>
            <th class="table-column-ps-0">Sr no</th>
            <th class="table-column-ps-0">Member Name</th>
            <th>Time</th>
            <th>Attendance Date</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($attendance as $row)
            <tr>
                <td class="table-column-ps-0">{{ $loop->iteration }}</td>
                <td class="table-column-ps-0">
                    <a class="d-flex align-items-center" href="{{ route('menbers.view', encrypt($row->id)) }}">
                        <div class="avatar avatar-circle">
                            @if ($row->member_image)
                                <img class="avatar-img" src="{{ asset($row->member_image) }}" alt="Image Description">
                            @else
                                <img class="avatar-img" src="{{ asset('assets/img/160x160/images (1).jpg') }}" alt="Image Description">
                            @endif
                        </div>
                        <div class="ms-3">
                            <div class="d-flex align-items-center">
                                <span class="h5 text-inherit mb-0 me-1">{{ $row->member_name }}</span>
                                <i class="bi-patch-check-fill text-primary" data-bs-toggle="tooltip"
                                    data-bs-placement="top" aria-label="Top endorsed"
                                    data-bs-original-title="Top endorsed"></i>
                            </div>
                            <span class="d-block fs-5 text-body h5  mb-0 me-1">{{ $row->member_email }}</span>
                        </div>
                    </a>
                </td>
                <td>{{ \Carbon\Carbon::parse($row->time)->format('h:i A') }}</td>
                <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d M, Y') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">
                    <div class="d-flex flex-column align-items-center p-4">
                        <img class="mb-3" src="{{ asset('assets/svg/illustrations/oc-error.svg') }}" alt="No data"
                            style="width: 10rem;" data-hs-theme-appearance="default">
                        <img class="mb-3" src="{{ asset('assets/svg/illustrations-light/oc-error.svg') }}" alt="No data"
                            style="width: 10rem;" data-hs-theme-appearance="dark">
                        <p class="mb-0">No data available.</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
@include('admin.pagination.paginationNumber',['data'=>$attendance])