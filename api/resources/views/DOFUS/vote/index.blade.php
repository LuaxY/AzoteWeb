@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('header')
    {!! Html::style('css/vote.css') !!}
    {!! Html::style('css/encyclopedia.css') !!}
@stop

@section('breadcrumbs')
{? $page_name = 'Voter' ?}
{!! Breadcrumbs::render('page', $page_name) !!}
@stop

@section('content')
<div class="ak-container ak-main-center vote-rewards">
    <div class="ak-title-container">
        <h1 class="ak-return-link">
            <span class="ak-icon-big ak-character"></span></a> Vote pour le serveur
        </h1>
    </div>
    <div class="ak-infos-general">
        <div class="row">
            <div class="col-sm-6">
                <div class="ak-infos">
                    <div class="ak-cumul">Vous avez déjà voté <span class="nb-steps">{{ $votesCount }}</span> fois !</div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="ak-block-gift-win">
                        <div class="ak-gift"><img src="{{ URL::asset('imgs/assets/gift.jpg') }}" alt="gift"></div>
                        <div class="ak-gift-win"><span>Cadeaux gagnés</span> {{ $giftsCount }}</div>
                    </div>
                    <div class="ak-block-next-gift">
                        <div class="ak-gift"><img src="{{ URL::asset('imgs/assets/gift.jpg') }}" alt="gift"></div>
                        <div class="ak-next-gift"><span>Prochain cadeau</span> dans {{ $nextGifts }} votes</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ak-container ak-panel panel-vote-link">
        <div class="ak-panel-content">
            <div class="row">
                <div class="col-sm-6">
                    @if ($delay->canVote)
                    <a id="vote-link" href="{{ URL::to('http://www.rpg-paradize.com/?page=vote&vote=' . config("dofus.rpg-paradize.id")) }}" target="_blank" class="btn btn-blok btn-lg btn-info">Voter</a>
                    @else
                    <p><b>Vous devez attendre {{ $delay->hours }}h {{ $delay->minutes }}m {{ $delay->seconds }}s avant de pouvoir re-voter.</b></p>
                    @endif
                </div>
                <div class="col-sm-6">
                    <p>Chaque vote permet d'obtenir {{ config('dofus.vote') }} ogrines.<br>Tous les 10 votes vous gagnez un nouveau cadeau.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="ak-panel-stack">
        <div class="ak-block-rewards">
            <div class="ak-menu-left ak-ajaxloader">
                <a data="1">1 <sup>èr</sup> Palier</a>
                <!--<a data="2">2 <sup>ème</sup> Palier</a>
                <a data="3">3 <sup>ème</sup> Palier</a>
                <a data="4">4 <sup>ème</sup> Palier</a>
                <a data="5">5 <sup>ème</sup> Palier</a>-->
            </div>
            <div class="ak-select-menu-left ak-ajaxloader">
                <select onchange="$('.ak-block-rewards .ak-menu-left a[data='+this.value+']').trigger('click');">
                    <option value="1">Palier 1</option>
                    <!--<option value="2">Palier 2</option>
                    <option value="3">Palier 3</option>
                    <option value="4">Palier 4</option>
                    <option value="5">Palier 5</option>-->
                </select>
                <a class="ak-select-link hide"></a>
            </div>
            <div id="step-view">
                @include('vote.paliers')
            </div>
            <div class="loadmask"></div>
            <div class="ak-loading">
                <div class="spinner">
                    <div class="mask">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var $ = require('jquery');

    $(".ak-block-rewards .ak-menu-left a[data={{ $palierId }}]").addClass("ak-selected");
    $(".ak-block-rewards .ak-select-menu-left option[value={{ $palierId }}]").attr('selected','selected');

    progress();

    @if ($steps[$current] != null)
    showItem({{ $steps[$current]->itemId }}, {{ $current }}, {{ $steps[$current]->votes }});
    @else
    //showItem(15752, 1, 1);
    loader('ak-block-rewards', false);
    @endif

    $(".ak-block-rewards .ak-menu-left a").on("click", function() {
        var self = $(this);
        var palierId = self.attr("data");

        loader('ak-block-rewards', true);

        $(".ak-block-rewards .ak-menu-left a.ak-selected").removeClass("ak-selected");

        $.ajax({
            type: "GET",
            url: "{{ URL::route('vote.palier') }}/" + palierId,
        })
        .done(function(res) {
            $("#step-view").html(res);

            $(".ak-block-rewards .ak-menu-left a[data="+palierId+"]").addClass("ak-selected");
            $(".ak-block-rewards .ak-select-menu-left option[value="+palierId+"]").attr('selected','selected');

            loader('ak-block-rewards', false);

            var item = $("#load-item");

            progress();
            showItem(item.attr("item"), item.attr("step"), item.attr("votes"));
        });
    });

    $("#step-view").on("click", ".ak-block-step a", function() {
        $(".ak-block-step a.ak-selected").removeClass("ak-selected");

        var parent = $(this).parent(".ak-block-step");
        var item = parent.attr("item");
        var step = parent.attr("step");
        var votes = parent.attr("votes");

        $(this).addClass("ak-selected");
        $(".ak-select-time-line option[value="+step+"]").attr('selected','selected');

        showItem(item, step, votes);
    });

    $("#vote-link").on("click", function() {
        setTimeout(function() {
            window.location.href = "{{ URL::route('vote.confirm') }}";
        }, 1000);
    });

    function loader(seletor, state) {
        if (state) {
            $("."+seletor).addClass("mask-relative masked");
            $("."+seletor+" .loadmask").show();
            $("."+seletor+" .ak-loading").show();
        } else {
            $("."+seletor).removeClass("mask-relative masked");
            $("."+seletor+" .loadmask").hide();
            $("."+seletor+" .ak-loading").hide();
        }
    }

    function progress() {
        var percent = $(".progress-bar").attr("data");
        $(".progress-bar").animate({width: percent +'%'}, 0, "linear");
    }

    function showItem(item, step, votes) {
        // Show loader
        loader('ak-block-rewards', true);

        // Clear previous data
        $(".ak-name-gift").html("");
        $(".ak-encyclo-detail-right .ak-panel-content").html("");
        $(".ak-encyclo-detail-illu img").attr("src", "");
        $(".ak-step").removeClass("ak-step1 ak-step2 ak-step3 ak-step4 ak-step5");

        // Display new data
        $(".ak-nb-step span").html(votes);
        $(".ak-nb-step").addClass("ak-step" + step);

        // Get reward info
        $.ajax({
            type: "GET",
            url: "{{ URL::route('vote.object') }}/" + item,
            dataType: "json",
        })
        .done(function(res) {
            // Dislay new reward
            $(".ak-name-gift").html(res.name);
            $(".ak-encyclo-detail-right .ak-panel-content").html(res.description);
            $(".ak-encyclo-detail-illu img").attr("src", res.image);

            // Hide loader
            loader('ak-block-rewards', false);
        });
    }
</script>
@stop
