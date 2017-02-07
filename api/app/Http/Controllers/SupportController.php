<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Support\Support;
use App\ModelCustom;
use App\Account;
use App\Character;
use App\SupportRequest;
use App\SupportTicket;
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

            if ($keyType == 'message' || $keyType == 'text')
            {
                $report[$newKey] = $value;
                continue;
            }

            if ($keyType == 'image')
            {
                if (!in_array($value->getClientOriginalExtension(), ['png', 'jpg', 'jpeg'])) continue;
                $imageName = time() . '.' . $value->getClientOriginalExtension();
                $value->move(public_path() . "/uploads/support", $imageName);
                $report[$newKey] = $imageName;
                continue;
            }

            if ($keyType == 'document')
            {
                $docName = $value->getClientOriginalName();
                $value->move(public_path() . "/uploads/support", $docName);
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
                $server = $valueText;
                $report[$newKey] = $server;
            }

            if ($keyType == 'select')
            {
                $report[$newKey] = $valueText;
            }
        }

        echo $this->generateHtmlReport($report);

        $supportRequest = new SupportRequest;
        $supportRequest->user_id  = Auth::user()->id;
        $supportRequest->state    = SupportRequest::OPEN;
        $supportRequest->category = isset($report['select|Ma demande concerne']) ? $report['select|Ma demande concerne'] : "Sans catégorie";
        $supportRequest->subject  = isset($report['text|Sujet']) ? $report['text|Sujet'] : "Sans sujet";
        $supportRequest->save();

        $supportTicket = new SupportTicket;
        $supportTicket->request_id = $supportRequest->id;
        $supportTicket->user_id    = Auth::user()->id;
        $supportTicket->data       = json_encode($report);
        $supportTicket->private    = false;
        $supportTicket->save();

        dd($report, json_encode($report), $request->all());
    }

    private function generateHtmlReport($report)
    {
        $html = "";
        $server = '';
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
                case 'select':
                    $html .= $value;
                    break;
                case 'image':
                    $html .= "<br><img src='/imgs/uploads/$value' height='200'>";
                    break;
                case 'server':
                    if (!$this->isServerExist($value))
                    {
                        $html .= "Non trouvé";
                    }
                    else
                    {
                        $server = $value;
                        $html .= ucfirst($server);
                    }
                    break;
                case 'account':
                    if ($server == '')
                    {
                        $html .= "Non trouvé";
                        break;
                    }

                    $accountId = $value;
                    $account = Account::on($server . '_auth')->where('Id', $accountId)->where('Email', Auth::user()->email)->first();

                    if ($account)
                    {
                        $html .= $account->Nickname;
                    }
                    else
                    {
                        $html .= "Non trouvé";
                    }
                    break;
                case 'character':
                    if ($server == '' || $accountId == 0)
                    {
                        $html .= "Non trouvé";
                        break;
                    }

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

    private function isServerExist($server)
    {
        if (!in_array($server, config('dofus.servers')))
        {
            return false;
        }

        return true;
    }
}
