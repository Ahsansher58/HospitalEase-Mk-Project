<div class="row">
    <div class="col-xxl-12 col-xl-7 col-md-6">
        <div class="mb-3">
            <h5 class="mb-3">{{ $sub_cat->sub_category_name }}@if ($sub_cat->sub_category_required == '1')
                    <span class="text-danger">*</span>
                @endif
            </h5>
            @php
                if ($sub_cat->id == '8') {
                    $fieldName = 'mandatory_location';
                } else {
                    $fieldName = 'maincat' . $main_cat->id . '_subcat' . $sub_cat->id . '_case10';
                }
                $field = str_replace('mandatory_', '', $fieldName);
                $hospitalProfileCase10 = !empty($hospitalProfileData[0][$field]) ? $hospitalProfileData[0][$field] : '';
            @endphp
            <!-- Hidden Field for Required Cases -->
            <input type="hidden" name="required_casees[]"
                value="{{ $sub_cat->sub_category_required == '1' ? $fieldName : '' }}">

            <input type="text" class="form-control form-input-control" name="{{ $fieldName }}"
                id="{{ $fieldName }}" placeholder="{{ $sub_cat->sub_category_name }}"
                value="{{ old($fieldName, $hospitalProfileCase10) }}"
                {{ $sub_cat->sub_category_required == '1' ? 'required' : '' }}>
            <p class="font-size-14 gray-70 text-end mt-2 mb-2">0/100 Characters</p>

            @if ($sub_cat->sub_category_required == '1')
                <small id="{{ $fieldName }}Error" class="error-message text-danger"
                    @if (!$errors->has($fieldName)) style="display: none" @endif>
                    This is required.
                </small>
            @endif
        </div>
    </div>
</div>
