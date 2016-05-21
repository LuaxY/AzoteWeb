@extends('layouts.boostrap')

@section('menu')

<div class="col-md-3">


    <div class="panel panel-default panel-flush">
        <div class="panel-heading">Profile</div>
        <div class="panel-body">
            <div class="thumbnail">
                <img src="{{ Gravatar::src(Auth::user()->email, 262) }}">
            </div>

            <p>{{ Auth::user()->email }}</p>
            <p>{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</p>
        </div>
    </div>
</div>

@stop

@section('page')

<div class="col-md-9">

    <div class="panel panel-default">
        <div class="panel-heading">Mes comptes de jeu</div>
        <div class="panel-body">

            <div class="row">

                @foreach ($accounts as $account)

                <div class="col-md-3 col-xs-12">
                    <div class="thumbnail">
                        <img src="{{ Gravatar::src($account->Nickname, 171) }}">
                        <div class="caption" style="text-align: center;">
                            <h4>{{ $account->Nickname }}</h4>
                            <p>{{ count($account->characters()) }} personnage(s)</p>
                            <p><a href="" class="btn btn-default btn-block" role="button"><i class="fa fa-search" aria-hidden="true"></i> Visualiser</a></p>
                        </div>
                    </div>
                </div>

                @endforeach

                <div class="col-md-3 col-xs-12">
                    <div class="thumbnail">
                        {!! Html::image('images/add_account.png', 'Free slot') !!}
                        <div class="caption" style="text-align: center;">
                            <h4>Libre</h4>
                            <p>&nbsp;</p>

                             @can('user-edit')
                            <p><a href="" class="btn btn-default btn-block" role="button"><i class="fa fa-plus" aria-hidden="true"></i> Créer</a></p>
                            @endcan
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>
@stop
