<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\WorldCharacter;
use App\ModelCustom;

class Account extends Model
{
    protected $primaryKey = 'Id';

    protected $table = 'accounts';

    public $timestamps = false;

    public $server;

    protected $dates = ['CreationDate', 'BanEndDate'];

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
        'register' => [
            'login'                => 'required|min:3|max:32|unique:{DB}.accounts,Login|alpha_dash',
            'nickname'             => 'required|min:3|max:32|unique:{DB}.accounts,Nickname|alpha_dash',
            'password'             => 'required|min:6',
            'passwordConfirmation' => 'required|same:password',
        ],
        'update' => [
            'password'             => 'required|min:6',
            'passwordConfirmation' => 'required|same:password',
        ]
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
        $worldCharacters = ModelCustom::hasManyOnOneServer('auth', $this->server, WorldCharacter::class, 'AccountId', $this->Id);

        foreach ($worldCharacters as $worldCharacter)
        {
            $characters[] = $worldCharacter->character();
        }

        return $characters;
    }

    public function returnStatus()
    {
        if($this->IsJailed == 1)
        {
            $text = 'Jailed';
            $label = 'danger';
        }
        if($this->IsBanned == 1)
        {
            $text = 'Banned';
            $label = 'danger';
        }
        if($this->IsJailed == 0 && $this->IsBanned == 0)
        {
            $text = 'OK';
            $label = 'success';
        }

        return '<span class="label label-'.$label.'">'.$text.'</span>';
    }
}
