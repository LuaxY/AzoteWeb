<?php

namespace App\Support\Form;

class SelectForm implements IForm
{
    public static function render($name, $data, $params)
    {
        $html  = "$name: <select name=\"$name\">\n";
        $html .= "<option value=\"r|null\"></option>\n";

        foreach ($data as $obj)
        {
            $value = $obj->value;
            $child = (isset($obj->child) ? $obj->child : false);
            $v;

            if ($child)
            {
                $v = "c|$child";
            }
            else
            {
                $v = "f|$value";
            }

            $html .= "<option value=\"$v\">$value</option>\n";
        }

        $html .= "</select><br>\n";

        return $html;
    }
}
