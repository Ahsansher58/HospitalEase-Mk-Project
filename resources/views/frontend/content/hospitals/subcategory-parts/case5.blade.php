<div class="row">
    <div class="col-xxl-12 col-xl-7 col-md-6">
        <div class="mb-3">
            <h4 class="mb-3">{{ $sub_cat->sub_category_name }}@if ($sub_cat->sub_category_required == '1')
                    <span class="text-danger">*</span>
                @endif
            </h4>
            @php
                if ($sub_cat->id == '1') {
                    $fieldName = 'mandatory_hospital_name';
                } elseif ($sub_cat->id == '4') {
                    $fieldName = 'mandatory_phone';
                } elseif ($sub_cat->id == '5') {
                    $fieldName = 'mandatory_emergency_contact';
                } elseif ($sub_cat->id == '6') {
                    $fieldName = 'mandatory_email';
                } elseif ($sub_cat->id == '7') {
                    $fieldName = 'mandatory_website';
                } else {
                    $fieldName = 'maincat' . $main_cat->id . '_subcat' . $sub_cat->id . '_case5';
                }
                $field = str_replace('mandatory_', '', $fieldName);
                $hospitalProfileCase5 = !empty($hospitalProfileData[0][$field]) ? $hospitalProfileData[0][$field] : '';

            @endphp
            <!-- Hidden Field for Required Cases -->
            <input type="hidden" name="required_casees[]"
                value="{{ $sub_cat->sub_category_required == '1' ? $fieldName : '' }}">

            <input type="text" class="form-control form-input-control" name="{{ $fieldName }}"
                id="{{ $fieldName }}" placeholder="{{ $sub_cat->sub_category_name }}"
                value="{{ old($fieldName, $hospitalProfileCase5) }}"
                {{ $sub_cat->sub_category_required == '1' ? 'required' : '' }}>
            {{-- <p class="font-size-14 gray-70 text-end mt-2 mb-2">0/50 Characters</p> --}}

            @if ($sub_cat->sub_category_required == '1')
                <small id="{{ $fieldName }}Error" class="error-message text-danger"
                    @if (!$errors->has($fieldName)) style="display: none" @endif>
                    This is required.
                </small>
            @endif
        </div>
    </div>
</div>
