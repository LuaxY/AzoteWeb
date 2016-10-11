<?php

$locale = Request::segment(1);

if (in_array($locale, Config::get('app.locales')))
{
    App::setLocale($locale);
}
else
{
    $locale = null;
}

Route::group(['prefix' => $locale], function() {

    Route::any('/', [
        'uses' => 'PostController@index',
        'as'   => 'home'
    ]);

    /* ============ NEWS ============ */

    Route::get(Lang::get('routes.posts.index'), [
        'uses' => 'PostController@index',
        'as'   => 'posts'
    ]);
    Route::get(Lang::get('routes.posts.show'), [
        'uses' => 'PostController@show',
        'as'   => 'posts.show'
    ]);
    Route::post(Lang::get('routes.posts.comment.store'), [
        'uses' => 'PostController@commentStore',
        'as'   => 'posts.comment.store'
    ]);
    Route::delete(Lang::get('routes.posts.comment.destroy'), [
        'uses' => 'PostController@commentDestroy',
        'as'   => 'posts.comment.destroy'
    ]);

    /* ============ ACCOUNT ============ */

    Route::get(Lang::get('routes.account.register'), [
        'uses'       => 'AccountController@register',
        'as'         => 'register'
    ]);

    Route::post(Lang::get('routes.account.register'), [
        'middleware' => 'guest',
        'uses'       => 'AccountController@store',
        'as'         => 'register'
    ]);

    Route::get(Lang::get('routes.account.profile'), [
        'middleware' => 'auth',
        'uses'       => 'AccountController@profile',
        'as'         => 'profile'
    ]);

    Route::get(Lang::get('routes.account.activation'), [
        'middleware' => 'guest',
        'uses'       => 'AccountController@activation',
        'as'         => 'activation'
    ]);

    Route::post(Lang::get('routes.account.re_send_email'), [
        'uses'       => 'AccountController@re_send_email',
        'as'         => 're-send-email'
    ]);

    Route::get(Lang::get('routes.account.password_lost'), [
        'middleware' => 'guest',
        'uses'       => 'AccountController@password_lost',
        'as'         => 'password-lost'
    ]);

    Route::post(Lang::get('routes.account.password_lost'), [
        'middleware' => 'guest',
        'uses'       => 'AccountController@passord_lost_email',
        'as'         => 'password-lost'
    ]);

    Route::get(Lang::get('routes.account.reset'), [
        'uses'       => 'AccountController@reset_form',
        'as'         => 'reset'
    ]);

    Route::post(Lang::get('routes.account.reset'), [
        'middleware' => 'auth',
        'uses'       => 'AccountController@reset_password',
        'as'         => 'reset'
    ]);

    Route::any(Lang::get('routes.account.change_email'), [
        'middleware' => 'auth',
        'uses'       => 'AccountController@change_email',
        'as'         => 'account.change_email'
    ]);

    Route::any(Lang::get('routes.account.change_password'), [
        'middleware' => 'auth',
        'uses'       => 'AccountController@change_password',
        'as'         => 'account.change_password'
    ]);

    Route::any(Lang::get('routes.account.change_profile'), [
        'middleware' => 'auth',
        'uses'       => 'AccountController@change_profile',
        'as'         => 'account.change_profile'
    ]);

    Route::any(Lang::get('routes.account.certify'), [
        'middleware' => 'auth',
        'uses'       => 'AccountController@certify',
        'as'         => 'account.certify'
    ]);

    /* ============ GAME ACCOUNT ============ */

    Route::get(Lang::get('routes.gameaccount.create'), [
        'middleware' => 'auth',
        'uses'       => 'GameAccountController@create',
        'as'         => 'gameaccount.create'
    ]);

    Route::post(Lang::get('routes.gameaccount.create'), [
        'middleware' => 'auth',
        'uses'       => 'GameAccountController@store',
        'as'         => 'gameaccount.create'
    ]);

    Route::get(Lang::get('routes.gameaccount.view'), [
        'middleware' => 'auth',
        'uses'       => 'GameAccountController@view',
        'as'         => 'gameaccount.view'
    ]);

    Route::any(Lang::get('routes.gameaccount.edit'), [
        'middleware' => 'auth',
        'uses'       => 'GameAccountController@edit',
        'as'         => 'gameaccount.edit'
    ]);

    Route::any(Lang::get('routes.gameaccount.transfert'), [
        'middleware' => 'auth',
        'uses'       => 'GameAccountController@transfert',
        'as'         => 'gameaccount.transfert'
    ]);

    Route::any(Lang::get('routes.gameaccount.gifts'), [
        'middleware' => 'auth',
        'uses'       => 'GameAccountController@gifts',
        'as'         => 'gameaccount.gifts'
    ]);

    /* ============ CHARACTERS ============ */

    Route::get(Lang::get('routes.characters.view'), [
        'middleware' => 'auth',
        'uses'       => 'CharactersController@view',
        'as'         => 'characters.view'
    ]);

    /* ============ AUTH ============ */

    Route::get(Lang::get('routes.account.login'), [
        'middleware' => 'guest',
        'uses'       => 'AuthController@login',
        'as'         => 'login'
    ]);

    Route::post(Lang::get('routes.account.login'), [
        'middleware' => 'guest',
        'uses'       => 'AuthController@auth',
        'as'         => 'login'
    ]);

    Route::get(Lang::get('routes.account.logout'), [
        'middleware' => 'auth',
        'uses'       => 'AuthController@logout',
        'as'         => 'logout'
    ]);

    /* ============ SHOP ============ */

    Route::get(Lang::get('routes.shop.payment.choose-country'), [
        'middleware' => 'auth',
        'uses'       => 'PaymentController@country',
        'as'         => 'shop.payment.country'
    ]);

    Route::get(Lang::get('routes.shop.payment.choose-method'), [
        'middleware' => 'auth',
        'uses'       => 'PaymentController@method',
        'as'         => 'shop.payment.method'
    ]);

    Route::any(Lang::get('routes.shop.payment.get-code'), [
        'middleware' => 'auth',
        'uses'       => 'PaymentController@code',
        'as'         => 'shop.payment.code'
    ]);

    Route::post(Lang::get('routes.shop.payment.process'), [
        'middleware' => 'auth',
        'uses'       => 'PaymentController@process',
        'as'         => 'shop.payment.process'
    ]);

    /* ============ VOTE ============ */

    Route::get(Lang::get('routes.vote.index'), [
        'middleware' => 'auth',
        'uses'       => 'VoteController@index',
        'as'         => 'vote.index'
    ]);

    Route::get(Lang::get('routes.vote.confirm'), [
        'middleware' => 'auth',
        'uses'       => 'VoteController@confirm',
        'as'         => 'vote.confirm'
    ]);

    Route::post(Lang::get('routes.vote.process'), [
        'middleware' => 'auth',
        'uses'       => 'VoteController@process',
        'as'         => 'vote.process'
    ]);

    Route::get(Lang::get('routes.vote.palier'), [
        'middleware' => 'auth',
        'uses'       => 'VoteController@palier',
        'as'         => 'vote.palier'
    ]);

    Route::get(Lang::get('routes.vote.object'), [
        'middleware' => 'auth',
        'uses'       => 'VoteController@object',
        'as'         => 'vote.object'
    ]);

    /* ============ LADDER ============ */

    Route::get(Lang::get('routes.ladder.general'), [
        'uses' => 'LadderController@general',
        'as'   => 'ladder.general'
    ]);

    Route::get(Lang::get('routes.ladder.pvp'), [
        'uses' => 'LadderController@pvp',
        'as'   => 'ladder.pvp'
    ]);

    Route::get(Lang::get('routes.ladder.guild'), [
        'uses' => 'LadderController@guild',
        'as'   => 'ladder.guild'
    ]);

    /* ============ OTHERS ============ */

    Route::get(Lang::get('routes.download'), [
        'uses' => 'PageController@download',
        'as'   => 'download'
    ]);

    Route::get(Lang::get('routes.servers'), [
        'uses' => 'PageController@servers',
        'as'   => 'servers'
    ]);

});

