<!--MAIN-->
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
                            <div class="d-sm-flex align-items-start align-items-sm-center justify-content-between mb-5">
                                <h3 class="font-medium mb-0">My Educational Qualifications</h3>
                                <div class="text-end justify-content-between d-flex mt-4 mt-sm-0">
                                    {{-- <a href="javascript:void()" class="btn btn-cancel rounded-50 ps-0" data-bs-toggle="modal" data-bs-target="#changePassword">Change Password</a>  --}}
                                    <a href="{{ route('doctor.editEducationalQualifications') }}" class="btn btn-info rounded-50">
                                        <img src="images/icons/edit-2.svg" alt="" class="me-0 me-md-2" /><span class="d-none d-md-inline-flex">Edit Educational Qualifications</span>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
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
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="mb-2">College Name</label>
                                        <input type="text" class="form-control form-input-control"
                                            placeholder="{{ $doctor_profile->college_name ?? 'N/A' }}" disabled>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="mb-2">Years Studied</label>
                                        <input type="number" class="form-control form-input-control"
                                            placeholder="{{ $doctor_profile->year_studied ?? 'N/A' }}" disabled>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="mb-2">Degree Name</label>
                                        <input type="text" class="form-control form-input-control"
                                            placeholder="{{ $doctor_profile->degree ?? 'N/A' }}" disabled>
                                    </div>
                                </div>

                                {{-- <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="mb-2">Years Experience</label>
                                        <input type="number" class="form-control form-input-control"
                                            placeholder="{{ $user->years_experience ?? 'N/A' }}" disabled>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="mb-2">Specialization</label>
                                        <input type="text" class="form-control form-input-control"
                                            placeholder="{{ $user->Specialization ?? 'N/A'}}" disabled>
                                    </div>
                                </div> --}}
                                @php
                                    $doctor_profile = $doctor_profile ?? session('user_profile');
                                    $marital_status = $doctor_profile->marital_status ?? '';
                                    $height         = $doctor_profile->height ?? '';
                                    $weight         = $doctor_profile->weight ?? '';
                                    $address        = $doctor_profile->address ?? '';
                                    $locality       = $doctor_profile->locality ?? '';
                                    $city           = $doctor_profile->city ?? '';
                                    $state          = $doctor_profile->state ?? '';
                                    $pincode        = $doctor_profile->pincode ?? '';
                                    $country        = $doctor_profile->country ?? '';
                                @endphp

                             {{-- <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="mb-2">Marital Status</label>
                                        <input type="text" class="form-control form-input-control"
                                            placeholder="{{ $marital_status }}" disabled>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="mb-2">Height</label>
                                        <input type="text" class="form-control form-input-control"
                                            placeholder="{{ $height }}" disabled>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="mb-2">Weight</label>
                                        <input type="text" class="form-control form-input-control"
                                            placeholder="{{ $weight }}" disabled>
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="mb-2">Address</label>
                                        <input type="text" class="form-control form-input-control"
                                            placeholder="{{ $address }}" disabled>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="mb-2">Locality</label>
                                        <input type="text" class="form-control form-input-control"
                                            placeholder="{{ $locality }}" disabled>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="mb-2">City</label>
                                        <select class="selectpicker form-select form-input-control w-100"
                                            data-live-search="true" title="{{ $city }}" disabled>
                                        </select>
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="mb-2">State</label>
                                        <select class="selectpicker form-select form-input-control w-100"
                                            data-live-search="true" title="{{ $state }}" disabled>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="mb-2">Pincode</label>
                                        <select class="selectpicker form-select form-input-control w-100"
                                            data-live-search="true" title="{{ $pincode }}" disabled>
                                        </select>
                                    </div>
                                </div> 
                             <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="mb-2">Country</label>
                                        <select class="selectpicker form-select form-input-control w-100"
                                            data-live-search="true" title="{{ $country }}" disabled>
                                        </select>
                                    </div>
                                </div> 
                            </div> --}}
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </main>
    <!--/MAIN-->
    <!-- Modal -->
    <div class="modal fade" id="changePassword" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-m modal-dialog-centered">
            <div class="modal-content rounded-24">
                <div class="modal-header border-0">
                    <h3 class="modal-title font-regular" id="staticBackdropLabel">Change Password</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body mt-4">
                    <form id="changePasswordForm" method="POST" action="{{ route('doctor.changePassword') }}">
                        @csrf
                        <div class="row gy-3">
                            <div class="col-lg-12">
                                <label class="mb-2">Old password<span class="text-danger">*</span></label>
                                <input type="password" class="form-control form-input-control" name="old_password"
                                    placeholder="Enter Your old password" required>
                            </div>
                            <div class="col-lg-12">
                                <label class="mb-2">New password<span class="text-danger">*</span></label>
                                <input type="password" class="form-control form-input-control" name="new_password"
                                    placeholder="Enter Your new password" required>
                            </div>
                            <div class="col-lg-12">
                                <label class="mb-2">Confirm password<span class="text-danger">*</span></label>
                                <input type="password" class="form-control form-input-control"
                                    name="new_password_confirmation" placeholder="Enter Your confirm password" required>
                            </div>
                        </div>

                        <div class="my-5">
                            <button type="submit" class="btn btn-info me-2 font-regular">Change Password</button>
                            <button type="button" class="btn btn-cancel font-size-16 font-regular"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('frontend.includes.user-footer')

@endsection
