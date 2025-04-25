@extends('layouts/layoutMaster')

@section('title', 'Main Categories')

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
    @include('content.scripts.script-main-category');
@endsection

@section('content')

    <!-- Ajax Sourced Server-side -->
    <div class="card mb-3">
        <h5 class="card-header">Add Main Categories</h5>
        <div class="card-body">
            <!-- Start Form -->
            <form
                action="{{ old('cat_id') !== null ? route('mainCategory.update', old('cat_id')) : route('mainCategory.store') }}"
                method="POST" id="main_category_form">
                <input type="hidden" name="cat_id" id="cat_id" value="{{ old('cat_id') }}">
                <div id="method_field">
                    @if (old('cat_id') !== null)
                        <input type="hidden" name="_method" value="PUT">
                    @endif
                </div>
                @csrf <!-- Laravel CSRF protection -->
                <div class="add-cat">
                    <div class="row">
                        <!-- Sort Order Input -->
                        <div class="col-lg-5">
                            <label class="form-label" for="sort_order">Sort Order</label>
                            <input class="form-control" type="number" placeholder="Sort Order" id="sort_order"
                                name="sort_order" value="{{ old('sort_order') }}" autocomplete="off" />
                            @if ($errors->has('sort_order'))
                                <span class="text-danger">{{ $errors->first('sort_order') }}</span>
                            @endif
                        </div>

                        <!-- Category Name Input -->
                        <div class="col-lg-5">
                            <label class="form-label" for="name">Category Name</label>
                            <input class="form-control" type="text" placeholder="Enter Category Name" id="name"
                                name="name" value="{{ old('name') }}" autocomplete="off" />
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <div class="col-lg-2">
                            <button type="submit"
                                class="btn btn-primary waves-effect w-100 mt-4">{{ old('cat_id') !== null ? 'Update' : 'Submit' }}</button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- End Form -->
        </div>
    </div>

    <div class="card">
        <h5 class="card-header">Manage Main Categories</h5>
        <div class="card-body">
            <div class="card-datatable text-nowrap position-relative">
                <table class="datatables-ajax6 table dataTables_wrapper dt-bootstrap5 no-footer">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sort Order Number</th>
                            <th>Category Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!--/ Ajax Sourced Server-side -->

@endsection