Route::get('support', function () {
    return view('support/temp');
});

Route::get('news.rss', [ 'uses' => 'RssController@news' ]);

/* ============ API ============ */

Route::group(['prefix' => 'api'], function()
{
    /*Route::group(['prefix' => 'account'], function()
    {
        Route::post('register', 'Api\AccountController@register');

        Route::post('login', 'Api\AccountController@login');

        Route::get('profile', [
            'middleware' => 'auth.api',
            'uses'       => 'Api\AccountController@profile',
        ]);

        Route::post('update', [
            'middleware' => 'auth.api',
            'uses'       => 'Api\AccountController@update',
        ]);

        Route::group(['prefix' => 'game'], function()
        {
            Route::post('create', [
                'middleware' => 'auth.api',
                'uses'       => 'Api\GameAccountController@create',
            ]);

            Route::post('update', [
                'middleware' => 'auth.api',
                'uses'       => 'Api\GameAccountController@update',
            ]);

            Route::get('characters/{accountId}', [
                'middleware' => 'auth.api',
                'uses'       => 'Api\GameAccountController@characters',
            ])->where('accountId', '[0-9]+');
        });
    });*/

    Route::group(['prefix' => 'support'], function()
    {
        Route::get('create', 'SupportController@create');

        Route::get('child/{child}/{params?}', [
            'middleware' => 'auth.api',
            'uses'       => 'SupportController@child',
        ]);

        Route::post('store', 'SupportController@store');
    });
});

