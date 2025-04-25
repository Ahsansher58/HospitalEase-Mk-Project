@extends('frontend.layouts.hospitals')
@section('title', 'Hospital Ease - Signup')
@include('frontend.includes.favicon')
@section('content')
    <!--MAIN-->
    <main>
        <section class="authentication-wrapper">
            <a href="/" class="btn back-arrow position-absolute">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.6665 15.9999H25.3332" stroke="" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M6.6665 16L14.6665 24" stroke="" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M6.6665 16L14.6665 8" stroke="" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </a>
            <div class="d-none d-lg-flex col-lg-7 justify-content-end auth-cover-bg auth-cover-hospitalbg">

            </div>
            <div class="d-flex col-lg-5 col-12 align-items-center">
                <div class="w-px-430 mx-auto py-5 px-3">

                    <div class="text-center">
                        <img src="{{ asset('assets/frontend/images/logo-active.svg') }}" />
                        <h2 class="my-3 h2-title gray-90 font-medium">Create an Account</h2>
                        <p>Already Registered ? <a href="{{ route('hospital.login') }}" class="info-link">Sign in now</a>
                        </p>
                        <button class="btn google-btn w-100 "><img
                                src="{{ asset('assets/frontend/images/icons/google-icon.svg') }}" class="me-2" />
                            Google</button>
                        <div class="sign-in-with mx-auto my-4">
                            <span>or continue with</span>
                        </div>
                    </div>
                    <!-- Display Errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form class="needs-validation" action="{{ route('hospitals.signup.submit') }}" method="POST"
                        novalidate>
                        @csrf
                        <div class="mb-4">
                            <input type="email" class="form-control form-input-control" placeholder="Email" id="email"
                                name="email" required>
                            <div class="invalid-feedback">
                                Please enter a valid email address
                            </div>
                        </div>
                        <div class="mb-4">
                            <input type="text" class="form-control form-input-control" placeholder="Mobile"
                                id="mobile" name="mobile" required>
                            <div class="invalid-feedback">
                                Please enter mobile number
                            </div>
                        </div>
                        <div class="mb-4">
                            <input type="text" class="form-control form-input-control" placeholder="Hospital Name"
                                id="hospital_name" name="hospital_name" required>
                            <div class="invalid-feedback">
                                Please enter your Hospital name
                            </div>
                        </div>
                        <div class="mb-4">
                            <input type="password" class="form-control form-input-control" placeholder="Password"
                                id="password" name="password" required>
                            <div class="invalid-feedback">
                                Please enter your password
                            </div>
                        </div>
                        <div class="mb-4">
                            <input type="password" class="form-control form-input-control" placeholder="Confirm Password"
                                id="password_confirmation" name="password_confirmation" required>
                            <div class="invalid-feedback">
                                Please confirm your password
                            </div>
                        </div>
                        <div class="mb-3">
                            {!! NoCaptcha::display() !!}
                        </div>
                        <button class="btn btn-info w-100" type="submit">Sign In</button>
                    </form>


                </div>
            </div>
        </section>
    </main>
    <!--/MAIN-->
    {!! NoCaptcha::renderJs() !!}
    <script>
        // Apply Bootstrap validation styles to the form
        (function() {
            'use strict';

            // Fetch all forms with class 'needs-validation'
            const forms = document.querySelectorAll('.needs-validation');

            // Loop over each form and prevent submission if invalid
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>

@endsection
