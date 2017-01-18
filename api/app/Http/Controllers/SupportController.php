<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Support\Support;
use App\ModelCustom;
use App\Account;
use App\Character;
use Auth;

class SupportController extends Controller
{
    public function create()
    {
        $html = Support::generateForm('support');
        return view('support/create', ['html' => $html]);
    }

    public function child(Request $request, $child, $params = false)
    {
        return Support::generateForm($child, $params, $request->all());
    }

    public function store(Request $request)
    {
        $inputs = $request->all();
        $html = "";

        $server = 'sigma';
        $accountId = 0;
        $characterId = 0;

        foreach ($inputs as $key => $value)
        {
            $keyData = explode('|', $key);

            if (count($keyData) < 2)
            {
                continue;
            }

            $keyType =  $keyData[0];
            $keyText =  $keyData[1];

            $valueData = explode('|', $value);
            $valueType = $valueData[0];
            $valueText = $valueData[1];

            if (count($valueData) < 2)
            {
                continue;
            }

            if ($keyType == 'special')
            {
                if ($keyText == 'account')
                {
                    // TODO convert to view

                    $accountId = (int)$valueText;
                    $account = Account::on($server . '_auth')->where('Id', $accountId)->where('Email', Auth::user()->email)->first();

                    if ($account)
                    {
                        $html .= "<b>Compte</b> : {$account->Nickname}<br>\n";
                    }
                    else
                    {
                        $html .= "<b>Compte</b> : Not found<br>\n";
                    }
                }

                if ($keyText == 'character')
                {
                    // TODO convert to view

                    $characterId = (int)$valueText;
                    $character = ModelCustom::hasOneOnOneServer('world', $server, Character::class, 'Id', $characterId);;

                    if (!$this->isCharacterOwnedByMe($server, $accountId, $characterId))
                    {
                        $html .= "<b>Personnage</b> : Not found<br>\n";
                        continue;
                    }

                    if ($character)
                    {
                        $html .= "<b>Personnage</b> : {$character->Name}<br>\n";
                    }
                    else
                    {
                        $html .= "<b>Personnage</b> : Not found<br>\n";
                    }
                }

                if ($keyText == 'server')
                {
                    // TODO convert to view
                    // TODO if $server exist

                    $server = $valueText;

                    $html .= "<b>Serveur</b> : ".ucfirst($server)."<br>\n";
                }

                continue;
            }

            if ($keyType == 'text')
            {
                $keyTextFormated = str_replace('_', ' ', $keyText);
                $html .= "<b>$keyTextFormated</b> : ";
            }

            $html .= "$valueText<br>\n";
        }

        echo $html;

        dd($request->all());
    }

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
                    if ($character && $characterId == $character->Id)
                    {
                        return true;
                    }
                }

                return false;
            }
        }
    }
}
