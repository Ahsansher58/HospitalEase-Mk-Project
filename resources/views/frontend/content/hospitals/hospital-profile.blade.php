@extends('frontend.layouts.after-login-hospitals')

@section('title', 'Hospital Ease - Profile')
@include('frontend.includes.favicon')
<script>
    let arrayCase6 = [];
    let arrayCase7 = [];
    let arrayCase9 = [];
</script>
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
                        <div class="hospital-list-block my-favourite-hospital frame pb-0">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="mb-0">Hospital Profile</h3>
                                <a href="#" class="btn btn-info btn-sm"><img src="images/icons/edit-2.svg"
                                        alt="" class="me-0 me-md-2" /><span class="d-none d-md-inline-flex">Edit
                                        Profile</span></a>
                            </div>
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <div class="mx--24 hospital-profile-wrapper mt-4">
                                <div class="row">
                                    <div class="col-lg-3 left-nav-wrap scroll-x">
                                        @if ($main_cats->isNotEmpty())
                                            <ul class="py-4 pb-0 nav flex-lg-column">
                                                @foreach ($main_cats as $main_cat)
                                                    <li class="nav-item">
                                                        <a class="nav-link @if ($loop->first) active @endif"
                                                            aria-current="page" href="#" data-bs-toggle="tab"
                                                            data-bs-target="#mainCat{{ $main_cat->id }}">{{ $main_cat->name }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p>No categories available.</p>
                                        @endif
                                    </div>
                                    <div class="col-lg-9">
                                        <form action="{{ route('hospital.profile.update') }}" method="POST"
                                            enctype="multipart/form-data" id="hospitalProfileForm" novalidate>
                                            @csrf
                                            <div class="tab-content border-0 py-4 pt-4 px-4 ps-2 min-height-500"
                                                id="nav-tabContent">
                                                @if ($main_cats->isNotEmpty())
                                                    @foreach ($main_cats as $main_cat)
                                                        <div class="tab-pane fade @if ($loop->first) show active @endif px-0 border-0 pt-15"
                                                            id="mainCat{{ $main_cat->id }}">
                                                            @if ($main_sub_cats->has($main_cat->id))
                                                                @foreach ($main_sub_cats[$main_cat->id] as $sub_cat)
                                                                    @switch($sub_cat->type)
                                                                        @case(1)
                                                                            {{-- Checkbox --}}
                                                                            @include('frontend.content.hospitals.subcategory-parts.case1')
                                                                        @break

                                                                        @case(2)
                                                                            {{-- Radio Input --}}
                                                                            @include('frontend.content.hospitals.subcategory-parts.case2')
                                                                        @break

                                                                        @case(3)
                                                                            {{-- Multi Select Icons & Text --}}
                                                                            @include('frontend.content.hospitals.subcategory-parts.case3')
                                                                        @break

                                                                        @case(4)
                                                                            {{-- Multi-Image & Text --}}
                                                                            @include('frontend.content.hospitals.subcategory-parts.case4')
                                                                        @break

                                                                        @case(5)
                                                                            {{-- Textbox Input --}}
                                                                            @include('frontend.content.hospitals.subcategory-parts.case5')
                                                                        @break

                                                                        @case(6)
                                                                            {{-- TextArea Input --}}
                                                                            @include('frontend.content.hospitals.subcategory-parts.case6')
                                                                        @break

                                                                        @case(7)
                                                                            {{-- Date Picker --}}
                                                                            @include('frontend.content.hospitals.subcategory-parts.case7')
                                                                        @break

                                                                        @case(8)
                                                                            {{-- Multi-Image & Text Input with Sorting --}}
                                                                            @include('frontend.content.hospitals.subcategory-parts.case8')
                                                                        @break

                                                                        @case(9)
                                                                            {{-- Search Tag Input --}}
                                                                            @include('frontend.content.hospitals.subcategory-parts.case9')
                                                                        @break

                                                                        @case(10)
                                                                            {{-- Google map Input --}}
                                                                            @include('frontend.content.hospitals.subcategory-parts.case10')
                                                                        @break
                                                                    @endswitch
                                                                @endforeach
                                                            @else
                                                                <p>No subcategories available.</p>
                                                            @endif

                                                            <button type="submit" class="btn btn-info">Submit</button>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <p>No categories available.</p>
                                                @endif
                                            </div>
                                        </form>

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


    @include('frontend.includes.hospital-footer')
    <!--Editor CSS-->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.0.2/css/material-design-iconic-font.min.css">

    <link rel="stylesheet" href="{{ asset('assets/frontend/css/froala_editor.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/froala_style.css') }}">

    <!-- Editor JS -->
    <script type="text/javascript" src="{{ asset('assets/frontend/js/froala_editor.min.js') }}"></script>


    <!-- Tag CSS -->
    <link href="{{ asset('assets/frontend/css/jquery.tagsinput.css') }}" rel="stylesheet">
    <!-- Tag JS -->
    <script src="{{ asset('assets/frontend/js/jquery.tagsinput.js') }}"></script>
    <script>
        (function() {
            arrayCase6.forEach((textAreaID, index) => {
                new FroalaEditor("#" + textAreaID, {
                    events: {
                        'contentChanged': function() {
                            document.getElementById(textAreaID + 'Content').value = this.html.get();
                        },
                        'initialized': function() {
                            var oldValue = document.getElementById(textAreaID + 'Content').value;
                            if (oldValue) {
                                this.html.set(oldValue);
                            }
                        }
                    }
                });

            });

            arrayCase7.forEach((datepickerID) => {
                var datepickerElement = document.getElementById(datepickerID);
                var datepickerElementOld = document.getElementById(datepickerID + 'old');
                if (datepickerElement) {
                    var oldDate = datepickerElementOld.value || "";

                    flatpickr(datepickerElement, {
                        dateFormat: "d/m/Y",
                        defaultDate: oldDate || "today",
                    });
                } else {
                    console.log("Element with ID", datepickerID, "not found.");
                }
            });


            arrayCase9.forEach((tagsInputID, index) => {
                $('#' + tagsInputID).tagsInput();
                $("a").click(function(e) {
                    e.preventDefault();
                    var i = $(this).data("value");
                    $('#' + tagsInputID).addTag(i);
                });
            });
        })()
    </script>
    <script>
        document.getElementById('hospitalProfileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let isValid = true;

            let requiredFields = document.querySelectorAll('[required]');
            requiredFields.forEach(function(field) {
                const errorSpan = document.getElementById(field.id + 'Error');

                let isFieldValid = false;

                if (field.type === 'checkbox') {
                    let checkboxes = document.getElementsByName(field.name);
                    isFieldValid = Array.from(checkboxes).some(checkbox => checkbox.checked);
                } else if (field.type === 'radio') {
                    let radioButtons = document.getElementsByName(field.name);
                    isFieldValid = Array.from(radioButtons).some(radio => radio.checked);
                } else {
                    isFieldValid = field.value.trim() !== '';
                }

                if (!isFieldValid) {
                    isValid = false;
                    if (errorSpan) {
                        errorSpan.style.display = 'block';
                    }
                } else {
                    if (errorSpan) {
                        errorSpan.style.display = 'none';
                    }
                }
            });

            if (isValid) {
                this.submit();
            } else {
                return false;
            }
        });
    </script>
@endsection
