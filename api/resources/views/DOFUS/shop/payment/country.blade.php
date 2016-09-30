@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('header')
    {!! Html::style('css/flags.css') !!}
@stop

@section('breadcrumbs')
{!! Breadcrumbs::render('shop.page', 'Choix du pays') !!}
@stop

@section('content')
<div class="ak-title-container">
    <h1><span class="ak-icon-big ak-shop"></span> Achat d'ogrines</h1>
</div>

<div class="ak-container ak-panel-stack ak-payments-process-choice">
    <div class="ak-container ak-panel">
        <div class="ak-intro-connection">
            <div class="ak-panel-content ak-text-intro">
                <h3>Astuce:</h3>
                <p style="text-align: center;">
                    Les paiements hors SMS et Appels comme <strong>paysafecard, neosurf et carte bancaire</strong> se trouvent dans le <strong>drapeau européen {{Html::image('imgs/shop/europe.png', 'europe')}}</strong>
            </div>
        </div>
        <div class="ak-panel-title">
              <span class="ak-panel-title-icon"></span> Choisissez votre pays
        </div>
        <div class="ak-panel-content">
            <div class="panel-main">
                @foreach ($rates as $country => $data)
                <a href="{{ URL::route('shop.payment.method', $country) }}" title="{{ $country }}"><span class="icon-flag flag-{{ $country }}"></span></a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@stop
