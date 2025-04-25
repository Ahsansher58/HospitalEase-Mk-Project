@extends('frontend.layouts.after-login-users')

@section('title', 'Hospital Ease - Dashboard')
@include('frontend.includes.favicon')
@section('content')
    @include('frontend.includes.after-login-header')
    <!--MAIN-->
    <main class="inner-page">
        <!--BANNER-->
        @include('frontend.includes.user-top-banner')
        <!--/BANNER-->

        <section class="pb-5">
            <div class="container">

                <div class="row">


                    <div class="col-xl-3">
                        <!--SIDE TAB-->
                        @include('frontend.includes.user-side-navbar')
                        <!--SIDE TAB-->
                    </div>

                    <div class="col-xl-9">
                        <div class="hospital-list-block my-favourite-hospital frame">
                            <h3 class="font-medium">My Favourite Hospitals</h3>
                            @foreach ($favoriteHospitals as $favorite)
                                <!--HOSPITAL LIST BLOCK-->
                                <div class="hospital-info-widget"
                                    onclick="window.location.href='{{ route('hospital.show', $favorite->hospital->hospital_slug) }}'">

                                    <!-- Hospital Image -->
                                    @php
                                        $hospital_images = $favorite->hospital->hospital_images;
                                        if (isset($hospital_images)) {
                                            $hospital_images = json_decode($favorite->hospital->hospital_images, true);
                                        }
                                        if (isset($hospital_images) and isset($hospital_images['images'][0])) {
                                            $hospital_image = $hospital_images['images'][0];
                                        } else {
                                            $hospital_image = asset('assets/frontend/images/hospital-img.png');
                                        }
                                    @endphp
                                    <img src="{{ $hospital_image }}" class="img-fluid rounded-24" />

                                    <div class="w-100">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <!-- Hospital Name -->
                                            <h3 class="mb-0">{{ $favorite->hospital->hospital_name }}</h3>

                                            <!-- Favorite Button -->
                                            <button class="btn btn-like active">
                                                <i class="fa fa-heart" aria-hidden="true"></i>
                                                <i class="fa fa-heart-o" aria-hidden="true"></i>
                                            </button>
                                        </div>

                                        <!-- Rating -->
                                        <div class="d-flex"
                                            onclick="window.location.href='{{ route('hospital.show', $favorite->hospital->hospital_slug) }}'">
                                            <div class="rating me-3">
                                                <i class="fa fa-star active" aria-hidden="true"></i>
                                                <i class="fa fa-star active" aria-hidden="true"></i>
                                                <i class="fa fa-star active" aria-hidden="true"></i>
                                                <i class="fa fa-star active" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                            </div>
                                            <span class="font-size-14 font-medium text-secondary"
                                                onclick="window.location.href='{{ route('hospital.show', $favorite->hospital->hospital_slug) }}'">4.8
                                                by 120
                                                patients</span>
                                        </div>

                                        <!-- departments -->
                                        <ul class="chip mt-2 mb-2"
                                            onclick="window.location.href='{{ route('hospital.show', $favorite->hospital->hospital_slug) }}'">
                                            @php
                                                $departments = json_decode($favorite->hospital->departments);
                                            @endphp

                                            @if ($departments && is_array($departments))
                                                @foreach ($departments as $index => $department)
                                                    @if ($index < 4)
                                                        <li class="border-0 chip-bg-light">{{ $department }}
                                                        </li>
                                                    @endif
                                                @endforeach

                                                @if (count($departments) > 4)
                                                    <li class="border-0 chip-bg-light">
                                                        +{{ count($departments) - 4 }}</li>
                                                @endif
                                            @else
                                                <li class="border-0 chip-bg-light">No departments available</li>
                                            @endif
                                        </ul>

                                        <!-- Address -->
                                        <div class="d-flex mb-3 align-items-start"
                                            onclick="window.location.href='{{ route('hospital.show', $favorite->hospital->hospital_slug) }}'">
                                            @if ($favorite->hospital->location != '')
                                                <img src="{{ asset('assets/frontend/images/icons/marker.svg') }}"
                                                    alt="" class="me-3">
                                                <span
                                                    class="font-size-14 font-medium text-secondary">{{ $favorite->hospital->location }}</span>
                                            @endif

                                        </div>

                                        <!-- Facilities -->
                                        <div class="d-flex align-items-start"
                                            onclick="window.location.href='{{ route('hospital.show', $favorite->hospital->hospital_slug) }}'">
                                            <img src="{{ asset('assets/frontend/images/icons/address-icon.svg') }}"
                                                alt="" class="me-3">
                                            <span class="font-size-14 font-medium text-secondary">Emergency
                                                Services | ICU | Ambulance |
                                                Pharmacy | Parking</span>
                                        </div>
                                    </div>
                                </div>
                                <!--/HOSPITAL LIST BLOCK-->
                            @endforeach

                        </div>
                    </div>
                </div>

            </div>
        </section>

    </main>
    <!--/MAIN-->
    @include('frontend.includes.user-footer')
@endsection
