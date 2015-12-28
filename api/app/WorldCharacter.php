<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Character;

class WorldCharacter extends Model
{
    protected $primaryKey = 'Id';

    protected $table = 'worlds_characters';

    protected $connection = 'auth';

    public function character()
    {
        return $this->hasOne('App\Character', 'Id', 'CharacterId');
    }
}
