<?php

namespace App\Helpers;

use App\Models\Contract;
use App\Models\Ikpu;
use App\Models\Nds;
use App\Models\Unit;
use Illuminate\Support\Facades\Cache;

class WayBillDocument{

    public static function getTemplate($wayBill,$items,$company,$partner = null){

        $product_groups = self::getProductGroups($wayBill->cargos);

        $contract = Contract::where(['id'=>$wayBill->contract_id])->first();

        $result = [
            "DeliveryType"=> $wayBill->delivery_type, // Тип перевозки
            "WaybillDoc"=> [ // Информация по ТТН
                "WaybillNo"=> $wayBill->number, // Номер ТТН
                "WaybillDate"=> $wayBill->date // Дата ТТН
            ],
            "ContractDoc"=> [ // Информация по договору
                "ContractNo"=> $contract->contract_number, // Номер договора
                "ContractDate"=> $contract->contract_date // Дата договора
            ],
            "HasCommittent"=> $wayBill->has_committent>0?true:false, // Есть комитент true/false
            "Consignor"=> [ // Информация по грузоотправителю
                "TinOrPinfl"=> $wayBill->consignor_inn, // ИНН/ПИНФЛ грузоотправителя
                "Name"=> $wayBill->consignor_name, // Название грузоотправителя
                "BranchCode"=> "" // Код филиала грузоотправителя
            ],
            "Consignee"=> [ // Информация по грузополучателю
                "TinOrPinfl"=> $wayBill->consignee_inn, // ИНН/ПИНФЛ грузополучателю
                "Name"=> $wayBill->consignee_name, // Название грузополучателю
                "BranchCode"=> "", // Код филиала грузополучателю
                "BranchName"=> "" // Название филиала грузополучателю
            ],
            "FreightForwarder"=> [ // Информация по экспедитору
                "TinOrPinfl"=> $wayBill->freight_forwarder_inn, // ИНН/ПИНФЛ экспедитора
                "Name"=> $wayBill->freight_forwarder_name, // Название экспедитора
                "BranchCode"=> "", // Код филиала экспедитора
                "BranchName"=> "" // Название филиала экспедитора
            ],
            "Carrier"=> [ // Информация по грузоперевозчику
                "TinOrPinfl"=> $wayBill->carrier_inn, // ИНН/ПИНФЛ грузоперевозчика
                "Name"=> $wayBill->carrier_name, // Название грузоперевозчика
                "BranchCode"=> "", // Код филиала грузоперевозчика
                "BranchName"=> "" // Название филиала грузоперевозчика
            ],
            "Client"=> [ // Информация по клиенту
                "TinOrPinfl"=> $wayBill->client_inn, // ИНН/ПИНФЛ клиента
                "Name"=>  $wayBill->client_name, // Название клиента
                "ContractNo"=>  $wayBill->client_contract ?? $wayBill->client_contract->conract_number, // Номер договора
                "ContractDate"=> $wayBill->client_contract ?? $wayBill->client_contract->conract_date, // Дата договора
                "BranchCode"=> "", // Код филиала клиента
                "BranchName"=> "" // Название филиала клиента
            ],
            "Payer"=> [ // Информация по заказчику
                "TinOrPinfl"=> $wayBill->payer_inn, // ИНН/ПИНФЛ заказчика
                "Name"=> $wayBill->payer_name, // Название заказчика
                "ContractNo"=> $wayBill->payer_contract ?? $wayBill->payer_contract->contract_number, // Номер договора
                "ContractDate"=> $wayBill->payer_contract ?? $wayBill->payer_contract->contract_date, // Дата договора
                "BranchCode"=> "", // Код филиала заказчика
                "BranchName"=> "" // Название филиала заказчика
            ],
            "TransportType"=> $wayBill->transport_type, // Тип транспорта
            "Roadway"=> [ // Информация по грузуперевозке
                "OtherCarOwners"=> [// Владелец транспорта
                    [
                         "TinOrPinfl"=> $wayBill->owner_inn,  // ИНН/ПИНФЛ владельца транспорта
                         "Name"=> $wayBill->owner_name // Название владельца транспорта
                    ]
                ],
                "Truck"=> [
                    "RegNo"=> $wayBill->truck_regno, // Номер автомобиля
                    "Model"=> $wayBill->truck_model // Модель автомобиля
                ],
                "Trailer"=> null, //$wayBill->trailer, // Информация по полуприцепу
                "Carriages"=> [], // Информация по прицепу
                "Driver"=> [ // Информация по водителю
                    "pinfl"=> $wayBill->driver_pinfl, // ПИНФЛ водителя
                    "FullName"=> $wayBill->driver_name // ФИО водителя
                ],
                "ProductGroups"=> $product_groups
            ],
            "ResponsiblePerson"=> [ // Отвественное лицо
                "Pinfl"=> $wayBill->person_pinfl, // ПИНФЛ
                "FullName"=> $wayBill->person_name // ФИО
            ],
            "TotalDistance"=> $wayBill->total_distance, // Общее расстояние (км)
            "DeliveryCost"=> $wayBill->delivery_cost, // Цена доставки за 1 км
            "TotalDeliveryCost"=> $wayBill->total_delivery_cost, // Общая стоимость доставки
            "isValid"=> true
        ];

        return json_encode($result,JSON_UNESCAPED_UNICODE);

    }

