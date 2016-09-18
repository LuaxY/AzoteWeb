@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('breadcrumbs')
{!! Breadcrumbs::render('gameaccount.page', 'Transférer des Ogrines', [$account->server, $account->Id]) !!}
@stop

@section('content')
<div class="ak-container ak-main-center">
    <div class="ak-title-container ak-backlink">
        <h1><span class="ak-icon-big ak-bank"></span></a> Transférer des Ogrines</h1>
        <a href="{{ URL::route('gameaccount.view', [$account->server, $account->Id]) }}" class="ak-backlink-button">Retour à mon compte</a>
    </div>

    <div class="ak-container ak-panel-stack">
        <div class="ak-container ak-panel ak-glue">
            <div class="ak-panel-content">
                <div class="panel-main ak-form">
                    {!! Form::open(['route' => ['gameaccount.transfert', $account->server, $account->Id]]) !!}

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@stop
