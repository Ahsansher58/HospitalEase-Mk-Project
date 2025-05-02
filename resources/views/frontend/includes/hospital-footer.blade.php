<!--Start: Scroll button to top -->
<button type="button" id="scrollTop"><i class="fa fa-angle-up"></i></button>
<!--End: Scroll button to top -->

<!-- Slider JS -->
<script src="{{ asset('assets/frontend/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/frontend/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/frontend/js/bootstrap-select.js') }}"></script>



<!-- jQuery UI -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<!-- Slider JS -->
<script src="{{ asset('assets/frontend/js/slick.min.js') }}"></script>

<!-- Custom JS -->
<script src="{{ asset('assets/frontend/js/custom.js') }}"></script>


<!-- Form Validation JS -->
<script src="{{ asset('assets/frontend/js/validation.js') }}"></script>

<!-- Calander JS -->
<script src="{{ asset('assets/frontend/js/flatpickr.js') }}"></script>
<script src="{{ asset('assets/frontend/js/forms-pickers.js') }}"></script>

<script src="{{ asset('assets/frontend/js/dataTables.js') }}"></script>

<script>
    $(".toggle-password").click(function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
</script>


<script>
    var btnUpload = $("#upload_file"),
        btnOuter = $(".button_outer");
    btnUpload.on("change", function(e) {
        var ext = btnUpload.val().split('.').pop().toLowerCase();

        // Check if the uploaded file has a valid extension
        if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg', 'pdf']) == -1) {
            $(".error_msg").text("Not a valid file. Allowed formats are gif, png, jpg, jpeg, pdf.");
        } else {
            $(".error_msg").text(""); // Clear the error message
            btnOuter.addClass("file_uploading"); // Add uploading class

            // Simulate upload delay and show uploaded file
            setTimeout(function() {
                btnOuter.addClass("file_uploaded"); // Add uploaded class after timeout
            }, 3000);

            // Get the uploaded file (only the first file in case of multiple files selected)
            var uploadedFile = e.target.files[0];

            if (uploadedFile) {
                // Check if the uploaded file is an image
                if (['gif', 'png', 'jpg', 'jpeg'].includes(ext)) {
                    var fileURL = URL.createObjectURL(uploadedFile);
                    setTimeout(function() {
                        $("#uploaded_view").html('<img src="' + fileURL + '" alt="Uploaded Image" />')
                            .addClass("show");
                    }, 3500);
                }
                // If the file is a PDF, display an icon or text
                else if (ext === 'pdf') {
                    var pdfIconUrl = "{{ asset('assets/frontend/images/icons/pdf-icon.png') }}";
                    setTimeout(function() {
                        $("#uploaded_view").html(
                                '<img src="' + pdfIconUrl + '" alt="PDF File" />'
                            )
                            .addClass("show");
                    }, 3500);
                }
            }
        }
    });

    $(".file_remove").on("click", function(e) {
        $("#uploaded_view").removeClass("show");
        $("#uploaded_view").find("img").remove();
        btnOuter.removeClass("file_uploading");
        btnOuter.removeClass("file_uploaded");
    });

   {{--  function autocomplete(inp, arr) {
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
    var report = ["AB", "x-ray", "CT Scan", "Blood Test"];

    /*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
    autocomplete(document.getElementById("myInput"), report); --}}
</script>
<script>
    // Automatically hide success alert after 5 seconds
    setTimeout(function() {
        $('.alert-success').fadeOut('slow');
    }, 5000); // 5000 milliseconds = 5 seconds

    // Automatically hide error alert after 5 seconds
    setTimeout(function() {
        $('.alert-danger').fadeOut('slow');
    }, 5000); // 5000 milliseconds = 5 seconds
</script>
