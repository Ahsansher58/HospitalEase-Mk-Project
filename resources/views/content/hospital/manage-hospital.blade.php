@extends('layouts/layoutMaster')

@section('title', 'Manage Hospital')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/animate-css/animate.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss'])
@endsection
<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @include('content.scripts.script-hospitals');
    @vite(['resources/assets/js/tables-datatables-advanced.js', 'resources/assets/js/forms-selects.js', 'resources/assets/js/extended-ui-sweetalert2.js'])
@endsection
@section('content')

    <!-- Ajax Sourced Server-side -->
    <div class="card">
        <h5 class="card-header">Hospitals</h5>
        <div class="card-body">
            <div class="filter">
                <h6><i class="ti ti-filter"></i> Filter</h6>
                <div class="row">
                    <div class="col-lg-4  mb-3">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ti ti-search"></i></span>
                            <input type="text" id="customSearchInput" class="form-control" placeholder="Search..."
                                aria-label="Search..." aria-describedby="customSearchInput">
                        </div>
                    </div>
                    <div class="col-lg-4  mb-3">
                        <select id="select2Status" class="select2 form-select form-select-lg"
                            data-placeholder="Select Status" data-allow-clear="true">
                            <option value="">Selecte Status</option>
                            <option value="1">Approved</option>
                            <option value="0">Pending</option>
                        </select>
                    </div>
                    <div class="col-lg-4  mb-3">
                        <button type="button" class="btn btn-outline-primary export-csv waves-effect w-100"><i
                                class="ti ti-download me-2"></i> Export as CSV</button>
                    </div>
                </div>
            </div>
            <div class="card-datatable text-nowrap position-relative">
                <table class="datatables-hospitals table dataTables_wrapper dt-bootstrap5 no-footer">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date Created</th>
                            <th>Approval Status</th>
                            <th>Name of Hospital</th>
                            <th>Contact Number</th>
                            <th>Email Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!--/ Ajax Sourced Server-side -->

@endsection
