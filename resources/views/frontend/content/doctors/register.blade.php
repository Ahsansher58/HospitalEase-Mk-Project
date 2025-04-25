<!-- resources/views/about.blade.php -->
@extends('frontend.layouts.doctors')
@section('title', 'Hospital Ease - Doctor login')
@include('frontend.includes.favicon')
@section('content')
    <main>
        <section class="authentication-wrapper">
            <div class="d-flex col-lg-9 col-12 align-items-center">
                <div class="w-px-430 mx-auto py-5 px-3">
                    <div class="text-center">
                        <img src="{{ asset('assets/frontend/images/logo-active.svg') }}" />
                        <h2 class="my-3 h2-title gray-90 font-medium">Create an account</h2>
                        <p>Already have an account? <a href="{{ route('doctors.login') }}" class="info-link">Sign in now</a>
                        </p>


                    </div>
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
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

                    <form class="needs-validation" action="{{ route('doctors.register.submit') }}" method="POST" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label class="mb-1">Email</label>
                            <input type="email" class="form-control form-input-control" placeholder="Email" name="email"
                                required>
                            <div class="invalid-feedback">
                                Please enter email
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-6 mb-3 mb-sm-1">
                                <label class="mb-1">First Name</label>
                                <input type="text" class="form-control form-input-control" placeholder="First Name"
                                    name="firstName" required>
                                <div class="invalid-feedback">
                                    Please enter first name
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="mb-1">Last Name</label>
                                <input type="text" class="form-control form-input-control" placeholder="Last Name"
                                    name="lastName" required>
                                <div class="invalid-feedback">
                                    Please enter last name
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="mb-1">Mobile</label>
                            <input type="number" class="form-control form-input-control" placeholder="Mobile Number"
                                name="mobile" maxlength="10" required
                                oninput="if(this.value.length > 10) this.value = this.value.slice(0, 10);">
                            <div class="invalid-feedback">
                                Please enter mobile number
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="mb-1">Date of Birth</label>
                            <input type="date" class="form-control form-input-control" name="dob" required>
                            <div class="invalid-feedback">
                                Please enter Date of Birth
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="mb-1">Gender</label>
                            <div class="row justify-content-between g-2">
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="gender" id="male" value="male"
                                        autocomplete="off" required>
                                    <label class="btn btn-outline-default w-100" for="male">Male</label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="gender" id="female" value="female"
                                        autocomplete="off" required>
                                    <label class="btn btn-outline-default w-100" for="female">Female</label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="gender" id="other" value="other"
                                        autocomplete="off" required>
                                    <label class="btn btn-outline-default w-100" for="other">Other</label>
                                    <div class="invalid-feedback">
                                        Select gender
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 font-size-14">
                            By signing up, you’re accepting our <a href="#" class="info-link">Terms</a> and <a
                                href="#" class="info-link">Privacy policies</a>
                        </div>
                        <div class="mb-3">
                            {!! NoCaptcha::display() !!}
                        </div>
                        <button class="btn btn-info w-100" type="submit">Continue</button>
                    </form>


                    <div class="text-center">
                        <div class="sign-in-with mx-auto my-5">
                            <span>or sign up with</span>
                        </div>
                        <button class="btn google-btn w-100 "><img
                                src="{{ asset('assets/frontend/images/icons/google-icon.svg') }}" class="me-2" />
                            Google</button>
                    </div>

                </div>
            </div>
            <div class="d-none d-lg-flex col-lg-3 justify-content-end auth-cover-bg">
                <img src="{{ asset('assets/frontend/images/mic-bg.png') }}" class="img-fluid clip-img" />
            </div>
        </section>
    </main>
    {!! NoCaptcha::renderJs() !!}
    <script>
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
@endsection
