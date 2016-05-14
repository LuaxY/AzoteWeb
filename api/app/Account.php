<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use WorldCharacter;

class Account extends Model
{
    protected $primaryKey = 'Id';

    protected $table = 'accounts';

    protected $connection = 'auth';

    public $timestamps = false;

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
            'login'                => 'required|min:3|max:32|unique:accounts,Login|alpha_dash',
            'nickname'             => 'required|min:3|max:32|unique:accounts,Nickname|alpha_dash',
            'password'             => 'required|min:6',
            'passwordConfirmation' => 'required|same:password',
        ],
        'update' => [
            'password'             => 'required|min:6',
            'passwordConfirmation' => 'required|same:password',
        ]
    ];

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
        $worldCharacters =  $this->hasMany('App\WorldCharacter', 'AccountId', 'Id');

        foreach ($worldCharacters->get() as $worldCharacter)
        {
            $characters[] = $worldCharacter->character()->first();
        }

        return $characters;
    }
}
