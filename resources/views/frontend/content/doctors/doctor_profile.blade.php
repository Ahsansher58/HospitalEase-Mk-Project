<!--MAIN-->
@extends('frontend.layouts.after-login-doctors')
@section('title', 'Hospital Ease - Dashboard')
@include('frontend.includes.favicon')
@section('content')
    @include('frontend.includes.after-login-doctor-header')
    <!--MAIN-->
    <main class="inner-page after-login-main-bg">
        <!--BANNER-->
        @include('frontend.includes.doctor-top-banner')
        <!--/BANNER-->

        <section class="pb-5">
            <div class="container">
                <div class="row">


                    <div class="col-xl-3">
                        <div class="user-info-widget disabled d-block">
                            <h4 class="mb-2">{{ $user->name }}</h4>
                            @php
                                use Carbon\Carbon;
                                $user = Auth::user();
                                $age = Carbon::parse($user->dob)->age;
                            @endphp
                            <p class="mb-0 gray-70">{{ Carbon::parse($user->dob)->format('d - M - Y') }} ({{ $age }}
                                Year Old)</p>
                        </div>
                        <!--SIDE TAB-->
                        @include('frontend.includes.doctor-side-navbar')
                        <!--SIDE TAB-->

                    </div>

                    <div class="col-xl-9">
                        <div class="hospital-list-block my-favourite-hospital frame">
                            <div class="d-sm-flex justify-content-between align-items-center mb-5">
                                <div class="mb-3 mb-sm-0">
                                    <h3 class="font-medium mb-2">Well done {{ $user->name }},</h3>
                                    <p class="font-regular gray-70 mb-0">Please Complete your profile</p>
                                </div>

                                <a href="{{ route('doctor.dashboard') }}" class="twi-link text-end">I Will Do Later</a>

                            </div>

                            <form class="needs-validation profile-form" action="{{ route('doctor.profileUpdate') }}"
                                method="POST" novalidate>
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-4">
                                            <label for="height" class="mb-2">Height (in feet)</label>
                                            <input type="text" class="form-control form-input-control" placeholder="5.6"
                                                id="height" name="height" value="{{ old('height') }}" required>
                                            <div class="invalid-feedback">
                                                Please enter height
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-4">
                                            <label for="weight" class="mb-2">Weight (in kg)</label>
                                            <input type="text" class="form-control form-input-control" placeholder="76"
                                                id="weight" name="weight" value="{{ old('weight') }}" required>
                                            <div class="invalid-feedback">
                                                Please enter weight
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-4">
                                            <label for="address" class="mb-2">Address</label>
                                            <input type="text" class="form-control form-input-control"
                                                placeholder="e.g., #3B, 4th Cross Street" id="address" name="address"
                                                value="{{ old('address') }}" required>
                                            <div class="invalid-feedback">
                                                Please enter address
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-4">
                                            <label for="locality" class="mb-2">Locality</label>
                                            <select class="selectpicker form-select form-input-control w-100"
                                                data-live-search="true" title="Choose locality" id="locality"
                                                name="locality" required>
                                                @foreach ($uniqueLocalities as $locality)
                                                    <option value="{{ $locality }}"
                                                        {{ old('locality') == $locality ? 'selected' : '' }}>
                                                        {{ $locality }}
                                                    </option>
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
                                                data-live-search="true" title="Choose city" id="city" name="city"
                                                required>
                                                @foreach ($uniqueCities as $city)
                                                    <option value="{{ $city }}"
                                                        {{ old('city') == $city ? 'selected' : '' }}>
                                                        {{ $city }}
                                                    </option>
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
                                                data-live-search="true" title="Choose state" id="state" name="state"
                                                required>
                                                @foreach ($uniqueStates as $state)
                                                    <option value="{{ $state }}"
                                                        {{ old('state') == $state ? 'selected' : '' }}>
                                                        {{ $state }}
                                                    </option>
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
                                                placeholder="Enter pincode" id="pincode" name="pincode"
                                                value="{{ old('pincode') }}" required>
                                            <div class="invalid-feedback">
                                                Please enter pincode
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-4">
                                            <label for="country" class="mb-2">Country</label>
                                            <select class="selectpicker form-select form-input-control w-100"
                                                data-live-search="true" title="Choose country" id="country"
                                                name="country" required>
                                                @foreach ($uniqueCountries as $country)
                                                    <option value="{{ $country }}"
                                                        {{ old('country') == $country ? 'selected' : '' }}>
                                                        {{ $country }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select country
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3 mt-md-5 mb-3 text-end">
                                    <button class="btn btn-info" type="submit">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </main>
    <!--/MAIN-->
    @include('frontend.includes.user-footer')
@endsection
