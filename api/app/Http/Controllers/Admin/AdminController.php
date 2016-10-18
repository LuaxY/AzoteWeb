<?php

namespace App\Http\Controllers\Admin;

use App\Account;
use App\Http\Controllers\Controller;
use App\Post;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Yuansir\Toastr\Facades\Toastr;

class AdminController extends Controller
{
    public function index()
    {
        $countuser = User::all()->count();
        $countpost = Post::all()->count();

        foreach (config('dofus.servers') as $server) {
            $countservers[$server] = Account::on($server.'_auth')->get()->count();
        }

        $count = ['users' => $countuser, 'posts' => $countpost, 'servers' => $countservers];

        $newusers = User::latest('created_at')->take(5)->select('id', 'pseudo', 'email', 'firstname', 'lastname', 'active', 'created_at')->get();
        $newposts = Post::latest('updated_at')->take(5)->select('id', 'title', 'type', 'author_id', 'published', 'updated_at')->get();

        return view('admin.index', compact('newusers', 'count', 'newposts'));
    }
}
