@extends('layouts.boostrap')

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

<div class="col-md-3">

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="thumbnail">
                <img src="{{ Gravatar::src(Auth::user()->email, 262) }}">
            </div>

            <p>Bnojour <b>{{ Auth::user()->firstname }}</b></p>
            <p><a href=""><i class="fa fa-power-off"></i> Déconnexion</a></p>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-body">
            <p>Mon solde de points</p>
            <h1>0</h1>
            <p><b><a href=""><i class="fa fa-cart-plus"></i> En acheter d'avantage</a></b></p>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">
            <ul class="nav" >
                <li><a href=""><i class="fa fa-user"></i> Mon profile</a></li>
                <li><a href=""><i class="fa fa-users"></i> Mes comptes de jeu</a></li>
                <li><a href=""><i class="fa fa-shopping-cart"></i> Mes achats</a></li>
                <li><a href=""><i class="fa fa-life-ring"></i> Mes tickets</a></li>
            </ul>
        </div>
    </div>

</div>
@stop
