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

class Product extends Model implements FilterInterface
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
        return $this->hasMany(ProductItems::class,'product_id','id')->with(['ikpu','package','nds','warehouse']);
    }
    public function scopeOwner($query, $owner){
        return $query->where(['owner'=>$owner]);
    }

    public function chat(){
        return $this->hasOne(OpenaiChat::class,'document_id','id')->where(['doctype'=>'Factura']);
    }

    public static function getOriginLabel($origin){

        $product_origin = [
            1=>['title'=>__('main.own_production')],
            2=>['title'=>__('main.purchase_sale')],
            3=>['title'=>__('main.provision_services')],
            4=>['title'=>__('main.not_use')]
        ];

        return $product_origin[$origin]['title'];

    }

    public static function getOriginList(){

        return [
            ['id'=>1,'title'=>__('main.own_production')],
            ['id'=>2, 'title'=>__('main.purchase_sale')],
            ['id'=>3, 'title'=>__('main.provision_services')],
            ['id'=>4, 'title'=>__('main.not_use')]
        ];
    }

    public static function createDocumentFromDidox($document,&$company)
    {

        Elog::save('Create factura-product');
        Elog::save($document);
        if(empty($document)) return ['status'=>false,'error'=>'Factura empty document'];

            if(!$contract = Contract::where(['contract_number'=>$document->contract_number])->first()) {
                Elog::save('error contract not found '. $document->contract_number);
                return ['status'=>false,'error'=>'Contract not found'];
            };

            $product = Product::create([
                'user_id'=>$company->user_id, //Auth::id(),
                'company_id'=>$company->id,
                'didox_id' => $document->doc_id,
                'factura_id' => $document->document_json->facturaid,
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
            foreach($document->products as $item) {
                if(!$nds = Nds::where(['slug'=>$item->vatrate])->first()){
                    $nds = Nds::create(['slug'=>$item->vatrate,'title_ru'=>$item->vatrate .'%','title_uz'=>$item->vatrate .'%','title_en'=>$item->vatrate .'%','status'=>1]);
                }
                if(!$ikpu = Ikpu::where(['code'=>$item->catalogcode])->first()){
                    $ikpu = Ikpu::create(['code'=>$item->catalogcode,'title_ru'=>$item->catalogname,'title_uz'=>$item->catalogname,'title_en'=>$item->catalogname,'status'=>1]);
                }
                /*if(!$unit = Unit::where(['code'=>$item->packagename])->first()){
                    $unit = Unit::create(['slug'=>Str::slug($item->packagename),'title_ru'=>$item->packagename,'title_uz'=>$item->packagename,'title_en'=>$item->packagename,'status'=>1]);
                }*/
                if(!$unit = Package::where(['code'=>$item->packagecode])->first()){
                    $unit = Package::create(['code'=>$item->packagecode,'title_ru'=>$item->packagename,'title_uz'=>$item->packagename,'title_en'=>$item->packagename]);
                }

                $productItem = ProductItems::create([
                    'title'=> $item->catalogname,
                    'ikpu_id' => $ikpu->id,
                    'product_id'=> $product->id,
                    'company_id'=> $company->id,
                    'unit_id'=> $unit->id,
                    'nds_id'=> $nds->id,
                    'company_warehouse_id'=> $item->warehouseid,
                    'product_origin'=> $item->origin,
                    'amount'=> $item->summa,
                    'quantity'=> $item->count,
                    'barcode'=> $item->barcode,
                ]);
            }
           Elog::save('save ok '.$product->id . ' - ' . $document->doc_id);

            return ['status'=>true];

    }



    /*public static function createFromDidoxDocument($documents)
    {

        if(empty($documents)) return false;

        $company_id = Company::getCurrentCompanyId();
        foreach($documents as $document){

            if(!$contract = Contract::where(['contract_number'=>$document->contract_number])->first()) continue;

            $product = Product::create([
                'user_id'=>Auth::id(),
                'company_id'=>$company_id,
                'didox_id' => $document->doc_id,
                'factura_id' => $document->document_json->facturaid,
                'owner' => $document->owner,
                'contract_id' => $contract->id,
                'date' => $document->doc_date,
                'contragent' => $document->owner_tin,
                'contragent_company' => $document->partnerCompany,
                'contragent_bank_code' => $document->document_json->seller->account,
                // 'total' => $document->total_sum,
                'status' => $document->doc_status,
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
                    if (!$unit = Unit::where(['title_ru' => $item->packagename])->first()) {
                        $unit = Unit::create(['slug' => Str::slug($item->packagename), 'title_ru' => $item->packagename, 'title_uz' => $item->packagename, 'title_en' => $item->packagename, 'status' => 1]);
                    }

                    $productItem = ProductItems::create([
                        'title' => $item->catalogname,
                        'ikpu_id' => $ikpu->id,
                        'product_id' => $product->id,
                        'company_id' => $company_id,
                        'unit_id' => $unit->id,
                        'nds_id' => $nds->id,
                        'company_warehouse_id' => $item->warehouseid,
                        'product_origin' => $item->origin,
                        'amount' => $item->summa,
                        'quantity' => $item->count,
                        'barcode' => $item->barcode,

                    ]);
                }
            }

        }

    }*/

    // список типов
    public static function getTypes(){

        if(!Cache::has('factura_types')) {
            $types = [
                __('main.factura_type_standart'),
                __('main.factura_type_optional'),
                __('main.factura_type_fixed'),
                __('main.factura_type_recovery'),
                __('main.factura_type_additional'),
                __('main.factura_type_corrected'),
                __('main.factura_type_no_payment'),
                __('main.factura_type_fixed_no_payment'),
                __('main.factura_type_additional_no_payment')
            ];

            Cache::put($types,86400);
            return $types;
        }

        return Cache::get('factura_types');

    }
    // текущий тип
    public static function getCurrentType(){
        $types = self::getTypes();
        if(request()->has('type')){
            return $types[request()->get('type')];
        }
        return $types[0];

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
