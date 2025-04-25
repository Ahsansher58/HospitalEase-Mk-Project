<!-- resources/views/about.blade.php -->
@extends('frontend.layouts.hospitals')

@section('title', 'Hospital Ease - Login')
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
                        <h2 class="my-3 h2-title gray-90 font-medium">Welcome back!</h2>
                        <p>Don’t have an account yet? <a href="{{ route('hospitals.signup') }}" class="info-link">Sign up
                                now</a></p>
                        <button class="btn google-btn w-100 "><img
                                src="{{ asset('assets/frontend/images/icons/google-icon.svg') }}" class="me-2" />
                            Google</button>
                        <div class="sign-in-with mx-auto my-4">
                            <span>or sign in with</span>
                        </div>
                    </div>
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form class="needs-validation" action="{{ route('hospitals.login.submit') }}" method="POST" novalidate>
                        @csrf
                        <div class="mb-3">
                            <input type="text" class="form-control form-input-control" placeholder="Email or Mobile"
                                id="email" name="login" required>
                            <div class="invalid-feedback">
                                Please enter email or mobile
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="position-relative">
                                <input type="password" id="password-field" class="form-control form-input-control"
                                    placeholder="Password" id="passwordName" name="password" required>
                                <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"
                                    required></span>
                                <div class="invalid-feedback">
                                    Please enter your password
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mb-5">
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" id="remember-me">
                                <label class="form-check-label font-size-14 gray-90" for="remember-me"> Remember Me </label>
                            </div>
                            <a href="#" class="font-size-14 gray-90 link">
                                Forgot Password?
                            </a>
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
