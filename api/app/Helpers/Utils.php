<?php

namespace App\Helpers;

use App\Shop\ShopStatus;

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

    static public function format_price($price, $delimiter = ".")
    {
        return number_format($price, 0, ",", $delimiter);
    }
}
