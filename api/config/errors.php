<?php

return [

    'invalid_server' => [
        'status'  => 404,
        'title'   => 'Le serveur sélectionné est invalide',
        'details' => 'Le serveur "%s" ne fait pas partie des serveurs disponible.',
    ],

    'not_account_owner' => [
        'status'  => 401,
        'title'   => 'Le compte sélectionné est invalide',
        'details' => 'Le compte sélectionné ne vous appartient pas, vous ne pouvez donc pas le consulter.',
    ],

];
