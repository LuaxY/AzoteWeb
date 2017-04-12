<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Cache;
use App\Guild;
use App\Experience;
use App\Alignment;
use \DB;
use App\Helpers\Utils;

class Character extends Model
{
    protected $primaryKey = 'Id';

    protected $table = 'characters';

    protected $dates = ['CreationDate', 'LastUsage', 'DeletedDate'];

    public $timestamps = false;

    public $server;

    public function position($type = 'pvp', $spec = 'all', $server = 'sigma')
    {
        $resultDB = Cache::remember('position.'.$this->Id.'.'.$type.'.'.$spec.'.'.$server, 30, function () use ($server, $type, $spec) {
            $power = 0;
            if($type == 'xp')
                $power = $this->Experience + ($this->PrestigeRank * 3000000000000);
            $db = config('database.connections');
            $auth  = $db[$server.'_auth']['database'];
            $world = $db[$server.'_world']['database'];
        
            $result = DB::table($world.'.characters AS ch')
                ->leftJoin($auth.'.worlds_characters AS wc', 'ch.Id', '=', 'wc.CharacterId')
                ->leftJoin($auth.'.accounts AS acc', 'wc.AccountId', '=', 'acc.Id')
                ->where('acc.UserGroupId', 1);
                
                if($type == 'pvp')
                    $result = $result->where('ch.Honor', '>', $this->Honor);
                if($type == 'xp')
                    $result = $result->where(DB::raw('ch.Experience + (ch.PrestigeRank * 3000000000000)'), '>', $power);
                
                if($spec == 'breed')
                    $result = $result->where('Breed', $this->Breed);

                $result = $result->count('ch.Id');

            return Utils::format_price((int)($result + 1), ' ');
        });

        return $resultDB;
 
    }

    public function alignment()
    {
        $alignment = new Alignment($this->AlignmentSide, $this->Honor);
        return $alignment;
    }

    public function titleActive($server = 'sigma')
    {
        $titleactive =  Cache::remember('titleactive_'.$server.'_'.$this->TitleId, 1440, function () use($server) {
               return ModelCustom::hasOneOnOneServer('world', $server, Title::class, 'Id', $this->TitleId);
        });
       return $titleactive ? $titleactive : null;
    }

    public function ornamentActive($server = 'sigma')
    {
        $ornamentactive =  Cache::remember('ornamentactive'.$server.'_'.$this->Ornament, 1440, function () use($server) {
               return ModelCustom::hasOneOnOneServer('world', $server, Ornament::class, 'Id', $this->Ornament);
        });
       return $ornamentactive ? $ornamentactive : null;
    }

    public function items()
    {
        return $this->hasMany(CharacterItem::class, 'OwnerId', 'Id');
    }

    public function guild($server = 'sigma')
    {
        $guildmember = Cache::remember('guildmember_'.$server.'_'.$this->Id, 120, function () use($server) {
               return ModelCustom::hasOneOnOneServer('world', $server, GuildMember::class, 'CharacterId', $this->Id);
        });

        if($guildmember)
        {
            $guild = Cache::remember('guild_'.$guildmember->GuildId, 120, function () use($server, $guildmember) {
                return Guild::on($server.'_world')->findOrFail($guildmember->GuildId);
            });
            if($guild)
                return $guild;
            else
                return null;
        }
        return null;
    }

    public function level($server = 'sigma')
    {
        // Prestige
        $tempExp = $this->Experience;

        if (config('dofus.details')[$server]->prestige) {
            $maxExp = Cache::remember('exp_' . $server . '_max', 1440, function () use ($server) {
                return Experience::on($server . '_world')->orderBy('CharacterExp', 'desc')->first();
            });

            if ($maxExp) {
                $tempExp = $this->Experience - ($this->PrestigeRank * $maxExp->CharacterExp);
            }
        }

        $exp = Cache::remember('exp_' . $server . '_' . $tempExp, 1440, function () use ($server, $tempExp) {
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
