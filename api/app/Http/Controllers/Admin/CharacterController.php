<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yuansir\Toastr\Facades\Toastr;

class CharacterController extends Controller
{
    public function index()
    {
        return view('admin.characters.index');
    }
}
