<?php

namespace App\Services\Payment;

use \Cache;
use App\Services\Payment;
use App\Helpers\CloudFlare;

use Auth;

class PayFee extends Payment
{
    private $rates;

    public function __construct()
    {
        $this->rates = new \stdClass;

        $countryName   = 'fr';
        $methodName    = 'audiotel';

        $this->rates->$countryName = new \stdClass;
        $this->rates->$countryName->$methodName = new \stdClass;

		$rate = config('dofus.payment.payfee.rate');
		$user = config('dofus.payment.payfee.user');
		$private_key = config('dofus.payment.payfee.private');
		
		$ClientIP = CloudFlare::ip();
		
		$token = file_get_contents('http://pay-fee.com/api/rate/token?user=' . $user . '&rate=' . $rate . '&ip=' . $ClientIP . '&identifier=1&private_key=' . $private_key);

        $newMethod = new \stdClass;

        $newMethod->devise   = "&euro;";
        $newMethod->points   = 105;
        $newMethod->price    = 3;
        $newMethod->cost     = "3 " . $newMethod->devise;
        $newMethod->text     = "";
        $newMethod->link     = "http://pay-fee.com/api/rate?token=$token";
        $newMethod->payfee   = true;

        $newMethod->legal = new \stdClass;
        $newMethod->legal->header    = null;
        $newMethod->legal->phone     = null;
        $newMethod->legal->shortcode = null;
        $newMethod->legal->keyword   = null;
        $newMethod->legal->footer    = null;

        $palier = substr(md5($newMethod->cost), 0, 5);

        $this->rates->$countryName->$methodName->$palier = $newMethod;
		
        $prices = explode('|', config('dofus.payment.recursos.prices'));
        $coeff  = config('dofus.payment.recursos.coeff');

        if ($prices) {
			$methodNameCB  	= 'carte bancaire';
            $methodNamePS  	= 'paysafecard';

            $this->rates->$countryName->$methodNameCB = new \stdClass;
            $this->rates->$countryName->$methodNamePS = new \stdClass;

			$cid = config('dofus.payment.recursos.c_sms');
			$wmid = config('dofus.payment.recursos.w');
			
			$CBMethod = new \stdClass;
			
			$CBMethod->devise   = "&euro;";
            $CBMethod->points   = 0;
            $CBMethod->price    = 0;
            $CBMethod->cost     = "Paliers sur la prochaine page";
            $CBMethod->text     = "";
            $CBMethod->link     = "https://iframes.recursosmoviles.com/v3/?wmid=$wmid&cid=$cid&c=fr&m=creditcard&h=paysafecard&pcreditcard=210 Ogrines,245 Ogrines,560 Ogrines,700 Ogrines,1750 Ogrines,3500 Ogrines,5250 Ogrines,7000 Ogrines";
            $CBMethod->recursos = true;

            if (config('app.env') == 'production') {
                $CBMethod->link = str_replace('http:', 'https:', $CBMethod->link);
            }

            $CBMethod->legal = new \stdClass;
            $CBMethod->legal->header    = null;
            $CBMethod->legal->phone     = null;
            $CBMethod->legal->shortcode = null;
            $CBMethod->legal->keyword   = null;
            $CBMethod->legal->footer    = null;
			
			$PSMethod = new \stdClass;
			
			$PSMethod->devise   = "&euro;";
            $PSMethod->points   = 0;
            $PSMethod->price    = 0;
            $PSMethod->cost     = "Paliers sur la prochaine page";
            $PSMethod->text     = "";
            $PSMethod->link     = "https://iframes.recursosmoviles.com/v3/?wmid=$wmid&cid=$cid&c=fr&m=paysafecard&&h=creditcard&ppaysafecard=210 Ogrines,245 Ogrines,560 Ogrines,700 Ogrines,1750 Ogrines,3500 Ogrines,5250 Ogrines,7000 Ogrines";
            $PSMethod->recursos = true;

            if (config('app.env') == 'production') {
                $PSMethod->link = str_replace('http:', 'https:', $PSMethod->link);
            }

            $PSMethod->legal = new \stdClass;
            $PSMethod->legal->header    = null;
            $PSMethod->legal->phone     = null;
            $PSMethod->legal->shortcode = null;
            $PSMethod->legal->keyword   = null;
            $PSMethod->legal->footer    = null;
			
			$palier = substr(md5($CBMethod->cost), 0, 5);
            $this->rates->$countryName->$methodNameCB->$palier = $CBMethod;
			
			$palier = substr(md5($PSMethod->cost), 0, 5);
            $this->rates->$countryName->$methodNamePS->$palier = $PSMethod;
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
            property_exists($this->rates->$country->$method, $palier)) {
            return $this->rates->$country->$method->$palier;
        }

        return null;
    }

    public function check($country, $method, $palier, $code)
    {
        $check = new \stdClass;
        $check->code = $code;
        $check->error = false;

        $check->provider = config('dofus.payment.dedipass.name');

		$rate = 2;
		$user = 11;
		$ClientIP = CloudFlare::ip();
		
        $json   = @file_get_contents("http://pay-fee.com/api/pay?user=$user&rate=$rate&ip=$ClientIP&identifier=1&code=$code");

        $result = json_decode($json);

        $check->raw = $json;

        if (isset($result->status) && $result->status == "success") {
            $check->success = true;
			
			$check->country 	= $country;
            $check->type    	= $method;
			$check->palier_name = $palier;
            $check->palier_id   = 0;
            $check->points      = 105;
            $check->message     = $result->error;
            $check->payout      = 105;
        } else {
            $check->message = isset($result->error) ? $result->error : "Aucune réponse du serveur de validation";
            $check->success = false;
        }

        return $check;
    }
}
