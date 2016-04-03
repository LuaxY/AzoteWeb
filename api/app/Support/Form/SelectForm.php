<?php

namespace App\Support\Form;

class SelectForm implements IForm
{
    public static function render($name, $data)
    {
        $html = "<select name=\"$name\">\n";

        foreach ($data as $obj)
        {
            $value = $obj->value;
            $child = (isset($obj->child) ? $obj->child : false);

            if ($child)
            {
                $html .= "<option value=\"c|$child\">$value</option>\n";
            }
            else
            {
                $html .= "<option value=\"f|$value\">$value</option>\n";
            }
        }

        $html .= "</select><br>\n";

        return $html;
    }
}
