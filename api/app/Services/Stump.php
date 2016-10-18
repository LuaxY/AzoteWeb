<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7;

use App\Transfert;

use Auth;

class Stump
{
    public static function transfert($server, $accountId, $type, $amount, $url, $successCallback)
    {
        $transfert = new Transfert;
        $transfert->user_id    = Auth::user()->id;
        $transfert->account_id = $accountId;
        $transfert->server     = $server;
        $transfert->state      = Transfert::IN_PROGRESS;
        $transfert->amount     = $amount;
        $transfert->type       = $type;
        $transfert->save();

        $api = config('dofus.details')[$server];
        $success = false;

        $success = false;

        try {
            $client = new Client();
            $res = $client->request('PUT', "http://{$api->ip}:{$api->port}$url", [
                'headers' => [
                    'APIKey' => config('dofus.api_key')
                ],
                'timeout' => 10, // seconds
            ]);

            if ($res->getStatusCode() == 200) {
            // Server return 200 (Good)
                $successCallback();

                $transfert->state  = Transfert::OK_API;
                //$transfert->rawIn  = Psr7\str($res->getRequest());
                //$transfert->rawOut = Psr7\str($res->getResponse());
                $transfert->save();

                $success = true;
            } else {
                // Server return 2xx (Bad)
                $transfert->state  = Transfert::REFUND;
                //$transfert->rawIn  = Psr7\str($res->getRequest());
                //$transfert->rawOut = Psr7\str($res->getResponse());
                $transfert->save();

                $success = false;
            }
        } catch (ServerException $e) {
        // Server return 5xx error
            // Not success but call it to avoid duplication
            $successCallback();

            $transfert->state  = Transfert::FAIL;
            $transfert->rawIn  = Psr7\str($e->getRequest());
            $transfert->rawOut = Psr7\str($e->getResponse());
            $transfert->save();

            $success = false;
        } catch (TransferException $e) {
        // Other errors
            $transfert->state  = Transfert::REFUND;
            $transfert->rawIn  = Psr7\str($e->getRequest());

            if ($e->hasResponse()) {
                $transfert->rawOut = Psr7\str($e->getResponse());
            } else {
                $transfert->rawOut = "NO RESPONSE";
            }

            $transfert->save();

            $success = false;
        }

        return $success;
    }
}
