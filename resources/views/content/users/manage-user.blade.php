@extends('layouts/layoutMaster')

@section('title', 'List of Users')

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
    @vite(['resources/assets/js/tables-datatables-advanced.js', 'resources/assets/js/forms-selects.js', 'resources/assets/js/extended-ui-sweetalert2.js'])
    @include('content.scripts.script-users');
@endsection
@section('content')

    <!-- Ajax Sourced Server-side -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">List Of Users</h5>
            <div>
                <a href="{{ route('add-user') }}" class="btn btn-primary"><i class="ti ti-plus"></i> Add User</a>
            </div>
        </div>

        <div class="card-body">
            <div class="filter">
                <h6><i class="ti ti-filter"></i> Filter</h6>
                <div class="row">
                    <div class="col-lg-3  mb-3">
                        <select id="country" name="country" class="select2 form-select form-select-lg"
                            data-placeholder="Select Country" data-allow-clear="true">
                            <option value="">Select Country</option>
                            <!-- Populate dynamically -->
                        </select>
                    </div>
                    <div class="col-lg-3  mb-3">
                        <select id="state" name="state" class="select2 form-select form-select-lg"
                            data-placeholder="Select State" data-allow-clear="true">
                            <option value="">Select State</option>
                            <!-- Populate dynamically -->
                        </select>
                    </div>
                    <div class="col-lg-3  mb-3">
                        <select id="city" name="city" class="select2 form-select form-select-lg"
                            data-placeholder="Select City" data-allow-clear="true">
                            <option value="">Select City</option>
                            <!-- Populate dynamically -->
                        </select>
                    </div>
                    <div class="col-lg-3  mb-3">
                        <button type="button" onclick="csv_export_users()"
                            class="btn btn-outline-primary export-csv waves-effect w-100"><i
                                class="ti ti-download me-2"></i> Export as CSV</button>
                    </div>
                </div>
            </div>
            <div class="card-datatable text-nowrap position-relative">
                <table class="datatables-ajax2 table dataTables_wrapper dt-bootstrap5 no-footer">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date Created</th>
                            <th>Status</th>
                            <th>Full Name</th>
                            <th>Email Address</th>
                            <th>Location </th>
                            <th>Contact Number</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!--/ Ajax Sourced Server-side -->

@endsection
