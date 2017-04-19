<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Redis;
use Illuminate\Support\Facades\Cache;
use App\Character;
use App\Services\Linker;
use App\Characteristic;
use Illuminate\Pagination\Paginator;
use App\World;

class LinkerController extends Controller
{
    public function get(Request $request)
    {
        $server = $request->input('server');
        $characterId = $request->input('character');
        $position = $request->input('position');
        if(!$server || !$characterId || !$position)
            return response()->json([], 402);
        if(!in_array($position, ['right', 'left', 'bottom']))
             return response()->json([], 402);
        if(!is_numeric($characterId))
            return response()->json([], 402);
        if(!World::isServerExist($server))
             return response()->json([], 402);
        $itemid = $request->input('id');
        $itemsall = Cache::get('character_inventory_'.$server.'_'.$characterId);
        if(!$itemsall)
            return response()->json([], 402);
        $collection = collect($itemsall[$position]);
        $item = $collection->where('ItemId', $itemid)->first();
        $effects = $item->Effects;
        $effectArray = [];

        foreach($effects as $effect)
        {
            if(@!isset($effect->Template->Description)) 
                $effect->Template->Description = "-Description not found-";
            $text = $effect->Template->Description;
            if(@!isset($effect->Template->Value)) 
                $effect->Template->Value = "-Value not found-";
            $value = $effect->Value;
            if($effect->Template->UseDice)
                $text = preg_replace('/#1.*#2/', $value, $text);

            $characteristic = Characteristic::findOrFail($effect->Template->Characteristic);
            if(!empty($characteristic->asset))
                $asset = strtolower(str_replace('_', '-', $characteristic->asset));
            else
                $asset = null;
            $name = $characteristic->name;
            $finish = collect(['text' => $text, 'asset' => $asset, 'name' => $name]);
            array_push($effectArray, $finish);
        }
        $perPage = 3;
        $newEffectArray = [];
        $totalPages = (int)ceil(count($effectArray)/$perPage);
        for($x = 1; $x <= $totalPages; $x++)
        {
            $offSet = ($x * $perPage) - $perPage; 
            $newarray = array_slice($effectArray, $offSet, $perPage);
            array_push($newEffectArray, $newarray);
        }
        return view('templates.linker', compact('item', 'newEffectArray'));
    }
}
