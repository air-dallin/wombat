<?php

namespace App\Helpers;

use App\Models\Contract;
use App\Models\Ikpu;
use App\Models\Nds;
use App\Models\Unit;
use Illuminate\Support\Facades\Cache;

class ActDocument{

    public static function getTemplate($act,$actItems,$company,$partner = null){

        $actItems = self::getProductItems($actItems);

        $contract = Contract::where(['id'=>$act->contract_id])->first();

        $result = [
           "actdoc" => [
                "actno" => $act->number,
                "actdate" =>$act->date,
                "acttext" => $act->act_text
           ],
           "contractdoc" => [
                "contractno" => $contract->contract_number,
                "contractdate" => $contract->contract_date
           ],
           "productlist" => [
                "tin" => $company->inn,
                "hasexcise" => false,
                "hasvat" => $act->has_vat ? true :false,
                "products" => $actItems,
                "actproductid" => null
            ],
            "sellertin" => $company->inn,
            "sellername" => $company->name,
            "sellerbranchcode" => "",
            "sellerbranchname" => "",
            "buyertin" => $act->partner_inn,
            "buyername" =>  $act->partner_company,
            "buyerbranchcode" => "",
            "buyerbranchname" => "",
            "expansion" => [
                "ordernumber" => ""
            ],
            "actid" => $act->act_id
        ];


        return json_encode($result,JSON_UNESCAPED_UNICODE);

    }

    public static function getProductItems($actItems){

        $act_items = [];

        foreach($actItems as $actItem){

            $actItem = explode('|',$actItem);
            $ikpu = Ikpu::where(['id'=>$actItem[0]])->first();
            $unit = Unit::where(['code'=>$actItem[3]])->first();
            $nds = Nds::where(['id'=>$actItem[6]])->first();
            $ndsTitle = $nds->getTitle();
            $ndsValue = preg_replace('/[^.0-9]/','',$ndsTitle);

            $summa = $actItem[4] * $actItem[5];
            $ndsSumma = $summa/100*$ndsValue;
            $total = $summa + $ndsSumma;

            $act_items[] = [
                "ordno" => 1,
                "catalogcode" => $ikpu->code,
                "catalogname" => $ikpu->title_ru,
                "name" => $actItem[1],
                "measureid" => null,
                "packagecode" => $actItem[3],
                "packagename" => $unit->title_ru,
                "count" => $actItem[4],
                "summa" => $actItem[5],
                "totalsumwithoutvat" => "",
                "vatrate" => number_format($ndsValue,2,',',' '),
                "vatsum" => $ndsSumma,
                "totalsum" => $total,
                "withoutvat" => true
            ];
        }

        return $act_items;

    }

    /** информация от товарах */
    public static function getProductItemsInfo($acts){

        $total = 0;
        $quantity = 0;
        foreach ($acts as $act) {
            $actItem = explode('|', $act);
            if(!Cache::has('nds_'.$actItem[6])){
                $nds = Nds::where(['id' => $actItem[6]])->first();
                $ndsTitle = $nds->getTitle();
                $ndsValue = preg_replace('/[^.0-9]/', '', $ndsTitle);
                Cache::put('nds_'.$actItem[6],$ndsValue,3600);
            }else{
                $ndsValue = Cache::get('nds_'.$actItem[6]);
            }
            $summa = $actItem[4] * $actItem[5];
            $ndsSumma = $summa / 100 * $ndsValue;
            $total += $summa + $ndsSumma;
            $quantity+=$actItem[6];
        }

        return ['amount'=>$total,'quantity'=> $quantity];

    }

}
