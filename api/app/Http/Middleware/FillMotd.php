<?php

namespace App\Http\Middleware;

use Closure;
use \Config;

class FillMotd
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $json = json_decode(file_get_contents("../motd.json"));

        $motd = [];
        $motdjson = $json->motd;

        $motd['title']    = $motdjson->title;
        $motd['subtitle'] = $motdjson->subtitle;
        $motd['postid']   = $motdjson->postid;

        Config::set('dofus.motd', $motd);

        return $next($request);
    }
}
