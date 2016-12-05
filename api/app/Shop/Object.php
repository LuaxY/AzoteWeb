<?php

namespace App\Shop;

use Illuminate\Database\Eloquent\Model;
use App\ItemTemplate;

class Object extends Model
{
    protected $table = 'shop_items';

    public $timestamps = false;

    public function item()
    {
        return $this->hasOne(ItemTemplate::class, 'Id', 'item_id');
    }
}
