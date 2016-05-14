<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Account;

use Validator;
use Auth;

class GameAccountController extends Controller
{
    public function create(Request $request)
    {
        $verifier = app()->make('validation.presence');
        $verifier->setConnection('auth');
        $validator = Validator::make($request->all(), Account::$rules['register']);
        $validator->setPresenceVerifier($verifier);

        if ($validator->fails())
        {
            return $this->error(401, 'formulaire incorrect', $validator->errors()->all());
        }

        $account = new Account;
        $account->Login           = $request->input('login');
        $account->PasswordHash    = md5($request->input('password'));
        $account->Nickname        = $request->input('nickname');
        $account->UserGroupId     = 1;
        $account->Ticket          = strtoupper(str_random(32));
        $account->SecretQuestion  = 'Code secret disponible sur le site';
        $account->SecretAnswer    = sprintf('%04d', rand(0, 9999));
        $account->Lang            = 'fr';
        $account->Email           = Auth::user()->email;
        $account->CreationDate    = date('Y-m-d H:i:s');
        $account->Tokens          = 0;
        $account->NewTokens       = 0;
        $account->SubscriptionEnd = '2016-01-01 00:00:00';
        $account->IsJailed        = false;
        $account->IsBanned        = false;
        $account->save();

        return response()->json([
            'message' => 'compte créé',
            'id'      => $account->Id,
        ]);
    }

    public function update(Request $request)
    {
        // is account id owned by me ?
        $account = Account::where('Id', $request->input('id'))->where('Email', Auth::user()->email)->first();

        if ($account)
        {
            $verifier = app()->make('validation.presence');
            $verifier->setConnection('auth');
            $validator = Validator::make($request->all(), Account::$rules['update']);
            $validator->setPresenceVerifier($verifier);

            if ($validator->fails())
            {
                return $this->error(401, 'formulaire incorrect', $validator->errors()->all());
            }

            $account->PasswordHash = md5($request->input('password'));
            $account->update(['PasswordHash' => $account->PasswordHash]);

            return $this->success('compte mis à jour');
        }

        return $this->error(401, "id compte invalide");
    }

    public function characters($accountId)
    {
        // is account id owned by me ?
        $account = Account::where('Id', $accountId)->where('Email', Auth::user()->email)->first();

        if ($account)
        {
            $_characters = $account->characters();

            $characters = [];

            foreach ($_characters as $_character)
            {
                $character = new \stdClass;
                $character->name = $_character->Name;
                $character->level = $_character->level();
                $character->classe = $_character->classe();
                $character->lookLink = url('forge/player', [$_character->Id, 'full', 1, 150, 220]);
                $characters[] = $character;
            }

            return response()->json(['characters' => $characters]);
        }

        return $this->error(401, "id compte invalide");
    }
}
