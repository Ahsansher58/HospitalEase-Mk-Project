<!-- LIST OF INSURANCE -->
<h5 class="mb-3">{{ $sub_cat->sub_category_name }}
    @if ($sub_cat->sub_category_required == '1')
        <span class="text-danger">*</span>
    @endif
</h5>

<div class="specialization-section hospital-icon-block pt-0 pb-5">
    <div class="container container-secondary">
        <!-- BLOCK -->
        <div class="row justify-content-center">
            @php
                $json_data = $sub_cat->value;
                $data = json_decode($json_data, true);
                $fieldName = 'maincat' . $main_cat->id . '_subcat' . $sub_cat->id . '_case4';
                $hospitalProfileCase4 = !empty($hospitalProfileData[0][$fieldName])
                    ? $hospitalProfileData[0][$fieldName]
                    : [];
            @endphp

            <!-- Hidden Field for Required Cases -->
            <input type="hidden" name="required_casees[]"
                value="{{ $sub_cat->sub_category_required == '1' ? $fieldName : '' }}">

            @foreach ($data as $item)
                <div class="col-md-4 col-6 text-center">
                    <div class="specialization-block mt-0 mb-5">
                        <div class="form-check ps-0">
                            <label class="form-check-label float-none" for="{{ $fieldName }}_{{ $loop->index }}">
                                <div class="mb-2 insurance-logo">
                                    <img src="{{ asset($item['file']) }}" class="img-fluid" alt="" />
                                </div>
                                {{ $item['name'] }}
                            </label>
                            <div>
                                <input class="form-check-input float-none ms-0 mt-3" type="checkbox"
                                    value="{{ str_replace(' ', '_', trim($item['name'])) }}"
                                    id="{{ $fieldName }}_{{ $loop->index }}" name="{{ $fieldName }}[]"
                                    {{ in_array(str_replace(' ', '_', trim($item['name'])), old($fieldName, $hospitalProfileCase4)) ? 'checked' : '' }}
                                    {{ $loop->index == 0 && $sub_cat->sub_category_required == '1' ? 'required' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            @if ($sub_cat->sub_category_required == '1')
                <small id="{{ $fieldName }}_0Error" class="error-message mt-1 text-danger"
                    @if (!$errors->has($fieldName)) style="display: none" @endif>
                    This is required, please select at least one
                </small>
            @endif
        </div>
        <!-- /BLOCK -->
    </div>
</div>
<!-- /LIST OF INSURANCE -->
