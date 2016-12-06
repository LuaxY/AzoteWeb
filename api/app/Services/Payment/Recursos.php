<?php

namespace App\Services\Payment;

use \Cache;
use App\Services\Payment;
use Auth;

use App\Transaction;
use App\RecursosTransaction;
use App\Shop\ShopStatus;

class Recursos extends Payment
{
    private $rates;

    const CACHE_EXPIRE_MINUTES = 1440; // 1 day

    public function __construct()
    {
        $this->rates = new \stdClass;

        $prices = explode('|', config('dofus.payment.recursos.prices'));
        $coeff  = config('dofus.payment.recursos.coeff');

        if ($prices)
        {
            $countryName = 'fr';
            $methodName  = 'carte bancaire';

            $this->rates->$countryName = new \stdClass;
            $this->rates->$countryName->$methodName = new \stdClass;

            foreach ($prices as $price)
            {
                $newMethod = new \stdClass;

                $newMethod->devise   = "&euro;";
                $newMethod->points   = $price * $coeff;
                $newMethod->cost     = $price . " " . $newMethod->devise;
                $newMethod->text     = "";
                $newMethod->link     = route('redirect_recursos_cb', [null]);
                $newMethod->recursos = true;

                $newMethod->legal = new \stdClass;
                $newMethod->legal->header    = null;
                $newMethod->legal->phone     = null;
                $newMethod->legal->shortcode = null;
                $newMethod->legal->keyword   = null;
                $newMethod->legal->footer    = null;

                $palier = substr(md5($newMethod->cost), 0, 5);

                $this->rates->$countryName->$methodName->$palier = $newMethod;
            }
        }
    }

    public function rates()
    {
        return $this->rates;
    }

    public function palier($country, $method, $palier)
    {
        if (property_exists($this->rates, $country) &&
            property_exists($this->rates->$country, $method) &&
            property_exists($this->rates->$country->$method, $palier))
        {
            return $this->rates->$country->$method->$palier;
        }

        return null;
    }

    public function check($country, $method, $palier, $code)
    {

    }

    public function redirect_cb($key)
    {
        $params = [
            't'     => 'creditcard',
            'p'     => 3.50,
            'co'    => 'fr',
            'c'     => config('dofus.payment.recursos.c'),
            'w'     => config('dofus.payment.recursos.w'),
            'email' => Auth::user()->email,
        ];

        $c = curl_init("https://iframes.recursosmoviles.com/v3/redirect.php?id=$key");
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_REFERER, 'https://iframes.recursosmoviles.com');
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
        $page = curl_exec($c);
        curl_close($c);

        return $page;
    }

    public function check_cb($key)
    {
        $recursos = RecursosTransaction::where('key', $key)->first();

        if (!$recursos)
        {
            $c = curl_init("https://iframes.recursosmoviles.com/v3/checkid.php?id=$key");
            curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($c, CURLOPT_REFERER, 'https://iframes.recursosmoviles.com');
            curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
            $code = curl_exec($c);
            curl_close($c);

            $recursos = new RecursosTransaction;
            $recursos->user_id = Auth::user()->id;
            $recursos->key     = $key;
            $recursos->code    = $code;
            $recursos->points  = 0;
            $recursos->price   = 3.50;
            $recursos->save();
        }

        $params = [
            't'     => 'creditcard',
            'p'     => $recursos->price,
            'co'    => 'fr',
            'c'     => config('dofus.payment.recursos.c'),
            'w'     => config('dofus.payment.recursos.w'),
            'code'  => $recursos->code,
        ];

        $c = curl_init("https://iframes.recursosmoviles.com/v3/checkcode.php");
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_REFERER, 'https://iframes.recursosmoviles.com');
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
        $result = curl_exec($c);
        curl_close($c);

        $data = explode(':', $result);

        if ($data[0] == "OK")
        {
            $transaction = new Transaction;
            $transaction->user_id     = Auth::user()->id;
            $transaction->state       = ShopStatus::PAYMENT_SUCCESS;
            $transaction->code        = $recursos->code;
            $transaction->points      = $recursos->points;
            $transaction->country     = "all";
            $transaction->palier_name = "??";
            $transaction->palier_id   = 0;
            $transaction->type        = "carte bancaire";
            $transaction->provider    = "Recursos";
            $transaction->raw         = $result;
            $transaction->save();

            Cache::forget('transactions_' . Auth::user()->id);
            Cache::forget('transactions_' . Auth::user()->id . '_10');

            Auth::user()->points += $recursos->points;
            Auth::user()->save();

            return true;
        }

        return false;
    }
}
