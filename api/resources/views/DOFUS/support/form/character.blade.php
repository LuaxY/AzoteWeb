{{ $name }}:<br>

@foreach ($characters as $character)
<label for="{{ $character->Id }}">
    <div class="character">
        <input type="radio" name="{{ $name }}" id="{{ $character->Id }}" value="{{ $character->Id }}">
        <img src="{{ DofusForge::player($character->Id, 'sigma', 'face', 2, 50, 50) }}">
        {{ $character->Name }} - {{ $character->classe() }} niveau {{ $character->level() }}
    </div>
</label>
@endforeach

<br>
