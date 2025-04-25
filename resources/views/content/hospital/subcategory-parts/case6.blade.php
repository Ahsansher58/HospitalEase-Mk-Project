<!--HOSPITAL DESCRIPTION-->
<div class="mb-5">
    <h5 class="mb-3">{{ $sub_cat->sub_category_name }}@if ($sub_cat->sub_category_required == '1')
            <span class="text-danger">*</span>
        @endif
    </h5>
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

    <textarea id="{{ $fieldName }}" name="{{ $fieldName }}" class="form-control" rows="5"
        {{ $sub_cat->sub_category_required == '1' ? 'required' : '' }}>{{ old($fieldName, $hospitalProfileCase6) }}</textarea>
    @if ($sub_cat->sub_category_required == '1')
        <small id="{{ $fieldName }}Error" class="error-message text-danger mt-1"
            @if (!$errors->has($fieldName)) style="display: none" @endif>
            This is required.
        </small>
    @endif
</div>
<!--/HOSPITAL DESCRIPTION-->
<script>
    arrayCase6.push('{{ $fieldName }}');
</script>
