<!--MAIN-->
@extends('frontend.layouts.after-login-doctors')

@section('title', 'Hospital Ease - Educational Qualifications')
@include('frontend.includes.favicon')
@vite(['resources/assets/js/forms-selects.js', 'resources/assets/js/extended-ui-sweetalert2.js'])
    @include('content.scripts.script-users');
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
                                    <a href="javascript:void()" class="btn btn-info rounded-50 mx-2">
                                        <img src="images/icons/edit-2.svg" alt="" class="me-0 me-md-2" /><span class="d-none d-md-inline-flex" data-bs-toggle="modal"
                                            data-bs-target="#educational_qualifications_modal">Add More</span>
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
                                    @if(count($doctorEducationalQualification) > 0)
                                        @foreach($doctorEducationalQualification as $list)
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label class="mb-2">College Name</label>
                                                    <input type="text" class="form-control form-input-control" placeholder="{{ $list->college_name ?? 'N/A' }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label class="mb-2">Years Studied</label>
                                                    <input type="number" class="form-control form-input-control"
                                                        placeholder="{{ $list->year_studied ?? 'N/A' }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label class="mb-2">Degree Name</label>
                                                    <input type="text" class="form-control form-input-control"
                                                        placeholder="{{ $list->degree ?? 'N/A' }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 d-flex align-items-center mt-4">
                                                <div class="mb-3">
                                                    <a href="{{ route('doctor.editEducationalQualifications' ,$list->id ) }}" class="btn btn-info text-center btn-sm">
                                                        <img src="images/icons/ edit-2.svg" alt="" class="me-0 me-md-2" /><span class="d-none d-md-inline-flex">Edit</span>
                                                    </a>
                                                    <a href='javascript:void()'
                                                    class='btn btn-danger text-center btn-sm'
                                                    onclick='delete_educational_qualification({{$list->id}})'>
                                                    <i class='menu-icon tf-icons ti ti-trash'></i> Remove
                                                </a>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="row">
                                            <div class="col-lg-12 text-center">
                                                <h6 class="text-center">No Data Found</h6>        
                                            </div>      
                                        </div>
                                    @endif
                                </div>
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

    <!-- Modal -->
    <div class="modal fade" id="educational_qualifications_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-m modal-dialog-centered">
            <div class="modal-content rounded-24">
                <div class="modal-header border-0">
                    <h3 class="modal-title font-regular" id="staticBackdropLabel">Educational Qualifications</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body mt-4">
                    <form id="AppointmentForm" method="POST" action="{{ route('doctor.EducationalQualificationsStore') }}"  enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="mb-2">College Name</label>
                                            <input type="text" class="form-control form-input-control" name="college_name" required>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please enter College Name
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="year_studied" class="mb-2">Years Studied</label>
                                            <input type="number" class="form-control form-input-control" name="year_studied" required>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please enter Years Studied
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="degree" class="mb-2">Degree Name</label>
                                            <input type="text" class="form-control form-input-control"
                                                name="degree" id="degree" required>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please enter Degree Name
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="mb-2">Upload Certificate</label>
                                            <input type="file" class="form-control form-input-control"
                                            placeholder="" name="award_certificate" required>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please Upload Certificate
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="form-label" for="show_certificate_in_public">Show Certificate in public?</label>
                                        <div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="show_certificate_in_public_yes"
                                                    name="show_certificate_in_public" value="1">
                                                <label class="form-check-label" for="show_certificate_in_public_yes">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" id="show_certificate_in_public_no"
                                                    name="show_certificate_in_public" value="0">
                                                <label class="form-check-label" for="show_certificate_in_public_no">No</label>
                                            </div>
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

    @include('frontend.includes.user-footer')

@endsection
