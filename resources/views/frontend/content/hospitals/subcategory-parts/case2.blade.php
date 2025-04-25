<!-- BLOOD BANK AVAILABLE -->
<h4 class="mb-3">
    {{ $sub_cat->sub_category_name }}
    @if ($sub_cat->sub_category_required == '1')
        <span class="text-danger">*</span>
    @endif
</h4>

@php
    $options = explode(',', $sub_cat->option);
    sort($options);
    $sub_category_required = explode(',', $sub_cat->sub_category_required);
    $fieldName = "maincat{$main_cat->id}_subcat{$sub_cat->id}_case2";
    $hospitalProfileCase2 = !empty($hospitalProfileData[0][$fieldName]) ? $hospitalProfileData[0][$fieldName] : '';
@endphp

<!-- Hidden Field for Required Cases -->
<input type="hidden" name="required_casees[]" value="{{ $sub_cat->sub_category_required == '1' ? $fieldName : '' }}">

<div class="mb-5">
    @foreach ($options as $key => $option)
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="{{ $fieldName }}"
                id="{{ $fieldName }}_{{ $key }}" value="{{ trim($option) }}"
                {{ old($fieldName, $hospitalProfileCase2) == trim($option) ? 'checked' : '' }}
                {{ $key == 0 && $sub_cat->sub_category_required == '1' ? 'required' : '' }}>
            <label class="form-check-label font-size-15" for="{{ $fieldName }}_{{ $key }}">
                {{ trim($option) }}
            </label>
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
<!-- /BLOOD BANK AVAILABLE -->
