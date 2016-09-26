<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Cache;

use App\WorldCharacter;
use App\ModelCustom;
use App\Transfert;

class Account extends Model
{
    protected $primaryKey = 'Id';

    protected $table = 'accounts';

    public $timestamps = false;

    public $server;

    protected $dates = ['CreationDate', 'BanEndDate', 'LastConnection'];

    protected $hidden = array('PasswordHash');

    protected $fillable = array(
		'Login',
		'PasswordHash',
		'Nickname',
		'UserGroupId',
		'Ticket',
		'SecretQuestion',
		'SecretAnswer',
		'Lang',
		'Email',
		'CreationDate',
		'SubscriptionEnd',
		'LastVote',
		'VoteCount',
        'IsJailed',
        'IsBanned',
        'Tokens',
        'NewTokens',
	);

    public static $rules = [
        'sanction' => [
            'BanEndDate'            => 'required|date_format:Y-m-d H:i:s',
            'BanReason'             => 'required',
        ],
        'register' => [
            'login'                => 'required|min:3|max:32|unique:{DB}.accounts,Login|alpha_dash',
            'nickname'             => 'required|min:3|max:32|unique:{DB}.accounts,Nickname|alpha_dash',
            'password'             => 'required|min:6',
            'passwordConfirmation' => 'required|same:password',
        ],
        'update-password' => [
            'passwordOld'          => 'required|old_passwordStump:{PASSWORD}',
            'password'             => 'required|min:6',
            'passwordConfirmation' => 'required|same:password',
        ],
    ];

    public function changeConnection($conn)
    {
        $this->connection = $conn;
    }

    public function isAdmin()
    {
        if ($this->Role >= 4)
            return true;
        else
            return false;
    }

    public function characters()
    {
        $characters = [];

        $worldCharacters = Cache::remember('characters_'.$this->server.'_'.$this->Id, 10, function() {
            return ModelCustom::hasManyOnOneServer('auth', $this->server, WorldCharacter::class, 'AccountId', $this->Id);
        });

        foreach ($worldCharacters as $worldCharacter)
        {
            $characters[] = $worldCharacter->character();
        }

        return $characters;
    }

    public function transferts()
    {
        $transferts = Cache::remember('transferts_'.$this->server.'_'.$this->Id, 10, function() {
            return Transfert::where('server', $this->server)->where('account_id', $this->Id)->orderBy('created_at', 'desc')->get();
        });

        return $transferts;
    }

    public function points()
    {
        $account = Account::on($this->server . '_world')->where('Id', $this->Id)->first();

        if ($account)
        {
            return $account->Tokens + $account->NewTokens;
        }
        else
        {
            return 0;
        }
    }

    public function addPoints($amount)
    {
        $account = Account::on($this->server . '_world')->where('Id', $this->Id)->first();

        if ($account)
        {
            $account->NewTokens += $amount;
            $account->save();

            return true;
        }

        return false;
    }

    public function htmlStatus()
    {
        $texts = array();
        $hidden = '';
        if($this->IsJailed == 1)
        {
            $texts[] = 'Jailed';
            $label = 'danger';
        }
        if($this->IsBanned == 1)
        {
            $texts[] = 'Banned';
            $label = 'danger';
        }
        if($this->IsJailed == 0 && $this->IsBanned == 0)
        {
            $texts[] = 'OK';
            $label = 'success';
            $hidden = 'hidden';
        }
        if($this->IsJailed == 1 || $this->IsBanned == 1)
        {
            $texts[] .= '('.$this->BanEndDate.')';
        }

        $span = '';
        foreach($texts as $text)
        {
            $span .= ' <span class="label label-'.$label.'">'.$text.'</span>';

        }

        $span .= ' <button id="pop-'.$this->Id.'" class="'.$hidden.' btn btn-xs btn-default" data-toggle="popover" data-placement="top" title="Sanctioned by '.$this->BannerAccountId.'" data-content="'.$this->BanReason.'"><i class="fa fa-info"></i></button>';


        return $span;
    }

    public function user()
    {
        return User::where('email', $this->Email)->first();
    }
}
