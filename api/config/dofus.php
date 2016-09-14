<?php

return [

    'title'        => env('TITLE', 'Dofus'),

    'subtitle'     => env('SUBTITLE', 'Serveur privé'),

    'servers'      => [], // filled in FillServers middleware

    'details'      => [], // filled in FillServers middleware

    'motd'       => [], // filled in FillMotd middleware

    'default_avatar'  => 'imgs/avatar/default.jpg',

    'template'     => env('TEMPLATE', 'dofus'),

    'theme'        => env('THEME', false),

    'background'   => env('BACKGROUND', 'dofus4'),

    'color'        => env('COLOR', '#000'),

    'carousel'     => env('CAROUSEL', false),

    'email'        => env('SUPPORT_EMAIL', 'support@azote.us'),

    'tawk'         => [
        'id'  => env('TAWK_ID', false),
        'api' => env('TAWK_API'),
    ],

    'forum'       => [
        'domain'     => env('FORUM_DOMAIN', ''),
        'user_group' => env('FORUM_USER_GROUP', 3)
    ],

    'forum'       => [

        'domain'     => env('FORUM_DOMAIN', ''),

        'user_group' => env('FORUM_USER_GROUP', 3)

    ],

    'social'       => [
        'forum'    => env('SOCIAL_FORUM'),
        'facebook' => env('SOCIAL_FACEBOOK'),
        'twitter'  => env('SOCIAL_TWITTER'),
        'youtube'  => env('SOCIAL_YOUTUBE'),
    ],

    'vote'         => env('POINTS_BY_VOTES', 10),

    'rpg-paradize' => [
        'id'       => env('RPG_PARADIZE', 0),
        'delay'    => 10810, // 3h + 5s
    ],

    'promos' => [],

    'payment' => [

        'used' => env('PAYMENT', 'dedipass'),

        'dedipass' => [
            'name'       => 'DediPass',
            'url'        => 'https://api.dedipass.com/v1/pay/rates?key={PUBLIC_KEY}',
            'validation' => 'http://api.dedipass.com/v1/pay/?public_key={PUBLIC_KEY}&private_key={PRIVATE_KEY}&code={CODE}',
            'public'     => env('PAYMENT_PUBLIC', 0),
            'private'    => env('PAYMENT_PRIVATE', 0),
        ],

    ],

    'ranks'       => [

        '1'  => 'Player',

        '2'  => 'Moderator',

        '3'  => 'GameMaster_Padawan',

        '4'  => 'GameMaster',

        '5'  => 'Administrator',

    ],

];
