<!--MAIN-->
@extends('frontend.layouts.after-login-doctors')

@section('title', 'Hospital Ease - Award & Achievements')
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
                                <h3 class="font-medium mb-0">Award & Achievements</h3>
                                <div class="text-end justify-content-between d-flex mt-4 mt-sm-0">
                                    <a href="{{ route('doctor.editAwardAchievements') }}" class="btn btn-info rounded-50 text-center">
                                        <img src="images/icons/edit-2.svg" alt="" class="me-0 me-md-2" /><span class="d-none d-md-inline-flex">Edit</span>
                                    </a>
                                    <a href="javascript:void()"
                                    class="btn btn-info btn-md rounded-50 w-100" data-bs-toggle="modal"
                                    data-bs-target="#award_achievements_modal"><img
                                        src="{{ asset('assets/frontend/images/icons/plus-icon.svg') }}"
                                        class="img-fluid me-0 me-sm-2" /><span
                                        class="d-none d-sm-inline-flex">Add</span></a>
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
                                @foreach($doctor_profile as $list )
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="mb-2">Award Name</label>
                                            <input type="text" class="form-control form-input-control"
                                                placeholder="{{ $list->award_name ?? 'N/A' }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="mb-2">Awarded Year</label>
                                            <input type="number" class="form-control form-input-control"
                                                placeholder="{{ $list->awarded_year ?? 'N/A' }}" disabled>
                                        </div>
                                    </div>
                                    @if(!empty($list->award_certificate) && file_exists(public_path('uploads/doctors/' . $list->award_certificate)))
                                        @php
                                            $imageUrl  = asset('uploads/doctors/' . $list->award_certificate);
                                        @endphp
                                            <div class="col-lg-4 text-center">
                                                <label class="mb-2">Award Certificate</label>
                                                <div class="mb-3">
                                                    <img src="{{ $imageUrl }}" alt="Award Certificate" style="max-width: 40%; height: 40%;">
                                                </div>
                                            </div>
                                    @endif
                                @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </section>

    </main>
     <!-- Modal -->
    <div class="modal fade" id="award_achievements_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-m modal-dialog-centered">
            <div class="modal-content rounded-24">
                <div class="modal-header border-0">
                    <h3 class="modal-title font-regular" id="staticBackdropLabel">Award & Achievements</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body mt-4">
                    <form action="{{ route('doctor.AwardAchievementsStore') }}" id="edit_award_achievements_info" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="row gy-3">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="mb-2">Award Name</label>
                                    <input type="text" class="form-control form-input-control" name="award_name"
                                        value="" required>
                                </div>
                                <div class="invalid-feedback">
                                    Please enter Award Name
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="awarded_year" class="mb-2">Awarded Year</label>
                                    <input type="number" class="form-control form-input-control" name="awarded_year"
                                    value="" required>
                                </div>
                                <div class="invalid-feedback">
                                    Please enter Awarded Year
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="mb-2">Upload Certificate</label>
                                    <input type="file" class="form-control form-input-control"
                                    placeholder="" name="award_certificate" required>
                                </div>
                                <div class="invalid-feedback">
                                    Please Upload Certificate
                                </div>
                            </div>
                        </div>
                        <div class="my-5">
                            <button type="submit" class="btn btn-info me-2 font-regular">Add</button>
                            <button type="button" class="btn btn-cancel font-size-16 font-regular"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
