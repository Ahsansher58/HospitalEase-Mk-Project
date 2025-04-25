@extends('frontend.layouts.after-login-users')

@section('title', 'Hospital Ease - My Appointments')
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
                            <h3 class="font-medium">My Appointments</h3>
                            <div class="table-responsive hospital-table">
                                <table id="Appointment" class="table mb-0"></table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>


    </main>
    <!--/MAIN-->
    @include('frontend.includes.user-footer')
    <script>
        var appointmentUrl = "{{ route('user.getappointment', ['id' => auth()->user()->id]) }}";
        var table = new DataTable('#Appointment', {
            processing: true,
            serverSide: true,
            lengthMenu: [50, 100, 200, 400, 500, "Display All"],
            ajax: {
                url: appointmentUrl,
                dataSrc: 'data',
                data: function(d) {
                    d.search['value'] = $('#customSearchBox').val();
                }
            },
            columns: [{
                    title: 'Select',
                    data: 'checkbox'
                },
                {
                    title: 'Appointment Number',
                    data: 'appointment_number'
                },
                {
                    title: 'Hospital Name',
                    data: 'hospital_name'
                },
                {
                    title: 'Phone',
                    data: 'phone_number'
                },
                {
                    title: 'Email',
                    data: 'email'
                },
                {
                    title: 'Appointment Date',
                    data: 'appointment_date'
                }
                /* ,
                                {
                                    title: 'Actions',
                                    data: 'actions'
                                } */
            ],
            order: [
                [0, 'desc']
            ],
            pageLength: 10,
        });

        // Trigger the search on typing in the custom search box
        $('#customSearchBox').on('keyup', function() {
            table.draw();
        });

        $(document).ready(function() {
            $('.selectpicker').on('change', function() {
                const exportType = $(this).val();
                if (exportType === 'CSV') {
                    window.location.href = '{{ route('hospital.export.csv') }}';
                }
            });
        });

        /* function deleteAppointment(appointmentId) {
            var deleteAppointmentURL = "{{ route('user.appointments.destroy', ':id') }}".replace(':id', appointmentId);
            if (confirm('Are you sure you want to delete this appointment?')) {
                $.ajax({
                    url: deleteAppointmentURL,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Add CSRF token
                    },
                    success: function(response) {
                        alert(response.message);
                        table.draw();
                    },
                    error: function(xhr) {
                        alert('Failed to delete appointment. Please try again.');
                    }
                });
            }
        } */
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
