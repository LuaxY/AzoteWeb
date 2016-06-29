@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('header')
    {!! Html::style('css/shop.css') !!}
@stop

@section('content')
    <div class="content">
        <h1 class="content-title">
            <span class="icon-big icon-shop"></span> Achat d'ogrines
        </h1>

        <div class="shop">
            {!! Form::open(['route' => 'shop.payment.code']) !!}
                <input type="hidden" name="country" value="{{ $country }}" />
                <div class="shop-content">
                    <div class="title">
                        <span class="picto"></span> Choisissez votre mode de paiement &nbsp;<span class="icon-flag flag-{{ $country }}"></span>
                    </div>
@foreach ($methods as $methodName => $method)
@foreach ($method as $palier => $data)
                    <label>
                        <div class="shop-element">
                            <input type="radio" name="method" value="{{ $methodName }}_{{ $palier }}" />
                            <span class="shop-element-description">
                                <span class="shop-icon"><img src="{{ URL::asset('imgs/shop/payment/' . $methodName . '.png') }}" /></span>
                                <span class="shop-name">Code {{ $methodName }} : <span>{{ $data->points }} - {{ $data->cost }}</span></span>
                            </span>
@if ( array_key_exists($country . '|' . $methodName, config('dofus.promos')) )
                            <div class="shop-promo">
                                <span class="promo-title">Promo</span>
                                <span class="promo-desc">+ {{ config('dofus.promos')[$country . '|' . $methodName] }} ogrines offerts pour un achat par {{ $methodName }}</span>
                            </div>
@endif
                        </div>
                    </label>
@endforeach
@endforeach
                    <label>
                        <div class="shop-element unavailable">
                            <input type="radio" name="method" value="paypal" disabled="disabled" />
                            <span class="shop-description">
                                <span class="shop-icon"><img src="{{ URL::asset('imgs/shop/payment/paypal.png') }}" /></span>
                                <span class="shop-name">PayPal : <span>2,00 &euro;</span></span>
                            </span>
                        </div>
                    </label>
                    <label>
                        <div class="shop-element unavailable">
                            <input type="radio" name="method" value="paysafecard" disabled="disabled" />
                            <span class="shop-description">
                                <span class="shop-icon"><img src="{{ URL::asset('imgs/shop/payment/paysafecard.png') }}" /></span>
                                <span class="shop-name">paysafecard : <span>2,00 &euro;</span></span>
                            </span>
                        </div>
                    </label>

                    @if ($errors->has('country')) <div class="input-error">{{ $errors->first('country') }}</div> @endif
                    @if ($errors->has('method_')) <div class="input-error">{{ $errors->first('method_') }}</div> @endif
                    @if ($errors->has('palier')) <div class="input-error">{{ $errors->first('palier') }}</div> @endif
                </div>

                <div class="shop-content cgv">
                    <div class="checkbox">
                        <label>
                            @if ($errors->has('cgv')) <span class="input-error">{{ $errors->first('cgv') }}</span><br /> @endif
                            <input type="checkbox" name="cgv" value="1" /> En cochant cette case, vous acceptez expressément que la fourniture du contenu numérique (ogrines) commence immédiatement après l'envoi de notre mail de confirmation d'achat et renoncez donc expressément à votre droit de rétractation. Vous confirmez avoir pris connaissance des <a href="{{ URL::to('legal/cgv') }}">conditions générales de vente</a> d'{{ config('dofus.title') }} et vous confirmez que <b>{{ Auth::user()->Nickname }}</b> est le propriétaire du moyen de paiement ou que vous avez reçu l'autorisation du titulaire du moyen de paiement.
                        </label>
                    </div>
                    <button class="buy">
                        Payer maintenant
                        <span>(Commande avec obligation de paiement)</span>
                    </button>
                </div>
            {!! Form::close() !!}
        </div> <!-- shop -->
    </div> <!-- content -->
@stop
