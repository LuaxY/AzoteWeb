@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('breadcrumbs')
{!! Breadcrumbs::render('page', 'Mon compte') !!}
@stop

@section('content')
<div class="ak-container ak-main-center">
    <div class="ak-title-container">
        <h1><span class="ak-icon-big ak-bank"></span></a> Mon compte</h1>
    </div>

    <div class="ak-container ak-panel-stack">
        <div class="ak-container ak-panel ak-glue">
            <div class="ak-panel-content">
                <div class="panel-main">

                </div>
            </div>
            <div class="ak-panel-title">
                  <span class="ak-panel-title-icon"></span> Mes comptes de jeu
            </div>
        </div>
    </div>

    <table class="ak-container ak-table">
        <tr>
            <th class="ak-center">#</th>
            <th>Pseudo</th>
            <th>Serveur</th>
            <th class="ak-center" style="width: 100px;">Personnages</th>
            <th class="ak-center" style="width: 200px;">Actions</th>
        </tr>
        @foreach ($accounts as $account)
        <tr>
            <td class="ak-rank">1</td>
            <td><a href="{{ URL::route('gameaccount.view', [$account->server, $account->Id]) }}">{{ $account->Nickname }}</a></td>
            <td>{{ ucfirst($account->server) }}</td>
            <td class="ak-center">{{ count($account->characters()) }}</td>
            <td class="ak-center"><a href="{{ URL::route('gameaccount.view', [$account->server, $account->Id]) }}"><span class="ak-icon-small ak-filter"></span></a></td>
        </tr>
        @endforeach
    </table>

    <div class="ak-container ak-panel-stack">
        <div class="ak-container ak-panel ak-glue">
            <div class="ak-panel-content">
                <div class="">
                    <a href="{{ URL::route('gameaccount.create') }}"><button class="btn btn-info btn-lg">Créer un nouveau compte</button></a>
                </div>
            </div>

            <div class="ak-panel-title">
                  <span class="ak-panel-title-icon"></span> Mes achats
            </div>
        </div>
    </div>

    <table class="ak-container ak-table">
        <tr>
            <th class="ak-center">#</th>
            <th>Date d'achat</th>
            <th>Ogrines</th>
            <th>Code</th>
            <th>Statut</th>
        </tr>
        @foreach (Auth::user()->transactions() as $transaction)
        <tr>
            <td class="ak-center">{{ $transaction->id }}</td>
            <td>{{ $transaction->created_at }}</td>
            <td>{{ Utils::format_price($transaction->points, ' ') }} OGR</td>
            <td>{{ $transaction->code }}</td>
            <td>{{ Utils::transaction_status($transaction->state) }}</td>
        </tr>
        @endforeach
    </table>
@stop
