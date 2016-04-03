<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Support\Support;

class SupportController extends Controller
{
    public function create()
    {
        $html = Support::generateForm('support');
        return view('support/create', ['html' => $html]);
    }

    public function child($child)
    {
        return Support::generateForm($child);
    }

    public function store()
    {
        var_dump($_POST);
    }
}