    public static function getProductGroups($cargos){

        if(empty($cargos)) return null;
        $result = [];
        foreach ($cargos as $cargo) {

            $products = self::getProducts($cargo->items);

            $result[] = [ // Информация по грузу
                [
                    "LoadingPoint" => [ // Адрес погрузки
                        "regionId" => $cargo->loading_region_id, // ID региона
                        "regionName" => $cargo->loading_region_name, // Название региона
                        "districtCode" => $cargo->loading_district_code, // Код района
                        "districtName" => $cargo->loading_district_name, // Название района
                        "mahallaId" => $cargo->loading_mahalla_id, // ID махалли
                        "mahallaName" => $cargo->loading_mahalla_name, // Название махалли
                        "address" => $cargo->loading_address, // Адрес
                        "longitude" => $cargo->loading_longtitude, // Широта
                        "latitude" => $cargo->loading_latitude // Долгота
                    ],
                    "LoadingTrustee" => [ // Отв. лицо грузоотправителя
                        "pinfl" => $cargo->loading_trustee_pinfl, // ПИНФЛ
                        "fullName" => $cargo->loading_trustee_name // ФИО
                    ],
                    "UnloadingPoint" => [ // Адрес доставки
                        "regionId" => $cargo->unloading_region_id, // ID региона
                        "regionName" => $cargo->unloading_region_name, // Название региона
                        "districtCode" => $cargo->unloading_district_code, // Код района
                        "districtName" => $cargo->unloading_district_name, // Название района
                        "mahallaId" => $cargo->unloading_mahalla_id, // ID махалли
                        "mahallaName" => $cargo->unloading_mahalla_name, // Название махалли
                        "address" => $cargo->unloading_address, // Адрес
                        "longitude" => $cargo->unloading_longtitude, // Широта
                        "latitude" => $cargo->unloading_latitude  // Долгота
                    ],
                    "UnloadingTrustee" => [ // Отв. лицо грузополучателя
                        "Pinfl" => $cargo->unloading_trustee_pinfl, // ПИНФЛ
                        "FullName" => $cargo->unloading_trustee_name // ФИО
                    ],
                    "UnloadingEmpowerment" => [ // Доверенность
                        "EmpowermentId" => $cargo->unloading_guarant_id, // ID  доверенности
                        "EmpowermentNo" => $cargo->unloading_guarant_number, // Номер доверенности
                        "EmpowermentDateOfIssue" => $cargo->unloading_guarant_date_issue, // Дата доверенности (от)
                        "EmpowermentDateOfExpire" => $cargo->unloading_guarant_date_expire // Дата доверенности (до)
                    ],
                    "ProductInfo" => [
                        "TotalDeliverySum" => $cargo->total_delivery_sum, // Сумма доставки
                        "TotalWeightBrutto" => $cargo->total_delivery_brutto, // Сумма брутто
                        "products" => $products
                    ]
                ]
            ];
        }

        return $result;

    }


    public static function getProducts($items){

        $_items = [];

        foreach($items as $item){

            $item = explode('|',$item);
            $ikpu = Ikpu::where(['id'=>$item[0]])->first();
            $unit = Unit::where(['code'=>$item[3]])->first();

            $_items[] = [
                "CommittentTinOrPinfl" => "", // ИНН/ПИНФЛ комитента
                "CommittentName" => "", // Название комитента
                "CatalogCode" =>  $ikpu->code, // ИКПУ
                "CatalogName" => $ikpu->title_ru, // Название ИКПУ
                "ProductName" => $item[1], // Название товара
                "PackageCode" => $item[3], // Код упаковки
                "PackageName" =>  $unit->title_ru, // Название упаковки
                "Amount" => $item[4], // Количество
                "Price" => $item[5], // Цена за ед.
                "DeliverySum" => "20.00", // Сумма доставки
                "WeightBrutto" => "500", // Вес брутто
                "WeightNetto" => "499" // Вес нетто
            ];
        }

        return $_items;

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
