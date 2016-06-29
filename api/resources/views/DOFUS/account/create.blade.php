@extends('layouts.master')

@section('header')
    {!! Html::style('css/register.css') !!}
    {!! Html::script('https://www.google.com/recaptcha/api.js') !!}
@stop

<?php $no_carousel = true; ?>

@section('page')
    <div class="container">
        <div class="register">
            <div class="row">
                <div class="step-1">
                    <div id="main">
                        <div class="block-header">
                            <div class="title">
                                <span>1</span>
                                Créez votre compte
                            </div>
                            <div class="text">
                                Pour figurer en bonne place dans le grand registre des héros,<br />
                                c'est très simple. Prenez votre plus belle plume et remplissez les cases ci-dessous.
                            </div>
                        </div>
                        <div class="block-body">
                            {!! Form::open(['route' => 'register']) !!}
                                <div class="form-group">
                                    <label for="username">Nom de compte*</label>
                                    <input id="username" type="text" autocorrect="off" autocapitalize="off" placeholder="Nom de compte" name="username" value="{{ Input::old('username') }}" @if ($errors->has('username')) class="has-error" @endif />
                                    @if ($errors->has('username')) <span class="input-error">{{ $errors->first('username') }}</span> @endif
                                </div>
                                <div class="form-group">
                                    <label for="password">Mot de passe*</label>
                                    <input id="password" type="password" placeholder="Mot de passe" name="password" value="{{ Input::old('password') }}" @if ($errors->has('password')) class="has-error" @endif />
                                    <div id="passwordpower"></div>
                                    @if ($errors->has('password')) <span class="input-error">{{ $errors->first('password') }}</span> @endif
                                </div>
                                <div class="form-group">
                                    <label for="password_confirm">Confirmation*</label>
                                    <input id="password_confirm" type="password" placeholder="Confirmation" name="password_confirm" value="{{ Input::old('password_confirm') }}" @if ($errors->has('password_confirm')) class="has-error" @endif />
                                    @if ($errors->has('password_confirm')) <span class="input-error">{{ $errors->first('password_confirm') }}</span> @endif
                                </div>
                                <div class="form-group">
                                    <label for="email">E-mail*</label>
                                    <input id="email" type="email" placeholder="E-mail" name="email" value="{{ Input::old('email') }}" @if ($errors->has('email')) class="has-error" @endif />
                                    @if ($errors->has('email')) <span class="input-error">{{ $errors->first('email') }}</span> @endif
                                </div>
                                <div class="form-group captcha">
                                    <label for="captcha">Code de sécurité*</label>
                                    {!! Recaptcha::render() !!}
                                </div>
                                @if ($errors->has('g-recaptcha-response')) <br /><span class="input-error">{{ $errors->first('g-recaptcha-response') }}</span> @endif
                                <div class="form-group">
                                    <label class="checkbox">
                                        <input type="checkbox" name="cg" value="1" />
                                        J'ai lu et j'accepte les <a href="">conditions générales</a> du site.
                                    </label>
                                    @if ($errors->has('cg')) <span class="input-error">{{ $errors->first('cg') }}</span> @endif
                                </div>
                                <div class="block-submit">
                                    <input class="btn-big" type="submit" value="Terminer l'inscription" />
                                </div>
                            {!! Form::close() !!}
                        </div> <!-- block-body -->
                    </div> <!-- main -->
                </div> <!-- step-1 -->

                <div class="step-2">
                    <div id="main">
                        <div class="block-header">
                            <div class="title">
                                <span>2</span>
                                Téléchargez {{ config('dofus.title') }}
                            </div>
                            <div class="text">
                                Tofus, wabbits, bouftous, héros, donjons, quêtes et dragons :<br />
                                téléchargez l'intégrale du Monde des Douze !
                            </div>
                        </div>
                        <div class="block-body">
                            <a href="" class="btn-big download">Télécharger le  jeu</a>
                        </div>
                    </div> <!-- main -->
                </div> <!-- step-2 -->
            </div> <!-- row -->

            <div class="step-3">
                <div id="main">
                    <div class="block-header">
                        <div class="title">
                            <span>3</span>
                            En route !
                        </div>
                        <div class="illu"></div>
                        <div class="text">
                            <p>Votre quête peut maintenant<br />
                            commencer. Faites preuve de<br />
                            bravoure, d’intelligence<br />
                            et d’héroïsme.</p>
                            <br />
                            <p>Préparez-vous à vivre une<br />
                            aventure hors du commun,<br />
                            le peuple n’attend que vous !</p>
                        </div>
                    </div>
                    <div class="block-body">
                        <div style="text-align:center;margin-top:50px;">
                            <img src="{{ URL::asset('imgs/wip.png') }}" /><br />
                            Prochainement...
                        </div>
                    </div>
                </div> <!-- main -->
            </div> <!-- step-3 -->
        </div> <!-- register -->
    </div> <!-- container -->
@stop
