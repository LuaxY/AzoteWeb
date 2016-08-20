@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('breadcrumbs')
{!! Breadcrumbs::render('page', 'Support') !!}
@stop

@section('content')
<div class="ak-container ak-main-center">
    <div class="ak-title-container">
        <h1><span class="ak-icon-big ak-support"></span></a> Support</h1>
    </div>

    <div class="ak-container ak-panel-stack">
        <div class="ak-container ak-panel ak-glue">
            <div class="ak-panel-content">
                <div class="panel-main">
                    <p>Support en cours de construction. En cas de problème, contactez-nous par email.</p>
                    <a href="mailto:{{ config('dofus.email') }}"><button class="btn btn-primary btn-lg">Contacter le support</button></a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
