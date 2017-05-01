<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ModelCustom;
use Cache;

class MarketCharacter extends Model
{
    protected $table = 'market_characters';

    public function character()
    {
         $character = Cache::remember('character_view_'.$this->server.'_'.$this->character_id, 120, function () {
            return ModelCustom::hasOneOnOneServer('world', $this->server, Character::class, 'Id', $this->character_id);
         });
         return $character;
    }
}
