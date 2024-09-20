<?php

namespace App\Helpers;


use App\Services\DibankService;
use App\Services\StyxService;

class PaymentOrderDocument
{

    /** шаблон для kapital-bank
    */
    public static function getTemplateKapital($paymentOrder, $partner,$info)
    {

        $uniq = StyxService::uniq($paymentOrder);

        $result = [
            "client_id" => $paymentOrder->company->kapital->client_id,
            "sid" => $paymentOrder->company->kapital->getSid(),
            "payment" => [
                "document" => [
                    "num" => $paymentOrder->number,
                    "branch" => null,
                    "general_id" => null,
                    "uniq" => $uniq,
                    "ddate" => date('d.m.Y',strtotime($paymentOrder->order_date)),
                    "mfo_dt" =>  $paymentOrder->company->mfo,
                    "acc_dt" => $paymentOrder->company->bank_code,
                    "name_dt" => $paymentOrder->company->name,
                    "inn_dt" => $paymentOrder->company->inn,
                    "mfo_ct" =>  $partner['Accounts'][0]['BankMfo'],
                    "acc_ct" => $partner['Accounts'][0]['AccountCode'],
                    "name_ct" => $partner['CompanyName'],
                    "inn_ct" => $partner['CompanyInn'],
                    "purpose" => $paymentOrder->purpose->title_ru,
                    "purp_code" => $paymentOrder->purpose->code,
                    "amount" => $paymentOrder->amount,
                    "dtype" => "35",
                    "state" => 52,
                    "dir" => 1,
                    "err" => "",
                    "err_msg" => "",
                    "anor" => 0
                ],
                "signs" => [
                    [
                        "method" => 5,
                        "sert_num" => $info['serial'],
                        "signature" => $info['signature'],
                    ],
                ]
            ]
        ];

        //$result = json_encode($result, JSON_UNESCAPED_UNICODE);

        Elog::save('contractDocument Kapital');
        Elog::save($result);

        return $result;

    }

    /** шаблон для dibank */
    public static function getTemplate($paymentOrder, $partner)
    {

        $result = [
            "method" => "createdoc",
            "document" => [
                "documentId" => $paymentOrder->id,
                "currentTin" => $paymentOrder->company->inn,
                "amount" => $paymentOrder->amount,
                "purpose" =>  $paymentOrder->comment,
                "dir" => $paymentOrder->owner,
                "doc_type" => DibankService::DOC_TYPE_PAYMENT_ORDER,
                "doc_number" => $paymentOrder->number,
                "doc_date" => $paymentOrder->order_date,
                "purpose_code" => $paymentOrder->purpose->code,
                "dt_mfo" => $paymentOrder->company->mfo,
                "dt_acc" =>  $paymentOrder->company->bank_code,
                "dt_name" => $paymentOrder->company->name,
                "dt_tin" => $paymentOrder->company->inn,
                "dt_cardNumber" => null,
                "dt_cardExpire_date" => null,
                "ct_mfo" => $partner['Accounts'][0]['BankMfo'],
                "ct_acc" => $partner['Accounts'][0]['AccountCode'],
                "ct_name" => $partner['CompanyName'],
                "ct_tin" =>  $partner['CompanyInn'],
                "ct_cardNumber" => null,
                "ct_treasuryAcc" => null,
                "ct_budgetAcc" => null,
                "bank_client_type" => null
            ]
        ];

        $result = json_encode($result, JSON_UNESCAPED_UNICODE);

        Elog::save('contractDocument Dibank');
        Elog::save($result);

        return $result;

    }

}
