<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    protected $primaryKey = 'Id';

    protected $table = 'characters';

    protected $connection = 'world';
}
