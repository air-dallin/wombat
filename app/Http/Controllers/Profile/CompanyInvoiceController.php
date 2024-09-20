<?php

namespace App\Http\Controllers\Profile;

use App\Helpers\ObjectHelper;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyInvoice;
use App\Models\Crop;
use App\Models\Image;
use App\Models\Livestock;
use App\Models\Region;
use App\Models\Status;
use App\Models\User;
use App\Models\UserCrop;
use App\Models\UserInfo;
use App\Models\UserLivestock;
use App\Services\FacturaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CompanyInvoiceController extends Controller
{
    public function index()
    {
        $company = Company::getCurrentCompany();
        $companies = Company::getCompany();
        if(!is_object($company)) {
            $companyInvoices = CompanyInvoice::byCompany($companies)->active()->order()->paginate(15);
        }else{
            $companyInvoices = CompanyInvoice::where(['company_id'=>$company->id])->active()->order()->paginate(15);
        }

        $company = Company::getCurrentCompany();
        $current_company_id = !empty($company) ? $company->id : 0;

        return view('frontend.profile.company_invoice.index', compact('companyInvoices','current_company_id','companies','company'));
    }

    public function create()
    {
        $companies = Company::getCompany();
        return view('frontend.profile.company_invoice.form',compact('companies'));
    }

    public function edit(CompanyInvoice $companyInvoice)
    {
        $companies = Company::getCompany();
        return view('frontend.profile.company_invoice.form', compact('companyInvoice','companies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\CompanyInvoice $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CompanyInvoice $companyInvoice)
    {
        $params = $request->all();

        $companyInvoice->update($params);

        return redirect()->route('frontend.profile.company_invoice.index', app()->getLocale());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'bank_invoice'  => 'required|string',
                'company_id' => 'required',
            ]
        );

        $params = $request->all();

        if ($validator->fails()) {
            return redirect()->route('frontend.profile.company_invoice.create', app()->getLocale())
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors());
            ;

        }

         CompanyInvoice::create($params);

        return redirect()->route('frontend.profile.company_invoice.index', app()->getLocale());
    }

    public function destroy(CompanyInvoice $companyInvoice){
        $companyInvoice->update(['status'=>Status::STATUS_DELETED]);
        //$company->status = CompanyInvoice::STATUS_DELETE;
        //$company->save();

        return redirect()->route('frontend.profile.company_invoice.index', app()->getLocale())->with('success', 'Operation successfull completed!');
    }

    public function setMainInvoice(Request $request){
        if($request->has('id') && $request->has('company_id') ){
            $invoices = CompanyInvoice::where(['company_id'=>$request->company_id])->get();
            foreach ($invoices as &$invoice){
                if($invoice->id==$request->id){
                    $invoice->update(['is_main'=>true]);
                }else {
                    $invoice->update(['is_main' => false]);
                }
            }
            return ['status'=>true];
        }
        return ['status'=>false,'error'=>__('main.company_not_found')];
    }



}
