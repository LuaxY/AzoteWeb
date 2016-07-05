<?php

return [

    'title'        => env('TITLE', 'Dofus'),

    'subtitle'     => env('SUBTITLE', 'Serveur privé'),

    'template'     => env('TEMPLATE', 'dofus'),

    'theme'        => env('THEME', false),

    'carousel'     => env('CAROUSEL', false),

    'vote'         => 10,

    'rpg-paradize' => [
        'id'       => env('RPG_PARADIZE', 0),
        'delay'    => 10810, // 3h + 5s
    ],

];
