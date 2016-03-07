<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Beta extends Model
{
    protected $primaryKey = 'id';

    protected $table = 'beta';

    protected $connection = 'web';

    public $timestamps = false;
}
