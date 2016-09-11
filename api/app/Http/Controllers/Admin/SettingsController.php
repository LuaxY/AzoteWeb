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
class SettingsController extends Controller
{
    public function index()
    {
        $motd = config('dofus.motd');
        $posts_id_object = Post::select('id','title')->get();
        $posts = [];
        if($posts_id_object)
        {
            foreach ($posts_id_object as $post_id_object)
            {
                $posts[$post_id_object->id] = ''.$post_id_object->title.' (id: '.$post_id_object->id.')';
            }
        }
        return view('admin.settings.index', compact('motd', 'posts'));
    }

    public function update(Request $request)
    {
        switch ($request->settings_type)
        {
            case 'motd':
                $motd = [];
                $motd['motd']['title'] = $request->title;
                $motd['motd']['subtitle'] = $request->subtitle;
                $motd['motd']['postid'] = $request->postid;
                file_put_contents("../motd.json", json_encode($motd, JSON_UNESCAPED_UNICODE));
                Toastr::success('Motd updated', $title = null, $options = []);
                return redirect(route('admin.settings'));
                break;

        }
    }
}
