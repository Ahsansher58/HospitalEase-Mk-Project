@extends('frontend.layouts.after-login-doctors')

@section('title', 'Hospital Ease - Award & Achievements')
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
                                <h3 class="font-medium mb-0">Award & Achievements</h3>
                                {{-- <button class="btn btn-info me-3" type="submit">Add More</button> --}}
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
                            <form action="{{ route('doctor.AwardAchievementsUpdateAll') }}" id="edit_award_achievements_info" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="mb-2">Award Name</label>
                                            <input type="text" class="form-control form-input-control" name="award_name"
                                                value="{{ $doctor_profile->award_name }}" required>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please enter Award Name
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="awarded_year" class="mb-2">Awarded Year</label>
                                            <input type="number" class="form-control form-input-control" name="awarded_year"
                                            value="{{ $doctor_profile->awarded_year ?? ''}}" required>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please enter Awarded Year
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
