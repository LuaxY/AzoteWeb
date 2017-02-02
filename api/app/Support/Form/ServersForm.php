<?php

namespace App\Support\Form;

class ServersForm implements IForm
{
    public static function render($name, $data, $params)
    {
        $child = (isset($data->child) ? $data->child : false);
        $servers = config('dofus.details');

        return view('support.form.servers', compact('name', 'servers', 'child'));
    }
}
