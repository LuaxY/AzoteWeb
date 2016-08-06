<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Experience;

class Character extends Model
{
    protected $primaryKey = 'Id';

    protected $table = 'characters';

    public $server;

    public function level()
    {
        $exp = Experience::where('CharacterExp', '<=', $this->Experience)->orderBy('Level', 'desc')->first();

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
}
