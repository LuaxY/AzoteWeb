<?php

namespace App\Support\Form;

use App\Account;
use Auth;

class CharactersForm implements IForm
{
    public static function render($name, $data, $params)
    {
        $html = "$name:<br>\n";
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
                $html .= "<div class=\"character\"><input type=\"radio\" name=\"$name\" id=\"{$character->Id}\" value=\"{$character->Id}\"> <label for=\"{$character->Id}\"><img src=\"http://api.dofus.lan/forge/player/{$character->Id}/face/2/50/50\"> {$character->Name} - {$character->classe()} niveau {$character->level()}</label></div>\n";
            }
        }

        //$html .= "</select><br>\n";
        $html .= "<br>\n";

        return $html;
    }
}
