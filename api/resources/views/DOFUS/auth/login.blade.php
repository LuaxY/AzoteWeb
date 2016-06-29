@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('header')
    {!! Html::style('css/login.css') !!}
@stop

@section('content')
    <div class="content">
        <h1 class="content-title">
            <span class="icon-big icon-character"></span> Connexion
        </h1>

        <div id="login-form">
            <div class="left">
                {!! Form::open(['route' => 'login']) !!}
                @if($errors->has('auth')) <span class="input-error" style="font-weight: 400; font-size: 12px;">{{ $errors->first('auth') }}</span> @endif
                <div class="form-group">
                    <label for="username">Nom de compte</label>
                    <input id="username" type="text" autocorrect="off" autocapitalize="off" placeholder="Nom de compte" name="username" value="{{ Input::old('username') }}" @if ($errors->has('auth')) class="has-error" @endif />
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input id="password" type="password" placeholder="Mot de passe" name="password" @if ($errors->has('auth')) class="has-error" @endif />
                </div>
                <input type="submit" value="Connexion" />
                {!! Form::close() !!}
                <a class="login-lost" href="{{ URL::to('password/reset') }}">Mot de passe oublié ?</a>
            </div>

            <div class="right">
                <h2>Pas de compte ?</h2>
                <a class="login-register" href="{{ URL::route('register') }}">Créer un compte</a>
                <img src="{{ URL::asset('imgs/erezia.png') }}" width="200" />
            </div>
        </div>
    </div> <!-- content -->
@stop
