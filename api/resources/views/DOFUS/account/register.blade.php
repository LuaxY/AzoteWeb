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
                                    <label for="username">Votre prénom</label>
                                    <input id="username" type="text" autocorrect="off" autocapitalize="off" placeholder="Votre prénom" name="firstname" value="{{ Input::old('firstname') }}" @if ($errors->has('firstname')) class="has-error" @endif required />
                                    @if ($errors->has('firstname')) <span class="input-error">{{ $errors->first('firstname') }}</span> @endif
                                </div>

                                <div class="form-group">
                                    <label for="username">Votre nom</label>
                                    <input id="username" type="text" autocorrect="off" autocapitalize="off" placeholder="Votre nom" name="lastname" value="{{ Input::old('lastname') }}" @if ($errors->has('lastname')) class="has-error" @endif required />
                                    @if ($errors->has('lastname')) <span class="input-error">{{ $errors->first('lastname') }}</span> @endif
                                </div>

                                <div class="form-group">
                                    <label for="email">Votre adresse e-mail</label>
                                    <input id="email" type="email" placeholder="Votre adresse e-mail" name="email" value="{{ Input::old('email') }}" @if ($errors->has('email')) class="has-error" @endif required />
                                    @if ($errors->has('email')) <span class="input-error">{{ $errors->first('email') }}</span> @endif
                                </div>

                                <div class="form-group">
                                    <label for="password">Votre mot de passe</label>
                                    <input id="password" type="password" placeholder="Mot de passe" name="password" value="{{ Input::old('password') }}" @if ($errors->has('password')) class="has-error" @endif required />
                                    <div id="passwordpower"></div>
                                    @if ($errors->has('password')) <span class="input-error">{{ $errors->first('password') }}</span> @endif
                                </div>

                                <div class="form-group">
                                    <label for="password_confirm">Confirmation</label>
                                    <input id="password_confirm" type="password" placeholder="Confirmation" name="passwordConfirmation" value="{{ Input::old('passwordConfirmation') }}" @if ($errors->has('passwordConfirmation')) class="has-error" @endif required />
                                    @if ($errors->has('passwordConfirmation')) <span class="input-error">{{ $errors->first('passwordConfirmation') }}</span> @endif
                                </div>

                                <div class="form-group captcha">
                                    <label for="captcha">Code de sécurité*</label>
                                    {!! Recaptcha::render() !!}
                                </div>
                                @if ($errors->has('g-recaptcha-response')) <br /><span class="input-error">{{ $errors->first('g-recaptcha-response') }}</span> @endif

                                <div class="form-group">
                                    <label class="checkbox">
                                        <input type="checkbox" name="cgu" value="1" required/>
                                        J'ai lu et j'accepte les <a href="">Conditions d'utilisation</a> du site.
                                    </label>
                                    @if ($errors->has('cgu')) <span class="input-error">{{ $errors->first('cgu') }}</span> @endif
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
