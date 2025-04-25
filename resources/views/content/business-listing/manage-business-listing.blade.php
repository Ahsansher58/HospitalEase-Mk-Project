@extends('layouts/layoutMaster')

@section('title', 'Manage Business Listing')

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
    @include('content.scripts.script-bussiness-listing');
@endsection
@section('content')

    <!-- Ajax Sourced Server-side -->
    <div class="card">
        <h5 class="card-header">Manage Business Listing</h5>
        <div class="card-body">
            <div class="filter">
                <h6><i class="ti ti-filter"></i> Filter</h6>
                <div class="row">
                    <div class="col-lg-4  mb-3">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text" id="basic-addon-search31"><i class="ti ti-search"></i></span>
                            <input type="text" id="customSearchInput" class="form-control" placeholder="Search..."
                                aria-label="Search..." aria-describedby="basic-addon-search31">
                        </div>
                    </div>
                    <div class="col-lg-4  mb-3">
                        <select id="business_category_id" class="select2 form-select form-select-lg"
                            data-placeholder="Select Category" data-allow-clear="true">
                            @if ($businessCategories->isEmpty())
                                <option value="">No categories available</option>
                            @else
                                <option value="">Select category</option>
                                @foreach ($businessCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-lg-4  mb-3">
                        <select id="select_city" class="select2 form-select form-select-lg" data-placeholder="Select City"
                            data-allow-clear="true">
                            @if ($uniqueCities->isEmpty())
                                <option value="">No City available</option>
                            @else
                                <option value="">Select City</option>
                                @foreach ($uniqueCities as $city)
                                    <option value="{{ $city }}">{{ $city }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-lg-4  mb-3">
                        <select id="select_state" class="select2 form-select form-select-lg" data-placeholder="Select State"
                            data-allow-clear="true">
                            @if ($uniqueStates->isEmpty())
                                <option value="">No state available</option>
                            @else
                                <option value="">Select state</option>
                                @foreach ($uniqueStates as $state)
                                    <option value="{{ $state }}">{{ $state }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-lg-4  mb-3">
                        <select id="select_country" class="select2 form-select form-select-lg"
                            data-placeholder="Select Country" data-allow-clear="true">
                            @if ($uniqueCountries->isEmpty())
                                <option value="">No Country available</option>
                            @else
                                <option value="">Select Country</option>
                                @foreach ($uniqueCountries as $country)
                                    <option value="{{ $country }}">{{ $country }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-lg-4  mb-3">
                        <button type="button" class="btn btn-outline-primary export-csv waves-effect w-100"
                            id="downloadCsvButton"><i class="ti ti-download me-2"></i> Export as CSV</button>
                    </div>
                </div>
            </div>
            <div class="card-datatable text-nowrap position-relative">
                <table class="datatables-ajax4 table dataTables_wrapper dt-bootstrap5 no-footer">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category</th>
                            <th>Business Name</th>
                            <th>Location </th>
                            <th>Mobile Number</th>
                            <th>WhatsApp Number</th>
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
