@extends('layouts/layoutMaster')

@section('title', 'Business Categories')

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
    @include('content.scripts.script-business-category');
@endsection
@section('content')

    <!-- Ajax Sourced Server-side -->
    <div class="card mb-3">
        <h5 class="card-header">Add Business Categories</h5>
        <div class="card-body">
            <form action="{{ route('business-categories.store') }}" method="POST" id="business_category_form"
                enctype="multipart/form-data">
                @csrf <!-- CSRF token for form protection -->
                <input type="hidden" name="id" id="category_id"> <!-- Hidden field for category ID -->
                <div id="method_field"></div>
                <div class="add-cat">
                    <div class="row">
                        <div class="col-lg-3">
                            <label class="form-label" for="select2Status">Choose Main Category</label>
                            <select id="select2Status" class="select2 form-select form-select-lg" name="main_category_id"
                                data-placeholder="Choose Main Category" data-allow-clear="true">
                                @if ($categories->isNotEmpty())
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                @else
                                    <option disabled>No categories available</option>
                                @endif
                            </select>
                            <!-- Error for main_category_id -->
                            @if ($errors->has('main_category_id'))
                                <span class="text-danger">{{ $errors->first('main_category_id') }}</span>
                            @endif
                        </div>
                        <div class="col-lg-1">
                            <label class="form-label" for="sortOrder">Sort Order</label>
                            <input class="form-control" type="number" placeholder="Sort Order" id="sortOrder"
                                name="order_no" autocomplete="off" />
                            <!-- Error for order_no -->
                            @if ($errors->has('order_no'))
                                <span class="text-danger">{{ $errors->first('order_no') }}</span>
                            @endif
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="categoryName">Category Name</label>
                            <input class="form-control" type="text" placeholder="Enter Category Name" id="categoryName"
                                name="name" autocomplete="off" />
                            <!-- Error for name -->
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="categoryImage">Category Image</label>
                            <input class="form-control" type="file" id="categoryImage" name="image" accept="image/*" />
                            <!-- Error for image -->
                            @if ($errors->has('image'))
                                <span class="text-danger">{{ $errors->first('image') }}</span>
                            @endif
                        </div>
                        <div class="col-lg-2 mt-4">
                            <button type="submit" class="btn btn-primary waves-effect">Submit</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <div class="card">
        <h5 class="card-header">Manage Business Categories</h5>
        <div class="card-body">
            <div class="card-datatable text-nowrap position-relative">
                <table class="datatables-ajax7 table dataTables_wrapper dt-bootstrap5 no-footer">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Order Number</th>
                            <th>Main Category</th>
                            <th>Sub Category</th>
                            <th style="max-width:50px !important;">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!--/ Ajax Sourced Server-side -->
@endsection
