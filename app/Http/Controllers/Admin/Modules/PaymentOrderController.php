<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Helpers\ObjectHelper;
use App\Http\Controllers\Controller;
use App\Models\CompanyInvoice;
use App\Models\City;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Module;
use App\Models\Movements;
use App\Models\PaymentOrder;
use App\Models\Region;
use App\Models\Status;
use App\Services\Modules\ServicePaymentOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PaymentOrderController extends Controller
{

    public function index()
    {
        $payment_orders = PaymentOrder::with(['company'])->whereNotIn('status',[Status::STATUS_DELETED,Status::STATUS_DRAFT])->paginate(15);
        return view('admin.modules.payment_order.index', compact('payment_orders'));
    }


    public function create()
    {
        $regions       = Region::all();
        $cities        = City::all();
        $companies     = Company::getCompany();
        $invoices    = Invoice::where(['status'=>1])->get();
        // возможно счета будут динамически подгружаться в зависимости от выбора компании

     /*   if(!empty($companies)) {
            $company_invoices = CompanyInvoice::with('company')->whereIn('company_id', ObjectHelper::getIds($companies))->get();
        }else{
            $company_invoices = [];
        }*/
        $movements = Movements::where(['status'=>1])->get(); // статьи движений

        return view('admin.modules.payment_order.form', compact('regions', 'cities', 'companies', 'invoices', /*'company_invoices',*/ 'movements'));
    }

    public function edit(PaymentOrder $paymentOrder)
    {
        $regions       = Region::all();
        $cities        = City::all();
        $companies         = Company::getCompany($paymentOrder->company_id); // where(['user_id'=>Auth::id()])->get();
        $invoices    = Invoice::where(['status'=>1])->get();
        // возможно счета будут динамически подгружаться в зависимости от выбора компании
        //$company_invoices = CompanyInvoice::with('company')->whereIn('company_id',$companies)->get();
        /*if(!empty($companies)) {
            $company_invoices = CompanyInvoice::with('company')->whereIn('company_id', $companies)->get();
        }else{
            $company_invoices = [];
        } */

        $company_invoices = CompanyInvoice::where(['company_id'=>$paymentOrder->company_id])->active()->get();


        $movements = Movements::where(['status'=>1])->get(); // статьи движений

        $payment_order = $paymentOrder;

        return view('admin.modules.payment_order.form', compact('payment_order','regions', 'cities', 'companies', 'invoices', 'movements','company_invoices'));
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
        $params = $request->all();

        $paymentOrder->update($params);

        if($paymentOrder->status == Status::STATUS_SEND){
            ServicePaymentOrder::send($paymentOrder);
        }

        return redirect()->route('admin.modules.payment_order.index', app()->getLocale());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
           [
               'contract_number'  => 'required|string',
               'contract_date' => 'required|string',
               'contragent'   => 'required|string|min:8',
               'invoice_id' => 'required', /*|min:1|exists:invoices,id*/
               'company_invoice' => 'required', /*|min:1|exists:company_invoices,id*/
               'amount' => 'required',
               'movement_id' => 'required',
               //'payment_purpose' => 'required',
           ]
       );

        $params = $request->all();
        $params['user_id'] = Auth::id();

        // $company_invoices = CompanyInvoice::with('company')->where(['id'=>$params['company_invoice_id']])->first();
        // $params['company_id'] = $company_invoices->company_id;

        if ($validator->fails()) {
           return redirect()->route('admin.modules.payment_order.create', app()->getLocale())
               ->withErrors($validator)
               ->withInput()
               ->with('error', $validator->errors());
        }

        if($paymentOrder = PaymentOrder::create($params)){

            if($paymentOrder->status == Status::STATUS_SEND){
                $result = ServicePaymentOrder::send($paymentOrder);
                if($result['status']=='false'){

                    $paymentOrder->delete();

                    $referer =  $request->headers->get('referer');
                    return redirect()->to($referer)
                        ->withInput()
                        ->with('error', 'error to send document');            }
            }

        }else{
            dd($paymentOrder->getErrrors());
        }



        return redirect()->route('admin.modules.payment_order.index', app()->getLocale())->with('success', 'Update success');


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
        return redirect()->route('admin.modules.payment_order.index', app()->getLocale());
    }

    public function getInvoices(Request $request){

        if(!empty($request->company_id)){

            if($company = Company::where(['id'=>$request->company_id])->first()) {

                    $invoice = $company->bank_code;

                    return ['status' => true, 'data' => $invoice];

            }

            /*if($company_invoices = CompanyInvoice::where(['company_id'=>$request->company_id])->get()) {
                if(count($company_invoices)) {
                    $invoices = [];
                    foreach ($company_invoices as $invoice) {
                        $invoices[] = "<option value='{$invoice->id}'>{$invoice->invoice}</option>";
                    }

                    return ['status' => true, 'data' => $invoices];
                }
            }*/

        }
        return ['status'=>false,'error'=>__('main.invoice_not_found')];

    }


}
