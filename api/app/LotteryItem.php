<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\ItemTemplate;
use App\Lottery;

class LotteryItem extends Model
{
    public static $rules = [
        'store' => [
            'item'               => 'required|numeric|digits_between:1,5',
            'percentage'         => 'required|numeric|between:1,100|integer'
        ],
        'update' => [
            'percentage'         => 'required|numeric|between:1,100|integer'
        ],
    ];

    public function item()
    {
        return $this->hasOne(ItemTemplate::class, 'Id', 'item_id')->first();
    }

    public function lottery()
    {
        return $this->hasOne(Lottery::class, 'type', 'type')->first();
    }
}
