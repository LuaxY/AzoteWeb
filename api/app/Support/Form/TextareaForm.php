<?php

namespace App\Support\Form;

class TextareaForm implements IForm
{
    public static function render($name, $data)
    {
        return "$name:<br>\n<textarea name=\"$name\"  rows=\"6\" cols=\"60\"></textarea><br>\n";
    }
}
