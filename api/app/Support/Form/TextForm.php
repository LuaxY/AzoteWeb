<?php

namespace App\Support\Form;

class TextForm implements IForm
{
    public static function render($data)
    {
        return "{$data['name']} : <input type=\"text\" name=\"{$data['name']}\" " . (@$data['required'] ? 'required' : '') . "><br>\n";
    }
}
