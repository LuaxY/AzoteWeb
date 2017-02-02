<?php

namespace App\Support\Form;

class SelectForm implements IForm
{
    public static function render($name, $data, $params)
    {
        return view('support.form.select', compact('name', 'data'));
    }
}
