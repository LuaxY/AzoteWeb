@extends('layouts.master')

@section('header')
    {!! Html::style('css/404.css') !!}
@stop

<?php $no_carousel = true; ?>

@section('page')
    <div class="container">
        <div class="p404">
            <div class="p404-text">
                <div>404</div>
                    <div>Tous les chemins mènent au monde des douze...</div>
                <div>sauf celui-là !</div>
            </div>
            <a class="p404-back" href="{{ URL::to('/') }}">Aller à l'accueil</a>
        </div>
    </div>
@stop
