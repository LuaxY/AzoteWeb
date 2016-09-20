@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('breadcrumbs')
{!! Breadcrumbs::render('account.page', 'Certification') !!}
@stop

@section('content')
<div class="ak-container ak-main-center">
    <div class="ak-title-container ak-backlink">
        <h1><span class="ak-icon-big ak-bank"></span></a> Certification du compte</h1>
        <a href="{{ URL::route('profile') }}" class="ak-backlink-button">Retour à mon compte</a>
    </div>

    <div class="ak-container ak-panel-stack">
        <div class="ak-container ak-panel ak-glue">
            <div class="ak-panel-content">
                <h3>ATTENTION, vous êtes sur le point de certifier votre compte</h3>
                <p>Les informations renseignées ci-dessous doivent correspondrent à la <strong>réalité</strong> et ne pourront en <strong>aucun cas être modifiées.</strong> </p>
                <p>A tout moment, l'équipe se réserve le droit de vérifier ces données par le biais d'un document d'identité.</p>
                <div class="panel-main ak-form">
                    {!! Form::model($authuser, ['route' => 'account.certify']) !!}

                    <div class="form-group @if ($errors->has('firstname')) has-error @endif">
                        <label class="control-label" for="firstname">Prénom</label>
                        {!! Form::text('firstname', null, ['class' => 'form-control ak-tooltip', 'id' => 'firstname', 'required' => 'required']) !!}
                        @if ($errors->has('firstname')) <label class="error control-label">{{ $errors->first('firstname') }}</label> @endif
                    </div>

                    <div class="form-group @if ($errors->has('lastname')) has-error @endif">
                        <label class="control-label" for="lastname">Nom</label>
                        {!! Form::text('lastname', null, ['class' => 'form-control ak-tooltip', 'id' => 'lastname', 'required' => 'required']) !!}
                        @if ($errors->has('lastname')) <label class="error control-label">{{ $errors->first('lastname') }}</label> @endif
                    </div>

                    <div class="form-group @if ($errors->has('birthday')) has-error @endif">
                        <label class="control-label" for="birthday">Date de naissance</label>
                        {!! Form::date('birthday', null, ['class' => 'form-control ak-tooltip', 'id' => 'birthday', 'required' => 'required']) !!}
                        @if ($errors->has('birthday')) <label class="error control-label">{{ $errors->first('birthday') }}</label> @endif
                    </div>

                    <input type="submit" role="button" class="btn btn-danger btn-lg" value="Certifier mon compte">
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@stop
