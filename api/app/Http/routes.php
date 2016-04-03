<?php

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'account'], function()
{
    Route::post('register', 'AccountController@register');

    Route::post('login', 'AccountController@login');

    Route::get('profile', [
    	'middleware' => 'auth',
    	'uses' => 'AccountController@profile',
    ]);

    Route::post('update', [
    	'middleware' => 'auth',
    	'uses' => 'AccountController@update',
    ]);

    Route::group(['prefix' => 'game'], function()
    {
        Route::post('create', [
            'middleware' => 'auth',
            'uses' => 'GameAccountController@create',
        ]);

        Route::post('update', [
            'middleware' => 'auth',
            'uses' => 'GameAccountController@update',
        ]);

        Route::get('characters/{accountId}', [
        	'middleware' => 'auth',
        	'uses' => 'GameAccountController@characters',
        ])->where('accountId', '[0-9]+');
    });
});

Route::group(['prefix' => 'support'], function()
{
    Route::get('create', 'SupportController@create');

    Route::get('child/{child}', 'SupportController@child');

    Route::post('store', 'SupportController@store');
});
