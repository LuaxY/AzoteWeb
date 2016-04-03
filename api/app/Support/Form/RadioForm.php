<?php

namespace App\Support\Form;

class RadioForm implements IForm
{
    public static function render($name, $data)
    {
        $html = "";

        foreach ($data as $obj)
        {
            $value = $obj->value;
            $child = (isset($obj->child) ? $obj->child : false);

            if ($child)
            {
                $html .= "<input type=\"radio\" name=\"$name\" value=\"c|$child\"> $value<br>\n";
            }
            else
            {
                $html .= "<input type=\"radio\" name=\"$name\" value=\"f|$value\"> $value<br>\n";
            }

        }

        return $html;
    }
}
