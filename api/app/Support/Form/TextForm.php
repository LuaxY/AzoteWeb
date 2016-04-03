<?php

namespace App\Support\Form;

class TextForm implements IForm
{
    public static function render($name, $data)
    {
        return "$name : <input type=\"text\" name=\"$name\" " . (@$data['required'] ? 'required' : '') . "><br>\n";
    }
}
