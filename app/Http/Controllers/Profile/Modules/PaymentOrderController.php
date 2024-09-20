<?php

namespace App\Http\Controllers\Profile\Modules;

use App\Helpers\CryptHelper;
use App\Helpers\Elog;
use App\Helpers\ExcelHelper;
use App\Helpers\FileHelper;
use App\Helpers\ObjectHelper;
use App\Helpers\PaymentOrderDocument;
use App\Helpers\QueryHelper;
use App\Http\Controllers\Controller;
use App\Models\Accounting;
use App\Models\CompanyAccount;
use App\Models\CompanyInvoice;
use App\Models\City;
use App\Models\Company;
use App\Models\Contract;
use App\Models\DibankOption;
use App\Models\Invoice;
use App\Models\Module;
use App\Models\Movements;
use App\Models\PaymentOrder;
use App\Models\PurposeCode;
use App\Models\Region;
use App\Models\Status;
use App\Services\DibankService;
use App\Services\DidoxService;
use App\Services\FacturaService;
use App\Services\KapitalService;
use App\Services\Modules\ServicePaymentOrder;
use App\Services\StyxService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PaymentOrderController extends Controller
{

    public function index(Request $request)
    {
        $payment_orders = PaymentOrder::myCompany()->notDeleted()->with(['company','contract'])->where(['user_id'=>Auth::id()])
            ->order()->filter()->orderBy('vdate','DESC')->paginate(15);

        if(QueryHelper::fixSearchQuery($request)){
            return response()->json(['status'=>true,'data'=>view('frontend.profile.modules.payment_order.table', compact('payment_orders'))->render()]);
        }
        return view('frontend.profile.modules.payment_order.index', compact('payment_orders'));
    }

    public function draft(Request $request)
    {
        $payment_orders = PaymentOrder::myCompany()->draft()->with(['company','contract'])->where(['user_id'=>Auth::id()])->order()->filter()->paginate(15);
        if(QueryHelper::fixSearchQuery($request)){
            return response()->json(['status'=>true,'data'=>view('frontend.profile.modules.payment_order.table', compact('payment_orders'))->render()]);
        }
        return view('frontend.profile.modules.payment_order.index', compact('payment_orders'));
    }

    public function create()
    {
        $regions   = Region::all();
        $cities    = City::all();
        $companies = Company::getCompany();
        $invoices  = Invoice::where(['status'=>Status::STATUS_ACTIVE])->get();

        $movements = Movements::where(['status'=>Status::STATUS_ACTIVE])->get(); // статьи движений

        $contracts = Contract::byCompany($companies)->signed()->get();

        $purposes = PurposeCode::all();

        return view('frontend.profile.modules.payment_order.form', compact('regions', 'cities', 'companies', 'invoices', 'contracts'/*, 'company_invoices'*/, 'movements','purposes'));
    }

    public function edit(PaymentOrder $payment_order)
    {
        $regions     = Region::all();
        $cities      = City::all();
        $companies   = Company::getCompany();
        $invoices    = Invoice::where(['status'=>Status::STATUS_ACTIVE])->get();

        CompanyInvoice::checkInvoice($payment_order);

        $company_invoices = CompanyInvoice::byCompany($companies)->active()->get();

        $movements = Movements::where(['status'=>Status::STATUS_ACTIVE])->get(); // статьи движений

        $contracts = Contract::byCompany($companies)->signed()->get();

        if($payment_order->purpose_id==0){
            if($payment_order->purp_code){
                if(!$purpose = PurposeCode::where(['code'=>$payment_order->purp_code])->first()){
                    $purpose = PurposeCode::create(['code'=>$payment_order->purp_code]);
                }
                $payment_order->update(['purpose_id'=>$purpose->id]);
            }
        }
        $purposes = PurposeCode::all();

        return view('frontend.profile.modules.payment_order.form', compact('payment_order','regions', 'cities', 'companies','contracts', 'invoices', 'movements','company_invoices','purposes'));
    }
    public function print(PaymentOrder $paymentOrder)
    {

        $companies     = Company::getCompany();
        $invoices    = Invoice::where(['status'=>Status::STATUS_ACTIVE])->get();

        $movements = Movements::where(['status'=>Status::STATUS_ACTIVE])->get(); // статьи движений

        $paymentOrder->with(['company']);
        $client = [];
        if($companyContragent = Company::where(['inn'=>$paymentOrder->inn_ct])->first()) {
            $client['bank_name'] =$companyContragent->bank_name;
        }else{
            $partner = FacturaService::getCompanyInfo($paymentOrder->inn_ct);
            $account = FacturaService::getPrimaryAccount($partner);
            $client['bank_name'] = $account['name'];
        }

        return view('frontend.profile.modules.payment_order.print', compact('paymentOrder','companies', 'invoices', 'movements','client'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User         $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaymentOrder $paymentOrder)
    {
        $error = [];
        $params = $request->all();

        Company::getDtParams($params);

        unset($params['send_state']);

        $paymentOrder->update($params);

        $referer =  $request->headers->get('referer');

        $dibank = false;

        if(!$dibank){ // kapital

            if($request->send_state=='public' && $paymentOrder->status!=1) {

                $certInfo = StyxService::getCertInfo();
                if (!empty($certInfo->certInfos)) {
                    $paymentOrder->load(['company.kapital', 'purpose', 'invoice']);

                    if(isset($paymentOrder->company->kapital) && !empty($paymentOrder->company->kapital->sid) && !empty($paymentOrder->company->kapital->client_id)) {

                        $companyContragent = Company::where(['inn' => $request->inn_ct])->first();

                        // исходящее owner=1
                        $seller = ['mfo' => $paymentOrder->company->mfo, 'account' => $paymentOrder->invoice->bank_invoice];
                        $client = ['mfo' => $companyContragent->mfo, 'account' => $paymentOrder->acc_ct];

                        $data = StyxService::strToSign($paymentOrder, $seller, $client);

                        $result = StyxService::signBySerial($certInfo->certInfos[0]->serialnumber, $data);
                        if ($result->status == 'success') {
                            $info = [
                                'serial' => $certInfo->certInfos[0]->serialnumber,
                                'signature' => $result->signedMsg
                            ];

                            $partner = FacturaService::getCompanyInfo($paymentOrder->inn_ct);
                            $document = PaymentOrderDocument::getTemplateKapital($paymentOrder, $partner, $info);

                            // отправка документа
                            $res = KapitalService::sendPayment($document);
                            if (is_null($res->error)) {
                                $paymentOrder->update(['state' => $res->result, 'status' => 1, 'general_id' => $res->id, 'response' => json_encode($res, JSON_UNESCAPED_UNICODE)]);
                            } else {
                                $error = __('main.document_sign_error') . ' KAPITALBANK ERROR:' . json_encode($res->error, JSON_UNESCAPED_UNICODE);
                            }


                        } else {
                            $error = __('main.document_sign_error');
                        }
                    } else {
                        $error = __('main.kapital_service_not_set');
                    }

                } else {
                    $error = __('main.epass_certificate_not_found');
                }
            }elseif ($request->send_state=='draft'){


            }




        }else { // dibank

            $paymentOrder->load('company.dibank');
            $dibank = $paymentOrder->company->dibank;
            $filter['document_id'] = $paymentOrder->dibank_id;
            $filter['user_key'] = $paymentOrder->company->dibank->user_key;
            $filter['serial'] = $paymentOrder->serial;
            //$filter['signature'] = $paymentOrder->signature;


            if ($paymentOrder->state == DibankService::DOC_STATUS_WAIT) {
                $partner = FacturaService::getCompanyInfo($paymentOrder->inn_ct);
                $document = PaymentOrderDocument::getTemplate($paymentOrder, $partner);
                $result = DibankService::createDocument($filter, $document, $dibank);
                if (!empty($result) && $result['success']) {
                    $paymentOrder->update(['state' => DibankService::DOC_STATUS_CREATE]);
                    $dibank->update(['dibank_id' => $result['documentId'], 'response' => json_encode($result, JSON_UNESCAPED_UNICODE)]);
                } else {
                    $error = __('main.error_create_document');
                }
            }

            if ($paymentOrder->state == DibankService::DOC_STATUS_CREATE) {
                $result = DibankService::signDocument($filter, $dibank);
                if (!empty($result) && $result['success']) {
                    $paymentOrder->update(['state' => DibankService::DOC_STATUS_SIGN]);
                    $dibank->update(['response_sign' => json_encode($result, JSON_UNESCAPED_UNICODE)]);
                } else {
                    $error = __('main.error_sign_document');
                }
            }
            if ($paymentOrder->state == DibankService::DOC_STATUS_SIGN) {
                $result = DibankService::sendDocument($filter, $dibank);
                if (!empty($result) && $result['success']) {
                    $paymentOrder->update(['state' => DibankService::DOC_STATUS_SEND]);
                    $dibank->update(['response_send' => json_encode($result, JSON_UNESCAPED_UNICODE)]);
                } else {
                    $error = __('main.error_send_document');
                }
            }
        }

        if($error){
            return redirect()->to($referer)
                ->withInput()
                ->with('error', $error);
        }

        return redirect()->route('frontend.profile.modules.payment_order.index', app()->getLocale());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
           [
               'contract_id'  => 'required',
               'inn_ct'   => 'required',
               'name_ct' => 'required',
               'acc_ct' => 'required',
               //'invoice_id' => 'required',
               'company_invoice_id' => 'required',
               'amount' => 'required',
               //'movement_id' => 'required',
               //'payment_purpose' => 'required',
           ]
       );

        if ($validator->fails()) {
            return redirect()->route('frontend.profile.modules.payment_order.create', app()->getLocale())
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors());
        }

        $referer = $request->headers->get('referer');

        $params = $request->all();
        $params['user_id'] = Auth::id();
        $params['status'] = 0;
        $params['state'] = 0;

        unset($params['send_state']);

        Company::getDtParams($params);

        $dibank = false;
        if(!$dibank){ // kapital-bank


            Elog::save('PaymentOrder:beforeSave');
            if($paymentOrder = PaymentOrder::create($params)) {

                if($movement = Movements::where(['id'=>$request->movement_id])->first()) {
                    Accounting::create(['company_id'=>$paymentOrder->company_id,'document_id'=>$paymentOrder->id,'document_type'=>'payment-order','debit_account'=>$movement->incoming_dt,'credit_account'=>$movement->incoming_ct,'amount'=>$paymentOrder->amount/100,'currency'=>'sum','date'=>date('Y-m-d',strtotime($paymentOrder->vdate))]);
                }
                Elog::save('PaymentOrder:save OK');
                if($request->send_state=='public') {

                    $certInfo = StyxService::getCertInfo();
                    Elog::save('PaymentOrder:STYX:certnfo');
                    Elog::save($certInfo);
                    if (!empty($certInfo->certInfos)) {
                        $paymentOrder->load(['company.kapital','purpose','invoice']);

                        if(isset($paymentOrder->company->kapital) && !empty($paymentOrder->company->kapital->sid) && !empty($paymentOrder->company->kapital->client_id)) {

                            if($companyContragent = Company::where(['inn' => $request->inn_ct])->first()) {

                                // исходящее owner=1
                                $seller = ['mfo' => $paymentOrder->company->mfo, 'account' => $paymentOrder->invoice->bank_invoice];
                                $client = ['mfo' => $companyContragent->mfo, 'account' => $paymentOrder->acc_ct];

                                $strToSign = StyxService::strToSign($paymentOrder, $seller, $client);

                                $result = StyxService::signBySerial($certInfo->certInfos[0]->serialnumber, $strToSign);
                                Elog::save('PaymentOrder:STYX:signBySerial');
                                Elog::save($result);
                                if ($result->status == 'success') {
                                    $info = [
                                        'serial' => $certInfo->certInfos[0]->serialnumber,
                                        'signature' => $result->signedMsg
                                    ];

                                    $partner = FacturaService::getCompanyInfo($paymentOrder->inn_ct);
                                    $document = PaymentOrderDocument::getTemplateKapital($paymentOrder, $partner, $info);

                                    // отправка документа
                                    $res = KapitalService::sendPaymentIB($document);
                                    if (is_null($res->error)) {
                                        $res = KapitalService::sendPayment($document);
                                        if (is_null($res->error)) {
                                            $paymentOrder->update(['state' => $res->result, 'status' => 1, 'general_id' => $res->id, 'response' => json_encode($res, JSON_UNESCAPED_UNICODE)]);
                                        } else {
                                            $error = __('main.document_sign_error') . ' KAPITALBANK send ERROR:' . json_encode($res->error, JSON_UNESCAPED_UNICODE);
                                        }
                                    } else {
                                        $error = __('main.document_sign_error') . ' KAPITALBANK create in IB ERROR:' . json_encode($res->error, JSON_UNESCAPED_UNICODE);
                                    }

                                } else {
                                    $error = __('main.document_sign_error');
                                }
                            }else{
                                $error = __('main.company_not_found ') . ' - ' . $request->inn_ct;
                            }
                        }else{
                            $error = __('main.kapital_service_not_set');

                        }

                    } else {
                        $error = __('main.epass_certificate_not_found');
                    }
                }elseif ($request->send_state=='draft'){


                }

            }else{
                $error = $paymentOrder->getErrrors();

            }

        }else { // dibank
            $params['state'] = DibankService::STATUS_WAIT;

            if (!DibankOption::where(['company_id' => $params['company_id']])->first()) {
                return redirect()->route('frontend.profile.modules.payment_order.index', app()->getLocale())->with('error', __('main.dibank_not_set'));
            }

            if ($paymentOrder = PaymentOrder::create($params)) {
                $paymentOrder->load('company.dibank');
                if($movement = Movements::where(['id'=>$request->movement_id])->first()) {
                    Accounting::create(['company_id'=>$paymentOrder->company_id,'document_id'=>$paymentOrder->id,'document_type'=>'payment-order','debit_account'=>$movement->incoming_dt,'credit_account'=>$movement->incoming_ct,'amount'=>$paymentOrder->amount/100,'currency'=>'sum','date'=>date('Y-m-d',strtotime($paymentOrder->vdate))]);
                }

                $filter['company_inn'] = $paymentOrder->company->inn;

                DibankService::checkExpire($paymentOrder->company->dibank);

                $filter['user_key'] = $paymentOrder->company->dibank->user_key;
                //$filter['partner_key'] = $paymentOrder->company->dibank->partner_key;
                $filter['serial'] = $paymentOrder->serial;
                $filter['signature'] = $paymentOrder->signature;

                $partner = FacturaService::getCompanyInfo($paymentOrder->inn_ct);
                $document = PaymentOrderDocument::getTemplate($paymentOrder, $partner);

                $dibank = $paymentOrder->company->dibank;
                $response = DibankService::createDocument($filter, $document, $dibank);

                $error = null;
                if (!empty($response['success']) && $response['success']) {
                    $filter['document_id'] = $response['documentId'];
                    $paymentOrder->update(['state' => DibankService::DOC_STATUS_CREATE]);
                    $dibank->update(['dibank_id' => $response['documentId'], 'response' => json_encode($response, JSON_UNESCAPED_UNICODE)]);
                    $result = DibankService::signDocument($filter, $dibank);
                    if (!empty($result) && $result['success']) {
                        $paymentOrder->update(['state' => DibankService::DOC_STATUS_SIGN]);
                        $dibank->update(['response_sign' => json_encode($result, JSON_UNESCAPED_UNICODE), 'state' => DibankService::DOC_STATUS_SIGN]);
                        $result = DibankService::sendDocument($filter, $dibank);
                        if (!empty($result) && $result['success']) {
                            $paymentOrder->update(['state' => DibankService::DOC_STATUS_SEND]);
                            $dibank->update(['response_send' => json_encode($result, JSON_UNESCAPED_UNICODE), 'state' => DibankService::DOC_STATUS_SEND]);
                        } else {
                            $error = __('main.error_send_document');
                        }
                    } else {
                        $error = __('main.error_sign_document');
                    }
                } else {
                    $error = __('main.error_create_document');
                }

            } else {
                $error = $paymentOrder->getErrrors();
            }

        } // use dibank service

        if($error){

            Elog::save('PaymentOrder:errors: ');
            Elog::save($error);

            if(!empty($paymentOrder)) {
                return redirect()->to('//' . $_SERVER['SERVER_NAME'] . '/' . app()->getLocale() . '/profile/modules/payment_order/edit/' . $paymentOrder->id)
                    ->withInput()
                    ->with('error', $error);
            }else{
                return redirect()->to($referer)
                    ->withInput()
                    ->with('error', $error);
            }
        }
        return redirect()->route('frontend.profile.modules.payment_order.index', app()->getLocale())->with('success', 'Update success');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\PaymentOrder $paymentOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentOrder $paymentOrder)
    {
        $paymentOrder->delete();
        return redirect()->route('frontend.profile.modules.payment_order.index', app()->getLocale());
    }

    public function getInvoices(Request $request){

        if(!empty($request->company_id)){

            if($company_invoices = CompanyInvoice::where(['company_id'=>$request->company_id,'status'=>1])->get()) {
                if(count($company_invoices)) {
                    $invoices = [];
                    foreach ($company_invoices as $invoice) {
                        $selected = $invoice->is_main ?'selected':'';
                        $invoices[] = "<option value='{$invoice->id}' $selected>{$invoice->bank_invoice}</option>";
                    }
                    return ['status' => true, 'data' => $invoices];
                }
            }

        }
        return ['status'=>false,'error'=>__('main.invoice_not_found')];

    }
    public function downloadOrder(Request $request,PaymentOrder $paymentOrder){
        $filename = ExcelHelper::create($paymentOrder);
        if(strpos($filename,'_')) FileHelper::download($filename);
        return redirect()->back();
    }

    public function getOrder(Request $request){

        $company_ids = Company::getMyCompanyIds();

        if($paymentOrder = PaymentOrder::whereIn('company_id',$company_ids)->where(['dir'=>KapitalService::DIR_INCOMING])->where(function($q){$q->where(['movement_id'=>0])->orWhereNull('movement_id');})->first()) {
            return ['status' => true, 'data' => ['company_name'=>$paymentOrder->name_dt, 'info'=>number_format($paymentOrder->amount/100,'2','.',' ') . ' ' . __('main.sum') .' ' . __('main.from_date') . ' ' . $paymentOrder->vdate,'id'=>$paymentOrder->id]];
        }

        return ['status'=>false,'error'=>__('main.not_found')];

    }

    public function setMovement(Request $request){
        if($request->has('id') && $request->has('movement_id')) {
            if ($paymentOrder = PaymentOrder::where(['id' => $request->id])->first()) {
                $paymentOrder->update(['movement_id'=>$request->movement_id]);
                if($movement = Movements::where(['id'=>$request->movement_id])->first()) {
                    Accounting::create(['company_id'=>$paymentOrder->company_id,'document_id'=>$paymentOrder->id,'document_type'=>'payment-order','debit_account'=>$movement->incoming_dt,'credit_account'=>$movement->incoming_ct,'amount'=>$paymentOrder->amount/100,'currency'=>'sum','date'=>date('Y-m-d',strtotime($paymentOrder->vdate))]);
                }
                return ['status' => true];
            }
        }
        return ['status'=>false,'error'=>__('main.not_found')];

    }


}
