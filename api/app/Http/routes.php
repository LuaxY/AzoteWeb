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

    /* ============ ACCOUNT ============ */

    Route::get(Lang::get('routes.account.register'), [
		'middleware' => 'guest',
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
		'uses'   => 'VoteController@index',
		'as'     => 'vote.index'
	]);

	Route::get(Lang::get('routes.vote.process'), [
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

});

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
