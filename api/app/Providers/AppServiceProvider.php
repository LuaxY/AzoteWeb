<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

use Validator;
use App\User;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('old_password', function ($attribute, $value, $parameters, $validator) {
            $password = $parameters[0];
            $salt     = $parameters[1];
            return $password === User::hashPassword($value, $salt);
        });

        Validator::extend('old_passwordStump', function ($attribute, $value, $parameters, $validator) {
            $password = $parameters[0];
            return $password === md5($value);
        });

        Blade::extend(function ($value) {
            return preg_replace('/\{\?(.+)\?\}/', '<?php ${1} ?>', $value);
        });

        Carbon::setlocale(config('app.locale'));
        setlocale(LC_TIME, config('app.locale'));
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
