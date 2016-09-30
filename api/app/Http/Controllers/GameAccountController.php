<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Exceptions\GenericException;

use App\User;
use App\Account;
use App\Transfert;
use App\Helpers\Utils;

use Validator;
use Auth;
use \Cache;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\TransferException;

class GameAccountController extends Controller
{
    const TICKET_LENGTH = 32;

    private function isAccountOwnedByMe($server, $accountId)
    {
        return Account::on($server . '_auth')->where('Id', $accountId)->where('Email', Auth::user()->email)->first();
    }

    private function isServerExist($server)
    {
        if (!in_array($server, config('dofus.servers')))
        {
            return false;
        }

        return true;
    }

    public function create()
    {
        return view('gameaccount/create');
    }

    public function store(Request $request)
    {
        $server = $request->input('server');

        if (!$this->isServerExist($server))
        {
            return redirect()->back()->withErrors(['server' => 'Le serveur sélectionné est invalide.'])->withInput();
        }

        if (count(Auth::user()->accounts($server)) >= config('dofus.accounts_limit'))
        {
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

        if ($validator->fails())
        {
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
        
        Cache::forget('accounts_' . Auth::user()->id);

        $request->session()->flash('notify', ['type' => 'success', 'message' => "Vous pouvez dés à présent jouer avec le nouveau compte de jeu !"]);

        return redirect()->route('gameaccount.view', [$account->server, $account->Id]);
    }

    public function view($server, $accountId)
    {
        if (!$this->isServerExist($server))
        {
            throw new GenericException('invalid_server', $server);
        }

        if (!$this->isAccountOwnedByMe($server, $accountId))
        {
            throw new GenericException('not_account_owner');
        }

        $account = Account::on($server . '_auth')->where('Id', $accountId)->first();
        $account->server = $server;

        return view('gameaccount/view', ['account' => $account]);
    }

    public function edit(Request $request, $server, $accountId)
    {
        if (!$this->isServerExist($server))
        {
            throw new GenericException('invalid_server', $server);
        }

        if (!$this->isAccountOwnedByMe($server, $accountId))
        {
            throw new GenericException('not_account_owner');
        }

        $account = Account::on($server . '_auth')->where('Id', $accountId)->first();
        $account->server = $server;

        if ($request->all())
        {
            $rules = Account::$rules['update-password'];
            $rules['passwordOld'] = str_replace('{PASSWORD}', $account->PasswordHash, $rules['passwordOld']);

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $account->PasswordHash = md5($request->input('password'));
            $account->save();

            $request->session()->flash('notify', ['type' => 'success', 'message' => "Mot de passe mis à jour."]);
            return redirect()->route('gameaccount.view', [$account->server, $account->Id]);
        }

        return view('gameaccount/edit', ['account' => $account]);
    }

    public function transfert(Request $request, $server, $accountId)
    {
        if (!$this->isServerExist($server))
        {
            throw new GenericException('invalid_server', $server);
        }

        if (!$this->isAccountOwnedByMe($server, $accountId))
        {
            throw new GenericException('not_account_owner');
        }

        $account = Account::on($server . '_auth')->where('Id', $accountId)->first();
        $account->server = $server;

        if ($request->all())
        {
            $ogrines = str_replace(' ', '', $request->input('ogrines'));

            $validator = Validator::make([ 'ogrines' => $ogrines ], [ 'ogrines' => 'required|integer|min:0|max:' . Auth::user()->points ]);

            if ($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $transfert = new Transfert;
            $transfert->account_id = $accountId;
            $transfert->server     = $server;
            $transfert->state      = Transfert::IN_PROGRESS;
            $transfert->amount     = $ogrines;
            $transfert->save();

            Auth::user()->points -= $ogrines;
            Auth::user()->save();

            $api = config('dofus.details')[$server];
            $success = false;

            try
            {
                $client = new Client();
                $res = $client->request('PUT', "http://{$api->ip}:{$api->port}/account/$accountId/addtokens/$ogrines", [
                    'headers' => [
                        'APIKey' => config('dofus.api_key')
                    ],
                    'timeout' => 10, // seconds
                ]);

                if ($res->getStatusCode() == 200)
                {
                    $transfert->state = Transfert::OK_API;
                    $transfert->save();

                    $success = true;
                }

            }
            catch (ClientException $e)
            {
                if ($e->getResponse()->getStatusCode() == 404)
                {
                    //$account->addPoints($ogrines);
                    //$account->save();
                    
                    Toastr::add('warning', 'Transfert hors ligne désactivé, connectez-vous en jeu puis re-tentez le transfert');

                    $transfert->state = Transfert::FAIL;
                    $transfert->save();

                    $success = false;
                }
                else
                {
                    $transfert->state = Transfert::FAIL;
                    $transfert->save();

                    $success = false;
                }
            }
            catch (TransferException $e)
            {
                $transfert->state = Transfert::FAIL;
                $transfert->save();

                $success = false;
            }

            Cache::forget('transferts_' . $server . '_' . $accountId);

            if ($success)
            {
                $request->session()->flash('notify', ['type' => 'success', 'message' => "Vous venez de transférer ". Utils::format_price($ogrines, ' ') ." Ogrines sur votre compte " . $account->Nickname]);
            }
            else
            {
                $request->session()->flash('notify', ['type' => 'error', 'message' => "Le transfert a échoué, merci de contacter le support !"]);
            }

            return redirect()->route('gameaccount.view', [$account->server, $account->Id]);
        }

        return view('gameaccount/transfert', ['account' => $account]);
    }
}
