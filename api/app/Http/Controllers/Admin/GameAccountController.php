<?php

namespace App\Http\Controllers\Admin;

use App\Account;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;

class GameAccountController extends Controller
{
    const TICKET_LENGTH = 32;

    private function isServerExist($server)
    {
        if (!in_array($server, config('dofus.servers')))
        {
            return false;
        }

        return true;
    }

    public function index(User $user, $server)
    {
        if (!$this->isServerExist($server))
        {
           abort(404);
        }
            $user = User::findOrFail($user->id);
            $accounts = $user->accounts($server);
            return view('admin.users.servers.index', compact('accounts', 'user', 'server'));
    }

    public function store(User $user, $server, Request $request)
    {
        if (!$this->isServerExist($server))
        {
            abort(404);
        }

        $database = $server . '_auth';

        $rules = Account::$rules['register'];
        $rules['login']    = str_replace('{DB}', $database, $rules['login']);
        $rules['nickname'] = str_replace('{DB}', $database, $rules['nickname']);

        $verifier = app()->make('validation.presence');
        $verifier->setConnection($database);
        $validator = Validator::make($request->all(), $rules);
        $validator->setPresenceVerifier($verifier);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $account = new Account;
        $account->changeConnection($database);
        $account->Login           = $request->login;
        $account->PasswordHash    = md5($request->password);
        $account->Nickname        = $request->nickname;
        $account->UserGroupId     = 1;
        $account->Ticket          = strtoupper(str_random(self::TICKET_LENGTH));
        $account->SecretQuestion  = 'Code secret disponible sur le site';
        $account->SecretAnswer    = sprintf('%04d', rand(0000, 9999));
        $account->Lang            = 'fr';
        $account->Email           = $user->email;
        $account->CreationDate    = date('Y-m-d H:i:s');
        $account->Tokens          = 0;
        $account->NewTokens       = 0;
        $account->SubscriptionEnd = '2016-01-01 00:00:00';
        $account->IsJailed        = false;
        $account->IsBanned        = false;
        $account->server          = $request->server;
        $account->save();

        return response()->json([], 202);
    }






}
