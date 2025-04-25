<h4 class="mb-3">
    {{ $sub_cat->sub_category_name }}
    @if ($sub_cat->sub_category_required == '1')
        <span class="text-danger">*</span>
    @endif
</h4>

<div class="mb-5">
    <div class="row">
        @php
            if ($sub_cat->id == '3') {
                $fieldName = 'mandatory_departments';
                $hospitalProfileCase1 = isset($hospitalProfileData[0]['departments'])
                    ? json_decode($hospitalProfileData[0]['departments'], true)
                    : [];
            } elseif ($sub_cat->id == '11') {
                $fieldName = 'mandatory_medical_system';
                $hospitalProfileCase1 = isset($hospitalProfileData[0]['medical_system'])
                    ? json_decode($hospitalProfileData[0]['medical_system'], true)
                    : [];
            } else {
                $fieldName = "maincat{$main_cat->id}_subcat{$sub_cat->id}_case1";
                $hospitalProfileCase1 = !empty($hospitalProfileData[0][$fieldName])
                    ? $hospitalProfileData[0][$fieldName]
                    : [];
            }
            $options = explode(',', $sub_cat->option);
            sort($options);
            $oldValuesCase1 = old($fieldName, $hospitalProfileCase1);
        @endphp

        <!-- Hidden Field for Required Cases -->
        <input type="hidden" name="required_casees[]"
            value="{{ $sub_cat->sub_category_required == '1' ? $fieldName : '' }}">

        @foreach ($options as $key => $option)
            <div class="col-lg-4">
                <div class="form-check form-check-inline mb-3">
                    <input class="form-check-input" type="checkbox" name="{{ $fieldName }}[]"
                        id="{{ $fieldName }}_{{ $key }}" value="{{ trim($option) }}"
                        {{ in_array(trim($option), $oldValuesCase1) ? 'checked' : '' }}
                        {{ $key == 0 && $sub_cat->sub_category_required == '1' ? 'required' : '' }}>

                    <label class="form-check-label font-size-15" for="{{ $fieldName }}_{{ $key }}">
                        {{ trim($option) }}
                    </label>
                </div>
            </div>
        @endforeach
        <div class="mt-2">
            @if ($sub_cat->sub_category_required == '1')
                <small id="{{ $fieldName }}_0Error" class="error-message mt-1 text-danger"
                    @if (!$errors->has($fieldName)) style="display: none" @endif>This is required, please select
                    at least one</small>
            @endif
        </div>
    </div>
</div>
