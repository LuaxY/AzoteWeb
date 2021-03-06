<?php

namespace App\Services\Payment;

use \Cache;
use App\Services\Payment;
use App\Helpers\CloudFlare;

use Auth;

class OneoPay extends Payment
{
    private $rates;

    public function __construct()
    {
        $this->rates = new \stdClass;

        $countryName   = 'fr';
        $methodName    = 'audiotel';

        $this->rates->$countryName = new \stdClass;
        $this->rates->$countryName->$methodName = new \stdClass;
		
        $palier = "FR-AUDIOTEL-300-A";
		$rate = json_decode(file_get_contents('http://api.oneopay.com/rates/?rate=FR-AUDIOTEL-300-A'));

        $newMethod = new \stdClass;

        $newMethod->devise   = "&euro;";
        $newMethod->points   = 150;
        $newMethod->price    = 3;
        $newMethod->cost     = "3 " . $newMethod->devise;
        $newMethod->number   = $rate->call;
        $newMethod->text   = "3€/appel depuis une ligne fixe + coût d'un appel, 1 appel requis.<br>Coût : 3€";

        $newMethod->legal = new \stdClass;
        $newMethod->legal->header    = null;
        $newMethod->legal->phone     = null;
        $newMethod->legal->shortcode = null;
        $newMethod->legal->keyword   = null;
        $newMethod->legal->footer    = null;

        $this->rates->$countryName->$methodName->$palier = $newMethod;
		
		$pricesCB = explode('|', config('dofus.payment.recursos.pricesCB'));
		$pricesPS = explode('|', config('dofus.payment.recursos.pricesPS'));
		$coeffCB  = config('dofus.payment.recursos.coeffCB');
		$coeffPS  = config('dofus.payment.recursos.coeffPS');
				
		$cid = config('dofus.payment.recursos.c_sms');
		$wmid = config('dofus.payment.recursos.w');
					
		if ($pricesCB) {
			$methodName = 'carte bancaire';

			$this->rates->$countryName->$methodName = new \stdClass;
			
			foreach ($pricesCB as $price) {
				$Method = new \stdClass;
					
				$Method->devise   = "&euro;";
				$Method->points   = $price * $coeffCB;
				$Method->price    = $price;
				$Method->cost     = $price . " " . $Method->devise;
				$Method->text     = "";
				//$Method->link     = "https://iframes.recursosmoviles.com/v3/?wmid=$wmid&cid=$cid&c=fr&m=creditcard&h=paysafecard&pcreditcard=240 Ogrines,280 Ogrines,640 Ogrines,800 Ogrines,2000 Ogrines,4000 Ogrines,6000 Ogrines,8000 Ogrines";
				$Method->link	  = route('redirect_recursos_cb');
				$Method->recursos = true;

				if (config('app.env') == 'production') {
					//$Method->link = str_replace('http:', 'https:', $Method->link);
				}

				$Method->legal = new \stdClass;
				$Method->legal->header    = null;
				$Method->legal->phone     = null;
				$Method->legal->shortcode = null;
				$Method->legal->keyword   = null;
				$Method->legal->footer    = null;		
				
				$palier = substr(md5($Method->cost), 0, 5);
				$this->rates->$countryName->$methodName->$palier = $Method;
			}
		}
				
		if ($pricesPS) {
			$methodName = 'paysafecard';

			$this->rates->$countryName->$methodName = new \stdClass;
					
			foreach ($pricesPS as $price) {
				$Method = new \stdClass;
						
				$Method->devise   = "&euro;";
				$Method->points   = $price * $coeffPS;
				$Method->price    = $price;
				$Method->cost     = $price . " " . $Method->devise;
				$Method->text     = "";
				$Method->link     = "https://iframes.recursosmoviles.com/v3/?wmid=$wmid&cid=$cid&c=fr&m=paysafecard&&h=creditcard&ppaysafecard=210 Ogrines,245 Ogrines,560 Ogrines,700 Ogrines,1750 Ogrines,3500 Ogrines,5250 Ogrines,7000 Ogrines";
				$Method->recursos = true;

				if (config('app.env') == 'production') {
					//$Method->link = str_replace('http:', 'https:', $Method->link);
				}

				$Method->legal = new \stdClass;
				$Method->legal->header    = null;
				$Method->legal->phone     = null;
				$Method->legal->shortcode = null;
				$Method->legal->keyword   = null;
				$Method->legal->footer    = null;	
						
				$palier = substr(md5($Method->cost), 0, 5);
				$this->rates->$countryName->$methodName->$palier = $Method;
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

        $check->provider = "OneoPay";

		$service = 331;
		
        $json   = @file_get_contents("http://checkcode.oneopay.com?service=$service&rate=$palier&code=$code");

        $result = json_decode($json);

        $check->raw = $json;

        if (isset($result->status) && $result->status == "success") {
            $check->success = true;
			
			$check->country 	= $country;
            $check->type    	= $method;
			$check->palier_name = $palier;
            $check->palier_id   = 0;
            $check->points      = 150;
            $check->message     = "Success";
            $check->payout      = 105;
        } else {
            $check->message = isset($result->message) ? $result->message : "Aucune réponse du serveur de validation";
            $check->success = false;
        }

        return $check;
    }
}