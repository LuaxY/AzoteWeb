<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class ShopMaintenance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $characterCount = 0;

        foreach (Auth::user()->accounts() as $account)
        {
            $characterCount += count($account->characters(false, true));
        }

        $canBuy = $characterCount > 0;

        if (config('dofus.shop_maintenance') || !$canBuy)
        {
            $request->session()->flash('notify', ['type' => 'warning', 'message' => "Boutique en maintenance."]);
            return redirect()->to('shop/maintenance');
        }

        return $next($request);
    }
}
