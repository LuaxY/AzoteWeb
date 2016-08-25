<?php

namespace App\Http\Controllers\Admin;

use App\Account;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
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

    public function edit(User $user, $server, $accountId)
    {
        if (!$this->isServerExist($server))
        {
            abort(404);
        }

        $user = User::findOrFail($user->id);
        $account = Account::on($server . '_auth')->where('Id', $accountId)->first();

        return view('admin.users.servers.edit', compact('user', 'server', 'account'));

    }

    public function update(User $user, $server, $accountId, Request $request)
    {
        if (!$this->isServerExist($server))
        {
            abort(404);
        }

        // TODO
        $account = Account::on($server . '_auth')->where('Id', $accountId)->first();

        return redirect()->back();
    }

    public function ban(User $user, $server, $accountId, Request $request)
    {
        if (!$this->isServerExist($server))
        {
            abort(404);
        }

        $account = Account::on($server . '_auth')->where('Id', $accountId)->first();

        $bannerUser = Auth::user();
        $bannerAccount = Account::on($server . '_auth')->where('Email', $bannerUser->email)->first();
        $bannerAccountId = $bannerAccount ? $banneraccount->Id : 0;

        $account->BanReason = $request->BanReason;
        $account->BanEndDate = $request->BanEndDate;
        $account->IsBanned = true;
        $account->BannerAccountId = $bannerAccountId;
        $account->save();

        return response()->json([], 202);
    }

    public function unban(User $user, $server, $accountId, Request $request)
    {
        if (!$this->isServerExist($server))
        {
            abort(404);
        }

        $account = Account::on($server . '_auth')->where('Id', $accountId)->first();

        $account->IsBanned = false;
        $account->save();

        return response()->json([], 202);
    }

    public function jail(User $user, $server, $accountId, Request $request)
    {
        if (!$this->isServerExist($server))
        {
            abort(404);
        }

        $account = Account::on($server . '_auth')->where('Id', $accountId)->first();

        $bannerUser = Auth::user();
        $bannerAccount = Account::on($server . '_auth')->where('Email', $bannerUser->email)->first();
        $bannerAccountId = $bannerAccount ? $banneraccount->Id : 0;

        $account->BanReason = $request->banReason;
        $account->IsJailed = true;
        $account->BannerAccountId = $bannerAccountId;
        $account->save();

        return response()->json([], 202);
    }

    public function unjail(User $user, $server, $accountId, Request $request)
    {
        if (!$this->isServerExist($server))
        {
            abort(404);
        }

        $account = Account::on($server . '_auth')->where('Id', $accountId)->first();

        $account->IsJailed = false;
        $account->save();

        return response()->json([], 202);
    }
}