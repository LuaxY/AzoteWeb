<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use \Cache;

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
            return Character::on('sigma_world')->orderBy('Experience', 'DESC')->take(100)->get();
        });

        return view('pages.ladder', ['characters' => $characters]);
    }

    public function servers()
    {
        $servers = config('dofus.details');

        return view('pages.servers', ['servers' => $servers]);
    }
}
