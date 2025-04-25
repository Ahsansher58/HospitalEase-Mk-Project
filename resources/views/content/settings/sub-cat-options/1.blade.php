<div class="col-lg-6 mb-4">
    <h5>Checkbox Input</h5>
    <label class="form-label" for="options1">Checkbox Options</label>
    <input class="form-control typeahead" type="text" placeholder="Enter options" id="options1" name="option1"
        autocomplete="off" value="{{ old('option1', $editMainSubCat['option'] ?? '') }}" />
    <small class="text-muted">Enter multiple options seperated by Comma (,) eg: option1, option2,
        option3...</small>
</div>
