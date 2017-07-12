@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('header')
    {!! Html::style('css/ladder.css') !!}
    {!! Html::style('css/ladder-tiny.css') !!}
@stop

@section('breadcrumbs')
{? $page_name = 'Kolizéum 1vs1' ?}
{!! Breadcrumbs::render('ladder.page', $page_name, $server) !!}
@stop

@section('content')
<div class="ak-container ak-main-center">
    <div class="ak-title-container">
        <h1><span class="ak-icon-big ak-ladder-kolizeum"></span></a> Classement {{ $page_name }}</h1>
    </div>

    @include('ladder.servers')
    @include('ladder.menu')

    <div class="ak-container ak-banner-header">
        <img class="img-responsive" src="{{ URL::asset('imgs/assets/header_koli1v1.jpg') }}">
    </div>

    <div class="ak-responsivetable-wrapper">
        <table border="1" class="ak-ladder ak-container ak-table ak-responsivetable" style="white-space: nowrap; visibility: visible;">
            <thead>
                <tr class="ak-ajaxloader">
                    <th>#</th>
                    <th>Nom</th>
                    <th>Classe</th>
                    <th class="ak-center">Niveau</th>
                    @if (config('dofus.details')[$server]->prestige) <th class="ak-center">Prestige</th> @endif
                    <th>Cote</th>
                    <th class="ak-xp-total">Victoires (jour)</th>
                    <th class="ak-xp-total">Défaites (jour)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($characters as $i => $character)
                <tr class="@if ($i == 0) ak-first-ladder @elseif ($i %2 == 0) ak-bg-even @else ak-bg-odd @endif">
                    <td class="ak-rank">
                        @if ($i < 3)
                        <span class="ak-icon-position ak-position-{{ $i+1 }}">{{ $i+1 }}</span>
                        @else
                        {{ $i+1 }}
                        @endif
                    </td>
                    <td class="ak-name">
                        <span class="ak-breed-icon breed{{ $character->Breed }}_{{ $character->Sex }}"></span>
                        {{ Html::link(route('characters.view', [$server, $character->Id, $character->Name]), $character->Name) }}
                    </td>
                    <td class="ak-class">{{ $character->classe() }}</td>
                    <td class="ak-center">{{ $character->level($server) }}</td>
                    @if (config('dofus.details')[$server]->prestige) <td class="ak-center">{{ $character->PrestigeRank }}</td> @endif
                    <td>{{$character->ArenaDuelRank}}</td>
                    <td class="ak-xp-total">{{$character->ArenaDuelDailyMatchsWon}}</td>
                    <td class="ak-xp-total">{{$character->ArenaDuelDailyMatchsCount - $character->ArenaDuelDailyMatchsWon}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop