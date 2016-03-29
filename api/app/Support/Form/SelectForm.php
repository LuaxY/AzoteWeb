<?php

namespace App\Support\Form;

class SelectForm implements IForm
{
    public static function render($data)
    {
        $html = "<select name=\"{$data['name']}\">\n";

        foreach ($data['values'] as $value)
        {
            $html .= "<option value=\"$value\">$value</option>\n";
        }

        $html .= "</select><br>\n";

        return $html;
    }
}
