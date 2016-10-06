<div class="ak-rewards">
    <div class="ak-progression">
        <div class="ak-title-progression">Palier : {{ $palierId }}</div>
        <div class="progress">
            <div class="progress-bar progress-bar-info" data="{{ $progress }}"></div>
        </div>
        <div class="ak-time-line">
            @foreach ($steps as $i => $step)
            @if ($step != null)
            <div class="ak-ajaxloader ak-block-step ak-block-step{{ $i }}" item="{{ $step->itemId }}" step="{{ $i }}" votes="{{ $step->votes }}">
                <span class="arrow"></span>
                <a class="@if ($current == $i) ak-selected @endif"><span class="ak-text"><span>{{ $step->votes }}</span> votes</span> <span class="ak-gift"></span></a>
            </div>
            @endif
            @endforeach
        </div>
        <div class="ak-select-time-line ak-ajaxloader">
            <select onchange="$('.ak-block-step'+this.value+' a').trigger('click');">
                @foreach ($steps as $i => $step)
                @if ($step != null)
                <option @if ($current == $i) selected="" @endif value="{{ $i }}">{{ $step->votes }} votes</option>
                @endif
                @endforeach
            </select>
        </div>
        <div class="ak-block-gift">
            <div class="ak-title-gift"> <span class="ak-nb-step"><span></span> votes</span>
                <div class="ak-text"> <span class="ak-nb-day">Cadeau à obtenir :</span> <span class="ak-name-gift"></span> </div>
            </div>
            <div class="ak-container ak-panel">
                <div class="ak-panel-content">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="ak-encyclo-detail-illu"><img src="" class="img-maxresponsive" data-max="200"></div>
                        </div>
                        <div class="col-sm-9">
                            <div class="ak-encyclo-detail-right ak-nocontentpadding">
                                <div class="ak-container ak-panel">
                                    <div class="ak-panel-title"><span class="ak-panel-title-icon"></span>Description</div>
                                    <div class="ak-panel-content"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ak-container">
            <div class="ak-social"></div>
        </div>
        @if ($steps[$current] != null)
        <div id="load-item" item="{{ $steps[$current]->itemId }}" step="{{ $current }}" votes="{{ $steps[$current]->votes }}"></div>
        @endif
    </div>
</div>
