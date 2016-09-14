<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Account;
use App\ForumAccount;

use Mail;
use Validator;
use Auth;
use Cookie;

class AccountController extends Controller
{
    const SALT_LENGTH   = 8;
    const TICKET_LENGTH = 32;

    public function register()
    {
        if (!Auth::guest())
        {
            return redirect()->route('download');
        }
        return view('account/register');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), User::$rules['register']);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $salt = str_random(self::SALT_LENGTH);

        $user = new User;
        $user->email     = $request->input('email');
        $user->password  = $user->hashPassword($request->input('password'), $salt);
        $user->salt      = $salt;
        $user->firstname = $request->input('firstname');
        $user->lastname  = $request->input('lastname');
        $user->active    = false;
        $user->ticket    = str_random(self::TICKET_LENGTH);
        $user->save();

        // TODO validator for forum account
        // TODO store forum account id in user

        $forumAccount = new ForumAccount;
        $forumAccount->name              = $request->input('firstname');
        $forumAccount->member_group_id   = config('dofus.forum.user_group');
        $forumAccount->email             = $request->input('email');
        $forumAccount->joined            = time();
        $forumAccount->ip_address        = '';
        $forumAccount->member_login_key  = str_random(32);
        $forumAccount->members_seo_name  = strtolower($request->input('firstname'));
        $forumAccount->members_pass_salt = $forumAccount->generateSalt();
        $forumAccount->members_pass_hash = $forumAccount->encryptedPassword($request->input('password'));
        $forumAccount->timezone          = 'Europe/Paris';

        $forumAccount->save();

        $forumAccount = ForumAccount::where('email', $forumAccount->email)->first();

        setcookie('ips4_member_id', $forumAccount->member_id,        0, '/', config('dofus.forum.domain'));
        setcookie('ips4_pass_hash', $forumAccount->member_login_key, 0, '/', config('dofus.forum.domain'));

        Mail::send('emails.welcome', ['user' => $user], function ($message) use ($user) {
            $message->from(config('mail.sender'), 'Azote.us');
            $message->to($user->email, $user->firstname . ' ' . $user->lastname);
            $message->subject('Azote.us - Confirmation d\'inscription');
        });

        $request->session()->flash('notify', ['type' => 'info', 'message' => "Vous allez recevoir un email d'activation !"]);

        return redirect('/');
    }

    public function activation($ticket = null)
    {
        $user = User::where('ticket', $ticket)->first();

        if (!$user)
        {
            request()->session()->flash('notify', ['type' => 'error', 'message' => "Clé d'activation invalide."]);
            return redirect('/');
        }

        $user->ticket = str_random(self::TICKET_LENGTH);
        $user->active = true;
        $user->update([
            'ticket' => $user->ticket,
            'active' => $user->active
        ]);

        Auth::login($user);

        request()->session()->flash('notify', ['type' => 'success', 'message' => "Compte activé, bienvenu {$user->firstname} !"]);

        return redirect('/');
    }

    public function password_lost()
    {
        return view('account/password-lost');
    }

    public function passord_lost_email(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();

        if ($user)
        {
            $user->ticket = str_random(self::TICKET_LENGTH);
            $user->update([
                'ticket' => $user->ticket
            ]);

            Mail::send('emails.password', ['user' => $user], function ($message) use ($user) {
                $message->from(config('mail.sender'), 'Azote.us');
                $message->to($user->email, $user->firstname . ' ' . $user->lastname);
                $message->subject('Azote.us - Mot de passe oublié');
            });
        }

        request()->session()->flash('notify', ['type' => 'info', 'message' => "Un email de réinitialisation de mot de passe a été envoyé."]);

        return redirect('/');
    }

    public function reset_form($ticket = null)
    {
        $user = User::where('ticket', $ticket)->first();

        if (!$user)
        {
            request()->session()->flash('notify', ['type' => 'error', 'message' => "Clé d'activation invalide."]);
            return redirect('/');
        }

        Auth::login($user);

        request()->session()->flash('notify', ['type' => 'info', 'message' => "Veuillez changer votre mot de passe."]);

        return view('account/reset');
    }

    public function reset_password(Request $request)
    {
        $validator = Validator::make($request->all(), User::$rules['update-password']);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Auth::user()->salt     = str_random(self::SALT_LENGTH);
        Auth::user()->password = Auth::user()->hashPassword($request->input('password'), Auth::user()->salt);
        Auth::user()->ticket   = str_random(self::TICKET_LENGTH);
        Auth::user()->update([
            'password' => Auth::user()->password,
            'salt'     => Auth::user()->salt,
            'ticket'   => Auth::user()->ticket,
        ]);

        $request->session()->flash('notify', ['type' => 'success', 'message' => "Mot de passe mis à jour."]);

        return redirect()->route('profile');
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
                return redirect()->back()->withErrors($validator)->withInput();
                //return $this->error(401, 'nom/prénom incorrect', $validator->errors()->all());
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
                return redirect()->back()->withErrors($validator)->withInput();
                //return $this->error(401, 'mot de passe incorrect', $validator->errors()->all());
            }

            Auth::user()->salt     = str_random(self::SALT_LENGTH);
            Auth::user()->password = Auth::user()->hashPassword($request->input('password'), Auth::user()->salt);
            Auth::user()->update([
                'password' => Auth::user()->password,
                'salt'     => Auth::user()->salt,
            ]);
        }

        $request->session()->flash('notify', ['type' => 'success', 'message' => "Profile mis à jour."]);

        return redirect()->route('profile');
        //return $this->success('profile mis à jour');
    }
}
