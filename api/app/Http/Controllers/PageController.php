<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use \Cache;
use Auth;
use Mail;

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

    public function email()
    {
        //return view('emails.transfert_points', ['user' => Auth::user()]);

        /*Mail::send(['html' => 'emails.newsletter'], [], function ($message) {
            $message->from(config('mail.sender'), 'Azote.us');
            $message->to('yann@voidmx.net', 'Web Developer');
            $message->subject('Azote.us');
        });*/

        return view('emails.newsletter');
    }
}
