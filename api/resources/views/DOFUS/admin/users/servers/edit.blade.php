@extends('layouts.admin.admin')
@section('title') User {{ $user->firstname }} @endsection
@section('page-title') User: {{ $user->firstname }} - Details @endsection
@section('content')
    <!-- Start content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    @include('includes.admin.users.navpills')
                    <div class="card-box">
                        <div class="pull-right">
                            <a href="#">
                                <i class="fa fa-phone"></i>
                            </a>
                        </div>
                        <h4 class="header-title m-b-30">#{{$account->Id}} Account: Edit</h4>
                        {!! Form::model($account, ['route' => ['admin.user.game.account.update', $user->id, $server, $account->Id]]) !!}
                        {{ method_field('PATCH') }}

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {{ $errors->has('Login') ? ' has-error' : '' }}">
                                    <label for="title">Login:</label>
                                    {!! Form::text('Login', null, ['class' => 'form-control', 'id' => 'Login']) !!}
                                    @if ($errors->has('Login'))
                                        <span class="help-block">
                                                                    <strong>{{ $errors->first('Login') }}</strong>
                                                                </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group {{ $errors->has('Nickname') ? ' has-error' : '' }}">
                                    <label for="Nickname">Nickname:</label>
                                    {!! Form::text('Nickname', null, ['class' => 'form-control', 'id' => 'Nickname']) !!}
                                    @if ($errors->has('Nickname'))
                                        <span class="help-block">
                                                                    <strong>{{ $errors->first('Nickname') }}</strong>
                                                                </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        {!! Form::submit('Update', ['class' => 'btn btn-info']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
@endsection
