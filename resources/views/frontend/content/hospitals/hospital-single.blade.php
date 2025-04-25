@extends('frontend.layouts.hospitals')

@section('title', 'Hospital Ease - ' . $hospital->hospital_name)
@section('favicon')
    @include('frontend.includes.favicon')
    <style>
        .star-rating {
            text-align: left;
            justify-content: flex-start;
            gap: 5px;
            direction: rtl;
        }

        .star-rating input[type="radio"] {
            display: none;
        }

        .star-rating label {
            font-size: 2rem;
            color: #d3d3d3;
            /* Default color for unselected stars */
            cursor: pointer;
            transition: color 0.2s ease-in-out;
        }

        .star-rating label:hover,
        .star-rating input[type="radio"]:checked~label {
            color: #ffc107;
            /* Highlight color for selected and hovered stars */
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
                            if (isset($hospital_images)) {
                                $hospital_images = json_decode($hospital->hospital_images, true);
                            }
                        @endphp
                        @php
                            if (isset($hospital_images) and isset($hospital_images['images'][0])) {
                                $hospital_image = $hospital_images['images'][0];
                            } else {
                                $hospital_image = asset('assets/img/no_image.jpg');
                            }
                        @endphp

                        <img src="{{ $hospital_image }}" alt="{{ $hospital->hospital_name }}"
                            class="img-fluid rounded-24 w-100" />
                    </div>
                    <div class="col-lg-7">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="pt-4 pt-lg-0">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h2 class="mb-0">{{ $hospital->hospital_name }}</h2>
                                <button class="btn btn-like favorite-btn @if (in_array($hospital->hospital_id, $favoriteHospitals)) active @endif"
                                    id="favorite-btn-{{ $hospital->hospital_id }}"
                                    data-hospital-id="{{ $hospital->hospital_id }}">
                                    <i class="fa fa-heart" aria-hidden="true"></i>
                                    <i class="fa fa-heart-o" aria-hidden="true"></i>
                                </button>
                            </div>

                            <div class="bg-white p-3 rounded-8 d-flex align-items-center">
                                <!-- Rating Widget -->
                                <div class="rating-widget">
                                    <span class="count">{{ $hospital->averageRating }}</span>
                                    <div class="rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fa fa-star {{ $i <= $hospital->averageRating ? 'active' : '' }}"
                                                aria-hidden="true"></i>
                                        @endfor
                                    </div>
                                    <a href="javascript:void(0)" class="link-reivew">See all
                                        reviews ({{ $hospital->reviewsCount }})</a>
                                </div>
                                <div class="info-text">
                                    {!! $hospital->description !!}
                                </div>
                            </div>

                            <ul class="chip mt-4 mb-2">
                                @php
                                    $specialization = json_decode($hospital->specialization);
                                @endphp

                                @if ($specialization && is_array($specialization))
                                    @foreach ($specialization as $index => $name)
                                        <li class="border-0 chip-bg-light">
                                            <a href="{{ route('business_listing') . '?search_by_specialization=' . $name }}"
                                                class="text-decoration-none">
                                                {{ $name }}
                                            </a>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="border-0 chip-bg-light">No specialization available</li>
                                @endif
                            </ul>
                            @php
                                $appointment_type = 'default_from';
                                if ($hospital->hospitalSetting) {
                                    $hospital_settings = json_decode($hospital->hospitalSetting->value);
                                    $appointment_type = $hospital_settings->appointment_type;
                                }
                            @endphp
                            @if ($appointment_type == 'website_link')
                                <a href="{{ $hospital_settings->website_address }}" class="btn btn-info">Book
                                    Appointment</a>
                            @elseif ($appointment_type == 'whats_app')
                                <a href="#" class="btn btn-info">Book Appointment</a>
                            @else
                                <a href="#" class="btn btn-info" data-bs-toggle="modal"
                                    data-bs-target="#appointmentModal" data-hospital-id="{{ $hospital->hospital_id }}">Book
                                    Appointment</a>
                            @endif

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
                            <a class="nav-link active" data-bs-toggle="tab" href="#tab1">Specialisation</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab2">Doctors</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab3">Facilities</a>
                        </li>
                        @php
                            if (!empty($hospital->other_data)) {
                                $other_data = json_decode($hospital->other_data, true); // Decode JSON as an associative array

                                if (is_array($other_data) && !empty($other_data)) {
                                    foreach ($other_data as $key => $value) {
                                        if (preg_match('/subcat(\d+)/', $key, $matches)) {
                                            $subcategoryId = $matches[1];
                                            if ($subcategoryId == 12 || $subcategoryId == 13 || $subcategoryId == 14) {
                                                // Fetch the subcategory name from the database using the ID
                                                $subcategory = \App\Models\SubCategory::find($subcategoryId);
                                            } // Adjust your model and namespace

                                            if ($subcategory) {
                                                echo '<li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#subcat' .
                                                    $subcategoryId .
                                                    '">' .
                                                    htmlspecialchars($subcategory->sub_category_name) .
                                                    '</a></li>';
                                            }
                                        }
                                    }
                                }
                            }
                        @endphp
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab5">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="reviews_tab_link" data-bs-toggle="tab" href="#tab7">Reviews</a>
                        </li>
                    </ul>
                </div>
                <!--Block-->
                <div class="row">
                    <div class="col-lg-8">
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1">
                                <h4>Specialisation</h4>
                                <ul class="chip mb-4">
                                    @if ($specialization && is_array($specialization))
                                        @php
                                            sort($specialization);
                                        @endphp
                                        @foreach ($specialization as $index => $name)
                                            <li>{{ $name }}</li>
                                        @endforeach
                                    @endif
                            </div>
                            <div class="tab-pane " id="tab2">
                                <h4>Doctors</h4>
                                <div class="row gy-4">
                                    <div class="col-sm-4">
                                        <div class="card dr-card rounded-24">
                                            <div class="position-relative">
                                                <img src="{{ asset('assets/frontend/images/dr-img-1.png') }}"
                                                    class="card-img-top" alt="...">
                                                <span class="card-chip rounded-6">15+ Years</span>
                                            </div>
                                            <div class="card-body text-center">
                                                <h4 class="card-title">Dr. Meera Patil</h4>
                                                <p class="card-text mb-3">Gynecology</p>
                                                <a href="#" class="btn btn-outline-info btn-xs">View Full
                                                    Profile</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="card dr-card rounded-24">
                                            <div class="position-relative">
                                                <img src="{{ asset('assets/frontend/images/dr-img-2.png') }}"
                                                    class="card-img-top" alt="...">
                                                <span class="card-chip rounded-6">15+ Years</span>
                                            </div>

                                            <div class="card-body text-center">
                                                <h4 class="card-title">Dr. Arjun Rao</h4>
                                                <p class="card-text mb-3">Cardiology</p>
                                                <a href="#" class="btn btn-outline-info btn-xs">View Full
                                                    Profile</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="card dr-card rounded-24">
                                            <div class="position-relative">
                                                <img src="{{ asset('assets/frontend/images/dr-img-3.png') }}"
                                                    class="card-img-top" alt="...">
                                                <span class="card-chip rounded-6">15+ Years</span>
                                            </div>
                                            <div class="card-body text-center">
                                                <h4 class="card-title">Dr. Vipin Rao</h4>
                                                <p class="card-text mb-3">Cardiology</p>
                                                <a href="#" class="btn btn-outline-info btn-xs">View Full
                                                    Profile</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane fade" id="tab3">
                                <h4>Facilities</h4>
                                <ul class="chip mb-4">
                                    @php
                                        $facilities = json_decode($hospital->facilities, true); // First array
                                        $all_facilities = json_decode($all_facilities, true); // Second array
                                        sort($facilities);
                                        $merged_facilities = [];

                                        foreach ($facilities as $facility) {
                                            $match = collect($all_facilities)->firstWhere('name', $facility);
                                            if ($match) {
                                                $merged_facilities[] = [
                                                    'name' => $facility,
                                                    'file' => $match['file'],
                                                ];
                                            }
                                        }
                                    @endphp

                                    @if ($merged_facilities && is_array($merged_facilities))
                                        @foreach ($merged_facilities as $facility)
                                            <li><img src="{{ asset($facility['file']) }}" class="img-fluid me-2"
                                                    alt="{{ $facility['name'] }}" />{{ $facility['name'] }}
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                            @if (!empty($hospital->other_data))
                                @php
                                    $other_data = json_decode($hospital->other_data, true);
                                @endphp
                                @if (is_array($other_data) && !empty($other_data))
                                    @foreach ($other_data as $key => $value)
                                        @if (preg_match('/subcat(\d+)/', $key, $matches))
                                            @php
                                                $subcategoryId = $matches[1];
                                                $subcategory = \App\Models\SubCategory::find($subcategoryId);
                                            @endphp
                                            @if ($subcategoryId == 12 || $subcategoryId == 13 || $subcategoryId == 14)
                                                <div class="tab-pane  fade" id="subcat{{ $subcategoryId }}">
                                                    {{-- type == checkbox --}}
                                                    @if ($subcategory->type == 1)
                                                        <h4>{{ $subcategory->sub_category_name }}</h4>
                                                        <ul class="bullet-check grid-2">
                                                            @if (is_array($value) && !empty($value))
                                                                @foreach ($value as $item)
                                                                    <li>{{ $item }}</li>
                                                                @endforeach
                                                            @endif
                                                        </ul>
                                                    @endif
                                                    {{-- type == Multi-Image & Text	 --}}
                                                    @if ($subcategory->type == 4)
                                                        <div class="row">
                                                            <h4>{{ $subcategory->sub_category_name }}</h4>
                                                            @php
                                                                $all_sub_value = json_decode($subcategory->value, true); //
                                                            @endphp

                                                            @if (is_array($value) && !empty($value))
                                                                @foreach ($value as $item)
                                                                    @php
                                                                        $normalizedItem = strtolower(
                                                                            str_replace([' ', '_'], '', $item),
                                                                        );
                                                                        $matchedEntry = collect($all_sub_value)->first(
                                                                            function ($entry) use ($normalizedItem) {
                                                                                return strtolower(
                                                                                    str_replace(
                                                                                        [' ', '_'],
                                                                                        '',
                                                                                        $entry['name'],
                                                                                    ),
                                                                                ) === $normalizedItem;
                                                                            },
                                                                        );

                                                                        $imageUrl = $matchedEntry['file'] ?? null;

                                                                    @endphp
                                                                    <div class="col-md-4">
                                                                        <div>
                                                                            <img src="{{ asset($imageUrl) }}"
                                                                                alt="{{ $item }}">
                                                                        </div>
                                                                        <div class="mt-2">
                                                                            <strong>{{ str_replace(['_', ' '], ' ', $item) }}</strong>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    @endif
                                                    {{-- type == Textarea --}}
                                                    @if ($subcategory->type == 6)
                                                        {!! $value !!}
                                                    @endif
                                                </div>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            @endif
                            <div class="tab-pane fade" id="tab5">
                                <h4>About</h4>
                                <p>{!! $hospital->description !!}</p>

                                <div class="row">
                                    <div class="col-xl-3 col-sm-6 col-12">
                                        <small>Phone</small>
                                        <p>{{ $hospital->phone }}</p>
                                    </div>
                                    <div class="col-xl-3 col-sm-6 col-12">
                                        <small>Emergency</small>
                                        <p>{{ $hospital->emergency_contact }}</p>
                                    </div>
                                    <div class="col-xl-3 col-sm-6 col-12">
                                        <small>Email</small>
                                        <p>{{ $hospital->email }}</p>
                                    </div>
                                    <div class="col-xl-3 col-sm-6 col-12">
                                        <small>Website</small>
                                        <p>{{ $hospital->website }}</p>
                                    </div>
                                </div>

                                <div class="row gy-4 align-items-center justify-content-between">
                                    <div class="col-sm-5">
                                        <h4>Location</h4>
                                        <p>{{ $hospital->location }}</p>
                                        <p>{{ $hospital->locality }}, {{ $hospital->city }}, {{ $hospital->state }},
                                            {{ $hospital->country }}</p>

                                        <a href="#" class="btn btn-info">Get Direction</a>
                                    </div>
                                    <div class="col-sm-6">
                                        <img src="{{ asset('assets/frontend/images/map-2.png') }}"
                                            class="img-fluid rounded-16 mb-4" />
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab7">
                                <h4>Reviews</h4>
                                @if (auth()->check())
                                    @if ($hospital->hospitalReview && count($hospital->hospitalReview) > 0)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h3>{{ $hospital->hospitalReview ? count($hospital->hospitalReview) : 0 }}
                                                    Reviews</h3>
                                            </div>
                                        </div>
                                        <div class="reviews-container">
                                            @forelse ($hospital->hospitalReview as $review)
                                                <div class="raview-block">
                                                    <div class="rating">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i class="fa fa-star {{ $i <= $review->rating ? 'active' : '' }}"
                                                                aria-hidden="true"></i>
                                                        @endfor
                                                    </div>
                                                    <div class="d-flex review-info">
                                                        <img src="{{ asset('assets/frontend/images/avatar-img.png') }}"
                                                            class="avatar rounded-circle me-3" />
                                                        <div class="text-secondary">{{ $review->review }}</div>
                                                    </div>
                                                    <date>{{ $review->created_at->format('F, Y') }}</date>
                                                    <!-- Check if there is a reply to the review -->
                                                    @if ($review->reply)
                                                        <div class="review-reply mt-3">
                                                            <strong>Reply:</strong>
                                                            <p class="text-secondary">{{ $review->reply }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @empty
                                                <p class="text-secondary">No reviews available for this hospital.</p>
                                            @endforelse

                                        </div>
                                    @else
                                        <p class="text-secondary">No reviews available for this hospital.</p>
                                    @endif


                                    <div class="container my-5">
                                        <h3 class="mb-4">Leave a Review</h3>
                                        <form id="reviewForm" action="{{ route('review.send') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="hospital_id"
                                                value="{{ $hospital->hospital_id }}">
                                            <!-- Rating -->
                                            <div class="mb-3">
                                                <div class="mb-3">
                                                    <div class="star-rating">
                                                        <input type="radio" id="rating-5" name="rating"
                                                            value="5">
                                                        <label for="rating-5"><i class="fa fa-star"></i></label>
                                                        <input type="radio" id="rating-4" name="rating"
                                                            value="4">
                                                        <label for="rating-4"><i class="fa fa-star"></i></label>
                                                        <input type="radio" id="rating-3" name="rating"
                                                            value="3">
                                                        <label for="rating-3"><i class="fa fa-star"></i></label>
                                                        <input type="radio" id="rating-2" name="rating"
                                                            value="2">
                                                        <label for="rating-2"><i class="fa fa-star"></i></label>
                                                        <input type="radio" id="rating-1" name="rating"
                                                            value="1">
                                                        <label for="rating-1"><i class="fa fa-star"></i></label>
                                                    </div>
                                                </div>

                                            </div>


                                            <!-- Review -->
                                            <div class="mb-3">
                                                <label for="review" class="form-label">Your Review</label>
                                                <textarea class="form-control form-input-control" id="review" name="review" rows="4"
                                                    placeholder="Write your review here..." required></textarea>
                                            </div>

                                            <!-- Submit Button -->
                                            <div class="text-end">
                                                <button type="submit"
                                                    class="btn btn-secondary btn-sm me-0 me-xl-3">Submit
                                                    Review</button>
                                            </div>
                                        </form>
                                    </div>
                                @else
                                    <p>You need to be logged in to review a hospital</p>
                                    <a href="{{ route('users.login') }}"
                                        class="btn btn-secondary btn-sm me-0 me-xl-3">Login Now</a>
                                @endif

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
            // See all Review click one review tab
            const reviewLinks = document.querySelectorAll('.link-reivew');
            reviewLinks.forEach(function(link) {
                // Add click event listener to each link
                link.addEventListener('click', function() {
                    const allTabs = document.querySelectorAll('.tab-content .tab-pane');
                    allTabs.forEach(function(tab) {
                        tab.classList.remove('show', 'active');
                    });
                    const tab7 = document.getElementById('tab7');
                    tab7.classList.add('show', 'active');
                    const navLinks = document.querySelectorAll('.nav-link');

                    navLinks.forEach(function(navLink) {
                        navLink.classList.remove('active');
                    });

                    link.classList.add('active');
                    // Scroll to review tab
                    const tab7Position = tab7.offsetTop;
                    document.getElementById('reviews_tab_link').classList.add('active');
                    window.scrollTo({
                        top: tab7Position - 100,
                        behavior: 'smooth'
                    });

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
    </script>
    <!-- Default form Modal -->
    <div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="appointmentForm" method="POST" action="{{ route('hospital.book.appointment') }}">
                    @csrf
                    <input type="hidden" id="hospitalId" name="hospital_id" value="{{ $hospital->hospital_id }}">
                    <input type="hidden" id="hospital_slug" name="hospital_slug"
                        value="{{ $hospital->hospital_slug }}">
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
@endsection
