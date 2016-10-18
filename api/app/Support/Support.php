<?php

namespace App\Support;

use App\Support\Form\TextForm;
use App\Support\Form\RadioForm;
use App\Support\Form\SelectForm;
use App\Support\Form\TextareaForm;
use App\Support\Form\AccountsForm;
use App\Support\Form\CharactersForm;
use App\Support\Form\SubmitForm;

class Support
{
    public static function generateForm($child, $params = false)
    {
        // TODO: protect this code !!!
        $json = json_decode(file_get_contents("support_files/$child.json"));
        $html = "";

        foreach ($json->fields as $field) {
            $type = $field->type;
            $name = $field->name;
            $data = (isset($field->data) ? $field->data : false);

            $form = false;

            switch ($type) {
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
                case 'textarea':
                    $form = new TextareaForm;
                    break;
                case 'accounts':
                    $form = new AccountsForm;
                    break;
                case 'characters':
                    $form = new CharactersForm;
                    break;
                case 'submit':
                    $form = new SubmitForm;
                    break;
            }

            if ($form) {
                $html .= $form->render($name, $data, $params);
            }
        }

        return $html;
    }
}
