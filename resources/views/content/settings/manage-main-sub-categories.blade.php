@extends('layouts/layoutMaster')

@section('title', 'Manage Main Sub Categories')

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
    @include('content.scripts.script-main-subcategory');
@endsection
@section('content')

    <!-- Ajax Sourced Server-side -->
    <div class="card">
        <h5 class="card-header">Manage Main Sub Categories</h5>
        <div class="card-body">
            <div class="filter">
                <h6><i class="ti ti-filter"></i> Filter</h6>
                <div class="row">
                    <div class="col-lg-4 mb-3">
                        <select id="main_category" class="select2 form-select form-select-lg"
                            data-placeholder="Select Main Category" data-allow-clear="true">
                            @if ($categories->isEmpty())
                                <option value="" disabled>No categories available</option>
                            @else
                                <option value="">Search by category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('mainCategory') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <select id="sub_category" class="select2 form-select form-select-lg"
                            data-placeholder="Select Sub Category" data-allow-clear="true">
                            @if ($subcategories->isEmpty())
                                <option value="" disabled>No Subcategories available</option>
                            @else
                                <option value="">Search by Subcategory</option>
                                @foreach ($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}">
                                        {{ $subcategory->sub_category_name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ti ti-search"></i></span>
                            <input type="text" class="form-control" id="search_by_name" placeholder="Search..."
                                aria-label="Search..." aria-describedby="search_by_name">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-datatable text-nowrap position-relative">
                <table class="datatables-ajax9 table dataTables_wrapper dt-bootstrap5 no-footer">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sort Order</th>
                            <th>Main Category</th>
                            <th>Sub Category</th>
                            <th>Sub Category Type</th>
                            <th>Options / Values</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!--/ Ajax Sourced Server-side -->

@endsection
