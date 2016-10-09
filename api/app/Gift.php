<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{
    public function item()
    {
        return $this->hasOne(ItemTemplate::class, 'Id', 'item_id')->first();
    }
}
