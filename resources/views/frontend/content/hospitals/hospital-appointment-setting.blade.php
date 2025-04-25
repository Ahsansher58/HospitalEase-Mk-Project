@extends('frontend.layouts.after-login-hospitals')

@section('title', 'Hospital Ease - Appointment setting')
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
                        <div class="hospital-list-block my-favourite-hospital frame p-3">
                            <h3 class="mb-0">Appointment Settings</h3>
                            <div class="mx--24 hospital-profile-wrapper mt-4">
                                <div class="row p-3">
                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <form method="POST" action="{{ route('hospital.appointment.setting.update') }}"
                                        id="appointmentForm">
                                        @csrf

                                        <!-- Appointment Type -->
                                        <div class="mb-5">
                                            <label for="appointmentType" class="mb-2">Appointment Type <span
                                                    class="text-danger">*</span></label>
                                            <select id="appointmentType" name="appointment_type"
                                                class="form-select form-input-control" required>
                                                <option value="" disabled
                                                    {{ empty($appointmentSettings['appointment_type'] ?? null) ? 'selected' : '' }}>
                                                    Select Appointment Type</option>
                                                <option value="whats_app"
                                                    {{ ($appointmentSettings['appointment_type'] ?? '') == 'whats_app' ? 'selected' : '' }}>
                                                    WhatsApp Account</option>
                                                <option value="website_link"
                                                    {{ ($appointmentSettings['appointment_type'] ?? '') == 'website_link' ? 'selected' : '' }}>
                                                    Website Link / Scheduler Link</option>
                                                <option value="default_from"
                                                    {{ ($appointmentSettings['appointment_type'] ?? '') == 'default_from' ? 'selected' : '' }}>
                                                    HospitalEase Default from</option>
                                            </select>

                                        </div>

                                        <!-- WhatsApp Info -->
                                        <div id="whats_app" style="display: none;">
                                            <h4>WhatsApp Info</h4>
                                            <div class="mb-3">
                                                <label class="mb-2" class="mb-2">WhatsApp Number <span
                                                        class="error_msg">*</span></label>
                                                <div class="input-group">
                                                    <select class="form-select form-input-control" name="country_code"
                                                        style="max-width: 150px;" required>
                                                        <option value="+91">+91 (India)</option>
                                                        <option value="+1">+1 (USA)</option>
                                                        <option value="+44">+44 (UK)</option>
                                                    </select>
                                                    <input type="number" class="form-control form-input-control"
                                                        placeholder="WhatsApp Number" name="whats_number"
                                                        value="{{ $appointmentSettings['whats_number'] ?? '' }}">
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="whatsMessage" class="mb-2">WhatsApp Welcome Message <span
                                                        class="text-danger">*</span></label>
                                                <textarea id="whatsMessage" name="whats_message" class="form-control form-input-control" rows="5">{{ $appointmentSettings['whats_message'] ?? '' }}</textarea>
                                            </div>
                                        </div>

                                        <!-- Website Link -->
                                        <div id="website_link" style="display: none;">
                                            <h4>Website Link / Scheduler Link</h4>
                                            <div class="mb-3">
                                                <label for="websiteAddress" class="mb-2">Website Address <span
                                                        class="text-danger">*</span></label>
                                                <input type="url" id="websiteAddress" name="website_address"
                                                    class="form-control form-input-control"
                                                    value="{{ $appointmentSettings['website_address'] ?? '' }}"
                                                    placeholder="Website Address">
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-info w-100">Save</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


    </main>
    <!--/MAIN-->


    @include('frontend.includes.hospital-footer')

    <script>
        $(document).ready(function() {
            // Handle change event when the appointment type is selected
            $('#appointmentType').on('change', function() {
                var selectedType = $(this).val();

                // Hide all appointment-specific divs and remove required attributes
                $('#whats_app, #website_link').hide().find('input, textarea, select').prop('required',
                    false);

                // Show the selected type's div and add required attributes
                if (selectedType === 'whats_app') {
                    $('#whats_app').show().find('input, textarea').prop('required', true);
                } else if (selectedType === 'website_link') {
                    $('#website_link').show().find('input').prop('required', true);
                }
            });

            // Trigger change event on page load to handle the default selection
            // This will show the appropriate div and handle the required fields correctly
            $('#appointmentType').trigger('change');

            // Form submission handler to ensure browser displays default validation messages
            $('#appointmentForm').on('submit', function(e) {
                var appointmentType = $('#appointmentType').val();

                // If the selected type is 'whats_app', disable all other fields except for 'whats_app' related fields
                if (appointmentType !== 'whats_app') {
                    $('#whats_app input, #whats_app textarea').prop('disabled',
                        true); // Disable 'whats_app' fields
                } else {
                    $('#whats_app input, #whats_app textarea').prop('disabled',
                        false); // Enable 'whats_app' fields
                }

                // If the selected type is 'website_link', disable all other fields except for 'website_link' related fields
                if (appointmentType !== 'website_link') {
                    $('#website_link input').prop('disabled', true); // Disable 'website_link' fields
                } else {
                    $('#website_link input').prop('disabled', false); // Enable 'website_link' fields
                }

                // If the selected type is 'default_from', disable all other fields except for 'default_from' related fields
                if (appointmentType !== 'default_from') {
                    $('#default_from input').prop('disabled', true); // Disable 'default_from' fields
                } else {
                    $('#default_from input').prop('disabled', false); // Enable 'default_from' fields
                }

                // Trigger validation before submitting the form
                if (!this.checkValidity()) {
                    e.preventDefault(); // Prevent submission if not valid
                    e.stopPropagation();
                    this.reportValidity(); // Show browser's default validation messages
                }
            });
        });
    </script>

@endsection
