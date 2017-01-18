<?php

namespace App\Support\Form;

use App\Account;
use Auth;

class AccountsForm implements IForm
{
    public static function render($name, $data, $params)
    {
        $child  = isset($data->child) ? $data->child : false;
        $server = isset($params['server']) ? $params['server'] : 'sigma';

        $accounts = Auth::user()->accounts($server);

        if (count($accounts) > 0)
        {
            return view('support.form.accounts', compact('name', 'child', 'accounts'));
        }

        return "Aucun comptes<br>";
    }
}
