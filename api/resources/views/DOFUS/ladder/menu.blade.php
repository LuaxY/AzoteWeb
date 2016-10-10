<div class="ak-page-menu">
    <nav class="ak-nav-expand ">
        <div class="ak-nav-expand-container" style="height: 42px;">
            <ul class="ak-nav-links ak-nav-expand-links">
                <li @if ($page_name == 'Général') class="on" @endif>
                    <a href="{{ URL::route('ladder.general') }}">Ladder Général</a>
                </li>
                <li @if ($page_name == 'PvP') class="on" @endif>
                    <a href="{{ URL::route('ladder.pvp') }}">Ladder PvP</a>
                </li>
                <li @if ($page_name == 'Guilde') class="on" @endif>
                    <a href="{{ URL::route('ladder.guild') }}">Ladder Guilde</a>
                </li>
            </ul>
        </div>
    </nav>
</div>
