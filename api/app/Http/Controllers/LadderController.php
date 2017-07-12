<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \Cache;
use \DB;
use App\World;
use App\Character;
use App\Guild;
use App\Exceptions\GenericException;

class LadderController extends Controller
{
    const LADDER_CACHE_EXPIRE_MINUTES = 30;

    public function general($server)
    {
        if (!World::isServerExist($server)) {
            throw new GenericException('invalid_server', $server);
        }

        $characters = Cache::remember('ladder.general.'.$server, self::LADDER_CACHE_EXPIRE_MINUTES, function () use ($server) {
            $db = config('database.connections');
            $auth  = $db[$server.'_auth']['database'];
            $world = $db[$server.'_world']['database'];

            $result = DB::table($world.'.characters AS ch')
                ->select('ch.Id', 'ch.Experience', 'ch.PrestigeRank', 'ch.Name', 'ch.Breed', 'ch.Sex')
                ->leftJoin($auth.'.worlds_characters AS wc', 'ch.Id', '=', 'wc.CharacterId')
                ->leftJoin($auth.'.accounts AS acc', 'wc.AccountId', '=', 'acc.Id')
                ->where('acc.UserGroupId', 1);
                                                    
            if (config('dofus.details')[$server]->prestige) {
                $result = $result->orderBy('ch.PrestigeRank', 'DESC');
            }

            $result = $result->orderBy('ch.Experience', 'DESC')
                ->take(100)
                ->get();

            return Character::hydrate($result->toArray());
        });

        return view('ladder.general', ['server' => $server, 'current' => 'general',  'characters' => $characters]);
    }

    public function pvp($server)
    {
        if (!World::isServerExist($server)) {
            throw new GenericException('invalid_server', $server);
        }

        $characters = Cache::remember('ladder.pvp.'.$server, self::LADDER_CACHE_EXPIRE_MINUTES, function () use ($server) {
            $db = config('database.connections');
            $auth  = $db[$server.'_auth']['database'];
            $world = $db[$server.'_world']['database'];

            $result = DB::table($world.'.characters AS ch')
                ->select('ch.Id', 'ch.Name', 'ch.Breed', 'ch.Sex', 'ch.Honor', 'ch.AlignmentSide', 'ch.PrestigeRank', 'ch.Experience')
                ->leftJoin($auth.'.worlds_characters AS wc', 'ch.Id', '=', 'wc.CharacterId')
                ->leftJoin($auth.'.accounts AS acc', 'wc.AccountId', '=', 'acc.Id')
                ->where('acc.UserGroupId', 1)
                ->orderBy('ch.Honor', 'DESC')
                ->where('ch.Honor', '>', 0)
                ->where('ch.AlignmentSide', '>', 0)
                ->take(100)
                ->get();

            return Character::hydrate($result->toArray());
        });

        return view('ladder.pvp', ['server' => $server, 'current' => 'pvp', 'characters' => $characters]);
    }

    public function guild($server)
    {
        if (!World::isServerExist($server)) {
            throw new GenericException('invalid_server', $server);
        }

        $guilds = Cache::remember('ladder.guilds.'.$server, self::LADDER_CACHE_EXPIRE_MINUTES, function () use ($server) {
            return Guild::on($server.'_world')->orderBy('Experience', 'DESC')->take(100)->get();
        });

        return view('ladder.guild', ['server' => $server, 'current' => 'guild',  'guilds' => $guilds]);
    }

    public function kolizeum($server)
    {
        if (!World::isServerExist($server)) {
            throw new GenericException('invalid_server', $server);
        }

        $characters = Cache::remember('ladder.kolizeum.'.$server, self::LADDER_CACHE_EXPIRE_MINUTES, function () use ($server) {
            $db = config('database.connections');
            $auth  = $db[$server.'_auth']['database'];
            $world = $db[$server.'_world']['database'];

            $result = DB::table($world.'.characters AS ch')
                ->select('ch.Id', 'ch.Name', 'ch.Breed', 'ch.Sex', 'ch.ArenaRank', 'ch.PrestigeRank', 'ch.Experience', 'ch.ArenaDailyMatchsCount', 'ch.ArenaDailyMatchsWon')
                ->leftJoin($auth.'.worlds_characters AS wc', 'ch.Id', '=', 'wc.CharacterId')
                ->leftJoin($auth.'.accounts AS acc', 'wc.AccountId', '=', 'acc.Id')
                ->where('acc.UserGroupId', 1)
                ->orderBy('ch.ArenaRank', 'DESC')
                ->where('ch.ArenaRank', '>', 0)
                ->take(100)
                ->get();

            return Character::hydrate($result->toArray());
        });

        return view('ladder.kolizeum', ['server' => $server, 'current' => 'kolizeum', 'characters' => $characters]);
    }

    public function kolizeum1v1($server)
    {
        if (!World::isServerExist($server)) {
            throw new GenericException('invalid_server', $server);
        }
        if(config('dofus.details')[$server]->version == '2.10')
        {
            throw new GenericException('invalid_server', $server);
        }

        $characters = Cache::remember('ladder.kolizeum1v1.'.$server, self::LADDER_CACHE_EXPIRE_MINUTES, function () use ($server) {
            $db = config('database.connections');
            $auth  = $db[$server.'_auth']['database'];
            $world = $db[$server.'_world']['database'];

            $result = DB::table($world.'.characters AS ch')
                ->select('ch.Id', 'ch.Name', 'ch.Breed', 'ch.Sex', 'ch.ArenaDuelRank', 'ch.PrestigeRank', 'ch.Experience', 'ch.ArenaDuelDailyMatchsCount', 'ch.ArenaDuelDailyMatchsWon')
                ->leftJoin($auth.'.worlds_characters AS wc', 'ch.Id', '=', 'wc.CharacterId')
                ->leftJoin($auth.'.accounts AS acc', 'wc.AccountId', '=', 'acc.Id')
                ->where('acc.UserGroupId', 1)
                ->orderBy('ch.ArenaDuelRank', 'DESC')
                ->where('ch.ArenaDuelRank', '>', 0)
                ->take(100)
                ->get();

            return Character::hydrate($result->toArray());
        });

        return view('ladder.kolizeum1v1', ['server' => $server, 'current' => 'kolizeum', 'characters' => $characters]);
    }
}
