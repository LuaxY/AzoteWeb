@section('menu')
@if (Auth::guest())
    <div class="panel blue">
        <div class="panel-title">
            <span class="icon-medplus icon-pets"></span>Connexion
        </div>
        <div class="panel-content login">
            {!! Form::open(['route' => 'login']) !!}
            @if($errors->has('auth')) <span class="input-error" style="font-weight: 400; font-size: 12px;">{{ $errors->first('auth') }}</span> @endif
            <div class="form-group">
                <label for="username">Nom de compte</label>
                <input id="username" type="text" autocorrect="off" autocapitalize="off" placeholder="Nom de compte" name="username" value="{{ Input::old('username') }}" @if ($errors->has('auth')) class="has-error" @endif />
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input id="password" type="password" placeholder="Mot de passe" name="password" @if ($errors->has('auth')) class="has-error" @endif />
            </div>
            <div class="block-submit">
                <input id="login" class="btn-medium" type="submit" value="Connexion" />
                <div class="menu-lost-login">
                    <a href="{{ URL::route('register') }}">S'inscrire</a><br />
                    <a href="{{ URL::to('password/reset') }}">Mot de passe oublié</a>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@else
    <div class="panel blue">
        <div class="panel-title">
            <span class="icon-medplus icon-pets"></span>Mon compte
        </div>
        <div class="panel-content account">
            <div class="account-avatar"><img src="{{ URL::asset(Auth::user()->Avatar) }}" /></div>
            <div class="account-details">
                <div class="account-name">{{ Auth::user()->Nickname }}</div>
                <div class="account-info">
                    <a href="{{ URL::route('dashboard') }}">Gestion de compte</a>
                    <div style="margin-top: 5px;">
                        Ogrines: {{ Utils::format_price(Auth::user()->Tokens) }}<span class="icon-small icon-ogrines"></span><br />
                        <a href="{{ URL::route('shop.payment.country') }}"><i>Acheter des ogrines</i></a>
                    </div>
                </div>
            </div>
            <div class="logout">
                <a href="{{ URL::route('logout') }}">Déconnexion</a>
            </div>
        </div>
    </div>
@endif
    <a class="btn-aside btn-vote" href="{{ URL::route('vote.index') }}">
        <em>Besoin d'ogrines ?</em>
        <br>
        Vote pour le serveur !
    </a>
    <a class="btn-aside btn-shop" href="{{ URL::route('shop.payment.country') }}">
        <em>Envie de cadeaux ?</em>
        <br>
        Achete des ogrines
    </a>

    <div class="panel">
        <div class="panel-title">
            <span class="icon-med icon-ladder-success"></span>Événements
        </div>
        <div class="panel-content">
            <div class="level-1">
                <a href="#" class="title">Tournois</a>
            </div>
            <ul class="level-2">
                <li><img src="{{ URL::asset('imgs/icons/tournois.png') }}"> Combats</li>
                <li><img src="{{ URL::asset('imgs/icons/champions.png') }}"> Champions</li>
                <li><img src="{{ URL::asset('imgs/icons/kamas.png') }}"> Résultats</li>
            </ul>
            <div class="level-1">
                <a href="#" class="title">Divers</a>
            </div>
            <ul class="level-2">
                <li><img src="{{ URL::asset('imgs/icons/alliance.png') }}"> Alliances versus Alliances</li>
                <li><img src="{{ URL::asset('imgs/icons/fee.png') }}"> Nowel</li>
            </ul>
        </div>
    </div>

    <div class="panel">
        <div class="panel-title">
            <span class="icon-med icon-shop"></span>Boutique
        </div>
        <div class="panel-content">
            <div class="level-1">
                <a href="#" class="title">Objets</a>
            </div>
            <ul class="level-2">
                <li>Object 1</li>
                <li>Object 2</li>
            </ul>
            <div class="level-1">
                <a href="#" class="title">Booster</a>
            </div>
            <ul class="level-2">
                <li>Starter Pack</li>
                <li>Pro Pack</li>
            </ul>
        </div>
    </div>
@stop
