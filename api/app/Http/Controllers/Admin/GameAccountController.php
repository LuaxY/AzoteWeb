<?php

namespace App\Http\Controllers\Admin;

use App\Account;
use App\Character;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Kamaln7\Toastr\Facades\Toastr;

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
        $account->server = $server;
        $characters = $account->characters();
        return view('admin.users.servers.edit', compact('user', 'server', 'account', 'characters'));
    }

    public function update(User $user, $server, $accountId, Request $request)
    {
        if (!$this->isServerExist($server))
        {
            abort(404);
        }
        $database = $server . '_auth';
        $account = Account::on($server . '_auth')->where('Id', $accountId)->first();
        $this->validate($request, [
            'Login'                => 'required|min:3|max:32|unique:'.$database.'.accounts,Login,'.$account->Id.'|alpha_dash',
            'Nickname'             => 'required|min:3|max:32|unique:'.$database.'.accounts,Nickname,'.$account->Id.'|alpha_dash',
            'UserGroupId'          => 'required|numeric',
        ]);

        if(!array_key_exists($request->UserGroupId, config('dofus.ranks')))
        {
            return redirect()->back();
        }
        $account->Login = $request->Login;
        $account->Nickname = $request->Nickname;
        $account->UserGroupId = $request->UserGroupId;
        $account->save();

        Toastr::success('Game account updated');
        return redirect()->route('admin.user.game.accounts', [$user->id, $server]);
    }
    public function password(User $user, $server, $accountId, Request $request)
    {
        if (!$this->isServerExist($server))
        {
            abort(404);
        }
        $validator = Validator::make($request->all(), Account::$rules['update']);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $account = Account::on($server . '_auth')->where('Id', $accountId)->first();
        $account->PasswordHash = md5($request->password);
        $account->save();

        return response()->json([], 202);
    }

    public function ban(User $user, $server, $accountId, Request $request)
    {
        if (!$this->isServerExist($server))
        {
            abort(404);
        }

        $validator = Validator::make($request->all(), Account::$rules['sanction']);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $bannerUser = Auth::user();
        $bannerAccount = Account::on($server . '_auth')->where('Email', $bannerUser->email)->first();
        $bannerAccountId = $bannerAccount ? $bannerAccount->Id : 0;

        $account = Account::on($server . '_auth')->where('Id', $accountId)->first();

        if($request->allaccounts == '0')
        {
            $account->BanReason = $request->BanReason;
            $account->BanEndDate = $request->BanEndDate;
            $account->IsBanned = true;
            $account->BannerAccountId = $bannerAccountId;
            $account->save();
        }
        if($request->allaccounts == '1')
        {
            $accounts = Account::on($server . '_auth')->where('Email', $account->Email)->get();

            foreach ($accounts as $account)
            {
                $account->BanReason = $request->BanReason;
                $account->BanEndDate = $request->BanEndDate;
                $account->IsBanned = true;
                $account->BannerAccountId = $bannerAccountId;
                $account->save();
            }
        }

        return response()->json(['test' => $request->allaccounts], 202);
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

        $validator = Validator::make($request->all(), Account::$rules['sanction']);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $bannerUser = Auth::user();
        $bannerAccount = Account::on($server . '_auth')->where('Email', $bannerUser->email)->first();
        $bannerAccountId = $bannerAccount ? $bannerAccount->Id : 0;

        $account = Account::on($server . '_auth')->where('Id', $accountId)->first();

        if($request->allaccounts == '0')
        {
            $account->BanReason = $request->BanReason;
            $account->BanEndDate = $request->BanEndDate;
            $account->IsJailed = true;
            $account->BannerAccountId = $bannerAccountId;
            $account->save();
        }
        if($request->allaccounts == '1')
        {
            $accounts = Account::on($server . '_auth')->where('Email', $account->Email)->get();

            foreach ($accounts as $account)
            {
                $account->BanReason = $request->BanReason;
                $account->BanEndDate = $request->BanEndDate;
                $account->IsJailed = true;
                $account->BannerAccountId = $bannerAccountId;
                $account->save();
            }
        }
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