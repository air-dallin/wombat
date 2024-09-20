<?php

namespace App\Http\Controllers\Profile\Modules;

use App\Helpers\ContractDocument;
use App\Helpers\Elog;
use App\Helpers\FileHelper;
use App\Helpers\PdfHelper;
use App\Helpers\QrcodeHelper;
use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Doc;
use App\Models\ActItems;
use App\Models\Company;
use App\Models\CompanyPlan;
use App\Models\Nds;
use App\Models\Nomenklature;
use App\Models\OpenaiChat;
use App\Models\Package;
use App\Models\Plan;
use App\Models\Queue;
use App\Models\Status;
use App\Services\DidoxService;
use App\Services\FacturaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use stdClass;

class DocController extends Controller
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
                'doctype' => DidoxService::DOC_TYPE_DOCUMENT,
                'company_id' => $company_id
            ]);

        }

        $ats = Doc::myCompany()->with(['company'])->where(['owner'=>$owner,'user_id'=>Auth::id()])
            ->notDeletedOnly()
            ->order()
            ->orderBy('date','DESC')
            ->orderBy('number','DESC')
            ->paginate(15);

        return view('frontend.profile.modules.doc.index', compact('acts','owner','company_id'));
    }


    public function draft()
    {
        dd('not worked');

        $company_id = Company::getCurrentCompanyId();
        $owner = DidoxService::OWNER_TYPE_NOT_USE;
        $acts = Doc::myCompany()
            ->with(['company'])
            ->draft()
            ->where(['user_id'=>Auth::id(),'owner'=>DidoxService::OWNER_TYPE_OUTGOING])
            ->order()
            ->orderBy('contract_date','DESC')
            ->orderBy('contract_number','DESC')
            ->paginate(15);

        return view('frontend.profile.modules.doc.index', compact('acts','owner','company_id'));

    }

    public function create(Request $request)
    {

        $maxNumber = Doc::mycompany()->owner(DidoxService::OWNER_TYPE_OUTGOING)->max('number');
        $new_number = !empty($maxNumber) && is_numeric($maxNumber) ? $maxNumber+1 : 1;
        $companies = Company::getCompany();
        $nomenklatures = Nomenklature::all();
        $packages = Package::all();
        $docs = Doc::myCompany()->signed()->orderBy('date','DESC')->get();
        $company = Company::where(['id'=>Company::getCurrentCompanyId()])->with('ikpu')->first(); // одна компания для Select
        $ikpu = !empty($company->ikpu) ? $company->ikpu : null;
        $nds = Nds::where(['status'=>Status::STATUS_ACTIVE])->get();
        /*$warehouse = CompanyWarehouse::myCompany()->active()->get();
        $product_origin = Product::getOriginList();*/

        $contracts = Contract::myCompany()->signed()->orderBy('contract_number','DESC')->get();


        $owner = DidoxService::getOwner($request);
        $owner = DidoxService::getOwnerLabel($owner);

        $selected_plan = null;
        $plans = Plan::all();
        if(!empty($company)){
            $selected_plan = CompanyPlan::where(['company_id'=>$company->id,'document_type'=>DidoxService::DOC_TYPE_DOCUMENT])->select('plan_id')->first();
        }


        return view('frontend.profile.modules.doc.form', compact('companies','nomenklatures','packages','docs','ikpu','nds', 'contracts',/*'warehouse','product_origin',*/'owner','new_number','plans','selected_plan'));

    }

    public function edit(Doc $doc)
    {
        if(!Company::checkCompany($doc->company_id)) abort(404);
        if($doc->owner!=DidoxService::OWNER_TYPE_OUTGOING) return $this->view($doc);

        $doc->with(['items','company']);
        $companies = Company::getCompany(); // where(['user_id'=>Auth::id()])->get();

        $docs = Doc::myCompany()->signed()->orderBy('number','DESC')->get();
        $company = Company::where(['id'=>Company::getCurrentCompanyId()])->with('ikpu')->first(); // одна компания для Select
        /*
        $nomenklatures = Nomenklature::all();
        $packages = Package::all();
        $ikpu = !empty($company->ikpu) ? $company->ikpu : null;
        $nds = Nds::where(['status'=>Status::STATUS_ACTIVE])->get();
        $warehouse = CompanyWarehouse::myCompany()->active()->get();
        $product_origin = Product::getOriginList(); */
        $owner = DidoxService::getOwnerLabel($doc->owner);
        $document = '';
        $sign = '';
        $plans = Plan::all();
        $selected_plan = CompanyPlan::where(['company_id'=>$doc->company_id,'document_type'=>DidoxService::DOC_TYPE_DOCUMENT])->select('plan_id')->first();
        return view('frontend.profile.modules.doc.form', compact('doc','companies','docs',/*,'nomenklatures','packages','ikpu','nds','warehouse','product_origin',*/'owner','document','sign','plans','selected_plan'));

    }

    public function view(Doc $doc)
    {

        if ($doc->owner == DidoxService::OWNER_TYPE_OUTGOING && !Company::checkCompany($doc->company_id)) abort(404);


        $doc->load(['items', 'company', 'chat.chatItems','contract']);

        $companies = Company::where(['user_id' => Auth::id()])->get();

        $response = json_decode($doc->response);

        $signature = false;


        $owner = new stdClass();
        $client = new stdClass();

        $owner->tin = $response->document_json->sellertin;
        $owner->company = $doc->company_name;
        $owner->address = $doc->company_address;

        $client->tin = $response->document_json->buyertin;
        $client->company = $doc->partner_company;
        $client->address = $doc->partner_address;

        $responseSign = json_decode($doc->response_sign);

        if(!empty($response->signature)) {
            $signature =json_decode($response->signature);

            $this->getSignatureInfo($signature, $owner, $client);
        }elseif($doc->doc_status==DidoxService::STATUS_SIGNED) {
            if (!empty($responseSign->data->document->signature)) {
                $signature = json_decode($responseSign->data->document->signature);
                $this->getSignatureInfo($signature, $owner, $client);
            }
        }

        $sign = '';
        if (!empty($doc->response_sign) && /*$doc->doc_status==DidoxService::STATUS_WAIT_YOUR_SIGNATURE &&*/ !empty($responseSign)) {
            $sign = !empty($responseSign->data) ? $responseSign->data->toSign : '';
            $document = $sign;
        } else {
            $document = json_encode($response->document_json, JSON_UNESCAPED_UNICODE);
        }

        $qrcode = QrcodeHelper::create($_SERVER['HTTP_HOST'].'/'.app()->getLocale().'/doc/'.$doc->didox_id);

        if(empty($doc->chat)) {
            $openai = true;
            $params = [
                'template'=> view('frontend.profile.modules.doc.view', compact('doc',  'companies','document','sign','client','owner','signature','openai','qrcode'))->render(),
                'doctype' => 'Doc',
                'document_id' => $doc->id,
                'company_id' => $doc->company_id
            ];
            OpenaiChat::init($params);
            $doc->load('chat.chatItems');
        }

        $chatItems =  $doc->chat->chatItems;

        return view('frontend.profile.modules.doc.view', compact('doc',  'companies','document','sign','client','owner','signature','chatItems','qrcode'));

    }

    public function print(Doc $doc)
    {
        if(!Company::checkCompany($doc->company_id)) abort(404);

        $doc->load('contract');

        $companies = Company::where(['user_id'=>Auth::id()])->get();

        $response = json_decode($doc->response);
        $signature = false;

        $owner = new stdClass();
        $client = new stdClass();

        $owner->tin = $response->document_json->sellertin;
        $owner->company = $doc->company_name;
        $owner->address = $doc->company_address;

        $client->tin = $response->document_json->buyertin;
        $client->company = $doc->partner_company;
        $client->address = $doc->partner_address;


        if(!empty($response->signature)) {
            $signature = json_decode($response->signature);
            $this->getSignatureInfo($signature, $owner, $client);
        }elseif($doc->doc_status==DidoxService::STATUS_SIGNED) {
            $responseSign = json_decode($doc->response_sign);
            if (!empty($responseSign->data->document->signature)) {
                $signature = json_decode($responseSign->data->document->signature);
                $this->getSignatureInfo($signature, $owner, $client);
            }
        }

        $qrcode = QrcodeHelper::create($_SERVER['HTTP_HOST'].'/'.app()->getLocale().'/doc/'.$doc->didox_id);

        return view('frontend.profile.modules.doc.print', compact('doc',  'companies','client','owner','signature','qrcode'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User         $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Doc $doc)
    {
        $params = $request->all();

    /*    $productItems = $params['product_items'];
        $update_products = $params['update_products'];
        unset($params['product_items']);
        unset($params['update_products']);*/
        $doc->update($params);
        //if($update_products) $this->createProductItems($productItems,$doc);

        if($doc->wasChanged() && $company = Company::where(['id'=>$params['company_id']])->first()) {

            $partner = FacturaService::getCompanyInfo($doc->partner_inn);
            $doc = DocDocument::getTemplate($doc,null,$company,$partner);
            $filter = [
                'owner' => DidoxService::OWNER_TYPE_OUTGOING,
                //'status' => '0,1,2,3,4,5,6,8,40,60',
                'doctype' => DidoxService::DOC_TYPE_DOCUMENT,
                'company_id' => $company->id,
                'didox_id' => $doc->didox_id
            ];
            Elog::save('create doc');
            $result = DidoxService::updateDocument($filter,$doc,$company);
            if(!empty($result['data'])) $doc->update(['response' => json_encode($result['data'],JSON_UNESCAPED_UNICODE)]);

            if($request->has('save_plan')) CompanyPlan::updatePlan(DidoxService::DOC_TYPE_DOCUMENT,$params);

        }

        return redirect()->route('frontend.profile.modules.document.index', app()->getLocale());
    }


    public function store(Request $request)
    {

        $params = $request->all();
        $params['user_id'] = Auth::id();

        unset($params['_token']);

        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $params['file'] =  FileHelper::getFileBase64($request->file('file'));
        }

        Queue::create([
            'company_id'=>$params['company_id'],
            'doctype'=>DidoxService::DOC_TYPE_DOCUMENT,
            'params'=>json_encode($params,JSON_UNESCAPED_UNICODE),
            'type' => Queue::TYPE_OUTGOING,
            'status'=>Queue::STATUS_WAIT
        ]);

        return redirect()->route('frontend.profile.modules.document.index', ['owner'=>'outgoing',app()->getLocale()])->with('success', __('main.add_queue'));


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Doc $doc
     * @return \Illuminate\Http\Response
     */
    public function destroy(Doc $doc)
    {

        $company= $doc->company;

        $result = DidoxService::deleteDocument($doc,$company);
        $error='';
        if(!empty($result->data)) {
            if ($result->data) {
                $doc->update(['status' => DidoxService::STATUS_DELETED, 'doc_status' => DidoxService::STATUS_DELETED]);
                //$doc->delete();
                return redirect()->route('frontend.profile.modules.doc.index',['owner'=>'outgoing', app()->getLocale()])->with('success',__('main.success'));
            }else{
                $error = !empty($result->data->message) ? $result->data->message : 'service error';
            }
        }else{
            $error = 'service error';
        }

        return redirect()->route('frontend.profile.modules.doc.index', ['owner'=>'outgoing', app()->getLocale()])->with('error',$error);
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

    public function checkStatus(Request $request,Doc $doc)
    {
        if(!Company::checkCompany($doc->company_id)) abort(404);
        $referer =  $request->headers->get('referer');

        $doc->load('company');
        $company = $doc->company;
        $response = DidoxService::getDocument($doc,$company);
        if(empty($response)){
            $company->update(['token_expire' => 0]);
            return redirect()->to($referer)->with('error', __('main.try_again'));
        }

        if($response->data->document->status!=$doc->status || $response->data->document->doc_status!=$doc->doc_status || empty($doc->response_sign)){
            $info = __('main.changed');
            /* if(!empty($response->data->toSign)) {
                $sign =  json_encode(['data'=>['toSign' =>$response->data->toSign]],JSON_UNESCAPED_UNICODE);
            }else{
                $sign = null;
            } */
            $sign = json_encode($response,JSON_UNESCAPED_UNICODE);


            $doc->update(['status'=>$response->data->document->status,'doc_status'=>$response->data->document->doc_status,'response_sign'=>$sign,'checked_at' => date('Y-m-d H:i:s',time())]);
        }else{
            $info = __('main.no_changes');
        }
        return redirect()->to($referer)->with('success', $info);
    }


    public function getCompanyInfo(Request $request)
    {

        $error = [];
        if (!empty($request->contract_id)) {

            if ($doc = Doc::where(['id' => $request->contract_id])->first()) {

                $response = json_decode($doc->response);
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
                $error = __('main.act_not_found');
            }

        }
        return ['status'=>false,'error'=>$error];
    }



    public function exportPdf(Request $request, Doc $doc){
        if(!Company::checkCompany($doc->company_id)) abort(404);

        /// $companies = Company::where(['user_id'=>Auth::id()])->get();
        $doc->load('company');

        /*
         $qrcode = null;
          ob_start();
       QRCode::url($_SERVER['HTTP_HOST'].'/'.app()->getLocale().'/acts/'.$doc->contract_number)->svg();
       $qrcode = ob_get_contents();
       ob_end_clean(); */

        /*PDF::setOption(['dpi' => 300, 'defaultFont' => 'arial', 'default_paper_orientation' => 'landscape']);
        $pdf = PDF::loadView('frontend.profile.modules.doc.print', compact('doc', 'companies','qrcode'));
        $pdf->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('contract_'.$doc->contract_number.'-'.$doc->contract_date.'.pdf'); */

        return PdfHelper::create([
            'view'=>'frontend.profile.modules.doc.print',
            'data' => compact('doc'),
            'orientation' => 'landscape',
            'filename' => 'doc_'.$doc->number.'-'.$doc->date.'.pdf'
        ]);

    }

    public function download(Doc $doc){
        if(!Company::checkCompany($doc->company_id)) abort(404);

        $doc->load('company');

        $response = json_decode($doc->response);
        $signature = false;

        $owner = new stdClass();
        $client = new stdClass();

        $owner->tin = $response->document_json->sellertin;
        $client->tin = $response->document_json->buyertin;

        if(!empty($response->signature)) {
            $signature = json_decode($response->signature);
            $this->getSignatureInfo($signature, $owner, $client);
        }elseif($doc->doc_status==DidoxService::STATUS_SIGNED) {
            $responseSign = json_decode($doc->response_sign);
            if (!empty($responseSign->data->document->signature)) {
                $signature = json_decode($responseSign->data->document->signature);
                $this->getSignatureInfo($signature, $owner, $client);
            }
        }

        $params = [
            'filename' => 'doc_'.$doc->number.'-'. date('Y.m.d',strtotime($doc->date)),
            'data' => compact('doc','owner','client','signature'),
            'type'=>'doc'
        ];


        $result = FileHelper::createArchieve($doc,$params);
        if(!$result['status']) {
            Session::flash('error',$result['error']);
            return redirect()->to(request()->headers->get('referer'));
        }
        FileHelper::download($result['file']);

    }


}
