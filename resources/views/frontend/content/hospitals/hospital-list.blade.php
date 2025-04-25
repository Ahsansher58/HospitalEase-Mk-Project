@extends('frontend.layouts.hospitals')

@section('title', 'Hospital Ease - Listing')
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
                <form action="{{ url()->current() }}" method="GET" id="hospital_search">
                    <div class="row justify-content-between g-3">
                        <div class="col-xl-2 col-lg-3">
                            <select class="form-select" onchange="window.location.href=this.value;">
                                @foreach ($headerSubCategory as $option)
                                    @php
                                        $route = route('hospital.list', ['medical_system' => strtolower($option)]);
                                        $selected = request()->is('*' . strtolower($option)) ? 'selected' : '';
                                    @endphp
                                    <option value="{{ $route }}" {{ $selected }}>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-7 col-lg-7 col-md-9">
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
                        </div>
                        <div class="col-xl-3 col-lg-2 col-md-3">
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
                    <div class="mt-4 small-filter">
                        <div class="row g-2">
                            <div class="col">
                                <select class="selectpicker form-select facilities-picker" title="Facilities Available"
                                    name="search_by_facilities[]" id="search_by_facilities"
                                    onchange="document.getElementById('hospital_search').submit();" multiple>
                                    <option value="all"
                                        {{ in_array('all', request('search_by_facilities', [])) ? 'selected' : '' }}>All
                                    </option>
                                    @php
                                        $all_facilities = json_decode($all_facilities, true);

                                        if (request('search_by_facilities') !== null) {
                                            $selectedFacilities = request('search_by_facilities');
                                        } else {
                                            $selectedFacilities = [];
                                        }

                                        if (is_array($all_facilities)) {
                                            usort($all_facilities, function ($a, $b) {
                                                return strcmp($a['name'], $b['name']);
                                            });
                                            foreach ($all_facilities as $facility) {
                                                $facilityName = is_array($facility)
                                                    ? $facility['name'] ?? ''
                                                    : $facility;

                                                if ($facilityName != '') {
                                                    $isSelected = in_array($facilityName, $selectedFacilities)
                                                        ? 'selected'
                                                        : '';
                                                    echo '<option value="' .
                                                        htmlspecialchars($facilityName) .
                                                        '" ' .
                                                        $isSelected .
                                                        '>' .
                                                        htmlspecialchars($facilityName) .
                                                        '</option>';
                                                }
                                            }
                                        } else {
                                            echo '<option disabled>No facilities found.</option>';
                                        }
                                    @endphp
                                </select>
                            </div>
                            <div class="col">
                                <select class="selectpicker form-select specialization-picker"
                                    title="Specialization Available" name="search_by_specialization[]"
                                    id="search_by_specialization"
                                    onchange="document.getElementById('hospital_search').submit();" multiple>
                                    <option value="all"
                                        {{ in_array('all', request('search_by_specialization', [])) ? 'selected' : '' }}>All
                                    </option>
                                    @php
                                        $all_specialization = json_decode($all_specialization, true);

                                        if (request('search_by_specialization') !== null) {
                                            $selectedSpecialization = request('search_by_specialization');
                                        } else {
                                            $selectedSpecialization = [];
                                        }

                                        if (is_array($all_specialization)) {
                                            usort($all_specialization, function ($a, $b) {
                                                return strcmp($a['name'], $b['name']);
                                            });
                                            foreach ($all_specialization as $specialization) {
                                                $specializationName = is_array($specialization)
                                                    ? $specialization['name'] ?? ''
                                                    : $specialization;

                                                if ($specializationName != '') {
                                                    $isSelected = in_array($specializationName, $selectedSpecialization)
                                                        ? 'selected'
                                                        : '';
                                                    echo '<option value="' .
                                                        htmlspecialchars($specializationName) .
                                                        '" ' .
                                                        $isSelected .
                                                        '>' .
                                                        htmlspecialchars($specializationName) .
                                                        '</option>';
                                                }
                                            }
                                        } else {
                                            echo '<option disabled>No specialization found.</option>';
                                        }
                                    @endphp
                                </select>
                            </div>
                            <div class="col">
                                <select class="selectpicker form-select" title="Distance">
                                    <option>.5km - 1km</option>
                                    <option>2km - 4km</option>
                                    <option>5km - 8km</option>
                                </select>
                            </div>
                            <div class="col">
                                <select class="selectpicker form-select" title="Ratings" name="search_by_rating"
                                    id="search_by_rating" onchange="document.getElementById('hospital_search').submit();">
                                    <option value="low_to_high"
                                        {{ request('search_by_rating') == 'low_to_high' ? 'selected' : '' }}>Low to High
                                    </option>
                                    <option value="high_to_low"
                                        {{ request('search_by_rating') == 'high_to_low' ? 'selected' : '' }}>High to Low
                                    </option>
                                </select>
                            </div>
                        </div>

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
                    <div class="col-lg-7 pe-0">
                        <div class="py-5">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <h3>{{ $totalCount }} hospitals</h3>
                            <div class="hospital-list-block">
                                <div class="hospital-list">
                                    @if ($hospitals->isEmpty())
                                        <div class="no-hospitals-message">
                                            <p>No hospitals found matching your criteria.</p>
                                        </div>
                                    @else
                                        @foreach ($hospitals as $hospital)
                                            <div class="hospital-info-widget">
                                                @php
                                                    $hospital_images = $hospital->hospital_images;
                                                    if (isset($hospital_images)) {
                                                        $hospital_images = json_decode(
                                                            $hospital->hospital_images,
                                                            true,
                                                        );
                                                    }
                                                @endphp
                                                @php
                                                    if (
                                                        isset($hospital_images) and isset($hospital_images['images'][0])
                                                    ) {
                                                        $hospital_image = $hospital_images['images'][0];
                                                    } else {
                                                        $hospital_image = asset('assets/img/no_image.jpg');
                                                    }
                                                @endphp
                                                <img src="{{ $hospital_image }}" class="img-fluid rounded-24"
                                                    onclick="window.location.href='{{ route('hospital.show', $hospital->hospital_slug) }}'" />
                                                <div class="w-100">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <h3 class="mb-0"
                                                            onclick="window.location.href='{{ route('hospital.show', $hospital->hospital_slug) }}'">
                                                            <p>{{ $hospital->hospital_name ?? '' }}</p>
                                                        </h3>
                                                        <button
                                                            class="btn btn-like favorite-btn @if (in_array($hospital->hospital_id, $favoriteHospitals)) active @endif"
                                                            id="favorite-btn-{{ $hospital->hospital_id }}"
                                                            data-hospital-id="{{ $hospital->hospital_id }}">
                                                            <i class="fa fa-heart" aria-hidden="true"></i>
                                                            <i class="fa fa-heart-o" aria-hidden="true"></i>
                                                        </button>
                                                    </div>

                                                    <div class="d-flex"
                                                        onclick="window.location.href='{{ route('hospital.show', $hospital->hospital_slug) }}'">
                                                        <div class="rating me-3">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <i class="fa fa-star {{ $i <= $hospital->averageRating ? 'active' : '' }}"
                                                                    aria-hidden="true"></i>
                                                            @endfor
                                                        </div>
                                                        <span class="font-size-14 font-medium text-secondary"
                                                            onclick="window.location.href='{{ route('hospital.show', $hospital->hospital_slug) }}'">{{ $hospital->averageRating }}
                                                            by {{ $hospital->reviewsCount }}
                                                            users</span>
                                                    </div>
                                                    <ul class="chip mt-2 mb-2"
                                                        onclick="window.location.href='{{ route('hospital.show', $hospital->hospital_slug) }}'">
                                                        @php
                                                            $specialization = json_decode($hospital->specialization);
                                                        @endphp

                                                        @if ($specialization && is_array($specialization))
                                                            @php
                                                                sort($specialization);
                                                            @endphp
                                                            @foreach ($specialization as $index => $department)
                                                                <li class="border-0 chip-bg-light">{{ $department }}
                                                                </li>
                                                            @endforeach
                                                        @else
                                                            <li class="border-0 chip-bg-light">No specialization available
                                                            </li>
                                                        @endif
                                                    </ul>

                                                    </ul>
                                                    <div class="d-flex mb-3 align-items-start"
                                                        onclick="window.location.href='{{ route('hospital.show', $hospital->hospital_slug) }}'">
                                                        @if ($hospital->location != '')
                                                            <img src="{{ asset('assets/frontend/images/icons/marker.svg') }}"
                                                                alt="" class="me-3">
                                                            <span
                                                                class="font-size-14 font-medium text-secondary">{{ $hospital->location }}</span>
                                                        @endif

                                                    </div>
                                                    <div class="d-flex align-items-start"
                                                        onclick="window.location.href='{{ route('hospital.show', $hospital->hospital_slug) }}'">
                                                        <img src="{{ asset('assets/frontend/images/icons/address-icon.svg') }}"
                                                            alt="" class="me-3">
                                                        <span class="font-size-14 font-medium text-secondary">Emergency
                                                            Services | ICU | Ambulance |
                                                            Pharmacy | Parking</span>
                                                    </div>
                                                    <div class="btn-wrap">
                                                        <a href="#" class="btn btn-outline-info font-size-16">Call
                                                            Now</a>
                                                        @php
                                                            $appointment_type = 'default_from';
                                                            if ($hospital->hospitalSetting) {
                                                                $hospital_settings = json_decode(
                                                                    $hospital->hospitalSetting->value,
                                                                );
                                                                $appointment_type =
                                                                    $hospital_settings->appointment_type;
                                                            }
                                                        @endphp
                                                        @if ($appointment_type == 'website_link')
                                                            <a href="{{ $hospital_settings->website_address }}"
                                                                class="btn btn-info ms-3">Book Appointment</a>
                                                        @elseif ($appointment_type == 'whats_app')
                                                            <a href="#" class="btn btn-info ms-3">Book
                                                                Appointment</a>
                                                        @else
                                                            <a href="#" class="btn btn-info ms-3"
                                                                data-bs-toggle="modal" data-bs-target="#appointmentModal"
                                                                data-hospital-id="{{ $hospital->hospital_id }}">Book
                                                                Appointment</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                            </div>

                            <!--PAGINATION-->
                            <nav class="mt-5">
                                {{ $hospitals->links('pagination::bootstrap-4') }}
                            </nav>
                            <!--/PAGINATION-->
                        </div>

                    </div>
                    <div class="col-lg-5 d-none d-lg-block">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3907.289074929954!2d78.14960377498869!3d11.67391364199888!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3babf04db5f4e63b%3A0xb1d0b14b2396c96d!2sJS%20Hospital%2C%20C%2FO%2C%20Ramakrishna%20Rd%2C%20Hasthampatti%2C%20Salem%2C%20Tamil%20Nadu%20636007!5e0!3m2!1sen!2sin!4v1729160691856!5m2!1sen!2sin"
                            width="100%" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade" class="hospital-mark-map"></iframe>
                    </div>
                </div>
                <!--/BLOCK-->
            </div>

        </section>

    </main>
    <!-- Default form Modal -->
    <div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="appointmentForm" method="POST" action="{{ route('hospital.book.appointment') }}">
                    @csrf
                    <input type="hidden" id="hospitalId" name="hospital_id">
                    <div class="modal-header mb-3">
                        <h5 class="modal-title" id="appointmentModalLabel">Book an Appointment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control form-input-control" id="patient_name"
                                name="patient_name" required value="{{ auth()->check() ? auth()->user()->name : '' }}"
                                {{ auth()->check() ? 'readonly' : '' }}
                                oninvalid="this.setCustomValidity('Please provide your name.')"
                                oninput="this.setCustomValidity('')">
                        </div>
                        <div class="mb-3">
                            <label for="mobile" class="form-label">Mobile Number</label>
                            <input type="text" class="form-control form-input-control" id="phone_number"
                                name="phone_number" required value="{{ auth()->check() ? auth()->user()->mobile : '' }}"
                                {{ auth()->check() ? 'readonly' : '' }}
                                oninvalid="this.setCustomValidity('Please provide your mobile number.')"
                                oninput="this.setCustomValidity('')">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control form-input-control" id="email" name="email"
                                required value="{{ auth()->check() ? auth()->user()->email : '' }}"
                                {{ auth()->check() ? 'readonly' : '' }}
                                oninvalid="this.setCustomValidity('Please provide a valid email address.')"
                                oninput="this.setCustomValidity('')">
                        </div>
                        <div class="mb-3">
                            <label for="appointment_date" class="form-label">Appointment Date & Time</label>
                            <input type="datetime-local" class="form-control form-input-control" id="appointment_date"
                                name="appointment_date" required
                                oninvalid="this.setCustomValidity('Please select a date and time.')"
                                oninput="this.setCustomValidity('')">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-info font-size-16"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-info ms-3" form="appointmentForm">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--/MAIN-->
    @include('frontend.includes.footer')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Event listener for the button click
            document.querySelectorAll('.favorite-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const hospitalId = this.getAttribute(
                        'data-hospital-id'
                    ); // Assuming the hospital ID is stored in a data attribute
                    setHospitalFav(hospitalId);
                });
            });

            function setHospitalFav(hospitalId) {
                const button = document.querySelector(`#favorite-btn-${hospitalId}`);
                const formData = new FormData();
                formData.append('hospitalId', hospitalId);

                fetch('{{ route('hospital.setFavorite') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                        },
                        'Cache-Control': 'no-cache',
                        body: formData,
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.status === 'added') {
                            button.classList.add('active');
                        } else if (data.status === 'notlogin') {
                            window.location.href = '{{ route('users.login') }}';
                        } else {
                            button.classList.remove('active');
                        }
                    })
                    .catch((error) => {
                        // Log the error for debugging
                        console.log('Error occurred:', error);

                        // Show a user-friendly message
                        alert('Error: ' + error.message || 'Something went wrong. Please try again later.');
                    });
            }
        });

        /*Book Appointment: Optional: JavaScript to set the hospital ID dynamically*/
        document.addEventListener('DOMContentLoaded', function() {
            const bookAppointmentButton = document.querySelector('.btn-info.ms-3');
            bookAppointmentButton.addEventListener('click', function(event) {
                const hospitalId = event.currentTarget.getAttribute('data-hospital-id');
                document.getElementById('hospitalId').value =
                    hospitalId;
            });
        });
    </script>
@endsection
