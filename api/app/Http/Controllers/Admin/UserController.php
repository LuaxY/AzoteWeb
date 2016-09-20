<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Yuansir\Toastr\Facades\Toastr;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function edit(User $user)
    {
        $user = User::findOrFail($user->id); // For the web account
        $epsilon_accounts = $user->accounts('epsilon');
        $sigma_accounts = $user->accounts('sigma');


        return view('admin.users.edit', compact('user', 'epsilon_accounts', 'sigma_accounts'));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), User::$rules['admin-store']);

        if ($validator->fails()) {
            return redirect(route('admin.user.create'))
                ->withErrors($validator)
                ->withInput();
        }

        $salt = str_random(8);

        $user = new User;
        $user->firstname = $request->firstname;
        $user->lastname  = $request->lastname;
        $user->email     = $request->email;
        $user->password  = $user->hashPassword($request->password, $salt);
        $user->salt      = $salt;

        $user->active = $request->active == 1 ? true : false;
        $user->ticket = $request->active == 1 ? null : str_random(32);
        $user->save();

        if(!$request->active)
        {
            Mail::send('emails.welcome', ['user' => $user], function ($message) use ($user) {
                $message->from('welcome@azote.us', 'Azote.us');
                $message->to($user->email, $user->firstname . ' ' . $user->lastname);
                $message->subject('Azote.us - Confirmation d\'inscription');
            });
        }

        Toastr::success('User created', $title = null, $options = []);
        return redirect(route('admin.users'));
    }

    public function update(User $user, Request $request)
    {
        $rules = [
            'pseudo'    => 'required|min:3|max:32|alpha_dash|unique:users,pseudo,' . $user->id,
            'firstname' => 'required|min:3|max:32|alpha_dash',
            'lastname'  => 'required|min:3|max:32|alpha_dash',
            'birthday'  => 'date',
            'email'     => 'required|email|unique:users,email, ' . $user->id,
            'rank'      => 'required|in:0,4',
            'points'    => 'required|numeric'
        ];
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->pseudo    = $request['pseudo'];
        $user->firstname = $request['firstname'];
        $user->lastname  = $request['lastname'];
        $user->rank      = $request['rank'];
        $user->points    = $request['points'];
        $user->email     = $request['email'];
        $user->birthday  = empty($request['birthday']) ? null : $request['birthday'];
        $user->save();

        if($request->useradvert == true)
        {
            Mail::send('emails.admin-email-update', ['user' => $user], function ($message) use ($user) {
                $message->from(config('mail.sender'), 'Azote.us');
                $message->to($user->email, $user->firstname . ' ' . $user->lastname);
                $message->subject('Azote.us - Changement d\'adresse e-mail');
            });
            Toastr::success('E-mail send', $title = null, $options = []);
        }

        Toastr::success('Account updated', $title = null, $options = []);

        return redirect()->back();
    }

    public function ban(User $user, Request $request)
    {
        $user = User::findOrFail($user->id);

        $user->banned = true;
        $user->banReason = $request->banReason;
        $user->save();

        return response()->json([], 200);
    }

    public function unban(User $user, Request $request)
    {
        $user = User::findOrFail($user->id);

        $user->banned = false;
        $user->banReason = null;
        $user->save();

        return response()->json([], 200);
    }

    public function activate(User $user, Request $request)
    {
        $user = User::findOrFail($user->id);

        $user->active = true;
        $user->ticket = null;
        $user->save();

        return response()->json([], 200);
    }

    public function decertify(User $user, Request $request)
    {
        $user = User::findOrFail($user->id);

        $user->certified = false;
        $user->save();

        return response()->json([], 200);
    }

    public function certify(User $user, Request $request)
    {
        $validator = Validator::make($request->all(), User::$rules['certify']);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $user = User::findOrFail($user->id);

        $user->firstname  = $request->firstname;
        $user->lastname   = $request->lastname;
        $user->birthday   = $request->birthday;
        $user->certified = true;
        $user->save();

        return response()->json([], 200);
    }

    public function password(User $user, Request $request)
    {
        $validator = Validator::make($request->all(), User::$rules['admin-update-password']);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $user = User::findOrFail($user->id);

        $salt = str_random(8);

        $user->password  = $user->hashPassword($request->password, $salt);
        $user->salt      = $salt;
        $user->save();


        return response()->json([], 200);
    }

    public function resetAvatar(User $user, Request $request)
    {
        $user = User::findOrFail($user->id);
        $old_avatar = $user->avatar;
        if($old_avatar != config('dofus.default_avatar'))
        {
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
}
