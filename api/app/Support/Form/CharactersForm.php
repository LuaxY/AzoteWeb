<?php

namespace App\Support\Form;

use App\Account;
use Auth;

class CharactersForm implements IForm
{
    public static function render($name, $data, $params)
    {
        $child = (isset($data->child) ? $data->child : false);
        $account = Account::where('Id', $params)->where('Email', Auth::user()->email)->first();

        if ($account)
        {
            $characters = $account->characters();

            return view('support/form/character', compact('name', 'characters'));
        }

        return "Compte introuvable";
    }
}
