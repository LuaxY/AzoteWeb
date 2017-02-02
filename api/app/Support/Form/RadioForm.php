<?php

namespace App\Support\Form;

class RadioForm implements IForm
{
    public static function render($name, $data, $params)
    {
        return view('support.form.radio', compact('name', 'data'));
    }
}
