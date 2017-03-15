<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use URL;
use Auth;

class SupportRequest extends Model
{
    protected $table = 'support_requests';

    const OPEN   = 0; // New ticket (wait staff response)
    const WAIT   = 1; // Ticket wait user response
    const CLOSE  = 2; // Ticket closed
    
    public static $rules =
    [
            "La boutique" =>
            [
                "text|Code reçu" => "sometimes|required|alpha_num|between:7,8",
                "text|Sujet" => "required|between:1,30",
                "message|Message" => "required|between:1,400",
                "image|Preuve d'achat" => "required|image|mimes:jpeg,jpg,bmp,png"
            ],
            "Un autre problème" =>
            [
                "text|Sujet" => "required|between:1,30",
                "message|Message" => "required|between:1,400",
                "image|Screenshot" => "image|mimes:jpeg,jpg,bmp,png"
            ],
            "Un problème en jeu" =>
            [
                "text|Sujet" => "required|between:1,30",
                "message|Message" => "required|between:1,400",
                "image|Screenshot" => "image|mimes:jpeg,jpg,bmp,png"
            ],
            "Mon compte web" =>
            [
                "text|Sujet" => "required|between:1,30",
                "message|Message" => "required|between:1,400",
                "image|Screenshot" => "image|mimes:jpeg,jpg,bmp,png",
                "text|E-mail du compte perdu" => "sometimes|required|email"
            ],
    ];

    public static $rulesAdmin =
    [
        "assign_to" =>
        [
            "adminid" => "required|numeric"
        ]
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function userAssigned()
    {
        if ($this->assign_to) {
             $user = User::where('id', $this->assign_to)->first();
            if ($user) {
                return $user;
            } else {
                return 0;
            }
        } else {
             return 0;
        }
    }

    public function tickets()
    {
        return $this->hasMany(SupportTicket::class, 'request_id', 'id');
    }

    public function ticket()
    {
        return $this->hasOne(SupportTicket::class, 'request_id', 'id')->where('reply', 0);
    }

    public function lastTicketAuthor()
    {
        $message = SupportTicket::select('user_id')->where('request_id', $this->id)->orderBy('id', 'desc')->first();

        if ($message) {
            $user = User::select('pseudo', 'rank')->where('id', $message->user_id)->first();
            if ($user) {
                return $user;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function isOpen()
    {
        return ($this->state != self::CLOSE);
    }

    public function generateHtmlReport($report)
    {
        $html = "";
        $server = '';
        $accountId = 0;
        $characterId = 0;

        foreach ($report as $key => $value) {
            if ($key == 'message|Message') {
                continue;
            }
            list($type, $key) = explode('|', $key);

            $html .= "<b>$key</b> : ";
            switch ($type) {
                case 'text':
                case 'message':
                case 'select':
                    $html .= $value;
                    break;
                case 'image':
                    $route = URL::asset('uploads/support/'.$value.'');
                    $html .= "<div class='ak-support-image'><a href='$route' data-lightbox='image' data-title='$value'><img alt='' class='img-responsive' src='$route'></a></div>";
                    break;
                case 'server':
                    if (!World::isServerExist($value)) {
                        $html .= "Non trouvé";
                    } else {
                        $server = $value;
                        $html .= ucfirst($server);
                    }
                    break;
                case 'account':
                    if ($server == '') {
                        $html .= "Non trouvé";
                        break;
                    }

                    $accountId = $value;
                    $account = Account::on($server . '_auth')->where('Id', $accountId)->where('Email', Auth::user()->email)->first();

                    if ($account) {
                        $html .= $account->Nickname;
                    } else {
                        $html .= "Non trouvé";
                    }
                    break;
                case 'character':
                    if ($server == '' || $accountId == 0) {
                        $html .= "Non trouvé";
                        break;
                    }

                    $characterId = $value;
                    $character = ModelCustom::hasOneOnOneServer('world', $server, Character::class, 'Id', $characterId);
                    ;

                    if (!$this->isCharacterOwnedByMe($server, $accountId, $characterId)) {
                        $html .= "Non trouvé (#1)";
                    } elseif ($character) {
                        $html .= $character->Name;
                    } else {
                        $html .= "Non trouvé (#2)";
                    }
                    break;
                default:
                    break;
            }

            $html .= "<br>\n";
        }

        return $html;
    }
    private function isCharacterOwnedByMe($server, $accountId, $characterId)
    {
        $account = Account::on($server . '_auth')->where('Id', $accountId)->where('Email', Auth::user()->email)->first();

        if ($account) {
            $account->server = $server;
            $characters = $account->characters(1);

            if ($characters) {
                foreach ($characters as $character) {
                    if ($character && $characterId == $character->Id) {
                        return true;
                    }
                }

                return false;
            }
        }
    }
}
