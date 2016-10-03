<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

use \Cache;

use App\Security;
use App\ModelCustom;
use App\ForumAccount;
use App\Vote;

class User extends Authenticatable
{
    use HasRoles; // CanResetPassword

    protected $dates = ['birthday', 'last_vote'];

    protected $fillable = [
        'email',
        'password',
        'salt',
        'lang',
        'rank',
        'firstname',
        'lastname',
        'last_ip_address',
        'active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'ticket',
    ];

    public static $rules = [
        'register' => [
            'pseudo'               => 'required|min:3|max:32|alpha_dash|unique:users,pseudo',
            'email'                => 'required|email|not_throw_away|unique:users,email',
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
            'passwordOld'          => 'required|old_password:{PASSWORD},{SALT}',
            'password'             => 'required|min:6',
            'passwordConfirmation' => 'required|same:password',
        ],
        'update-email' => [
            'passwordOld' => 'required|old_password:{PASSWORD},{SALT}',
            'email'       => 'required|email|unique:users,email',
        ],
        'update-profile' => [
            'firstname' => 'required|min:3|max:32|alpha_dash',
            'lastname'  => 'required|min:3|max:32|alpha_dash',
        ],
        'certify' => [
            'firstname' => 'required|min:3|max:32|alpha_dash',
            'lastname'  => 'required|min:3|max:32|alpha_dash',
            'birthday'  => 'required|date_format:Y-m-d'
        ],
        'admin-store' => [
            'pseudo'               => 'required|min:3|max:32|alpha_dash|unique:users,pseudo',
            'email'                => 'required|email|unique:users,email',
            'password'             => 'required|min:6',
            'passwordConfirmation' => 'required|same:password',
            'firstname'            => 'required|min:3|max:32|alpha_dash',
            'lastname'             => 'required|min:3|max:32|alpha_dash',
            'rank'                 => 'required|in:0,4',
        ],
        'admin-update-profile' => [
            'firstname' => 'required|min:3|max:32|alpha_dash',
            'lastname'  => 'required|min:3|max:32|alpha_dash',
            'avatar'    => 'image|mimes:jpg,jpeg,png|max:3500',
        ],
        'admin-update-password' => [
            'password'             => 'required|min:6',
            'passwordConfirmation' => 'required|same:password',
        ],
    ];

    public static function hashPassword($password, $salt)
    {
        $password = Security::hash('sha512', $password, 10);
        $salt     = Security::hash('sha512', $salt, 10);
        $hash     = Security::hash('sha512', $password . $salt, 10);

        return $hash;
    }

    public function accounts($server = null)
    {
        if ($server && in_array($server, config('dofus.servers')))
        {
            $accounts = Cache::remember('accounts_'.$server.'_'.$this->id, 10, function() use($server) {
                return ModelCustom::hasManyOnOneServer('auth', $server, Account::class, 'Email', $this->email);
            });

            return $accounts;
        }
        else
        {
            $accounts = Cache::remember('accounts_'.$this->id, 10, function() {
                return ModelCustom::hasManyOnManyServers('auth', Account::class, 'Email', $this->email);
            });

            return $accounts;
        }
    }

    public function transactions()
    {
        $transactions = Cache::remember('transactions_'.$this->id, 10, function() {
            return $this->hasMany(Transaction::class)->orderBy('created_at', 'desc')->get();
        });

        return $transactions;
    }

    public function votes()
    {
        $votes = Cache::remember('votes_'.$this->id, 10, function() {
            return $this->hasMany(Vote::class)->orderBy('created_at', 'desc')->take(10)->get();
        });

        return $votes;
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id', 'id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function forum()
    {
        return $this->hasOne(ForumAccount::class, 'member_id', 'forum_id');
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

    public function isActive()
    {
        return $this->active == 1;
    }

    public function isBanned()
    {
        return $this->banned == 1;
    }

    public function isCertified()
    {
        return $this->certified == 1;
    }
}
