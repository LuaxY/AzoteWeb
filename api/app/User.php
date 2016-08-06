<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Security;
use App\ModelCustom;

class User extends Authenticatable
{
    use HasRoles; // CanResetPassword

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
            'firstname'            => 'required|min:3|max:32|alpha_dash',
            'lastname'             => 'required|min:3|max:32|alpha_dash',
            'g-recaptcha-response' => 'required|recaptcha',
            'cgu'                  => 'required',
        ],
        'update-name' => [
            'firstname' => 'required|min:3|max:32|alpha_dash',
            'lastname'  => 'required|min:3|max:32|alpha_dash',
        ],
        'update-password' => [
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

    public function accounts()
    {
        return ModelCustom::hasManyOnManyServers('auth', Account::class, 'Email', $this->email);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class)->orderBy('created_at', 'desc')->get();
    }

    public function isAdmin()
    {
        if ($this->rank >= 4)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function isStaff()
    {
        if ($this->rank > 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
