<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Yuansir\Toastr\Facades\Toastr;
use App\User;


class AccountController extends Controller
{
    public function index()
    {
        return view('admin.profile');
    }

    public function accountUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), User::$rules['admin-update-profile']);

        if ($validator->fails()) {
            return redirect(route('admin.profile'))
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();
        $user->firstname = $request['firstname'];
        $user->lastname = $request['lastname'];

        if($request->hasFile('avatar'))
        {

            $old_avatar = Auth::user()->avatar;
            $user_id = Auth::user()->id;
            $file = $request->file('avatar');

            $image_name = "avatar-".$file->getClientOriginalName();

            if($old_avatar != config('dofus.default_avatar'))
            {
                File::delete($old_avatar);
            }

            $file->move('uploads/users/'.$user_id, $image_name);

            Image::make(sprintf('uploads/users/'.$user_id.'/%s', $image_name))->resize(200, 200)->save();
            $user->avatar = 'uploads/users/'.$user_id.'/'.$image_name;

        }
        Toastr::success('Account updated', $title = null, $options = []);
        $user->save();

        return redirect()->route('admin.account');

    }

    public function resetAvatar()
    {
        $user = Auth::user();
        if(Auth::user()->avatar != config('dofus.default_avatar'))
        {
            $old_avatar = $user->avatar;
            File::delete($old_avatar);
            $new_avatar = config('dofus.default_avatar');
            $user->avatar = $new_avatar;
            $user->save();

            return response()->json([], 200);
        }
        else
        {
            return response()->json([], 403);
        }
    }

    public function password()
    {
        return view('admin.password');
    }

    public function passwordUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), User::$rules['admin-update-password']);

        if ($validator->fails()) {
            return redirect(route('admin.password'))
                ->withErrors($validator)
                ->withInput();
        }

        Auth::user()->salt     = str_random(8);
        Auth::user()->password = Auth::user()->hashPassword($request->input('password'), Auth::user()->salt);
        Auth::user()->update([
            'password' => Auth::user()->password,
            'salt'     => Auth::user()->salt,
        ]);

        Toastr::success('Password updated', $title = null, $options = []);
        return redirect()->route('admin.account');

    }
}
