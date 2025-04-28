<table class="table">
    <thead class="thead-light">
        <tr>
            <th class="table-column-ps-0">Sr No</th>
            <th>Name & Email</th>
            <th>Mobile No</th>
            <th>Joining Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($trainers as $trainer)
            <tr>
                <td class="table-column-ps-0">{{ $trainers->firstItem() + $loop->index }}</td>
                <td class="table-column-ps-0">
                    <a class="d-flex align-items-center" href="{{ route('trainer.view', encrypt($trainer->id)) }}">
                        <div class="avatar avatar-circle">
                            @if ($trainer->image)
                                <img class="avatar-img" src="{{ asset($trainer->image) }}" alt="Image Description">
                            @else
                                <img class="avatar-img" src="{{ asset('assets/img/160x160/images (1).jpg') }}" alt="Image Description">
                            @endif
                        </div>
                        <div class="ms-3">
                            <div class="d-flex align-items-center">
                                <span class="h5 text-inherit mb-0 me-1">{{ $trainer->name }}</span>
                                <i class="bi-patch-check-fill text-primary" data-bs-toggle="tooltip"
                                    data-bs-placement="top" aria-label="Top endorsed"
                                    data-bs-original-title="Top endorsed"></i>
                            </div>
                            <span class="d-block fs-5 text-body h5  mb-0 me-1">{{ $trainer->email }}</span>
                        </div>
                    </a>
                </td>

                <td>{{ $trainer->phone }}</td>
                <td>{{ \Carbon\Carbon::parse($trainer->joining_date)->format('d M, Y') }}</td>
                <td>
                    <div class="d-flex gap-2 justify-content-center">
                        <!-- Edit Button -->
                        <button type="button" class="btn btn-outline-info btn-sm d-flex align-items-center gap-1 btn-edit-trainer"
                            data-bs-toggle="modal" data-bs-target="#editTrainerModal"
                            data-trainer='@json($trainer)' data-bs-toggle="tooltip" title="Edit Plan">
                            <i class="bi bi-pencil-square fs-5"></i> Edit
                        </button>

                        <!-- View Button as Link -->
                        <a href="{{ route('trainer.view', encrypt($trainer->id)) }}"
                            class="btn btn-outline-success btn-sm d-flex align-items-center gap-1"
                            data-bs-toggle="tooltip" title="View Plan">
                            <i class="bi bi-eye fs-5"></i> View
                        </a>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">
                    <div class="d-flex flex-column align-items-center p-4">
                        <img class="mb-3" src="./assets/svg/illustrations/oc-error.svg" alt="Image Description"
                            style="width: 10rem;" data-hs-theme-appearance="default">
                        <img class="mb-3" src="./assets/svg/illustrations-light/oc-error.svg" alt="Image Description"
                            style="width: 10rem;" data-hs-theme-appearance="dark">
                        <p class="mb-0">No data to show</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
@include('admin.pagination.paginationNumber',['data'=>$trainers])
