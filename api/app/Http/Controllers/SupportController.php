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
        return view('support/new', ['html' => $html]);
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

            $keyTextFormated = str_replace('_', ' ', $keyText);
            $html .= "<b>$keyTextFormated</b> : ";

            if ($keyType == 'message')
            {
                $html .= "$value<br>\n";
                continue;
            }

            if ($keyType == 'file')
            {
                // TODO protect this with validator
                $imageName = time() . '.' . $value->getClientOriginalExtension();
                $value->move(public_path() . "/imgs/uploads", $imageName);
                $html .= "<br><img src='/imgs/uploads/$imageName' height='200'><br>\n";
                continue;
            }

            $valueData = explode('|', $value);

            if (count($valueData) < 2)
            {
                continue;
            }

            $valueType = $valueData[0];
            $valueText = $valueData[1];

            if ($keyType == 'account')
            {
                // TODO convert to view

                $accountId = (int)$valueText;
                $account = Account::on($server . '_auth')->where('Id', $accountId)->where('Email', Auth::user()->email)->first();

                if ($account)
                {
                    $html .= $account->Nickname;
                }
                else
                {
                    $html .= "Not trouvé";
                }
            }

            if ($keyType == 'character')
            {
                // TODO convert to view

                $characterId = (int)$valueText;
                $character = ModelCustom::hasOneOnOneServer('world', $server, Character::class, 'Id', $characterId);;

                if (!$this->isCharacterOwnedByMe($server, $accountId, $characterId))
                {
                    $html .= "Non trouvé (#1)";
                    //continue;
                }
                elseif ($character)
                {
                    $html .= $character->Name;
                }
                else
                {
                    $html .= "Non trouvé (#2)";
                }
            }

            if ($keyType == 'server')
            {
                // TODO convert to view
                // TODO if $server exist

                $server = $valueText;

                $html .= ucfirst($server);
            }

            if ($keyType == 'text')
            {
                $html .= $valueText;
            }

            $html .= "<br>\n";
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
