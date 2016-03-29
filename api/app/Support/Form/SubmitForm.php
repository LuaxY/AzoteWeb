<?php

namespace App\Support\Form;

class SubmitForm implements IForm
{
    public static function render($data)
    {
        return "<input type=\"submit\" value=\"{$data['value']}\"><br>\n";
    }
}
