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
                            <h3 class="font-medium">Social Media</h3>

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
                            <form id="socialMediaForm" method="POST" action="{{ route('doctor.social-media-store') }}">
                                @csrf
                                <div class="row gy-3">
                                    <div class="col-lg-12">
                                        <label class="mb-2"> Youtube Link<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-input-control" name="youtube_link"
                                            placeholder="Enter Youtube Link" value="{{ $social_media->youtube_link ?? '' }}">
                                    </div>
                                    <div class="col-lg-12">
                                        <label class="mb-2"> Facebook Link<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-input-control" name="facebook_link"
                                            placeholder="Enter Facebook Link" value="{{ $social_media->facebook_link ?? '' }}">
                                    </div>
                                    <div class="col-lg-12">
                                        <label class="mb-2"> Linkedin Link<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-input-control" name="linkdin_link"
                                            placeholder="Enter Linkedin Link" value="{{ $social_media->linkdin_link ?? '' }}">
                                    </div>
                                    <div class="col-lg-12">
                                        <label class="mb-2"> Instagram Link<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-input-control" name="instagram_link"
                                            placeholder="Enter Instagram Link" value="{{ $social_media->instagram_link ?? '' }}">
                                    </div>
                                    <div class="col-lg-12">
                                        <label class="mb-2"> Whatsapp Link<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-input-control" name="whatsapp_link"
                                            placeholder="Enter Whatsapp Link" value="{{ $social_media->whatsapp_link ?? '' }}">
                                    </div>
                                    <div class="col-lg-12">
                                        <label class="mb-2"> Telegram Link<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-input-control" name="telegram_link"
                                            placeholder="Enter Telegram Link" value="{{ $social_media->telegram_link ?? '' }}">
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
        </section>


    </main>
    <!--/MAIN-->
    @include('frontend.includes.user-footer')



    <script>
        var table = $('#socialMedia').DataTable({
            ajax: {
                url: '{{ route('doctor.getSocialMedia') }}',
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
                },
                {
                    data: 4
                },
                {
                    data: 5
                }
            ]
        });
        $(document).ready(function() {

            //filter
            $('#searchSocialMediaName').on('keyup', function() {
                table.column(1).search(this.value).draw();
            });
            $('#searchSocialMediaIcon').on('keyup', function() {
                table.column(2).search(this.value).draw();
            });
            
        });
   


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
        var medicine = ["Codeine", "Sulfa Drugs", "Amoxicillin", "Aspirin", "Ibuprofen", "Penicillin"];

        /*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
        autocomplete(document.getElementById("medicineInput"), medicine);
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
