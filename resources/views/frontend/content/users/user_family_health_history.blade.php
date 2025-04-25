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
                <div class="row">

                    <div class="col-xl-3">
                        <!--SIDE TAB-->
                        @include('frontend.includes.user-side-navbar')
                        <!--SIDE TAB-->
                    </div>

                    <div class="col-xl-9">
                        <div class="hospital-list-block my-favourite-hospital frame">
                            <h3 class="font-medium"> Family Health History</h3>
                            <form autocomplete="off">
                                <div class="row mb-4 g-2 align-items-center">
                                    <div class="col-md-4">
                                        <div class="autocomplete">
                                            <div class="search-widget bg-light border">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" placeholder="Search "
                                                        id="fhh_title">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="search-widget search-symptoms bg-light border">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="fhh_description"
                                                    placeholder="Title or desctiption">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2"><a href="javascript:void()"
                                            class="btn btn-info btn-md rounded-50 w-100" data-bs-toggle="modal"
                                            data-bs-target="#add_family_health_history"><img
                                                src="{{ asset('assets/frontend/images/icons/plus-icon.svg') }}"
                                                class="img-fluid me-0 me-sm-2" /><span
                                                class="d-none d-sm-inline-flex">Add</span></a></div>
                                </div>
                            </form>
                            <div id='show_messages'>
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
                            </div>
                            <div class="table-responsive medicine-table">
                                <table id="familyHealthHistory" class="table mb-0 medicine-table">
                                    <thead>
                                        <tr>
                                            <th>id</th>
                                            <th>Title </th>
                                            <th>Description</th>
                                            <th>ACTION </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </section>


    </main>
    <!--/MAIN-->
    @include('frontend.includes.user-footer')
    <!-- Modal -->
    <div class="modal fade" id="add_family_health_history" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-m modal-dialog-centered">
            <div class="modal-content rounded-24">
                <div class="modal-header border-0">
                    <h3 class="modal-title font-regular" id="staticBackdropLabel"> Family Health History</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body mt-4">
                    <form id="addFamilyHealthHistory" method="POST" action="{{ route('user.familyHealthHistoryStore') }}">
                        @csrf
                        <div class="row gy-3">
                            <div class="col-lg-12">
                                <label class="mb-2">Title<span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-input-control" name="title"
                                    placeholder="Enter Title" required>
                            </div>
                            <div class="col-lg-12">
                                <label class="mb-2">Description <span class="text-danger">*</span></label>
                                <input class="form-control form-input-control" name="description"
                                    placeholder="Enter Description" required>
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
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-m modal-dialog-centered">
            <div class="modal-content rounded-24">
                <div class="modal-header border-0">
                    <h3 class="modal-title font-regular" id="staticBackdropLabel">Edit Family Health History</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body mt-4">
                    <form id="editForm">
                        <input type="hidden" id="fhh_id" name="fhh_id">
                        @csrf
                        <div class="row gy-3">
                            <div class="col-lg-12">
                                <label class="mb-2">Title<span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-input-control" id="title"
                                    name="title" placeholder="Enter Title" required>
                            </div>
                            <div class="col-lg-12">
                                <label class="mb-2">Description <span class="text-danger">*</span></label>
                                <input class="form-control form-input-control" id="description" name="description"
                                    placeholder="Enter Description" required>
                            </div>
                        </div>

                        <div class="my-5">
                            <button type="submit" class="btn btn-info me-2 font-regular">Update</button>
                            <button type="button" class="btn btn-cancel font-size-16 font-regular"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        var table = $('#familyHealthHistory').DataTable({
            ajax: {
                url: '{{ route('user.getFamilyHealthHistory') }}',
                dataSrc: 'data'
            },
            lengthMenu: [50, 100, 200, 400, 500, "Display All"],
            order: [
                [0, 'desc']
            ],
            columnDefs: [{
                targets: 0, // Target the first column (index 0)
                visible: false, // Hide the first column
                searchable: false, // Disable search for the first column (optional)
            }, ],
            columns: [{
                    data: 0
                },
                {
                    data: 1
                },
                {
                    data: 2
                },
                {
                    data: 3
                }
            ]
        });
        $(document).ready(function() {

            //filter
            $('#fhh_title').on('keyup', function() {
                table.column(0).search(this.value).draw();
            });
            $('#fhh_description').on('keyup', function() {
                table.column(1).search(this.value).draw();
            });
            // Handle form submission (to update data)
            $('#editForm').on('submit', function(e) {
                e.preventDefault();

                var fhhID = $('#fhh_id').val(); // Get the ID from the edit button
                var updateUrl = '{{ route('user.updateFamilyHealthHistory', ':id') }}'.replace(':id',
                    fhhID);
                // Send the updated data to the server via AJAX
                $.ajax({
                    url: updateUrl, // Adjust with your update route
                    method: 'PUT',
                    data: $(this).serialize(),
                    success: function(response) {

                        $('#editModal').modal('hide');
                        $('#show_messages').html('<div class="alert alert-success">' + response
                            .message +
                            '</div>');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        // Show validation error messages
                        var errors = xhr.responseJSON.errors;
                        var errorMessages = '';
                        for (var key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                errorMessages += errors[key].join('<br>') + '<br>';
                            }
                        }

                        // Display error messages in your modal or alert box
                        $('#validationErrors').html(
                            errorMessages
                        ); // Assuming you have an element for errors in your modal
                    }
                });
            });
        });
        // Open the modal on Edit button click
        function edit_popup(fhhID) {
            var editUrl = '{{ route('user.editGetFamilyHealthHistory', ':id') }}'.replace(':id', fhhID);
            $.ajax({
                url: editUrl,
                method: 'GET',
                success: function(data) {
                    // Populate the modal fields with the fetched data
                    $('#title').val(data.title);
                    $('#description').val(data.description);
                    $('#fhh_id').val(data.id);

                    // Open the modal
                    $('#editModal').modal('show');
                }
            });
        }

        function delete_fhh(fhhID) {
            // Show confirmation popup
            if (confirm("Are you sure you want to delete this record?")) {
                // Make AJAX request to delete the medicine
                var deleteUrl = '{{ route('user.deleteFamilyHealthHistory', ':id') }}'.replace(':id', fhhID);
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
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        $('#show_messages').html(
                            '<div class="alert alert-danger">An error occurred while deleting the medicine. Please try again.</div>'
                        );
                    }
                });
            } else {
                $('#show_messages').html(
                    '<div class="alert alert-danger">Family Health History deletion was canceled</div>'
                );
            }
        }
    </script>
    <style>
        .dt-layout-row {
            display: none !important;
        }

        .dt-layout-row.dt-layout-table {
            display: flex !important;
            margin: 0 !important;
        }

        div.dt-container.dt-empty-footer tbody>tr:last-child>* {
            border-bottom: 0;
        }

        table.dataTable>thead>tr>th,
        table.dataTable>thead>tr>td {
            border-color: #D9D9D9;
        }

        table.dataTable>thead>tr>th:last-child .dt-column-order {
            display: none;
        }
    </style>
@endsection
