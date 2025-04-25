@extends('layouts/layoutMaster')
@if (isset($editMainSubCat))
    @section('title', 'Update Main Sub Categories')
@else
    @section('title', 'Add Main Sub Categories')
@endif


<!-- Vendor Styles -->
@section('vendor-style')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/animate-css/animate.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss', 'resources/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.scss', 'resources/assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.scss', 'resources/assets/vendor/libs/jquery-timepicker/jquery-timepicker.scss', 'resources/assets/vendor/libs/pickr/pickr-themes.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/jquery-repeater/jquery-repeater.js', 'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js', 'resources/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js', 'resources/assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js', 'resources/assets/vendor/libs/jquery-timepicker/jquery-timepicker.js', 'resources/assets/vendor/libs/pickr/pickr.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/tables-datatables-advanced.js', 'resources/assets/js/forms-selects.js', 'resources/assets/js/forms-extras.js', 'resources/assets/js/forms-pickers.js'])
    <script>
        function showHide(elem) {
            // Hide all divs initially
            for (let i = 1; i <= 10; i++) {
                document.getElementById('type' + i).style.display = 'none';
            }
            // Show the selected div if a type is chosen
            if (elem.selectedIndex !== 0) {
                document.getElementById('type' + elem.value).style.display = 'block';
                document.getElementById('option3').value = '';
                document.getElementById('option4').value = '';
                document.getElementById('option8').value = '';
                if (elem.value == 3)
                    document.getElementById('option3').value = 'Multi Select Icons and Text';
                if (elem.value == 4)
                    document.getElementById('option4').value = 'Multi Select Image and Text';
                if (elem.value == 8)
                    document.getElementById('option8').value = 'Multi-Image & Text Input with Sorting';

            }
        }

        window.onload = function() {
            // Call showHide on page load to show related options if type is already selected
            const selectedType = document.getElementById('formValidationCategoryType').value;
            showHide(document.getElementById('formValidationCategoryType'));
        };
    </script>
@endsection

@section('content')
    <div class="card mb-3">
        <h5 class="card-header">{{ isset($editMainSubCat) ? 'Update Sub Category' : 'Add Sub Category' }}</h5>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form
                action="{{ isset($editMainSubCat) ? route('subCategory.update', $editMainSubCat['id']) : route('subCategory.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @if (isset($editMainSubCat))
                    @method('PUT')
                @endif
                <div class="add-cat">
                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <label class="form-label" for="mainCategory">Choose Main Category</label>
                            <select id="mainCategory" name="mainCategory" class="select2 form-select"
                                data-allow-clear="true">
                                <option value="">Choose Main Category</option>
                                @if ($categories->isEmpty())
                                    <option value="" disabled>No categories available</option>
                                @else
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('mainCategory', $editMainSubCat['main_category_id'] ?? '') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label class="form-label" for="sort_order">Sort Order</label>
                            <input class="form-control" type="number" placeholder="Sort Order" id="sort_order"
                                name="sort_order" value="{{ old('sort_order', $editMainSubCat['sort_order'] ?? '') }}"
                                autocomplete="off" />
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label class="form-label" for="sub_category_name">Sub Category Name</label>
                            <input class="form-control" type="text" placeholder="Enter Sub Category Name"
                                id="sub_category_name" name="sub_category_name"
                                value="{{ old('sub_category_name', $editMainSubCat['sub_category_name'] ?? '') }}"
                                autocomplete="off" />
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label class="form-label" for="sub_category_required">Sub Category is required?</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="sub_category_yes"
                                        name="sub_category_required" value="1"
                                        {{ old('sub_category_required', $editMainSubCat['sub_category_required'] ?? '0') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sub_category_yes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="sub_category_no"
                                        name="sub_category_required" value="0"
                                        {{ old('sub_category_required', $editMainSubCat['sub_category_required'] ?? '0') == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sub_category_no">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <label class="form-label" for="formValidationCategoryType">Type Of Sub Category</label>
                            <select id="formValidationCategoryType" name="type" class="select2 form-select"
                                data-allow-clear="true" onchange="showHide(this)">
                                <option value="0">Choose Type Of Sub Category</option>
                                @php
                                    $type_values = config('subcategorytype.subcategory_type');
                                @endphp
                                @foreach ($type_values as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ old('type', $editMainSubCat['type'] ?? '') == $value ? 'selected' : '' }}>
                                        {{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @for ($i = 1; $i <= 10; $i++)
                        <div class="row" id="type{{ $i }}" style="display: none;">
                            @include('content.settings.sub-cat-options.' . $i)
                        </div>
                    @endfor
                    <div class="row">
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary waves-effect mt-4" id="success">
                                @if (isset($editMainSubCat))
                                    Update
                                @else
                                    Submit
                                @endif
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
