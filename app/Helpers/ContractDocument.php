<?php

namespace App\Helpers;


use App\Models\Ikpu;
use App\Models\Nds;
use App\Models\Token;
use App\Models\Unit;
use App\Services\FacturaService;
use Illuminate\Support\Facades\Cache;

class ContractDocument
{

    public static function getTemplate($contract,$productItems, $company, $partner)
    {

        if(empty($company->factura_response)){


            if($token = Token::where(['service'=>'factura'])->first()){
                if(time()>$token->expire){
                    $result = FacturaService::getToken();
                }else {
                    $result = ['access_token' => $token->token, 'expires_in' => $token->expire];
                }
            }else {
                $result = FacturaService::getToken();
                if (!isset($result['access_token'])) {
                    return ['status' => false, 'error' => __('main.token_incorrect')];
                }
            }
            $companyInfo = FacturaService::getCompanyInfo($company->inn,$result['access_token']);
            Elog::save('factura companyInfo:');
            Elog::save($companyInfo);
            $data['factura_response'] = json_encode($companyInfo,JSON_UNESCAPED_UNICODE);
            $company->update(['factura_response'=>$data['factura_response']]);
        }
        $factura = json_decode($company->factura_response);
        $productItems = self::getProductItems($productItems);

        $result = [
            "didoxorderid" => null,
            "contractdoc" => [
                "contractname" => $contract->contract_name,
                "contractno" => $contract->contract_number,
                "contractdate" => $contract->contract_date,
                "contractexpiredate" => $contract->contract_expire,
                "contractplace" => $contract->contract_place
            ],
            "owner" => [
                "tin" => $company->inn,
                "name" => $company->name,
                "branchcode" => null,
                "branchname" => null,
                "fiztin" => $factura->DirectorPinfl,
                "fio" => $company->director,
                "address" => $company->address,
                "workphone" => null,
                "mobile" => null,
                "oked" => $company->oked,
                "account" => $company->bank_code,
                "bankid" => $company->mfo
            ],
            "clients" => [
                [
                    "branchcode" => null,
                    "branchname" => null,
                    "tin" => $partner['CompanyInn'],
                    //"name" => $partner['CompanyName'],
                    "fiztin" => $partner['DirectorPinfl'],
                    //"fio" => $partner['DirectorName'],
                    //"address" => $partner['CompanyAddress'],
                    "workphone" => null,
                    "mobile" => null,
                    "oked" => $partner['Oked'],
                    //"account" => $partner['Accounts'][0]['AccountCode'],
                    //"bankid" => $partner['Accounts'][0]['BankMfo']

                    "name" => $contract->partner_company_name,
                    "account" => $contract->partner_bank_code,
                    "bankid" => $contract->partner_mfo,
                    "address" => $contract->partner_address,
                    "fio" => $contract->partner_director

                ]
            ],
            "parts" => [
                [
                    "ordno" => 1,
                    "title"=>$contract->contract_name,
                    "body"=>$contract->contract_text
                ]
            ],
            "products" => $productItems,
            "hasvat" => true,
            "contractid"=>$contract->id,
            "sellertin"=>$company->inn,
            "buyertin"=>$partner['CompanyInn']
        ];

        $result = json_encode($result, JSON_UNESCAPED_UNICODE);

        Elog::save('contractDocument');
        Elog::save($result);

        return $result;

    }

    public static function getProductItems($productItems){

        Elog::save('getProductItems');

        $product_items = [];

        foreach($productItems as $productItem){

            $productItem = explode('|',$productItem);
            $ikpu = Ikpu::where(['id'=>$productItem[0]])->first();
            $unit = Unit::where(['code'=>$productItem[3]])->first();
            $nds = Nds::where(['id'=>$productItem[6]])->first();
            $ndsTitle = $nds->getTitle();
            $ndsValue = preg_replace('/[^.0-9]/','',$ndsTitle);

            $summa = $productItem[4] * $productItem[5];
            $ndsSumma = $summa/100*$ndsValue;
            $total = $summa + $ndsSumma;

            // ikpu|title|barcode|unit|quantity|amount|nds  |- warehouse| - origin
            $product_items[] = [
                "id" => null,
                "ordno" => 1,
                "name" => $productItem[1],
                "catalogcode" => $ikpu->code,
                "catalogname" => $ikpu->title_ru,
                "barcode" => $productItem[2],
                "measureid" => null, //$productItem[3],
                "packagecode" => $productItem[3],
                "packagename" => $unit->title_ru,
                "count" => $productItem[4],
                "summa" => $productItem[5],
                "deliverysum" => "0.00",
                "vatrate" => number_format($ndsValue,2,',',' '),
                "vatsum" => $total,
                "exciserate" => 0,
                "excisesum" => 0,
                "deliverysumwithvat" => "0.00",
                "withoutvat" => false,
            ];
        }

        return $product_items;

    }

    /** информация от товарах */
    public static function getProductItemsInfo($products){

        $total = 0;
        $quantity = 0;
        foreach ($products as $product) {
            $productItem = explode('|', $product);
            if(!Cache::has('nds_'.$productItem[6])){
                $nds = Nds::where(['id' => $productItem[6]])->first();
                $ndsTitle = $nds->getTitle();
                $ndsValue = preg_replace('/[^.0-9]/', '', $ndsTitle);
                Cache::put('nds_'.$productItem[6],$ndsValue,3600);
            }else{
                $ndsValue = Cache::get('nds_'.$productItem[6]);
            }
            $summa = $productItem[4] * $productItem[5];
            $ndsSumma = $summa / 100 * $ndsValue;
            $total += $summa + $ndsSumma;
            $quantity+=$productItem[6];
        }

        return ['amount'=>$total,'quantity'=> $quantity];

    }
}
