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

class Act extends Model implements FilterInterface
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

    public function items(){
        return $this->hasMany(ActItems::class,'act_id','id')->with(['ikpu','package','nds']);
    }
    public function scopeOwner($query, $owner){
        return $query->where(['owner'=>$owner]);
    }

    public function chat(){
        return $this->hasOne(OpenaiChat::class,'document_id','id')->where(['doctype'=>'Act']);
    }

    public static function createDocumentFromDidox($document,&$company)
    {
        $path = 'queue_didox';
        Elog::save('Create Act',$path);
        Elog::save($document,$path);
        if(empty($document)) return ['status'=>false,'error'=>'Act empty document'];

            if(!empty($document->contract_number) &&  !$contract = Contract::where(['contract_number'=>$document->contract_number])->first()) {
                Elog::save('error contract not found '. $document->contract_number,$path);

                /** TODO get contract */

                return ['status'=>false,'error'=>'Contract not found ' . $document->contract_number];
            };

            $act = Act::create([
                'user_id'=>$company->user_id, //Auth::id(),
                'company_id'=>$company->id,
                'didox_id' => $document->doc_id,
                'factura_id' => $document->document_json->didoxfacturaid,
                'act_id' => $document->document_json->actid,
                'owner' => $document->owner,
                'parties_owner' => $document->parties_owner,
                'contract_id' => !empty($contract )?$contract->id : null,
                'number' => $document->document_json->actdoc->actno,
                'date' => $document->document_json->actdoc->actdate,
                'act_text' => $document->document_json->actdoc->acttext,
                'company_inn' => $document->document_json->buyertin,
                'company_name' => $document->document_json->buyername,
                'partner_inn' =>  $document->document_json->sellertin,
                'partner_company_name' => $document->document_json->sellername,
                'partner_phone' => $document->partnerPhone,
                'total_sum'=>  $document->total_sum,
                'total_delivery_sum'=>  $document->total_delivery_sum,
                'total_vat_sum'=> $document->total_vat_sum,
                'total_delivery_sum_with_vat'=> $document->total_delivery_sum_with_vat,
                'has_vat'=> $document->has_vat,
                'status' => $document->status,
                'doc_status' => $document->doc_status,
                'response' => json_encode($document,JSON_UNESCAPED_UNICODE)
            ]);
            if(!empty($document->products )) {

                    foreach ($document->products as $item) {
                        if (!$nds = Nds::where(['slug' => $item->vatrate])->first()) {
                            $nds = Nds::create(['slug' => $item->vatrate, 'title_ru' => $item->vatrate . '%', 'title_uz' => $item->vatrate . '%', 'title_en' => $item->vatrate . '%', 'status' => 1]);
                        }
                        if (!$ikpu = Ikpu::where(['code' => $item->catalogcode])->first()) {
                            $ikpu = Ikpu::create(['code' => $item->catalogcode, 'title_ru' => $item->catalogname, 'title_uz' => $item->catalogname, 'title_en' => $item->catalogname, 'status' => 1]);
                        }
                        /*if(!$unit = Unit::where(['code'=>$item->packagename])->first()){
                            $unit = Unit::create(['slug'=>Str::slug($item->packagename),'title_ru'=>$item->packagename,'title_uz'=>$item->packagename,'title_en'=>$item->packagename,'status'=>1]);
                        }*/
                        if (!$unit = Package::where(['code' => $item->packagecode])->first()) {
                            $unit = Package::create(['code' => $item->packagecode, 'title_ru' => $item->packagename, 'title_uz' => $item->packagename, 'title_en' => $item->packagename]);
                        }
                        try {
                        ActItems::create([
                            'title' => $item->catalogname,
                            'ikpu_id' => $ikpu->id,
                            'act_id' => $act->id,
                            'company_id' => $company->id,
                            'unit_id' => $unit->id,
                            'nds_id' => $nds->id,
                            //'product_origin'=> $item->origin,
                            'amount' => $item->summa,
                            'quantity' => $item->count,
                            //'barcode'=> $item->barcode,
                            'vatrate' => $item->vatrate,
                            'vatsum' => $item->vatsum,
                            'withoutvat' => $item->withoutvat ? 1 : 0,
                            'totalsum' => $item->totalsum,
                            'totalsumwithoutvat' => $item->totalsumwithoutvat,
                        ]);

                        }catch (\Exception $e) {
                            Elog::save('ERROR ACT items ' . $act->id, $path);
                            Elog::save($e, $path);
                        }
                    }

            }else{
                Elog::save('ACT products not set '. $act->id ,$path);
            }
           Elog::save('ACT save ok '. $act->id . ' - ' . $document->doc_id,$path);

            return ['status'=>true];

    }

    public function getFieldInn()
    {
        return 'partner_inn';
    }

    public function getFieldContragentCompanyName()
    {
        return 'partner_company_name';
    }



}
