<style>
* {
    font-family: "Helvetica", "Arial", sans-serif;
}
</style>
<h3>Bonjour {{ $user->firstname }} {{ $user->lastname }} !</h3>
<p>Vous venez de demander une réinitialisation de votre mot de passe Azote.us.<p>
<p>Si vous êtes bien l'auteur de cette demande, il reste une dernière étape pour valider le changement. Cliquez sur le lien ci-dessous:</p>
<br>
<a href="{{ route('reset', $user->ticket) }}">Cliquez-ici pour changer de mot de passe</a>
<br><br>
<p>Si vous n'avez pas fait cette demande, ignorez cet email et n'hésitez à prévenir le support.</p>
<p><b>Azote.us</b></p>
