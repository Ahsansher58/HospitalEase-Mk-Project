@extends('frontend.layouts.hospitals')
@section('title', 'Hospital Ease - Signup')
@include('frontend.includes.favicon')
@section('content')

    <!--MAIN-->
    <main>
        <section class="authentication-wrapper">
            <a href="hospital-signup.php" class="btn back-arrow position-absolute">
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

                    <div class="text-center mb-4">
                        <img src="{{ asset('assets/frontend/images/logo-active.svg') }}" />
                        <h2 class="my-3 h2-title gray-90 font-medium">Verify OTP</h2>
                        <p>Enter 5 digit code sent to <a href="#" class="info-link">{{ session('otp_email') }}</a></p>

                    </div>

                    <form class="needs-validation" action="{{ route('hospitals.verifyOtp') }}" method="POST" novalidate
                        id="otp-form">
                        @csrf
                        <input type="hidden" name="email" value="{{ session('otp_email') }}">
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

                        <!-- OTP Input Fields -->
                        <div class="mb-4">
                            <div class="row g-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <div class="col">
                                        <input type="text" class="form-control form-input-control text-center otp-input"
                                            placeholder="_" maxlength="1" required>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <!-- Resend Code Timer -->
                        {{-- <p class="text-center text-black">Resend code in 1:23 s</p> --}}
                        <p class="text-center text-black">
                            Didn't receive the code?
                            <button type="button" id="resend-otp" class="btn btn-link p-0">Resend OTP</button>
                        </p>

                        <!-- Submit Button -->
                        <button class="btn btn-info w-100" type="submit">Verify OTP</button>

                        <!-- Hidden OTP Field -->
                        <input type="hidden" name="otp" id="otp-hidden-input">
                    </form>
                </div>
            </div>
        </section>
    </main>
    <!--/MAIN-->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const otpInputs = document.querySelectorAll('.otp-input');
            const hiddenOtpInput = document.getElementById('otp-hidden-input');

            // Move focus to the next input after entering a digit
            otpInputs.forEach((input, index) => {
                input.addEventListener('input', () => {
                    if (input.value.length === 1 && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                    updateHiddenOtp();
                });

                // Allow moving back with backspace
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && input.value === '' && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                });
            });

            // Combine OTP inputs into the hidden input
            function updateHiddenOtp() {
                hiddenOtpInput.value = Array.from(otpInputs).map(input => input.value).join('');
            }
        });
    </script>
    <script>
        document.getElementById('resend-otp').addEventListener('click', function() {
            const email = '{{ session('otp_email') ?? request('email') }}'; // Get the email

            fetch('{{ route('hospitals.resendOtp') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message); // Show success message
                    } else {
                        alert(data.message); // Show error message
                    }
                })
                .catch(error => {
                    alert('An error occurred: ' + error.message);
                });
        });
    </script>

@endsection
