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
                {!! Form::open(['route' => 'password-lost']) !!}
                <div class="form-group">
                    <label for="username">Email</label>
                    <input id="username" type="text" autocorrect="off" autocapitalize="off" placeholder="Email" name="email" />
                </div>
                <input type="submit" value="Réinitialiser le mot de passe" />
                {!! Form::close() !!}
            </div>

            <div class="right">
                <h2>Pas de compte ?</h2>
                <a class="login-register" href="{{ URL::route('register') }}">Créer un compte</a>
            </div>
        </div>
    </div> <!-- content -->
@stop
