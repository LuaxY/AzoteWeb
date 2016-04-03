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
        $json = json_decode(file_get_contents("support/game_bug.json"));
        $html = "";

        foreach ($json->fields as $field)
        {
            $type = $field->type;
            $name = $field->name;
            $data = (isset($field->data) ? $field->data : false);

            $form = false;

            switch($type)
            {
                case 'text':
                case 'integer':
                    $form = new TextForm;
                    break;
                case 'radio':
                    $form = new RadioForm;
                    break;
                case 'select':
                    $form = new SelectForm;
                    break;
                case 'submit':
                    $form = new SubmitForm;
                    break;
            }

            if ($form)
            {
                $html .= $form->render($name, $data);
            }
        }

        return $html;
    }
}
