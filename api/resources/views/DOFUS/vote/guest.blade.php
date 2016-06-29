@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('header')
    {!! Html::style('css/vote.css') !!}
@stop

@section('content')
    <div class="content">
        <h1 class="content-title">
            <span class="icon-big icon-character"></span> Vote pour le serveur
        </h1>

        <div id="vote-process">
            <div class="left">
                <a class="vote-link" href="{{ URL::to('http://www.rpg-paradize.com/?page=vote&vote=' . config("dofus.rpg-paradize.id")) }}" target="_blank">Voter</a>
            </div>
            <div class="right">
                Vous n'êtes pas identifié, votre vote ne rapporteras aucun points. <a href="{{ URL::route('login') }}">S'identifier</a>
            </div>
        </div>
    </div> <!-- content -->
@stop
