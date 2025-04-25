<!--LIST OF Names-->
<div class="mb-5 mt-2">
    <h5 class="mb-3">{{ $sub_cat->sub_category_name }}@if ($sub_cat->sub_category_required == '1')
            <span class="text-danger">*</span>
        @endif
    </h5>
    @php
        if ($sub_cat->id == '10') {
            $fieldName = 'mandatory_hospital_images';
            $hospitalProfileCase8 = isset($hospitalProfileData[0]['hospital_images'])
                ? json_decode($hospitalProfileData[0]['hospital_images'], true)
                : [];
        } else {
            $fieldName = 'maincat' . $main_cat->id . '_subcat' . $sub_cat->id . '_case8';
        }

    @endphp
    <input type="hidden" name="required_casees[]"
        value="{{ $sub_cat->sub_category_required == '1' ? $fieldName . 'Req' : '' }}">
    <div class="row gx-2 mb-3">
        <div class="col-xxl-5 col-lg-12 col-md-5 ">
            <div class="mb-4">
                <input class="form-control form-input-control choose-input-control" type="file"
                    id="formFile{{ $sub_cat->id }}">
            </div>
        </div>
        <div class="col-xxl-3 col-lg-5 col-md-4 col-8">
            <div class="mb-3">
                <input class="form-control form-input-control" type="text" placeholder="Enter Name"
                    id="imageName{{ $sub_cat->id }}">
            </div>
        </div>
        <div class="col-xxl-2 col-lg-5 col-md-3 col-4">
            <div class="mb-3">
                <input class="form-control form-input-control" type="number" placeholder="Position"
                    id="imagePosition{{ $sub_cat->id }}">
            </div>
        </div>
        <div class="col-xxl-2 col-lg-2 text-end">
            <button id="addImageBtn{{ $sub_cat->id }}" class="btn btn-info px-3">
                <img src="{{ asset('assets/frontend/images/icons/plus-icon.svg') }}" alt="" width="24" />
            </button>
        </div>
    </div>
    <input type="hidden" id="{{ $fieldName }}Req" value="{{ old($fieldName . 'Req') }}"
        name="{{ $fieldName }}Req" {{ $sub_cat->sub_category_required == '1' ? 'required' : '' }}>
    <div class="row gy-4" id="imageList{{ $sub_cat->id }}"></div>
    @if ($sub_cat->sub_category_required == '1')
        <small id="{{ $fieldName }}ReqError" class="error-message mt-1 text-danger"
            @if (!$errors->has($fieldName . 'Req')) style="display: none" @endif>
            This is required
        </small>
    @endif
