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
                                <h3 class="mb-0">Reviews</h3>
                            </div>

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

                                <table id="Reviews" class="table mb-0 hospital-review-table">
                                    <thead>
                                        <tr>
                                            <th style="min-width:180px">Name </th>
                                            <th>Comments</th>
                                            <th style="min-width:150px">Reviewed on</th>
                                            <th>Status</th>
                                            <th class="text-center">ACTION </th>
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


    @include('frontend.includes.hospital-footer')
    <script>
        /*Datatable start*/
        var reviewsUrl = "{{ route('hospital.get.reviews', ['id' => auth()->user()->id]) }}";
        var table = new DataTable('#Reviews', {
            processing: true,
            serverSide: true,
            ajax: {
                url: reviewsUrl,
                dataSrc: 'data',
                data: function(d) {
                    // Add custom search box value to the DataTable's AJAX request
                    d.search['value'] = $('#customSearchBox').val();
                }
            },
            columns: [{
                    title: 'id',
                    data: 'id'
                }, {
                    title: 'Name',
                    data: 'username_rating'
                },
                {
                    title: 'Comments',
                    data: 'review'
                },
                {
                    title: 'Review On',
                    data: 'reviewed_on'
                },
                {
                    title: 'Status',
                    data: 'status'
                },
                {
                    title: 'Actions',
                    data: 'actions'
                }
            ],
            order: [
                [0, 'desc']
            ],
            columnDefs: [{
                targets: 0,
                visible: false,
                searchable: false,
            }, ],
            pageLength: 10, // Default pagination length
            lengthMenu: [10, 25, 50], // Dropdown options for number of rows per page
        });

        // Trigger the search on typing in the custom search box
        $('#customSearchBox').on('keyup', function() {
            table.draw();
        });
        /*Datatable END*/

        $(document).ready(function() {
            $('.selectpicker').on('change', function() {
                const exportType = $(this).val();
                if (exportType === 'CSV') {
                    window.location.href = '{{ route('hospital.review.exportcsv') }}';
                }
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
        var food = ["Penicillin", "Shellfish", "Milk", "Wheat", "Meat", "Pulses"];

        /*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
        autocomplete(document.getElementById("foodInput"), food);
    </script>
    <script>
        $(document).on('click', '.view-review', function() {
            var reviewId = $(this).data('id');
            var viewsUrl = "{{ route('reviews.view', ':id') }}".replace(':id', reviewId);

            $.ajax({
                url: viewsUrl,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        var data = response.data;
                        console.log(data.reply);

                        // Populate popup content based on whether a reply exists
                        $('#reviewPopup .modal-title').text(`Review by ${data.user_name}`);
                        if (data.reply) {
                            // If reply exists, show the reply message
                            $('#reviewPopup .modal-body').html(`
                          <p><strong>Rating:</strong> ${data.rating} / 5</p>
                          <p><strong>Comments:</strong> ${data.review}</p>
                          <p><strong>Reviewed On:</strong> ${data.reviewed_on}</p>
                          <p><strong>Your Reply:</strong> ${data.reply}</p>
                        `);
                        } else {
                            // If no reply exists, show the textarea and save button
                            $('#reviewPopup .modal-body').html(`
                          <p><strong>Rating:</strong> ${data.rating} / 5</p>
                          <p><strong>Comments:</strong> ${data.review}</p>
                          <p><strong>Reviewed On:</strong> ${data.reviewed_on}</p>
                          <p>
                              <strong>Reply:</strong>
                              <textarea id="replyText" class="form-control form-input-control"></textarea>
                          </p>
                          <button id="saveReply" class="btn btn-primary mt-2" data-id="${reviewId}">Save Reply</button>
                        `);
                        }

                        $('#reviewPopup').modal('show');
                    }
                },
                error: function() {
                    alert('Failed to fetch review details.');
                }
            });
        });

        $(document).on('click', '#saveReply', function() {
            var reviewId = $(this).data('id');
            var replyText = $('#replyText').val();
            var replyUrl = "{{ route('reviews.reply', ':id') }}".replace(':id', reviewId);

            $.ajax({
                url: replyUrl,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    reply: replyText
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        $('#reviewPopup').modal('hide');
                        // Optionally reload your DataTable or update the UI
                        $('#Reviews').DataTable().ajax.reload();
                    }
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message || 'Failed to save reply.');
                }
            });
        });
    </script>

    <div class="modal fade" id="reviewPopup" tabindex="-1" aria-labelledby="reviewPopupLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewPopupLabel">Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Content populated by AJAX -->
                </div>
            </div>
        </div>
    </div>


@endsection
