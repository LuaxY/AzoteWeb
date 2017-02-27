<div class="form-group ">
    <label class="control-label">{{ $name }}</label>
    <span class="hint">{{ @$field->hint }}</span>
    <input type="file" name="{{ @$field->input }}|{{ $name }}" placeholder="{{ $name }}" accept="{{ @$field->accept }}" {{ @$field->required }}>
</div>
