<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Services\DofusForge;

class ItemTemplate extends Model
{
    protected $table = 'items_templates';

    protected $connection = 'sigma_world';

    public $timestamps = false;

    public function name()
    {
        return DofusForge::text($this->NameId);
    }

    public function description()
    {
        return DofusForge::text($this->DescriptionId);
    }

    public function image()
    {
        return DofusForge::asset('dofus/www/game/items/200/' . $this->IconId . '.png');
    }
}
