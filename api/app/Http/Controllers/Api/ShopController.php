<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use Auth;
use App\Shop\Category;
use App\Shop\Object;

class ShopController extends ApiController
{
    const MAIN_SHOP_ID = 327;

    public function shop()
    {
        if (Auth::guest())
        {
            return $this->softError("AUTH_FAILED");

            /*$data = new stdClass;
            $data->error = "AUTH_FAILED";
            return $this->result($data);*/
        }

        $req = $this->input();

        if (@$req->params[0] != "DOFUS_INGAME")
        {
            return $this->softError("KEY_UNKNOWN");
        }

            if (@$req->method == "Home")           return $this->getHome();
        elseif (@$req->method == "ArticlesList")   return $this->getArticlesList();
        elseif (@$req->method == "QuickBuy")       return $this->buyArticle();
        elseif (@$req->method == "ArticlesSearch") return $this->searchForArticles();
        else return $this->softError("Method not found");
    }

    ////////// Shop page //////////

    private function getHome()
    {
        $req = $this->input();

        $result = new \stdClass;
        $result->content = $this->welcome();
        return $this->result($result);
    }

    private function getArticlesList()
    {
        $req        = $this->input();
        $categoryId = @$req->params[2];

        if ($categoryId)
        {
            $result = $this->page($categoryId);
            return $this->result($result);
        }
        else
        {
            return Home();
        }
    }

    private function buyArticle()
    {
        $req    = $this->input();
        $itemId = @$req->params[2];

        if ($itemId)
        {
            $result = $this->buy($itemId);
            return $this->result($result);
        }
        else
        {
            return $this->softError("invalid item id param");
        }
    }

    private function searchForArticles()
    {
        $req   = $this->input();
        $query = @$req->params[2];

        if (!empty($query))
        {
            $result = $this->search($query);
            return $this->result($result);
        }
        else
        {
            return $this->softError("invalid search param");
        }
    }

    ////////// Communication to server //////////

