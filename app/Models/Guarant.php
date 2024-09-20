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
use App\Services\DidoxService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Guarant extends Model implements FilterInterface
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
    public function contractIncoming(){
        return $this->hasOne(Contract::class,'id','contract_id')->where(['owner'=>DidoxService::OWNER_TYPE_INCOMING]);
    }

    public function items(){
        return $this->hasMany(GuarantItems::class,'guarant_id','id')->with(['ikpu','unit']);
    }
    public function chat(){
        return $this->hasOne(OpenaiChat::class,'document_id','id')->where(['doctype'=>'Guarant']);
    }
    public static function createDocumentFromDidox($document,&$company)
    {
        Elog::save('create from didox');
        if(empty($document)) return ['status'=>false,'error'=>'Guarant empty document'];

        if(!$contract = Contract::where(['contract_number'=>$document->contract_number])->first()) {
            Elog::save('contract not found num: ' . $document->contract_number);
            return ['status'=>false,'error'=>'Contract '.$document->contract_number . ' - '.$document->contract_date .' not found for guarant: ' . $document->document_json->empowermentdoc->empowermentno];
        }
        if(!$companyInvoice = CompanyInvoice::where(['company_id'=>$company->id,'bank_invoice'=>$document->document_json->seller->account])->first()) {
            $companyInvoice = CompanyInvoice::create(['company_id'=>$company->id,'bank_invoice'=>$document->document_json->seller->account,'status'=>\App\Models\Status::STATUS_ACTIVE]);
        }

        $guarant = Guarant::create([
            'user_id'=>$company->user_id, //Auth::id(),
            'company_id'=>$company->id,
            'didox_id' => $document->doc_id,
            'guarant_id' => $document->document_json->empowermentid,
            'owner' => $document->owner,
            'parties_owner' => $document->parties_owner,
            'company_name' => $document->document_json->buyer->name,
            'company_inn' => $document->document_json->buyertin,
            'company_mfo' => $document->document_json->buyer->bankid,
            'company_account' => $document->document_json->buyer->account,
            'company_address' => $document->document_json->buyer->address,
            'company_director' => $document->document_json->buyer->director,
            'company_accountant' => $document->document_json->buyer->accountant,

            'guarant_number'=>$document->document_json->empowermentdoc->empowermentno,
            'guarant_date'=>$document->document_json->empowermentdoc->empowermentdateofissue,
            'guarant_date_expire'=>$document->document_json->empowermentdoc->empowermentdateofexpire,

            'contract_id' => $contract->id,
            'company_invoice_id' => $companyInvoice->id,
            'partner_inn' =>  $document->document_json->sellertin,
            'partner_company_name' => $document->document_json->seller->name,
            'partner_bank_code' =>  $document->document_json->seller->account,
            'partner_mfo' =>  $document->document_json->seller->bankid,
            'partner_address' =>  $document->document_json->seller->address,
            'partner_director' =>  $document->document_json->seller->director,
            'partner_accountant' =>  $document->document_json->seller->accountant,
            'guarant_pinfl' =>  $document->document_json->agent->agenttin,
            'guarant_mfo' => null, // $document->document_json->agent->,
            'guarant_position' =>  $document->document_json->agent->jobtitle,
            'guarant_fio' =>  $document->document_json->agent->fio,
            'guarant_passport' =>  $document->document_json->agent->passport->number,
            'guarant_issue' =>  $document->document_json->agent->passport->issuedby,
            'guarant_issue_date' => $document->document_json->agent->passport->dateofissue,
            // 'total' => $document->total_sum,
            'status' => $document->doc_status,
            'doc_status' => $document->doc_status,
            'response' => json_encode($document,JSON_UNESCAPED_UNICODE)
        ]);
        if(!empty($document->products )) {
            foreach ($document->products as $item) {
                if (!$ikpu = Ikpu::where(['code' => $item->catalogcode])->first()) {
                    $ikpu = Ikpu::create(['code' => $item->catalogcode, 'title_ru' => $item->catalogname, 'title_uz' => $item->catalogname, 'title_en' => $item->catalogname, 'status' => 1]);
                }
                /* if(!empty($item->packagename) && !$unit = Unit::where(['title_ru'=>$item->packagename])->first()){
                    $unit = Unit::create(['slug'=>Str::slug($item->packagename),'title_ru'=>$item->packagename,'title_uz'=>$item->packagename,'title_en'=>$item->packagename,'status'=>1]);
                    $unit_id = $unit->id;
                }elseif(empty($item->packagename) ){
                    $unit_id = 0;
                } */
                GuarantItems::create([
                    'title' => $item->catalogname,
                    'ikpu_id' => $ikpu->id,
                    'guarant_id' => $guarant->id,
                    'company_id' => $company->id,
                    'unit_id' => $item->measureid, //  $unit_id,
                    'amount' => isset($item->summa) ? $item->summa : 0,
                    'quantity' => $item->count,
                ]);
            }
        }
        Elog::save('save ok '.$guarant->id . ' - ' . $document->doc_id);
        return ['status'=>true];

    }

    // вручную
    /*public static function createFromDidoxDocument($documents)
    {

        Elog::save('create from didox');
        if(empty($documents)) return false;

        $company_id = Company::getCurrentCompanyId();
        Elog::save('cid' . $company_id);
        //dd($documents);

        foreach($documents->data as $document){

            if(!$contract = Contract::where(['contract_number'=>$document->contract_number])->first()) {
                Elog::save('contract not found');
                continue;
            }
            if(!$companyInvoice = CompanyInvoice::where(['company_id'=>$company_id,'bank_invoice'=>$document->document_json->seller->account])->first()) {
                $companyInvoice = CompanyInvoice::create(['company_id'=>$company_id,'bank_invoice'=>$document->document_json->seller->account,'status'=>\App\Models\Status::STATUS_ACTIVE]);
            }

            $guarant = Guarant::create([
                'user_id'=>Auth::id(),
                'company_id'=>$company_id,
                'didox_id' => $document->doc_id,
                'guarant_id' => $document->document_json->empowermentid,
                'owner' => $document->owner,
                'company_inn' => $document->document_json->sellertin,
                'company_mfo' => $document->document_json->seller->bankid,
                'company_address' => $document->document_json->seller->address,
                'company_director' => $document->document_json->seller->director,
                'company_accountant' => $document->document_json->seller->accountant,
                'guarant_number'=>$document->document_json->empowermentdoc->empowermentno,
                'guarant_date'=>$document->document_json->empowermentdoc->empowermentdateofissue,
                'guarant_date_expire'=>$document->document_json->empowermentdoc->empowermentdateofexpire,
                'contract_id' => $contract->id,
                'company_invoice_id' => $companyInvoice->id,
                'partner_inn' =>  $document->document_json->buyertin,
                'partner_company_name' => $document->document_json->buyer->name,
                'partner_bank_code' =>  $document->document_json->buyer->account,
                'partner_mfo' =>  $document->document_json->buyer->bankid,
                'partner_address' =>  $document->document_json->buyer->address,
                'partner_director' =>  $document->document_json->buyer->director,
                'partner_accountant' =>  $document->document_json->buyer->accountant,
                'guarant_pinfl' =>  $document->document_json->agent->agenttin,
                'guarant_mfo' => null, // $document->document_json->agent->,
                'guarant_position' =>  $document->document_json->agent->jobtitle,
                'guarant_fio' =>  $document->document_json->agent->fio,
                'guarant_passport' =>  $document->document_json->agent->passport->number,
                'guarant_issue' =>  $document->document_json->agent->passport->issuedby,
                'guarant_issue_date' => $document->document_json->agent->passport->dateofissue,
                // 'total' => $document->total_sum,
                'status' => $document->doc_status,
                'doc_status' => $document->doc_status,
                'response' => json_encode($document,JSON_UNESCAPED_UNICODE)
            ]);
            foreach($document->products as $item) {
                if(!$ikpu = Ikpu::where(['code'=>$item->catalogcode])->first()){
                    $ikpu = Ikpu::create(['code'=>$item->catalogcode,'title_ru'=>$item->catalogname,'title_uz'=>$item->catalogname,'title_en'=>$item->catalogname,'status'=>1]);
                }
                / *if(!empty($item->packagename) && !$unit = Unit::where(['title_ru'=>$item->packagename])->first()){
                    $unit = Unit::create(['slug'=>Str::slug($item->packagename),'title_ru'=>$item->packagename,'title_uz'=>$item->packagename,'title_en'=>$item->packagename,'status'=>1]);
                    $unit_id = $unit->id;
                }elseif(empty($item->packagename) ){
                    $unit_id = 0;
                } * /
                GuarantItems::create([
                    'title'=> $item->catalogname,
                    'ikpu_id' => $ikpu->id,
                    'guarant_id'=> $guarant->id,
                    'company_id'=> $company_id,
                    'unit_id'=> $item->measureid, //  $unit_id,
                    'amount'=> isset($item->summa)?$item->summa:0,
                    'quantity'=> $item->count,
                ]);
            }

        }

    }*/

    public function getFieldInn()
    {
        return 'partner_inn';
    }

    public function getFieldContragentCompanyName()
    {
        return 'partner_company_name';
    }
}
