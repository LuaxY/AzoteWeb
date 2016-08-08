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
                    <label for="email">Email</label>
                    <input id="email" type="text" autocorrect="off" autocapitalize="off" placeholder="Email" name="email" value="{{ Input::old('email') }}" @if ($errors->has('auth')) class="has-error" @endif />
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input id="password" type="password" placeholder="Mot de passe" name="password" @if ($errors->has('auth')) class="has-error" @endif />
                </div>
                <input type="submit" value="Connexion" />
                {!! Form::close() !!}
                <a class="login-lost" href="{{ URL::route('password-lost') }}">Mot de passe oublié ?</a>
            </div>

            <div class="right">
                <h2>Pas de compte ?</h2>
                <a class="login-register" href="{{ URL::route('register') }}">Créer un compte</a>
                <img src="{{ URL::asset('imgs/azote.png') }}" width="100" />
            </div>
        </div>
    </div> <!-- content -->
@stop
