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
            <span class="picto"></span>Mes informations</span>
        </div>

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
@foreach (Auth::user()->transactions()->get() as $transaction)
            <tr>
                <td>{{ $transaction->id }}</td>
                <td>{{ $transaction->date }}</td>
                <td>{{ Utils::format_price( $transaction->points, ' ') }} OGR</td>
                <td>{{ $transaction->code }}</td>
                <td>{{ Utils::transaction_status($transaction->state) }}</td>
            </tr>
@endforeach
        </table>
    </div> <!-- content -->
@stop
