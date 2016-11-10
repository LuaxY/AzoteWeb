<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\ItemTemplate;
use App\Lottery;

class LotteryItem extends Model
{
    public function item()
    {
        return $this->hasOne(ItemTemplate::class, 'Id', 'item_id')->first();
    }

    public function lottery()
    {
        return $this->hasOne(Lottery::class, 'type', 'type')->first();
    }
}
