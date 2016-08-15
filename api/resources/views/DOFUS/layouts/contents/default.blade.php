@extends('layouts.master')
@include('layouts.tops.play')

@section('page')
@if (@$carousel)
<div class="ak-container ak-carrousel-bg">
    <div class="ak-video-home ak-no-video"></div>
    <a href="">
        <div class="img_sm"></div>
    </a>
    <div class="ak-carrousel-bg-title ak-carrousel-bg-title-left">
        <div class="ak-block-title">
            <a href="" class="ak-title-link">
                <div class="ak-layer">
                    <span class="ak-title">Ouverture du serveur !</span>
                    <br> <span class="ak-subtitle">Rejoignez-nous maintenant !</span> <span class="ak-banner-more">+</span>
                    <br>
                </div>
            </a>
        </div>
    </div>
</div>
@endif

@yield('top')

<div class="container ak-main-container">
    <div class="ak-main-content">
        <div class="ak-main-page">
            <div class="row">
                @yield('menu')
                <div class="main col-md-9">
                    <div class="ak-container ak-main-center">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
