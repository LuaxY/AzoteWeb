<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Cache;

use App\Experience;
use App\GuildMember;
use App\Services\DofusForge;

class Guild extends Model
{
    protected $primaryKey = 'Id';

    protected $table = 'guilds';

    public $timestamps = false;

    public $server;

    public function level()
    {
        $exp = Cache::remember('exp_'.$this->Experience, 1000, function () {
            return Experience::where('GuildExp', '<=', $this->Experience)->orderBy('Level', 'desc')->first();
        });

        if ($exp) {
            return $exp->Level;
        } else {
            return 1;
        }
    }

    public function members($server)
    {
        $guildMembers = ModelCustom::hasManyOnOneServer('world', $server, GuildMember::class, 'GuildId', $this->Id);
        return $guildMembers;
    }

    public function emblem()
    {
        return DofusForge::asset("dofus/renderer/emblem/{$this->EmblemForegroundShape}/{$this->EmblemBackgroundShape}/0x".strtoupper(dechex($this->EmblemForegroundColor))."/0x".strtoupper(dechex($this->EmblemBackgroundColor))."/48_48-0.png");
    }
}
