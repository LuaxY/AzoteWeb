<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>{{ config('dofus.title') }} - {{ config('dofus.subtitle') }}</title>
    <link rel="icon" type="image/png" href="{{ URL::asset('imgs/favicon.png') }}" />
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Oswald" type="text/css" />
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto" type="text/css" />
    {!! Html::style('css/dofus.css') !!}
    {!! Html::style('css/icons.css') !!}
@if (config('dofus.theme'))
    {!! Html::style('imgs/carousel/'.config('dofus.theme').'/style.css') !!}
@endif
@yield('header')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
</head>
@if (config('dofus.theme'))
<body>
@else
<body style="background-image: url('{{ URL::asset('imgs/carousel/common/'.config('dofus.background').'.jpg') }}'); background-color: {{ config('dofus.color') }};">
@endif
    <div id="header">
        <div class="container">
            <div id="menu">
                <ul class="box left">
                    <li><a href="{{ URL::route('home') }}">{{ config('dofus.title') }}</a></li>
                    <li><a href="{{ URL::route('register') }}">Rejoindre</a></li>
                    <li><a href="{{ URL::route('shop.payment.country') }}">Boutique</a></li>
                </ul>
                <a href="{{ URL::route('home') }}"><div id="logo"></div></a>
                <ul class="box right">
                    <li><a href="{{ URL::route('vote.index') }}">Vote</a></li>
                    <li><a href="http://forum.erezia.net/">Forum</a></li>
                    <li><a href="{{ URL::to('support') }}">Support</a></li>
                </ul>
            </div>
        </div>
    </div>

@if (empty($no_carousel))
    <div id="carousel">
@if (config('dofus.theme') && config('dofus.carousel'))
        <video class="video" poster="{{ URL::asset('imgs/carousel/'.config('dofus.theme').'/preview.png') }}" loop="loop" autoplay="">
            <source src="{{ URL::asset('imgs/carousel/'.config('dofus.theme').'/video.mp4') }}" type="video/mp4">
            <source src="{{ URL::asset('imgs/carousel/'.config('dofus.theme').'/video.webm') }}" type="video/webm">
            <source src="{{ URL::asset('imgs/carousel/'.config('dofus.theme').'/video.ogv') }}" type="video/ogv">
        </video>
@endif
    </div>
@endif

@yield('page')

<div id="footer">
    <div class="container">
        <div class="logo"></div>
        <div class="menu">
            <ul>
                <li>{{ config('dofus.title') }}</li>
                <li><a href="{{ URL::route('posts') }}">Actualités</a></li>
                <li><a href="{{ URL::route('register') }}">Télécharger</a></li>
                <li><a href="{{ URL::route('register') }}">Créer un compte</a></li>
                <li><a href="{{ URL::to('password-lost') }}">Mot de passe oublié ?</a></li>
            </ul>
            <ul>
                <li>Serveur</li>
                <li><a href="{{ URL::to('server/list') }}">Infos serveurs</a></li>
                <li><a href="{{ URL::to('events') }}">Évenemtns</a></li>
                <li><a href="{{ URL::to('ladder') }}">Classement</a></li>
                <li><a href="{{ URL::to('gifts') }}">Cadeaux</a></li>
            </ul>
            <!--<ul>
                <li>Tournois</li>
                <li><a href="{{ URL::to('pvp/fights') }}">Combats</a></li>
                <li><a href="{{ URL::to('pvp/champions') }}">Champion</a></li>
                <li><a href="{{ URL::to('pvp/result') }}">Résultats</a></li>
                <li><a href="{{ URL::to('pvp/reward') }}">Récompenses</a></li>
            </ul>-->
            <ul>
                <li>Support</li>
                <li><a href="{{ URL::to('support/help') }}">Aide</a></li>
                <li><a href="http://forum.erezia.net/">Forum</a></li>
                <li><a href="mailto:contact@erezia.net">Contact</a></li>
                <li><a href="{{ URL::to('support/faq') }}">FAQ</a></li>
            </ul>
        </div>
    </div>
    <div class="container copyright">
        <a href="">{{ config('dofus.title') }}</a> &copy; {{ date('Y') }}. Tous droits réservés. <a href="{{ URL::to('legal/cu') }}">Conditions d'utilisation</a> - <a href="{{ URL::to('legal/cgv') }}">Conditions Générales de Vente</a>
    </div>
    <div class="pegi"><img src="{{ URL::asset('imgs/picto_prevention.png') }}" /></div>
</div>

@yield('footer')
</body>
</html>
