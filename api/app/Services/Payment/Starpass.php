<?php

namespace App\Services\Payment;

use \Cache;
use App\Services\Payment;
use Auth;

class Starpass extends Payment
{
    private $rates;

    const CACHE_EXPIRE_MINUTES = 1440; // 1 day

    public function __construct()
    {
        $this->rates  = new \stdClass;

        if (!config('dofus.payment.starpass.config'))
        {
            return;
        }

        $configs = explode('|', config('dofus.payment.starpass.config'));

        foreach ($configs as $config)
        {
            $config = explode(';', $config);

            $idp    = $config[0];
            $idd    = $config[1];
            $points = $config[2];

            $json = null;

            if (Cache::has('payment.starpass.'.$idd))
            {
                $json = Cache::get('payment.starpass.'.$idd);
            }
            else
            {
                $url = config('dofus.payment.starpass.url');
                $url = str_replace('{IDD}', $idd, $url);
                $file = file_get_contents($url);

                preg_match('/oSmsAudiotelDataDoc'.$idd.' = {(.*)};/', $file, $matchesSmsAudiotel);

                if (isset($matchesSmsAudiotel[1]))
                {
                    $json['SmsAudiotel'] = json_decode("{".$matchesSmsAudiotel[1]."}");
                }

                preg_match('/oNoSmsNoAudiotelTariffDataJsonDoc'.$idd.' = {(.*)};/', $file, $matchesOther);

                if (isset($matchesOther[1]))
                {
                    $json['Other'] = json_decode("{".$matchesOther[1]."}");
                }

                Cache::add('payment.starpass.'.$idd, $json, self::CACHE_EXPIRE_MINUTES);
            }

            if ($json)
            {
                foreach ($json as $docType)
                {
                    foreach ($docType as $countryName => $country)
                    {
                        if ($countryName == "xx")
                        {
                            continue;
                        }

                        if (!property_exists($this->rates, $countryName))
                        {
                            $this->rates->$countryName = new \stdClass;
                        }

                        foreach ($country as $methodName => $method)
                        {
                            if ($methodName == "cb")
                            {
                                $methodName = "carte bancaire";
                            }

                            $allowed = ['carte bancaire', 'sms', 'audiotel', 'mobilecall']; // paypal, wha, dtmp, sofort

                            if (!in_array($methodName, $allowed))
                            {
                                continue;
                            }

                            if (!property_exists($this->rates->$countryName, $methodName))
                            {
                                $this->rates->$countryName->$methodName = new \stdClass;
                            }

                            $palier = $methodName . '-' . $points;
                            $palier = substr(md5($palier), 0, 5);

                            $newMethod = new \stdClass;
                            $newMethod->points = $points;
                            $newMethod->idp    = $idp;
                            $newMethod->idd    = $idd;

                            if ($methodName == "carte bancaire")
                            {
                                $newMethod->cost   = $method->sCodeString;
                                $newMethod->devise = $method->sCodeCurrency;
                                $newMethod->text   = "";
                                $newMethod->link   = route('code_starpass_cb');
                            }

                            if ($methodName == "sms")
                            {
                                $newMethod->devise  = $method->sCurrencyToDisplay;
                                $newMethod->number  = $method->smsPhoneNumber;
                                $newMethod->keyword = $method->smsKeyword;
                                $newMethod->cost    = $method->smsCostDetail;
                                $newMethod->text    = "{$method->smsCostDetail}/SMS + prix d'un SMS<br>1 envoi de SMS par code d'accès";
                            }

                            if ($methodName == "audiotel" || $methodName == "mobilecall")
                            {
                                $newMethod->devise = $method->sCurrencyToDisplay;
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
                }

                $sortArray = (array)$this->rates;

                uksort($sortArray, function($a, $b) {
                    return $a == "fr" ? -1 : 1;
                });

                $this->rates = (object)$sortArray;
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
        $check = new \stdClass;
        $check->code = $code;
        $check->error = false;

        $palier = $this->palier($country, $method, $palier);

        $idp = $palier->idp;
        $idd = $palier->idd;
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

            $check->points  = $palier->points;
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
