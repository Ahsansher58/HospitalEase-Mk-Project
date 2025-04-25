<!--MAIN-->
@extends('frontend.layouts.after-login-doctors')

@section('title', 'Doctor - Dashboard')
@include('frontend.includes.favicon')
@section('content')
    @include('frontend.includes.after-login-doctor-header')
    <main class="inner-page after-login-main-bg">
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
                        <div class="frame add-record-frame">
                            <div class="rounded-8 add-record-head">
                                <div class="row gy-3 justify-content-between">
                                    <div class="col-sm-4 col-6">
                                        <small class="font-regular">Username</small>
                                        <h5 class="mb-0">{{ $user->name }}</h5>
                                    </div>
                                    <div class="col-sm-3 col-md-2 col-6">
                                        <small class="font-regular">DOB</small>
                                        <h5 class="mb-0">{{ \Carbon\Carbon::parse($user->dob)->format('d M Y') }}</h5>
                                    </div>
                                    <div class="col-sm-3 col-md-2 col-4">
                                        <small class="font-regular">Gender</small>
                                        <h5 class="mb-0">{{ $user->gender }}</h5>
                                    </div>
                                    @if (session('user_profile'))
                                        <div class="col-sm-3 col-md-2 col-4">
                                            <small class="font-regular">Height</small>
                                            <h5 class="mb-0">{{ session('user_profile')->height }} feet</h5>
                                        </div>
                                        <div class="col-sm-3 col-md-2 col-4">
                                            <small class="font-regular">Weight</small>
                                            <h5 class="mb-0">{{ session('user_profile')->weight }} kg</h5>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- <div class="add-record-list d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-start align-items-sm-center me-2">
                                    <img src="{{ asset('assets/frontend/images/icons/medical-records.svg') }}"
                                        alt="" class="me-2 img-fluid w-h-3" />
                                    <div>
                                        <h4 class="mb-2">My Medical Records</h4>
                                        <p class="mb-0 font-regular gray-70">Easy access to upload files like prescriptions
                                            or reports.</p>
                                    </div>
                                </div>
                                <a href="{{ route('user.medicalRecords') }}"
                                    class="btn btn-info btn-sm font-size-15 font-medium"><img
                                        src="{{ asset('assets/frontend/images/icons/plus-icon.svg') }}" alt=""
                                        class="me-0 me-lg-2" width="18" height="18"><span
                                        class="d-none d-lg-inline">Add my records</span></a>
                            </div> --}}

                            {{-- <div class="add-record-list d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-start align-items-sm-center me-2">
                                    <img src="{{ asset('assets/frontend/images/icons/allergin-medicine.svg') }}"
                                        alt="" class="me-2 img-fluid w-h-3" />
                                    <div>
                                        <h4 class="mb-2">Allergic to Medicine</h4>
                                        <p class="mb-0 font-regular gray-70">List medicines you're allergic to for safe
                                            care.</p>
                                    </div>
                                </div>
                                <a href="{{ route('user.allergicMedicine') }}"
                                    class="btn btn-info btn-sm font-size-15 font-medium"><img
                                        src="{{ asset('assets/frontend/images/icons/plus-icon.svg') }}" alt=""
                                        class="me-0 me-lg-2" width="18" height="18"><span
                                        class="d-none d-lg-inline">Add Allergic to
                                        Medicines</span></a>
                            </div>

                            <div class="add-record-list d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-start align-items-sm-center me-2">
                                    <img src="{{ asset('assets/frontend/images/icons/allergin-food.svg') }}" alt=""
                                        class="me-2 img-fluid w-h-3" />
                                    <div>
                                        <h4 class="mb-2">Allergic to Food</h4>
                                        <p class="mb-0 font-regular gray-70">Add food allergies to avoid reactions.</p>
                                    </div>
                                </div>
                                <a href="{{ route('user.allergicFood') }}"
                                    class="btn btn-info btn-sm font-size-15 font-medium"><img
                                        src="{{ asset('assets/frontend/images/icons/plus-icon.svg') }}" alt=""
                                        class="me-0 me-lg-2" width="18" height="18"><span
                                        class="d-none d-lg-inline">Add Allergic to
                                        Food</span></a>
                            </div> --}}

                        </div>
                    </div>
                </div>

            </div>
        </section>

    </main>
    <!--/MAIN-->
    @include('frontend.includes.user-footer')
@endsection
