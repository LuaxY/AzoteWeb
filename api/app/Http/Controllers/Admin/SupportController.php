<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use \Cache;
Use App\SupportRequest;
Use App\SupportTicket;
use App\Http\Requests;
use Auth;
use App\User;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Yuansir\Toastr\Facades\Toastr;

class SupportController extends Controller
{
    public function index()
    {
        $type = "Open";
        $route = "datatables.supportopendata";
        return view('admin.support.list', compact('type', 'route'));
    }
    public function closedTickets()
    {
        $type = "Closed";
        $route = "datatables.supportcloseddata";
        return view('admin.support.list', compact('type', 'route'));
    }
    public function myTickets()
    {
        $type = "Mine";
        $route = "datatables.supportminedata";
        return view('admin.support.list', compact('type', 'route'));
    }

    public function show($id)
    {
        $request = SupportRequest::findOrFail($id);
        $ticket = $request->ticket()->first(); // Initial ticket
        $messages = $request->tickets()->get(); // All messages (+ticket)
        $adminsDB = User::where('rank', 4)->where('id','!=', $request->assign_to)->where('id', '!=', Auth::user()->id)->get();
        $admins = array();
        if($adminsDB)
        {
            foreach($adminsDB as $admin)
            {
                $admins[$admin->id] = $admin->pseudo;
            }
        }

        $htmlReport = $request->generateHtmlReport(json_decode($ticket->data));

        if($messages)
        {
            foreach($messages as $k => $message) // Chaque ticket (messages)
            {
            $infos = array();
            $datas = json_decode($message->data);

            foreach($datas as $key => $data)
            {
                $keyData = explode('|', $key);
                
                if (count($keyData) < 2)
                    {
                        continue;
                    }

                    $keyText = str_replace('"', ' ', $keyData[1]);
                    if($keyData[0] == 'message')
                        $infos[$keyData[0]] = $data;
            }
            $messages[$k]->data = $infos;
            }
        }
        return view('admin.support.show', compact('request', 'messages', 'htmlReport', 'ticket', 'admins'));
    }

    public function postMessage(Request $request, $id)
    {
        $validator = Validator::make($request->all(), SupportTicket::$rules['postMessageAdmin']);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

        $supportRequest = SupportRequest::findOrFail($id);

        if($supportRequest->isOpen())
        {
            if((!$supportRequest->userAssigned() || $request->assign_to != Auth::user()->id) && $request->input('take')) // Assign ticket to admin + info message
            {
                $supportRequest->assign_to = Auth::user()->id;

                $message = ''.Auth::user()->pseudo.' a pris en charge le ticket';
            
                $newKey = 'message|Message';
                $messageArray[$newKey] = $message;

                $supportTicket = new SupportTicket;
                $supportTicket->request_id = $id;
                $supportTicket->user_id    = Auth::user()->id;
                $supportTicket->data       = json_encode($messageArray);
                $supportTicket->private    = false;
                $supportTicket->reply      = 2; //INFO Reply
                $supportTicket->save();
            }

            $message = $request->input('message'); // Add admin message (textarea)
            $newKey = 'message|Message';
            $messageArray[$newKey] = $message;

            $supportTicket = new SupportTicket;
            $supportTicket->request_id = $id;
            $supportTicket->user_id    = Auth::user()->id;
            $supportTicket->data       = json_encode($messageArray);
            $supportTicket->private    = false;
            $supportTicket->reply      = true;
            $supportTicket->save();

            if($request->input('close')) // Close request if requested by admin
            {
                $supportRequest->state = SupportRequest::CLOSE;

                $message = 'Le ticket est maintenant cloturé';
            
                $newKey = 'message|Message';
                $messageArray[$newKey] = $message;

                $supportTicket = new SupportTicket;
                $supportTicket->request_id = $id;
                $supportTicket->user_id    = Auth::user()->id;
                $supportTicket->data       = json_encode($messageArray);
                $supportTicket->private    = false;
                $supportTicket->reply      = 2; //INFO Reply
                $supportTicket->save();
            }
            else
            {
                $supportRequest->state = SupportRequest::WAIT;
            }
            $supportRequest->save();
        }
        Toastr::success("Message send", $title = null, $options = []);
        return redirect()->back();
    }

    public function switchStatus(Request $request, $id)
    {
        $supportRequest = SupportRequest::findOrFail($id);

        if($supportRequest->isOpen())
        {
            $supportRequest->state = SupportRequest::CLOSE;
            $supportRequest->save();
            $message = "Le ticket est maintenant cloturé"; 
            $messageHtml = "Ticket is now closed";
        }
        else
        {
            $supportRequest->state = SupportRequest::OPEN;
            $supportRequest->save();
            $message = "Le ticket est maintenant ré-ouvert";
            $messageHtml = "Ticket is now re-open";
        }

        $newKey = 'message|Message';
        $messageArray[$newKey] = $message;

        $supportTicket = new SupportTicket;
        $supportTicket->request_id = $id;
        $supportTicket->user_id    = Auth::user()->id;
        $supportTicket->data       = json_encode($messageArray);
        $supportTicket->private    = false;
        $supportTicket->reply      = 2; // Info reply
        $supportTicket->save();

        Cache::forget('tickets_admin_open');
        Cache::forget('tickets_admin_close');
        Cache::forget('tickets_admin_mine');

        if ($request->ajax())
        {
           return response()->json([], 200);
        }
        else
        {
            Toastr::success($messageHtml, $title = null, $options = []);
            return redirect()->back();
        }
    }
    public function take(Request $request, $id)
    {
        $supportRequest = SupportRequest::findOrFail($id);

        $admin = User::where('id', Auth::user()->id)->where('rank', 4)->first();
        if($admin)
        {
            $supportRequest->assign_to = $admin->id;
            $supportRequest->save();

            $message = ''.Auth::user()->pseudo.' a pris en charge le ticket';

            $newKey = 'message|Message';
            $messageArray[$newKey] = $message;

            $supportTicket = new SupportTicket;
            $supportTicket->request_id = $id;
            $supportTicket->user_id    = Auth::user()->id;
            $supportTicket->data       = json_encode($messageArray);
            $supportTicket->private    = false;
            $supportTicket->reply      = 2; // Info reply
            $supportTicket->save();

            Toastr::success('You took this ticket', $title = null, $options = []);
        }
    
        return redirect()->back();
    }
        
    public function assignTo(Request $request, $id)
    {
        $supportRequest = SupportRequest::findOrFail($id);
        
        $validator = Validator::make($request->all(), SupportRequest::$rulesAdmin['assign_to']);
        if ($validator->fails()) 
        {
            return response()->json($validator->messages(), 400);
        }

        $admin = User::where('id', $request->input('adminid'))->where('rank', 4)->first();

        if($admin)
        {
            $supportRequest->assign_to = $admin->id;
            $supportRequest->save();

            $message = 'Le ticket a été assigné à '.$admin->pseudo;

            $newKey = 'message|Message';
            $messageArray[$newKey] = $message;

            $supportTicket = new SupportTicket;
            $supportTicket->request_id = $id;
            $supportTicket->user_id    = Auth::user()->id;
            $supportTicket->data       = json_encode($messageArray);
            $supportTicket->private    = false;
            $supportTicket->reply      = 2; // Info reply
            $supportTicket->save();

            return response()->json([$admin->pseudo], 200);
        }
    
        return response()->json([], 400);
    }
}
