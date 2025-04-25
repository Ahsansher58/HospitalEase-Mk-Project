@extends('frontend.layouts.after-login-users')

@section('title', 'Hospital Ease - Dashboard')
@include('frontend.includes.favicon')
@section('content')
    @include('frontend.includes.after-login-header')
    <!--MAIN-->
    <main class="inner-page">
        <!--BANNER-->
        @include('frontend.includes.user-top-banner')
        <!--/BANNER-->

        <section class="pb-5">
            <div class="container">
                <!--BLOCK-->
                <div class="row">
                    <div class="col-xl-3">
                        <!--SIDE TAB-->
                        @include('frontend.includes.user-side-navbar')
                        <!--SIDE TAB-->
                    </div>

                    <div class="col-xl-9">
                        <div class="hospital-list-block my-favourite-hospital frame">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h3 class="font-medium mb-0">My Medical Records</h3>
                                <button class="btn btn-info rounded-50" data-bs-toggle="modal"
                                    data-bs-target="#addRecord"><img
                                        src="{{ asset('assets/frontend/images/icons/plus-icon.svg') }}"
                                        class="img-fluid me-0 me-md-2" /><span class="d-none d-md-inline-flex">Add New
                                        Record</span>
                                </button>
                            </div>

                            <!-- Nav tabs -->
                            <div class="scroll-x mb-4">
                                <ul class="nav nav-tabs nav-tab-2 border-0">
                                    <li class="nav-item">
                                        <a class="nav-link btn btn-info active" data-bs-toggle="tab" href="#all">All</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link " data-bs-toggle="tab" href="#prescriptions">Prescriptions</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#lab_reports">Lab Reports</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#xrays_imaging">X-rays/Imaging</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#medical_history">Medical History</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#vaccination_records">Vaccination
                                            Records</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- /Nav tabs -->
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <!-- Check if an error message exists -->
                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <!--TAB CONTNET-->
                            <div class="tab-content p-0 border-0 w-max-100">
                                <div class="tab-pane pe-0 pb-0 active border-0" id="all">
                                    <!--BLOCK-->
                                    <div class="row g-3">
                                        @if ($allMedicalRecords->isEmpty())
                                            <div class="col-12">
                                                <p class="text-center">No medical records found.</p>
                                            </div>
                                        @else
                                            @foreach ($allMedicalRecords as $record)
                                                @include('frontend.content.users.record-card', [
                                                    'record' => $record,
                                                ])
                                            @endforeach
                                        @endif
                                    </div>
                                    <!--/BLOCK-->
                                </div>

                                <div class="tab-pane pb-0 pe-0 border-0" id="prescriptions">
                                    <!--BLOCK-->
                                    <div class="row g-3">
                                        @if ($prescriptions->isEmpty())
                                            <div class="col-12">
                                                <p class="text-center">No medical records found.</p>
                                            </div>
                                        @else
                                            @foreach ($prescriptions as $record)
                                                @include('frontend.content.users.record-card', [
                                                    'record' => $record,
                                                ])
                                            @endforeach
                                        @endif
                                    </div>
                                    <!--/BLOCK-->
                                </div>

                                <div class="tab-pane pb-0 pe-0 border-0" id="lab_reports">
                                    <!--BLOCK-->
                                    <div class="row g-3">
                                        @if ($labReports->isEmpty())
                                            <div class="col-12">
                                                <p class="text-center">No medical records found.</p>
                                            </div>
                                        @else
                                            @foreach ($labReports as $record)
                                                @include('frontend.content.users.record-card', [
                                                    'record' => $record,
                                                ])
                                            @endforeach
                                        @endif
                                    </div>
                                    <!--/BLOCK-->
                                </div>

                                <div class="tab-pane pe-0 pb-0 border-0" id="xrays_imaging">
                                    <!--BLOCK-->
                                    <div class="row g-3">
                                        @if ($xraysImaging->isEmpty())
                                            <div class="col-12">
                                                <p class="text-center">No medical records found.</p>
                                            </div>
                                        @else
                                            @foreach ($xraysImaging as $record)
                                                @include('frontend.content.users.record-card', [
                                                    'record' => $record,
                                                ])
                                            @endforeach
                                        @endif
                                    </div>
                                    <!--/BLOCK-->
                                </div>

                                <div class="tab-pane pe-0 pb-0 border-0" id="medical_history">
                                    <!--BLOCK-->
                                    <div class="row g-3">
                                        @if ($medicalHistory->isEmpty())
                                            <div class="col-12">
                                                <p class="text-center">No medical records found.</p>
                                            </div>
                                        @else
                                            @foreach ($medicalHistory as $record)
                                                @include('frontend.content.users.record-card', [
                                                    'record' => $record,
                                                ])
                                            @endforeach
                                        @endif
                                    </div>
                                    <!--/BLOCK-->
                                </div>

                                <div class="tab-pane pe-0 pb-0 border-0" id="vaccination_records">
                                    <!--BLOCK-->
                                    <div class="row g-3">
                                        @if ($vaccinationRecords->isEmpty())
                                            <div class="col-12">
                                                <p class="text-center">No medical records found.</p>
                                            </div>
                                        @else
                                            @foreach ($vaccinationRecords as $record)
                                                @include('frontend.content.users.record-card', [
                                                    'record' => $record,
                                                ])
                                            @endforeach
                                        @endif
                                    </div>
                                    <!--/BLOCK-->
                                </div>
                            </div>
                            <!--/TAB CONTNET-->
                        </div>
                    </div>
                </div>
                <!--/BLOCK-->
            </div>
        </section>
    </main>
    <!--/MAIN-->

    <!-- Modal -->
    <div class="modal fade" id="addRecord" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content rounded-24">
                <div class="modal-header border-0">
                    <h3 class="modal-title font-regular" id="staticBackdropLabel">Add New Record</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body mt-4">
                    <form id="medicalRecordForm" method="POST" action="{{ route('user.medicalRecords.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row gy-3">
                            <div class="col-lg-4">
                                <label class="mb-2">Category<span class="text-danger">*</span></label>
                                <select name="report_category" class="selectpicker form-select form-input-control w-100"
                                    title="Choose Category" required>
                                    <option value="prescriptions">Prescriptions</option>
                                    <option value="lab_reports">Lab Reports</option>
                                    <option value="xrays_imaging">X-rays/Imaging</option>
                                    <option value="medical_history">Medical History</option>
                                    <option value="vaccination_records">Vaccination Records</option>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label class="mb-2">Report Name<span class="text-danger">*</span></label>
                                <input type="text" name="report_name" class="form-control form-input-control"
                                    placeholder="Enter report name" required>
                            </div>
                            <div class="col-lg-4">
                                <label class="mb-2">Report Date<span class="text-danger">*</span></label>
                                <input type="date" name="report_date"
                                    class="form-control form-input-control form-select me-lg-2"
                                    placeholder="Choose report date" id="report_date" required>
                            </div>
                        </div>

                        <div class="my-5">
                            <h4 class="mb-2">Upload Report File</h4>
                            <p class="font-regular font-size-13 gray-70">You can upload one or more images</p>

                            <div
                                class="d-flex align-items-center justify-content-center bg-light p-3 p-md-5 upload-frame mb-5">
                                <div class="text-center">
                                    <div class="panel">
                                        <div class="button_outer">
                                            <div class="msg-box">
                                                <img src="{{ asset('assets/frontend/images/icons/upload-icon.svg') }}"
                                                    class="img-fluid mb-3" alt="" />
                                                <h5 class="mb-2">Browse and choose the files you want to upload</h5>
                                                <p class="font-regular font-size-13 gray-70 mb-3">File type: JPG, PDF, PNG
                                                </p>
                                            </div>
                                            <div class="processing_bar"></div>
                                        </div>
                                    </div>
                                    <div class="error_msg mb-3 font-medium font-size-13"></div>
                                    <div class="uploaded_file_view" id="uploaded_view">
                                        <span class="file_remove"><img
                                                src="{{ asset('assets/frontend/images/icons/close.svg') }}" alt=""
                                                width="20" height="20" /></span>
                                    </div>
                                    <div class="btn_upload btn btn-info btn-sm font-size-15 me-2">
                                        <input type="file" name="report_file" id="upload_file" required>
                                        Select file
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-info me-2 font-regular">Save</button>
                            <button type="button" class="btn btn-cancel font-size-16 font-regular"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('frontend.includes.user-footer')
    <script>
        document.getElementById('report_date').valueAsDate = new Date();

        function delete_report(reportId) {
            // Show confirmation popup
            if (confirm("Are you sure you want to delete this Report?")) {
                // Make AJAX request to delete the medicine
                var deleteUrl = '{{ route('user.medicalRecords.delete', ':id') }}'.replace(':id', reportId);
                var token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: deleteUrl,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    success: function(response) {
                        $('#show_messages').html('<div class="alert alert-success">' + response.message +
                            '</div>');
                        location.reload();
                    },
                    error: function(xhr) {
                        $('#show_messages').html(
                            '<div class="alert alert-danger">An error occurred while deleting the Report. Please try again.</div>'
                        );
                    }
                });
            } else {
                $('#show_messages').html(
                    '<div class="alert alert-danger">Report deletion was canceled</div>'
                );
            }
        }
    </script>
@endsection
