<?php

namespace App\Support;

use App\Support\Form\TextForm;
use App\Support\Form\RadioForm;
use App\Support\Form\SelectForm;
use App\Support\Form\SubmitForm;

class Support
{
    public static function generateForm()
    {
        $html = "";

        $html .= TextForm::render(['name' => 'Nom de compte', 'required' => true]);
        $html .= RadioForm::render(['name' => 'Personnage', 'values' => ['Perso1', 'Perso2']]);
        $html .= SelectForm::render(['name' => 'Sort', 'values' => ['Sort1', 'Sort2']]);
        $html .= SubmitForm::render(['value' => 'Envoyer']);

        return $html;
    }
}
