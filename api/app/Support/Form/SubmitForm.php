<?php

namespace App\Support\Form;

class SubmitForm implements IForm
{
    public static function render($name, $data, $params)
    {
        return view('support.form.submit', compact('name'));
    }
}
