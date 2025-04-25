@extends('layouts/layoutMaster')

@section('title', 'Update Users')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite(['resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/animate-css/animate.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss'])
@endsection
<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js', 'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.js', 'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/forms-selects.js'])
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @php
        $hsp_country = $data->userprofile->country ?? 'India';
        $hsp_state = $data->userprofile->state ?? '';
        $hsp_city = $data->userprofile->city ?? '';
        $hsp_locality = $data->userprofile->locality ?? '';
    @endphp
    <script>
        $(document).ready(function() {
            let hsp_country = @json($hsp_country);
            let hsp_state = @json($hsp_state);
            let hsp_city = @json($hsp_city);
            let hsp_locality = @json($hsp_locality);

            // Get all countries
            $.ajax({
                url: '{{ route('sadmin.getCountries') }}',
                method: 'GET',
                success: function(countries) {
                    let countrySelect = $('#country');
                    $.each(countries, function(index, country) {
                        countrySelect.append('<option value="' + country + '">' + country +
                            '</option>');
                    });

                    if (hsp_country) {
                        countrySelect.val(hsp_country).trigger('change');
                        console.log(hsp_country);

                    }
                },
            });

            // On Country Change
            $('#country').change(function() {
                let country = $(this).val();
                $('#state').empty().append('<option value="">Select State</option>');
                $('#city').empty().append('<option value="">Select City</option>');
                $('#locality').empty().append('<option value="">Select Locality</option>');

                if (country) {
                    $.ajax({
                        url: '{{ route('sadmin.getStates') }}',
                        method: 'GET',
                        data: {
                            country: country
                        },
                        success: function(states) {
                            let stateSelect = $('#state');
                            $.each(states, function(index, state) {
                                stateSelect.append('<option value="' + state + '">' +
                                    state + '</option>');
                            });

                            if (hsp_state) {
                                stateSelect.val(hsp_state).trigger('change');
                                console.log(hsp_state);
                            }
                        },
                    });
                }
            });

            // On State Change
            $('#state').change(function() {
                let state = $(this).val();
                $('#city').empty().append('<option value="">Select City</option>');
                $('#locality').empty().append('<option value="">Select Locality</option>');

                if (state) {
                    $.ajax({
                        url: '{{ route('sadmin.getCities') }}',
                        method: 'GET',
                        data: {
                            country: $('#country').val(),
                            state: state,
                        },
                        success: function(cities) {
                            let citySelect = $('#city');
                            $.each(cities, function(index, city) {
                                citySelect.append('<option value="' + city + '">' +
                                    city + '</option>');
                            });

                            if (hsp_city) {
                                citySelect.val(hsp_city).trigger('change');
                                console.log(hsp_city);
                            }
                        },
                    });
                }
            });

            // On City Change
            $('#city').change(function() {
                let city = $(this).val();
                $('#locality').empty().append('<option value="">Select Locality</option>');

                if (city) {
                    $.ajax({
                        url: '{{ route('sadmin.getLocalities') }}',
                        method: 'GET',
                        data: {
                            country: $('#country').val(),
                            state: $('#state').val(),
                            city: city,
                        },
                        success: function(localities) {
                            let localitySelect = $('#locality');
                            $.each(localities, function(index, locality) {
                                localitySelect.append('<option value="' + locality +
                                    '">' + locality + '</option>');
                            });

                            if (hsp_locality) {
                                localitySelect.val(hsp_locality);
                                console.log(hsp_city);
                            }
                        },
                    });
                }
            });
        });
    </script>
@endsection
@section('content')

    <!-- Ajax Sourced Server-side -->
    <div class="card">
        <h5 class="card-header">Update Users</h5>
        <div class="card-body">
            <!-- Display Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Display Success Message -->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form id="formValidationExamples" action="{{ route('user.update', ['id' => $data->id]) }}" method="POST"
                novalidate>
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label" for="formValidationHospital">Full Name<span
                                class="text-danger">*</span></label>
                        <input class="form-control typeahead" type="text" placeholder="Full Name" id="fullname"
                            name="fullname" autocomplete="off" value="{{ old('fullname', $data->name) }}" />
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label" for="formValidationEmail">Email Address<span
                                class="text-danger">*</span></label>
                        <input class="form-control typeahead" type="email" placeholder="Email Address" id="email"
                            name="email" autocomplete="off" value="{{ old('email', $data->email) }}" />
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label" for="formValidationPass">Password<span
                                class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Password" aria-describedby="basic-default-password2" />
                            <span id="basic-default-password2" class="input-group-text cursor-pointer"><i
                                    class="ti ti-eye-off"></i></span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label" for="password_confirmation">Confirm Password<span
                                class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" placeholder="Confirm Password"
                                aria-describedby="basic-default-password2" />
                            <span id="basic-default-password2" class="input-group-text cursor-pointer"><i
                                    class="ti ti-eye-off"></i></span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="country">Country<span class="text-danger">*</span></label>
                        <select id="country" name="country" class="form-control form-input-control">
                            <option value="">Select Country</option>
                            <!-- Populate dynamically -->
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="state">State<span class="text-danger">*</span></label>
                        <select id="state" name="state" class="form-control form-input-control">
                            <option value="">Select State</option>
                            <!-- Populate dynamically -->
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="city">City<span class="text-danger">*</span></label>
                        <select id="city" name="city" class="form-control form-input-control">
                            <option value="">Select City</option>
                            <!-- Populate dynamically -->
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="locality">Locality<span class="text-danger">*</span></label>
                        <select id="locality" name="locality" class="form-control form-input-control">
                            <option value="">Select Locality</option>
                            <!-- Populate dynamically -->
                        </select>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label" for="mobile">Mobile Number<span class="text-danger">*</span></label>
                        <input class="form-control typeahead" type="number" placeholder="Mobile Number" id="mobile"
                            name="mobile" autocomplete="off" value="{{ old('mobile', $data->mobile) }}" />
                    </div>

                </div>
                <button type="submit" class="btn btn-primary export-csv waves-effect mt-3">Update <i
                        class="menu-icon tf-icons ti ti-arrow-right ms-1"></i></button>
            </form>
        </div>
    </div>
    <!--/ Ajax Sourced Server-side -->

@endsection
