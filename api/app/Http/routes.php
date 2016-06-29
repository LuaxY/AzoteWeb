<?php

Route::get('/', [
    'uses' => 'PostController@index',
    'as'   => 'home'
]);

/* ============ TMP ============ */

Route::any('vote', [ 'as' => 'vote.index' ]);
Route::any('shop', [ 'as' => 'shop.payment.country' ]);
Route::any('posts', [ 'as' => 'posts' ]);
Route::any('posts/show', [ 'as' => 'posts.show' ]);

/* ============ AUTH ============ */

Route::get('login', [
    'middleware' => 'guest',
    'uses' => 'AuthController@login',
    'as'   => 'login'
]);

Route::post('login', [
    'middleware' => 'guest',
    'uses' => 'AuthController@auth',
    'as'   => 'login'
]);

Route::get('logout', [
    'middleware' => 'auth',
    'uses' => 'AuthController@logout',
    'as'   => 'logout'
]);

/* ============ ACCOUNT ============ */

Route::group(['prefix' => 'account'], function()
{
    Route::get('register', [
        'middleware' => 'guest',
        'uses' => 'AccountController@register',
        'as'   => 'register'
    ]);

    Route::post('register', [
        'middleware' => 'guest',
        'uses' => 'AccountController@store',
        'as'   => 'register'
    ]);

    Route::get('profile', [
        'middleware' => 'auth',
        'uses' => 'AccountController@profile',
        'as'   => 'profile'
    ]);
});

/* ============ API ============ */

Route::group(['prefix' => 'api'], function()
{
    Route::group(['prefix' => 'account'], function()
    {
        Route::post('register', 'Api\AccountController@register');

        Route::post('login', 'Api\AccountController@login');

        Route::get('profile', [
            'middleware' => 'auth.api',
            'uses' => 'Api\AccountController@profile',
        ]);

        Route::post('update', [
            'middleware' => 'auth.api',
            'uses' => 'Api\AccountController@update',
        ]);

        Route::group(['prefix' => 'game'], function()
        {
            Route::post('create', [
                'middleware' => 'auth.api',
                'uses' => 'Api\GameAccountController@create',
            ]);

            Route::post('update', [
                'middleware' => 'auth.api',
                'uses' => 'Api\GameAccountController@update',
            ]);

            Route::get('characters/{accountId}', [
                'middleware' => 'auth.api',
                'uses' => 'Api\GameAccountController@characters',
            ])->where('accountId', '[0-9]+');
        });
    });

    Route::group(['prefix' => 'support'], function()
    {
        Route::get('create', 'SupportController@create');

        Route::get('child/{child}/{params?}', [
            'middleware' => 'auth.api',
            'uses' => 'SupportController@child',
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
