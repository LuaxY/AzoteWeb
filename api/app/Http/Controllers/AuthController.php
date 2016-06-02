<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\User;

use Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth/login');
    }

    public function auth(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();

		if ($user && ($user->password === $user->hashPassword($request->input('password'), $user->salt)))
		{
			Auth::login($user);
			return redirect(route('profile'));
		}
		else
		{
			return redirect()->back()->withErrors(['auth' => 'Nom de compte ou mot de passe incorrect.'])->withInput();
		}
    }

    public function logout()
	{
		if (Auth::check())
		{
			Auth::logout();
		}
		return redirect('/');
	}
}
