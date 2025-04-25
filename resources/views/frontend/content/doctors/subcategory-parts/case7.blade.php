<div class="row">
    <div class="col-xxl-12 col-xl-5 col-md-6">
        <div class="mb-5">
            <h4 class="mb-3"> {{ $sub_cat->sub_category_name }} @if ($sub_cat->sub_category_required == '1')
                    <span class="text-danger">*</span>
                @endif
            </h4>

            @php
                $fieldName = 'maincat' . $main_cat->id . '_subcat' . $sub_cat->id . '_case7';
                $hospitalProfileCase7 = !empty($hospitalProfileData[0][$fieldName])
                    ? $hospitalProfileData[0][$fieldName]
                    : '';
            @endphp

            <!-- Hidden Field for Required Cases -->
            <input type="hidden" name="required_casees[]"
                value="{{ $sub_cat->sub_category_required == '1' ? $fieldName : '' }}">

            <input type="hidden" id="{{ $fieldName }}old" value="{{ old($fieldName, $hospitalProfileCase7) }}">
            <input type="date" name="{{ $fieldName }}" class="form-control form-input-control"
                placeholder="14 / 10 / 1994" id="{{ $fieldName }}"
                {{ $sub_cat->sub_category_required == '1' ? 'required' : '' }}>

            @if ($sub_cat->sub_category_required == '1')
                <small id="{{ $fieldName }}Error" class="error-message mt-1 text-danger"
                    @if (!$errors->has($fieldName)) style="display: none" @endif>
                    This is required
                </small>
            @endif
        </div>
    </div>
</div>
<script>
    arrayCase7.push('{{ $fieldName }}');
</script>
