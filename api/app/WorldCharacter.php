<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Cache;

use App\Character;

class WorldCharacter extends Model
{
    protected $primaryKey = 'Id';

    protected $table = 'worlds_characters';

    public $server;

    public function character()
    {
        $character = Cache::remember('world_character_'.$this->server.'_'.$this->Id, 10, function() {
            return ModelCustom::hasOneOnOneServer('world', $this->server, Character::class, 'Id', $this->CharacterId);
        });

        return $character;
    }

    public function account()
    {
        $character = Cache::remember('world_character_account_'.$this->server.'_'.$this->Id, 10, function() {
            return ModelCustom::hasOneOnOneServer('auth', $this->server, Account::class, 'Id', $this->AccountId);
        });

        return $character;
    }
}
