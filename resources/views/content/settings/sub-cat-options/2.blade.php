<div class="col-lg-6 mb-4">
    <h5>Radio Input</h5>
    <label class="form-label" for="options2">Radio Button Options</label>
    <input class="form-control typeahead" type="text" placeholder="Enter options" id="options2" name="option2"
        autocomplete="off" value="{{ old('option2', $editMainSubCat['option'] ?? '') }}" />
    <small class="text-muted">Enter multiple options seperated by Comma (,) eg: option1, option2,
        option3...</small>
</div>
