<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class CharactersController extends Controller
{
    public function view($server, $characterId)
    {
        request()->session()->flash('notify', ['type' => 'warning', 'message' => "Affichage des personnages prochainement"]);
        return redirect()->back();
    }
}
