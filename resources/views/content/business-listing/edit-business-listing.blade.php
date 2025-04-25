@extends('layouts/layoutMaster')

@section('title', ' Edit Business Listing')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite(['resources/assets/vendor/libs/flatpickr/flatpickr.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/tagify/tagify.scss', 'resources/assets/vendor/libs/typeahead-js/typeahead.scss', 'resources/assets/vendor/libs/dropzone/dropzone.scss', 'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss'])
    <style>
        .dz-image img {
            width: 180px !important;
            height: 180px !important;
        }

        .dz-details,
        .dz-size,
        .dz-filename {
            padding: 5px !important;
        }

        .dz-remove {
            margin-top: 35px !important;
        }
    </style>
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
    @vite(['resources/assets/vendor/libs/flatpickr/flatpickr.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/tagify/tagify.js', 'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.js', 'resources/assets/vendor/libs/typeahead-js/typeahead.js', 'resources/assets/vendor/libs/bloodhound/bloodhound.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/forms-selects.js', 'resources/assets/js/forms-tagify.js', 'resources/assets/js/forms-typeahead.js', 'resources/assets/js/form-layouts.js', 'resources/assets/js/forms-file-upload.js', 'resources/assets/js/form-validation.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/17.0.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection

@section('content')
    <div class="card mb-4">
        <h5 class="card-header">Edit Business Listing</h5>
        <form class="card-body" id="formValidationExamples2" novalidate action="{{ route('business.update', $listing->id) }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="imageJson" id="imageJson" value="{{ old('imageJson', $listing->photos) }}" />
            <input type="hidden" id="removeImageJson" name="removeImageJson">

            <h6>Business Information</h6>
            <div class="row">
                <!-- Category of Business -->
                <div class="col-md-6 mb-4">
                    <label class="form-label" for="formValidationMainCategory">Category of Business</label>
                    <select class="select2Multiple selectpicker form-select w-100 multiselect-picker" multiple
                        aria-label="Default select example" data-live-search="true" title="Category of Business"
                        id="formValidationMainCategory" name="categories[]" data-allow-clear="true">
                        @foreach ($businessCategories as $businessCategory)
                            <optgroup label="{{ $businessCategory->name }}">
                                <option value="{{ $businessCategory->id }}"
                                    {{ (old('categories') && in_array($businessCategory->id, old('categories'))) || (isset($listing->categories) && in_array($businessCategory->id, $listing->categories)) ? 'selected' : '' }}>
                                    {{ $businessCategory->name }}
                                </option>
                                @foreach ($businessSubCategories->where('main_category_id', $businessCategory->id) as $subcategory)
                                    <option value="{{ $subcategory->id }}"
                                        {{ (old('categories') && in_array($subcategory->id, old('categories'))) || (isset($listing->categories) && in_array($subcategory->id, $listing->categories)) ? 'selected' : '' }}>
                                        {{ $subcategory->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('categories')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Add other fields like business_name, business_address, etc. and pre-fill with $listing data -->
                <div class="col-md-6 mb-4">
                    <label class="form-label" for="formValidationBusinessName">Business Name</label>
                    <input type="text" id="formValidationBusinessName" name="business_name"
                        value="{{ old('business_name', $listing->business_name) }}" class="form-control"
                        placeholder="Business name" />

                    @error('business_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-4">
                    <div class="form-password-toggle">
                        <label class="form-label" for="formValidationBusinessAddress">Business Address</label>
                        <input type="text" id="formValidationBusinessAddress" name="business_address"
                            value="{{ old('business_address', $listing->business_address) }}" class="form-control"
                            placeholder="Business Address" />

                        @error('business_address')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- City -->
                <div class="col-md-6 mb-4">
                    <label class="form-label" for="formValidationCity">City</label>
                    <select id="formValidationCity" name="city" class="select2 form-select" data-allow-clear="true">
                        <option value="">Select City</option>
                        @foreach ($uniqueCities as $city)
                            <option value="{{ $city }}"
                                {{ old('city', $listing->city) == $city ? 'selected' : '' }}>
                                {{ $city }}
                            </option>
                        @endforeach
                    </select>
                    @error('city')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- State -->
                <div class="col-md-6 mb-4">
                    <label class="form-label" for="formValidationState">State</label>
                    <select id="formValidationState" name="state" class="select2 form-select" data-allow-clear="true">
                        @foreach ($uniqueStates as $state)
                            <option value="{{ $state }}"
                                {{ old('state', $listing->state) == $state ? 'selected' : '' }}>
                                {{ $state }}
                            </option>
                        @endforeach
                    </select>
                    @error('state')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Country -->
                <div class="col-md-6 mb-4">
                    <label class="form-label" for="formValidationCountry">Country</label>
                    <select id="formValidationCountry" name="country" class="select2 form-select" data-allow-clear="true">
                        @foreach ($uniqueCountries as $country)
                            <option value="{{ $country }}"
                                {{ old('country', $listing->country) == $country ? 'selected' : '' }}>
                                {{ $country }}
                            </option>
                        @endforeach
                    </select>
                    @error('country')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label" for="banner_image">Banner Image</label>
                        @if ($listing->banner_image)
                            <div>
                                <img src="{{ asset('storage/' . $listing->banner_image) }}" alt="Current Banner Image"
                                    class="img-fluid mb-2" width="200">
                            </div>
                        @endif

                        <input type="file" id="banner_image" name="banner_image" class="form-control"
                            placeholder="Banner Image" />

                        @error('banner_image')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
                <hr class="my-4 mx-n4" />
                <h6>Contact Information</h6>
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label" for="formValidationWhatsapp">WhatsApp Number</label>
                        <input type="number" id="formValidationWhatsapp" name="whatsapp_number"
                            value="{{ old('whatsapp_number', $listing->whatsapp_number) }}" class="form-control"
                            placeholder="WhatsApp Number" />
                        @error('whatsapp_number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label" for="formValidationMobileNumber">Mobile Number</label>
                        <input type="number" id="formValidationMobileNumber" name="mobile_number"
                            value="{{ old('mobile_number', $listing->mobile_number) }}" class="form-control"
                            placeholder="Mobile Number" />
                        @error('mobile_number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label" for="formValidationEmail">Email Address</label>
                        <input type="email" id="formValidationEmail" name="email"
                            value="{{ old('email', $listing->email) }}" class="form-control"
                            placeholder="Email Address" />
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label" for="formValidationWebAddress">Website Address</label>
                        <input type="text" id="formValidationWebAddress" name="website"
                            value="{{ old('website', $listing->website) }}" class="form-control"
                            placeholder="Website Address" />
                        @error('website')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <hr class="my-4 mx-n4" />
                <h6>Other Information</h6>
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <textarea id="editor" name="description" class="form-control" rows="4" placeholder="Description">{{ old('description', $listing->description) }}</textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12 mb-4">
                    <label class="form-label" for="formValidationGmap">Google Map URL</label>
                    <input type="text" id="formValidationGmap" name="google_map_url"
                        value="{{ old('google_map_url', $listing->google_map_url) }}" class="form-control"
                        placeholder="Google Map URL" />
                    @error('google_map_url')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Multi  -->
                <div class="col-12 mb-4">
                    <label class="form-label" for="multicol-last-name">Product/Venue Photos</label>
                    <div action="{{ route('upload.photos') }}" remove-action="{{ route('delete.file') }}"
                        class="dropzone needsclick" id="dropzone-multi" name="photos[]">
                        <div class="dz-message needsclick">
                            Drop files here or click to upload
                            <span class="note needsclick">(This is just a demo dropzone. Selected files are <span
                                    class="fw-medium">not</span> actually uploaded.)</span>
                        </div>
                        <div class="fallback">
                            <input name="photos[]" type="file" multiple />
                        </div>
                    </div>

                    <!-- Display existing images from $listing->photos -->
                    @if ($listing->photos)
                        @foreach (json_decode($listing->photos) as $photo)
                            <div class="dz-preview dz-file-preview">
                                <div class="dz-image">
                                    <img src="{{ asset('storage/' . $photo) }}" alt="Existing Photo" width="180px"
                                        height="180px">
                                </div>
                                <a class="dz-remove" href="javascript:void(0);" data-dz-remove=""
                                    data-photo="{{ $photo }}">Remove file</a>
                            </div>
                        @endforeach
                    @endif

                    @error('photos.*')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <!-- Multi  -->
            </div>
            <hr class="my-4 mx-n4" />
            <h6>Social Media Information</h6>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label" for="formValidationFacebook">Facebook Link</label>
                    <input type="text" id="formValidationFacebook" name="facebook_link"
                        value="{{ old('facebook_link', $listing->facebook_link) }}" class="form-control"
                        placeholder="Facebook Link" />
                    @error('facebook_link')
                        <!-- Remove the '*' here -->
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label" for="formValidationInstagram">Instagram Link</label>
                    <input type="text" id="formValidationInstagram" name="instagram_link"
                        value="{{ old('instagram_link', $listing->instagram_link) }}" class="form-control"
                        placeholder="Instagram Link" />
                    @error('instagram_link')
                        <!-- Add error handling for Instagram -->
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label" for="formValidationYoutube">YouTube Link</label>
                    <input type="text" id="formValidationYoutube" name="youtube_link"
                        value="{{ old('youtube_link', $listing->youtube_link) }}" class="form-control"
                        placeholder="YouTube Link" />
                    @error('youtube_link')
                        <!-- Add error handling for Instagram -->
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label" for="formValidationLinkedIn">LinkedIn Link</label>
                    <input type="text" id="formValidationLinkedIn" name="linkedin_link"
                        value="{{ old('linkedin_link', $listing->youtube_link) }}" class="form-control"
                        placeholder="LinkedIn Link" />
                    @error('linkedin_link')
                        <!-- Add error handling for Instagram -->
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="reset" class="btn btn-label-secondary">Cancel</button>
            </div>
    </div>
    </form>
    </div>
@endsection
