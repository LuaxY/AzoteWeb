<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemTemplate extends Model
{
    protected $table = 'items_templates';

    protected $connection = 'world';

    public $timestamps = false;
}
