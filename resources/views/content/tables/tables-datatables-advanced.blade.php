@extends('layouts/layoutMaster')

@section('title', 'Patient')

<!-- Vendor Styles -->
@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/select2/select2.scss',
  'resources/assets/vendor/libs/flatpickr/flatpickr.scss'
])
@endsection

@section('content')

<!-- Ajax Sourced Server-side -->
<div class="card">
  <h5 class="card-header">Hospitals</h5>
  <div class="card-body">
  <div class="filter mb-3">
  <h6>Filter</h6>
  <div class="row">
  <div class="col-lg-3">
  <select id="select2Status" class="select2 form-select form-select-lg" data-placeholder="Select Status" data-allow-clear="true">
              <option value="AP">Approved</option>
              <option value="PE">Pending</option>
            </select>
</div>
  <div class="col-lg-3">
  <select id="select2City" class="select2 form-select form-select-lg" data-placeholder="Select City" data-allow-clear="true">
              <option value="CH">Chennai</option>
              <option value="BA">Bangalore</option>
            </select>
</div>
<div class="col-lg-3">
  <select id="select2State" class="select2 form-select form-select-lg" data-placeholder="Select State" data-allow-clear="true">
              <option value="TA">Tamilnadu</option>
              <option value="KA">Karnadaka</option>
            </select>
</div>
<div class="col-lg-3">
  <select id="select2Contry" class="select2 form-select form-select-lg" data-placeholder="Select Country" data-allow-clear="true">
              <option value="IN">India</option>
            </select>
</div>
</div>
</div>
  <div class="card-datatable text-nowrap position-relative">
  <button type="button" class="btn btn-outline-primary export-csv waves-effect">Export as CSV</button>
    <table class="datatables-ajax table dataTables_wrapper dt-bootstrap5 no-footer">
      <thead>
        <tr>
          <th>Date Created</th>
          <th>Approval Status</th>
          <th>Name of Hospital</th>
          <th>Location </th>
          <th> Contact Number</th>
          <th>Email Address</th>
          <th>Action</th>
        </tr>
      </thead>
    </table>
</div>
  </div>
</div>
<!--/ Ajax Sourced Server-side -->
<!-- Vendor Scripts -->
@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
  'resources/assets/vendor/libs/moment/moment.js',
  'resources/assets/vendor/libs/select2/select2.js',
  'resources/assets/vendor/libs/flatpickr/flatpickr.js'
])
@endsection

<!-- Page Scripts -->
@section('page-script')
@vite([
  'resources/assets/js/tables-datatables-advanced.js',
  'resources/assets/js/forms-selects.js'
])
@endsection

@endsection
