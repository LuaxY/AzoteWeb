<?php

namespace App\Http\Controllers;

use App\Account;
use App\Character;
use App\Exceptions\GenericException;
use App\RecoverCharacter;
use App\WorldCharacter;
use Illuminate\Http\Request;
use App\World;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\ItemPosition;
use App\Services\Stump;

class CharactersController extends Controller
{
    public function view(Request $request, $server, $characterId, $characterName)
    {
        if (!World::isServerExist($server)) {
            throw new GenericException('invalid_server', $server);
        }

        $character = Cache::remember('character_view_'.$server.'_'.$characterId, 120, function () use($server, $characterId, $characterName) {
               return Character::on($server . '_world')->where('Id', $characterId)->where('Name', $characterName)->first();
        });
        if(!$character)
            abort(404);
        
        $character->server = $server;
        return view('gameaccount.character.view', compact('character', 'server'));
    }

    public function caracteristics(Request $request, $server, $characterId, $characterName)
    {
        if (!World::isServerExist($server)) {
            throw new GenericException('invalid_server', $server);
        }

        $character = Cache::remember('character_view_'.$server.'_'.$characterId, 120, function () use($server, $characterId, $characterName) {
               return Character::on($server . '_world')->where('Id', $characterId)->where('Name', $characterName)->first();
        });
        if(!$character)
            abort(404);
        
        $character->server = $server;

        $json = Cache::remember('character_inventory_json_'.$server.'_'.$characterId, 10, function () use ($server, $character){
            $json = Stump::get($server, "/Character/$character->Id/Inventory");
            //$json = file_get_contents('uploads/tests/api.json');
            return $json;
        });
        if(!$json)
        {
          $request->session()->flash('notify', ['type' => 'warning', 'message' => "Affichage impossible actuellement"]);
          return redirect()->back();
        }

        $itemsall = Cache::remember('character_inventory_'.$server.'_'.$characterId, 10, function () use($json,$server, $character) {
               $itemsall = array('left' => [], 'right' => [], 'bottom' => []);
               $items = json_decode($json);
                foreach($items as $item)
                {
                    switch($item->Position)
                    {
                        case ItemPosition::ACCESSORY_POSITION_SHIELD:
                        case ItemPosition::ACCESSORY_POSITION_AMULET:      
                        case ItemPosition::INVENTORY_POSITION_RING_LEFT:
                        case ItemPosition::ACCESSORY_POSITION_CAPE:
                        case ItemPosition::ACCESSORY_POSITION_BOOTS:
                            array_push($itemsall['left'], $item);
                        break;
                        case ItemPosition::ACCESSORY_POSITION_WEAPON:
                        case ItemPosition::ACCESSORY_POSITION_HAT:      
                        case ItemPosition::INVENTORY_POSITION_RING_RIGHT:
                        case ItemPosition::ACCESSORY_POSITION_BELT:
                        case ItemPosition::ACCESSORY_POSITION_PETS:
                            array_push($itemsall['right'], $item);
                        break;
                        case ItemPosition::INVENTORY_POSITION_DOFUS_1:
                        case ItemPosition::INVENTORY_POSITION_DOFUS_2:
                        case ItemPosition::INVENTORY_POSITION_DOFUS_3:
                        case ItemPosition::INVENTORY_POSITION_DOFUS_4:
                        case ItemPosition::INVENTORY_POSITION_DOFUS_5:
                        case ItemPosition::INVENTORY_POSITION_DOFUS_6:
                            array_push($itemsall['bottom'], $item);
                        break;
                        default:
                        break;
                    }
                }
                return $itemsall;
        });
       
        $itemsleft = collect($itemsall['left']);
        $itemsright = collect($itemsall['right']);
        $itemsbottom = collect($itemsall['bottom']);

        $spells = Cache::remember('character_spells'.$server.'_'.$characterId, 10, function () use($character){
            return $character->spells()->get();
        });

        return view('gameaccount.character.caracteristics', compact('json', 'character', 'server', 'spells', 'itemsleft', 'itemsright', 'itemsbottom'));
    }

    public function recover(Request $request, $server, $accountId, $characterId)
    {
        if (!World::isServerExist($server)) {
            throw new GenericException('invalid_server', $server);
        }

        if (!World::isCharacterDeletedOwnedByMe($server, $accountId, $characterId)) {
            throw new GenericException('owner_error');
        }

        $account = Account::on($server . '_auth')->where('Id', $accountId)->first();
        $account->server = $server;

        if ($request->all()) {
            $deletedCharactersCount = count($account->DeletedCharacters(1));
            $character = Character::on($server . '_world')->where('Id', $characterId)->first();
            $price = $character->recoverPrice();
            $newname = $request->input('nickname');
            $oldname = $character->Name;

            $validator = Validator::make(['price_amount' => $price, 'nickname' => $newname], ['price_amount' => 'required|integer|min:1|max:' . Auth::user()->points, 'nickname' => ['required', 'regex:/^[A-Z][a-z]{2,9}(?:-[A-Za-z][a-z]{2,9}|[a-z]{1,10})$/']]);

            if ($validator->fails()) {
                if ($validator->errors()->has('price_amount')) {
                    $validator->errors()->add('price', "Vous n'avez pas assez d'ogrines pour faire cet achat. Pour en acheter, rendez-vous sur notre Boutique.");
                }
                return redirect()->back()->withErrors($validator)->withInput();
            }

            if (!Character::on($server . '_world')->where([['Name', '=', $newname], ['Id', '!=', $characterId],])->first()) {
                if ((WorldCharacter::on($server . '_auth')->where('AccountId', $accountId)->count() - $deletedCharactersCount) < config('dofus.characters_limit')) {
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
                } else {
                    $request->session()->flash('notify', ['type' => 'error', 'message' => "Vous avez trop de personnages sur ce compte. Le maximum est de ".config('dofus.characters_limit')."."]);
                    return redirect()->back();
                }
            } else {
                $request->session()->flash('notify', ['type' => 'error', 'message' => "Le pseudo de ce personnage est déjà utilisé. Veuillez en choisir un autre ou le libérer en jeu"]);
                return redirect()->back()->withErrors(['nickname' => 'Le pseudo de ce personnage est déjà utilisé. Veuillez en choisir un autre ou le libérer en jeu'])->withInput();
            }
        }

        $character = Character::on($server . '_world')->where('Id', $characterId)->first();
        return view('gameaccount.recover', compact('account', 'character'));
    }
}
