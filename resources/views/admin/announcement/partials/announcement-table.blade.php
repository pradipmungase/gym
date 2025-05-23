<table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
    <thead class="thead-light">
        <tr>
            <th class="table-column-ps-0">Sr no</th>
            <th class="table-column-ps-0">Title</th>
            <th class="table-column-ps-0">For</th>
            <th>Description</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($announcements as $announcement)
            <tr>
                <td class="table-column-ps-0">{{ $announcements->firstItem() + $loop->index }}</td>
                <td>{{ ucfirst($announcement->title) }}</td>
                <td>{{ ucfirst($announcement->for) }}</td>
                <td>{{ ucfirst($announcement->description) }}</td>
                <td>{{ \Carbon\Carbon::parse($announcement->date)->format('d M, Y') }}</td>
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

@include('admin.pagination.paginationNumber',['data'=>$announcements])