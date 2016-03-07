<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Account;
use App\Beta;

use Validator;
use Auth;

class AccountController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), User::$rules['register']);

        if ($validator->fails())
        {
            return $this->error(401, 'formulaire incorrect', $validator->errors()->all());
        }

        $allowedEmail = Beta::where('email', $request->input('email'))->first();

        if (!$allowedEmail || $allowedEmail->active == 0)
        {
            return $this->error(401, 'adresse email non autorisée pour la bêta');
        }

        $salt = str_random(8);

        $user = new User;
        $user->email     = $request->input('email');
        $user->password  = hash('sha1', $request->input('password') . $salt);
        $user->salt      = $salt;
        $user->firstname = $request->input('firstName');
        $user->lastname  = $request->input('lastName');
        $user->save();

        $allowedEmail->active = 0;
        $allowedEmail->save();

        return $this->success('compte créé');
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();

        if ($user && ($user->password === hash('sha1', $request->input('password') . $user->salt)))
        {
            Auth::login($user);
            Auth::user()->ticket = str_random(32);
            Auth::user()->update(['ticket' => Auth::user()->ticket]);
            return response()->json(['authorizationTicket' => Auth::user()->ticket]);
        }

        return $this->error(401, 'identifiants invalide');
    }

    public function profile()
    {
        $profile = new \stdClass;
        $profile->firstname     = Auth::user()->firstname;
        $profile->lastname      = Auth::user()->lastname;
        $profile->email         = Auth::user()->email;
        $profile->lastIPAddress = Auth::user()->last_ip_address;
        $profile->gameAccounts  = [];

        $accounts = Account::where('Email', Auth::user()->email)->get();

        foreach ($accounts as $account)
        {
            $gameAccount = new \stdClass;
            $gameAccount->id              = $account->Id;
            $gameAccount->login           = $account->Login;
            $gameAccount->nickname        = $account->Nickname;
            $gameAccount->secretAnswer    = $account->SecretAnswer;
            $gameAccount->newTokens       = $account->NewTokens;
            $gameAccount->creationDate    = $account->CreationDate;
            $gameAccount->lastConnection  = $account->LastConnection;
            $gameAccount->lastConnectedIp = $account->LastConnectionIp;
            $gameAccount->lastVote        = $account->LastVote;

            $profile->gameAccounts[] = $gameAccount;
        }

        return response()->json(['profile' => $profile]);
    }

    public function update(Request $request)
    {
        if ($request->input('firstname') && $request->input('lastname'))
        {
            $validator = Validator::make($request->all(), User::$rules['update1']);

            if ($validator->fails())
            {
                return $this->error(401, 'nom/prénom incorrect', $validator->errors()->all());
            }

            Auth::user()->firstname = $request->input('firstname');
            Auth::user()->lastname = $request->input('lastname');
            Auth::user()->update([
                'firstname' => Auth::user()->firstname,
                'lastname' => Auth::user()->lastname,
            ]);
        }

        if ($request->input('password') && $request->input('passwordConfirmation'))
        {
            $validator = Validator::make($request->all(), User::$rules['update2']);

            if ($validator->fails())
            {
                return $this->error(401, 'mot de passe incorrect', $validator->errors()->all());
            }

            Auth::user()->salt     = str_random(8);
            Auth::user()->password = hash('sha1', $request->input('password') . Auth::user()->salt);
            Auth::user()->update([
                'password' => Auth::user()->password,
                'salt'     => Auth::user()->salt,
            ]);
        }

        return $this->success('profile mis à jour');
    }
}
