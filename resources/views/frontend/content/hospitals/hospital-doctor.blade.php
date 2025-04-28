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
                            <div class="d-sm-flex justify-content-between align-items-center mb-3">
                                <h3 class="mb-0">Doctors</h3>
                                <form autocomplete="off" class="hospital-search-placeholder">
                                    <div class="d-flex my-4 my-sm-0 align-items-center">
                                        <div class="autocomplete">
                                            <input type="text" id="doctorInput" name="doctor"
                                                class="form-control form-input-control search-control input-md"
                                                placeholder="Search">
                                        </div>
                                        <buttn class="btn btn-info btn-md ms-3" data-bs-toggle="modal"
                                            data-bs-target="#addDoctor"><img
                                                src="{{ asset('assets/frontend/images/icons/plus-icon.svg') }}"
                                                class="d-md-none" height="16" /> <span class="d-none d-md-block">Add
                                                Doctor</span>
                                        </buttn>
                                    </div>
                                </form>
                            </div>


                            <div class="row gy-4">
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="card dr-card rounded-24">
                                        <div class="position-relative">
                                            <img src="{{ asset('assets/frontend/images/dr-img-1.png') }}"
                                                class="card-img-top" alt="...">
                                            <span class="card-chip rounded-6">15+ Years</span>
                                        </div>
                                        <div class="card-body text-center">
                                            <h4 class="card-title">Dr. Meera Patil</h4>
                                            <p class="card-text mb-3">Gynecology</p>
                                            <a href="#" class="btn btn-outline-info btn-xs rounded-4 font-public-sans"
                                                data-bs-toggle="modal" data-bs-target="#editDoctor">Edit Profile</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="card dr-card rounded-24">
                                        <div class="position-relative">
                                            <img src="{{ asset('assets/frontend/images/dr-img-2.png') }}"
                                                class="card-img-top" alt="...">
                                            <span class="card-chip rounded-6">15+ Years</span>
                                        </div>

                                        <div class="card-body text-center">
                                            <h4 class="card-title">Dr. Arjun Rao</h4>
                                            <p class="card-text mb-3">Cardiology</p>
                                            <a href="#" class="btn btn-outline-info btn-xs rounded-4 font-public-sans"
                                                data-bs-toggle="modal" data-bs-target="#editDoctor">Edit Profile</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="card dr-card rounded-24">
                                        <div class="position-relative">
                                            <img src="{{ asset('assets/frontend/images/dr-img-3.png') }}"
                                                class="card-img-top" alt="...">
                                            <span class="card-chip rounded-6">15+ Years</span>
                                        </div>
                                        <div class="card-body text-center">
                                            <h4 class="card-title">Dr. Vipin Rao</h4>
                                            <p class="card-text mb-3">Cardiology</p>
                                            <a href="#" class="btn btn-outline-info btn-xs rounded-4 font-public-sans"
                                                data-bs-toggle="modal" data-bs-target="#editDoctor">Edit Profile</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                                    <div class="card dr-card rounded-24 add-dr-card">
                                        <div class="text-center">
                                            <img src="{{ asset('assets/frontend/images/icons/stethoscope-black.svg') }}"
                                                class="card-img-top mb-4" alt="...">
                                            <h4 class="card-title mb-3 text-black">Add more doctors</h4>
                                            <a href="#"
                                                class="btn btn-outline-secondary btn-xs rounded-4 font-public-sans"
                                                data-bs-toggle="modal" data-bs-target="#addDoctor">Add Doctor</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>



            </div>
        </section>


    </main>
    <!--/MAIN-->

    <!-- Modal -->
    <div class="modal fade" id="addDoctor" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content rounded-24">
                <div class="modal-header border-0">
                    <h3 class="modal-title font-regular" id="staticBackdropLabel">Add New Doctors</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body mt-4">
                    <form id="doctorForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="row g-3">
                            <div class="col-lg-3">
                                <div class="panel upload-img-panel mb-3 w-100">
                                    <div class="button_outer">
                                        <div class="msg-box">
                                            <img src="{{ asset('assets/frontend/images/upload-img.png') }}"
                                                class="upload-img" alt="" />
                                        </div>
                                        <div class="processing_bar"></div>
                                    </div>
                                    <div class="error_msg mb-3 font-medium font-size-13"></div>
                                    <div class="uploaded_file_view" id="uploaded_view">
                                        <span class="file_remove"></span>
                                    </div>
                                </div>
                                <div class="mx-lg-2 text-center">
                                    <p class="mb-2 font-size-13">Recommended Resolution</p>
                                    <p class="font-medium font-size-13 mb-4">1080 x 1080 Pixel</p>
                                    <div class="btn_upload btn btn-info font-size-15 me-2 w-100 font-regular">
                                        <input type="file" multiple id="upload_file" name="" multiple>
                                        Change Profile
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-9">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-4">
                                            <label class="mb-2">Doctor Name<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control  form-input-control "
                                                placeholder="Enter report name">
                                        </div>

                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-4">
                                            <label class="mb-2">Experiences<span class="text-danger">*</span></label>
                                            <select class="selectpicker form-select form-input-control w-100"
                                                title="Experiences">
                                                <option>Experiences-1</option>
                                                <option>Experiences-2</option>
                                                <option>Experiences-3</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="mb-4">
                                            <label class="mb-2">Specialisation<span class="text-danger">*</span></label>
                                            <select data-placeholder="Specialisation" name="tags[]" multiple
                                                class="chosen-select form-select form-input-control w-100">
                                                <option value="Engineering">Engineering</option>
                                                <option value="Carpentry">Carpentry</option>
                                                <option value="Plumbing">Plumbing</option>
                                                <option value="Electical">Electrical</option>
                                                <option value="Mechanical">Mechanical</option>
                                                <option value="HVAC">HVAC</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="mb-2">Degree<span class="text-danger">*</span></label>
                                            <div class="row">
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control  form-input-control "
                                                        placeholder="Give a short profile about the doctor...">
                                                </div>
                                                <div class="col-sm-2 mt-3 mt-sm-0 text-end text-sm-start">
                                                    <button class="btn btn-outline-info">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="mb-2">Short Introduction<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control  form-input-control "
                                                placeholder="Give a short profile about the doctor...">
                                            <p class="text-end mb-0 font-size-14 gray-70 mt-1">0 / 250 words</p>
                                        </div>
                                    </div>

                                    <div class="mt-5 text-end">
                                        <button class="btn btn-cancel font-size-16 font-regular me-2 "
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button class="btn btn-info font-regular">Save</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="doctorModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="doctorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content rounded-24">
                <div class="modal-header border-0">
                    <h3 class="modal-title font-regular" id="doctorModalLabel">
                        <span id="modalTitle">Add Doctor</span>
                    </h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body mt-4">
                    <form id="doctorForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <!-- Hidden Input for Edit -->
                        <input type="hidden" id="doctorId" name="doctorId" value="">

                        <div class="row g-3">
                            <div class="col-lg-3">
                                <div class="panel upload-img-panel mb-3 w-100">
                                    <div class="button_outer">
                                        <div class="msg-box">
                                            <img id="profileImage"
                                                src="{{ asset('assets/frontend/images/upload-img.png') }}"
                                                class="upload-img" alt="" />
                                        </div>
                                        <div class="processing_bar"></div>
                                    </div>
                                    <div class="error_msg mb-3 font-medium font-size-13"></div>
                                    <div class="uploaded_file_view" id="uploaded_view1">
                                        <span class="file_remove"></span>
                                    </div>
                                </div>
                                <div class="mx-lg-2 text-center">
                                    <p class="mb-2 font-size-13">Recommended Resolution</p>
                                    <p class="font-medium font-size-13 mb-4">1080 x 1080 Pixel</p>
                                    <div class="btn_upload btn btn-info font-size-15 me-2 w-100 font-regular">
                                        <input type="file" id="upload_file1" name="profileImage">
                                        Change Profile
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-9">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-4">
                                            <label class="mb-2">Doctor Name<span class="text-danger">*</span></label>
                                            <input type="text" id="doctorName" name="doctorName"
                                                class="form-control form-input-control" placeholder="Enter doctor's name">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-4">
                                            <label class="mb-2">Experiences<span class="text-danger">*</span></label>
                                            <select id="experience" name="experience"
                                                class="selectpicker form-select form-input-control w-100"
                                                title="Select Experience">
                                                <option>Experience-1</option>
                                                <option>Experience-2</option>
                                                <option>Experience-3</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-4">
                                            <label class="mb-2">Specialisation<span class="text-danger">*</span></label>
                                            <select id="specialisation" name="specialisation[]" multiple
                                                class="chosen-select form-select form-input-control w-100">
                                                <option value="Engineering">Engineering</option>
                                                <option value="Carpentry">Carpentry</option>
                                                <option value="Plumbing">Plumbing</option>
                                                <option value="Electrical">Electrical</option>
                                                <option value="Mechanical">Mechanical</option>
                                                <option value="HVAC">HVAC</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="mb-2">Short Introduction<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="shortIntro" name="shortIntro"
                                                class="form-control form-input-control"
                                                placeholder="Give a short profile about the doctor...">
                                            <p class="text-end mb-0 font-size-14 gray-70 mt-1">0 / 250 words</p>
                                        </div>
                                    </div>
                                    <div class="mt-5 text-end">
                                        <button type="button" class="btn btn-cancel font-size-16 font-regular me-2"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-info font-regular"
                                            id="saveButton">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    @include('frontend.includes.hospital-footer')
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/chosen.css') }}" />
    <script src="{{ asset('assets/frontend/js/chosen.jquery.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const doctorModal = new bootstrap.Modal(document.getElementById("doctorModal"));

            // Open Modal for Add
            document.getElementById("addDoctorBtn").addEventListener("click", function() {
                resetModal();
                document.getElementById("modalTitle").textContent = "Add Doctor";
                doctorModal.show();
            });

            // Open Modal for Edit
            document.querySelectorAll(".editDoctorBtn").forEach((button) => {
                button.addEventListener("click", function() {
                    const doctorId = this.getAttribute("data-id");
                    loadDoctorData(doctorId); // Fetch doctor data and populate fields
                    document.getElementById("modalTitle").textContent = "Edit Doctor";
                    doctorModal.show();
                });
            });

            // Reset Modal Fields
            function resetModal() {
                document.getElementById("doctorForm").reset();
                document.getElementById("doctorId").value = "";
                document.getElementById("profileImage").src =
                    "{{ asset('assets/frontend/images/upload-img.png') }}";
            }

            // Load Doctor Data (Simulated)
            function loadDoctorData(id) {
                // Simulate an API response
                const doctorData = {
                    id             : 1,
                    name           : "John Doe",
                    experience     : "Experience-1",
                    specialisation : ["Engineering", "Plumbing"],
                    shortIntro     : "Experienced in multiple specializations.",
                    profileImage   : "/path/to/profile/image.jpg",
                };

                document.getElementById("doctorId").value       = doctorData.id;
                document.getElementById("doctorName").value     = doctorData.name;
                document.getElementById("experience").value     = doctorData.experience;
                document.getElementById("specialisation").value = doctorData.specialisation;
                document.getElementById("shortIntro").value     = doctorData.shortIntro;
                document.getElementById("profileImage").src     = doctorData.profileImage;
            }

            // Add or Update Doctor
            function saveDoctor(url, formData) {
                alert(url);
                return false;
                $.ajax({
                    url         : url,
                    type        : 'POST',
                    data        : formData,
                    contentType : false,
                    processData : false,
                    success     : function(response) {
                        if (response.success) {
                            alert(response.message);
                            // Optionally reload the data or update the table
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('An error occurred: ' + xhr.responseJSON.message);
                    },
                });
            }

            // Example Usage for Add Doctor
            $('#doctorForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                saveDoctor("{{ route('hospital.doctor.store') }}", formData);
            });

            // Example Usage for Update Doctor
            $('#editDoctorForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                saveDoctor('/doctors/update/' + $('#doctor_id').val(), formData);
            });

        });
    </script>
    <script>
        $(document).ready(function() {
            $(".chosen-select").chosen();
        });

        var btnUpload = $("#upload_file"),
            btnOuter = $(".button_outer");
        btnUpload.on("change", function(e) {
            var ext = btnUpload.val().split('.').pop().toLowerCase();
            if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                $(".error_msg").text("Not an Image...");
            } else {
                $(".error_msg").text("");
                btnOuter.addClass("file_uploading");
                setTimeout(function() {
                    btnOuter.addClass("file_uploaded");
                }, 3000);
                var uploadedFile = URL.createObjectURL(e.target.files[0]);
                setTimeout(function() {
                    $("#uploaded_view").append('<img src="' + uploadedFile + '" />').addClass("show");
                }, 3500);
            }
        });
        $(".file_remove").on("click", function(e) {
            $("#uploaded_view").removeClass("show");
            $("#uploaded_view").find("img").remove();
            btnOuter.removeClass("file_uploading");
            btnOuter.removeClass("file_uploaded");
        });

        var btnUpload1 = $("#upload_file1"),
            btnOuter1 = $(".button_outer");
        btnUpload1.on("change", function(e) {
            var ext = btnUpload1.val().split('.').pop().toLowerCase();
            if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                $(".error_msg").text("Not an Image...");
            } else {
                $(".error_msg").text("");
                btnOuter1.addClass("file_uploading");
                setTimeout(function() {
                    btnOuter1.addClass("file_uploaded");
                }, 3000);
                var uploadedFile1 = URL.createObjectURL(e.target.files[0]);
                setTimeout(function() {
                    $("#uploaded_view1").append('<img src="' + uploadedFile1 + '" />').addClass("show");
                }, 3500);
            }
        });
        $(".file_remove").on("click", function(e) {
            $("#uploaded_view1").removeClass("show");
            $("#uploaded_view1").find("img").remove();
            btnOuter1.removeClass("file_uploading");
            btnOuter1.removeClass("file_uploaded");
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
        var doctor = ["Annnu", "Babita", "Simran", "Vipin", "Meena", "Seema"];

        /*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
        autocomplete(document.getElementById("doctorInput"), doctor);
    </script>
@endsection
