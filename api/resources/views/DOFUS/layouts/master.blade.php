<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <title>{{ config('dofus.title') }} - {{ config('dofus.subtitle') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <meta name="description" content="{{ config('dofus.title') }} Serveurs Dofus 2.10 & 2.35" />
	<meta name="keywords" content="azote,sigma,epsilon,dofus,server,serveur,kamas,jetons,orbes,gratuit,dofus gratuit,serveur dofus,dofus serveur,jeu,jouer,serveur privé dofus,dofus serveur privé,mmorpg,jeu en ligne" />
    <link rel="alternate" type="application/rss+xml" title="News RSS" href="{{ URL::to('news.rss') }}" />
    {!! Html::style('css/common.css') !!}
    {!! Html::style('css/toastr.min.css') !!}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    {!! Html::script('js/admin/toastr.min.js') !!}
    {!! Html::script('js/common.js') !!}
    @yield('header')
    <style type="text/css">
        body {
            background: url('{{ URL::asset('imgs/carousel/common/'.config('dofus.theme.background').'.jpg') }}')  center top no-repeat;
            background-color: {{ config('dofus.theme.color') }};
        }
    </style>
</head>

<body class="@yield('background')">

    <header>
        <div class="ak-idbar">
            <div class="ak-idbar-content">
                <div class="ak-idbar-left">
                    <div class="ak-brand" data-set="ak-brand">
                        <a class="navbar-brand" href="{{ URL::route('home') }}"></a>
                    </div>
                    <a class="ak-support" href="{{ URL::to('support') }}">Support</a>
                    @if (!Auth::guest() && Auth::user()->isAdmin())
                    <a class="ak-admin" href="{{ URL::route('admin.dashboard') }}">Admin</a>
                    @endif
                </div>
                <div class="ak-idbar-right">
                    @if (Auth::guest())
                    <div class="ak-nav-not-logged">
                        <div class="ak-connect-links">
                            <a href="{{ URL::route('login') }}" class="login ak-modal-trigger">
                                <span>Connexion</span>
                                <img class="img-responsive" src="{{ URL::asset('imgs/avatar/default.jpg') }}" alt="Avatar">
                            </a>
                            <a href="{{ URL::route('register') }}" class="register">Inscription</a>
                        </div>
                    </div>
                    @else
                    <div class="ak-idbar-right">
                        <a class="ak-nav-notifications ak-button-modal">
                            <span class="label label-danger">0</span>
                        </a>
                        <div class="ak-button-modal ak-nav-logged">
                            <a class="ak-logged-account" href="{{ URL::route('profile') }}">
                                <span class="ak-nickname">{{ Auth::user()->pseudo }}</span>
                                <span class="avatar">
                                    <img src="{{ URL::asset(Auth::user()->avatar) }}" alt="Avatar">
                                </span>
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <nav class="navbar navbar-default" data-role="ak_navbar">
            <div class="navbar-container">

                <a class="ak-main-logo" href="{{ URL::route('home') }}"></a>

                <div class="navbar-header">
                    <a class="burger-btn" href="javascript:void(0)"><span></span></a>
                </div>

                <div class="navbar-collapse navbar-ex1-collapse collapse">
                    <ul class="nav navbar-nav">
                        <span class="ak-navbar-left-part">
                            <li class="lvl0 dropdown sep">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">Azote <b class="caret"></b></a>
                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                    <li class="lvl1">
                                        <ul>
                                            <li class="lvl2"><a href="{{ URL::route('posts') }}">Actualités</a></li>
                                            <li class="lvl2"><a href="{{ URL::to('servers') }}">Serveurs</a></li>
                                            <li class="lvl2"><a href="{{ URL::to('ladder') }}">Classement</a></li>
                                            <li class="lvl2"><a href="{{ URL::to('event') }}">Événements</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li class="lvl0 sep"><a href="{{ URL::route('register') }}">Rejoindre</a></li>
                            <li class="lvl0 sep"><a href="{{ URL::route('shop.payment.country') }}">Boutique</a></li>
                        </span>
                        <li class="lvl0 ak-menu-brand">
                            <a class="navbar-brand" href="{{ URL::route('home') }}"></a>
                        </li>
                        <span class="ak-navbar-right-part">
                            <li class="lvl0 sep"><a href="{{ URL::route('vote.index') }}">Vote</a></li>
                            <li class="lvl0 sep"><a href="{{ config('dofus.social.forum') }}" target="_blank">Forum</a></li>
                            <li class="lvl0 sep"><a href="{{ URL::to('support') }}">Support</a></li>
                        </span>
                    </ul>

                </div>
            </div>
        </nav>

        <!-- Keep in order largest -> lowest device resolution -->
        <div class="largedesktop device-profile visible-lg" data-deviceprofile="largedesktop"></div>
        <div class="desktop device-profile visible-md" data-deviceprofile="desktop"></div>
        <div class="tablet device-profile visible-sm" data-deviceprofile="tablet"></div>
        <div class="mobile device-profile visible-xs" data-deviceprofile="mobile"></div>
    </header>

    <div class="ak-beta"></div>

    @yield('page')

    <footer>
        <div class="ak-footer-content">
            <div class="row ak-block1">
                <div class="col-md-9 ak-block-links">
                    <div class="col-md-6 clearfix">
                        <div class="col-xs-6">
                            <div class="ak-list">
                                <div>
                                    <span class="ak-link-title">{{ config('dofus.title') }}</span>
                                </div>
                                <a href="{{ URL::route('posts') }}">Actualités</a>
                                <a href="{{ URL::route('download') }}">Télécharger</a>
                                <a href="{{ URL::route('register') }}">Créer un compte</a>
                                <a href="{{ URL::route('password-lost') }}">Mot de passe oublié ?</a>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="ak-list">
                                <div>
                                    <span class="ak-link-title">Serveur</span>
                                </div>
                                <a href="{{ URL::to('server/list') }}">Infos serveurs</a>
                                <a href="{{ URL::to('events') }}">Évenemtns</a>
                                <a href="{{ URL::to('ladder') }}">Classement</a>
                                <a href="{{ URL::to('gifts') }}">Cadeaux</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 clearfix">
                        <!--<div class="col-xs-6">
                            <div class="ak-list">
                                <div>
                                    <span class="ak-link-title">Tournois</span>
                                </div>
                                <a href="{{ URL::to('pvp/fights') }}">Combats</a>
                                <a href="{{ URL::to('pvp/champions') }}">Champion</a>
                                <a href="{{ URL::to('pvp/result') }}">Résultats</a>
                                <a href="{{ URL::to('pvp/reward') }}">Récompenses</a>
                            </div>
                        </div>-->
                        <div class="col-xs-6">
                            <div class="ak-list">
                                <div>
                                    <span class="ak-link-title">Support</span>
                                </div>
                                <a href="{{ URL::to('support/help') }}">Aide</a>
                                <a href="{{ config('dofus.social.forum') }}">Forum</a>
                                <a href="mailto:{{ config('dofus.email') }}">Contact</a>
                                <a href="{{ URL::to('support/faq') }}">FAQ</a>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-3 ak-block-download">
                    <a href="{{ URL::route('download') }}" class="btn btn-primary btn-lg">Télécharger le jeu</a>
                    <a class="ak-problem" href="{{ URL::to('support') }}">Un problème ? Contactez le support.        </a>
                    <div class="ak-social-network">
                        <a href="{{ config('dofus.social.facebook') }}" class="fb" target="_blank"></a>
                        <a href="{{ config('dofus.social.twitter') }}" class="tw" target="_blank"></a>
                        <a href="{{ config('dofus.social.youtube') }}" class="yo" target="_blank"></a>
                    </div>
                </div>
            </div>
            <div class="row ak_legal">
                <div id="col-md-12">
                    <div class="ak-legal">
                        <div class="row">
                            <div class="col-sm-1">
                                <a href="" class="ak-logo-azote"></a>
                            </div>
                            <div class="col-sm-8">
                                <p>&copy; {{ date('Y') }} <a href="">{{ config('dofus.title') }}</a>. Tous droits réservés. <a href="{{ URL::to('cgu') }}" target="_blank">Conditions d'utilisation</a> - <a href="{{ URL::to('privacy') }}"target="_blank">Politique de confidentialité</a> - <a href="{{ URL::to('cgv') }}" target="_blank">Conditions Générales de Vente</a> - <a href="{{ URL::to('legal') }}" target="_blank">Mentions Légales</a></p>
                            </div>
                            <div class="col-sm-3"><span class="prevention"></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    @if(Session::has('notify'))
    {{ Toastr::add(Session::get('notify')['type'], str_replace("'", "\\'", Session::get('notify')['message'])) }}
    {!! Toastr::render() !!}
    @endif

    @if (!Auth::guest() && !Auth::user()->isStaff() && config('dofus.tawk.id'))
        <script type="text/javascript">
            var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
            (function(){
                var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
                s1.async=true;
                s1.src='https://embed.tawk.to/{{ config('dofus.tawk.id') }}/default';
                s1.charset='UTF-8';
                s1.setAttribute('crossorigin','*');
                s0.parentNode.insertBefore(s1,s0);
                Tawk_API = Tawk_API || {};
                Tawk_API.visitor = {
                	name  : '{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}',
                	email : '{{ Auth::user()->email }}',
                	hash  : '{{ hash_hmac('sha256', Auth::user()->email, config('dofus.tawk.api') ) }}'
                };
            })();
        </script>
        @yield('scriptlogged')
    @endif
</body>
</html>
