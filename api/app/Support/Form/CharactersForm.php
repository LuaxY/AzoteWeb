<?php

namespace App\Support\Form;

use App\Account;
use Auth;

class CharactersForm implements IForm
{
    public static function render($name, $data, $params)
    {
        $html = "";
        //$html  = "$name: <select name=\"$name\">\n";
        //$html .= "<option value=\"r|null\"></option>\n";

        $child = (isset($data->child) ? $data->child : false);
        $account = Account::where('Id', $params)->where('Email', Auth::user()->email)->first();

        if ($account)
        {
            $characters = $account->characters();

            foreach ($characters as $character)
            {
                //$html .= "<option value=\"c|$child|$character->Id\">$character->Name</option>\n";
                $html .= "<img src=\"http://api.dofus.lan/forge/player/{$character->Id}/face/2/50/50\">\n";
            }
        }

        //$html .= "</select><br>\n";
        $html .= "<br>\n";

        return $html;
    }
}
