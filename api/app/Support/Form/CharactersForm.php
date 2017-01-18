<?php

namespace App\Support\Form;

use App\ModelCustom;
use App\Account;
use Auth;

class CharactersForm implements IForm
{
    public static function render($name, $data, $params)
    {
        $child  = isset($data->child) ? $data->child : false;
        $server = isset($params['server']) ? $params['server'] : 'sigma';
        $accountId = isset($params['account']) ? $params['account'] : 0;

        $account = ModelCustom::hasOneOnOneServer('auth', $server, Account::class, 'Id', $accountId);

        if ($account)
        {
            $characters = $account->characters(false, true);

            if (count($characters) > 0)
            {
                return view('support.form.characters', compact('name', 'child', 'characters'));
            }

            return "Aucun personnages<br>";
        }

        return "Compte introuvable<br>";
    }
}
