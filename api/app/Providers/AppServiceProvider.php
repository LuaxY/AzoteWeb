<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Validator;
use App\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('old_password', function($attribute, $value, $parameters, $validator) {
            $password = $parameters[0];
            $salt     = $parameters[1];
            return $password === User::hashPassword($value, $salt);
        });

        Validator::extend('old_passwordStump', function($attribute, $value, $parameters, $validator) {
            $password = $parameters[0];
            return $password === md5($value);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
