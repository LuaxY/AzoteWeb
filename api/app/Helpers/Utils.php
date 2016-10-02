<?php

namespace App\Helpers;

use App\Shop\ShopStatus;
use App\Transfert;

class Utils
{
    static public function transaction_status($status)
    {
        switch ($status)
        {
            case ShopStatus::PAYMENT_SUCCESS:
                $status_text = "Validé";
                break;
            case ShopStatus::PAYMENT_FAIL:
                $status_text = "Echec";
                break;
            case ShopStatus::PAYMENT_ERROR:
            default:
                $status_text = "Erreur";
                break;
        }

        return $status_text;
    }

    static public function transfert_status($status)
    {
        switch ($status)
        {
            case Transfert::IN_PROGRESS:
                $status_text = "En cours";
                break;
            case Transfert::OK_SQL:
            case Transfert::OK_API:
                $status_text = "Terminée";
                break;
            case Transfert::REFUND:
                $status_text = "Remboursé";
                break;
            case Transfert::FAIL:
            default:
                $status_text = "Echoué";
                break;
        }

        return $status_text;
    }

    static public function format_price($price, $delimiter = ' ')
    {
        return number_format(round($price, 0, PHP_ROUND_HALF_DOWN), 0, ",", $delimiter);
    }
}
