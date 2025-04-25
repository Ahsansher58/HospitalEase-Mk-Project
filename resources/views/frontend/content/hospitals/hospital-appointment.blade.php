@extends('frontend.layouts.after-login-hospitals')

@section('title', 'Hospital Ease - Profile')
@include('frontend.includes.favicon')
@section('content')
    @include('frontend.includes.after-login-hospitals-header')

    <!--MAIN-->
    <main class="inner-page">

        <section class="pb-5 pt-lg-5 pt-3">
            <div class="container layout-container">
                <h3 class="text-lg-start text-end mt-2">JV Hospital</h3>
                <div class="d-flex">
                    <!--SIDE TAB-->
                    @include('frontend.includes.hospital-side-navbar')
                    <!--SIDE TAB-->
                    <div class="contnet-wrapper">
                        <div class="hospital-list-block my-favourite-hospital frame">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="mb-0">Appointments</h3>
                                <a href="{{ route('hospital.appointment.setting') }}" class="btn btn-info btn-sm"><img
                                        src="{{ asset('assets/frontend/images/icons/plus-icon.svg') }}" class="d-md-none">
                                    <span class="d-none d-md-block">Appointment Settings</span></a>
                            </div>
                            @if (($appointmentSettings['appointment_type'] ?? '') == 'default_from')
                                <form autocomplete="off" class="mt-4 hospital-search-placeholder">
                                    <div class="row justify-content-between">
                                        <div class="col-xl-10 col-md-8 col-sm-8 ">
                                            <div class="row g-2">
                                                <div class="col-xl-4">
                                                    <div class="autocomplete">
                                                        <input type="text" id="customSearchBox" name="customSearchBox"
                                                            class="form-control form-input-control search-control input-md"
                                                            placeholder="Search">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-xl-2 col-md-3 col-sm-4 mt-2 mt-sm-0">
                                            <select class="selectpicker form-select form-input-control w-100 input-md"
                                                title="Export">
                                                <option>CSV</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>

                                <div class="table-responsive hospital-table">

                                    <table id="Appointment" class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th style="min-width:80px"><input class="form-check-input" type="checkbox"
                                                        value="" id="flexCheckDefault"></th>
                                                <th>Name </th>
                                                <th style="min-width:180px">Mobile</th>
                                                <th>Email Address</th>
                                                <th style="min-width:200px">Appointment Date</th>
                                                <th style="min-width:200px;" class="text-center">ACTION </th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            @endif

                            @if (($appointmentSettings['appointment_type'] ?? '') == 'whats_app')
                                <div class="justify-content-between mt-4 hospital-profile-wrapper">
                                    <div class="row mt-4 mb-3">
                                        <div class="col-md-3">
                                            <h4 class="mb-0">Appointment Type: </h4>
                                        </div>
                                        <div class="col-md-9 mb-2">
                                            <p class="mb-0 ">WhatsApp Account</p>
                                        </div>
                                    </div>
                                    <div class="row mt-4 mb-3">
                                        <div class="col-md-3">
                                            <h4 class="mb-0">WhatsApp Number: </h4>
                                        </div>
                                        <div class="col-md-9">
                                            <p class="mb-0">{{ $appointmentSettings['country_code'] ?? '' }}
                                                {{ $appointmentSettings['whats_number'] ?? '' }}</p>
                                        </div>
                                    </div>
                                    <div class="row mt-4 mb-3">
                                        <div class="col-md-3">
                                            <h4 class="mb-0">Message: </h4>
                                        </div>
                                        <div class="col-md-9">
                                            <p class="mb-0">{{ $appointmentSettings['whats_message'] ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (($appointmentSettings['appointment_type'] ?? '') == 'website_link')
                                <div class="justify-content-between mt-4 hospital-profile-wrapper">
                                    <div class="row mt-4 mb-3">
                                        <div class="col-md-3">
                                            <h4 class="mb-0">Appointment Type: </h4>
                                        </div>
                                        <div class="col-md-9">
                                            <p class="mb-0">Website link / Scheduler link</p>
                                        </div>
                                    </div>
                                    <div class="row mt-4 mb-3">
                                        <div class="col-md-3">
                                            <h4 class="mb-0">Website address: </h4>
                                        </div>
                                        <div class="col-md-9">
                                            <p class="mb-0">{{ $appointmentSettings['website_address'] ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>



            </div>
        </section>


    </main>
    <!--/MAIN-->


    @include('frontend.includes.hospital-footer')
    <script>
        var appointmentUrl = "{{ route('hospital.get.appointment', ['id' => auth()->user()->id]) }}";
        var table = new DataTable('#Appointment', {
            processing: true,
            serverSide: true,
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
                    title: 'Name',
                    data: 'patient_name'
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
                },
                {
                    title: 'Actions',
                    data: 'actions'
                }
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

        function deleteAppointment(appointmentId) {
            var deleteAppointmentURL = "{{ route('hospital.appointments.destroy', ':id') }}".replace(':id', appointmentId);
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
        }
    </script>
    <script>
        function autocomplete(inp, arr) {
            /*the autocomplete function takes two arguments,
            the text field element and an array of possible autocompleted values:*/
            var currentFocus;
            /*execute a function when someone writes in the text field:*/
            inp.addEventListener("input", function(e) {
                var a, b, i, val = this.value;
                /*close any already open lists of autocompleted values*/
                closeAllLists();
                if (!val) {
                    return false;
                }
                currentFocus = -1;
                /*create a DIV element that will contain the items (values):*/
                a = document.createElement("DIV");
                a.setAttribute("id", this.id + "autocomplete-list");
                a.setAttribute("class", "autocomplete-items");
                /*append the DIV element as a child of the autocomplete container:*/
                this.parentNode.appendChild(a);
                /*for each item in the array...*/
                for (i = 0; i < arr.length; i++) {
                    /*check if the item starts with the same letters as the text field value:*/
                    if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                        /*create a DIV element for each matching element:*/
                        b = document.createElement("DIV");
                        /*make the matching letters bold:*/
                        b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                        b.innerHTML += arr[i].substr(val.length);
                        /*insert a input field that will hold the current array item's value:*/
                        b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                        /*execute a function when someone clicks on the item value (DIV element):*/
                        b.addEventListener("click", function(e) {
                            /*insert the value for the autocomplete text field:*/
                            inp.value = this.getElementsByTagName("input")[0].value;
                            /*close the list of autocompleted values,
                            (or any other open lists of autocompleted values:*/
                            closeAllLists();
                        });
                        a.appendChild(b);
                    }
                }
            });
            /*execute a function presses a key on the keyboard:*/
            inp.addEventListener("keydown", function(e) {
                var x = document.getElementById(this.id + "autocomplete-list");
                if (x) x = x.getElementsByTagName("div");
                if (e.keyCode == 40) {
                    /*If the arrow DOWN key is pressed,
                    increase the currentFocus variable:*/
                    currentFocus++;
                    /*and and make the current item more visible:*/
                    addActive(x);
                } else if (e.keyCode == 38) { //up
                    /*If the arrow UP key is pressed,
                    decrease the currentFocus variable:*/
                    currentFocus--;
                    /*and and make the current item more visible:*/
                    addActive(x);
                } else if (e.keyCode == 13) {
                    /*If the ENTER key is pressed, prevent the form from being submitted,*/
                    e.preventDefault();
                    if (currentFocus > -1) {
                        /*and simulate a click on the "active" item:*/
                        if (x) x[currentFocus].click();
                    }
                }
            });

            function addActive(x) {
                /*a function to classify an item as "active":*/
                if (!x) return false;
                /*start by removing the "active" class on all items:*/
                removeActive(x);
                if (currentFocus >= x.length) currentFocus = 0;
                if (currentFocus < 0) currentFocus = (x.length - 1);
                /*add class "autocomplete-active":*/
                x[currentFocus].classList.add("autocomplete-active");
            }

            function removeActive(x) {
                /*a function to remove the "active" class from all autocomplete items:*/
                for (var i = 0; i < x.length; i++) {
                    x[i].classList.remove("autocomplete-active");
                }
            }

            function closeAllLists(elmnt) {
                /*close all autocomplete lists in the document,
                except the one passed as an argument:*/
                var x = document.getElementsByClassName("autocomplete-items");
                for (var i = 0; i < x.length; i++) {
                    if (elmnt != x[i] && elmnt != inp) {
                        x[i].parentNode.removeChild(x[i]);
                    }
                }
            }
            /*execute a function when someone clicks in the document:*/
            document.addEventListener("click", function(e) {
                closeAllLists(e.target);
            });
        }

        /*An array containing all the country names in the world:*/
        var food = ["Penicillin", "Shellfish", "Milk", "Wheat", "Meat", "Pulses"];

        /*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
        autocomplete(document.getElementById("foodInput"), food);
    </script>
@endsection
