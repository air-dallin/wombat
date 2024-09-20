<?php

namespace App\Http\Controllers\Profile;

use App\Helpers\AccountingHelper;
use App\Http\Controllers\Controller;
use App\Models\Accounting;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CompanyAccountController extends Controller
{

    /**
     * список компаний
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company_id = Company::getCurrentCompanyId();
        if (empty($company_id)) {
            $companies = Company::where(['user_id'=>Auth::id()])->active()->order()->paginate(15);
        } else {
            $companies = Company::where(['id' => $company_id])->active()->order()->paginate(15);
        }
        return view('frontend.profile.company_account.index', compact('companies'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Company $company
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
    public function accounts(Request $request,Company $company)
    {
        $from = $request->has('from') ? $request->from :date('Y-m-01');
        $to = $request->has('to') ? $request->to :date('Y-m-d');
        $options = AccountingHelper::getOptions($request);
        $accountings = AccountingHelper::getData($options);

        $planRows = AccountingHelper::getPlanRows($accountings);
        $planRows = AccountingHelper::arrayPaginator($planRows, $request,$options);
        Session::remove('planUrlBack');
        return view('frontend.profile.company_account.account', compact('company','planRows','from','to'));
    }

    /** отчет по проводкам
    */
    public function plan(Request $request,Company $company,$dt,$ct){

        $query = Accounting::where(['company_id'=>$company->id,'debit_account'=>$dt,'credit_account'=>$ct]);

        if($request->has('from') && $request->has('to')){
            $query->where(function($q) use($request){
                $q->whereBetween('date', [$request->from, $request->to]);
                if($request->has('saldo')){
                    $q->orWhereBetween('date', [date('2020-0-01'), $request->from]);
                }
            });
        }

        $accounts = $query->order()->paginate(15);

        if(Session::has('planUrlBack')){
            $urlBack = Session::get('planUrlBack');
        }else{
            $urlBack = url()->previous();
            Session::put('planUrlBack',$urlBack);
        }

        return view('frontend.profile.company_account.plan',compact('company','accounts','urlBack'));

    }

}
