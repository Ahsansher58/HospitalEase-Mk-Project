@extends('layouts/layoutMaster')

@section('title', 'Add Users')

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
    @vite(['resources/assets/js/forms-selects.js', 'resources/assets/js/form-validation.js'])
@endsection
@section('content')

    <!-- Ajax Sourced Server-side -->
    <div class="card">
        <h5 class="card-header">Add Users</h5>
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

            <form id="formValidationExamples" action="{{ route('user.store') }}" method="POST" novalidate>
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label" for="formValidationHospital">Full Name<span
                                class="text-danger">*</span></label>
                        <input class="form-control typeahead" type="text" placeholder="Full Name" id="fullname"
                            name="fullname" autocomplete="off" value="{{ old('fullname') }}" />
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label" for="formValidationEmail">Email Address<span
                                class="text-danger">*</span></label>
                        <input class="form-control typeahead" type="email" placeholder="Email Address" id="email"
                            name="email" autocomplete="off" value="{{ old('email') }}" />
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
                            name="mobile" autocomplete="off" value="{{ old('mobile') }}" />
                    </div>

                </div>
                <button type="submit" class="btn btn-primary export-csv waves-effect mt-3">Submit <i
                        class="menu-icon tf-icons ti ti-arrow-right ms-1"></i></button>
            </form>
        </div>
    </div>
    <!--/ Ajax Sourced Server-side -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Fetch countries
            fetch('{{ route('sadmin.getCountries') }}')
                .then(response => response.json())
                .then(countries => {
                    const countrySelect = document.getElementById('country');
                    countries.forEach(country => {
                        const option = document.createElement('option');
                        option.value = country;
                        option.textContent = country;
                        countrySelect.appendChild(option);
                    });
                });

            // On Country Change
            document.getElementById('country').addEventListener('change', function() {
                const country = this.value;
                const stateSelect = document.getElementById('state');
                const citySelect = document.getElementById('city');
                const localitySelect = document.getElementById('locality');

                stateSelect.innerHTML = '<option value="">Select State</option>';
                citySelect.innerHTML = '<option value="">Select City</option>';
                localitySelect.innerHTML = '<option value="">Select Locality</option>';

                if (country) {
                    fetch(`{{ route('sadmin.getStates') }}?country=${country}`)
                        .then(response => response.json())
                        .then(states => {
                            states.forEach(state => {
                                const option = document.createElement('option');
                                option.value = state;
                                option.textContent = state;
                                stateSelect.appendChild(option);
                            });
                        });
                }
            });

            // On State Change
            document.getElementById('state').addEventListener('change', function() {
                const state = this.value;
                const citySelect = document.getElementById('city');
                const localitySelect = document.getElementById('locality');

                citySelect.innerHTML = '<option value="">Select City</option>';
                localitySelect.innerHTML = '<option value="">Select Locality</option>';

                if (state) {
                    const country = document.getElementById('country').value;
                    fetch(`{{ route('sadmin.getCities') }}?country=${country}&state=${state}`)
                        .then(response => response.json())
                        .then(cities => {
                            cities.forEach(city => {
                                const option = document.createElement('option');
                                option.value = city;
                                option.textContent = city;
                                citySelect.appendChild(option);
                            });
                        });
                }
            });

            // On City Change
            document.getElementById('city').addEventListener('change', function() {
                const city = this.value;
                const localitySelect = document.getElementById('locality');

                localitySelect.innerHTML = '<option value="">Select Locality</option>';

                if (city) {
                    const country = document.getElementById('country').value;
                    const state = document.getElementById('state').value;
                    fetch(
                            `{{ route('sadmin.getLocalities') }}?country=${country}&state=${state}&city=${city}`
                        )
                        .then(response => response.json())
                        .then(localities => {
                            localities.forEach(locality => {
                                const option = document.createElement('option');
                                option.value = locality;
                                option.textContent = locality;
                                localitySelect.appendChild(option);
                            });
                        });
                }
            });
        });
    </script>
@endsection
