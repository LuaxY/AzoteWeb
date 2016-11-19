<?php

namespace App\Services\Payment;

use \Cache;
use App\Services\Payment;
use Auth;

class Starpass extends Payment
{
    private $rates;
    private $points;

    const CACHE_EXPIRE_MINUTES = 1440; // 1 day

    public function __construct()
    {
        $json = null;

        if (Cache::has('payment.starpass'))
        {
            $json = Cache::get('payment.starpass');
        }
        else
        {
            $url = config('dofus.payment.starpass.url');
            $url = str_replace('{IDD}', config('dofus.payment.starpass.idd'), $url);
            $file = file_get_contents($url);

            preg_match('/oSmsAudiotelDataDoc'.config('dofus.payment.starpass.idd').' = {(.*)};/', $file, $matches);

            $json = json_decode("{".$matches[1]."}");

            Cache::add('payment.starpass', $json, self::CACHE_EXPIRE_MINUTES);
        }

        $this->points = config('dofus.payment.starpass.points');
        $this->rates  = new \stdClass;

        if ($json)
        {
            foreach ($json as $countryName => $country)
            {
                $this->rates->$countryName = new \stdClass;
                $palier = "one";

                foreach ($country as $methodName => $method)
                {
                    $this->rates->$countryName->$methodName = new \stdClass;

                    $newMethod = new \stdClass;
                    $newMethod->devise = $method->sCurrencyToDisplay;
                    $newMethod->points = $this->points;

                    if ($methodName == "sms")
                    {
                        $newMethod->number  = $method->smsPhoneNumber;
                        $newMethod->keyword = $method->smsKeyword;
                        $newMethod->cost    = $method->smsCostDetail;
                        $newMethod->text    = "{$method->smsCostDetail}/SMS + prix d'un SMS<br>1 envoi de SMS par code d'accès";
                    }

                    if ($methodName == "audiotel" || $methodName == "mobilecall")
                    {
                        $newMethod->number = $method->audiotelPhone;
                        $newMethod->cost   = $method->audiotelFixedCostDetail;
                        $newMethod->text   = "{$method->fCostPerAction}/appel depuis une ligne fixe + coût d'un appel, {$method->iActionQuantity} appel requis.<br>Coût : ".(intval($method->iActionQuantity) * $method->fTotalCost)." {$method->sCurrencyToDisplay}";
                    }

                    $newMethod->legal = new \stdClass;
                    $newMethod->legal->header    = null;
                    $newMethod->legal->phone     = null;
                    $newMethod->legal->shortcode = null;
                    $newMethod->legal->keyword   = null;
                    $newMethod->legal->footer    = null;

                    if ($countryName == "fr" && $methodName == "sms")
                    {
                        $newMethod->legal->shortcode = '<img src="/images/smsasterix.png" style="width: 16px;vertical-align: 0px;margin-left: 3px;">';
                        $newMethod->legal->footer    = '<img src="/images/smsplus.png" style="width: 100px;margin-top: 5px;">';
                    }

                    $this->rates->$countryName->$methodName->$palier = $newMethod;
                }
            }

            $sortArray = (array)$this->rates;

            uksort($sortArray, function($a, $b) {
                return $a == "fr" ? -1 : 1;
            });

            $this->rates = (object)$sortArray;
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

    public function check($palier, $code)
    {
        $check = new \stdClass;
        $check->code = $code;
        $check->error = false;

        $idp = config('dofus.payment.starpass.idp');
        $idd = config('dofus.payment.starpass.idd');
        $validation = config('dofus.payment.starpass.validation');

        $validation = str_replace('{KEY}',  $idp.";;".$idd, $validation);
        $validation = str_replace('{CODE}', $code,          $validation);

        $check->provider = config('dofus.payment.starpass.name');

        $result = @file_get_contents($validation);

        $data = explode("|", $result);

        $check->raw = $result;

        if (isset($data[1]) && $data[0] == "OUI")
        {
            $check->success = true;

            if (!$data[2] || $data[2] == "")
            {
                if (Auth::user()->isAdmin())
                {
                    $check->country     = 'xx';
                    $check->type        = 'test';
                    $check->palier_name = "TEST";
                    $check->palier_id   = 0;
                }
                else
                {
                    $check->success = false;
                    $check->message = "Rang insuffisant pour utiliser le code de test";
                    return $check;
                }
            }
            elseif (count($data) >= 5)
            {
                $check->country     = strtolower($data[2]);
                $check->type        = strtolower($data[5]);
                $check->palier_name = strtolower($data[3]);
                $check->palier_id   = $data[4];
            }
            else
            {
                $check->success = false;
                $check->message = "Erreur lors de la validation";
                return $check;
            }

            $check->points  = $this->points;
            $check->message = "Code validé";
            $check->payout  = "";
        }
        else
        {
            $check->message = "Code incorrect";
            $check->success = false;
        }

        return $check;
    }
}
