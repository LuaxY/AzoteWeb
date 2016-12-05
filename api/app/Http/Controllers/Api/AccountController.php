<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use Auth;
use Session;
use App\ModelCustom;
use App\Account;
use App\User;

class AccountController extends ApiController
{
    public function auth()
    {
        $req = $this->input();

        if ($req && property_exists($req, 'method') && $req->method == "AuthenticationByAnkamaToken")
        {
            $result = new \stdClass;
            $ticket =      $req->params[0];
            $serverId =    $req->params[1];
            $characterId = $req->params[2];

            if (Auth::check())
            {
                $user = Auth::user();
            }
            elseif ($ticket == "ADMIN")
            {
                $user = User::where('pseudo', 'Luax')->first();
                //$user = ModelCustom::hasOneOnOneServer('auth', 'sigma', Account::class, 'Login', 'Luax');
            }
            else
            {
                $user = User::where('ticket', $ticket)->first();
                //$user = ModelCustom::hasOneOnOneServer('auth', 'sigma', Account::class, 'Ticket', $ticket);
            }

            if ($user)
            {
                Auth::login($user);
                Session::put("ticket",      $ticket);
                Session::put("serverId",    $serverId);
                Session::put("characterId", $characterId);
                $result->nickname = $user->pseudo;
            }
            else
            {
                //$result->error = "AUTH_FAILED";
                return $this->softError("AUTH_FAILED");
            }

            return $this->result($result);
        }

        return $this->softError("Method not found");
    }

    public function info()
    {
        if (Auth::guest())
        {
            $data = new \stdClass;
            $data->error = "AUTH_FAILED";

            return $this->result($data);
        }

        $req = $this->input();

        if (@$req->method == "Money")
        {
            $result = new \stdClass;
            $result->ogrins = Auth::user()->points;
            $result->krozs = 0;

            return $this->result($result);
        }

        return $this->softError("Method not found");
    }
}
