<?php

namespace App\Services\Modules;

use App\Models\PaymentOrder;

class ServicePaymentOrder
{

    public static function send(PaymentOrder $paymentOrder){
        echo 'send from service';

        $result['status'] = 'false';

        return $result;
            dd($paymentOrder);
    }
}
