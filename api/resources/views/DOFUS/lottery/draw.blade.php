@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('header')
    {!! Html::style('css/codes.css') !!}
    {!! Html::style('css/set.css') !!}
@stop

@section('breadcrumbs')
{? $page_name = 'Loterie' ?}
{!! Breadcrumbs::render('page', $page_name) !!}
@stop

@section('content')
<div class="ak-page-header"></div>
<div class="ak-container ak-main-center">
    <div class="ak-title-container">
        <h1 class="ak-return-link">
            <span class="ak-icon-big ak-codes"></span> <span id="title">Utilise le ticket !</span>
        </h1>
    </div>
    <div class="ak-code-banner ak-banner-dofus ak-gift"></div>
    <div class="ak-container ak-panel-stack ak-glue">
        <div class="ak-container ak-panel ak-consume-card ak-nocontentpadding">
            <div class="ak-panel-title">
                <span class="ak-panel-title-icon"></span> Vous avez débloqué :
            </div>
            <div class="ak-panel-content">
                <div class="ak-container ak-panel">
                    <div class="ak-panel-content">
                        <div class="ak-container ak-panel ak-gift-price ak-nocontentpadding ak-code-price">
                            <div class="ak-panel-content">
                                <div class="ak-price-content">
                                    @if ($ticket->used)
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="ak-image">
                                                <img id="gift-image" src="{{ URL::asset($ticket->item()->image()) }}" class="img-maxresponsive">
                                            </div>
                                        </div>
                                        <div class="col-sm-10">
                                            <div class="ak-name" id="gift-name">{{ $ticket->item()->name() }}</div>
                                            <div class="ak-description" id="gift-description">{{ $ticket->item()->description() }}</div>
                                            <br>
                                        </div>
                                    </div>
                                    @else
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="ak-image">
                                                <img id="gift-image" src="{{ URL::asset($ticket->lottery()->image_path) }}" class="img-maxresponsive">
                                            </div>
                                        </div>
                                        <div class="col-sm-10">
                                            <div class="ak-name" id="gift-name"></div>
                                            <div class="ak-description" id="gift-description">
                                                <button id="draw" class="btn btn-primary btn-lg">Lancer le tirage</button>
                                            </div>
                                            <br>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="loadmask"></div>
                                    <div class="ak-loading" style="top:50px;">
                                        <div class="spinner">
                                            <div class="mask">
                                                <div class="circle"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ak-container ak-panel-stack ak-glue">
            <div class="ak-container ak-panel">
                <div class="ak-panel-title">
                    <span class="ak-panel-title-icon"></span> Liste des items possible
                </div>
                <div class="ak-responsivetable-wrapper" style="overflow: hidden;">
                    <table border="1" class="ak-container ak-table ak-responsivetable ak-set-composition" style="white-space: nowrap; visibility: visible;">

                        @foreach ($ticket->objects() as $object)
                        <tr>
                            <td class="ak-set-composition-illu img-first-column">
                                <img src="{{ $object->item()->image() }}" alt="{{ $object->item()->name() }}" width="70">
                            </td>
                            <td class="ak-set-composition-name">
                                {{ $object->item()->name() }}
                            </td>
                            <td class="ak-set-composition-level">Niv {{ $object->item()->Level }}</td>
                        </tr>
                        @endforeach

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var $ = require('jquery');

    loader('ak-price-content', false);

    $("#draw").on("click", function() {
        var self = $(this);

        $("#gift-description").html('');

        loader('ak-price-content', true);

        $.ajax({
            type: "GET",
            url: "{{ URL::route('lottery.process', [$ticket->id]) }}",
        })
        .done(function(data) {
            var json = $.parseJSON(data);
            $("#gift-image").attr("src", json.image);
            $("#gift-name").html(json.name);
            $("#gift-description").html(json.description);
            $("#title").html("Félicitations !");

            loader('ak-price-content', false);
        })
        .error(function(data) {
            $("#gift-description").html(data);
        });
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
</script>
@stop
