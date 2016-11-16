@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('header')
    {!! Html::style('css/flags.css') !!}
    {!! Html::style('css/flag-icon.min.css') !!}
@stop

@section('breadcrumbs')
{? $page_name = 'Choix du pays' ?}
{!! Breadcrumbs::render('shop.page', $page_name) !!}
@stop

@section('content')
<div class="ak-title-container">
    <h1><span class="ak-icon-big ak-shop"></span> Achat d'ogrines</h1>
</div>

<div class="ak-container ak-panel-stack ak-payments-process-choice">
    <div class="ak-container ak-panel">
        <div class="ak-panel-title">
            <span class="ak-panel-title-icon"></span> Choisissez votre pays
        </div>
        <div class="ak-panel-content">
            <div class="panel-main">
                <style>
                .flag-icon {
                    float: left;
                }

                a.btn-flag {
                    width: 148px;
                    margin: 5px;
                }
                </style>
                @foreach ($rates as $country => $data)
                @if ($country != 'all' && $country != 'anre') <a class="btn btn-default btn-flag" href="{{ URL::route('shop.payment.method', $country) }}" title="{{ $country }}"><span class="flag-icon flag-icon-{{ $country }}"></span> {{ $country }}</a> @endif
                @if ($country == 'anre') <a class="btn btn-default btn-flag" href="{{ URL::route('shop.payment.method', $country) }}" title="DOM"><span class="flag-icon flag-icon-fr"></span> DOM</a> @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@stop
