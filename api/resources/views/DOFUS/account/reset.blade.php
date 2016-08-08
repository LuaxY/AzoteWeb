@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('header')
    {!! Html::style('css/login.css') !!}
@stop

@section('content')
    <div class="content">
        <h1 class="content-title">
            <span class="icon-big icon-character"></span> Mot de passe oublié
        </h1>

        <div id="login-form">
            <div class="left">
                {!! Form::open(['route' => 'reset']) !!}
                <div class="form-group">
                    <label for="password">Nouveau mot de passe</label>
                    <input id="password" type="password" autocorrect="off" autocapitalize="off" placeholder="Mot de passe" name="password" value="{{ Input::old('password') }}" @if ($errors->has('password')) class="has-error" @endif />
                    @if ($errors->has('password')) <span class="input-error">{{ $errors->first('password') }}</span> @endif
                </div>
                <div class="form-group">
                    <label for="passwordConfirmation">Confirmation</label>
                    <input id="passwordConfirmation" type="password" autocorrect="off" autocapitalize="off" placeholder="Confirmation" name="passwordConfirmation" value="{{ Input::old('passwordConfirmation') }}" @if ($errors->has('passwordConfirmation')) class="has-error" @endif />
                    @if ($errors->has('passwordConfirmation')) <span class="input-error">{{ $errors->first('passwordConfirmation') }}</span> @endif
                </div>
                <input type="submit" value="Réinitialiser le mot de passe" />
                {!! Form::close() !!}
            </div>
        </div>
    </div> <!-- content -->
@stop
