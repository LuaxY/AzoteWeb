@extends('layouts.emails.template')

@section('content')
<b>Bonjour {{ $user->firstname }} {{ $user->lastname }} !</b>
<br><br>
Vous venez de demander un changement d'adresse email {{ config('dofus.title') }}.
<br><br>
Si vous êtes bien l'auteur de cette demande, vous pouvez ignorer cet email, dans le cas contraire merci de prendre contact avec le support. Cliquez sur l'un lien ci-dessous:
<br><br>
<a style="color:#00bcc5;font-weight:bold;text-decoration:none;" href="{{ URL::to('support') }}">Cliquez-ici pour contacter le support</a>
@stop
