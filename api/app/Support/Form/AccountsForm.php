<?php

namespace App\Support\Form;

use App\Account;
use Auth;

class AccountsForm implements IForm
{
    public static function render($name, $data, $params)
    {
        $child = (isset($data->child) ? $data->child : false);
        $accounts = Auth::user()->accounts('sigma');

        if (count($accounts) > 0)
        {
            return view('support.form.accounts', compact('name', 'child', 'accounts'));
        }

        return "Aucun compte";
    }
}
