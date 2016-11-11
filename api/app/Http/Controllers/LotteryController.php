<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Exceptions\GenericException;

use App\LotteryTicket;
use App\ItemTemplate;
use App\Gift;
use App\Services\DofusForge;
use Auth;
use \Cache;

class LotteryController extends Controller
{
    public function index()
    {
        return view('lottery.index');
    }

    public function draw($id)
    {
        $ticket = LotteryTicket::find($id);

        if (!$ticket || $ticket->user_id != Auth::user()->id)
        {
            throw new GenericException('invalid_ticket');
        }

        if (count($ticket->objects()) <= 0)
        {
            throw new GenericException('ticket_no_objects');
        }

        return view('lottery.draw', ['ticket' => $ticket]);
    }

    public function process($id)
    {
        $ticket = LotteryTicket::find($id);

        if (!$ticket || $ticket->user_id != Auth::user()->id || $ticket->used)
        {
            throw new GenericException('invalid_ticket');
        }

        if (count($ticket->objects()) <= 0)
        {
            throw new GenericException('ticket_no_objects');
        }

        $object = $ticket->draw();

        if ($object)
        {
            $gift = new Gift;
            $gift->user_id     = Auth::user()->id;
            $gift->item_id     = $object->item()->Id;
            $gift->description = $ticket->description;
            $gift->save();

            $ticket->item_id = $object->item()->Id;
            $ticket->used    = true;
            $ticket->save();

            Cache::forget('gifts_available_' . Auth::user()->id);
            Cache::forget('gifts_' . Auth::user()->id);

            Cache::forget('tickets_available_' . Auth::user()->id);
            Cache::forget('tickets_' . Auth::user()->id);

            Cache::forget('tickets_admin_' . Auth::user()->id);

            return json_encode([
                'image'       => $object->item()->image(),
                'name'        => $object->item()->name(),
                'description' => $object->item()->description(),
            ]);
        }

        throw new GenericException('invalid_ticket');
    }
}
