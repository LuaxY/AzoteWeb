@extends('layouts.master')
@include('layouts.tops.play')

@section('page')
@if (@$carousel)
<div class="ak-container ak-carrousel-bg">
    <div class="ak-video-home ak-no-video"></div>
    <a href="">
        <div class="img_sm"></div>
    </a>
    <div class="ak-carrousel-bg-title ak-carrousel-bg-title-left hidden-xs">
        @if (config('dofus.motd'))
        <div class="ak-block-title">
            <a href="{{ route('posts.show', config('dofus.motd.post_id')) }}" class="ak-title-link">
                <div class="ak-layer">
                    <span class="ak-title">{{ config('dofus.motd.title') }}</span>
                    <br> <span class="ak-subtitle">{{ config('dofus.motd.subtitle') }}</span> <span class="ak-banner-more">+</span>
                    <br>
                </div>
            </a>
        </div>
        @endif
    </div>
</div>
@endif

@yield('top')

<div class="container ak-main-container">
    <div class="ak-main-content">
        <div class="ak-main-page">
            <div class="row">
                <div class="main col-md-9 col-md-push-3">
                    <div class="ak-container ak-main-center">
                        @yield('content')
                    </div>
                </div>
                @yield('menu')
            </div>
        </div>
    </div>
</div>
@stop
