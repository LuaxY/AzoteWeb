<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\LotteryItem;
use App\ItemTemplate;

class LotteryTicket extends Model
{
    const NORMAL = 0;
    const GOLD   = 1;

    public function image()
    {
        if ($this->type == self::GOLD)
        {
            return 'ticket_gold.png';
        }

        return 'ticket_normal.png';
    }

    public function box()
    {
        if ($this->type == self::GOLD)
        {
            return 'box_gold.png';
        }

        return 'box_normal.png';
    }

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
}
