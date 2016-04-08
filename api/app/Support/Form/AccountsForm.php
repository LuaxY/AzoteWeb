<?php

namespace App\Support\Form;

use App\Account;
use Auth;

class AccountsForm implements IForm
{
    public static function render($name, $data, $params)
    {
        $html  = "$name: <select name=\"$name\">\n";
        $html .= "<option value=\"r|null\"></option>\n";

        $child = (isset($data->child) ? $data->child : false);
        $accounts = Account::where('Email', Auth::user()->email)->get();

        foreach ($accounts as $account)
        {
            $html .= "<option value=\"c|$child|$account->Id\">$account->Nickname</option>\n";
        }

        $html .= "</select><br>\n";

        return $html;
    }
}
