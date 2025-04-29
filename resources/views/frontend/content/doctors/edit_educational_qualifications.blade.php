@extends('frontend.layouts.after-login-doctors')

@section('title', 'Hospital Ease - Dashboard')
@include('frontend.includes.favicon')
@section('content')
    @include('frontend.includes.after-login-doctor-header')
    <!--MAIN-->
    <main class="inner-page">
        <!--BANNER-->
        @include('frontend.includes.doctor-top-banner')
        <!--/BANNER-->

        <section class="pb-5">
            <div class="container">
                <div class="row">


                    <div class="col-xl-3">
                        <!--SIDE TAB-->
                        @include('frontend.includes.doctor-side-navbar')
                        <!--SIDE TAB-->
                    </div>

                    <div class="col-xl-9">
                        <div class="hospital-list-block my-favourite-hospital frame">
                            <div class="d-flex align-items-center justify-content-between mb-5">
                                <h3 class="font-medium mb-0">My Educational Qualifications</h3>
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{ route('doctor.EducationalQualificationsUpdateAll') }}" id="edit_educational_info" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="mb-2">College Name</label>
                                            <input type="text" class="form-control form-input-control" name="college_name"
                                                value="{{ $doctor_profile->college_name }}" required>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please enter College Name
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="year_studied" class="mb-2">Years Studied</label>
                                            <input type="number" class="form-control form-input-control" name="year_studied"
                                            value="{{ $doctor_profile->year_studied ?? ''}}" required>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please enter Years Studied
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="degree" class="mb-2">Degree Name</label>
                                            <input type="text" class="form-control form-input-control"
                                                name="degree" id="degree" value="{{ $doctor_profile->degree ?? '' }}" required>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please enter Degree Name
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="mb-2">Upload Certificate</label>
                                            <input type="file" class="form-control form-input-control"
                                            placeholder="" name="award_certificate" required>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please Upload Certificate
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="form-label" for="show_certificate_in_public">Show Certificate in public?</label>
                                        <div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="show_certificate_in_public_yes"
                                                    name="show_certificate_in_public" value="1"
                                                    {{ old('show_certificate_in_public', $doctor_profile['show_certificate_in_public'] ?? '0') == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="show_certificate_in_public_yes">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="show_certificate_in_public_no"
                                                    name="show_certificate_in_public" value="0"
                                                    {{ old('show_certificate_in_public', $doctor_profile['show_certificate_in_public'] ?? '0') == '0' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="show_certificate_in_public_no">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3 mt-md-5 mb-3">
                                    <button class="btn btn-info me-3" type="submit">Update Profile</button>
                                    <a href="{!! route('doctor.educational-qualifications') !!}" class="btn btn-cancel">Cancel</a>
                                </div>
                            </form>


                        </div>
                    </div>
                </div>

            </div>
        </section>
    </main>
    <!--/MAIN-->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            flatpickr("#flatpickr-date", {
                dateFormat: "d/m/Y", // Set the display format
                defaultDate: "{{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('d / m / Y') : '' }}", // Set the initial date
            });
        });
    </script>
    @include('frontend.includes.user-footer')
    <!-- Flatpickr Initialization Script -->

@endsection
<script type="text/javascript">
    $(document).ready(function(){
        $('#showCertificateCheckbox').on('change', function(){
            if ($(this).is(':checked')) {
                $(this).val('Yes'); 
            } else {
                $(this).val('No');
            }
        });
    });

</script>
