<?php

namespace App\Http\Controllers\Profile\Modules;

use App\Helpers\Elog;
use App\Helpers\FileHelper;
use App\Helpers\PdfHelper;
use App\Helpers\ProductDocument;
use App\Helpers\QrcodeHelper;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyPlan;
use App\Models\CompanyWarehouse;
use App\Models\Contract;
use App\Models\Nds;
use App\Models\Nomenklature;
use App\Models\OpenaiChat;
use App\Models\Package;
use App\Models\Plan;
use App\Models\Product;
use App\Models\ProductItems;
use App\Models\Queue;
use App\Models\Status;
use App\Services\DidoxService;
use App\Services\FacturaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class WaybillController extends Controller
{

    public function create(Request $request)
    {
        $maxNumber = Waybill::mycompany()->owner(DidoxService::OWNER_TYPE_OUTGOING)->max('number');
        $new_number = !empty($maxNumber) && is_numeric($maxNumber) ? $maxNumber+1 : 1;
        $companies = Company::getCompany();
        $nomenklatures = Nomenklature::all();
        $packages = Package::all();
        $contracts = Contract::myCompany()->signed()->orderBy('contract_number','DESC')->get();
        $company = Company::where(['id'=>Company::getCurrentCompanyId()])->with('ikpu')->first(); // одна компания для Select
        $ikpu = !empty($company->ikpu) ? $company->ikpu : null;
        $nds = Nds::where(['status'=>Status::STATUS_ACTIVE])->get();
        $warehouse = CompanyWarehouse::myCompany()->active()->get();
        $product_origin = Product::getOriginList();

        $owner = DidoxService::getOwner($request);
        $owner = DidoxService::getOwnerLabel($owner);
        $plans = Plan::all();
        $selected_plan = null;
        if(!empty($company)){
            $selected_plan = CompanyPlan::where(['company_id'=>$company->id,'document_type'=>DidoxService::DOC_TYPE_FACTURA])->select('plan_id')->first();
        }

        return view('frontend.profile.modules.waybill.form', compact('companies','nomenklatures','packages','contracts','ikpu','nds','warehouse','product_origin','new_number','owner','plans','selected_plan'));

    }

    public function edit(Waybill $waybill)
    {
        if(!Company::checkCompany($waybill->company_id)) abort(404);
        if($waybill->owner!=DidoxService::OWNER_TYPE_OUTGOING) return $this->view($waybill);

        $waybill->with(['items','company','plan']);
        $companies         = Company::getCompany();
        $nomenklatures = Nomenklature::all();
        $packages = Package::all();  // Unit::all();
        $contracts = Contract::myCompany()->orderBy('contract_number','DESC')->get();
        $company = Company::where(['id'=>Company::getCurrentCompanyId()])->with('ikpu')->first(); // одна компания для Select
        $ikpu = !empty($company->ikpu) ? $company->ikpu : null;
        $nds = Nds::where(['status'=>Status::STATUS_ACTIVE])->get();
        $warehouse = CompanyWarehouse::myCompany()->active()->get();
        $product_origin = Product::getOriginList();
        $owner = DidoxService::getOwnerLabel($waybill->owner);
        $plans = Plan::all();
        $selected_plan = CompanyPlan::where(['company_id'=>$waybill->company_id,'document_type'=>DidoxService::DOC_TYPE_FACTURA])->select('plan_id')->first();

        return view('frontend.profile.modules.waybill.form', compact('waybill', 'companies','nomenklatures','packages','contracts','nds','warehouse','ikpu','product_origin','owner','plans','selected_plan'));

    }

    public function view(Waybill $waybill)
    {
        if(!Company::checkCompany($waybill->company_id)) abort(404);

        $waybill->load(['items','company','chat.chatItems']);

        $companies         = Company::getCompany();
        $nomenklatures = Nomenklature::all();
        $contracts = Contract::myCompany()->orderBy('contract_number','DESC')->get();
        $company = Company::where(['id'=>Company::getCurrentCompanyId()])->with('ikpu')->first(); // одна компания для Select
        $ikpu = !empty($company->ikpu) ? $company->ikpu : null;
        $nds = Nds::where(['status'=>Status::STATUS_ACTIVE])->get();
        $warehouse = CompanyWarehouse::myCompany()->active()->get();
        $product_origin = Product::getOriginList();


        $response = json_decode($waybill->response);
        $signature = !empty($response->signature)?json_decode($response->signature)[0]:null;
        if(!empty($waybill->response_sign) && $waybill->doc_status==DidoxService::STATUS_WAIT_YOUR_SIGNATURE) {
            $responseSign = json_decode($waybill->response_sign);
            $sign = !empty($responseSign->data) ? $responseSign->data->toSign : '';
            $document = $sign;
        }else {
            $document = json_encode($response->document_json, JSON_UNESCAPED_UNICODE);
            $sign = null;
        }
        $owner = $response->document_json->seller;
        $client = $response->document_json->buyer;

        if($waybill->owner==DidoxService::OWNER_TYPE_OUTGOING) {
            $ownerCompany = $waybill->company;
            if(!$clientCompany = Company::where(['inn' => $response->document_json->buyertin])->first()){
                // TODO no-company
            }
        }else{
            $ownerCompany = Company::where(['inn' => $response->document_json->sellertin])->first();
            $clientCompany = $waybill->company;
        }

        $owner->tin = $response->document_json->buyertin;

        $owner->bank_name = !empty($ownerCompany) ? $ownerCompany->bank_name : '';
        $owner->phone = !empty($ownerCompany) ? $ownerCompany->phone : '';

        $client->tin = $response->document_json->sellertin;
        $client->bank_name = !empty($clientCompany) ? $clientCompany->bank_name : '';
        $client->phone = !empty($clientCompany) ? $clientCompany->phone : '';

        $qrcode = QrcodeHelper::create($_SERVER['HTTP_HOST'].'/'.app()->getLocale().'/waybill/'.$waybill->didox_id);

        if(empty($waybill->chat)) {
            $openai = true;
            $params = [
                'template'=>view('frontend.profile.modules.waybill.view', compact('waybill', 'companies','nomenklatures','contracts','nds','warehouse','ikpu','product_origin','sign','owner','client','signature','openai','qrcode'))->render(),
                'doctype' => 'Waybill',
                'document_id' => $waybill->id,
                'company_id' => $waybill->company_id
            ];
            OpenaiChat::init($params);
            $waybill->load('chat.chatItems');
        }
        $chatItems =  $waybill->chat->chatItems;

        return view('frontend.profile.modules.waybill.view', compact('waybill', 'companies','nomenklatures','contracts','nds','warehouse','ikpu','product_origin','sign','document','owner','client','qrcode','signature','chatItems'));

    }

    private function getInfo(&$object,&$response){

        $response = json_decode($object->response);
        $signature = !empty($response->signature)?json_decode($response->signature)[0]:null;
        $sign = '';
        if(!empty($object->response_sign) && $object->doc_status==DidoxService::STATUS_WAIT_YOUR_SIGNATURE) {
            $responseSign = json_decode($object->response_sign);
            $sign = !empty($responseSign->data) ? $responseSign->data->toSign : '';
            $document = $sign;
        }else {
            $document = json_encode($response->document_json, JSON_UNESCAPED_UNICODE);
            $sign = null;
        }
        $owner = $response->document_json->seller;
        $client = $response->document_json->buyer;

        if($object->owner==DidoxService::OWNER_TYPE_OUTGOING) {
            $ownerCompany = $object->company;
            if(!$clientCompany = Company::where(['inn' => $response->document_json->buyertin])->first()){
                // TODO no-company
            }
        }else{
            $ownerCompany = Company::where(['inn' => $response->document_json->sellertin])->first();
            $clientCompany = $object->company;
        }

        $owner->tin = $response->document_json->buyertin;

        $owner->bank_name = !empty($ownerCompany) ? $ownerCompany->bank_name : '';
        $owner->phone = !empty($ownerCompany) ? $ownerCompany->phone : '';

        $client->tin = $response->document_json->sellertin;
        $client->bank_name = !empty($clientCompany) ? $clientCompany->bank_name : '';
        $client->phone = !empty($clientCompany) ? $clientCompany->phone : '';
        $objectName = explode('\\',strtolower(get_class($object)));
        $objectName = end($objectName);

        // $qrcode = QrcodeHelper::create($_SERVER['HTTP_HOST'].'/'.app()->getLocale().'/'.$objectName.'/'.$object->didox_id);

        return ['owner'=>$owner,'client'=>$client,/*'qrcode'=>$qrcode,*/'signature'=>$signature/*,'sign'=>$sign,'document'=>$document*/];

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product         $waybill
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Waybill $waybill)
    {
        $params = $request->all();

        $waybillItems = $params['product_items'];
        $update_products = $params['update_products'];
        unset($params['product_items']);
        unset($params['update_products']);
        $waybill->update($params);
        if($update_products) $this->createProductItems($waybillItems,$waybill);

        if(($waybill->wasChanged() || $update_products) && $company = Company::where(['id'=>$params['company_id']])->first()) {
            $didox_id = $waybill->didox_id;
            $partner = FacturaService::getCompanyInfo($waybill->partner_inn);
            $waybill = ProductDocument::getTemplate($waybill,$waybillItems,$company,$partner);
            $filter = [
                'owner' => DidoxService::OWNER_TYPE_OUTGOING,
                //'status' => '0,1,2,3,4,5,6,8,40,60',
                'doctype' => DidoxService::DOC_TYPE_WAYBILL,
                'company_id' => $company->id,
                'didox_id' => $didox_id
            ];
            Elog::save('create waybill');
            $result = DidoxService::updateDocument($filter,$waybill,$company);
            if(!empty($result['data'])) $waybill->update(['response' => json_encode($result['data'],JSON_UNESCAPED_UNICODE)]);
            if($request->has('save_plan')) CompanyPlan::updatePlan(DidoxService::DOC_TYPE_FACTURA,$params);

        }

        return redirect()->route('frontend.profile.modules.document.index', app()->getLocale());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'contract_id' => 'required',
                'date' => 'required',
                'company_id' => 'required',
                'partner_inn' => 'required',
                'partner_company_name' => 'required',
                'partner_bank_code' => 'required',
                'product_items' => 'required',
            ]
        );

        $params = $request->all();
        $params['user_id'] = Auth::id();

        if ($validator->fails()) {
            return redirect()->route('frontend.profile.modules.waybill.create', app()->getLocale())
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors());
        }
        $params['type'] = 1;
        CompanyPlan::updatePlan(DidoxService::DOC_TYPE_FACTURA,$params);

        Queue::create([
            'company_id'=>$params['company_id'],
            'doctype'=>DidoxService::DOC_TYPE_WAYBILL,
            'params'=>json_encode($params,JSON_UNESCAPED_UNICODE),
            'type' => Queue::TYPE_OUTGOING,
            'status'=>Queue::STATUS_WAIT
        ]);

        return redirect()->route('frontend.profile.modules.document.index', ['owner'=>'outgoing', app()->getLocale()])->with('success', __('main.factura_queue'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Waybill $waybill
     * @return \Illuminate\Http\Response
     */
    public function destroy(Waybill $waybill)
    {

        $company= $waybill->company;

        $result = DidoxService::deleteDocument($waybill,$company);
        $error='';
        if(!empty($result->data)) {
            if ($result->data) {
                $waybill->update(['status' => DidoxService::STATUS_DELETED, 'doc_status' => DidoxService::STATUS_DELETED]);
                //$contract->delete();
                return redirect()->route('frontend.profile.modules.document.index', ['owner'=>'outgoing', app()->getLocale()])->with('success',__('main.success'));
            }else{
                $error = !empty($result->data->message) ? $result->data->message : 'service error';
            }
        }else{
            $error = 'service error';
        }
        return redirect()->route('frontend.profile.modules.document.index', ['owner'=>'outgoing', app()->getLocale()])->with('error',$error);
    }

    public function checkStatus(Request $request,Waybill $waybill)
    {

        if(!Company::checkCompany($waybill->company_id)) abort(404);
        $referer =  $request->headers->get('referer');

        $waybill->load('company');
        $company = $waybill->company;
        $response = DidoxService::getDocument($waybill,$company);

        if(empty($response)){
            $company->update(['token_expire' => 0]);
            return redirect()->to($referer)->with('error', __('main.try_again'));
        }

        if($response->data->document->status!=$waybill->status || $response->data->document->doc_status!=$waybill->doc_status || empty($waybill->response_sign)){

            $info = __('main.changed');
            /* if(!empty($response->data->toSign)) {
                $sign = json_encode($response,JSON_UNESCAPED_UNICODE) ; // ['data'=>['toSign' =>$response->data->toSign]],JSON_UNESCAPED_UNICODE);
            }else{
                $sign = null;
            } */
            //$sign = json_encode($response,JSON_UNESCAPED_UNICODE);

        }else{
            $info = __('main.no_changes');
        }

        $waybill->update(['status'=>$response->data->document->status,'doc_status'=>$response->data->document->doc_status,'response_sign'=>json_encode($response,JSON_UNESCAPED_UNICODE),'checked_at' => date('Y-m-d H:i:s',time())]);

        return redirect()->to($referer)->with('success', $info);
    }

    public function getNomenklatures(Request $request){

        if(!empty($request->nomenklature_id)){

            //dd($request->all());
            //$nomenklature = Nomenklature::with(['category','unit','nds'])->where(['id'=>$request->nomenklature_id])->first();

            $nomenklatures  = [] ; /* [
                'category' => !empty($nomenklature->category) ? $nomenklature->category->getTitle() : __('main.not_set'),
                 'unit' => !empty($nomenklature->unit)?$nomenklature->unit->getTitle(): __('main.not_set'),
                'nds' => !empty($nomenklature->nds)?$nomenklature->nds->getTitle(): __('main.not_set'),
               // 'nomenklature' => $nomenklature->getTitle(),
                'article' => $nomenklature->article
            ]; */

            return ['status'=>true,'data'=>$nomenklatures];
        }
        return ['status'=>false];
    }

    private function createProductItems(&$waybillItems,&$waybill){
        ProductItems::where(['company_id'=>$waybill->company_id,'product_id'=>$waybill->id])->delete();
        foreach($waybillItems as $waybillItem) {
            $waybillItem = explode('|',$waybillItem);
            ProductItems::create([
                'company_id' => $waybill->company_id,
                'product_id' => $waybill->id,
                'ikpu_id' => $waybillItem[0],
                'title' => $waybillItem[1],
                'barcode' => $waybillItem[2],
                'unit_id' => $waybillItem[3],
                'quantity' => $waybillItem[4],
                'amount' => $waybillItem[5],
                'nds_id' => $waybillItem[6],
                'company_warehouse_id' => $waybillItem[7],
                'product_origin' => $waybillItem[8]
            ]);
        }
        return true;
    }

    public function sign(Request $request, Waybill $waybill){
        $waybill->load('items','company');
        $filter = [
            'didox_id' => $waybill->didox_id,
            'signature' => $request->signature
        ];
        $result = DidoxService::signDocument($filter,$waybill->company);
        if($result['data']['status']!='error') {
            $waybill->update(['response_sign' => json_encode($result, JSON_UNESCAPED_UNICODE)]);

            // обновить колво товаров
            if(!empty($waybill->items)){
                Nomenklature::recalculateQuantity($waybill);
            }

            // TODO : нужно установить статус подписи

            return ['status' => true];
        }
        return ['status'=>false,'error'=>$result['data']['message']];

    }
    public function print(Waybill $waybill)
    {
        if(!Company::checkCompany($waybill->company_id)) abort(404);

        $waybill->load(['items','company']);
        $companies         = Company::getCompany(); // where(['user_id'=>Auth::id()])->get();
        $nomenklatures = Nomenklature::all();
        //$packages = Package::all();  // Unit::all();
        $contracts = Contract::myCompany()->orderBy('contract_number','DESC')->get();
        $company = Company::where(['id'=>Company::getCurrentCompanyId()])->with('ikpu')->first(); // одна компания для Select
        $ikpu = !empty($company->ikpu) ? $company->ikpu : null;
        $nds = Nds::where(['status'=>Status::STATUS_ACTIVE])->get();
        $warehouse = CompanyWarehouse::myCompany()->active()->get();
        $product_origin = Product::getOriginList();

        $response = json_decode($waybill->response);
        $signature = !empty($response->signature)?json_decode($response->signature)[0]:null;
        if(!empty($waybill->response_sign) && $waybill->doc_status==DidoxService::STATUS_WAIT_YOUR_SIGNATURE) {
            $responseSign = json_decode($waybill->response_sign);
            $sign = !empty($responseSign->data) ? $responseSign->data->toSign : '';
            $document = $sign;
        }else {
            $document = json_encode($response->document_json, JSON_UNESCAPED_UNICODE);
            $sign = null;
        }
        $owner = $response->document_json->seller;
        $client = $response->document_json->buyer;

        if($waybill->owner==DidoxService::OWNER_TYPE_OUTGOING) {
            $ownerCompany = $waybill->company;
            if(!$clientCompany = Company::where(['inn' => $response->document_json->buyertin])->first()){
                // TODO no-company
            }
        }else{
            $ownerCompany = Company::where(['inn' => $response->document_json->sellertin])->first();
            $clientCompany = $waybill->company;
        }

        $owner->tin = $response->document_json->buyertin;

        $owner->bank_name = !empty($ownerCompany) ? $ownerCompany->bank_name : '';
        $owner->phone = !empty($ownerCompany) ? $ownerCompany->phone : '';

        $client->tin = $response->document_json->sellertin;
        $client->bank_name = !empty($clientCompany) ? $clientCompany->bank_name : '';
        $client->phone = !empty($clientCompany) ? $clientCompany->phone : '';

        $qrcode = QrcodeHelper::create($_SERVER['HTTP_HOST'].'/'.app()->getLocale().'/waybill/'.$waybill->didox_id);
        return view('frontend.profile.modules.waybill.print', compact('waybill', 'companies','nomenklatures','contracts','nds','warehouse','ikpu','product_origin','sign','document','owner','client','qrcode','signature'));

    }


    public function exportPdf(Request $request, Waybill $waybill){
        if(!Company::checkCompany($waybill->company_id)) abort(404);

        $waybill->load(['items','company']);
        $companies         = Company::getCompany(); // where(['user_id'=>Auth::id()])->get();
        $nomenklatures = Nomenklature::all();
        $units = Package::all();  // Unit::all();
        $contracts = Contract::myCompany()->orderBy('contract_number','DESC')->get();
        $company = Company::where(['id'=>Company::getCurrentCompanyId()])->with('ikpu')->first(); // одна компания для Select
        $ikpu = !empty($company->ikpu) ? $company->ikpu : null;
        $nds = Nds::where(['status'=>Status::STATUS_ACTIVE])->get();
        $warehouse = CompanyWarehouse::myCompany()->active()->get();
        $product_origin = Product::getOriginList();

        $response = json_decode($waybill->response);
        $signature = !empty($response->signature)?json_decode($response->signature)[0]:null;
        if(!empty($waybill->response_sign) && $waybill->doc_status==DidoxService::STATUS_WAIT_YOUR_SIGNATURE) {
            $responseSign = json_decode($waybill->response_sign);
            $sign = !empty($responseSign->data) ? $responseSign->data->toSign : '';
            $document = $sign;
        }else {
            $document = json_encode($response->document_json, JSON_UNESCAPED_UNICODE);
            $sign = null;
        }
        $owner = $response->document_json->seller;
        $client = $response->document_json->buyer;

        if($waybill->owner==DidoxService::OWNER_TYPE_OUTGOING) {
            $ownerCompany = $waybill->company;
            if(!$clientCompany = Company::where(['inn' => $response->document_json->buyertin])->first()){
                // TODO no-company
            }
        }else{
            $ownerCompany = Company::where(['inn' => $response->document_json->sellertin])->first();
            $clientCompany = $waybill->company;
        }

        $owner->tin = $response->document_json->buyertin;

        $owner->bank_name = !empty($ownerCompany) ? $ownerCompany->bank_name : '';
        $owner->phone = !empty($ownerCompany) ? $ownerCompany->phone : '';

        $client->tin = $response->document_json->sellertin;
        $client->bank_name = !empty($clientCompany) ? $clientCompany->bank_name : '';
        $client->phone = !empty($clientCompany) ? $clientCompany->phone : '';

        $qrcode = QrcodeHelper::create($_SERVER['HTTP_HOST'].'/'.app()->getLocale().'/waybill/'.$waybill->didox_id);


        return PdfHelper::create([
            'view'=>'frontend.profile.modules.waybill.print',
            'data' => compact('waybill', 'companies','nomenklatures','units','contracts','nds','warehouse','ikpu','product_origin','sign','document','owner','client','qrcode','signature'),
            'orientation' => 'landscape',
            'filename' => 'product_'.$waybill->product_number.'-'.$waybill->contract_date.'.pdf'
        ]);

    }

    public function download(Waybill $waybill){

        $waybill->load(['items','company']);
        $companies         = Company::getCompany(); // where(['user_id'=>Auth::id()])->get();
        $nomenklatures = Nomenklature::all();
        $units = Package::all();  // Unit::all();
        $contracts = Contract::myCompany()->orderBy('contract_number','DESC')->get();
        $company = Company::where(['id'=>Company::getCurrentCompanyId()])->with('ikpu')->first(); // одна компания для Select
        $ikpu = !empty($company->ikpu) ? $company->ikpu : null;
        $nds = Nds::where(['status'=>Status::STATUS_ACTIVE])->get();
        $warehouse = CompanyWarehouse::myCompany()->active()->get();
        $product_origin = Product::getOriginList();

        $response = json_decode($waybill->response);
        $signature = !empty($response->signature)?json_decode($response->signature)[0]:null;
        if(!empty($waybill->response_sign) && $waybill->doc_status==DidoxService::STATUS_WAIT_YOUR_SIGNATURE) {
            $responseSign = json_decode($waybill->response_sign);
            $sign = !empty($responseSign->data) ? $responseSign->data->toSign : '';
            $document = $sign;
        }else {
            $document = json_encode($response->document_json, JSON_UNESCAPED_UNICODE);
            $sign = null;
        }
        $owner = $response->document_json->seller;
        $client = $response->document_json->buyer;

        if($waybill->owner==DidoxService::OWNER_TYPE_OUTGOING) {
            $ownerCompany = $waybill->company;
            if(!$clientCompany = Company::where(['inn' => $response->document_json->buyertin])->first()){
                // TODO no-company
            }
        }else{
            $ownerCompany = Company::where(['inn' => $response->document_json->sellertin])->first();
            $clientCompany = $waybill->company;
        }

        $owner->tin = $response->document_json->buyertin;

        $owner->bank_name = !empty($ownerCompany) ? $ownerCompany->bank_name : '';
        $owner->phone = !empty($ownerCompany) ? $ownerCompany->phone : '';

        $client->tin = $response->document_json->sellertin;
        $client->bank_name = !empty($clientCompany) ? $clientCompany->bank_name : '';
        $client->phone = !empty($clientCompany) ? $clientCompany->phone : '';

        $qrcode = QrcodeHelper::create($_SERVER['HTTP_HOST'].'/'.app()->getLocale().'/waybill/'.$waybill->didox_id);

        $params = [
            'filename' => 'factura_' . $waybill->number.'-'. date('Y.m.d',strtotime($waybill->date)),
            'data' => compact('waybill', 'companies','nomenklatures','units','contracts','nds','warehouse','ikpu','product_origin','sign','document','owner','client','qrcode','signature'),
            'type'=>'waybill',
            'not_save_sign' => true,
        ];
        $result = FileHelper::createArchieve($waybill,$params);
        if(!$result['status']) {
            Session::flash('error',$result['error']);
            return redirect()->to(request()->headers->get('referer'));
        }
        FileHelper::download($result['file']);



    }


}
