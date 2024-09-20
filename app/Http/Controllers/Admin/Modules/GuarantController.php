<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Helpers\ObjectHelper;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyInvoice;
use App\Models\Invoice;
use App\Models\Module;
use App\Models\Movements;
use App\Models\Guarant;
use App\Models\Region;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class GuarantController extends Controller
{

    public function index()
    {

        $guarants = Guarant::with(['company'])->/*where(['user_id'=>Auth::id()])->*/whereIn('status',[Module::STATUS_INACTIVE,Module::STATUS_ACTIVE])->paginate(15);

        return view('admin.modules.guarant.index', compact('guarants'));
    }

    public function draft()
    {

        $guarants = Guarant::with(['company'])->where([/*'user_id'=>Auth::id(),*/'status'=>Module::STATUS_DRAFT])->paginate(15);

        return view('admin.modules.guarant.index', compact('guarants'));
    }



    public function create()
    {
        $regions       = Region::all();
        $cities        = City::all();
        $companies         = Company::getCompany(); //where(['user_id'=>Auth::id()])->get();
        $invoices    = Invoice::where(['status'=>1])->get();

        $movements = Movements::where(['status'=>1])->get(); // статьи движений

        return view('admin.modules.guarant.form', compact('regions', 'cities', 'companies', 'invoices', 'movements'));
    }

    public function edit(Guarant $guarant)
    {
        $regions       = Region::all();
        $cities        = City::all();
        $companies         = Company::getCompany(); // where(['user_id'=>Auth::id()])->get();
        $invoices    = Invoice::where(['status'=>1])->get();
        // возможно счета будут динамически подгружаться в зависимости от выбора компании

        if(!empty($companies)) {
            $company_invoices = CompanyInvoice::with('company')->whereIn('company_id', ObjectHelper::getIds($companies))->get();
        }else{
            $company_invoices = [];
        }
        $movements = Movements::where(['status'=>1])->get(); // статьи движений

        $guarant = $guarant;

        return view('admin.modules.guarant.form', compact('Guarant','regions', 'cities', 'companies', 'invoices', 'company_invoices', 'movements'));
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

        $guarant->update($params);

        return redirect()->route('admin.modules.guarant.index', app()->getLocale());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
              /*  'contract_number'  => 'required|string',
                'contract_date' => 'required|string',
                'contragent'   => 'required|string|min:3',
                'invoice_id' => 'required|exists:invoices,id',
                'company_invoice_id' => 'required|exists:company_invoices,id',
                'amount' => 'required',
                'movement_id' => 'required',
                //'payment_purpose' => 'required',*/
            ]
        );

        $params = $request->all();
        $params['user_id'] = Auth::id();

        $company_invoices = CompanyInvoice::with('company')->where(['id'=>$params['company_invoice_id']])->first();
        $params['company_id'] = $company_invoices->company_id;

       /* if ($validator->fails()) {
            return redirect()->route('admin.modules.guarant.create', app()->getLocale())
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors());
        }*/

        if(!$guarant = Guarant::create($params)){
            dd($guarant->getErrrors());
        }

        return redirect()->route('admin.modules.guarant.index', app()->getLocale())->with('success', 'Update success');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Guarant $guarant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Guarant $guarant)
    {

        $guarant->delete();
        return redirect()->route('admin.modules.guarant.index', app()->getLocale());
    }

    /*public function getInvoices(Request $request){

        if(!empty($request->company_id)){

            $company_invoices = CompanyInvoice::where(['company_id'=>$request->company_id])->get();

            foreach ($company_invoices as $invoice){
                $invoices[] = "<option value='{$invoice->id}'>{$invoice->invoice}</option>";
            }

            return ['status'=>true,'data'=>$invoices];
        }
        return ['status'=>false];

    }*/


}
