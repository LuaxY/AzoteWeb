<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \Cache;
use \DB;

use App\Character;
use App\Guild;

class LadderController extends Controller
{
    const LADDER_CACHE_EXPIRE_MINUTES = 10;

    public function general()
    {
        $characters = Cache::remember('ladder.general', self::LADDER_CACHE_EXPIRE_MINUTES, function () {
            $server = 'sigma';
            $db = config('database.connections');
            $auth  = $db[$server.'_auth']['database'];
            $world = $db[$server.'_world']['database'];

            $result = DB::table($world.'.characters AS ch')
                ->select('ch.*')
                ->leftJoin($auth.'.worlds_characters AS wc', 'ch.Id', '=', 'wc.CharacterId')
                ->leftJoin($auth.'.accounts AS acc', 'wc.AccountId', '=', 'acc.Id')
                ->where('acc.UserGroupId', 1)
                ->orderBy('ch.Experience', 'DESC')
                ->take(100)
                ->get();

            return Character::hydrate($result);
        });

        return view('ladder.general', ['characters' => $characters]);
    }

    public function pvp()
    {
        $characters = Cache::remember('ladder.pvp', self::LADDER_CACHE_EXPIRE_MINUTES, function () {
            $server = 'sigma';
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

        return view('ladder.pvp', ['characters' => $characters]);
    }

    public function guild()
    {
        $guilds = Cache::remember('ladder.guilds', self::LADDER_CACHE_EXPIRE_MINUTES, function () {
            $server = 'sigma';
            return Guild::on($server.'_world')->orderBy('Experience', 'DESC')->take(100)->get();
        });

        return view('ladder.guild', ['guilds' => $guilds]);
    }
}
