@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('header')
    {!! Html::style('css/shop.css') !!}
@stop

@section('content')
    <div class="content">
        <h1 class="content-title">
            <span class="icon-big icon-shop"></span> Achat d'ogrines
        </h1>

        <div class="shop">
            <div class="shop-content">
                <div class="title">
                    <span class="picto"></span> Choisissez votre pays
                </div>
                <div class="shop-country">
@foreach ($rates as $country => $data)
                    <a href="{{ URL::route('shop.payment.method', $country) }}" title="{{ $country }}"><span class="icon-flag flag-{{ $country }}"></span></a>
@endforeach
                </div>
            </div>
        </div> <!-- shop -->
    </div> <!-- content -->
@stop
