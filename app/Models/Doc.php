<?php

namespace App\Models;

use App\Helpers\Elog;
use App\Helpers\FileHelper;
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

class Doc extends Model implements FilterInterface
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
        return $this->hasMany(DocItems::class,'doc_id','id')->with(['ikpu','package','nds','warehouse']);
    }
    public function scopeOwner($query, $owner){
        return $query->where(['owner'=>$owner]);
    }

    public function chat(){
        return $this->hasOne(OpenaiChat::class,'document_id','id')->where(['doctype'=>'Doc']);
    }

    public static function createDocumentFromDidox($document,&$company)
    {
        Elog::save('Create Doc');
        Elog::save($document);
        if(empty($document)) return ['status'=>false,'error'=>'Doc empty document'];

            if(!empty($document->contract_number) &&  !$contract = Contract::where(['contract_number'=>$document->contract_number])->first()) {
                Elog::save('error contract not found '. $document->contract_number);
                //return ['status'=>false,'error'=>'Contract not found ' . $document->contract_number];

                /** todo contract */

            };

            $doc = Doc::create([
                'user_id'=>$company->user_id, //Auth::id(),
                'company_id'=>$company->id,
                'didox_id' => $document->doc_id,
                'doc_id' => $document->document_json->documentid,
                'owner' => $document->owner,
                'parties_owner' => $document->parties_owner,
                'contract_id' => !empty($contract) ? $contract->id : null,
                'number' => $document->document_json->document->documentno,
                'name' => $document->document_json->document->documentname,
                'date' => $document->document_json->document->documentdate,
                'date_updated_at' => $document->updated,
                'company_inn' => $document->document_json->buyertin,
                'company_name' => $document->document_json->buyer->name,
                'company_address' => $document->document_json->buyer->address,
                'partner_inn' =>  $document->document_json->sellertin,
                'partner_company_name' => $document->document_json->seller->name,
                'partner_phone' => correct_phone($document->partnerPhone),
                'partner_address' => $document->document_json->seller->address,
                'total_sum'=>  $document->total_sum,
                'total_delivery_sum'=>  $document->total_delivery_sum,
                'total_vat_sum'=> $document->total_vat_sum,
                'total_delivery_sum_with_vat'=> $document->total_delivery_sum_with_vat,
                'has_vat'=> $document->has_vat,
                'status' => $document->status,
                'doc_status' => $document->doc_status,
                'url' => $document->document_json->url,
                'hash' => $document->document_json->hash,
                'response' => json_encode($document,JSON_UNESCAPED_UNICODE)
            ]);
            DidoxService::getFile($company,$document->doc_id);

            Elog::save('DOC save ok '. $doc->id . ' - ' . $document->doc_id);

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

    public function getFile(){
        $filename = 'document_'.$this->didox_id .'.pdf';
        $filepath = public_path() . '/documents/'.$this->didox_id.'/' . $filename;
        if(!file_exists($filepath)){
            FileHelper::createDir('documents/'.$this->didox_id);
            $this->getFileDidox();
        }
        return '/documents/'.$this->didox_id .'/' . $filename;
    }

   public function getFileDidox(){
       $this->load('company');
       $company = $this->company;
       return DidoxService::getFile($company,$this->didox_id,'base');
    }


}