/* ============ FORGE ============ */

Route::group(['prefix' => 'forge'], function()
{
    Route::get('image/{request}', 'Api\ForgeController@image')->where('request', '(.*)');

    Route::get('player/{id}/{mode}/{orientation}/{sizeX}/{sizeY}', 'Api\ForgeController@player')->where([
        'id'          => '[0-9]+',
        'mode'        => '(full|face)',
        'orientation' => '[0-8]',
        'sizeX'       => '[0-9]+',
        'sizeY'       => '[0-9]+'
    ]);

    Route::get('text/{id}', 'Api\ForgeController@text')->where('id', '[0-9]+');
});

/* ============ ADMIN PANEL ============ */

Route::group(['middleware' => ['auth', 'admin']], function() {

    Route::controller('filemanager', 'FilemanagerLaravelController');

    /* ============ ADMIN PREFIX ============ */
    Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function() {

        Route::any('/', [
            'uses' => 'AdminController@index',
            'as'   => 'admin.dashboard'
        ]);

        // ACCOUNT //
        Route::group(['prefix' => 'account'], function() {

            Route::get('/', [
                'uses' => 'AccountController@index',
                'as'   => 'admin.account'
            ]);

            Route::patch('/update', [
                'uses' => 'AccountController@accountUpdate',
                'as'   => 'admin.account.update'
            ]);

            route::patch('/avatar/reset', [
                'uses' => 'AccountController@resetAvatar',
                'as'   => 'admin.account.avatar.reset'
            ]);

            Route::get('/password', [
                'uses' => 'AccountController@password',
                'as'   => 'admin.password'
            ]);

            Route::patch('/password/update', [
                'uses' => 'AccountController@passwordUpdate',
                'as'   => 'admin.password.update'
            ]);
        });

        // POSTS //
        Route::controller('/posts/data', 'PostDatatablesController', [
            'anyData'  => 'datatables.postdata'
        ]);
        Route::resource('post', 'PostController', ['names' => [
            'index'   => 'admin.posts', // GET Index
            'create'  => 'admin.post.create', // GET Create
            'store'   => 'admin.post.store', // POST Store (create POST)
            'destroy' => 'admin.post.destroy', // DELETE
            'edit'    => 'admin.post.edit', // GET Edit (view) /post/ID/edit
            'update'  => 'admin.post.update' // PUT OU PATCH for update the edit
        ]]);

        // TASKS //
        Route::patch('/task/updatePositions', [
            'uses' => 'TaskController@updatePositions',
            'as'   => 'admin.task.update.positions'
        ]);
        Route::patch('/task/updateModal', [
            'uses' => 'TaskController@updateModal',
            'as'   => 'admin.task.update.modal'
        ]);
        Route::resource('task', 'TaskController', ['names' => [
            'index'   => 'admin.tasks', // GET Index
            'create'  => 'admin.task.create', // GET Create
            'store'   => 'admin.task.store', // POST Store (create TASK)
            'destroy' => 'admin.task.destroy', // DELETE
            'edit'    => 'admin.task.edit', // GET Edit (view) /task/ID/edit
            'update'  => 'admin.task.update' // PUT OU PATCH for update the edit
        ]]);

        // USERS //
        Route::controller('/users/data', 'UserDatatablesController', [
            'anyData'  => 'datatables.userdata'
        ]);

        Route::group(['prefix' => 'user/{user}'], function() {

            // Users actions
            Route::patch('ban', [
                'uses' => 'UserController@ban',
                'as'   => 'admin.user.ban'
            ])->where('user', '[0-9]+');

            Route::patch('unban', [
                'uses' => 'UserController@unban',
                'as'   => 'admin.user.unban'
            ])->where('user', '[0-9]+');

            Route::patch('activate', [
                'uses' => 'UserController@activate',
                'as'   => 'admin.user.activate'
            ])->where('user', '[0-9]+');

            Route::patch('decertify', [
                'uses' => 'UserController@decertify',
                'as'   => 'admin.user.decertify'
            ])->where('user', '[0-9]+');

            Route::patch('certify', [
                'uses' => 'UserController@certify',
                'as'   => 'admin.user.certify'
            ])->where('user', '[0-9]+');

            Route::patch('password', [
                'uses' => 'UserController@password',
                'as'   => 'admin.user.password'
            ])->where('user', '[0-9]+');

            Route::patch('avatar/reset', [
                'uses' => 'UserController@resetAvatar',
                'as'   => 'admin.user.reset.avatar'
            ])->where('user', '[0-9]+');

            Route::patch('avatar/reset', [
                'uses' => 'UserController@resetAvatar',
                'as'   => 'admin.user.reset.avatar'
            ])->where('user', '[0-9]+');


            // Game Accounts
            Route::group(['prefix' => 'server/{server}'], function() {

                // Index
                Route::get('/', [
                    'uses' => 'GameAccountController@index',
                    'as'   => 'admin.user.game.accounts'
                ])->where('user','[0-9]+');
                // Store
                Route::post('/store', [
                    'uses' => 'GameAccountController@store',
                    'as'   => 'admin.user.game.account.store'
                ])->where('user','[0-9]+');
                // Edit (view)
                Route::get('/{id}/edit', [
                    'uses' => 'GameAccountController@edit',
                    'as'   => 'admin.user.game.account.edit'
                ])->where('user','[0-9]+')->where('id', '[0-9]+');
                // Ban
                Route::patch('/{id}/ban', [
                    'uses' => 'GameAccountController@ban',
                    'as'   => 'admin.user.game.account.ban'
                ])->where('user','[0-9]+')->where('id', '[0-9]+');
                // Unban
                Route::patch('/{id}/unban', [
                    'uses' => 'GameAccountController@unban',
                    'as'   => 'admin.user.game.account.unban'
                ])->where('user','[0-9]+')->where('id', '[0-9]+');
                // Jail
                Route::patch('/{id}/jail', [
                    'uses' => 'GameAccountController@jail',
                    'as'   => 'admin.user.game.account.jail'
                ])->where('user','[0-9]+')->where('id', '[0-9]+');
                // Unjail
                Route::patch('/{id}/unjail', [
                    'uses' => 'GameAccountController@unjail',
                    'as'   => 'admin.user.game.account.unjail'
                ])->where('user','[0-9]+')->where('id', '[0-9]+');
                // Unjail
                Route::patch('/{id}/password', [
                    'uses' => 'GameAccountController@password',
                    'as'   => 'admin.user.game.account.password'
                ])->where('user','[0-9]+')->where('id', '[0-9]+');
                // Update
                Route::patch('/{id}', [
                    'uses' => 'GameAccountController@update',
                    'as'   => 'admin.user.game.account.update'
                ])->where('user','[0-9]+')->where('id', '[0-9]+');
            });
        });

        Route::resource('user', 'UserController', ['names' => [
            'index'   => 'admin.users', // GET Index
            'create'  => 'admin.user.create', // GET Create
            'store'   => 'admin.user.store', // POST Store (create TASK)
            'destroy' => 'admin.user.destroy', // DELETE
            'edit'    => 'admin.user.edit', // GET Edit (view) /user/ID/edit
            'update'  => 'admin.user.update' // PUT OU PATCH for update the edit
        ]]);

        // CHARACTERS //
        Route::controller('/characters/data', 'CharacterDatatablesController', [
            'anyData'  => 'datatables.characterdata'
        ]);

        Route::get('characters', [
            'uses' => 'CharacterController@index',
            'as'   => 'admin.characters'
        ]);

        // SETTINGS //
        Route::get('settings', [
            'uses' => 'SettingsController@index',
            'as'   => 'admin.settings'
        ]);
        Route::patch('settings/update', [
            'uses' => 'SettingsController@update',
            'as'   => 'admin.settings.update'
        ]);
    });
});
