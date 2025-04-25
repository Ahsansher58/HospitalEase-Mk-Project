@extends('layouts/layoutMaster')

@section('title', 'Locality, City, State and Country Masters')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/tagify/tagify.scss', 'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.scss', 'resources/assets/vendor/libs/typeahead-js/typeahead.scss', 'resources/assets/vendor/libs/animate-css/animate.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/tagify/tagify.js', 'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.js', 'resources/assets/vendor/libs/typeahead-js/typeahead.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/tables-datatables-advanced.js', 'resources/assets/js/forms-selects.js', 'resources/assets/js/forms-tagify.js', 'resources/assets/js/forms-typeahead.js', 'resources/assets/js/extended-ui-sweetalert2.js'])
    @include('content.scripts.script-location-master');
@endsection

@section('content')

    <!-- Ajax Sourced Server-side -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y" id="scrolldiv">
            <div class="card mb-3">
                <h5 class="card-header">Add Locality, City, State and Country Masters</h5>
                <div class="card-body">
                    <div class="add-cat">
                        <form action="{{ route('locationMaster.store') }}" method="POST" id="location_master_form">
                            <div id="method_field"></div>
                            @csrf <!-- Laravel's CSRF protection -->

                            <div class="row align-items-center">
                                <div class="col-lg-3">
                                    <label class="form-label" for="formValidationHospital4">Locality</label>
                                    <input id="TagifyCustomInlineSuggestion4" name="locality" class="form-control"
                                        placeholder="Enter Locality" value="{{ old('locality') }}">
                                    @error('locality')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-3">
                                    <label class="form-label" for="formValidationHospital">City</label>
                                    <input id="TagifyCustomInlineSuggestion" name="city" class="form-control"
                                        placeholder="Enter City" value="{{ old('city') }}">
                                    @error('city')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-3">
                                    <label class="form-label" for="formValidationHospital2">State</label>
                                    <input id="TagifyCustomInlineSuggestion2" name="state" class="form-control"
                                        placeholder="Enter State" value="{{ old('state') }}">
                                    @error('state')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-3">
                                    <label class="form-label" for="formValidationHospital3">Country</label>
                                    <input id="TagifyCustomInlineSuggestion3" name="country" class="form-control"
                                        placeholder="Enter Country" value="{{ old('country') }}">
                                    @error('country')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-3">
                                    <button type="submit" class="btn btn-primary waves-effect w-100 mt-4">Submit</button>
                                </div>
                            </div>
                        </form> <!-- Close Form Tag -->
                    </div>
                </div>
            </div>

            <div class="card">
                <h5 class="card-header">Manage Locality, City, State and Country Masters</h5>
                <div class="card-body">
                    <div class="card-datatable text-nowrap position-relative">
                        <table class="datatables-ajax8 table dataTables_wrapper dt-bootstrap5 no-footer">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Locality</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Country</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <!--/ Ajax Sourced Server-side -->
    @endsection
