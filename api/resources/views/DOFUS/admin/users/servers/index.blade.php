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
                        <div class="dropdown pull-right">
                            <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false">
                                <i class="zmdi zmdi-more-vert"></i>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Actions</a></li>
                                <li><a href="#">Actions</a></li>
                            </ul>
                        </div>
                        <h4 class="header-title m-b-30">{{ ucfirst($server) }} Accounts: Details</h4>
                        {{ Html::image('imgs/admin/'.$server.'.png', $server, ['class' => 'center-block m-b-30']) }}

                        @if(count($accounts) == 0)
                            <div class="alert alert-info">
                                <strong>Info!</strong> User doesn't have any game accounts on this server
                            </div>
                        @else
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Login</th>
                                        <th>Nickname</th>
                                        <th>Tokens</th>
                                        <th>Last connection</th>
                                        <th>Last IP</th>
                                        <th>Last KEY</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($accounts as $account)
                                        <tr>
                                            <td>{{ $account->Id }}</td>
                                            <td>{{ $account->Login }}</td>
                                            <td>{{ $account->Nickname }}</td>
                                            <td>{{ $account->Tokens }}</td>
                                            <td>{{ $account->LastConnection }}</td>
                                            <td>{{ $account->LastConnectedIp }}</td>
                                            <td>{{ $account->LastClientKey }}</td>
                                            <td>@if($account->IsJailed == 1)<span class="label label-danger">Jailed</span>@endif
                                                @if(!$account->IsJailed && !$account->IsBanned)<span class="label label-success">OK</span>@endif
                                                @if($account->IsBanned == 1)<span class="label label-danger">Banned</span>@endif
                                            </td>
                                            <td> <a href="{{ route('admin.user.game.account.edit', [$user->id, $server, $account->Id]) }}" class="edit btn btn-xs btn-default" data-toggle="tooltip" title="Edit"><i class="fa fa-search"></i></a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                        @endif
                    </div>
                </div>
        </div>
    </div>
@endsection