    private function RequestToServer($method, $request)
    {
        $rpc = new \stdClass;

        $rpc->method  = $method;
        $rpc->request = $request;

        $data = json_encode($rpc);

        if (($socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false)
        {
            return  "socket_create: " . socket_last_error();
        }

        if (!@socket_connect($socket, config('dofus.shop.host'), config('dofus.shop.port')))
        {
            return  "socket_connect: " . socket_last_error();
        }

        socket_write($socket, $data, strlen($data));
        socket_close($socket);
    }

    ////////// Shop structure //////////

    private function welcome()
    {
        $content = new \stdClass;

        $content->categories          = $this->categories();
        $content->gondolahead_main    = $this->gondolahead_main();
        $content->gondolahead_article = $this->gondolahead_article();
        $content->hightlight_carousel = $this->hightlight_carousel();
        $content->hightlight_image    = $this->hightlight_image();

        return $content;
    }

    private function page($categoryId)
    {
        $content = new \stdClass;

        $content->result   = true;
        $content->count    = 0;
        $content->articles = [];

        $objects = Object::where('categorie_id', $categoryId)->where('enabled', 1)->get();

        foreach ($objects as $object)
        {
            $article = $this->article($object);

            $content->articles[] = $article;
            $content->count++;
        }

        return $content;
    }

    private function buy($objectId)
    {
        $content = new \stdClass;

        $object = Object::find($objectId);

        if (!$object)
        {
            $content->error = "PAIDFAILED";
            return $content;
        }

        $price = $object->price + $object->promo;

        if (Auth::user()->Tokens < $price)
        {
            $content->error = "MISSINGMONEY";
            return $content;
        }

        $buyRequest = new \stdClass;
        $buyRequest->key         = "ILovePanda";
        $buyRequest->price       = $price;
        $buyRequest->characterId = Session::get('characterId');
        $buyRequest->actions     = [];

        $action = new \stdClass;
        $action->type             = "item";
        $action->item             = new \stdClass;
        $action->item->itemId     = $object->item_id;
        $action->item->quantity   = 1;
        $action->item->maxEffects = false;

        $buyRequest->actions[] = $action;

        $error = $this->RequestToServer("buyItem", $buyRequest);

        if (!empty($error))
        {
            $content->error = $error;
            return $content;
        }

        Auth::user()->Tokens -= $price;
        Auth::user()->update(['Tokens' => Auth::user()->Tokens]);

        $content->result = true;
        $content->ogrins = Auth::user()->Tokens;
        $content->krozs  = 0;

        return $content;
    }

    private function search($query)
    {
        $content = new \stdClass;

        $content->result   = true;
        $content->count    = 0;
        $content->articles = [];

        $objects = Object::where('name', 'like', "%$query%")->where('enabled', 1)->get();

        foreach ($objects as $object)
        {
            $article = $this->article($object);

            $content->articles[] = $article;
            $content->count++;
        }

        return $content;
    }

    private function categories()
    {
        $data = new \stdClass;

        $data->result = true;
        $data->categories = [];
        $data->categories[] = $this->category(self::MAIN_SHOP_ID, "SHOP_HOME", "Boutique " . config('dofus.title'));

        return $data;
    }

    private function category($id, $key, $name)
    {
        $categorie = new \stdClass;

        $categorie->id          = $id;
        $categorie->key         = $key;
        $categorie->name        = $name;
        $categorie->displaymode = "MOSAIC";
        $categorie->description = "";
        $categorie->image       = false;
        $categorie->child       = [];

        $childs = Category::where('enabled', 1)->get();

        foreach ($childs as $child)
        {
            if ($child->parent == 0)
                $categorie->child[] = $this->child($child);
        }

        return $categorie;
    }

    private function child($currentChild)
    {
        $categorie = new \stdClass;

        $categorie->id          = $currentChild->id;
        $categorie->key         = $currentChild->key;
        $categorie->name        = $currentChild->name;
        $categorie->displaymode = $currentChild->displaymod;
        $categorie->description = $currentChild->description;
        $categorie->image       = $currentChild->image;
        $categorie->child       = [];

        //$childs = $childs = $currentChild->childs;
        $childs = Category::where('parent', $currentChild->id)->where('enabled', 1)->get();

        foreach ($childs as $child)
        {
            $categorie->child[] = $this->child($child);
        }

        return $categorie;
    }

    private function article($object)
    {
        $article = new \stdClass;

        $article->id          = "{$object->id}";
        $article->key         = $object->key;
        $article->name        = $object->name;
        $article->subtitle    = $object->subtitle;
        $article->description = $object->description;

        if ($object->promo < 0)
        {
            $article->price          = $object->price + $object->promo;
            $article->original_price = $object->price;
        }
        else
        {
            $article->price          = $object->price;
            $article->original_price = null;
        }

        $article->startdate = $object->startdate;
        $article->enddate   = null;
        $article->currency  = "OGR";
        $article->stock     = null;
        $article->image     = new \stdClass;

        $article->image->{'70_70'}   = false;
        $article->image->{'200_200'} = false;
        $article->image->{'590_178'} = false;

        $article->references =     [];

        $reference = new \stdClass;

        $reference->type        = "VIRTUALGIFT";
        $reference->quantity    = "{$object->quantity}";
        $reference->free        = 0;
        $reference->name        = $object->name;
        $reference->description = $object->description;
        $reference->content     = [];

        $item = new \stdClass;

        $item->id          = $object->item->Id;
        $item->name        = $object->name;
        $item->description = $object->description;
        $item->image       = false;

        $reference->content[] = $item;

        $article->references[] = $reference;

        return $article;
    }

    private function gondolahead_main()
    {
        $data = new \stdClass;

        $data->result       = true;
        $data->gondolaheads = [];

        return $data;
    }

    private function gondolahead_article()
    {
        $content = new \stdClass;

        $content->result   = true;
        $content->count    = 0;
        $content->articles = [];

        $objects = Object::where('featured', 1)->where('enabled', 1)->take(6)->get();

        foreach ($objects as $object)
        {
            $article = $this->article($object);

            $content->articles[] = $article;
            $content->count++;
        }

        return $content;
    }

    private function hightlight_carousel()
    {
        return json_decode(file_get_contents("SHOP/hightlight_carousel.json"));
    }

    private function hightlight_image()
    {
        return json_decode(file_get_contents("SHOP/hightlight_image_empty.json"));
    }
}
