<?php

namespace App\Helpers;

use App\Models\Contract;

class DocDocument{

    public static function getTemplate($doc,$docItems=null,$company,$partner=null){

        $contract = Contract::where(['id'=>$doc->contract_id])->first();

        $result = [
            "isValid" => true,
            "didoxorderid" => "",
            "Document" => [
                "DocumentNo" => $doc->number,
                "DocumentDate" => $doc->date,
                "DocumentName" => $doc->name,
            ],
            "ContractDoc" => [
                "ContractNo" => $contract->contract_number,
                "ContractDate" => $contract->contract_date
            ],
            "SellerTin" => $company->inn,
            "Seller" => [
               "Name" => $company->name,
               "Address" => $company->address,
               "BranchCode" => "",
               "BranchName" => ""
            ],
            "BuyerTin" => $doc->partner_inn,
            "Buyer" => [
               "Name" => $doc->partner_company,
               "Address" => $doc->partner_address,
               "BranchCode" => "",
               "BranchName" => ""
            ],
           "document" => $doc->file
        ];

        return json_encode($result,JSON_UNESCAPED_UNICODE);

    }


}
