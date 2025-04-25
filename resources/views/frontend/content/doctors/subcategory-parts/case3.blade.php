<!-- SPACIALIZATION -->
<h4 class="mb-3">{{ $sub_cat->sub_category_name }}
    @if ($sub_cat->sub_category_required == '1')
        <span class="text-danger">*</span>
    @endif
</h4>

<div class="specialization-section hospital-icon-block pt-0 pb-5">
    <div class="container container-secondary">
        <!-- BLOCK -->
        <div class="row justify-content-center">
            @php
                $json_data = $sub_cat->value;
                $data = json_decode($json_data, true);
                if ($sub_cat->id == '9') {
                    $fieldName = 'mandatory_facilities';
                    $hospitalProfileCase3 = isset($hospitalProfileData[0]['facilities'])
                        ? json_decode($hospitalProfileData[0]['facilities'], true)
                        : [];
                } elseif ($sub_cat->id == '3') {
                    $fieldName = 'mandatory_specialization';
                    $hospitalProfileCase3 = isset($hospitalProfileData[0]['specialization'])
                        ? json_decode($hospitalProfileData[0]['specialization'], true)
                        : [];
                } else {
                    $fieldName = "maincat{$main_cat->id}_subcat{$sub_cat->id}_case3";
                    $hospitalProfileCase3 = !empty($hospitalProfileData[0][$fieldName])
                        ? $hospitalProfileData[0][$fieldName]
                        : [];
                }

            @endphp

            <!-- Hidden Field for Required Cases -->
            <input type="hidden" name="required_casees[]"
                value="{{ $sub_cat->sub_category_required == '1' ? $fieldName : '' }}">
            @php
                usort($data, function ($a, $b) {
                    return strcmp($a['name'], $b['name']);
                });
            @endphp
            @foreach ($data as $key => $item)
                <div class="col-md-3 col-sm-4 col-6 text-center">
                    <div class="specialization-block mt-0 mb-5">
                        <div class="form-check ps-0">
                            <label class="form-check-label float-none" for="{{ $fieldName }}_{{ $key }}">
                                <div class="icon-widget mx-auto mb-3 p-4">
                                    <img src="{{ asset($item['file']) }}" class="img-fluid" alt="{{ $item['name'] }}" />
                                </div>
                                {{ $item['name'] }}
                            </label>
                            <div>
                                <input class="form-check-input float-none ms-0 mt-3" type="checkbox"
                                    name="{{ $fieldName }}[]" id="{{ $fieldName }}_{{ $key }}"
                                    value="{{ trim($item['name']) }}"
                                    {{ in_array(trim($item['name']), old($fieldName, $hospitalProfileCase3)) ? 'checked' : '' }}
                                    {{ $key == 0 && $sub_cat->sub_category_required == '1' ? 'required' : '' }}>
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
<!-- /SPACIALIZATION -->
