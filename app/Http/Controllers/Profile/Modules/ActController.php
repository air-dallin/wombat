<?php

namespace App\Http\Controllers\Profile\Modules;

use App\Helpers\ContractDocument;
use App\Helpers\Elog;
use App\Helpers\FileHelper;
use App\Helpers\PdfHelper;
use App\Helpers\QrcodeHelper;
use App\Http\Controllers\Controller;
use App\Models\Act;
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

class ActController extends Controller
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
                'doctype' => DidoxService::DOC_TYPE_ACT,
                'company_id' => $company_id
            ]);

        }

        $ats = Act::myCompany()->with(['company'])->where(['owner'=>$owner,'user_id'=>Auth::id()])
            ->notDeletedOnly()
            ->order()
            ->orderBy('date','DESC')
            ->orderBy('number','DESC')
            ->paginate(15);

        return view('frontend.profile.modules.act.index', compact('acts','owner','company_id'));
    }


    public function draft()
    {
        dd('not worked');

        $company_id = Company::getCurrentCompanyId();
        $owner = DidoxService::OWNER_TYPE_NOT_USE;
        $acts = Act::myCompany()
            ->with(['company'])
            ->draft()
            ->where(['user_id'=>Auth::id(),'owner'=>DidoxService::OWNER_TYPE_OUTGOING])
            ->order()
            ->orderBy('contract_date','DESC')
            ->orderBy('contract_number','DESC')
            ->paginate(15);

        return view('frontend.profile.modules.act.index', compact('acts','owner','company_id'));

    }

    public function create(Request $request)
    {

        $maxNumber = Act::mycompany()->owner(DidoxService::OWNER_TYPE_OUTGOING)->max('number');
        $new_number = !empty($maxNumber) && is_numeric($maxNumber) ? $maxNumber+1 : 1;
        $companies = Company::getCompany();
        $nomenklatures = Nomenklature::all();
        $packages = Package::all();
        $acts = Act::myCompany()->signed()->orderBy('date','DESC')->get();
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
            $selected_plan = CompanyPlan::where(['company_id'=>$company->id,'document_type'=>DidoxService::DOC_TYPE_ACT])->select('plan_id')->first();
        }

        return view('frontend.profile.modules.act.form', compact('companies','nomenklatures','packages','acts','ikpu','nds',/*'warehouse','product_origin',*/'owner','new_number','plans','selected_plan'));

    }

    public function edit(Act $act)
    {
        if(!Company::checkCompany($act->company_id)) abort(404);
        if($act->owner!=DidoxService::OWNER_TYPE_OUTGOING) return $this->view($act);

        $act->with(['items','company']);
        $companies = Company::getCompany(); // where(['user_id'=>Auth::id()])->get();
        $nomenklatures = Nomenklature::all();
        $packages = Package::all();
        $acts = Act::myCompany()->signed()->orderBy('number','DESC')->get();
        $company = Company::where(['id'=>Company::getCurrentCompanyId()])->with('ikpu')->first(); // одна компания для Select
        $ikpu = !empty($company->ikpu) ? $company->ikpu : null;
        $nds = Nds::where(['status'=>Status::STATUS_ACTIVE])->get();
        /*$warehouse = CompanyWarehouse::myCompany()->active()->get();
        $product_origin = Product::getOriginList(); */
        $owner = DidoxService::getOwnerLabel($act->owner);
        $document = '';
        $sign = '';
        $plans = Plan::all();
        $selected_plan = CompanyPlan::where(['company_id'=>$act->company_id,'document_type'=>DidoxService::DOC_TYPE_ACT])->select('plan_id')->first();
        return view('frontend.profile.modules.act.form', compact('act','companies','nomenklatures','packages','acts','ikpu','nds',/*'warehouse','product_origin',*/'owner','document','sign','plans','selected_plan'));

    }

    public function view(Act $act)
    {

        if ($act->owner == DidoxService::OWNER_TYPE_OUTGOING && !Company::checkCompany($act->company_id)) abort(404);


        $act->load(['items', 'company', 'chat.chatItems','contract']);

        $companies = Company::where(['user_id' => Auth::id()])->get();

        $response = json_decode($act->response);

        $signature = false;


        $owner = new stdClass();
        $client = new stdClass();

        $owner->tin = $response->document_json->sellertin;
        $owner->company = $act->company_name;
        $client->tin = $response->document_json->buyertin;
        $client->company = $act->partner_company;
        $responseSign = json_decode($act->response_sign);

        if(!empty($response->signature)) {
            $signature =json_decode($response->signature);

            $this->getSignatureInfo($signature, $owner, $client);
        }elseif($act->doc_status==DidoxService::STATUS_SIGNED) {
            if (!empty($responseSign->data->document->signature)) {
                $signature = json_decode($responseSign->data->document->signature);
                $this->getSignatureInfo($signature, $owner, $client);
            }
        }

        $sign = '';
        if (!empty($act->response_sign) && /*$act->doc_status==DidoxService::STATUS_WAIT_YOUR_SIGNATURE &&*/ !empty($responseSign)) {
            $sign = !empty($responseSign->data) ? $responseSign->data->toSign : '';
            $document = $sign;
        } else {
            $document = json_encode($response->document_json, JSON_UNESCAPED_UNICODE);
        }

        $qrcode = QrcodeHelper::create($_SERVER['HTTP_HOST'].'/'.app()->getLocale().'/act/'.$act->didox_id);

        if(empty($act->chat)) {
            $openai = true;
            $params = [
                'template'=>$act->act_text . ' ' . view('frontend.profile.modules.act.view', compact('act',  'companies','document','sign','client','owner','signature','openai','qrcode'))->render(),
                'doctype' => 'Act',
                'document_id' => $act->id,
                'company_id' => $act->company_id
            ];
            OpenaiChat::init($params);
            $act->load('chat.chatItems');
        }

        $chatItems =  $act->chat->chatItems;

        return view('frontend.profile.modules.act.view', compact('act',  'companies','document','sign','client','owner','signature','chatItems','qrcode'));

    }

    public function print(Act $act)
    {
        if(!Company::checkCompany($act->company_id)) abort(404);

        $act->load('contract');

        $companies     = Company::where(['user_id'=>Auth::id()])->get();

        $response = json_decode($act->response);
        $signature = false;

        $owner = new stdClass();
        $client = new stdClass();

        $owner->tin = $response->document_json->sellertin;
        $client->tin = $response->document_json->buyertin;

        if(!empty($response->signature)) {
            $signature = json_decode($response->signature);
            $this->getSignatureInfo($signature, $owner, $client);
        }elseif($act->doc_status==DidoxService::STATUS_SIGNED) {
            $responseSign = json_decode($act->response_sign);
            if (!empty($responseSign->data->document->signature)) {
                $signature = json_decode($responseSign->data->document->signature);
                $this->getSignatureInfo($signature, $owner, $client);
            }
        }

        $qrcode = QrcodeHelper::create($_SERVER['HTTP_HOST'].'/'.app()->getLocale().'/act/'.$act->didox_id);

        return view('frontend.profile.modules.act.print', compact('act',  'companies','client','owner','signature','qrcode'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User         $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Act $act)
    {
        $params = $request->all();

        $productItems = $params['product_items'];
        $update_products = $params['update_products'];
        unset($params['product_items']);
        unset($params['update_products']);
        $act->update($params);
        if($update_products) $this->createProductItems($productItems,$act);

        if($act->wasChanged() && $company = Company::where(['id'=>$params['company_id']])->first()) {

            $partner = FacturaService::getCompanyInfo($act->partner_inn);
            $act = ActDocument::getTemplate($act,$productItems,$company,$partner);
            $filter = [
                'owner' => DidoxService::OWNER_TYPE_OUTGOING,
                //'status' => '0,1,2,3,4,5,6,8,40,60',
                'doctype' => DidoxService::DOC_TYPE_ACT,
                'company_id' => $company->id,
                'didox_id' => $act->didox_id
            ];
            Elog::save('create act');
            $result = DidoxService::updateDocument($filter,$act,$company);
            if(!empty($result['data'])) $act->update(['response' => json_encode($result['data'],JSON_UNESCAPED_UNICODE)]);

            if($request->has('save_plan')) CompanyPlan::updatePlan(DidoxService::DOC_TYPE_ACT,$params);

        }

        return redirect()->route('frontend.profile.modules.act.index', app()->getLocale());
    }

    private function createProductItems(&$productItems,&$act){
        ActItems::where(['company_id'=>$act->company_id,'act_id'=>$act->id])->delete();
        foreach($productItems as $productItem) {
            $productItem = explode('|',$productItem);
            ActItems::create([
                'company_id' => $act->company_id,
                'act_id' => $act->id,
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

        $params = $request->all();
        $params['user_id'] = Auth::id();

        unset($params['_token']);

        CompanyPlan::updatePlan(DidoxService::DOC_TYPE_ACT,$params);

        Queue::create([
            'company_id'=>$params['company_id'],
            'doctype'=>DidoxService::DOC_TYPE_ACT,
            'params'=>json_encode($params,JSON_UNESCAPED_UNICODE),
            'type' => Queue::TYPE_OUTGOING,
            'status'=>Queue::STATUS_WAIT
        ]);

        $company = Company::where(['id'=>$params['company_id']])->first();
        if(mb_strlen($company->director<3))   $company->director = $params['company_director'];
        if(mb_strlen($company->accountant<3)) $company->accountant = $params['company_accountant'];
        $company->save();


        return redirect()->route('frontend.profile.modules.document.index', ['owner'=>'outgoing',app()->getLocale()])->with('success', __('main.add_queue'));


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Act $act
     * @return \Illuminate\Http\Response
     */
    public function destroy(Act $act)
    {

        $company= $act->company;

        $result = DidoxService::deleteDocument($act,$company);
        $error='';
        if(!empty($result->data)) {
            if ($result->data) {
                $act->update(['status' => DidoxService::STATUS_DELETED, 'doc_status' => DidoxService::STATUS_DELETED]);
                //$act->delete();
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

    public function checkStatus(Request $request,Act $act)
    {
        if(!Company::checkCompany($act->company_id)) abort(404);
        $referer =  $request->headers->get('referer');

        $act->load('company');
        $company = $act->company;
        $response = DidoxService::getDocument($act,$company);
        if(empty($response)){
            $company->update(['token_expire' => 0]);
            return redirect()->to($referer)->with('error', __('main.try_again'));
        }

        //dd($response);
        if($response->data->document->status!=$act->status || $response->data->document->doc_status!=$act->doc_status || empty($act->response_sign)){
            $info = __('main.changed');
            /* if(!empty($response->data->toSign)) {
                $sign =  json_encode(['data'=>['toSign' =>$response->data->toSign]],JSON_UNESCAPED_UNICODE);
            }else{
                $sign = null;
            } */
            $sign = json_encode($response,JSON_UNESCAPED_UNICODE);


            $act->update(['status'=>$response->data->document->status,'doc_status'=>$response->data->document->doc_status,'response_sign'=>$sign,'checked_at' => date('Y-m-d H:i:s',time())]);
        }else{
            $info = __('main.no_changes');
        }
        return redirect()->to($referer)->with('success', $info);
    }

    public function getCompanyInfo(Request $request)
    {

        $error = [];
        if (!empty($request->contract_id)) {

            if ($act = Act::where(['id' => $request->contract_id])->first()) {

                $response = json_decode($act->response);
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



    public function exportPdf(Request $request, Act $act){
        if(!Company::checkCompany($act->company_id)) abort(404);

        /// $companies = Company::where(['user_id'=>Auth::id()])->get();
        $act->load('company');

        /*
         $qrcode = null;
          ob_start();
       QRCode::url($_SERVER['HTTP_HOST'].'/'.app()->getLocale().'/acts/'.$act->contract_number)->svg();
       $qrcode = ob_get_contents();
       ob_end_clean(); */

        /*PDF::setOption(['dpi' => 300, 'defaultFont' => 'arial', 'default_paper_orientation' => 'landscape']);
        $pdf = PDF::loadView('frontend.profile.modules.act.print', compact('act', 'companies','qrcode'));
        $pdf->setPaper('a4', 'landscape')->setWarnings(false);
        return $pdf->stream('contract_'.$act->contract_number.'-'.$act->contract_date.'.pdf'); */

        return PdfHelper::create([
            'view'=>'frontend.profile.modules.act.print',
            'data' => compact('act'),
            'orientation' => 'landscape',
            'filename' => 'act_'.$act->number.'-'.$act->date.'.pdf'
        ]);

    }

    public function download(Act $act){
        if(!Company::checkCompany($act->company_id)) abort(404);

        $act->load('company');

        $response = json_decode($act->response);
        $signature = false;

        $owner = new stdClass();
        $client = new stdClass();

        $owner->tin = $response->document_json->sellertin;
        $client->tin = $response->document_json->buyertin;

        if(!empty($response->signature)) {
            $signature = json_decode($response->signature);
            $this->getSignatureInfo($signature, $owner, $client);
        }elseif($act->doc_status==DidoxService::STATUS_SIGNED) {
            $responseSign = json_decode($act->response_sign);
            if (!empty($responseSign->data->document->signature)) {
                $signature = json_decode($responseSign->data->document->signature);
                $this->getSignatureInfo($signature, $owner, $client);
            }
        }

        $params = [
            'filename' => 'act_'.$act->number.'-'. date('Y.m.d',strtotime($act->date)),
            'data' => compact('act','owner','client','signature'),
            'type'=>'act'
        ];


        $result = FileHelper::createArchieve($act,$params);
        if(!$result['status']) {
            Session::flash('error',$result['error']);
            return redirect()->to(request()->headers->get('referer'));
        }
        FileHelper::download($result['file']);

    }


}
