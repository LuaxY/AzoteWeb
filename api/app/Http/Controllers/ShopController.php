<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Character;
use App\MarketCharacter;
use Cache;
use \DB;
use App\Experience;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    const CHARACTERS_PER_PAGE = 20;

    public function index()
    {
        return view('shop.index');
    }

    public function details(Request $request, $article)
    {
        return view('shop.details');
    }

    public function market(Request $request)
    {
        $page = $request->has('page') && is_numeric($request->input('page')) ? $request->input('page') : 1;
        if (!is_numeric($page)) {
            abort(404);
        }

        // FILTERS INIT
        $filters = [
            'character_homeserv' => ['values' => []], 
            'character_breed_id' => ['values' => []], 
            'character_sex'      => ['values' => []],
            'character_level'    => ['values' => []],
            'character_prestige' => ['values' => []],
            'character_price'    => ['values' => []]
        ];
        if(count($request->all()) > 0) // REQUEST WITH FILTERS
        {
            // VALIDATION
            $rules = [
            'character_homeserv'      => 'sometimes|array|distinct|serverarray',
            'character_breed_id'      => 'sometimes|array|distinct|breedarray',
            'character_sex'           => 'sometimes|array|distinct|sexarray',

            'TEXT'                    => 'present|nullable|alpha_dash',
            'character_level_min'     => 'required|numeric|between:1,200',
            'character_level_max'     => 'required|numeric|between:1,200',
            'character_prestige_min'  => 'required|numeric|between:0,15',
            'character_prestige_max'  => 'required|numeric|between:0,15',
            'character_price_min'     => 'present|nullable|numeric',
            'character_price_max'     => 'present|nullable|numeric'
            ];

            $validator = Validator::make($request->all(), $rules);

            if (!$validator->fails()) 
            {
                if($request->character_homeserv)
                {
                    $servers = $request->character_homeserv;
                    $filters['character_homeserv'] = ['values' => $servers, 'name' => 'Serveurs', 'text' => $servers, 'separator' => ','];
                }
                else
                    $servers = config('dofus.servers');

                $filters['TEXT'] = ['values' => $request->TEXT, 'name' => 'Nom'];

                $levels = [$request->character_level_min,$request->character_level_max];
                $filters['character_level'] = ['values' => $levels, 'name' => 'Niveau', 'text' => $levels, 'separator' => ' à'];

                foreach($servers as $server)
                {
                    $min_exp = Experience::on($server .'_world')->select('CharacterExp')->where('Level', $request->character_level_min)->first();
                    $max_exp = Experience::on($server .'_world')->select('CharacterExp')->where('Level', $request->character_level_max)->first();

                    $exp_min[$server] = $min_exp->CharacterExp;
                    $exp_max[$server] = $max_exp->CharacterExp;
                    $dbMaxExp[$server] = Experience::maxExp($server);
                }   
                if($request->character_breed_id)
                {
                    $textbreeds = $request->character_breed_id;
                    $textbreeds = str_replace(["10","11","12","13","14","15","16","17","18","1","2","3","4","5","6","7","8","9"], ['Sadida', 'Sacrieur', 'Pandawa', 'Roublard', 'Zobal', 'Steamer', 'Eliotrope', 'Huppermage', 'Ouginak', 'Féca', 'Osamodas', 'Enutrof', 'Sram', 'Xélor', 'Ecaflip', 'Eniripsa', 'Iop', 'Crâ'], $textbreeds);
                    $filters['character_breed_id'] = ['values' => $request->character_breed_id, 'name' => 'Classe', 'text' => $textbreeds, 'separator' => ','];
                }
                
                if($request->character_sex)
                {
                    $textsex = $request->character_sex;
                    $textsex = str_replace([0,1], ['Mâle','Femelle'], $textsex);
                    $filters['character_sex'] = ['values' => $request->character_sex, 'name' => 'Sexe', 'text' => $textsex, 'separator' => ','];
                }

                $prestiges = [$request->character_prestige_min,$request->character_prestige_max];
                $filters['character_prestige'] = ['values' => $prestiges, 'name' => 'Prestige', 'text' => $prestiges, 'separator' => ' à'];

                if($request->character_price_min || $request->character_price_max)
                    $prices = [$request->character_price_min,$request->character_price_max];
                else
                    $prices = [];
                $filters['character_price'] = ['values' => $prices, 'name' => 'Prix', 'text' => $prices, 'separator' => ' à'];

                $market_characters = Cache::remember('market_characters_filtered_'.$request->fullUrl().'_'.$page, 30, function () use ($servers, $request, $dbMaxExp, $exp_min, $exp_max){
                $ids = [];
                // Finds id's of filtered market characters
                foreach($servers as $server)
                {
                    $elements = DB::table('market_characters AS mk')
                                        ->where('mk.server', $server)
                                        ->select('mk.id', 'mk.character_id', 'mk.server')
                                        ->leftJoin('azote_'.$server.'_world.characters AS ch', 'ch.Id', '=', 'mk.character_id');

                    if($request->TEXT)
                        $elements = $elements->where('ch.Name', 'like', '%'.$request->TEXT.'%');
                    if($request->character_breed_id)
                        $elements = $elements->whereIn('ch.Breed', $request->character_breed_id);
                    if($request->character_sex)
                        $elements = $elements->whereIn('ch.Sex', $request->character_sex);

                    if($request->character_level_max != 200)
                        $elements = $elements->whereBetween(DB::raw('ch.Experience - (ch.PrestigeRank * '.$dbMaxExp[$server].')'), [$exp_min[$server], $exp_max[$server]]);
                    else
                        $elements = $elements->where(DB::raw('ch.Experience - (ch.PrestigeRank * '.$dbMaxExp[$server].')'), '>=', $exp_min[$server]);
                    
                    $elements = $elements->whereBetween('ch.PrestigeRank', [$request->character_prestige_min, $request->character_prestige_max]);

                    if($request->character_price_min)
                        $elements = $elements->where('mk.ogrines', '>=', $request->$character_price_min);
                    if($request->character_price_max)
                        $elements = $elements->where('mk.ogrines', '<=', $request->$character_price_max);

                    $elements = $elements->get();

                    foreach($elements as $element)
                    {
                        array_push($ids,$element->character_id);
                    }
                }
                    return MarketCharacter::latest('created_at')->whereIn('character_id', $ids)->paginate(self::CHARACTERS_PER_PAGE);
                });
            }
            else
            {
                $market_characters = Cache::remember('market_characters'.$page, 30, function (){
                    return MarketCharacter::latest('created_at')->paginate(self::CHARACTERS_PER_PAGE);
                });
            }
        }
        else // REQUEST WITHOUT FILTERS
        {
            $market_characters = Cache::remember('market_characters'.$page, 30, function (){
                return MarketCharacter::latest('created_at')->paginate(self::CHARACTERS_PER_PAGE);
            });
        }
        return view('shop.market', compact('market_characters', 'filters'));
    }

}
