<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Support\Support;
use App\Account;
use App\Character;

class SupportController extends Controller
{
    public function create()
    {
        $html = Support::generateForm('support');
        return view('support/create', ['html' => $html]);
    }

    public function child($child, $params = false)
    {
        return Support::generateForm($child, $params);
    }

    public function store(Request $request)
    {
        $inputs = $request->all();
        $html = "";

        foreach ($inputs as $key => $value)
        {
            $keyData = explode('|', $key);
            $keyType =  $keyData[0];
            $keyText =  $keyData[1];

            $valueData = explode('|', $value);
            $valueType = $valueData[0];
            $valueText = $valueData[1];

            if ($keyType == 'special')
            {
                if ($keyText == 'account')
                {
                    // TODO convert to view
                    // TODO is $accountId owned by me ?

                    $accountId = (int)$valueText;
                    $account = Account::on('sigma_world')->find($accountId);

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
                    // TODO is $characterId owned by me ?

                    $characterId = (int)$valueText;
                    $character = Character::on('sigma_world')->find($characterId);

                    if ($character)
                    {
                        $html .= "<b>Compte</b> : {$character->Name}<br>\n";
                    }
                    else
                    {
                        $html .= "<b>Compte</b> : Not found<br>\n";
                    }
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
}
