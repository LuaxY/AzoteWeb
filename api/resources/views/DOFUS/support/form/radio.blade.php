{{ $name }} :<br>

@foreach ($data as $choice)
    @if (isset($choice->child))
        <input type="radio" name="{{ $name }}" value="c|{{ $choice->child }}"> {{ $choice->value }}<br>
    @else
        <input type="radio" name="{{ $name }}" value="f|{{ $choice->value }}"> {{ $choice->value }}<br>
    @endif
@endforeach
