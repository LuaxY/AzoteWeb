<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\User;
use App\ForumAccount;

use Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth/login');
    }

    public function auth(Request $request)
    {
        $clientIp = $request->ip();
        $user = User::where('email', $request->input('email'))->first();

        if ($user && ($user->password === $user->hashPassword($request->input('password'), $user->salt)))
        {
            if (!$user->active)
            {
                $request->session()->flash('notify', ['type' => 'warning', 'message' => "Votre compte n'est pas activé, vérifiez vos emails."]);
                return redirect()->back()->withErrors(['auth' => 'Votre compte n\'est pas activé, vérifiez vos emails.'])->withInput();
            }
            if ($user->isBanned())
            {
                $request->session()->flash('notify', ['type' => 'warning', 'message' => "Votre compte est banni."]);
                return redirect()->back()->withErrors(['auth' => 'Votre compte est banni.'])->withInput();
            }
            $user->last_ip_address = $clientIp;
            $user->save();

            if($request->remember)
            {
                Auth::login($user, true);
            }
            else
            {
                Auth::login($user);
            }

            $forumAccount = ForumAccount::find($user->forum_id);

            if ($forumAccount)
            {
                $forumAccount->member_login_key = str_random(32);
                $forumAccount->save();

                setcookie('ips4_member_id', $forumAccount->member_id,        0, '/', config('dofus.forum.domain'));
                setcookie('ips4_pass_hash', $forumAccount->member_login_key, 0, '/', config('dofus.forum.domain'));
            }

            return redirect()->route('profile');
        }
        else
        {
            $request->session()->flash('notify', ['type' => 'error', 'message' => "Nom de compte ou mot de passe incorrect."]);
            return redirect()->back()->withErrors(['auth' => 'Nom de compte ou mot de passe incorrect.'])->withInput();
        }
    }

    public function logout()
    {
        if (Auth::check())
        {
            $forumAccount = ForumAccount::find(Auth::user()->forum_id);

            if ($forumAccount)
            {
                $forumAccount->member_login_key = '';
                $forumAccount->save();

                setcookie('ips4_member_id',       '', time()-3600, '/', config('dofus.forum.domain'));
                setcookie('ips4_pass_hash',       '', time()-3600, '/', config('dofus.forum.domain'));
                setcookie('ips4_IPSSessionFront', '', time()-3600, '/', config('dofus.forum.domain'));
            }

            Auth::logout();
        }
        return redirect('/');
    }
}
