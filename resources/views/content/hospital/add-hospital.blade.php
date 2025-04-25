@extends('layouts/layoutMaster')

@section('title', 'Add Hospital')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite(['resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/animate-css/animate.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss'])
    <!--Editor CSS-->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.0.2/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/froala_editor.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/froala_style.css') }}">
    <style>
        .ck-editor__editable {
            min-height: 150px;
        }
    </style>
@endsection
<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js', 'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.js', 'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js'])
@endsection
@section('content')
    <script>
        let arrayCase6 = [];
        let arrayCase7 = [];
        let arrayCase9 = [];
    </script>
    <!-- Ajax Sourced Server-side -->
    <div class="card">
        <h5 class="card-header">Add Hospitals</h5>
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
            <form id="hospitalProfileForm" method="POST" action="{{ route('store_hospital') }}" novalidate>
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <h5>Basic Details</h5>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                        <input class="form-control typeahead" type="email" placeholder="Enter Email" id="email"
                            name="email" value="{{ old('email') }}" autocomplete="off" required />
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label" for="mobile">Mobile <span class="text-danger">*</span></label>
                        <input class="form-control typeahead" type="text" placeholder="Enter mobile" id="mobile"
                            name="mobile" value="{{ old('mobile') }}" autocomplete="off" required />
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                        <input class="form-control typeahead" type="text" placeholder="Enter Password" id="password"
                            name="password" value="{{ old('password') }}" autocomplete="off" />
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label" for="password_confirmation">Confirm Password <span
                                class="text-danger">*</span></label>
                        <input class="form-control typeahead" type="text" placeholder="Enter Confirm Password"
                            id="password_confirmation" value="{{ old('password_confirmation') }}"
                            name="password_confirmation" autocomplete="off" />
                    </div>
                    <div class="col-md-12">
                        @if ($main_cats->isNotEmpty())
                            @foreach ($main_cats as $main_cat)
                                @if ($main_sub_cats->has($main_cat->id))
                                    @foreach ($main_sub_cats[$main_cat->id] as $sub_cat)
                                        @switch($sub_cat->type)
                                            @case(1)
                                                {{-- Checkbox --}}
                                                @include('content.hospital.subcategory-parts.case1')
                                            @break

                                            @case(2)
                                                {{-- Radio Input --}}
                                                @include('content.hospital.subcategory-parts.case2')
                                            @break

                                            @case(3)
                                                {{-- Multi Select Icons & Text --}}
                                                @include('content.hospital.subcategory-parts.case3')
                                            @break

                                            @case(4)
                                                {{-- Multi-Image & Text --}}
                                                @include('content.hospital.subcategory-parts.case4')
                                            @break

                                            @case(5)
                                                {{-- Textbox Input --}}
                                                @include('content.hospital.subcategory-parts.case5')
                                            @break

                                            @case(6)
                                                {{-- TextArea Input --}}
                                                @include('content.hospital.subcategory-parts.case6')
                                            @break

                                            @case(7)
                                                {{-- Date Picker --}}
                                                @include('content.hospital.subcategory-parts.case7')
                                            @break

                                            @case(8)
                                                {{-- Multi-Image & Text Input with Sorting --}}
                                                @include('content.hospital.subcategory-parts.case8')
                                            @break

                                            @case(9)
                                                {{-- Search Tag Input --}}
                                                @include('content.hospital.subcategory-parts.case9')
                                            @break

                                            @case(10)
                                                {{-- Google map Input --}}
                                                @include('content.hospital.subcategory-parts.case10')
                                            @break
                                        @endswitch
                                    @endforeach
                                @else
                                    <p>No subcategories available.</p>
                                @endif
                            @endforeach
                        @else
                            <p>No categories available.</p>
                        @endif
                    </div>
                </div>
                <button type="submit" class="btn btn-primary export-csv waves-effect mt-3">Submit <i
                        class="menu-icon tf-icons ti ti-arrow-right ms-1"></i></button>
            </form>
        </div>
    </div>
    <!--/ Ajax Sourced Server-side -->

@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/forms-selects.js', 'resources/assets/js/form-validation.js'])
    <script src="https://cdn.ckeditor.com/ckeditor5/17.0.0/classic/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Tag JS -->
    <script src="{{ asset('assets/frontend/js/jquery.tagsinput.js') }}"></script>
    <script>
        (function() {
            arrayCase6.forEach((textAreaID, index) => {
                ClassicEditor
                    .create(document.querySelector("#" + textAreaID))
                    .catch(error => {
                        console.error(error);
                    });
            });

            arrayCase7.forEach((datepickerID) => {
                var datepickerElement = document.getElementById(datepickerID);
                var datepickerElementOld = document.getElementById(datepickerID + 'old');
                if (datepickerElement) {
                    var oldDate = datepickerElementOld.value || "";

                    flatpickr(datepickerElement, {
                        dateFormat: "d/m/Y",
                        defaultDate: oldDate || "today",
                    });
                } else {
                    console.log("Element with ID", datepickerID, "not found.");
                }
            });


            arrayCase9.forEach((tagsInputID, index) => {
                $('#' + tagsInputID).tagsInput();
                $("a").click(function(e) {
                    e.preventDefault();
                    var i = $(this).data("value");
                    $('#' + tagsInputID).addTag(i);
                });
            });
        })()
    </script>

@endsection
