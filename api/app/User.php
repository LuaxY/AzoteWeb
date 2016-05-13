<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Security;

class User extends Authenticatable
{
    protected $fillable = [
        'email',
        'password',
        'salt',
        'lang',
        'rank',
        'firstname',
        'lastname',
        'last_ip_address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static $rules = [
        'register' => [
            'email'                => 'required|email|unique:users,email',
            'password'             => 'required|min:6',
            'passwordConfirmation' => 'required|same:password',
            'firstName'            => 'required|min:3|max:32|alpha_dash',
            'lastName'             => 'required|min:3|max:32|alpha_dash',
            //'captchaResponse'      => 'required|recaptcha',
        ],
        'update1' => [
            'firstname' => 'required|min:3|max:32|alpha_dash',
            'lastname'  => 'required|min:3|max:32|alpha_dash',
        ],
        'update2' => [
            'password'             => 'required|min:6',
            'passwordConfirmation' => 'required|same:password',
        ],
    ];

    public function hashPassword($password, $salt)
    {
        $password = Security::hash('sha512', $password, 10);
        $salt     = Security::hash('sha512', $salt, 10);
        $hash     = Security::hash('sha512', $password . $salt, 10);

        return $hash;
    }
}
