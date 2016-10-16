<?php

namespace App\Http\Controllers;

use App\Account;
use App\Character;
use App\Exceptions\GenericException;
use App\RecoverCharacter;
use App\WorldCharacter;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class CharactersController extends Controller
{
    private function isCharacterOwnedByMe($server, $accountId, $characterId)
    {
        $account = Account::on($server . '_auth')->where('Id', $accountId)->where('Email', Auth::user()->email)->first();
        if ($account)
        {
            $account->server = $server;
            $characters = $account->characters(1);
            if ($characters)
            {
                foreach ($characters as $character)
                {
                    if ($characterId == $character->Id)
                        return true;
                }
                return false;
            }
        }
    }

    private function isCharacterDeletedOwnedByMe($server, $accountId, $characterId)
    {
        $account = Account::on($server . '_auth')->where('Id', $accountId)->where('Email', Auth::user()->email)->first();
        if ($account)
        {
            $account->server = $server;
            $characters = $account->DeletedCharacters(1);
            if ($characters)
            {
                foreach ($characters as $character)
                {
                    if ($characterId == $character->Id)
                        return true;
                }
                return false;
            }
        }
    }

    private function isServerExist($server)
    {
        if (!in_array($server, config('dofus.servers')))
        {
            return false;
        }

        return true;
    }

    public function view($server, $accountId, $characterId)
    {
        request()->session()->flash('notify', ['type' => 'warning', 'message' => "Affichage des personnages prochainement"]);
        return redirect()->back();
    }

    public function recover(Request $request, $server, $accountId, $characterId)
    {
        if (!$this->isServerExist($server))
        {
            throw new GenericException('invalid_server', $server);
        }

        if(!$this->isCharacterDeletedOwnedByMe($server, $accountId, $characterId))
        {
            throw new GenericException('owner_error');
        }

        $account = Account::on($server . '_auth')->where('Id', $accountId)->first();
        $account->server = $server;

        if ($request->all())
        {
            $deletedCharactersCount = count($account->DeletedCharacters(1));
            $character = Character::on($server . '_world')->where('Id', $characterId)->first();
            $price = $character->recoverPrice();
            $newname = $request->input('nickname');
            $oldname = $character->Name;

            $validator = Validator::make(['price_amount' => $price, 'nickname' => $newname], ['price_amount' => 'required|integer|min:1|max:' . Auth::user()->points, 'nickname' => array('required', 'regex:/^[A-Z][a-z]{2,9}(?:-[A-Za-z][a-z]{2,9}|[a-z]{1,10})$/')]);

            if ($validator->fails())
            {
                if($validator->errors()->has('price_amount'))
                {
                    $validator->errors()->add('price', "Vous n'avez pas assez d'ogrines pour faire cet achat. Pour en acheter, rendez-vous sur notre Boutique.");
                }
                return redirect()->back()->withErrors($validator)->withInput();
            }

            if(!Character::on($server . '_world')->where([['Name', '=', $newname], ['Id', '!=', $characterId],])->first())
            {
                if((WorldCharacter::on($server . '_auth')->where('AccountId', $accountId)->count() - $deletedCharactersCount) < config('dofus.characters_limit'))
                {
                    Auth::user()->points -= $price;
                    Auth::user()->save();
                    $character->DeletedDate = null;
                    $character->Name = $newname;
                    $character->save();

                    $recoverCharacter = new RecoverCharacter;
                    $recoverCharacter->user_id = Auth::user()->id;
                    $recoverCharacter->points = $price;
                    $recoverCharacter->characterId = $characterId;
                    $recoverCharacter->oldName = $oldname;
                    $recoverCharacter->newName = $newname;
                    $recoverCharacter->save();

                    Cache::forget('characters_' . $server . '_' . $accountId);
                    Cache::forget('characters_deleted_' . $server . '_' . $accountId);

                    $request->session()->flash('notify', ['type' => 'success', 'message' => "Le personnage a correctement été récupéré. Bon jeu!"]);
                    return redirect()->route('gameaccount.view', [$server, $accountId]);
                }
                else
                {
                    $request->session()->flash('notify', ['type' => 'error', 'message' => "Vous avez trop de personnages sur ce compte. Le maximum est de ".config('dofus.characters_limit')."."]);
                    return redirect()->back();
                }
            }
            else
            {
                $request->session()->flash('notify', ['type' => 'error', 'message' => "Le pseudo de ce personnage est déjà utilisé. Veuillez en choisir un autre ou le libérer en jeu"]);
                return redirect()->back()->withErrors(['nickname' => 'Le pseudo de ce personnage est déjà utilisé. Veuillez en choisir un autre ou le libérer en jeu'])->withInput();
            }
        }

        $character = Character::on($server . '_world')->where('Id', $characterId)->first();
        return view('gameaccount.recover', compact('account', 'character'));
    }
}
