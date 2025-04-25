<h5>Multi-Image & Text Input with Sorting</h5>
<input type="hidden" id="option8" name="option8" />
<small class="text-muted mb-3">Allowed file type: JPG, PNG and SVG</small>
<div class="form-repeater">
    @php
        $values = old('group-a') ?? json_decode($editMainSubCat['value'] ?? '[]', true);
    @endphp

    <div data-repeater-list="group-a">
        @if (count($values) > 0)
            @foreach ($values as $index => $item)
                <div data-repeater-item>
                    <div class="row">
                        <div class="mb-3 col-lg-6 col-xl-4 col-12 mb-0">
                            <label class="form-label" for="form-repeater-{{ $index }}-file">Image</label>
                            <input class="form-control" type="file" name="group-a[{{ $index }}][file8]"
                                id="form-repeater-{{ $index }}-file">
                            @if (isset($item['file']))
                                <div>
                                    <img src="{{ asset($item['file']) }}" alt="Image" width="100">
                                </div>
                            @endif
                        </div>
                        <div class="mb-3 col-lg-6 col-xl-4 col-12 mb-0">
                            <label class="form-label" for="form-repeater-{{ $index }}-name">Name</label>
                            <input type="text" id="form-repeater-{{ $index }}-name"
                                name="group-a[{{ $index }}][name8]" class="form-control" placeholder="Enter Name"
                                value="{{ old('group-a.' . $index . '.name', $item['name'] ?? '') }}" />
                        </div>
                        <div class="mb-3 col-lg-6 col-xl-2 col-12 mb-0">
                            <label class="form-label" for="form-repeater-{{ $index }}-position">Position</label>
                            <input type="number" id="form-repeater-{{ $index }}-position"
                                name="group-a[{{ $index }}][position8]" class="form-control"
                                placeholder="Enter Position"
                                value="{{ old('group-a.' . $index . '.position', $item['position'] ?? '') }}" />
                        </div>
                        <div class="mb-3 col-lg-12 col-xl-2 col-12 d-flex align-items-center mb-0 mt-3">
                            <button type="button" class="btn btn-label-danger btn-remove me-3" data-repeater-delete>
                                <i class="ti ti-trash"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-add" data-repeater-create>
                                <i class="ti ti-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <!-- Default empty item -->
            <div data-repeater-item>
                <div class="row">
                    <div class="mb-3 col-lg-6 col-xl-4 col-12 mb-0">
                        <label class="form-label" for="form-repeater-0-file">Image</label>
                        <input class="form-control" type="file" name="group-a[0][file8]" id="form-repeater-0-file">
                    </div>
                    <div class="mb-3 col-lg-6 col-xl-4 col-12 mb-0">
                        <label class="form-label" for="form-repeater-0-name">Name</label>
                        <input type="text" id="form-repeater-0-name" name="group-a[0][name8]" class="form-control"
                            placeholder="Enter Name" value="{{ old('group-a.0.name') }}" />
                    </div>
                    <div class="mb-3 col-lg-6 col-xl-2 col-12 mb-0">
                        <label class="form-label" for="form-repeater-0-position">Position</label>
                        <input type="number" id="form-repeater-0-position" name="group-a[0][position8]"
                            class="form-control" placeholder="Enter Position"
                            value="{{ old('group-a.0.position') }}" />
                    </div>
                    <div class="mb-3 col-lg-12 col-xl-2 col-12 d-flex align-items-center mb-0 mt-3">
                        <button type="button" class="btn btn-label-danger btn-remove me-3" data-repeater-delete>
                            <i class="ti ti-trash"></i>
                        </button>
                        <button type="button" class="btn btn-primary btn-add" data-repeater-create>
                            <i class="ti ti-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

</div>
