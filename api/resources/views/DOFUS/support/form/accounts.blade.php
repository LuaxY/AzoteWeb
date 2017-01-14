{{ $name }} :<br>

<select name="{{ $name }}">
    <option value="r|null"></option>
    @foreach ($accounts as $account)
        <option value="c|{{ $child }}|{{ $account->Id }}">{{ $account->Nickname }}</option>
    @endforeach
</select>

<br>
