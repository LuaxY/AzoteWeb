<div class="form-group ">
    <label class="control-label">{{ $name }}</label>
    <span class="hint">{{ @$field->hint }}</span>
    <textarea name="message|{{ $name }}" class="form-control" rows="10" {{ @$field->required }}></textarea>
</div>
