<?php

namespace App\Support\Form;

use App\Account;
use Auth;

class CharactersForm implements IForm
{
    public static function render($name, $data, $params)
    {
        $child = (isset($data->child) ? $data->child : false);
        $account = Auth::user()->accounts('sigma')[0];

        if ($account)
        {
            $characters = $account->characters(false, true);

            return view('support.form.characters', compact('name', 'child', 'characters'));
        }

        return "Compte introuvable";
    }
}
