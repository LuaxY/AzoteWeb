<?php

return [

    'title'        => env('TITLE', 'Dofus'),

    'subtitle'     => env('SUBTITLE', 'Serveur privé'),

    'servers'      => [], // filled in FillServers middleware

    'details'      => [], // filled in FillServers middleware

    'motd'         => [], // filled in FillSettings middleware

    'theme'        => [ // modified in FillSettings middleware
        'background' => 'kolizeum',
        'color'      => '#3d301e'
    ],

    'default_avatar' => 'imgs/avatar/default.jpg',

    'accounts_limit' => 8,

    'email'        => env('SUPPORT_EMAIL', 'support@azote.us'),

    'api_key'      => env('API_KEY'),

    'download'     => [
        'mac' => 'http://dl.azote.us/Azote_Setup.dmg',
        'win' => 'http://dl.azote.us/Azote_Setup.exe',
    ],

    'certify'     => [
        'min_age'  => '10',
        'max_age'  => '100',
        'article'  => 'http://forum.azote.us/topic/26-compte-web-certifi%C3%A9-quest-ce/'
    ],

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
