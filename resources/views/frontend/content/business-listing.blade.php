@extends('frontend.layouts.hospitals')

@section('title', 'Hospital Ease - business-listing')
@section('favicon')
    @include('frontend.includes.favicon')
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
        <section class="inner-banner search-hopital-list py-4">
            <div class="container">
                <!--Block-->
                <form action="{{ route('business_listing') }}" method="GET" id="business_search">
                    <div class="row justify-content-between g-3">
                        <div class="col-xl-7 col-lg-7 col-md-9">
                            <div class="search-widget bg-white">
                                <div class="input-group">
                                    <input type="text" name="search_by" id="search_by" class="form-control"
                                        placeholder="Search by" value="{{ request('search_by') }}" />
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
                        </div>
                        {{-- <div class="col-xl-3 col-lg-2 col-md-3">
                            <div class="dropdown d-flex justify-content-end filter-dropdown">
                                <button class="btn filter-btn dropdown-toggle" type="button" id="dropdownMenuButton1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="{{ asset('assets/frontend/images/icons/filter-icon.svg') }}" class="me-2" />
                                    Filter
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="#">Price</a></li>
                                    <li><a class="dropdown-item" href="#">Location</a></li>
                                    <li><a class="dropdown-item" href="#">Rating</a></li>
                                </ul>
                            </div>
                        </div> --}}
                    </div>
                </form>
                <!--/Block-->
            </div>
        </section>
        <!--/BANNER-->
        <section class="hospital-list-section position-relative">
            <div class="container">
                <!--BLOCK-->
                <div class="row">
                    <div class="col-lg-8 pe-0">
                        <div class="py-5">
                            <h3>{{ $totalCount }} business Listings</h3>
                            <div class="hospital-list-block">
                                <div class="hospital-list">
                                    @if ($businessListings->isEmpty())
                                        <div class="no-hospitals-message">
                                            <p>No business Listings found matching your criteria.</p>
                                        </div>
                                    @else
                                        @foreach ($businessListings as $business)
                                            <div class="hospital-info-widget">
                                                @php
                                                    if (isset($business->banner_image)) {
                                                        $business_image = asset('storage/' . $business->banner_image);
                                                    } else {
                                                        $business_image = asset(
                                                            'assets/frontend/images/hospital-img.png',
                                                        );
                                                    }
                                                @endphp
                                                <img src="{{ $business_image }}" class="img-fluid rounded-24"
                                                    onclick="window.location.href='{{ route('business.show', ['slug' => $business->slug]) }}'" />
                                                <div class="w-100">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <h3 class="mb-0"
                                                            onclick="window.location.href='{{ route('business.show', ['slug' => $business->slug]) }}'">
                                                            <p>{{ $business->business_name ?? '' }}</p>
                                                        </h3>

                                                    </div>
                                                    <ul class="chip mt-2 mb-2"
                                                        onclick="window.location.href='{{ route('business.show', $business->slug) }}'">
                                                        @if (!empty($business->cat_name))
                                                            @foreach ($business->cat_name as $index => $category)
                                                                @if ($index < 4)
                                                                    <li class="border-0 chip-bg-light">
                                                                        {{ $category }}
                                                                    </li>
                                                                @endif
                                                            @endforeach

                                                            @if (count($business->cat_name) > 4)
                                                                <li class="border-0 chip-bg-light">
                                                                    +{{ count($business->cat_name) - 4 }}
                                                                </li>
                                                            @endif
                                                        @else
                                                            <li class="border-0 chip-bg-light">No categories available</li>
                                                        @endif
                                                    </ul>


                                                    <div class="d-flex mb-3 align-items-start"
                                                        onclick="window.location.href='{{ route('business.show', $business->slug) }}'">
                                                        @if ($business->business_address != '')
                                                            <img src="{{ asset('assets/frontend/images/icons/marker.svg') }}"
                                                                alt="" class="me-3">
                                                            <span
                                                                class="font-size-14 font-medium text-secondary">{{ $business->business_address }}
                                                                | {{ $business->city }} |
                                                                {{ $business->state }} |
                                                                {{ $business->country }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="btn-wrap">
                                                        <a href="tel:{{ $business->whatsapp_number }}"
                                                            class="btn btn-outline-info font-size-16">Call Now</a>
                                                        <a href="{{ $business->website }}"
                                                            class="btn btn-outline-info font-size-16 ms-3"
                                                            target="_blank">Visit
                                                            Website</a>
                                                        <a href="javascript:void(0)"
                                                            onclick="window.location.href='{{ route('business.show', $business->slug) }}'"
                                                            class="btn btn-info ms-3 font-size-16">More
                                                            details</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                            </div>

                            <!--PAGINATION-->
                            <nav class="mt-5">
                                {{ $businessListings->links('pagination::bootstrap-4') }}
                            </nav>
                            <!--/PAGINATION-->
                        </div>
                    </div>

                    <div class="col-lg-4 d-none d-lg-block bg-gradient-dark">
                        <div class="sidebar mt-3 search-hopital-list">
                            <div class="accordion" id="categoriesAccordion">
                                @foreach ($categories as $mainIndex => $mainCategory)
                                    <div class="accordion-item mb-3">
                                        <h2 class="accordion-header" id="heading-{{ $mainCategory['id'] }}">
                                            <button class="accordion-button {{ $mainIndex === 0 ? '' : 'collapsed' }}"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapse-{{ $mainCategory['id'] }}"
                                                aria-expanded="{{ $mainIndex === 0 ? 'true' : 'false' }}"
                                                aria-controls="collapse-{{ $mainCategory['id'] }}">
                                                {{ $mainCategory['name'] }}
                                            </button>
                                        </h2>
                                        <div id="collapse-{{ $mainCategory['id'] }}"
                                            class="accordion-collapse collapse {{ $mainIndex === 0 ? 'show' : '' }}"
                                            aria-labelledby="heading-{{ $mainCategory['id'] }}"
                                            data-bs-parent="#categoriesAccordion">
                                            <div class="accordion-body">
                                                @if (!empty($mainCategory['subcategories']))
                                                    @foreach ($mainCategory['subcategories'] as $subCategory)
                                                        <a href="{{ route('business_listing') }}/?search_by_category={{ $subCategory['id'] }}"
                                                            class="d-block mb-1">{{ $subCategory['name'] }}</a>
                                                    @endforeach
                                                @else
                                                    <p class="text-muted mb-0">No subcategories available</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
                <!--/BLOCK-->
            </div>

        </section>

    </main>

    @include('frontend.includes.footer')
    <script>
        // Toggle the visibility of subcategories
        function toggleSubcategories(mainCategoryId) {
            var subcategories = document.getElementById('subcategories-' + mainCategoryId);
            if (subcategories.style.display === 'none') {
                subcategories.style.display = 'block';
            } else {
                subcategories.style.display = 'none';
            }
        }
    </script>

@endsection
