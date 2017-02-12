<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

use \Cache;

use App\Security;
use App\ModelCustom;
use App\ForumAccount;
use App\Vote;
use App\LotteryTicket;
use App\Shop\ShopStatus;

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
        'recover-password' => [
            'password'             => 'required|min:6',
            'passwordConfirmation' => 'required|same:password',
        ],
        'update-email' => [
            'passwordOld' => 'required|old_password:{PASSWORD},{SALT}',
            'email'       => 'required|email|not_throw_away|unique:users,email',
        ],
        'update-profile' => [
            'firstname' => 'required|min:3|max:32|alpha_dash',
            'lastname'  => 'required|min:3|max:32|alpha_dash',
        ],
        'certify' => [
            'firstname' => 'required|min:3|max:32|alpha_dash',
            'lastname'  => 'required|min:3|max:32|alpha_dash',
            'birthday'  => 'required|date',
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
        'addticket' => [
            'description'             => 'required|min:5|max:32',
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

    public function transactions($take = null)
    {
        $transactions = null;

        if ($take)
        {
            $transactions = Cache::remember('transactions_' . $this->id . '_' . $take, 10, function() use($take) {
                return $this->hasMany(Transaction::class)->orderBy('created_at', 'desc')->take($take)->get();
            });
        }
        else
        {
            $transactions = Cache::remember('transactions_' . $this->id, 10, function() {
                return $this->hasMany(Transaction::class)->orderBy('created_at', 'desc')->get();
            });
        }

        return $transactions;
    }

    public function votes($take = null)
    {
        $votes = null;

        if ($take)
        {
            $votes = Cache::remember('votes_' . $this->id . '_' . $take, 10, function() use($take) {
                return $this->hasMany(Vote::class)->orderBy('created_at', 'desc')->take($take)->get();
            });
        }
        else
        {
            $votes = Cache::remember('votes_' . $this->id, 10, function() {
                return $this->hasMany(Vote::class)->orderBy('created_at', 'desc')->get();
            });
        }

        return $votes;
    }

    public function gifts($onlyAvailable = false, $server = "")
    {
        $gifts = null;

        if ($onlyAvailable)
        {
            $gifts = Cache::remember('gifts_available_' . $server . '_' . $this->id, 10, function() use($server) {
                return $this->hasMany(Gift::class)->where('server', $server)->where('delivred', false)->get();
            });
        }
        else
        {
            $gifts = Cache::remember('gifts_' . $this->id, 10, function() {
                return $this->hasMany(Gift::class)->get();
            });
        }

        return $gifts;
    }

    public function lotteryTickets($onlyAvailable = false)
    {
        $tickets = null;

        if ($onlyAvailable)
        {
            $tickets = Cache::remember('tickets_available_' . $this->id, 10, function() {
                return $this->hasMany(LotteryTicket::class, 'user_id', 'id')->where('used', false)->orderBy('created_at', 'desc')->get();
            });
        }
        else
        {
            $tickets = Cache::remember('tickets_' . $this->id, 10, function() {
                return $this->hasMany(LotteryTicket::class, 'user_id', 'id')->orderBy('created_at', 'desc')->get();
            });
        }

        return $tickets;
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

    public function isFistBuy()
    {
        foreach ($this->accounts() as $account)
        {
            foreach ($account->characters(false, true) as $character)
            {
                if ($character->level() > config('dofus.payment.level_for_real'))
                {
                    return false;
                }
            }
        }

        $transactionsCount = count($this->hasMany(Transaction::class)->where('state', ShopStatus::PAYMENT_SUCCESS)->get());

        return $transactionsCount < config('dofus.payment.minimum_for_real');
    }

    public function IsBannedByKey()
    {
        $email = $this->email;
        $servers = config('dofus.servers');

        $keys = array(); // Array with user keys
        foreach ($servers as $server)
        {
            if($server == 'sigma')
            {
                $keysSigma = Account::on($server . '_auth')->select('LastClientKey')->where('Email', $email)->get();
                if($keysSigma)
                {
                    foreach($keysSigma as $k)
                    {
                        if($k->LastClientKey != null)
                        array_push($keys, $k->LastClientKey);
                    }
                }
            }
            else
            {
                $keysOther = Account::on($server . '_auth')->select('LastHardwareId')->where('Email', $email)->get();
                if($keysOther)
                {
                    foreach($keysOther as $v)
                    {
                        if($v->LastHardwareId != null)
                        array_push($keys, $v->LastHardwareId);
                    }
                }
            }
        }

        $keys_banned_db = BannedKeys::select('Key')->get();
        $keys_banned = array(); // Array with banned keys
        if($keys_banned_db)
        {
            foreach($keys_banned_db as $keyBanned)
            {
                array_push($keys_banned, $keyBanned->Key);
            }
        }  

            If(!empty($keys) && !empty($keys_banned))
            {     
                foreach($keys as $key)
                {
                    if(in_array($key, $keys_banned))
                    {
                        $this->shadowBan = true;
                        $this->save();
                        
                        return true;
                    }
                }
            } 
        return false;
    }
}
