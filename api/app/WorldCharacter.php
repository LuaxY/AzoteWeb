<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Character;

class WorldCharacter extends Model
{
    protected $primaryKey = 'Id';

    protected $table = 'worlds_characters';

    public $server;

    public function character()
    {
        return ModelCustom::hasOneOnOneServer('world', $this->server, Character::class, 'Id', $this->CharacterId);
    }
}
