<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Account;

use Mail;
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
        $user->active    = false;
        $user->ticket    = str_random(32);
        $user->save();

        Mail::send('emails.welcome', ['user' => $user], function ($message) use ($user) {
            $message->from('welcome@azote.us', 'Azote.us');
            $message->to($user->email, $user->firstname . ' ' . $user->lastname);
            $message->subject('Azote.us - Confirmation d\'inscription');
        });

        $request->session()->flash('msg_flash', "Vous allez recevoir un email d'activation !");

        return redirect('/');
    }

    public function activation($ticket)
    {
        $user = User::where('ticket', $ticket)->first();

        if (!$user)
        {
            request()->session()->flash('msg_flash', "Clé d'activation invalide.");

            return redirect('/');
        }

        $user->ticket = null;
        $user->active = true;
        $user->update([
            'ticket' => $user->ticket,
            'active' => $user->active
        ]);

        Auth::login($user);

        request()->session()->flash('msg_flash', "Bienvenu {$user->firstname} !");

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
