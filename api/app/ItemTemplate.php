<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemTemplate extends Model
{
    protected $table = 'items_templates';

    protected $connection = 'sigma_world';

    public $timestamps = false;
}
