{{ $name }} :<br>

<select name="{{ $name }}">
    <option value="r|null"></option>
    @foreach ($data as $choice)
        @if (isset($choice->child))
            <option value="c|{{ $choice->child }}">{{ $choice->value }}</option>
        @else
            <option value="f|{{ $choice->value }}">{{ $choice->value }}</option>
        @endif
    @endforeach
</select>

<br>
