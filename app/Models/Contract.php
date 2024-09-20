<?php

namespace App\Models;

use App\Http\Interfaces\FilterInterface;
use App\Http\Traits\ByCompany;
use App\Http\Traits\Filters;
use App\Http\Traits\Locale;
use App\Http\Traits\MyCompany;
use App\Http\Traits\Order;
use App\Http\Traits\Status;
use App\Http\Traits\Statuses;
use App\Services\DidoxService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Contract extends Model implements FilterInterface
{
    use HasFactory, Locale, Order,Status,MyCompany,ByCompany,Statuses,Filters;

    protected $guarded = [];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }
    public function company(){
        return $this->hasOne(Company::class,'id','company_id');
    }
    public function contractIncoming(){
        return $this->hasOne(Contract::class,'didox_id','didox_id');
    }
    public function items(){
        return $this->hasMany(ContractItems::class,'contract_id','id')->with(['ikpu','package','nds']);
    }
    public function scopeOwner($query, $owner){
        return $query->where(['owner'=>$owner]);
    }
    public function chat(){
        return $this->hasOne(OpenaiChat::class,'document_id','id')->where(['doctype'=>'Contract']);
    }
    // план счетов
    public function plan(){
        return $this->hasOne(Plan::class,'id','plan_id');
    }

    public static function createDocumentFromDidox($document,&$company)
    {

        if(empty($document)) return ['status'=>false,'error'=>'Contract empty document'];

             $contract = Contract::create([
                'user_id'=>$company->user_id,
                'company_id'=>$company->id,
                'didox_id' => $document->doc_id,
                'contract_id' => $document->document_json->contractid,
                'owner' => $document->owner,
                'parties_owner' => $document->parties_owner,
                'contract_number' => $document->contract_number,
                'contract_date' => $document->contract_date,
                'contract_expire' => $document->document_json->contractdoc->contractexpiredate,
                'contract_place' => $document->document_json->contractdoc->contractplace,

                 // need delete >>>>
                 'contragent' => $document->document_json->sellertin,
                'contragent_company' => $document->document_json->clients[0]->name,
                'contragent_bank_code' => $document->document_json->clients[0]->account,
                 // <<<< need delete

                 // new fields
                 'company_inn' => $document->document_json->owner->tin,
                 'company_mfo' => $document->document_json->owner->bankid,
                 'company_address' => $document->document_json->owner->address,
                 'company_director' => $document->document_json->owner->fio,
                 'company_account' => $document->document_json->owner->account,
                 'company_name' => $document->document_json->owner->name,
                 //'company_accountant' => $document->document_json->owner->accountant,

                 'partner_inn' =>  $document->document_json->clients[0]->tin,
                 'partner_company_name' => $document->document_json->clients[0]->name,
                 'partner_bank_code' =>  $document->document_json->clients[0]->account,
                 'partner_mfo' =>  $document->document_json->clients[0]->bankid,
                 'partner_address' =>  $document->document_json->clients[0]->address,
                 'partner_director' =>  $document->document_json->clients[0]->fio,
                 // 'partner_accountant' =>  $document->document_json->clients[0]->accountant,

                'amount' => $document->total_sum,
                'status' => $document->status,
                'doc_status' => $document->doc_status,
                'contract_name' => $document->document_json->contractdoc->contractname,
                'contract_text' => $document->document_json->parts[0]->body,
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

                if (!$unit = Package::where(['code' => $item->packagecode])->first()) {
                    $unit = Package::create(['code' => $item->packagecode, 'title_ru' => $item->packagename, 'title_uz' => $item->packagename, 'title_en' => $item->packagename]);
                }

                ContractItems::create([
                    'title' => $item->catalogname,
                    'ikpu_id' => $ikpu->id,
                    'contract_id' => $contract->id,
                    'company_id' => $company->id,
                    'unit_id' => $unit->id,
                    'nds_id' => $nds->id,
                    'amount' => $item->summa,
                    'quantity' => $item->count,
                    'barcode' => $item->barcode,
                ]);
            }
        }
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
