<?php

namespace App\Shop;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'shop_categories';

    public $timestamps = false;

    public function childs()
    {
        return $this->hasMany(Category::class, 'parent', 'id');
    }
}
