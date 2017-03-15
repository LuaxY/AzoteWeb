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

    public $timestamps = false;

    public $server;

    public function level($server = 'sigma')
    {
        // Prestige
        $tempExp = $this->Experience;

        if (config('dofus.details')[$server]->prestige) {
            $maxExp = Cache::remember('exp_' . $server . '_max', 1000, function () use ($server) {
                return Experience::on($server . '_world')->orderBy('CharacterExp', 'desc')->first();
            });

            if ($maxExp) {
                $tempExp = $this->Experience - ($this->PrestigeRank * $maxExp->CharacterExp);
            }
        }

        $exp = Cache::remember('exp_' . $server . '_' . $tempExp, 1000, function () use ($server, $tempExp) {
            return Experience::on($server . '_world')->where('CharacterExp', '<=', $tempExp)->orderBy('Level', 'desc')->first();
        });

        if ($exp) {
            return $exp->Level;
        } else {
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
            17 => 'Huppermage',
            18 => 'Ouginak'
         ];
        
         return array_key_exists($this->Breed, $classes) ? $classes[$this->Breed] : "Classe inconnue";
    }

    public function alignement()
    {
        $alignement = [
            0 => [ 'Neutre',     '' ],
            1 => [ 'Bontarien',  '#59d7ff' ],
            2 => [ 'Brakmarien', '#ff6161' ],
            3 => [ 'Mercenaire', '#7d6b54' ],
        ];

        $align = $alignement[$this->AlignmentSide];

        return '<font color="' . $align[1] . '">' . $align[0] . '</font>';
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

    public function recoverPrice()
    {
        $price = $this->level() * config('dofus.recover_level_multiplicator_price');
        return $price < config('dofus.recover_minimal_price') ? config('dofus.recover_minimal_price') : $price;
    }
}
