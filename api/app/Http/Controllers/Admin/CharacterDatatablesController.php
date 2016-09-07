<?php

namespace App\Http\Controllers\Admin;

use App\Account;
use App\Character;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Post;

use App\User;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

class CharacterDatatablesController extends Controller
{
    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function anyData()
    {
        $accounts = Account::on('sigma_auth')->get();

        return Datatables::of($accounts)
            ->make(true);
    }




}
