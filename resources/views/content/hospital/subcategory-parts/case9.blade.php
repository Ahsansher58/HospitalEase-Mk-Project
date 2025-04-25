<!--CITIES COVERED-->
<div class="mb-5">
    <h5 class="mb-3">{{ $sub_cat->sub_category_name }}@if ($sub_cat->sub_category_required == '1')
            <span class="text-danger">*</span>
        @endif
    </h5>
    @php
        $fieldName = 'maincat' . $main_cat->id . '_subcat' . $sub_cat->id . '_case9';
        $hospitalProfileCase9 = !empty($hospitalProfileData[0][$fieldName]) ? $hospitalProfileData[0][$fieldName] : '';
    @endphp

    <!-- Hidden Field for Required Cases -->
    <input type="hidden" name="required_casees[]" value="{{ $sub_cat->sub_category_required == '1' ? $fieldName : '' }}">
    <input type="text" class="form-control form-input-control" name="{{ $fieldName }}" id="{{ $fieldName }}"
        data_role="tagsinput" value="{{ old($fieldName, $hospitalProfileCase9) }}"
        {{ $sub_cat->sub_category_required == '1' ? 'required' : '' }}>

    @if ($sub_cat->sub_category_required == '1')
        <small id="{{ $fieldName }}Error" class="error-message mt-2 text-danger"
            @if (!$errors->has($fieldName)) style="display: none" @endif>
            This is required
        </small>
    @endif
</div>
<script>
    arrayCase9.push('{{ $fieldName }}');
</script>
<!--/CITIES COVERED-->
