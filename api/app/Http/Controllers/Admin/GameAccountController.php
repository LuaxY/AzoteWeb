<?php

namespace App\Http\Controllers\Admin;

use App\Account;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;

class GameAccountController extends Controller
{
    private function isServerExist($server)
    {
        if (!in_array($server, config('dofus.servers')))
        {
            return false;
        }

        return true;
    }

    public function index(User $user, $server)
    {
        if (!$this->isServerExist($server))
        {
            abort(404);
        }

        $user = User::findOrFail($user->id);
        $accounts = $user->accounts($server);
        return view('admin.users.servers.index', compact('accounts', 'user', 'server'));

    }

    public function edit(User $user, $server, $accountId)
    {
        if (!$this->isServerExist($server))
        {
            abort(404);
        }

        $user = User::findOrFail($user->id);
        $account = Account::on($server . '_auth')->where('Id', $accountId)->first();


        return view('admin.users.servers.edit', compact('user', 'server', 'account'));
    }

    public function update(User $user, $server, $accountId, Request $request)
    {

        if (!$this->isServerExist($server))
        {
            abort(404);
        }

        $account = Account::on($server . '_auth')->where('Id', $accountId)->first();

        return redirect()->back();

    }



}