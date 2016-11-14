<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lottery;
use App\Shop\ShopStatus;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Cache;

class LotteryController extends Controller
{
    public function tickets()
    {
        return view('admin.lottery.tickets');
    }

    public function index()
    {
        $lotteryTypes = Cache::remember('lottery_admin', 10, function()
        {
            return Lottery::all();
        });

        return view('admin.lottery.index', compact('lotteryTypes'));
    }

    public function edit(Lottery $lottery)
    {
        $type = Lottery::findOrFail($lottery->id);
        return view('admin.lottery.edit', compact('type'));
    }
}
