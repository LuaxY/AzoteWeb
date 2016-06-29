<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'state',
        'code',
        'points',
        'country',
        'palier_name',
        'palier_id',
        'type',
    ];
}
