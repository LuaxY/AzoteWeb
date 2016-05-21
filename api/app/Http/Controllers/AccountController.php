<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Account;

use Validator;
use Auth;

class AccountController extends Controller
{
    public function register(Request $request)
    {
        return view('account/register');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), User::$rules['register']);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $salt = str_random(8);

        $user = new User;
        $user->email     = $request->input('email');
        $user->password  = $user->hashPassword($request->input('password'), $salt);
        $user->salt      = $salt;
        $user->firstname = $request->input('firstname');
        $user->lastname  = $request->input('lastname');
        $user->save();

        Auth::login($user);

        return redirect('/');
    }

    public function profile()
    {
        $accounts = Auth::user()->accounts();

        /*if (Auth::user()->cannot('user-edit')) {
            abort(403);
        }*/

        return view('account/profile', compact('accounts'));
    }

    public function update(Request $request)
    {
        if ($request->input('firstname') && $request->input('lastname'))
        {
            $validator = Validator::make($request->all(), User::$rules['update-name']);

            if ($validator->fails())
            {
                return $this->error(401, 'nom/prénom incorrect', $validator->errors()->all());
            }

            Auth::user()->firstname = $request->input('firstname');
            Auth::user()->lastname = $request->input('lastname');
            Auth::user()->update([
                'firstname' => Auth::user()->firstname,
                'lastname'  => Auth::user()->lastname,
            ]);
        }

        if ($request->input('password') && $request->input('passwordConfirmation'))
        {
            $validator = Validator::make($request->all(), User::$rules['update-password']);

            if ($validator->fails())
            {
                return $this->error(401, 'mot de passe incorrect', $validator->errors()->all());
            }

            Auth::user()->salt     = str_random(8);
            Auth::user()->password = Auth::user()->hashPassword($request->input('password'), Auth::user()->salt);
            Auth::user()->update([
                'password' => Auth::user()->password,
                'salt'     => Auth::user()->salt,
            ]);
        }

        return $this->success('profile mis à jour');
    }
}
