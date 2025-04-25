@extends('frontend.layouts.hospitals')

@section('title', 'Hospital Ease - ' . $business->business_name)
@section('favicon')
    @include('frontend.includes.favicon')
    <style>
        .social-media-links p {
            margin: 10px 0;
        }

        .social-link {
            text-decoration: none;
            font-size: 16px;
            color: white;
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            font-weight: bold;
        }

        .social-link i {
            margin-right: 8px;
            font-size: 20px;
        }

        /* Button Styles */
        .social-link.whatsapp {
            background-color: #25d366;
        }

        .social-link.facebook {
            background-color: #3b5998;
        }

        .social-link.instagram {
            background-color: #e4405f;
        }

        .social-link.youtube {
            background-color: #ff0000;
        }

        .social-link.linkedin {
            background-color: #0077b5;
        }

        /* Hover Effects */
        .social-link:hover {
            background-color: #333;
            transform: translateY(-2px);
        }

        /* Hover Effects for each platform */
        .social-link.whatsapp:hover {
            background-color: #128c7e;
        }

        .social-link.facebook:hover {
            background-color: #2d4373;
        }

        .social-link.instagram:hover {
            background-color: #c13584;
        }

        .social-link.youtube:hover {
            background-color: #cc0000;
        }

        .social-link.linkedin:hover {
            background-color: #00679a;
        }
    </style>
@endsection

@section('content')
    @if (Auth::check())
        @include('frontend.includes.after-login-header')
    @else
        @include('frontend.includes.inner-header')
    @endif
    <!--MAIN-->
    <main class="inner-page">
        <!--BANNER-->
        <section class="inner-banner py-5">
            <div class="container">
                <!--Block-->
                <div class="row g-lg-5">
                    <div class="col-lg-5">
                        @php
                            if (isset($business->banner_image)) {
                                $business_image = asset('storage/' . $business->banner_image);
                            } else {
                                $business_image = asset('assets/frontend/images/hospital-img.png');
                            }
                        @endphp
                        <img src="{{ $business_image }}" alt="{{ $business->business_name }}"
                            class="img-fluid rounded-24 w-100" />
                    </div>
                    <div class="col-lg-7">

                        <div class="pt-4 pt-lg-0">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h2 class="mb-0">{{ $business->business_name }}</h2>
                            </div>

                            <div class="bg-white p-3 rounded-8 d-flex align-items-center">
                                <div class="info-text">
                                    {!! substr($business->description, 0, 300) . (strlen($business->description) > 300 ? '...' : '') !!}
                                </div>
                            </div>

                            <ul class="chip mt-4 mb-2">
                                @php
                                    $cat_names = $business->cat_name;
                                @endphp

                                @if ($cat_names && is_array($cat_names))
                                    @foreach ($cat_names as $index => $cat_name)
                                        @if ($index < 4)
                                            <li class="border-0 chip-bg-light">{{ $cat_name }}
                                            </li>
                                        @endif
                                    @endforeach

                                    @if (count($cat_names) > 4)
                                        <li class="border-0 chip-bg-light">
                                            +{{ count($cat_names) - 4 }}</li>
                                    @endif
                                @else
                                    <li class="border-0 chip-bg-light">No category available</li>
                                @endif
                            </ul>
                            <a href="{{ $business->website }}" class="btn btn-info">Website</a>

                        </div>
                    </div>
                </div>
                <!--/Block-->
            </div>
        </section>
        <!--/BANNER-->

        <section class="sepration-top">
            <div class="container">
                <!-- Nav tabs -->
                <div class="scroll-x">
                    <ul class="nav nav-tabs nav-justified">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#tab1">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab3">Images</a>
                        </li>
                    </ul>
                </div>
                <!--Block-->
                <div class="row">
                    <div class="col-lg-8">
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1">
                                <h4>About</h4>
                                <p>{!! $business->description !!}</p>

                                <div class="row">
                                    <div class="col-xl-3 col-sm-6 col-12">
                                        <small>Phone</small>
                                        <p>{{ $business->mobile_number }}</p>
                                    </div>
                                    <div class="col-xl-3 col-sm-6 col-12">
                                        <small>Emergency</small>
                                        <p>{{ $business->mobile_number }}</p>
                                    </div>
                                    <div class="col-xl-3 col-sm-6 col-12">
                                        <small>Website</small>
                                        <p>{{ $business->website }}</p>
                                    </div>
                                </div>
                                <div class="social-media-links">
                                    <a href="tel:{{ $business->whatsapp_number }}" class="social-link whatsapp"><i
                                            class="fa fa-whatsapp"></i></a>
                                    <a href="{{ $business->facebook_link }}" class="social-link facebook"><i
                                            class="fa fa-facebook-f"></i></a>
                                    <a href="{{ $business->instagram_link }}" class="social-link instagram"><i
                                            class="fa fa-instagram"></i></a>
                                    <a href="{{ $business->youtube_link }}" class="social-link youtube"><i
                                            class="fa fa-youtube"></i></a>
                                    <a href="{{ $business->linkedin_link }}" class="social-link linkedin">IN</a>
                                </div>

                                <div class="row gy-4 align-items-center justify-content-between">
                                    <div class="col-sm-5">
                                        <h4>Location</h4>
                                        <p>{{ $business->business_address }}, {{ $business->city }},
                                            {{ $business->state }}
                                            ,
                                            {{ $business->country }}</p>

                                        <a href="{{ $business->google_map_url }}" class="btn btn-info">Get Direction</a>
                                    </div>
                                    <div class="col-sm-6">
                                        <img src="{{ asset('assets/frontend/images/map-2.png') }}"
                                            class="img-fluid rounded-16 mb-4" />
                                    </div>
                                </div>
                            </div>


                            <div class="tab-pane fade" id="tab3">
                                <h4>Images</h4>
                                <ul class="chip mb-4">
                                    @if (isset($business->banner_image))
                                        <li><img src="{{ asset('storage/' . $business->banner_image) }}"
                                                class="img-fluid me-2" style="width: 200px" />
                                        </li>
                                    @endif

                                    @php
                                        $images = json_decode($business->photos, true);
                                    @endphp

                                    @if ($images && is_array($images))
                                        @foreach ($images as $image)
                                            <li><img src="{{ asset('storage/' . $image) }}" class="img-fluid me-2"
                                                    style="width: 200px" />
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 d-none d-lg-block">
                        <div class="px-4 py-5">
                            <img src="{{ asset('assets/frontend/images/map.png') }}" class="img-fluid rounded-8 mb-4" />
                            @if ($advertisement)
                                @php
                                    $imageTag =
                                        $advertisement->option == 1
                                            ? '<img src="' .
                                                asset('storage/' . $advertisement->image_code) .
                                                '" alt="' .
                                                $advertisement->campaign_name .
                                                '" class="img-fluid rounded-12 d-none d-xl-block" >'
                                            : $advertisement->image_code;
                                @endphp
                                <p>{!! $imageTag !!}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <!--/Block-->
            </div>
        </section>
    </main>
    <!--/MAIN-->
    @include('frontend.includes.footer')
@endsection
