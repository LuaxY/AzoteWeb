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
        $report = [];

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
            $newKey = $keyType . '|' . $keyTextFormated;

            if ($keyType == 'message')
            {
                $report[$newKey] = $value;
                continue;
            }

            if ($keyType == 'image')
            {
                // TODO protect this with validator
                $imageName = time() . '.' . $value->getClientOriginalExtension();
                $value->move(public_path() . "/imgs/uploads", $imageName);
                $report[$newKey] = $imageName;
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
                $report[$newKey] = (int)$valueText;
            }

            if ($keyType == 'character')
            {
                $report[$newKey] = (int)$valueText;
            }

            if ($keyType == 'server')
            {
                // TODO if $server exist
                $server = $valueText;
                $report[$newKey] = $server;
            }

            if ($keyType == 'text')
            {
                $report[$newKey] = $valueText;
            }
        }

        echo $this->generateHtmlReport($report);

        dd($report);
        dd($request->all());
    }

    private function generateHtmlReport($report)
    {
        $html = "";
        $server = 'sigma';
        $accountId = 0;
        $characterId = 0;

        foreach ($report as $key => $value)
        {
            list($type, $key) = explode('|', $key);

            $html .= "<b>$key</b> : ";

            switch ($type)
            {
                case 'text':
                case 'message':
                    $html .= $value;
                    break;
                case 'image':
                    $html .= "<br><img src='/imgs/uploads/$value' height='200'>";
                    break;
                case 'server':
                    $server = $value;
                    $html .= ucfirst($server);
                    break;
                case 'account':
                    $accountId = $value;
                    $account = Account::on($server . '_auth')->where('Id', $accountId)->where('Email', Auth::user()->email)->first();

                    if ($account)
                    {
                        $html .= $account->Nickname;
                    }
                    else
                    {
                        $html .= "Not trouvé";
                    }
                    break;
                case 'character':
                    $characterId = $value;
                    $character = ModelCustom::hasOneOnOneServer('world', $server, Character::class, 'Id', $characterId);;

                    if (!$this->isCharacterOwnedByMe($server, $accountId, $characterId))
                    {
                        $html .= "Non trouvé (#1)";
                    }
                    elseif ($character)
                    {
                        $html .= $character->Name;
                    }
                    else
                    {
                        $html .= "Non trouvé (#2)";
                    }
                    break;
                default:
                    $html .= "INVALID";
                    break;
            }

            $html .= "<br>\n";
        }

        return $html;
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
