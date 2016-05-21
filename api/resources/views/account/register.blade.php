@extends('layouts.boostrap')

@section('page')

<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">

    @if($errors->has())

    <div class="panel panel-danger">
        <div class="panel-heading">Erreurs</div>
        <div class="panel-body">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{!! $error !!}</li>
                @endforeach
            </ul>
        </div>
    </div>

    @endif

    <div class="panel panel-default">

        <div class="panel-heading">Inscription</div>
        <div class="panel-body" style="padding: 30px;">

            <p>Déjà inscrit? <a href="{{ route('login') }}">Connectez-vous</a> à votre compte.</p>
            <hr>

            {{ Form::open(['class' => 'form-horizontal']) }}

                <div class="form-group @if ($errors->has('firstname')) has-error @endif">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa m-r-xs fa-user" aria-hidden="true"></i></span>
                        <input type="text" class="form-control" name="firstname" placeholder="Prénom" value="{{ old('firstname') }}" autofocus required>
                    </div>
                </div>

                <div class="form-group @if ($errors->has('lastname')) has-error @endif">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa m-r-xs fa-user" aria-hidden="true"></i></span>
                        <input type="text" class="form-control" name="lastname" placeholder="Nom" value="{{ old('lastname') }}" required>
                    </div>
                </div>

                <div class="form-group @if ($errors->has('email')) has-error @endif">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa m-r-xs fa-envelope" aria-hidden="true"></i></span>
                        <input type="email" class="form-control" name="email" placeholder="Adresse E-mail" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="row" style="margin-left: -30px; margin-right: -30px;">

                    <div class="col-lg-6">
                        <div class="input-group @if ($errors->has('password')) has-error @endif" style="margin-bottom: 15px;">
                            <span class="input-group-addon"><i class="fa m-r-xs fa-key" aria-hidden="true"></i></span>
                            <input type="password" class="form-control" name="password" placeholder="Mot de passe" required>
                        </div>
                    </div>

                    <div class="col-lg-6 pull-right">
                        <div class="input-group margin-bottom-20 @if ($errors->has('passwordConfirmation')) has-error @endif" style="margin-bottom: 15px;">
                            <span class="input-group-addon"><i class="fa m-r-xs fa-key" aria-hidden="true"></i></span>
                            <input type="password" class="form-control" name="passwordConfirmation" placeholder="Confirmation" required>
                        </div>
                    </div>

                </div>

                <hr>

                <div style="margin-left: -15px;">
                    {!! Recaptcha::render(['lang' => 'fr']) !!}
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="form-group @if ($errors->has('cgu')) has-error @endif">
                            <div class="checkbox">
                                <label><input type="checkbox" name="cgu" required> J'ai lu et j'accepte les <a href="">Conditions d'utilisation</a></label>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 pull-right" style="margin-right: -15px;">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa m-r-xs fa-user-plus"></i>S'inscrire</button>

                        </div>
                    </div>
                </div>

            {{ Form::close() }}

        </div>
    </div>

</div>
@stop
