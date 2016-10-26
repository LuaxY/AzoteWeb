<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class World extends Model
{
    protected $primaryKey = 'Id';
    
    public $timestamps = false;

    public function isOnline()
    {
        return $this->Status == 3 ? true : false;
    }

    public function getOnlineCharacters()
    {
        return $this->CharsCount;
    }
}
