<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function download()
    {
        return view('pages.download');
    }

    public function servers()
    {
        $servers = config('dofus.details');

        return view('pages.servers', ['servers' => $servers]);
    }
}
