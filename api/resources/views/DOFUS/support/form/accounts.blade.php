{{ $name }} :<br>

<select name="special|account">
    <option value="reset|null"></option>
    @foreach ($accounts as $account)
        @if ($child)
            <option value="child|{{ $account->Id }}|{{ $child }}">{{ $account->Nickname }}</option>
        @else
            <option value="final|{{ $account->Id }}">{{ $account->Nickname }}</option>
        @endif
    @endforeach
</select>

<br>
