<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Routes Language Lines
    |--------------------------------------------------------------------------
    */

    /** News **/

    'posts.index' => 'nouveautes',

    'posts.show' => 'article/{id?}/{slug?}',

    /** Account **/

    'account.register' => 'inscription',

    'account.dashboard' => 'profile',

    'account.login' => 'compte/connexion',

    'account.logout' => 'compte/deconnexion',

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

    /** Events **/

    'event.st-patrick' => 'event/st-patrick',

);
