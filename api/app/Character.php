<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Cache;

use App\Experience;

class Character extends Model
{
    protected $primaryKey = 'Id';

    protected $table = 'characters';

    protected $dates = ['CreationDate', 'LastUsage', 'DeletedDate'];

    public $server;

    public function level()
    {
        $exp = Cache::remember('exp_'.$this->Experience, 1000, function() {
            return Experience::where('CharacterExp', '<=', $this->Experience)->orderBy('Level', 'desc')->first();
        });

        if ($exp)
        {
            return $exp->Level;
        }
        else
        {
            return 1;
        }
    }

    public function classe()
    {
        $classes = [
            1  => 'Féca',
            2  => 'Osamodas',
            3  => 'Enutrof',
            4  => 'Sram',
            5  => 'Xélor',
            6  => 'Ecaflip',
            7  => 'Eniripsa',
            8  => 'Iop',
            9  => 'Crâ',
            10 => 'Sadida',
            11 => 'Sacrieur',
            12 => 'Pandawa',
            13 => 'Roublard',
            14 => 'Zobal',
            15 => 'Steamer',
            16 => 'Eliotrope',
            17 => 'Huppermage'
        ];

        return $classes[$this->Breed];
    }

    public function isDeleted()
    {
        return $this->DeletedDate ? true : false;
    }

    public function scopeDeleted($query)
    {
        $query->where('DeletedDate', '!=', null);
    }

    public function account($server)
    {
        $worldCharacter = ModelCustom::hasOneOnOneServer('auth', $server, WorldCharacter::class, 'CharacterId', $this->Id);
        return $worldCharacter ? $worldCharacter->account() : null;
    }
}
