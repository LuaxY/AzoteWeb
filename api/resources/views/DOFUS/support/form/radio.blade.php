{{ $name }} :<br>

@foreach ($data as $choice)
    @if (isset($choice->child))
        <input type="radio" name="text|{{ $name }}" value="child|{{ $choice->value }}|{{ $choice->child }}"> {{ $choice->value }}<br>
    @else
        <input type="radio" name="text|{{ $name }}" value="final|{{ $choice->value }}"> {{ $choice->value }}<br>
    @endif
@endforeach
