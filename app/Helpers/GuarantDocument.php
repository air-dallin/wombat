<?php

namespace App\Helpers;

use App\Models\CompanyInvoice;
use App\Models\Contract;
use App\Models\Ikpu;


class GuarantDocument{

    public static function getTemplate($guarant,$productItems,$company){

        Elog::save('getTemplate');

        $productItems = self::getProductItems($productItems);

        $invoice = CompanyInvoice::where(['id'=>$guarant->company_invoice_id])->first();

        $contract = Contract::where(['id'=>$guarant->contract_id])->first();

        $result = [
            "empowermentdoc" => [
                "empowermentno" => $guarant->guarant_number,
                "empowermentdateofissue" => $guarant->guarant_date,
                "empowermentdateofexpire" => $guarant->guarant_date_expire
            ],
            "contractdoc" => [
                "contractno" => $contract->contract_number,
                "contractdate" => $contract->contract_date
            ],
            "agent" => [
                "jobtitle" => $guarant->guarant_position,
                "fio" => $guarant->guarant_fio,
                "passport" => [
                    "number" => $guarant->guarant_passport,
                    "issuedby" => $guarant->guarant_issue,
                    "dateofissue" => $guarant->guarant_issue_date
                ],
                "agenttin" => $guarant->guarant_pinfl,
                "agentempowermentid" => null
            ],
            "sellertin" => $guarant->partner_inn,
            "seller" => [
                "name" => $guarant->partner_company_name,
                "account" => $guarant->partner_bank_code,
                "bankid" => $guarant->partner_mfo,
                "address" => $guarant->partner_address,
                "director" => $guarant->partner_director,
                "accountant" => $guarant->partner_accountant
            ],
            "buyertin" => $guarant->company_inn,
            "productlist" => [
                "tin" => $guarant->company_inn,
                "hasexcise" => false,
                "hasvat" => true,
                "products" => $productItems,
                //"empowermentproductid" => null
            ],
            "buyer" => [
                "name" => $company->name,
                "account" => $invoice->bank_invoice,
                "bankid" => $guarant->company_mfo,
                "address" => $guarant->company_address,
                "director" => $guarant->company_director,
                "accountant" => $guarant->company_accountant
            ],
            //"empowermentid" => null
        ];

        $result = json_encode($result, JSON_UNESCAPED_UNICODE);

        Elog::save($result);

        return $result;

    }

    public static function getProductItems($productItems){

        Elog::save('getProductItems');
        $product_items = [];

        foreach($productItems as $productItem){

            $productItem = explode('|',$productItem);
            $ikpu = Ikpu::where(['id'=>$productItem[0]])->first();
            $unit = Unit::where(['id'=>$productItem[2]])->first();

            $product_items[] = [
                "ordno"=> 1,
                "name"=> $productItem[1],
                "catalogcode"=> $ikpu->code,
                "catalogname"=> $ikpu->title_ru,
                "measureid"=> $unit->code,
                "count"=> $productItem[3],
                "summa"=> $productItem[4]
            ];
        }
        //Elog::save($product_items);

        return $product_items;

    }

    /** информация от товарах */
    public static function getProductItemsInfo($products){

        $total = 0;
        $quantity = 0;
        foreach ($products as $product) {
            $productItem = explode('|', $product);
            $total += $productItem[4] ;
            $quantity+=$productItem[3];
        }

        return ['amount'=>$total,'quantity'=> $quantity];

    }

}
