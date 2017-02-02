<?php

namespace App\Support\Form;

class TextareaForm implements IForm
{
    public static function render($name, $data, $params)
    {
        return view('support.form.textarea', compact('name'));
    }
}
