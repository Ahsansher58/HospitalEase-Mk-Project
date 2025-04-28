@extends('frontend.layouts.after-login-doctors')

@section('title', 'Hospital Ease - Dashboard')
@include('frontend.includes.favicon')
@section('content')
    @include('frontend.includes.after-login-doctor-header')
    <!--MAIN-->
    <main class="inner-page">
        <!--BANNER-->
        @include('frontend.includes.doctor-top-banner')
        <!--/BANNER-->

        <section class="pb-5">
            <div class="container">
                <div class="row">


                    <div class="col-xl-3">
                        <!--SIDE TAB-->
                        @include('frontend.includes.doctor-side-navbar')
                        <!--SIDE TAB-->
                    </div>

                    <div class="col-xl-9">
                        <div class="hospital-list-block my-favourite-hospital frame">
                            <div class="d-flex align-items-center justify-content-between mb-5">
                                <h3 class="font-medium mb-0">My Educational Qualifications</h3>
                                <button class="btn btn-info me-3" type="submit">Add More</button>
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{ route('doctor.EducationalQualificationsUpdateAll') }}" id="edit_educational_info" method="POST">
                                @csrf
                                @method('PUT')
                                <!-- This is needed if your form is doing a PUT request (usually for updating data) -->

                                <div class="row">
                                    {{-- <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="mb-2">Name</label>
                                            <input type="text" class="form-control form-input-control"
                                                placeholder="Premkumar" name="name" value="{{ $user->name }}" required>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please enter name
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="mb-2">Email</label>
                                            <input type="email" class="form-control form-input-control"
                                                placeholder="{{ $user->email }}" disabled name="email"
                                                value="{{ $user->email }}">
                                        </div>
                                        <div class="invalid-feedback">
                                            Please enter email
                                        </div>
                                    </div> --}}
                                    {{-- <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="mb-2">Phone</label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control form-input-control phone-input"
                                                    placeholder="{{ $user->mobile }}" disabled name="mobile"
                                                    value="{{ $user->mobile }}">
                                                <span class="country-code position-absolute">+91 |</span>
                                            </div>
                                            <div class="invalid-feedback">
                                                Please enter phone
                                            </div>
                                        </div>
                                    </div> --}}
                                    {{-- <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="mb-2">Profile Photo</label>
                                            <input type="file" class="form-control form-input-control"
                                            placeholder="" name="profile_image" required>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please upload Profile Photo
                                        </div>
                                    </div> --}}

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="mb-2">College Name</label>
                                            <input type="text" class="form-control form-input-control" name="college_name"
                                                value="{{ $doctor_profile->college_name }}" required>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please enter College Name
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="year_studied" class="mb-2">Years Studied</label>
                                            <input type="number" class="form-control form-input-control" name="year_studied"
                                            value="{{ $doctor_profile->year_studied ?? ''}}" required>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please enter Years Studied
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="degree" class="mb-2">Degree Name</label>
                                            <input type="text" class="form-control form-input-control"
                                                name="degree" id="degree" value="{{ $doctor_profile->degree ?? '' }}" required>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please enter Degree Name
                                        </div>
                                    </div>
                                   {{--  <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="weight" class="mb-2">Weight</label>
                                            <input type="text" class="form-control form-input-control" placeholder="76"
                                                name="weight" id="weight"
                                                value="{{ $doctor_profile->weight ?? old('weight') }}">
                                        </div>
                                        <div class="invalid-feedback">
                                            Please enter weight
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="address" class="mb-2">Address</label>
                                            <input type="text" class="form-control form-input-control"
                                                placeholder="#3B, 4th cross street" name="address"
                                                value="{{ $doctor_profile->address ?? '' }}">
                                        </div>
                                        <div class="invalid-feedback">
                                            Please enter address
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-4">
                                            <label for="locality" class="mb-2">Locality</label>
                                            <select class="selectpicker form-select form-input-control w-100"
                                                name="locality" required>
                                                @foreach ($uniqueLocalities as $locality)
                                                    <option value="{{ $locality }}"
                                                        {{ $doctor_profile && $doctor_profile->locality == $locality ? 'selected' : '' }}>
                                                        {{ $locality }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select locality
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-4">
                                            <label for="city" class="mb-2">City</label>
                                            <select class="selectpicker form-select form-input-control w-100"
                                                name="city" required>
                                                @foreach ($uniqueCities as $city)
                                                    <option value="{{ $city }}"
                                                        {{ $doctor_profile && $doctor_profile->city == $city ? 'selected' : '' }}>
                                                        {{ $city }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select city
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-4">
                                            <label for="state" class="mb-2">State</label>
                                            <select class="selectpicker form-select form-input-control w-100"
                                                name="state" required>
                                                @foreach ($uniqueStates as $state)
                                                    <option value="{{ $state }}"
                                                        {{ $doctor_profile && $doctor_profile->state == $state ? 'selected' : '' }}>
                                                        {{ $state }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select state
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-4">
                                            <label for="pincode" class="mb-2">Pincode</label>
                                            <input type="text" class="form-control form-input-control"
                                                placeholder="Enter pincode" name="pincode"
                                                value="{{ $doctor_profile && $doctor_profile->pincode ?? '' }}" required>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please enter pincode
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-4">
                                            <label for="country" class="mb-2">Country</label>
                                            <select class="selectpicker form-select form-input-control w-100"
                                                name="country" required>
                                                @foreach ($uniqueCountries as $country)
                                                    <option value="{{ $country }}"
                                                        {{ $doctor_profile && $doctor_profile->country == $country ? 'selected' : '' }}>
                                                        {{ $country }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select country
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>

                                <div class="mt-3 mt-md-5 mb-3">
                                    <button class="btn btn-info me-3" type="submit">Update Profile</button>
                                    <a href="{!! route('doctor.educational-qualifications') !!}" class="btn btn-cancel">Cancel</a>
                                </div>
                            </form>


                        </div>
                    </div>
                </div>

            </div>
        </section>
    </main>
    <!--/MAIN-->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            flatpickr("#flatpickr-date", {
                dateFormat: "d/m/Y", // Set the display format
                defaultDate: "{{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('d / m / Y') : '' }}", // Set the initial date
            });
        });
    </script>
    @include('frontend.includes.user-footer')
    <!-- Flatpickr Initialization Script -->

@endsection
