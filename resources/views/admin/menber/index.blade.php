@extends('admin.layout.adminApp')
@section('content')
    <main id="content" role="main" class="main">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-end justify-content-between mb-4">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <h1 class="page-header-title">Menber Details</h1>
                    </div>
                    <div class="col-md-6 text-md-end d-flex flex-wrap gap-2 justify-content-md-end">
                        <a class="btn btn-primary" href="{{ route('addMenber') }}" >
                            <i class="bi bi-person-plus-fill me-1"></i> Add Menber
                        </a>
                    </div>
                </div>

            </div>
                <div class="table-responsive datatable-custom">
                    <table id="datatable"
                        class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                        <thead class="thead-light">
                            <tr>
                                <th class="table-column-ps-0">Sr no</th>
                                <th class="table-column-ps-0">Full name</th>
                                <th>Rent Due</th>
                                <th>Wing Name</th>
                                <th>Room No</th>
                                <th>Admission_Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="table-column-ps-0">1</td>
                                <td class="table-column-ps-0">
                                    <a class="d-flex align-items-center" href="./user-profile.html">
                                        <div class="flex-shrink-0">
                                            <div class="avatar avatar-sm avatar-circle">
                                                <img class="avatar-img" src="./assets/img/160x160/img10.jpg"
                                                    alt="Image Description">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="text-inherit mb-0">Amanda Harvey <i
                                                    class="bi-patch-check-fill text-primary" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Top endorsed"></i></h5>
                                        </div>
                                    </a>
                                </td>
                                <td>
                                    <span class="legend-indicator bg-success"></span>Successful
                                </td>
                                <td>Unassigned</td>
                                <td>amanda@site.com</td>
                                <td>1 year ago</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

        </div>
    </main>
@endsection
