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
        'details' => 'Le compte sélectionné n\'est pas valide.',
    ],

    'owner_error' => [
        'status'  => 401,
        'title'   => 'Le compte ou personnage sélectionné est invalide',
        'details' => 'Le compte ou le personnage sélectionné n\'est pas valide.',
    ],

];
