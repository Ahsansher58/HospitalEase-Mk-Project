<!-- resources/views/about.blade.php -->
@extends('frontend.layouts.users')

@section('title', 'Hospital Ease - All business categories')
@include('frontend.includes.favicon')
@section('content')
    @if (Auth::check())
        @include('frontend.includes.after-login-header')
    @else
        @include('frontend.includes.inner-header')
    @endif
    <main class="inner-page">
        <!--BANNER-->
        <section class="inner-banner search-hopital-list py-4">
            <div class="container">
                <!--Block-->
                <form action="{{ route('business_listing') }}" method="GET" id="business_search">
                    <div class="row justify-content-center">
                        <div class="col-xl-8 col-lg-8 col-md-8">
                            <h2 class="text-center mt-3">All Business Categories</h2>
                            <div class="search-widget bg-white">
                                <div class="input-group">
                                    <input type="text" name="search_by_city" id="search_by_city" class="form-control"
                                        placeholder="Search your city" value="{{ request('search_by_city') }}" />
                                    <span class="v-line"></span>
                                    <input type="text" class="form-control location-input" name="search_by_location"
                                        id="search_by_location" placeholder="Search location"
                                        value="{{ request('search_by_location') }}" />
                                    <button class="btn btn-search btn-secondary" id="search_by" type="submit">
                                        <svg class="me-2" width="24" height="25" viewBox="0 0 24 25" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M21 21.5L16.65 17.15M19 11.5C19 15.9183 15.4183 19.5 11 19.5C6.58172 19.5 3 15.9183 3 11.5C3 7.08172 6.58172 3.5 11 3.5C15.4183 3.5 19 7.08172 19 11.5Z"
                                                stroke="#1E1E1E" stroke-width="2.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg><span class="d-none d-md-flex">Search</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!--/Block-->
            </div>
        </section>
        <!--/BANNER-->
        <!--SERVICES-->
        <section class="sepration-y">
            <div class="container container-secondary">
                <!--BLOCK-->
                <div class="row">
                    @if (!empty($businessCategory))
                        @foreach ($businessCategory as $business_cat)
                            <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                                <a href="{{ route('business_listing_cat', ['slug' => Str::slug($business_cat->name)]) }}"
                                    class="services-block text-center mb-4 mb-lg-0">
                                    <img src="{{ Storage::url($business_cat->image) }}" class="img-fluid mb-3"
                                        alt="{{ $business_cat->name }}" />
                                    <p class="title mb-0">{{ $business_cat->name }}</p>
                                </a>
                            </div>
                        @endforeach
                    @endif
                </div>
                <!--/BLOCK-->
            </div>
        </section>
        <!--/SERVICES-->
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
@endsection
