<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Exceptions\GenericException;

use App\User;
use App\Account;
use App\Transfert;
use App\World;
use App\Gift;
use App\Helpers\Utils;
use App\Services\Stump;

use Validator;
use Auth;
use \Cache;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7;

class GameAccountController extends Controller
{
    const TICKET_LENGTH = 32;

    private function isAccountOwnedByMe($server, $accountId)
    {
        return Account::on($server . '_auth')->where('Id', $accountId)->where('Email', Auth::user()->email)->first();
    }

    private function isServerExist($server)
    {
        if (!in_array($server, config('dofus.servers'))) {
            return false;
        }

        return true;
    }

    public function create()
    {
        return view('gameaccount.create');
    }

    public function store(Request $request)
    {
        $server = $request->input('server');

        if (!$this->isServerExist($server)) {
            return redirect()->back()->withErrors(['server' => 'Le serveur sélectionné est invalide.'])->withInput();
        }

        if (count(Auth::user()->accounts($server)) >= config('dofus.accounts_limit')) {
            $request->session()->flash('notify', ['type' => 'error', 'message' => "Vous avez atteint la limite de compte possible sur ce serveur !"]);
            return redirect()->back()->withInput();
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
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $account = new Account;
        $account->changeConnection($database);
        $account->Login           = $request->input('login');
        $account->PasswordHash    = md5($request->input('password'));
        $account->Nickname        = $request->input('nickname');
        $account->UserGroupId     = 1;
        $account->Ticket          = strtoupper(str_random(self::TICKET_LENGTH));
        $account->SecretQuestion  = 'Code secret disponible sur le site';
        $account->SecretAnswer    = sprintf('%04d', rand(0000, 9999));
        $account->Lang            = 'fr';
        $account->Email           = Auth::user()->email;
        $account->CreationDate    = date('Y-m-d H:i:s');
        $account->SubscriptionEnd = '2016-01-01 00:00:00';
        $account->IsJailed        = false;
        $account->IsBanned        = false;
        $account->server          = $server;
        $account->save();

        Cache::forget('accounts_' . $server . '_' . Auth::user()->id);
        Cache::forget('accounts_' . Auth::user()->id);

        $request->session()->flash('notify', ['type' => 'success', 'message' => "Vous pouvez dés à présent jouer avec le nouveau compte de jeu !"]);

        return redirect()->route('gameaccount.view', [$account->server, $account->Id]);
    }

    public function view($server, $accountId)
    {
        if (!$this->isServerExist($server)) {
            throw new GenericException('invalid_server', $server);
        }

        if (!$this->isAccountOwnedByMe($server, $accountId)) {
            throw new GenericException('not_account_owner');
        }

        $account = Account::on($server . '_auth')->where('Id', $accountId)->first();
        $account->server = $server;

        return view('gameaccount.view', ['account' => $account]);
    }

    public function edit(Request $request, $server, $accountId)
    {
        if (!$this->isServerExist($server)) {
            throw new GenericException('invalid_server', $server);
        }

        if (!$this->isAccountOwnedByMe($server, $accountId)) {
            throw new GenericException('not_account_owner');
        }

        $account = Account::on($server . '_auth')->where('Id', $accountId)->first();
        $account->server = $server;

        if ($request->all()) {
            $validator = Validator::make($request->all(), Account::$rules['update-password']);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $account->PasswordHash = md5($request->input('password'));
            $account->save();

            $request->session()->flash('notify', ['type' => 'success', 'message' => "Mot de passe mis à jour."]);
            return redirect()->route('gameaccount.view', [$account->server, $account->Id]);
        }

        return view('gameaccount.edit', ['account' => $account]);
    }

    public function transfert(Request $request, $server, $accountId)
    {
        if (!$this->isServerExist($server)) {
            throw new GenericException('invalid_server', $server);
        }

        if (!$this->isAccountOwnedByMe($server, $accountId)) {
            throw new GenericException('not_account_owner');
        }

        $account = Account::on($server . '_auth')->where('Id', $accountId)->first();
        $account->server = $server;

        $world = World::on($server . '_auth')->where('Name', strtoupper($server))->first();

        if (!$world || !$world->isOnline()) {
            return view('gameaccount.maintenance', ['account' => $account]);
        }

        if ($request->all()) {
            $ogrines = str_replace(' ', '', $request->input('ogrines'));

            $validator = Validator::make([ 'ogrines' => $ogrines ], [ 'ogrines' => 'required|integer|min:1|max:' . Auth::user()->points ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $success = Stump::transfert($server, $accountId, "Ogrines", $ogrines, "/account/$accountId/addtokens/$ogrines", function () use ($ogrines) {
                Auth::user()->points -= $ogrines;
                Auth::user()->save();
            });

            Cache::forget('transferts_' . $server . '_' . $accountId);
            Cache::forget('transferts_' . $server . '_' . $accountId . '_10');

            if ($success) {
                $request->session()->flash('notify', ['type' => 'success', 'message' => "Vous venez de transférer ". Utils::format_price($ogrines, ' ') ." Ogrines sur votre compte " . $account->Nickname]);
            } else {
                $request->session()->flash('notify', ['type' => 'error', 'message' => "Le transfert a échoué !"]);
            }

            return redirect()->route('gameaccount.view', [$account->server, $account->Id]);
        }

        return view('gameaccount.transfert', ['account' => $account]);
    }

    public function gifts(Request $request, $server, $accountId)
    {
        if (!$this->isServerExist($server)) {
            throw new GenericException('invalid_server', $server);
        }

        if (!$this->isAccountOwnedByMe($server, $accountId)) {
            throw new GenericException('not_account_owner');
        }

        $account = Account::on($server . '_auth')->where('Id', $accountId)->first();
        $account->server = $server;

        $world = World::on($server . '_auth')->where('Name', strtoupper($server))->first();

        if (!$world || !$world->isOnline()) {
            return view('gameaccount.maintenance', ['account' => $account]);
        }

        if ($request->all()) {
        // is gift owned by me and not already delivred ?
            $gift = Gift::where('id', $request->input('gift_id'))->where('delivred', false)->where('user_id', Auth::user()->id)->first();

            if (!$gift) {
                return redirect()->back()->withErrors(['gift' => 'Cadeau selectionné invalide.'])->withInput();
            }

            $success = Stump::transfert($server, $accountId, $gift->item_id, 1, "/account/$accountId/bank/{$gift->item_id}/1", function () use ($gift, $accountId) {
                $gift->delivred   = true;
                $gift->account_id = $accountId;
                $gift->save();
            });

            Cache::forget('transferts_' . $server . '_' . $accountId);
            Cache::forget('transferts_' . $server . '_' . $accountId . '_10');

            Cache::forget('gifts_available_' . Auth::user()->id);
            Cache::forget('gifts_' . Auth::user()->id);

            if ($success) {
                $request->session()->flash('notify', ['type' => 'success', 'message' => "Vous venez de transférer 1x ". $gift->item()->name() ." dans votre banque sur votre compte " . $account->Nickname]);
            } else {
                $request->session()->flash('notify', ['type' => 'error', 'message' => "Le transfert a échoué !"]);
            }

            return redirect()->route('gameaccount.view', [$account->server, $account->Id]);
        }

        return view('gameaccount.gifts', ['account' => $account]);
    }
}
