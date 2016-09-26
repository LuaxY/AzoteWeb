<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Routes Language Lines
    |--------------------------------------------------------------------------
    */

    /** News **/

    'posts.index' => 'nouveautes',

    'posts.show' => 'article/{id}/{slug?}',

    'posts.comment.store' => 'article/{id}/{slug?}/commentaire',
    
    /** Account **/

    'account.register' => 'inscription',

    'account.profile' => 'compte/profil',

    'account.login' => 'compte/connexion',

    'account.logout' => 'compte/deconnexion',

    'account.activation' => 'compte/activation/{ticket?}',

    'account.password_lost' => 'compte/mot-de-passe-oublie',

    'account.reset' => 'compte/reset/{ticket?}',

    'account.change_email' => 'compte/changement-email',

    'account.change_password' => 'compte/changement-mot-de-passe',

    'account.change_profile' => 'compte/editer-profil',

    'account.certify' => 'compte/certification',

    /** Game Account **/

    'gameaccount.create' => 'compte/jeu/creation',

    'gameaccount.view' => 'compte/jeu/{server}/{accountId}',

    'gameaccount.edit' => 'compte/jeu/{server}/{accountId}/modifier',

    'gameaccount.transfert' => 'compte/jeu/{server}/{accountId}/transfert',

    /** Characters **/

    'characters.view' => 'personnages/{server}/{characterId}',

    /** Shop **/

    'shop.payment.choose-country' => 'boutique/paiement/choix-pays',

    'shop.payment.choose-method' => 'boutique/paiement/{country?}/choix-mode-paiement',

    'shop.payment.get-code' => 'boutique/paiement/obtention-code',

    'shop.payment.process' => 'boutique/paiement/process',

    /** Vote **/

    'vote.index' => 'vote',

    'vote.process' => 'vote/process',

    'vote.palier' => 'vote/palier/{id?}',

    'vote.object' => 'vote/objet/{item?}',

    /** Others **/

    'download' => 'telecharger',

    'ladder' => 'classement',

    /** Events **/

    'event.st-patrick' => 'event/st-patrick',

];
