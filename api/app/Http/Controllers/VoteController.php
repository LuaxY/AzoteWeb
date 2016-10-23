<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use Validator;
use \Cache;

use App\Vote;
use App\VoteReward;
use App\ItemTemplate;
use App\Gift;
use App\LotteryTicket;
use App\Services\DofusForge;

class VoteController extends Controller
{
    public function index()
    {
        if(Auth::guest())
        {
            return view('vote.guest');
        }

        $palierId   = $this->palierId();
        $votesCount = $this->userVotes();
        $giftsCount = $this->giftsCount();
        $nextGifts  = $this->nextGift();
        $progress   = $this->progressBar($palierId);
        $steps      = $this->stepsList($palierId);
        $current    = (($votesCount + $nextGifts) / 10) % 5;
        $delay      = $this->delay();

        if ($current <= 0)
        {
            $current = 5;
        }

        $data = [
            'palierId'   => $palierId,
            'votesCount' => $votesCount,
            'giftsCount' => $giftsCount,
            'nextGifts'  => $nextGifts,
            'progress'   => $progress,
            'steps'	     => $steps,
            'current'    => $current,
            'delay'      => $delay,
        ];

        if (Auth::user()->isFirstVote)
        {
            $data['popup'] = 'vote';
        }

        return view('vote.index', $data);
    }

    public function confirm()
    {
        $delay = $this->delay();

        if (!$delay->canVote)
        {
            return redirect()->route('vote.index');
        }

        return view ('vote.confirm');
    }

    public function process(Request $request)
    {
        $delay = $this->delay();

        if (!$delay->canVote)
        {
            return redirect()->route('vote.index');
        }

        $rules = [
            'out'                  => 'required|integer',
            'g-recaptcha-response' => 'required|recaptcha'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $actualOUT = $this->getOuts();

        if (abs($actualOUT - $request->input('out')) > 5 && $actualOUT != 0)
        {
            return redirect()->back()->withErrors(['out' => 'Valeur OUT incorrect'])->withInput();
        }

        Auth::user()->votes    += 1;
        Auth::user()->points   += config('dofus.vote');
        Auth::user()->last_vote = date('Y-m-d H:i:s');

        if (Auth::user()->isFirstVote)
        {
            Auth::user()->isFirstVote = false;
        }

        Auth::user()->save();

        $accounts = Auth::user()->accounts();

        foreach ($accounts as $account)
        {
            $account->LastVote = date('Y-m-d H:i:s');
            $account->save();
        }

        $vote = new Vote;
        $vote->user_id = Auth::user()->id;
        $vote->points  = config('dofus.vote');
        $vote->save();

        Cache::forget('votes_' . Auth::user()->id);
        Cache::forget('votes_' . Auth::user()->id . '_10');

        if (Auth::user()->votes % 10 == 0)
        {
            $ticket = new LotteryTicket;
            $ticket->type        = Auth::user()->votes % 50 == 0 ? LotteryTicket::GOLD : LotteryTicket::NORMAL;
            $ticket->user_id     = Auth::user()->id;
            $ticket->description = "Ticket " . Auth::user()->votes . " votes";
            $ticket->save();

            Cache::forget('tickets_available_' . Auth::user()->id);
            Cache::forget('tickets_' . Auth::user()->id);

            $request->session()->flash('notify', ['type' => 'success', 'message' => "Vous avez reçus un nouveau ticket !"]);
            return redirect()->route('lottery.index');
        }

        $request->session()->flash('popup', 'ogrines');
        return redirect()->route('vote.index');
    }

    public function palier($id)
    {
        $votesCount = $this->userVotes();

        if ($id < 1 || $id > ceil(($votesCount+1) / 50))
        {
            $id = 1;
        }

        $progress   = $this->progressBar($id);
        $steps      = $this->stepsList($id);
        $current    = 1;

        $data = array(
            'palierId' => $id,
            'progress' => $progress,
            'steps'	   => $steps,
            'current'  => $current,
        );

        return view('vote.paliers', $data);
    }

    public function object($item)
    {
        $object = ItemTemplate::where('Id', $item)->firstOrFail();

        $json = [
            'name'        => $object->name(),
            'description' => $object->description(),
            'image'       => $object->image(),
        ];

        return json_encode($json);
    }

    private function userVotes()
    {
        return Auth::user()->votes;
    }

    private function palierId()
    {
        return intval($this->userVotes() / 50) + 1;
    }

    private function giftsCount()
    {
        return intval($this->userVotes() / 10);
    }

    private function nextGift()
    {
        return 10 - ($this->userVotes() % 10);
    }

    private function progressBar($palierId)
    {
        $progress = ($this->userVotes() - (($palierId - 1) * 50)) * 100 / 50;
        return $progress > 100 ? 100 : $progress;
    }

    private function stepsList($palierId)
    {
        return [
            1 => 50 * ($palierId - 1) + 10,
            2 => 50 * ($palierId - 1) + 20,
            3 => 50 * ($palierId - 1) + 30,
            4 => 50 * ($palierId - 1) + 40,
            5 => 50 * ($palierId - 1) + 50,
        ];
    }

    private function delay()
    {
        $obj = new \stdClass();

        $obj->now      = strtotime(date('Y-m-d H:i:s'));
        $obj->duration = $obj->now - strtotime(Auth::user()->last_vote);
        $obj->canVote  = $obj->duration < config('dofus.rpg-paradize.delay') ? false : true;
        $obj->wait     = config('dofus.rpg-paradize.delay') - $obj->duration;
        $obj->hours    = intval($obj->wait / 3600);
        $obj->minutes  = intval(($obj->wait % 3600) / 60);
        $obj->seconds  = intval((($obj->wait % 3600) % 60));

        return $obj;
    }

    private function getOuts()
    {
        $outs = Cache::remember('rpg_outs', 1, function () {
            $curl = curl_init();

            $header[0] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,";
            $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
            $header[] = "Cache-Control: max-age=0";
            $header[] = "Connection: keep-alive";
            $header[] = "Keep-Alive: 5";
            $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
            $header[] = "Accept-Language: fr,fr-fr;q=0.8,en-us;q=0.5,en;q=0.3";

            curl_setopt($curl, CURLOPT_URL, 'http://www.rpg-paradize.com/site--' . config('dofus.rpg-paradize.id'));
            curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:32.0) Gecko/20100101 Firefox/32.0");
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_REFERER, 'http://www.rpg-paradize.com');
            curl_setopt($curl, CURLOPT_ENCODING, "gzip,deflate");
            curl_setopt($curl, CURLOPT_AUTOREFERER, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_TIMEOUT, 5);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION,true);
            curl_setopt($curl, CURLOPT_VERBOSE, false);
            curl_setopt($curl, CURLOPT_COOKIEFILE,'cookieRPG.txt');
            curl_setopt($curl, CURLOPT_COOKIEJAR,'cookieRPG.txt');

            $webpage  = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            preg_match('#Clic Sortant : ([0-9]+)#', $webpage, $matches);

            if (!isset($matches[0]))
            {
                return 0;
            }

            return substr($matches[0], 15);
        });

        return $outs;
    }
}
