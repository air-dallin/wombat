<?php

namespace App\Helpers;

use App\Models\Contract;
use App\Models\Ikpu;
use App\Models\Nds;
use App\Models\Unit;
use App\Services\FacturaService;
use Illuminate\Support\Facades\Cache;

class ProductDocument{

    public static function getTemplate($product,$productItems,$company,$partner){

        $productItems = self::getProductItems($productItems);

        $contract = Contract::where(['id'=>$product->contract_id])->first();

        $result = [
            "didoxcontractid" => null,
            "version" => 1,
            "waybillids" => [
            ],
            "hasmarking" => false,
            "facturatype" => $product->factura_type,
            "productlist" => [
                "hascommittent" => false,
                "haslgota" => false,
                "tin" => $company->inn,
                "hasexcise" => false,
                "hasvat" => true,
                "products" => $productItems,
                "facturaproductid" => null
            ],
            "facturadoc" => [
                "facturano" => $product->number,
                "facturadate" => $product->date
            ],
            "contractdoc" => [
                "contractno" => $contract->contract_number,
                "contractdate" => $contract->contract_date
            ],
            "contractid" => null,
            "lotid" => null,
            "oldfacturadoc" => [
                "oldfacturadate" => $product->old_factura_date,
                "oldfacturano" => $product->old_factura_number,
                "oldfacturaid" => $product->old_factura_id
            ],
            "sellertin" => $company->inn,
            "seller" => [
                "name" => $company->name,
                "branchcode" => null,
                "branchname" => null,
                "vatregcode" => null,
                "account" => $company->bank_code,
                "bankid" => $company->mfo,
                "address" => $company->address,
                "director" => $company->director,
                "accountant" => $company->accountant,
                "vatregstatus" => null,
                "taxgap" => null
            ],
            "itemreleaseddoc" => [
                "itemreleasedfio" => null
            ],
            "buyertin" => $product->partner_inn,
            "buyer" => [
                "name" => $product->partner_company_name,
                "branchcode" => null,
                "branchname" => null,
                "vatregcode" => null,
                "account" => $product->partner_bank_code,
                "bankid" => FacturaService::getPrimaryAccountBankMfo($partner,$product->partner_bank_code),
                "address" => $partner['CompanyAddress'],
                "director" => $partner['DirectorName'],
                "accountant" => $partner['Accountant'],
                "vatregstatus" => null,
                "taxgap" => null
           ],
           "facturainvestmentobjectdoc" => [
                    "objectid" => null,
                    "objectname" => null
                ],
           "facturaempowermentdoc" => [
                    "empowermentno" => null,
                    "empowermentdateofissue" => null,
                    "agentfio" => null,
                    "agentpinfl" => null,
                    "agentfacturaid" => null
                ],
           "expansion" => [
                    "ordernumber" => null
                ],
           "foreigncompany" => [
                    "countryid" => null,
                    "name" => null,
                    "address" => null,
                    "bank" => null,
                    "account" => null
                ],
           "facturaid" => null
        ];

        return json_encode($result,JSON_UNESCAPED_UNICODE);

    }

    public static function getProductItems($productItems){

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

            $product_items[] = [
                "id" => null,
                "ordno" => 1,
                "lgotaid" => null,
                "committentname" => null,
                "committenttin" => null,
                "committentvatregcode" => null,
                "committentvatregstatus" => null,
                "name" => $productItem[1],
                "catalogcode" => $ikpu->code,
                "catalogname" => $ikpu->title_ru,
                "marks" => null,
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
                "withoutexcise" => true,
                "warehouseid" => $productItem[7],
                "origin" => $productItem[8],
                "lgotaname" => null,
                "lgotavatsum" => 0,
                "lgotatype" => null
            ];
        }

        return $product_items;

    }

    /** информация о товарах */
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
