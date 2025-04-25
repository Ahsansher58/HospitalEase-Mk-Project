@extends('layouts/layoutMaster')

@section('title',
    'Hospital Appointments
    ')

    <!-- Vendor Styles -->
@section('vendor-style')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/animate-css/animate.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss', 'resources/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.scss', 'resources/assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.scss', 'resources/assets/vendor/libs/pickr/pickr-themes.scss'])
@endsection

@section('content')

    <!-- Ajax Sourced Server-side -->
    <div class="card">
        <h5 class="card-header"><i class="ti ti-filter"></i> Hospital Appointments</h5>
        <div class="card-body">
            <div class="filter">
                <h6>Filter</h6>
                <div class="row">
                    <div class="col-lg-4  mb-3">
                        <input type="text" class="form-control" placeholder="Search by Hospital name, Patient, Phone, Email"
                            id="customSearch" />
                    </div>
                    <div class="col-lg-4  mb-3">
                        <input type="text" class="form-control" placeholder="YYYY-MM-DD to YYYY-MM-DD"
                            id="flatpickr-range" />
                    </div>
                    <div class="col-lg-4  mb-3">
                        <button type="button" onclick="csv_export_appointment()"
                            class="btn btn-outline-primary export-csv waves-effect"><i class="ti ti-download me-2"></i>
                            Export as CSV</button>
                    </div>
                </div>
            </div>
            <div class="card-datatable text-nowrap position-relative">
                <table class="datatables-appointments table dataTables_wrapper dt-bootstrap5 no-footer">
                    <thead>
                        <tr>
                            <th>ID </th>
                            <th>Appointment Number</th>
                            <th>Appointment Date</th>
                            <th>Hospital Name </th>
                            <th>Patient Name </th>
                            <th>Phone Number</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!--/ Ajax Sourced Server-side -->

@endsection
<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js', 'resources/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js', 'resources/assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js', 'resources/assets/vendor/libs/pickr/pickr.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @vite(['resources/assets/js/tables-datatables-advanced.js', 'resources/assets/js/forms-selects.js', 'resources/assets/js/forms-pickers.js', 'resources/assets/js/extended-ui-sweetalert2.js'])
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    @include('content.scripts.script-appointments');
    <script>
        $(document).ready(function() {
            $(".message").click(function() {
                $(".message").addClass("intro");
            });
        });
    </script>
@endsection
