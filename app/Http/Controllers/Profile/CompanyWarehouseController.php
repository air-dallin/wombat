<?php

namespace App\Http\Controllers\Profile;

use App\Helpers\ObjectHelper;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyWarehouse;
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

class CompanyWarehouseController extends Controller
{
    public function index()
    {
        $company = Company::getCurrentCompany();
        $companies = Company::getCompany();
        if(!is_object($company)) {
            $companyWarehouses = CompanyWarehouse::byCompany($companies)->notDeletedOnly()->order()->paginate(15);
        }else{
            $companyWarehouses = CompanyWarehouse::where(['company_id'=>$company->id])->notDeletedOnly()->order()->paginate(15);
        }
        $current_company_id = !empty($company)? $company->id : 0; //Company::getCurrentCompanyId();
        return view('frontend.profile.company_warehouse.index', compact('companyWarehouses','current_company_id','companies','company'));
    }

    public function create()
    {
        $companies = Company::getCompany();
        return view('frontend.profile.company_warehouse.form',compact('companies'));
    }

    public function edit(CompanyWarehouse $companyWarehouse)
    {

        $companies = Company::getCompany();
        return view('frontend.profile.company_warehouse.form', compact('companyWarehouse','companies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\CompanyWarehouse $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CompanyWarehouse $companyWarehouse)
    {
        $params = $request->all();

        $companyWarehouse->update($params);

        return redirect()->route('frontend.profile.company_warehouse.index', app()->getLocale());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'title_ru'  => 'required|string',
                //'title_uz'  => 'required|string',
                //'title_en'  => 'required|string',
                'company_id' => 'required',
                //'payment_purpose' => 'required',
            ]
        );

        $params = $request->all();
        //$params['user_id'] = Auth::id();

        if ($validator->fails()) {
            return redirect()->route('frontend.profile.company_warehouse.create', app()->getLocale())
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors());
            ;

        }

         CompanyWarehouse::create($params);

        return redirect()->route('frontend.profile.company_warehouse.index', app()->getLocale());
    }

    public function destroy(CompanyWarehouse $companyWarehouse){

       //  $company->delete();

        // $company->status = Status::STATUS_DELETED;
        $companyWarehouse->update(['status'=>Status::STATUS_DELETED]);

        return redirect()->route('frontend.profile.company_warehouse.index', app()->getLocale())->with('success', 'Operation successfull completed!');
    }



}
