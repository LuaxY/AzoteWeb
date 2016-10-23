<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\ItemTemplate;

class LotteryItem extends Model
{
    public function item()
    {
        return $this->hasOne(ItemTemplate::class, 'Id', 'item_id')->first();
    }
}
