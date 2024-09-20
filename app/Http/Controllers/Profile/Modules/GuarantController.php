<?php

namespace App\Http\Controllers\Profile\Modules;

use App\Helpers\Elog;
use App\Helpers\FileHelper;
use App\Helpers\GuarantDocument;
use App\Helpers\ObjectHelper;
use App\Helpers\QrcodeHelper;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyIkpu;
use App\Models\CompanyInvoice;
use App\Models\CompanyPlan;
use App\Models\Contract;
use App\Models\GuarantItems;
use App\Models\Ikpu;
use App\Models\Invoice;
use App\Models\Module;
use App\Models\Movements;
use App\Models\Guarant;
use App\Models\Nomenklature;
use App\Models\OpenaiChat;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Queue;
use App\Models\Region;
use App\Models\Status;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserInfo;
use App\Services\DidoxService;
use App\Services\FacturaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class GuarantController extends Controller
{

    public function index(Request $request)
    {
        dd('not worked');

        $owner = DidoxService::getOwner($request);

        $update = $request->has('update') ? $request->update : false;

        $company_id = Company::getCurrentCompanyId();

        if($update && /*$owner == DidoxService::OWNER_TYPE_INCOMING &&*/ $company_id!=0) {
            set_time_limit(0);
             $result = DidoxService::getDocuments([
                'owner'=> $owner, // DidoxService::OWNER_TYPE_INCOMING,
                'status'=>'0,1,2,3,4,5,6,8,40,60',
                 'doctype' =>DidoxService::DOC_TYPE_GUARANTS,
                 'company_id' => $company_id
            ]);
        }
        $guarants = Guarant::myCompany()->with(['company','contractIncoming'])->where(['user_id'=>Auth::id(),'owner'=>$owner])->notDeletedOnly()/*where('status','!=',Status::STATUS_DELETED)*/
        ->order()
            ->orderBy('guarant_date','DESC')
            ->orderBy('guarant_number','DESC')
            ->paginate(15);

        return view('frontend.profile.modules.guarant.index', compact('guarants','owner','company_id'));
    }

    public function draft()
    {
        dd('not worked');

        $owner = DidoxService::OWNER_TYPE_NOT_USE;
        $guarants = Guarant::myCompany()->with(['company','contractIncoming'])->draft()->where(['user_id'=>Auth::id()/*,'status'=>Module::STATUS_DRAFT*/])->paginate(15);

        return view('frontend.profile.modules.document.index', compact('guarants','owner'));
    }



    public function create()
    {
        $regions       = Region::all();
        $cities        = City::all();
        $companies       = Company::getCompany(); //where(['user_id'=>Auth::id()])->get();

        $invoices    = CompanyInvoice::myCompany()->where(['status'=>Status::STATUS_ACTIVE])->get(); // счета учета
        $contracts    = Contract::myCompany()->signed()/*whereIn('status',[0,1,2,Status::STATUS_SIGNED])*/->owner(DidoxService::OWNER_TYPE_INCOMING)->get(); // договора

        $units = Unit::all();

        $company = Company::where(['id'=>Company::getCurrentCompanyId()])->with('ikpu')->first(); // одна компания для Select
        $ikpu = !empty($company->ikpu) ? $company->ikpu : null;

        $maxId = Guarant::max('guarant_number');
        $number = $maxId+1;
        $plans = Plan::all();
        $selected_plan = null;
        if(!empty($company)){
            $selected_plan = CompanyPlan::where(['company_id'=>$company->id,'document_type'=>DidoxService::DOC_TYPE_GUARANTS])->select('plan_id')->first();
        }
        return view('frontend.profile.modules.guarant.form', compact('regions', 'cities', 'companies', 'company','invoices',/*'products',*/'units','ikpu','contracts','number','plans','selected_plan'));
    }

    public function edit(Guarant $guarant)
    {
        if(!Company::checkCompany($guarant->company_id)) abort(404);

        $guarant->with(['items','company.invoices','contract','plan']);

        $regions       = Region::all();
        $cities        = City::all();
        $companies         = Company::getCompany(); // where(['user_id'=>Auth::id()])->get();
        $contracts    = Contract::myCompany()->/*where(['status'=>Status::STATUS_ACTIVE])->*/owner(DidoxService::OWNER_TYPE_INCOMING)->get(); // договора

        $movements = Movements::where(['status'=>1])->get(); // статьи движений
        $units = Unit::all();

        $company = Company::where(['id'=>Company::getCurrentCompanyId()])->with('ikpu')->first(); // одна компания для Select
        $ikpu = !empty($company->ikpu) ? $company->ikpu : null;

        $plans = Plan::all();
        $selected_plan = CompanyPlan::where(['company_id'=>$guarant->company_id,'document_type'=>DidoxService::DOC_TYPE_GUARANTS])->select('plan_id')->first();

        return view('frontend.profile.modules.guarant.form', compact('guarant', 'regions', 'cities', 'companies', 'movements', 'units', 'ikpu', 'contracts','plans','selected_plan'));

    }

    public function view(Guarant $guarant)
    {
        if(!Company::checkCompany($guarant->company_id)) abort(404);

        $guarant->with(['items','company.invoices','contract']);

        $regions       = Region::all();
        $cities        = City::all();
        $companies         = Company::getCompany(); // where(['user_id'=>Auth::id()])->get();
        $contracts    = Contract::myCompany()->/*where(['status'=>Status::STATUS_ACTIVE])->*/owner(DidoxService::OWNER_TYPE_INCOMING)->get(); // договора
        $movements = Movements::where(['status'=>1])->get(); // статьи движений
        $units = Unit::all();
        $company = Company::where(['id'=>Company::getCurrentCompanyId()])->with('ikpu')->first(); // одна компания для Select
        $ikpu = !empty($company->ikpu) ? $company->ikpu : null;

        $response = json_decode($guarant->response);
        $responseSign = !empty($guarant->response_sign) ? json_decode($guarant->response_sign) : null;

        if($guarant->doc_status==DidoxService::STATUS_SIGNED && $responseSign){
            $signature = !empty($responseSign->data->document->signature);
        }else {
            $signature = !empty($response->signature); // ? json_decode($response->signature)[0] : null;
        }

        $owner = $response->document_json->seller;
        $client = $response->document_json->buyer;

        $agent = json_decode(json_encode(['fio'=>$guarant->guarant_fio,'tin'=>$guarant->guarant_pinfl],JSON_UNESCAPED_UNICODE));

        if($guarant->owner==DidoxService::OWNER_TYPE_OUTGOING) {
            $ownerCompany = $guarant->company;
            if(!$clientCompany =  Company::where(['inn' => $response->document_json->buyertin])->first()){
                // TODO no-company
            }
        }else{
            $ownerCompany = Company::where(['inn' => $response->document_json->sellertin])->first();
            $clientCompany = $guarant->company;
        }

        $owner->tin = !empty($response->document_json->buyertin) ? $response->document_json->buyertin : (!empty($response->document_json->clients[0]->tin)?$response->document_json->clients[0]->tin : ''); //buyertin;
        $client->tin = $response->document_json->sellertin;
        $owner->bank_name = !empty($ownerCompany) ? $ownerCompany->bank_name : '';
        $owner->phone = !empty($ownerCompany) ? $ownerCompany->phone : '';
        $client->bank_name = !empty($clientCompany) ? $clientCompany->bank_name : '';
        $client->phone = !empty($clientCompany) ? $clientCompany->phone : '';

        $sign = '';
        if(!empty($responseSign) && ( $guarant->doc_status==DidoxService::STATUS_WAIT_YOUR_SIGNATURE || $guarant->doc_status==DidoxService::STATUS_WAIT_FOR_AGENT_SIGNATURE)) {
            $sign = !empty($responseSign->data) ? $responseSign->data->toSign : '';
            $document = $sign;
        }else{
            $document = json_encode($response->document_json, JSON_UNESCAPED_UNICODE);
        }

        if($guarant->doc_status==DidoxService::STATUS_SIGNED) {

            if (!empty($responseSign->data->document->signature)) {
                $signature = json_decode($responseSign->data->document->signature);
                $this->getSignatureInfo($signature, $owner, $client, $agent);
            }
        }

        $qrcode = QrcodeHelper::create($_SERVER['HTTP_HOST'].'/'.app()->getLocale().'/guarant/'.$guarant->didox_id);

        if(empty($guarant->chat)) {
            $openai = true;
            $params = [
                'template'=>view('frontend.profile.modules.guarant.view', compact('guarant', 'regions', 'cities', 'companies', 'movements', 'units', 'ikpu', 'contracts','document','sign','owner','client','qrcode','signature','agent','company','openai'))->render(),
                'doctype' => 'Guarant',
                'document_id' => $guarant->id,
                'company_id' => $guarant->company_id
            ];
            OpenaiChat::init($params);
            $guarant->load('chat.chatItems');
        }
        $chatItems =  $guarant->chat->chatItems;

        return view('frontend.profile.modules.guarant.view', compact('guarant', 'regions', 'cities', 'companies', 'movements', 'units', 'ikpu', 'contracts','document','sign','owner','client','qrcode','signature','agent','company','chatItems'));

    }
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User         $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Guarant $guarant)
    {
        $params = $request->all();
        $productItems = $params['product_items'];
        $update_products = $params['update_products'];
        unset($params['product_items']);
        unset($params['update_products']);
        $guarant->update($params);
        if($update_products) $this->createProductItems($productItems,$guarant);
        $didox_id = $guarant->didox_id;
        if(($guarant->wasChanged() || $update_products) && $company = Company::where(['id'=>$params['company_id']])->first()) {
            $guarant = GuarantDocument::getTemplate($guarant,$productItems,$company);
            $filter = [
                'owner' => DidoxService::OWNER_TYPE_OUTGOING,
                //'status' => '0,1,2,3,4,5,6,8,40,60',
                'doctype' => DidoxService::DOC_TYPE_FACTURA,
                'company_id' => $company->id,
                'didox_id' => $didox_id
            ];
            $result = DidoxService::updateDocument($filter,$guarant,$company);
            if(!empty($result['data'])) $guarant->update(['response' => json_encode($result['data'],JSON_UNESCAPED_UNICODE)]);

            if($request->has('save_plan')) CompanyPlan::updatePlan(DidoxService::DOC_TYPE_GUARANTS,$params);

        }


        return redirect()->route('frontend.profile.modules.document.index', app()->getLocale());
    }

    public function store(Request $request)
    {

        $params = $request->all();

        $params['user_id'] = Auth::id();

        $company = Company::where(['id'=>$params['company_id']])->first();
        $update = [];
        if(empty($company->director)) $update['director'] = $params['company_director'];
        if(empty($company->accountant)) $update['accountant'] = $params['company_accountant'];
        unset($params['update_products']);

        CompanyPlan::updatePlan(DidoxService::DOC_TYPE_GUARANTS,$params);

        $queue = Queue::create([
            'company_id'=>$params['company_id'],
            'doctype'=>DidoxService::DOC_TYPE_GUARANTS,
            'params'=>json_encode($params,JSON_UNESCAPED_UNICODE),
            'type' => Queue::TYPE_OUTGOING,
            'status'=>Queue::STATUS_WAIT
        ]);

        if(count($update)) $company->update($update);

        /*if($company = Company::where(['id'=>$params['company_id']])->first()) {
            $guarant = new Guarant($params);
            $guarant = GuarantDocument::getTemplate($guarant,$productItems,$company);
            $filter = [
                'owner' => DidoxService::OWNER_TYPE_OUTGOING,
                'status' => '0,1,2,3,4,5,6,8,40,60',
                'doctype' => DidoxService::DOC_TYPE_GUARANTS,
                'company_id' => $company->id
            ];
            $result = DidoxService::createDocument($filter,$guarant,$company);
            DidoxService::getDocuments($filter);
            if($guarant = Guarant::where(['didox_id'=>$result->_id])->first()){
                $guarant->update($params);
            }
        }*/

        return redirect()->route('frontend.profile.modules.document.index', ['owner'=>'outgoing', app()->getLocale()])->with('success', __('main.guarant_queue'));


    }

    private function getSignatureInfo(&$signature,&$owner,&$client,&$agent){
        foreach ($signature as $target) {
            if ($target->taxId == $owner->tin) {
                $owner->serial = $target->serial;
                $owner->company = $target->company;
                $owner->fio = $target->firstName . ' ' . $target->lastName;
                $owner->signing_time = $target->signingTime;
                continue;
            }
            if ($target->taxId == $client->tin) {
                $client->serial = $target->serial;
                $client->company = $target->company;
                $client->fio = $target->firstName . ' ' . $target->lastName;
                $client->signing_time = $target->signingTime;
                continue;
            }
            if ($target->pinfl == $agent->tin) {
                $agent->serial = $target->serial;
                $agent->fio = $target->firstName . ' ' . $target->lastName;
                $agent->signing_time = $target->signingTime;
                continue;
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Guarant $guarant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Guarant $guarant)
    {

        $company= $guarant->company;

        $result = DidoxService::deleteDocument($guarant,$company);

        $error='';
        if(!empty($result->data)) {
            if ($result->data) {
                $guarant->update(['status' => DidoxService::STATUS_DELETED, 'doc_status' => DidoxService::STATUS_DELETED]);
                //$contract->delete();\
                return redirect()->route('frontend.profile.modules.document.index', ['owner'=>'outgoing', app()->getLocale()])->with('success',__('main.success'));
            }else{
                $error = !empty($result->data->message) ? $result->data->message : 'service error';
            }
        }else{
            $error = 'service error';
        }

        return redirect()->route('frontend.profile.modules.document.index',['owner'=>'outgoing', app()->getLocale()])->with('error',$error);
    }

    public function getInvoices(Request $request){

        if(!empty($request->company_id)){

            $company_invoices = CompanyInvoice::where(['company_id'=>$request->company_id])->get();

            foreach ($company_invoices as $invoice){
                $invoices[] = "<option value='{$invoice->id}'>{$invoice->invoice}</option>";
            }

            return ['status'=>true,'data'=>$invoices];
        }
        return ['status'=>false];

    }
    private function createProductItems(&$productItems,&$guarant){
        GuarantItems::where(['company_id'=>$guarant->company_id,'guarant_id'=>$guarant->id])->delete();
        foreach($productItems as $productItem) {
            $productItem = explode('|',$productItem);
            GuarantItems::create([
                'company_id' => $guarant->company_id,
                'guarant_id' => $guarant->id,
                'ikpu_id' => $productItem[0],
                'title' => $productItem[1],
                'unit_id' => $productItem[2],
                'quantity' => $productItem[3],
                'amount' => $productItem[4]
            ]);
        }
        return true;
    }

    public function checkStatus(Request $request,Guarant $guarant)
    {

        if(!Company::checkCompany($guarant->company_id)) abort(404);
        $referer =  $request->headers->get('referer');

        $guarant->load('company');
        $company = $guarant->company;
        $response = DidoxService::getDocument($guarant,$company);

        if(empty($response)){
            $company->update(['token_expire' => 0]);
            return redirect()->to($referer)->with('error', __('main.try_again'));
        }

        if($response->data->document->status!=$guarant->status || $response->data->document->doc_status!=$guarant->doc_status || empty($guarant->response_sign)){

            $info = __('main.changed');
            if(!empty($response->data->toSign) || !empty($response->data->document->signature)) {
                /* if($response->data->document->doc_status==DidoxService::STATUS_SIGNED){
                    $sign = json_encode($response,JSON_UNESCAPED_UNICODE);
                }else {
                    $sign = json_encode(['data' => ['toSign' => $response->data->toSign]], JSON_UNESCAPED_UNICODE);
                }*/
                $sign = json_encode($response,JSON_UNESCAPED_UNICODE);

            }else{
                $sign = null;
            }
            Elog::save('sign');
            Elog::save($sign);

            $guarant->update(['status'=>$response->data->document->status,'doc_status'=>$response->data->document->doc_status,'response_sign'=>$sign,'checked_at' => date('Y-m-d H:i:s',time())]);

        }else{
            $info = __('main.no_changes');
        }
        return redirect()->to($referer)->with('success', $info);
    }

    public function sign(Request $request, Guarant $guarant){
        $guarant->load('items','company');
        $filter = [
            'didox_id' => $guarant->didox_id,
            'signature' => $request->signature
        ];
        $result = DidoxService::signDocument($filter,$guarant->company);
        if($result['data']['status']!='error') {
            $guarant->update(['response_sign' => json_encode($result, JSON_UNESCAPED_UNICODE)]);

            // обновить колво товаров
            if(!empty($guarant->items)){
                Nomenklature::recalculateQuantity($guarant);
            }

            // TODO : нужно установить статус подписи

            return ['status' => true];
        }
        return ['status'=>false,'error'=>$result['data']['message']];

    }

    public function print(Guarant $guarant)
    {
        if(!Company::checkCompany($guarant->company_id)) abort(404);

        $guarant->with(['items','company.invoices','contract']);

        $companies    = Company::getCompany(); // where(['user_id'=>Auth::id()])->get();
        $contracts    = Contract::myCompany()->/*where(['status'=>Status::STATUS_ACTIVE])->*/owner(DidoxService::OWNER_TYPE_INCOMING)->get(); // договора
        $movements = Movements::where(['status'=>1])->get(); // статьи движений
        $units = Unit::all();
        $company = Company::where(['id'=>Company::getCurrentCompanyId()])->with('ikpu')->first(); // одна компания для Select
        $ikpu = !empty($company->ikpu) ? $company->ikpu : null;

        $response = json_decode($guarant->response);
        $responseSign = !empty($guarant->response_sign) ? json_decode($guarant->response_sign) : null;

        if($guarant->doc_status==DidoxService::STATUS_SIGNED && $responseSign){
            $signature = !empty($responseSign->data->document->signature);
        }else {
            $signature = !empty($response->signature); // ? json_decode($response->signature)[0] : null;
        }

        $owner = $response->document_json->seller;
        $client = $response->document_json->buyer;

        $agent = json_decode(json_encode(['fio'=>$guarant->guarant_fio,'tin'=>$guarant->guarant_pinfl],JSON_UNESCAPED_UNICODE));

        if($guarant->owner==DidoxService::OWNER_TYPE_OUTGOING) {
            $ownerCompany = $guarant->company;
            if(!$clientCompany =  Company::where(['inn' => $response->document_json->buyertin])->first()){
                // TODO no-company
            }
        }else{
            $ownerCompany = Company::where(['inn' => $response->document_json->sellertin])->first();
            $clientCompany = $guarant->company;
        }

        $owner->tin = !empty($response->document_json->buyertin) ? $response->document_json->buyertin : (!empty($response->document_json->clients[0]->tin)?$response->document_json->clients[0]->tin : ''); //buyertin;
        $client->tin = $response->document_json->sellertin;
        $owner->bank_name = !empty($ownerCompany) ? $ownerCompany->bank_name : '';
        $owner->phone = !empty($ownerCompany) ? $ownerCompany->phone : '';
        $client->bank_name = !empty($clientCompany) ? $clientCompany->bank_name : '';
        $client->phone = !empty($clientCompany) ? $clientCompany->phone : '';

        $sign = '';
        if(!empty($responseSign) && ( $guarant->doc_status==DidoxService::STATUS_WAIT_YOUR_SIGNATURE || $guarant->doc_status==DidoxService::STATUS_WAIT_FOR_AGENT_SIGNATURE)) {
            $sign = !empty($responseSign->data) ? $responseSign->data->toSign : '';
            $document = $sign;
        }else{
            $document = json_encode($response->document_json, JSON_UNESCAPED_UNICODE);
        }

        if($guarant->doc_status==DidoxService::STATUS_SIGNED) {

            if (!empty($responseSign->data->document->signature)) {
                $signature = json_decode($responseSign->data->document->signature);
                $this->getSignatureInfo($signature, $owner, $client, $agent);
            }
        }

        $qrcode = QrcodeHelper::create($_SERVER['HTTP_HOST'].'/'.app()->getLocale().'/guarant/'.$guarant->didox_id);

        return view('frontend.profile.modules.guarant.print', compact('guarant',  'companies', 'movements', 'units', 'ikpu', 'contracts','document','sign','owner','client','qrcode','signature','agent','company'));

    }

    public function download(Guarant $guarant){
        if(!Company::checkCompany($guarant->company_id)) abort(404);

        $guarant->load('company');

        $guarant->with(['items','company.invoices','contract']);

        $response = json_decode($guarant->response);
        $responseSign = !empty($guarant->response_sign) ? json_decode($guarant->response_sign) : null;

        if($guarant->doc_status==DidoxService::STATUS_SIGNED && $responseSign){
            $signature = !empty($responseSign->data->document->signature);
        }else {
            $signature = !empty($response->signature); // ? json_decode($response->signature)[0] : null;
        }

        $owner = $response->document_json->seller;
        $client = $response->document_json->buyer;

        $agent = json_decode(json_encode(['fio'=>$guarant->guarant_fio,'tin'=>$guarant->guarant_pinfl],JSON_UNESCAPED_UNICODE));

        if($guarant->owner==DidoxService::OWNER_TYPE_OUTGOING) {
            $ownerCompany = $guarant->company;
            if(!$clientCompany =  Company::where(['inn' => $response->document_json->buyertin])->first()){
                // TODO no-company
            }
        }else{
            $ownerCompany = Company::where(['inn' => $response->document_json->sellertin])->first();
            $clientCompany = $guarant->company;
        }

        $owner->tin = !empty($response->document_json->buyertin) ? $response->document_json->buyertin : (!empty($response->document_json->clients[0]->tin)?$response->document_json->clients[0]->tin : ''); //buyertin;
        $client->tin = $response->document_json->sellertin;
        $owner->bank_name = !empty($ownerCompany) ? $ownerCompany->bank_name : '';
        $owner->phone = !empty($ownerCompany) ? $ownerCompany->phone : '';
        $client->bank_name = !empty($clientCompany) ? $clientCompany->bank_name : '';
        $client->phone = !empty($clientCompany) ? $clientCompany->phone : '';

        if($guarant->doc_status==DidoxService::STATUS_SIGNED) {

            if (!empty($responseSign->data->document->signature)) {
                $signature = json_decode($responseSign->data->document->signature);
                $this->getSignatureInfo($signature, $owner, $client, $agent);
            }
        }

        $qrcode = QrcodeHelper::create($_SERVER['HTTP_HOST'].'/'.app()->getLocale().'/guarant/'.$guarant->didox_id);


        $params = [
            'filename' => 'guarant_'.$guarant->guarant_number.'-'.$guarant->guarant_date,
            'data' => compact('guarant','qrcode','owner','client','signature','agent'),
            'type'=>'guarant'
        ];

        $result = FileHelper::createArchieve($guarant,$params);
        if(!$result['status']) {
            Session::flash('error',$result['error']);
            return redirect()->to(request()->headers->get('referer'));
        }
        FileHelper::download($result['file']);

    }

}
