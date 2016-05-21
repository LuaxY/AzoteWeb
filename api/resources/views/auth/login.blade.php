@extends('layouts.boostrap')

@section('page')

<div class="col-md-4 col-md-offset-4">

    @if($errors->has('auth'))

    <div class="panel panel-danger">
        <div class="panel-heading">Erreurs</div>
        <div class="panel-body">
            {{ $errors->first('auth') }}
        </div>
    </div>

    @endif

    <div class="panel panel-default">

        <div class="panel-heading">Connexion</div>
        <div class="panel-body" style="padding: 30px;">

            {{ Form::open(['class' => 'form-horizontal']) }}

                <div class="form-group @if ($errors->has('auth')) has-error @endif">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa m-r-xs fa-envelope" aria-hidden="true"></i></span>
                        <input type="email" class="form-control" name="email" placeholder="Adresse E-mail" value="{{ old('email') }}" autofocus required>
                    </div>
                </div>

                <div class="form-group @if ($errors->has('auth')) has-error @endif">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa m-r-xs fa-key" aria-hidden="true"></i></span>
                        <input type="password" class="form-control" name="password" placeholder="Mot de passe" required>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="checkbox">
                                <label><input type="checkbox" name="remember"> Se souvenir de moi</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group pull-right">
                            <button type="submit" class="btn btn-primary"><i class="fa m-r-xs fa-sign-in"></i>Connexion</button>

                        </div>
                    </div>

                </div>

            {{ Form::close() }}

            <hr>
            <h4>Mot de passe oublié ?</h4>
            <p>Pas de soucis, <a href="">clique ici</a> pour réinitialiser ton mot de passe.<p>

        </div>
    </div>

</div>
@stop
