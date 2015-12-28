<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

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
}