</div>
<div id="hiddenFieldsContainer{{ $sub_cat->id }}"></div>
<!--/LIST OF Names-->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const imageList = document.getElementById('imageList{{ $sub_cat->id }}');
        const hiddenFieldsContainer = document.getElementById('hiddenFieldsContainer{{ $sub_cat->id }}');
        const addImageBtn = document.getElementById('addImageBtn{{ $sub_cat->id }}');

        addImageBtn.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default button action

            // Get form inputs
            const fileInput = document.getElementById('formFile{{ $sub_cat->id }}');
            const nameInput = document.getElementById('imageName{{ $sub_cat->id }}');
            const positionInput = document.getElementById('imagePosition{{ $sub_cat->id }}');

            // Clear previous error messages
            clearErrorMessages();

            let isValidInner = true;

            // Validation for file input
            if (!fileInput.files[0]) {
                displayError(fileInput, 'Please select an image file.');
                isValidInner = false;
            }

            // Validation for name input
            if (!nameInput.value.trim()) {
                displayError(nameInput, 'Please enter a name.');
                isValidInner = false;
            }

            // Validation for position input
            if (!positionInput.value.trim()) {
                displayError(positionInput, 'Please enter a position.');
                isValidInner = false;
            }

            if (!isValidInner) return;

            const formData = new FormData();
            formData.append('file', fileInput.files[0]);

            fetch('{{ route('upload_imageCase8') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'),
                    },
                    'Cache-Control': 'no-cache',
                    body: formData,
                })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        const imagePath = data.filePath;
                        const uniqueId = `btn-check-${Date.now()}`;

                        addNewCard(uniqueId, nameInput.value.trim(), positionInput.value.trim(),
                            imagePath);

                        fileInput.value = '';
                        nameInput.value = '';
                        positionInput.value = '';
                    } else {
                        if (data.errors && data.errors.file) {
                            alert(data.errors.file[0]);
                        } else {
                            alert('File upload failed. Please try again.');
                        }
                    }
                })
                .catch((error) => {
                    alert('File Size is more then 5MB');
                    console.error('Error uploading file:', error);
                });
        });

        // Event delegation for delete functionality
        imageList.addEventListener('click', function(event) {
            if (event.target.closest('.delete-btn')) {
                handleDelete(event.target.closest('.delete-btn'));
            }
        });

        // Function to display validation error messages
        function displayError(inputElement, message) {
            const errorMessage = document.createElement('div');
            errorMessage.className = 'error-message-file-upload text-danger mt-1';
            errorMessage.textContent = message;
            inputElement.parentNode.appendChild(errorMessage);
        }

        // Function to clear all error messages
        function clearErrorMessages() {
            document.querySelectorAll('.error-message-file-upload').forEach((el) => el.remove());
        }

        // Function to add a new image card and hidden fields
        @php
            if ($sub_cat->id == '10') {
                $hospitalProfileCase8 = isset($hospitalProfileData[0]['hospital_images']) ? json_decode($hospitalProfileData[0]['hospital_images'], true) : [];
                $oldValues = old($fieldName, $hospitalProfileCase8);
            } else {
                $hospitalProfileCase8 = !empty($hospitalProfileData[0][$fieldName]) ? $hospitalProfileData[0][$fieldName] : [];
                $oldValues = old($fieldName, $hospitalProfileCase8);
            }
            if ($oldValues && is_array($oldValues)) {
                $oldValues = array_map(function ($item) {
                    return is_array($item) ? array_map('stripslashes', $item) : $item;
                }, $oldValues);

                if (isset($oldValues['names']) && isset($oldValues['positions']) && isset($oldValues['images'])) {
                    foreach ($oldValues['names'] as $index => $name) {
                        $position = $oldValues['positions'][$index] ?? '';
                        $imagePath = $oldValues['images'][$index] ?? '';

                        // Output the JavaScript function call with dynamic parameters
                        echo '
                    var uniqueId = `btn-check-' .
                            $index .
                            '`;
                    var name = `' .
                            $name .
                            '`;
                    var position = `' .
                            $position .
                            '`;
                    var imagePath = `' .
                            $imagePath .
                            '`;
                    addNewCard(uniqueId, name, position, imagePath);
                    ';
                    }
                }
            }
        @endphp

        function addNewCard(uniqueId, name, position, imagePath) {
            /*Add hidden field value for validation start*/
            const nameInputField = document.getElementById(
                '{{ $fieldName }}Req');
            let currentValue = parseInt(nameInputField.value, 10) || 0;
            currentValue++;
            nameInputField.value = currentValue;


            /*Add hidden field value for validation end*/
            const newCard = `
            <div class="col-sm-4" data-hidden-id="${uniqueId}">
                <input type="checkbox" class="btn-check" id="${uniqueId}" autocomplete="off">
                <label class="card dr-card rounded-0 p-0 card-btn" for="${uniqueId}">
                    <div class="position-relative">
                        <img src="${imagePath}" class="card-img-top rounded-0" alt="Image">
                    </div>
                    <div class="card-body text-center">
                        <h4 class="card-title mb-3 font-size-15">${name}</h4>
                        <button class="btn btn-danger p-2 delete-btn" type="button">
                            <img src="{{ asset('assets/frontend/images/icons/delete-w.svg') }}" alt="" width="24">
                        </button>
                    </div>
                </label>
            </div>
        `;

            const hiddenFields = `
            <div class="hidden-fields" data-hidden-id="${uniqueId}">
                <input type="hidden" id="name_${uniqueId}" name="{{ $fieldName }}[names][]" value="${name}">
                <input type="hidden" id="position_${uniqueId}" name="{{ $fieldName }}[positions][]" value="${position}">
                <input type="hidden" id="image_${uniqueId}" name="{{ $fieldName }}[images][]" value="${imagePath}">
            </div>
        `;

            imageList.insertAdjacentHTML('beforeend', newCard);
            hiddenFieldsContainer.insertAdjacentHTML('beforeend', hiddenFields);
        }

        // Function to handle delete functionality
        function handleDelete(button) {

            /*Delete hidden field value for validation*/
            const nameInputField = document.getElementById(
                '{{ $fieldName }}Req');
            let currentValue = parseInt(nameInputField.value, 10) || 0;
            if (currentValue > 0) {
                currentValue--;
            }
            nameInputField.value = currentValue === 0 ? '' : currentValue;

            const card = button.closest('.col-sm-4');
            const hiddenId = card.getAttribute('data-hidden-id');
            const imagePathToDelete = card.querySelector('img').src.replace(window.location.origin +
                '/storage/', '');

            // Find or create a hidden input field to store the image path
            let hiddenField = document.querySelector(`.hidden-fields[data-hidden-id="${hiddenId}"]`);
            if (!hiddenField) {
                hiddenField = document.createElement('input');
                hiddenField.type = 'hidden';
                hiddenField.name = 'removed_images[]'; // Array format for submitting multiple values
                hiddenField.classList.add('hidden-fields');
                hiddenField.setAttribute('data-hidden-id', hiddenId);
                document.body.appendChild(hiddenField); // Append to the form or another container
            }

            // Set the value of the hidden input to store the image path
            hiddenField.value = imagePathToDelete;
            card.remove();
            document.querySelector(`.hidden-fields[data-hidden-id="${hiddenId}"]`).remove();
        }

        //handle old values

    });
</script>
