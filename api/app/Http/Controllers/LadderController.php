<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \Cache;
use \DB;

use App\Character;
use App\Guild;
use App\Exceptions\GenericException;

class LadderController extends Controller
{
    const LADDER_CACHE_EXPIRE_MINUTES = 10;

    private function isServerExist($server)
    {
        if (!in_array($server, config('dofus.servers')))
        {
            return false;
        }

        return true;
    }

    public function general($server)
    {
        if (!$this->isServerExist($server))
        {
            throw new GenericException('invalid_server', $server);
        }

        $characters = Cache::remember('ladder.general.'.$server, self::LADDER_CACHE_EXPIRE_MINUTES, function() use($server) {
            $db = config('database.connections');
            $auth  = $db[$server.'_auth']['database'];
            $world = $db[$server.'_world']['database'];

            $result = DB::table($world.'.characters AS ch')
                ->select('ch.*')
                ->leftJoin($auth.'.worlds_characters AS wc', 'ch.Id', '=', 'wc.CharacterId')
                ->leftJoin($auth.'.accounts AS acc', 'wc.AccountId', '=', 'acc.Id')
                ->where('acc.UserGroupId', 1);
                                                    
            if (config('dofus.details')[$server]->prestige)
            {
                $result = $result->orderBy('ch.PrestigeRank', 'DESC');
            }

            $result = $result->orderBy('ch.Experience', 'DESC')
                ->take(100)
                ->get();

            return Character::hydrate($result);
        });

        return view('ladder.general', ['server' => $server, 'current' => 'general',  'characters' => $characters]);
    }

    public function pvp($server)
    {
        if (!$this->isServerExist($server))
        {
            throw new GenericException('invalid_server', $server);
        }

        $characters = Cache::remember('ladder.pvp.'.$server, self::LADDER_CACHE_EXPIRE_MINUTES, function() use($server) {
            $db = config('database.connections');
            $auth  = $db[$server.'_auth']['database'];
            $world = $db[$server.'_world']['database'];

            $result = DB::table($world.'.characters AS ch')
                ->select('ch.*')
                ->leftJoin($auth.'.worlds_characters AS wc', 'ch.Id', '=', 'wc.CharacterId')
                ->leftJoin($auth.'.accounts AS acc', 'wc.AccountId', '=', 'acc.Id')
                ->where('acc.UserGroupId', 1)
                ->orderBy('ch.Honor', 'DESC')
                ->where('ch.Honor', '>', 0)
                ->where('ch.AlignmentSide', '>', 0)
                ->take(100)
                ->get();

            return Character::hydrate($result);
        });

        return view('ladder.pvp', ['server' => $server, 'current' => 'pvp', 'characters' => $characters]);
    }

    public function guild($server)
    {
        if (!$this->isServerExist($server))
        {
            throw new GenericException('invalid_server', $server);
        }

        $guilds = Cache::remember('ladder.guilds.'.$server, self::LADDER_CACHE_EXPIRE_MINUTES, function() use($server) {
            return Guild::on($server.'_world')->orderBy('Experience', 'DESC')->take(100)->get();
        });

        return view('ladder.guild', ['server' => $server, 'current' => 'guild',  'guilds' => $guilds]);
    }
}
