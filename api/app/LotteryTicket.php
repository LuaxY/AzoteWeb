<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Lottery;
use App\LotteryItem;
use App\ItemTemplate;

class LotteryTicket extends Model
{
    public function objects()
    {
        return $this->hasMany(LotteryItem::class, 'type', 'type')->get();
    }

    public function draw()
    {
        $objects = $this->objects();
        $max = 0;

        foreach ($objects as $object)
        {
            $max += $object->percentage;
        }

        $random = rand(1, $max);
        $percentage = 1;

        foreach ($objects as $object)
        {
            $current = $percentage;
            $percentage += $object->percentage;

            if ($random >= $current && $random < $percentage)
            {
                return $object;
            }
        }
    }

    public function item()
    {
        if ($this->item_id)
        {
            return $this->hasOne(ItemTemplate::class, 'Id', 'item_id')->first();
        }

        return null;
    }

    public function lottery()
    {
        return $this->hasOne(Lottery::class, 'type', 'type')->first();
    }

    public function giver()
    {
       if($this->giver)
       {
           $giver = User::find($this->giver);
           return $giver ? $giver->pseudo : null;
       }
       return null;
    }
}
