@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('header')
    {!! Html::style('css/flags.css') !!}
    {!! Html::style('css/shop.css') !!}

    <script>
    openPaymentWindow = function(ticket, country, method, palier) {
        window.open('http://code.dev/code?ticket=' + ticket + '&country=' + country + '&pay_id=' + method + '_' + palier, 'Payment', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=600,height=670,left = 420,top = 150');
    };
    </script>
@stop

@section('breadcrumbs')
{? $page_name = 'Paiement' ?}
{!! Breadcrumbs::render('shop.page', $page_name) !!}
@stop

@section('content')
<div class="ak-title-container ak-backlink">
    <h1 class="ak-return-link">
        <span class="ak-icon-big ak-shop"></span> Achat d'ogrines
    </h1>
</div>

<div class="ak-container ak-panel-stack ak-payments-process">
    <div class="ak-container ak-panel">
        <div class="ak-panel-title">
              <span class="ak-panel-title-icon"></span> Paiement par {{ $method }} pour {{ $payment->points }} Ogrines &nbsp;<span class="icon-flag flag-{{ $country }}"></span>
        </div>
        <div class="ak-panel-content">
            <div class="panel-main text-center">
                <a href="#" onClick="openPaymentWindow('{{ Auth::user()->ticket }}', '{{ $country }}', '{{ $method }}', '{{ $palier }}'); return false;"><btton class="btn btn-primary btn-lg">Procéder au paiement</button></a>
            </div>
        </div>
    </div>
</div>
@stop
