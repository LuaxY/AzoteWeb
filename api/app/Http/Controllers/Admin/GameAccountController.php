<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;

class GameAccountController extends Controller
{
    private function isServerExist($server)
    {
        $server_rule = [
            'server' => 'required|in:' . implode(',', config('dofus.servers')),
        ];

        $validator = Validator::make(['server' => $server], $server_rule);

        if ($validator->fails())
        {
            return false;
        }

        return true;
    }

    public function index(User $user, $server)
    {
        $servers = config('dofus.servers');
        if(in_array($server, $servers))
        {
            $user = User::findOrFail($user->id);
            $accounts = $user->accounts($server);
            $server = $server;
            return view('admin.users.servers.index', compact('accounts', 'user', 'server'));
        }
        else
        {
            abort(404);
        }
    }






}
