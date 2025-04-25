@extends('layouts/layoutMaster')

@section('title', isset($advertisement) ? 'Edit Advertisement' : 'Add Advertisement')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite(['resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/animate-css/animate.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss', 'resources/assets/vendor/libs/dropzone/dropzone.scss', 'resources/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.scss', 'resources/assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.scss', 'resources/assets/vendor/libs/pickr/pickr-themes.scss'])
@endsection
<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js', 'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.js', 'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js', 'resources/assets/vendor/libs/dropzone/dropzone.js', 'resources/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js', 'resources/assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js', 'resources/assets/vendor/libs/pickr/pickr.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/forms-selects.js', 'resources/assets/js/forms-selects.js', 'resources/assets/js/forms-pickers.js', 'resources/assets/js/forms-file-upload.js', 'resources/assets/js/form-validation.js'])

    <script src="https://cdn.ckeditor.com/ckeditor5/17.0.0/classic/ckeditor.js"></script>
    <script>
        function show1() {
            document.getElementById('div1').style.display = 'block';
            document.getElementById('div2').style.display = 'none';
        }

        function show2() {
            document.getElementById('div1').style.display = 'none';
            document.getElementById('div2').style.display = 'block';
        }
    </script>
@endsection

@section('content')

    <!-- Ajax Sourced Server-side -->
    <div class="card">
        <h5 class="card-header">{{ isset($advertisement) ? 'Edit Advertisement' : 'Add Advertisement' }}</h5>
        <div class="card-body">
            <!-- Display validation errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Display success message -->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form: Handle both Add and Edit -->
            <form
                action="{{ isset($advertisement) ? route('advertisements.update', $advertisement->id) : route('advertisements.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @if (isset($advertisement))
                    @method('PUT')
                @endif

                <input type="hidden" name="image_blob" id="hiddenImageInput">

                <div class="row">
                    <!-- Campaign Name -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label" for="formValidationCampaignName">Campaign Name</label>
                        <input class="form-control typeahead" type="text" placeholder="Campaign Name"
                            value="{{ old('campaign_name', $advertisement->campaign_name ?? '') }}"
                            id="formValidationCampaignName" name="campaign_name" autocomplete="off" />
                    </div>

                    <!-- Placement -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label" for="formValidationPlacement">Placement</label>
                        <select id="formValidationPlacement" name="placement" class="select2 form-select"
                            data-allow-clear="true">
                            <option value="">Select Placement</option>
                            @foreach ($places as $index => $place)
                                <option value="{{ $index }}"
                                    {{ isset($advertisement) && $advertisement->placement == $index ? 'selected' : '' }}>
                                    {{ $place }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Image or Code Option -->
                    <div class="col-md-12 mb-4">
                        <div class="form-check form-check-inline mt-3" onclick="show1();">
                            <input class="form-check-input" type="radio" name="option" id="inlineRadio1" value="1"
                                {{ isset($advertisement) && $advertisement->option == 1 ? 'checked' : 'checked' }} />
                            <label class="form-check-label" for="inlineRadio1">Choose Image</label>
                        </div>
                        <div class="form-check form-check-inline" onclick="show2();">
                            <input class="form-check-input" type="radio" name="option" id="inlineRadio2" value="2"
                                {{ isset($advertisement) && $advertisement->option == 2 ? 'checked' : '' }} />
                            <label class="form-check-label" for="inlineRadio2">Insert Code</label>
                        </div>
                    </div>

                    <!-- Upload Image -->
                    <div class="col-12 mb-4" id="div1"
                        style="{{ isset($advertisement) && $advertisement->option == 2 ? 'display:none;' : '' }}">
                        <label class="form-label" for="multicol-last-name">Choose Image</label>
                        <div action="/" class="dropzone needsclick" id="dropzone-basic">
                            <div class="dz-message needsclick">
                                Drop files here or click to upload
                            </div>
                            <div class="fallback">
                                <input name="image" type="file" />
                            </div>
                        </div>

                        @if (isset($advertisement) && $advertisement->option == 1)
                            <div class="mt-3">
                                <label>Current Image:</label><br>
                                <img src="{{ asset('storage/' . $advertisement->image_code) }}" width="100"
                                    alt="Advertisement Image">
                            </div>
                        @endif
                    </div>

                    <!-- Insert Code -->
                    <div class="col-12 mb-4" id="div2"
                        style="{{ isset($advertisement) && $advertisement->option == 2 ? 'display:block;' : '' }}">
                        <label class="form-label" for="image_code">Insert Code</label>
                        <textarea class="form-control" id="image_code" name="image_code" rows="3">{{ old('image_code', $advertisement->image_code ?? '') }}</textarea>
                    </div>

                    <!-- Start Date & Time -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label" for="start_date">Start Date & Time</label>
                        <input type="text" class="form-control" placeholder="YYYY-MM-DD HH:MM" id="flatpickr-datetime"
                            name="start_date" value="{{ old('start_date', $advertisement->start_date ?? '') }}" />
                    </div>

                    <!-- End Date & Time -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label" for="end_date">End Date & Time</label>
                        <input type="text" class="form-control" placeholder="YYYY-MM-DD HH:MM" id="flatpickr-datetime2"
                            name="end_date" value="{{ old('end_date', $advertisement->end_date ?? '') }}" />
                    </div>
                </div>

                <button type="submit" class="btn btn-primary export-csv waves-effect mt-3">
                    {{ isset($advertisement) ? 'Update Advertisement' : 'Submit' }} <i
                        class="menu-icon tf-icons ti ti-arrow-right ms-1"></i>
                </button>
            </form>
        </div>
    </div>

@endsection
