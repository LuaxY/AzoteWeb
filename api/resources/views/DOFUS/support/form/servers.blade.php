{{ $name }} :<br>

<select id="server" class="special" name="special|server">
    <option value="reset|null"></option>
    @foreach ($servers as $server)
        @if (isset($child))
            <option value="child|{{ $server->name }}|{{ $child }}">{{ ucfirst($server->name) }}</option>
        @else
            <option value="final|{{ $server->name }}">{{ ucfirst($server->name) }}</option>
        @endif
    @endforeach
</select>

<br>
