@extends('frontend.layouts.after-login-hospitals')

@section('title', 'Hospital Ease - Profile')
@include('frontend.includes.favicon')
@section('content')
    @include('frontend.includes.after-login-hospitals-header')

    <!--MAIN-->
    <main class="inner-page">

        <section class="pb-5 pt-lg-5 pt-3">
            <div class="container layout-container">
                <h3 class="text-lg-start text-end mt-2">JV Hospital</h3>
                <div class="d-flex">
                    <!--SIDE TAB-->
                    @include('frontend.includes.hospital-side-navbar')
                    <!--SIDE TAB-->
                    <div class="contnet-wrapper">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="hospital-list-block my-favourite-hospital frame">
                            <div class="d-sm-flex justify-content-between align-items-center mb-3 border-bottom">
                                <h3 class="mb-0">Setting</h3>
                                <form autocomplete="off" class="hospital-search-placeholder">
                                    <div class="d-flex my-4 my-sm-0 align-items-center">
                                        <a href="javascript:void()" class="btn btn-info btn-md ms-3" data-bs-toggle="modal"
                                            data-bs-target="#changePassword">Change Password</a>
                                    </div>
                                </form>
                            </div>
                            <form action="{{ route('hospital.update.locality') }}" method="POST">
                                @csrf
                                <div class="row justify-content-between g-2">
                                    <div class="col-md-6 mb-3">
                                        <h4 class="mb-3" for="country">Country<span class="text-danger">*</span></h4>
                                        <select id="country" name="country" class="form-control form-input-control">
                                            <option value="">Select Country</option>
                                            <!-- Populate dynamically -->
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <h4 class="mb-3" for="state">State<span class="text-danger">*</span></h4>
                                        <select id="state" name="state" class="form-control form-input-control">
                                            <option value="">Select State</option>
                                            <!-- Populate dynamically -->
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <h4 class="mb-3" for="city">City<span class="text-danger">*</span></h4>
                                        <select id="city" name="city" class="form-control form-input-control">
                                            <option value="">Select City</option>
                                            <!-- Populate dynamically -->
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <h4 class="mb-3" for="locality">Locality<span class="text-danger">*</span></h4>
                                        <select id="locality" name="locality" class="form-control form-input-control">
                                            <option value="">Select Locality</option>
                                            <!-- Populate dynamically -->
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3 text-end">
                                        <button type="submit" class="btn btn-info">Update Locality</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>


    </main>
    <!--/MAIN-->
    <!-- Change Password Modal -->
    <div class="modal fade" id="changePassword" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-m modal-dialog-centered">
            <div class="modal-content rounded-24">
                <div class="modal-header border-0">
                    <h3 class="modal-title font-regular" id="staticBackdropLabel">Change Password</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body mt-4">
                    <form id="changePasswordForm" method="POST" action="{{ route('hospital.changePassword') }}">
                        @csrf
                        <div class="row gy-3">
                            <div class="col-lg-12">
                                <label class="mb-2">Old password<span class="text-danger">*</span></label>
                                <input type="password" class="form-control form-input-control" name="old_password"
                                    placeholder="Enter Your old password" required>
                            </div>
                            <div class="col-lg-12">
                                <label class="mb-2">New password<span class="text-danger">*</span></label>
                                <input type="password" class="form-control form-input-control" name="new_password"
                                    placeholder="Enter Your new password" required>
                            </div>
                            <div class="col-lg-12">
                                <label class="mb-2">Confirm password<span class="text-danger">*</span></label>
                                <input type="password" class="form-control form-input-control"
                                    name="new_password_confirmation" placeholder="Enter Your confirm password" required>
                            </div>
                        </div>

                        <div class="my-5">
                            <button type="submit" class="btn btn-info me-2 font-regular">Change Password</button>
                            <button type="button" class="btn btn-cancel font-size-16 font-regular"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('frontend.includes.hospital-footer')
    @php
        $hsp_country = $hospital->country;
        $hsp_state = $hospital->state;
        $hsp_city = $hospital->city;
        $hsp_locality = $hospital->locality;
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
                            }
                        },
                    });
                }
            });
        });
    </script>

@endsection
