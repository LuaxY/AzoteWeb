@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('breadcrumbs')
{? $page_name = 'Transférer des cadeaux' ?}
{!! Breadcrumbs::render('gameaccount.page', $page_name, [$account->server, $account->Id]) !!}
@stop

@section('content')
<div class="ak-container ak-main-center">
    <div class="ak-title-container ak-backlink">
        <h1><span class="ak-icon-big ak-gift"></span></a> Transférer des cadeaux</h1>
        <a href="{{ URL::route('gameaccount.view', [$account->server, $account->Id]) }}" class="ak-backlink-button">Retour à mon compte</a>
    </div>

    {!! Form::open(['route' => ['gameaccount.gifts', $account->server, $account->Id]]) !!}

    <div class="ak-container ak-panel-stack">
        <div class="ak-container ak-panel ak-glue">
            <div class="ak-panel-content">
                <div class="panel-main ak-form">
                    Selectionnez le cadeau à transférer sur votre compte.
                    @if ($errors->has('gift'))
                    <div class="form-group  has-error" style="margin-bottom:0">
                        <label class="error control-label">{{ $errors->first('gift') }}</label>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <table class="ak-ladder ak-container ak-table ak-responsivetable" style="white-space: nowrap; visibility: visible;">
        <tr>
            <th class="ak-center"></th>
            <th>Date</th>
            <th width="50"></th>
            <th>Cadeau</th>
            <th>Description</th>
        </tr>
        @foreach (Auth::user()->gifts(true) as $gift)
        <tr>
            <td class="ak-rank"><input type="radio" name="gift_id" value="{{ $gift->id }}"></td>
            <td>{{ $gift->created_at->format('d/m/Y H:i:s') }}</td>
            <td><img src="{{ $gift->item()->image() }}" width="50" height="50"></td>
            <td>{{ $gift->item()->name() }}</td>
            <td>{{ $gift->description }}</td>
        </tr>
        @endforeach
    </table>

    <div class="ak-container ak-panel-stack">
        <div class="ak-container ak-panel ak-glue">
            <div class="ak-panel-content">
                <input type="submit" role="button" class="btn btn-primary btn-lg" value="Transférer">
            </div>
        </div>
    </div>

    {!! Form::close() !!}
</div>
@stop
