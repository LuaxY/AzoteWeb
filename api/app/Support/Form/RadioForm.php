<?php

namespace App\Support\Form;

class RadioForm implements IForm
{
    public static function render($name, $data, $params)
    {
        $html = "$name:<br>\n";

        foreach ($data as $obj) {
            $value = $obj->value;
            $child = (isset($obj->child) ? $obj->child : false);
            $v;

            if ($child) {
                $v = "c|$child";
            } else {
                $v = "f|$value";
            }

            $html .= "<input type=\"radio\" name=\"$name\" value=\"$v\"> $value<br>\n";
        }

        return $html;
    }
}
