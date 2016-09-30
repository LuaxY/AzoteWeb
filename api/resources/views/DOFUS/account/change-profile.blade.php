@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('breadcrumbs')
{? $page_name = 'Éditer le profil' ?}
{!! Breadcrumbs::render('account.page', $page_name) !!}
@stop

@section('content')
<div class="ak-container ak-main-center">
    <div class="ak-title-container ak-backlink">
        <h1><span class="ak-icon-big ak-bank"></span></a> Éditer le profil</h1>
        <a href="{{ URL::route('profile') }}" class="ak-backlink-button">Retour à mon compte</a>
    </div>

    <div class="ak-container ak-panel-stack">
        <div class="ak-container ak-panel ak-glue">
            <div class="ak-panel-content">
                <div class="panel-main ak-form">
                    {!! Form::model($authuser, ['route' => 'account.change_profile']) !!}

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

                    <input type="submit" role="button" class="btn btn-primary btn-lg" value="Éditer le profil">
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@stop
