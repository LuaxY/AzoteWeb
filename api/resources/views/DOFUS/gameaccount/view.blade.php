@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('breadcrumbs')
{!! Breadcrumbs::render('gameaccount', [$account->server, $account->Id]) !!}
@stop

@section('content')
<div class="ak-container ak-main-center">
    <div class="ak-title-container">
        <h1><span class="ak-icon-big ak-bank"></span></a> Compte de Jeu</h1>
    </div>

    <div class="ak-container ak-panel-stack">
        <div class="ak-container ak-panel ak-glue">
            <div class="ak-panel-content">
                <div class="panel-main profile ak-form">
                    <div class="code">
                        Code : <span>{{ $account->SecretAnswer }}</span>
                    </div>
                    <b>Pseudo</b>: {{ $account->Nickname }}<br>
                    <b>Serveur</b>: {{ ucfirst($account->server) }}<br><br>
                    <b>Ogrines</b>:
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="ak-icon-small ak-ogrines-icon"></span></span>
                            <input type="text" class="form-control ak-tooltip" value="{{ Utils::format_price($account->points()) }}" id="ogrinesGame" readonly />
                        </div>
                    </div>
                    <a href="{{ URL::route('gameaccount.transfert', [$account->server, $account->Id]) }}" class="btn btn-primary btn-sm">Transferer des Ogrines <span class="ak-icon-small ak-ogrines-icon"></span></a>
                    <a href="{{ URL::route('gameaccount.edit', [$account->server, $account->Id]) }}" class="btn btn-primary btn-sm">Changer de mot de passe</a>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="ak-panel-title">
                <span class="ak-panel-title-icon"></span> Mes personnages
            </div>
        </div>
    </div>

    <table class="ak-ladder ak-container ak-table ak-responsivetable" style="white-space: nowrap; visibility: visible;">
        <tr>
            <th class="ak-center"></th>
            <th>Pseudo</th>
            <th>Classe</th>
            <th class="ak-center">Niveau</th>
            <th class="ak-center" style="width: 200px;">Actions</th>
        </tr>
        @foreach ($account->characters() as $character)
        <tr>
            <td class="ak-rank"></td>
            <td class="ak-name">
                <span class="ak-breed-icon breed{{ $character->Breed }}_{{ $character->Sex }}"></span>
                <a href="{{ URL::route('characters.view', [$account->server, $character->Id]) }}">{{ $character->Name }}</a>
            </td>
            <td class="ak-class">{{ $character->classe() }}</td>
            <td class="ak-center">{{ $character->level() }}</td>
            <td class="ak-center"><a href="{{ URL::route('characters.view', [$account->server, $character->Id]) }}"><span class="ak-icon-small ak-filter"></span></a></td>
        </tr>
        @endforeach
    </table>

    <div class="ak-container ak-panel-stack">
        <div class="ak-container ak-panel ak-glue">
            <div class="ak-panel-title">
                  <span class="ak-panel-title-icon"></span> Mes transferts
            </div>
        </div>
    </div>

    <table class="ak-container ak-table">
        <tr>
            <th class="ak-center">#</th>
            <th>Date du transfert</th>
            <th>Montant</th>
            <th>Statut</th>
        </tr>
        @foreach ($account->transferts() as $transfert)
        <tr>
            <td class="ak-center">{{ $transfert->id }}</td>
            <td>{{ $transfert->created_at }}</td>
            <td>{{ Utils::format_price($transfert->amount, ' ') }} <span class="ak-icon-small ak-ogrines-icon"></span></td>
            <td>{{ Utils::transfert_status($transfert->state) }}</td>
        </tr>
        @endforeach
    </table>
</div>
@stop
