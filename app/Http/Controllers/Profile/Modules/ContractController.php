<?php

namespace App\Http\Controllers\Profile\Modules;

use App\Helpers\ContractDocument;
use App\Helpers\DocumentHelper;
use App\Helpers\Elog;
use App\Helpers\FileHelper;
use App\Helpers\PdfHelper;
use App\Helpers\QrcodeHelper;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyPlan;
use App\Models\Contract;
use App\Models\ContractItems;
use App\Models\Nds;
use App\Models\Nomenklature;
use App\Models\OpenaiChat;
use App\Models\Package;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Queue;
use App\Models\Status;
use App\Services\DidoxService;
use App\Services\FacturaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ContractController extends Controller
{

    public function index(Request $request)
    {
        dd('not worked');

        $owner = DidoxService::getOwner($request);

        $update = $request->has('update') ? $request->update : false;

        $company_id = Company::getCurrentCompanyId();

        if($update /*&& $owner == DidoxService::OWNER_TYPE_INCOMING*/ && $company_id!=0) {
            set_time_limit(0);

           $result = DidoxService::getDocuments([
               'owner'=> $owner, //'owner'=>DidoxService::OWNER_TYPE_INCOMING,
                'status'=>'0,1,2,3,4,5,6,8,40,60',
               'doctype' => DidoxService::DOC_TYPE_CONTRACT,
                'company_id' => $company_id
            ]);

        }

        $contracts = Contract::myCompany()->with(['company'])->where(['owner'=>$owner,'user_id'=>Auth::id()])
            ->notDeletedOnly()
            ->order()
            ->orderBy('contract_date','DESC')
            ->orderBy('contract_number','DESC')
        ->paginate(15);

        return view('frontend.profile.modules.contract.index', compact('contracts','owner','company_id'));
    }

    public function contractFactura(Request $request)
    {
        dd('not worked');

        $owner = DidoxService::getOwner($request);

        $update = $request->has('update') ? $request->update : false;

        $company_id = Company::getCurrentCompanyId();

        if($update /*&& $owner == DidoxService::OWNER_TYPE_INCOMING*/ && $company_id!=0) {
            set_time_limit(0);

           $result = DidoxService::getDocuments([
               'owner'=> $owner, //'owner'=>DidoxService::OWNER_TYPE_INCOMING,
                'status'=>'0,1,2,3,4,5,6,8,40,60',
               'doctype' => DidoxService::DOC_TYPE_CONTRACT,
                'company_id' => $company_id
            ]);

        }

        $products = Product::with(['company','contract'])
            ->where(['owner'=>$owner,'user_id'=>Auth::id(),'company_id'=>$company_id])
            ->where('status','!=',9)
            ->order()
            ->paginate(15);


        return view('frontend.profile.modules.contract.factura', compact('products','owner','company_id'));
    }

    public function draft()
    {
        dd('not worked');

        $company_id = Company::getCurrentCompanyId();
        $owner = DidoxService::OWNER_TYPE_NOT_USE;
        $contracts = Contract::myCompany()
            ->with(['company'])
            ->draft()
            ->where(['user_id'=>Auth::id(),'owner'=>DidoxService::OWNER_TYPE_OUTGOING])
            ->order()
            ->orderBy('contract_date','DESC')
            ->orderBy('contract_number','DESC')
            ->paginate(15);

        return view('frontend.profile.modules.contract.index', compact('contracts','owner','company_id'));

    }

    public function create(Request $request)
    {

        $maxNumber = Contract::mycompany()->owner(DidoxService::OWNER_TYPE_OUTGOING)->max('contract_number');
        $new_number = !empty($maxNumber) && is_numeric($maxNumber) ? $maxNumber+1 : 1;
        $companies = Company::getCompany();
        $nomenklatures = Nomenklature::all();
        $packages = Package::all();
        $contracts = Contract::myCompany()->signed()->orderBy('contract_number','DESC')->get();
        $company = Company::where(['id'=>Company::getCurrentCompanyId()])->with('ikpu')->first(); // одна компания для Select
        $ikpu = !empty($company->ikpu) ? $company->ikpu : null;
        $nds = Nds::where(['status'=>Status::STATUS_ACTIVE])->get();
        /*$warehouse = CompanyWarehouse::myCompany()->active()->get();
        $product_origin = Product::getOriginList();*/

        $owner = DidoxService::getOwner($request);
        $owner = DidoxService::getOwnerLabel($owner);

        $selected_plan = null;
        $plans = Plan::all();
        if(!empty($company)){
            $selected_plan = CompanyPlan::where(['company_id'=>$company->id,'document_type'=>DidoxService::DOC_TYPE_CONTRACT])->select('plan_id')->first();
        }


        return view('frontend.profile.modules.contract.form', compact('companies','nomenklatures','packages','contracts','ikpu','nds',/*'warehouse','product_origin',*/'owner','new_number','plans','selected_plan'));

    }

    public function edit(Contract $contract)
    {
        if(!Company::checkCompany($contract->company_id)) abort(404);
        if($contract->owner!=DidoxService::OWNER_TYPE_OUTGOING) return $this->view($contract);

        $contract->with(['items','company']);
        $companies = Company::getCompany(); // where(['user_id'=>Auth::id()])->get();
        $nomenklatures = Nomenklature::all();
        $packages = Package::all();
        $contracts = Contract::myCompany()->signed()->orderBy('contract_number','DESC')->get();
        $company = Company::where(['id'=>Company::getCurrentCompanyId()])->with('ikpu')->first(); // одна компания для Select
        $ikpu = !empty($company->ikpu) ? $company->ikpu : null;
        $nds = Nds::where(['status'=>Status::STATUS_ACTIVE])->get();
        /*$warehouse = CompanyWarehouse::myCompany()->active()->get();
        $product_origin = Product::getOriginList(); */
        $owner = DidoxService::getOwnerLabel($contract->owner);
        $document = '';
        $sign = '';
        $plans = Plan::all();
        $selected_plan = CompanyPlan::where(['company_id'=>$contract->company_id,'document_type'=>DidoxService::DOC_TYPE_CONTRACT])->select('plan_id')->first();
        return view('frontend.profile.modules.contract.form', compact('contract','companies','nomenklatures','packages','contracts','ikpu','nds',/*'warehouse','product_origin',*/'owner','document','sign','plans','selected_plan'));

    }

    public function view(Contract $contract)
    {

        if( $contract->owner == DidoxService::OWNER_TYPE_OUTGOING && !Company::checkCompany($contract->company_id)) abort(404);

        $contract->load(['items','company','chat.chatItems']);

        $companies     = Company::where(['user_id'=>Auth::id()])->get();

        $response = json_decode($contract->response);
       /* $sign = '';
        if(!empty($contract->response_sign) && $contract->doc_status==DidoxService::STATUS_WAIT_YOUR_SIGNATURE) {
            $responseSign = json_decode($contract->response_sign);
            $sign = !empty($responseSign->data) ? $responseSign->data->toSign : '';
            $document = $sign;
        }else {
            $document = json_encode($response->document_json, JSON_UNESCAPED_UNICODE);
        } */

        $signature = false;
        $owner = $response->document_json->owner;
        $client = $response->document_json->clients[0];

        if($contract->owner==DidoxService::OWNER_TYPE_OUTGOING) {
            $ownerCompany = $contract->company;
            if(!$clientCompany = Company::where(['inn' => $client->tin])->first()){
                // TODO no-company
            }
        }else{
            $ownerCompany = Company::where(['inn' => $owner->tin])->first();
            $clientCompany = $contract->company;
        }
        $owner->tin = $response->document_json->sellertin;
        $client->tin = $response->document_json->clients[0]->tin;
        $owner->bank_name = !empty($ownerCompany) ? $ownerCompany->bank_name : '';
        $owner->phone = !empty($ownerCompany) ? $ownerCompany->phone : '';
        $client->bank_name = !empty($clientCompany) ? $clientCompany->bank_name : '';
        $client->phone = !empty($clientCompany) ? $clientCompany->phone : '';

        $responseSign = json_decode($contract->response_sign);

        $sign = '';
        if(!empty($contract->response_sign) && /*$contract->doc_status==DidoxService::STATUS_WAIT_YOUR_SIGNATURE &&*/ !empty($responseSign)) {
            $sign = !empty($responseSign->data) ? $responseSign->data->toSign : '';
            $document = $sign;

           // dd($sign,$responseSign);

        }else {
            $document = json_encode($response->document_json, JSON_UNESCAPED_UNICODE);
        }

        if($contract->doc_status==DidoxService::STATUS_SIGNED) {
            if (!empty($responseSign->data->document->signature)) {
                $signature = json_decode($responseSign->data->document->signature);
                $this->getSignatureInfo($signature, $owner, $client);
            }
        }


        $qrcode = QrcodeHelper::create($_SERVER['HTTP_HOST'].'/'.app()->getLocale().'/contract/'.$contract->didox_id);

        if(empty($contract->chat)) {
            $openai = true;

            $text = DocumentHelper::getDocumentText($contract->response);

            $params = [
                'template'=>$contract->contract_text . ' ' . $text .' ' . view('frontend.profile.modules.contract.view', compact('contract',  'companies','document','sign','client','owner','signature','openai','qrcode'))->render(),
                'doctype' => 'Contract',
                'document_id' => $contract->id,
                'company_id' => $contract->company_id
            ];
            OpenaiChat::init($params);
            $contract->load('chat.chatItems');
        }

        $chatItems =  $contract->chat->chatItems;

        return view('frontend.profile.modules.contract.view', compact('contract',  'companies','document','sign','client','owner','signature','chatItems','qrcode'));

    }

    public function print(Contract $contract)
    {
        if(!Company::checkCompany($contract->company_id)) abort(404);

        $companies     = Company::where(['user_id'=>Auth::id()])->get();

        $response = json_decode($contract->response);
        $signature = false;
        $owner = $response->document_json->owner;
        $client = $response->document_json->clients[0];

        if($contract->owner==DidoxService::OWNER_TYPE_OUTGOING) {
            $ownerCompany = $contract->company;
            if(!$clientCompany = Company::where(['inn' => $client->tin])->first()){
                // TODO no-company
            }
        }else{
            $ownerCompany = Company::where(['inn' => $owner->tin])->first();
            $clientCompany = $contract->company;
        }
        $owner->tin = $response->document_json->sellertin;
        $client->tin = $response->document_json->clients[0]->tin;
        $owner->bank_name = !empty($ownerCompany) ? $ownerCompany->bank_name : '';
        $owner->phone = !empty($ownerCompany) ? $ownerCompany->phone : '';
        $client->bank_name = !empty($clientCompany) ? $clientCompany->bank_name : '';
        $client->phone = !empty($clientCompany) ? $clientCompany->phone : '';


        $responseSign = json_decode($contract->response_sign);

/*        $sign = '';
        if(!empty($contract->response_sign) && $contract->doc_status==DidoxService::STATUS_WAIT_YOUR_SIGNATURE && !empty($responseSign)) {
            $sign = !empty($responseSign->data) ? $responseSign->data->toSign : '';
            $document = $sign;
        }else {
            $document = json_encode($response->document_json, JSON_UNESCAPED_UNICODE);
        }*/

        if($contract->doc_status==DidoxService::STATUS_SIGNED) {

            if (!empty($responseSign->data->document->signature)) {
                $signature = json_decode($responseSign->data->document->signature);
                $this->getSignatureInfo($signature, $owner, $client);
            }
        }

        $qrcode = QrcodeHelper::create($_SERVER['HTTP_HOST'].'/'.app()->getLocale().'/contract/'.$contract->didox_id);

        return view('frontend.profile.modules.contract.print', compact('contract',  'companies','client','owner','signature','qrcode'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User         $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contract $contract)
    {
        $params = $request->all();

        $productItems = $params['product_items'];
        $update_products = $params['update_products'];
        unset($params['product_items']);
        unset($params['update_products']);
        $contract->update($params);
        if($update_products) $this->createProductItems($productItems,$contract);

        if($contract->wasChanged() && $company = Company::where(['id'=>$params['company_id']])->first()) {

            // 1. получить данные о партнере
            // 2. обновить
            $didox_id = $contract->didox_id;

            $partner = FacturaService::getCompanyInfo($contract->partner_inn);
            $contract = ContractDocument::getTemplate($contract,$productItems,$company,$partner);
            $filter = [
                'owner' => DidoxService::OWNER_TYPE_OUTGOING,
                //'status' => '0,1,2,3,4,5,6,8,40,60',
                'doctype' => DidoxService::DOC_TYPE_CONTRACT,
                'company_id' => $company->id,
                'didox_id' => $didox_id
            ];
            Elog::save('create contract');
            $result = DidoxService::updateDocument($filter,$contract,$company);
            if(!empty($result['data'])) $contract->update(['response' => json_encode($result['data'],JSON_UNESCAPED_UNICODE)]);

            if($request->has('save_plan')) CompanyPlan::updatePlan(DidoxService::DOC_TYPE_CONTRACT,$params);

        }

        return redirect()->route('frontend.profile.modules.document.index', app()->getLocale());
    }

    private function createProductItems(&$productItems,&$contract){
        ContractItems::where(['company_id'=>$contract->company_id,'contarct_id'=>$contract->id])->delete();
        foreach($productItems as $productItem) {
            $productItem = explode('|',$productItem);
            ContractItems::create([
                'company_id' => $contract->company_id,
                'contract_id' => $contract->id,
                'ikpu_id' => $productItem[0],
                'title' => $productItem[1],
                'barcode' => $productItem[2],
                'unit_id' => $productItem[3],
                'quantity' => $productItem[4],
                'amount' => $productItem[5],
                'nds_id' => $productItem[6],
            ]);
        }
        return true;
    }

    public function store(Request $request)
    {
        /*$validator = Validator::make($request->all(),
            [
                'contract_number'  => 'required|string',
                'contract_date' => 'required',
                'contragent'   => 'required|string',
                'contragent_company' => 'required',
                'contragent_bank_code' => 'required',
                'company_id' => 'required',
                //'amount' => 'required',
                'contract_text' => 'required|string',
            ]
        );*/

        $params = $request->all();
        $params['user_id'] = Auth::id();

       /* if ($validator->fails()) {
            return redirect()->route('frontend.profile.modules.contract.create', app()->getLocale())
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors());
        }*/

        unset($params['_token']);

        CompanyPlan::updatePlan(DidoxService::DOC_TYPE_CONTRACT,$params);

        Queue::create([
            'company_id'=>$params['company_id'],
            'doctype'=>DidoxService::DOC_TYPE_CONTRACT,
            'params'=>json_encode($params,JSON_UNESCAPED_UNICODE),
            'type' => Queue::TYPE_OUTGOING,
            'status'=>Queue::STATUS_WAIT
        ]);

        $company = Company::where(['id'=>$params['company_id']])->first();
        if(mb_strlen($company->director<3))   $company->director = $params['company_director'];
        if(mb_strlen($company->accountant<3)) $company->accountant = $params['company_accountant'];
        $company->save();

        /* if($company = Company::where(['id'=>$params['company_id']])->first()){
            // 1. получить данные о партнере
            // 2. отправить контракт
            // 3. получить все
         новые контракты для отображения в системе
            $contract = new Contract($params);
            $partner = FacturaService::getCompanyInfo($contract->contragent);
            $contract = ContractDocument::getTemplate($contract,$company,$partner);
            $filter = [
                'owner' => DidoxService::OWNER_TYPE_OUTGOING,
                'status' => '0,1,2,3,4,5,6,8,40,60',
                'doctype' => DidoxService::DOC_TYPE_CONTRACT,
                'company_id' => $company->id
            ];
            Elog::save('create contract');
            DidoxService::createDocument($filter,$contract,$company);
            DidoxService::getDocuments($filter);
        } */

        return redirect()->route('frontend.profile.modules.document.index', ['owner'=>'outgoing',app()->getLocale()])->with('success', __('main.contract_queue'));


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Contract $contract
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contract $contract)
    {

        $company= $contract->company;

        $result = DidoxService::deleteDocument($contract,$company);
        $error='';
        if(!empty($result->data)) {
            if ($result->data) {
                $contract->update(['status' => DidoxService::STATUS_DELETED, 'doc_status' => DidoxService::STATUS_DELETED]);
                //$contract->delete();
                return redirect()->route('frontend.profile.modules.document.index',['owner'=>'outgoing', app()->getLocale()])->with('success',__('main.success'));
            }else{
                $error = !empty($result->data->message) ? $result->data->message : 'service error';
            }
        }else{
            $error = 'service error';
        }

        return redirect()->route('frontend.profile.modules.document.index', ['owner'=>'outgoing', app()->getLocale()])->with('error',$error);
    }
    private function getSignatureInfo(&$signature,&$owner,&$client){
        foreach ($signature as $target) {
            //dd($target);
            if ($target->taxId == $owner->tin) {
                $owner->serial = $target->serial;
                //$owner->tin = $target->taxId;
                $owner->company = $target->company;
                $owner->fio = $target->lastName . ' ' . $target->firstName;
                $owner->signing_time = $target->signingTime;
                continue;
            }
            if ($target->taxId == $client->tin) {
                $client->serial = $target->serial;
                //$client->tin = $target->taxId;
                $client->company = $target->company;
                $client->fio = $target->lastName . ' ' . $target->firstName;
                $client->signing_time = $target->signingTime;
                continue;
            }

        }
    }

    public function checkStatus(Request $request,Contract $contract)
    {
        if(!Company::checkCompany($contract->company_id)) abort(404);
        $referer =  $request->headers->get('referer');

        $contract->load('company');
        $company = $contract->company;
        $response = DidoxService::getDocument($contract,$company);
        if(empty($response)){
            $company->update(['token_expire' => 0]);
            return redirect()->to($referer)->with('error', __('main.try_again'));
        }

        //dd($response);
        if($response->data->document->status!=$contract->status || $response->data->document->doc_status!=$contract->doc_status || empty($contract->response_sign)){
            $info = __('main.changed');
            /* if(!empty($response->data->toSign)) {
                $sign =  json_encode(['data'=>['toSign' =>$response->data->toSign]],JSON_UNESCAPED_UNICODE);
            }else{
                $sign = null;
            } */
            $sign = json_encode($response,JSON_UNESCAPED_UNICODE);


            $contract->update(['status'=>$response->data->document->status,'doc_status'=>$response->data->document->doc_status,'response_sign'=>$sign,'checked_at' => date('Y-m-d H:i:s',time())]);
        }else{
            $info = __('main.no_changes');
        }
        return redirect()->to($referer)->with('success', $info);
    }

    public function getContracts(Request $request){

        if(!empty($request->company_id)){

            if($contracts = Contract::where(['company_id'=>$request->company_id])->get()) {
                if(count($contracts)) {
                    $_contracts = [];
                    foreach ($contracts as $contract) {
                        $_contracts[] = "<option value='{$contract->id}'>" . $contract->contract_number . ' ('. $contract->contract_date .')' ."</option>";
                    }
                    return ['status' => true, 'data' => $_contracts];
                }
            }

        }
        return ['status'=>false,'error'=>__('main.contracts_not_found')];

    }

    public function getCompanyInfo(Request $request)
    {

        $error = [];
        if (!empty($request->contract_id)) {

            if ($contract = Contract::where(['id' => $request->contract_id])->first()) {

                $response = json_decode($contract->response);
                $companyInn = $response->document_json->owner->tin;

                if(!Session::has('companyInn_'.$companyInn)){
                    $companyInfo = FacturaService::getCompanyInfo($companyInn);
                    if(!isset($companyInfo['CompanyInn'])){
                        return ['status'=>false,'error'=>__('main.inn_user_not_found')];
                    }
                    Session::put('companyInn_'.$companyInn,$companyInfo);
                }else{
                    $companyInfo = Session::get('companyInn_'.$companyInn);
                }

                $bankInfo = FacturaService::getPrimaryAccount($companyInfo);

                $data['name'] = $companyInfo['CompanyName'];
                $data['address'] = $companyInfo['CompanyAddress'];
                $data['inn'] = $companyInfo['CompanyInn'];
                $data['oked'] = $companyInfo['Oked'];
                $data['nds_code'] = $companyInfo['VatCode'];
                $data['bank_code'] = $bankInfo['code'];
                $data['bank_name'] = $bankInfo['name'];
                $data['mfo'] = $bankInfo['mfo'];

                $data['director'] = $companyInfo['DirectorName'];
                $data['accountant'] = $companyInfo['Accountant'];

                return ['status' => true, 'data' => $data];

            }else{
                $error = __('main.contract_not_found');
            }

        }
        return ['status'=>false,'error'=>$error];
    }



    public function exportPdf(Request $request, Contract $contract){
        if(!Company::checkCompany($contract->company_id)) abort(404);

        /// $companies = Company::where(['user_id'=>Auth::id()])->get();
        $contract->load('company');

         /*
          $qrcode = null;
           ob_start();
        QRCode::url($_SERVER['HTTP_HOST'].'/'.app()->getLocale().'/contracts/'.$contract->contract_number)->svg();
        $qrcode = ob_get_contents();
        ob_end_clean(); */

        /*PDF::setOption(['dpi' => 300, 'defaultFont' => 'arial', 'default_paper_orientation' => 'landscape']);
        $pdf = PDF::loadView('frontend.profile.modules.contract.print', compact('contract', 'companies','qrcode'));
        $pdf->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('contract_'.$contract->contract_number.'-'.$contract->contract_date.'.pdf'); */

        return PdfHelper::create([
            'view'=>'frontend.profile.modules.contract.print',
            'data' => compact('contract'),
            'orientation' => 'landscape',
            'filename' => 'contract_'.$contract->contract_number.'-'.$contract->contract_date.'.pdf'
        ]);

    }

    public function download(Contract $contract){
        if(!Company::checkCompany($contract->company_id)) abort(404);

        $contract->load('company');

        $response = json_decode($contract->response);
        $signature = false;
        $owner = $response->document_json->owner;
        $client = $response->document_json->clients[0];

        if($contract->owner==DidoxService::OWNER_TYPE_OUTGOING) {
            $ownerCompany = $contract->company;
            if(!$clientCompany = Company::where(['inn' => $client->tin])->first()){
                // TODO no-company
            }
        }else{
            $ownerCompany = Company::where(['inn' => $owner->tin])->first();
            $clientCompany = $contract->company;
        }
        $owner->tin = $response->document_json->sellertin;
        $client->tin = $response->document_json->clients[0]->tin;
        $owner->bank_name = !empty($ownerCompany) ? $ownerCompany->bank_name : '';
        $owner->phone = !empty($ownerCompany) ? $ownerCompany->phone : '';
        $client->bank_name = !empty($clientCompany) ? $clientCompany->bank_name : '';
        $client->phone = !empty($clientCompany) ? $clientCompany->phone : '';


        $responseSign = json_decode($contract->response_sign);

        if($contract->doc_status==DidoxService::STATUS_SIGNED) {

            if (!empty($responseSign->data->document->signature)) {
                $signature = json_decode($responseSign->data->document->signature);
                $this->getSignatureInfo($signature, $owner, $client);
            }
        }


        $params = [
            'filename' => 'contract_'.$contract->contract_number.'-'. date('Y.m.d',strtotime($contract->contract_date)),
            'data' => compact('contract','owner','client','signature'),
            'type'=>'contract'
        ];


        $result = FileHelper::createArchieve($contract,$params);
        if(!$result['status']) {
            Session::flash('error',$result['error']);
            return redirect()->to(request()->headers->get('referer'));
        }
        FileHelper::download($result['file']);

    }


}
