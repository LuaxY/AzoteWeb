<?php

namespace App\Support\Form;

class SubmitForm implements IForm
{
    public static function render($name, $data, $params)
    {
        return "<input type=\"submit\" value=\"$name\"><br>\n";
    }
}
