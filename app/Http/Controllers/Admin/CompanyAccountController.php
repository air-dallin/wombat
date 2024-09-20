<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Accounting;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyAccount;
use App\Models\Region;
use Illuminate\Http\Request;

class CompanyAccountController extends Controller
{

    /**
     * список компаний
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::active()->order()->paginate(15);
        return view('admin.company_account.index',compact('companies'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        /*$company->with(['company_accounts']);

        return view('admin.companies.form',compact('company','regions','cities'));*/

    }

    /**
     * план счетов
     *
     * @return \Illuminate\Http\Response
     */
    public function accounts(Company $company)
    {
        $accounts = Accounting::where(['company_id'=>$company->id])->order()->paginate(15);
        return view('admin.company_account.account',compact('company','accounts'));
    }



}
