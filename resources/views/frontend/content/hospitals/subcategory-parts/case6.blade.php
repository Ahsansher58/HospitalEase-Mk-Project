<!--HOSPITAL DESCRIPTION-->
<div class="mb-5">
    <h4 class="mb-3">{{ $sub_cat->sub_category_name }}@if ($sub_cat->sub_category_required == '1')
            <span class="text-danger">*</span>
        @endif
    </h4>
    @php
        if ($sub_cat->id == '2') {
            $fieldName = 'mandatory_description';
        } else {
            $fieldName = 'maincat' . $main_cat->id . '_subcat' . $sub_cat->id . '_case6';
        }
        $field = str_replace('mandatory_', '', $fieldName);
        $hospitalProfileCase6 = !empty($hospitalProfileData[0][$field]) ? $hospitalProfileData[0][$field] : '';
    @endphp
    <!-- Hidden Field for Required Cases -->
    <input type="hidden" name="required_casees[]" value="{{ $sub_cat->sub_category_required == '1' ? $fieldName : '' }}">

    <div id='{{ $fieldName }}'></div>
    <textarea id="{{ $fieldName }}Content" name="{{ $fieldName }}"
        {{ $sub_cat->sub_category_required == '1' ? 'required' : '' }} style="display: none;">{{ old($fieldName, $hospitalProfileCase6) }}</textarea>

    @if ($sub_cat->sub_category_required == '1')
        <small id="{{ $fieldName }}ContentError" class="error-message text-danger mt-1"
            @if (!$errors->has($fieldName)) style="display: none" @endif>
            This is required.
        </small>
    @endif
</div>
<!--/HOSPITAL DESCRIPTION-->

<script>
    arrayCase6.push('{{ $fieldName }}');
</script>
