<!-- resources/views/about.blade.php -->
@extends('frontend.layouts.users')

@section('title', 'Hospital Ease - Home')
@include('frontend.includes.favicon')
@section('content')
    @include('frontend.includes.header')
    <main>
        <!--BANNER-->
        <section class="hero-banner bg-white">
            <div class="container container-secondary text-center">
                <h2 class="h2-title">Connecting You to the Right Care, Right Now</h2>
                <p class="sub-title">Find top hospitals and specialized treatments with just a few clicks.</p>
                <div class="row justify-content-center  mt-5">
                    <div class="col-xl-2 col-lg-3 col-md-3">
                        <select class="form-select" onchange="window.location.href=this.value;">
                            @foreach ($headerSubCategory as $option)
                                <option value="{{ route('hospital.list', ['medical_system' => strtolower($option)]) }}">
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xl-8 col-lg-8 col-md-9">
                        <form action="{{ route('hospital.all') }}" method="GET" id="hospital_search">
                            <div class="search-widget bg-white">
                                <div class="input-group">
                                    <input type="text" name="search_by_city" id="search_by_city" class="form-control"
                                        placeholder="Search your city" value="{{ request('search_by_city') }}" />
                                    <span class="v-line"></span>
                                    <input type="text" class="form-control location-input" name="search_by_location"
                                        id="search_by_location" placeholder="Search location"
                                        value="{{ request('search_by_location') }}" />
                                    <button class="btn btn-search btn-secondary" id="search_by" type="submit"><svg
                                            class="me-2" width="24" height="25" viewBox="0 0 24 25" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M21 21.5L16.65 17.15M19 11.5C19 15.9183 15.4183 19.5 11 19.5C6.58172 19.5 3 15.9183 3 11.5C3 7.08172 6.58172 3.5 11 3.5C15.4183 3.5 19 7.08172 19 11.5Z"
                                                stroke="#1E1E1E" stroke-width="2.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg><span class="d-none d-md-flex">Search</span></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </section>
        <!--/BANNER-->

        <!--EMERGENCY REQUESTS-->
        <section class="emergency-section">
            <div class="container container-secondary">
                <h2 class="text-center">Act Fast: Emergency Requests</h2>
                <!--REQUEST SLIDER-->
                <div class="request-slider">
                    @foreach ($emergencyRequests as $request)
                        <div>
                            <div class="emergency-card">
                                <div class="card-header">
                                    <img src="{{ asset('assets/frontend/images/icons/emergency-icon.svg') }}" />
                                    <div class="ms-4 me-0 me-md-4">
                                        <h3 class="card-title mb-3">{{ $request->notes }}</h3>
                                    </div>
                                </div>
                                <div class="card-footer mt-4">
                                    <time>{{ \Carbon\Carbon::parse($request->date_time)->diffForHumans() }}</time>
                                    <div class="btn-wrap">
                                        <a href="#" class="btn btn-sm btn-light">Share</a>
                                        <a href="tel:{{ $request->user->mobile ?? '' }}" class="btn btn-dark btn-sm">Help
                                            now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!--/REQUEST SLIDER-->
            </div>
        </section>
        <!--/EMERGENCY REQUESTS-->

        <!--SPACIALIZATION-->
        <section class="specialization-section">
            <div class="container container-secondary">
                <h2 class="text-center">Browse by Specialization</h2>
                <!--BLOCK-->
                <div class="row gy-5 justify-content-center">
                    @php
                        $specializations = json_decode($specializations, true);
                    @endphp
                    @foreach ($specializations as $specialization)
                        <div class="col-lg-3 text-center">
                            <a href="{{ route('hospital.all') }}/?search_by_specialization={{ $specialization['name'] }}"
                                class="specialization-block">
                                <div class="icon-widget mx-auto mb-3">
                                    <img src="{{ asset($specialization['file']) }}" />
                                </div>
                                {{ $specialization['name'] }}
                            </a>
                        </div>
                    @endforeach
                </div>
                <!--/BLOCK-->
            </div>
        </section>
        <!--/SPACIALIZATION-->

        <!--HEALTH CARE PROVIDER-->
        <section class="healthcare-provider-section position-relative">
            <div class="container container-secondary">
                <!--BLOCK-->
                <div class="row align-items-center">
                    <div class="col-lg-6 order-lg-last">
                        <img src="{{ asset('assets/frontend/images/provider-img.png') }}" class="img-widget" />
                    </div>
                    <div class="col-lg-6">
                        <h2>Hospital Ease for Healthcare Providers</h2>
                        <p>Are you a hospital looking to expand your reach? Join our platform to list your services,
                            facilities, and treatments for millions of potential patients.</p>
                        <a href="{{ route('hospitals.signup') }}" class="btn btn-primary">Register Your Hospital</a>
                    </div>
                    <!--/BLOCK-->
                </div>
            </div>

        </section>
        <!--/HEALTH CARE PROVIDER-->

        <!--SERVICES-->
        <section class="sepration-y">
            <div class="container container-secondary">
                <h2 class="text-center">More Than Just Healthcare</h2>
                <!--BLOCK-->
                <div class="row">
                    @if (!empty($businessCategory))
                        @foreach ($businessCategory as $business_cat)
                            <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                                <a href="{{ route('business_listing') }}/?search_by_category={{ $business_cat->id }}"
                                    class="services-block text-center mb-4 mb-lg-0">
                                    <img src="{{ Storage::url($business_cat->image) }}" class="img-fluid mb-3"
                                        alt="{{ $business_cat->name }}" />
                                    <p class="title mb-0">{{ $business_cat->name }}</p>
                                </a>
                            </div>
                        @endforeach
                    @endif
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-5 text-center">
                        <a href="{{ route('business-categories-all') }}" class="btn btn-primary">View All</a>
                    </div>
                </div>
                <!--/BLOCK-->
            </div>
        </section>
        <!--/SERVICES-->

        <!--WHY CHOOSE HOSPITAL EASE?-->
        <section class="sepration-y">
            <div class="container container-secondary">
                <h2 class="text-center">Why Choose Hospital Ease?</h2>
                <!--BLOCK-->
                <div class="row gy-5 mx-lg-5">
                    <div class="col-lg-6">
                        <div class="why-choose-block">
                            <div class="d-flex">
                                <div class="icon-widget">
                                    <img src="{{ asset('assets/frontend/images/icons/hospital-icon.svg') }}" />
                                </div>
                                <div class="ms-3 info-widget">
                                    <h3 class="mb-3">Hospital Listings</h3>
                                    <p>Access hospitals based on city, locality, medical treatments, and specializations
                                        like Dental, Orthopedic, Cancer, and more.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="why-choose-block">
                            <div class="d-flex">
                                <div class="icon-widget">
                                    <img src="{{ asset('assets/frontend/images/icons/record-icon.svg') }}" />
                                </div>
                                <div class="ms-3 info-widget">
                                    <h3 class="mb-3">Personal Medical Records</h3>
                                    <p>Safely upload and manage your prescriptions, X-rays, lab reports, and medical records
                                        all in one place.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="why-choose-block">
                            <div class="d-flex">
                                <div class="icon-widget">
                                    <img src="{{ asset('assets/frontend/images/icons/ambulance-icon.svg') }}" />
                                </div>
                                <div class="ms-3 info-widget">
                                    <h3 class="mb-3">Emergency Services</h3>
                                    <p>Post urgent requests for medical assistance, and your message will be prioritized and
                                        automatically removed after 12 hours.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="why-choose-block">
                            <div class="d-flex">
                                <div class="icon-widget">
                                    <img src="{{ asset('assets/frontend/images/icons/funeral-icon.svg') }}" />
                                </div>
                                <div class="ms-3 info-widget">
                                    <h3 class="mb-3">Postmortem & Funeral Services</h3>
                                    <p>Beyond medical care, we provide connections to postmortem, funeral services, and more
                                        for families in need.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/BLOCK-->
            </div>
        </section>
        <!--/WHY CHOOSE HOSPITAL EASE?-->

        <!--REQUEST SECTION-->
        <section class="sepration-bottom">
            <div class="container container-secondary">
                <div class="request-block mx-lg-5" id="EmergencyRequestForm">
                    <div class="text-center">
                        <h2>Emergency Request Section</h2>
                        <h1 class="h1-title">Need Immediate Assistance?</h1>
                        <p class="font-size-18">Post a short emergency message to get help from hospitals near you.</p>
                    </div>
                    <form action="{{ route('addEmergencyRequest') }}" method="POST">
                        @csrf
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                        @endif
                        <textarea class="form-control" name="emergency_request_note" id="emergency_request_note" rows="5"></textarea>
                        <div class="d-flex justify-content-between mt-3">
                            <span class="msg-txt">
                                Messages are automatically deleted after 12 hours.
                            </span>
                            <span class="msg-txt text-end">
                                Maximum 50 characters.
                            </span>
                        </div>

                        <div class="text-center mt-5">
                            <div class="captcha-container mb-3">
                                {!! NoCaptcha::display() !!}
                            </div>
                            <button type="submit" class="btn btn-white" name="post_emergency_request">Post
                                message</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <!--/REQUEST SECTION-->

        <!--FIND HOSPITAL SECTION-->
        <section class="sepration-bottom">
            <div class="container container-secondary">
                <div class="row justify-content-between align-items-center">
                    <div class="col-lg-4">
                        <h3>Find hospitals by city</h3>
                    </div>
                    <div class="col-lg-5 text-start text-lg-end">
                        <form method="GET" action="{{ route('hospital.all') }}">
                            <div class="search-widget">

                                <div class="input-group">
                                    <input type="text" name="search_by_city" class="form-control"
                                        placeholder="Search your city" />
                                    <button type="submit" class="btn btn-search"><svg width="24" height="25"
                                            viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M21 21.5L16.65 17.15M19 11.5C19 15.9183 15.4183 19.5 11 19.5C6.58172 19.5 3 15.9183 3 11.5C3 7.08172 6.58172 3.5 11 3.5C15.4183 3.5 19 7.08172 19 11.5Z"
                                                stroke="#1E1E1E" stroke-width="2.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg></button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

                <div class="grid-5 mt-5">
                    @if (isset($cities))
                        @foreach ($cities as $city)
                            @if ($city != '')
                                <div>
                                    <a href="{{ route('hospital.all') }}/?search_by_city={{ $city }}"
                                        class="btn city-btn">{{ $city }} <img
                                            src="{{ asset('assets/frontend/images/icons/btn-arrow.svg') }}"
                                            class="alt" /></a>
                                </div>
                            @endif
                        @endforeach
                    @endif

                </div>
            </div>
        </section>
        <!--/FIND HOSPITAL SECTION-->
    </main>
    @include('frontend.includes.footer')
    @if (session('error') || session('success') || $errors->any())
        <script type="text/javascript">
            window.onload = function() {
                var element = document.getElementById("EmergencyRequestForm");
                if (element) {
                    element.scrollIntoView({
                        behavior: "smooth",
                        block: "center"
                    });
                }
            }
        </script>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function scrollToNext() {
                $('.slick-prev').trigger('click');
            }
            setInterval(scrollToNext, 2000);
        });
    </script>
    {!! NoCaptcha::renderJs() !!}
    <style>
        .captcha-container {
            display: flex;
            justify-content: center;
            /* Centers horizontally */
            align-items: center;
            /* Centers vertically */
        }

        .g-recaptcha {
            transform: scale(1);
            /* Optional: Adjust size of the reCAPTCHA */
            -webkit-transform: scale(1);
            transform-origin: center;
            /* Ensures scaling happens from the center */
            -webkit-transform-origin: center;
        }
    </style>

@endsection
