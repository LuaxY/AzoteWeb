<?php

namespace App\Support\Form;

class RadioForm implements IForm
{
    public static function render($data)
    {
        $html = "";

        foreach ($data['values'] as $value)
        {
            $html .= "<input type=\"radio\" name=\"{$data['name']}\" value=\"$value\"> $value<br>\n";
        }

        return $html;
    }
}
