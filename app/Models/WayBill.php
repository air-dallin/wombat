<?php

namespace App\Models;

use App\Helpers\Elog;
use App\Http\Interfaces\FilterInterface;
use App\Http\Traits\Filters;
use App\Http\Traits\Locale;
use App\Http\Traits\MyCompany;
use App\Http\Traits\Order;
use App\Http\Traits\Status;
use App\Http\Traits\Statuses;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class WayBill extends Model
{
    use HasFactory, Locale, Order,Status,MyCompany,Statuses,Filters;

    protected $guarded = [];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }
    public function company(){
        return $this->hasOne(Company::class,'id','company_id');
    }
    public function contract(){
        return $this->hasOne(Contract::class,'id','contract_id');
    }

    public function cargos(){
        return $this->hasMany(WayBillCargo::class,'way_bill_id','id');
    }

    public function scopeOwner($query, $owner){
        return $query->where(['owner'=>$owner]);
    }

    public function chat(){
        return $this->hasOne(OpenaiChat::class,'document_id','id')->where(['doctype'=>'WayBill']);
    }


    /** получение документа от сервиса didox */
    public static function createDocumentFromDidox($document,&$company)
    {

        Elog::save('Create wayBill');
        Elog::save($document);
        if(empty($document)) return ['status'=>false,'error'=>'wayBill empty document'];

            if(!$contract = Contract::where(['contract_number'=>$document->contract_number])->first()) {
                Elog::save('error contract not found '. $document->contract_number);
                return ['status'=>false,'error'=>'Contract not found'];
            };

            $wayBill = WayBill::create([
                'user_id'=>$company->user_id, //Auth::id(),
                'company_id'=>$company->id,
                'didox_id' => $document->doc_id,
                'owner' => $document->owner,
                'parties_owner' => $document->parties_owner,
                'contract_id' => $contract->id,
                'number' => $document->document_json->facturadoc->facturano,
                'date' => $document->doc_date,

                // need delete >>>>
                'contragent' => $document->owner_tin,
                'contragent_company' => $document->partnerCompany,
                'contragent_bank_code' => $document->document_json->seller->account,
                // 'total' => $document->total_sum,
                // <<<< need delete

                'company_inn' => $document->document_json->sellertin,
                'company_mfo' => $document->document_json->seller->bankid,
                'company_address' => $document->document_json->seller->address,
                'company_director' => $document->document_json->seller->director,
                'company_account' => $document->document_json->seller->account,
                'company_name' => $document->document_json->seller->name,
                'company_accountant' => $document->document_json->seller->accountant,

                'partner_inn' =>  $document->document_json->buyertin,
                'partner_company_name' => $document->document_json->buyer->name,
                'partner_bank_code' =>  $document->document_json->buyer->account,
                'partner_mfo' =>  $document->document_json->buyer->bankid,
                'partner_address' =>  $document->document_json->buyer->address,
                'partner_director' =>  $document->document_json->buyer->director,
                'partner_accountant' =>  $document->document_json->buyer->accountant,

                'status' => $document->status,
                'doc_status' => $document->doc_status,
                'response' => json_encode($document,JSON_UNESCAPED_UNICODE)
            ]);

            if(!empty($document->productGroups)) {
                foreach ($document->productGroups as $item) {

                    $wayBillCargo = WayBillCargo::create([
                        'way_bill_id'=>$wayBill->id,

                         // Адрес погрузки
                        "loading_region_id" => $document->Roadway->ProductGroups->LoadingPoint->regionId, // ID региона
                        "loading_region_name" => $document->Roadway->ProductGroups->LoadingPoint->regionName, // Название региона
                        "loading_district_code" => $document->Roadway->ProductGroups->LoadingPoint->districtCode, // Код района
                        "loading_district_name" => $document->Roadway->ProductGroups->LoadingPoint->districtName, // Название района
                        "loading_mahalla_id" => $document->Roadway->ProductGroups->LoadingPoint->mahallaId, // ID махалли
                        "loading_mahalla_name" => $document->Roadway->ProductGroups->LoadingPoint->mahallaName, // Название махалли
                        "loading_address" => $document->Roadway->ProductGroups->LoadingPoint->address, // Адрес
                        "loading_longtitude" => $document->Roadway->ProductGroups->LoadingPoint->longitude, // Широта
                        "loading_latitude" => $document->Roadway->ProductGroups->LoadingPoint->latitude ,// Долгота
                        // Отв. лицо грузоотправителя
                        "loading_trustee_pinfl" => $document->Roadway->ProductGroups->LoadingTrustee->pinfl, // ПИНФЛ
                        "loading_trustee_name" => $document->Roadway->ProductGroups->LoadingTrustee->FullName, // ФИО
                         // Адрес доставки
                        "unloading_region_id" => $document->Roadway->ProductGroups->UnloadingPoint->regionId, // ID региона
                        "unloading_region_name" => $document->Roadway->ProductGroups->UnloadingPoint->regionName, // Название региона
                        "unloading_district_code" => $document->Roadway->ProductGroups->UnloadingPoint->districtCode, // Код района
                        "unloading_district_name" => $document->Roadway->ProductGroups->UnloadingPoint->districtName, // Название района
                        "unloading_mahalla_id" => $document->Roadway->ProductGroups->UnloadingPoint->mahallaId, // ID махалли
                        "unloading_mahalla_name" => $document->Roadway->ProductGroups->UnloadingPoint->mahallaName, // Название махалли
                        "unloading_address" => $document->Roadway->ProductGroups->UnloadingPoint->address, // Адрес
                        "unloading_longtitude" => $document->Roadway->ProductGroups->UnloadingPoint->longitude, // Широта
                        "unloading_latitude" => $document->Roadway->ProductGroups->UnloadingPoint->latitude, // Долгота
                         // Отв. лицо грузополучателя
                        "unloading_trustee_pinfl" => $document->Roadway->ProductGroups->UnloadingTrustee->Pinfl, // ПИНФЛ
                        "unloading_trustee_name" => $document->Roadway->ProductGroups->UnloadingTrustee->FullName ,// ФИО
                        // Доверенность
                        "unloading_guarant_id" => $document->Roadway->ProductGroups->UnloadingEmpowerment->EmpowermentId, // ID  доверенности
                        "unloading_guarant_number" => $document->Roadway->ProductGroups->UnloadingEmpowerment->EmpowermentNo, // Номер доверенности
                        "unloading_guarant_date_issue" => $document->Roadway->ProductGroups->UnloadingEmpowerment->EmpowermentDateOfIssue, // Дата доверенности (от)
                        "unloading_guarant_date_expire" => $document->Roadway->ProductGroups->UnloadingEmpowerment->EmpowermentDateOfExpire, // Дата доверенности (до)

                        "total_delivery_sum" => $document->Roadway->ProductGroups->ProductInfo->TotalDeliverySum, // Сумма доставки
                        "total_delivery_brutto" => $document->Roadway->ProductGroups->ProductInfo->TotalWeightBrutto // Сумма брутто
                    ]);

                    if(!empty($item->products)) {

                        foreach ($item->products as $product) {
                            if (!$ikpu = Ikpu::where(['code' => $item->catalogcode])->first()) {
                                $ikpu = Ikpu::create(['code' => $item->catalogcode, 'title_ru' => $item->catalogname, 'title_uz' => $item->catalogname, 'title_en' => $item->catalogname, 'status' => 1]);
                            }
                            if (!$unit = Package::where(['code' => $item->packagecode])->first()) {
                                $unit = Package::create(['code' => $item->packagecode, 'title_ru' => $item->packagename, 'title_uz' => $item->packagename, 'title_en' => $item->packagename]);
                            }

                            WayBillItem::create([
                                'title' => $product->catalogname,
                                'ikpu_id' => $ikpu->id,
                                'unit_id' => $unit->id,
                                'way_bill_cargo_id' => $wayBillCargo->id,
                                'company_id' => $company->id,
                                'amount' => $product->Amount,
                                'price' => $product->Price,
                                'committent_inn' => $product->CommittentTinOrPinfl,
                                'committent_name' => $product->CommittentName,
                                'delivery_sum' => $product->DeliverySum,
                                'weight_brutto' => $product->WeightBrutto,
                                'weight_netto' => $product->WeightNetto
                            ]);
                        }
                    }

                }
            }
            Elog::save('save ok '.$wayBill->id . ' - ' . $document->doc_id);

            return ['status'=>true];

    }


}
