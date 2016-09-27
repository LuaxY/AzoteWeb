<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use \Cache;
use \DB;

use App\Character;

class PageController extends Controller
{
    const LADDER_CACHE_EXPIRE_MINUTES = 10;

    public function download()
    {
        return view('pages.download');
    }

    public function ladder()
    {
        $characters = Cache::remember('ladder', self::LADDER_CACHE_EXPIRE_MINUTES, function() {
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

        return view('pages.ladder', ['characters' => $characters]);
    }

    public function servers()
    {
        $servers = config('dofus.details');

        return view('pages.servers', ['servers' => $servers]);
    }
}
