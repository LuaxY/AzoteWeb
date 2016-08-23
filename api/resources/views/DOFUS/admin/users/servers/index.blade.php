@extends('layouts.admin.admin')
@section('page-title') User {{ $user->firstname }} @endsection
@section('page-title') User: {{ $user->firstname }} - Details @endsection
@section('content')
     <!-- Start content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    @include('includes.admin.users.navpills')
                    <div class="card-box">
                        @if(count($accounts) < 4)
                        <div class="pull-right">
                                <button href="javascript:void(0)" class="btn btn-primary btn-lg waves-effect waves-light" data-toggle="modal" data-target="#game-account-add-modal"><i class="fa fa-plus"></i> Create account</button></li>
                        </div>
                        @endif
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
                                            <td> <a href="#" class="edit btn btn-xs btn-default" data-toggle="tooltip" title="Edit"><i class="fa fa-search"></i></a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                        @endif
                    </div>
                </div>
        </div>
    </div>
    </div>
        <div id="game-account-add-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Create: {{ ucfirst($server) }} Account</h4>
                    </div>
                    <div class="modal-body">

                        {!! Form::open([ 'route' => ['admin.user.game.account.store','server' => $server, 'id' => $user->id], 'id' => 'form-game-account-add']) !!}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="login" class="control-label">Login</label>
                                    <input type="text" class="form-control" id="login" name="login">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nickname" class="control-label">Nickname</label>
                                    <input type="text" class="form-control" id="nickname" name="nickname">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="password" class="control-label">Password</label>
                                    <input type="password" class="form-control" name="password" id="password">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="passwordConfirmation" class="control-label">Confirmation</label>
                                    <input type="password" class="form-control" name="passwordConfirmation" id="passwordConfirmation">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                        <button id="button-task-add" type="submit" class="btn btn-info waves-effect waves-light">Create Game Account</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
@endsection

@section('bottom')
<script>
    $( "#form-game-account-add" ).on( "submit", function( event ) {
        event.preventDefault();
        var $this = $(this);
        $.ajax({
            method: 'POST',
            url: $this.attr("action"),
            data: $this.serialize(),

            success: function (msg) {
                toastr.success('Adding account..');
                setTimeout(function(){ location.reload(); }, 500);
            },

            error: function (jqXhr, json, errorThrown) {
                var errors = jqXhr.responseJSON;
                var errorsHtml;
                if(errors)
                {
                    errorsHtml= '';
                    $.each( errors, function( key, value ) {
                        errorsHtml += '<li>' + value[0] + '</li>';
                    });
                }
                else
                {
                    errorsHtml = 'Unknow error';
                }
                toastr.error(errorsHtml);
            }
        });
    });
</script>
@endsection
