@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('header')
    {!! Html::style('css/table.css') !!}
    {!! Html::style('css/dashboard.css') !!}
@stop

@section('content')
    <div class="content">
        <h1 class="content-title">
            <span class="icon-big icon-bank"></span> Mon compte
        </h1>

        <div class="title">
            <span class="picto"></span>Mes comptes de jeu</span>
        </div>

        <table>
            <tr>
                <th>#</th>
                <th>Pseudo</th>
                <th>Serveur</th>
                <th style="width: 100px; text-align: center;">Personnages</th>
                <th style="width: 200px; text-align: center;">Actions</th>
            </tr>
@foreach ($accounts as $account)
            <tr>
                <td>1</td>
                <td>{{ $account->Nickname }}</td>
                <td>{{ ucfirst($account->server) }}</td>
                <td align="center">{{ count($account->characters()) }}</td>
                <td align="center">Visualiser</td>
            </tr>
@endforeach
        </table>

        <a href=""><button>+ Créer un nouveau compte</button></a>
        <br><br>

        <div class="title">
            <span class="picto"></span>Historique des transactions</span>
        </div>

        <table>
            <tr>
                <th>#</th>
                <th>Date d'achat</th>
                <th>Ogrines</th>
                <th>Code</th>
                <th>Statut</th>
            </tr>
@foreach (Auth::user()->transactions() as $transaction)
            <tr>
                <td>{{ $transaction->id }}</td>
                <td>{{ $transaction->created_at }}</td>
                <td>{{ Utils::format_price($transaction->points, ' ') }} OGR</td>
                <td>{{ $transaction->code }}</td>
                <td>{{ Utils::transaction_status($transaction->state) }}</td>
            </tr>
@endforeach
        </table>

    </div> <!-- content -->
@stop
