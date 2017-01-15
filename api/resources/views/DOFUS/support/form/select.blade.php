{{ $name }} :<br>

<select name="text|{{ $name }}">
    <option value="reset|null"></option>
    @foreach ($data as $choice)
        @if (isset($choice->child))
            <option value="child|{{ $choice->value }}|{{ $choice->child }}">{{ $choice->value }}</option>
        @else
            <option value="final|{{ $choice->value }}">{{ $choice->value }}</option>
        @endif
    @endforeach
</select>

<br>
