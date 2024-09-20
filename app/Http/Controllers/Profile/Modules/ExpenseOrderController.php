<?php

namespace App\Http\Controllers\Profile\Modules;

use App\Helpers\ObjectHelper;
use App\Helpers\PdfHelper;
use App\Helpers\QueryHelper;
use App\Http\Controllers\Controller;
use App\Models\Accounting;
use App\Models\Company;
use App\Models\CompanyAccount;
use App\Models\CompanyCasse;
use App\Models\Contract;
use App\Models\ExpenseOrder;
use App\Models\Invoice;
use App\Models\Movements;

use App\Models\Status;
use App\Services\DidoxService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExpenseOrderController extends Controller
{

    public function index(Request $request)
    {

        $expense_orders = ExpenseOrder::myCompany()->with(['company','casse','movement','contractOutgoing'])->where(['user_id'=>Auth::id()/*,'status'=>[Module::STATUS_INACTIVE,Module::STATUS_ACTIVE]*/])->active()->filter()->order()->orderBy('order_date','DESC')->paginate(15);
        if(QueryHelper::fixSearchQuery($request)){
            return response()->json(['status'=>true,'data'=>view('frontend.profile.modules.expense_order.table', compact('expense_orders'))->render()]);
        }
        return view('frontend.profile.modules.expense_order.index', compact('expense_orders'));
    }

    public function draft(Request $request) {
        $expense_orders = ExpenseOrder::myCompany()->with(['company','casse','movement','contractOutgoing'])->where(['user_id'=>Auth::id()/*,'status'=>Module::STATUS_DRAFT*/])->draft()->order()->filter()->orderBy('order_date','DESC')->paginate(15);
        if(QueryHelper::fixSearchQuery($request)){
            return response()->json(['status'=>true,'data'=>view('frontend.profile.modules.expense_order.table', compact('expense_orders'))->render()]);
        }
        return view('frontend.profile.modules.expense_order.index', compact('expense_orders'));
    }

    public function create()
    {
        $companies   = Company::getCompany(); // where(['user_id'=>Auth::id()])->get();

        $movements = Movements::where(['status'=>Status::STATUS_ACTIVE])->get(); // статьи движений
        $contracts    = Contract::myCompany()->/*where(['status'=>Status::STATUS_ACTIVE])->*/owner(DidoxService::OWNER_TYPE_OUTGOING)->get(); // договора

        return view('frontend.profile.modules.expense_order.form', compact('companies', 'movements','contracts'));
    }

    public function edit(ExpenseOrder $expenseOrder)
    {

        $companies   = Company::with('casse')->where(['user_id'=>Auth::id()])->get();

       // $company_casses = CompanyCasse::with('company')->get();
        $company_casses = [];
        if(!empty($companies) && count($companies)) $company_casses = CompanyCasse::whereIn('company_id', ObjectHelper::getIds($companies))->get();

        $movements = Movements::where(['status'=>Status::STATUS_ACTIVE])->get(); // статьи движений
        $contracts    = Contract::myCompany()/*->where(['status'=>Status::STATUS_ACTIVE])*/->owner(DidoxService::OWNER_TYPE_OUTGOING)->get(); // договора

        $expense_order = $expenseOrder;

        return view('frontend.profile.modules.expense_order.form', compact('expense_order','companies', 'company_casses', 'movements','contracts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User         $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExpenseOrder $expenseOrder)
    {
        $params = $request->all();

        $expenseOrder->update($params);

        return redirect()->route('frontend.profile.modules.expense_order.index', app()->getLocale());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'contract_id'  => 'required',
                // 'contract_date' => 'required|string',
                //'order_date' => 'required|string',
                'contragent'   => 'required|string',
                'contragent_company' => 'required',
                'contragent_bank_code' => 'required',
                'casse_id' => 'required',
                'amount' => 'required',
                'movement_id' => 'required',
                //'comment' => 'required',
            ]
        );

        $params = $request->all();
        $params['user_id'] = Auth::id();

       /* $company_casses = CompanyCasse::with('company')->where(['id'=>$params['casse_id']])->first();

        $params['company_id'] = $company_casses->company_id;*/

        if ($validator->fails()) {
            return redirect()->route('frontend.profile.modules.expense_order.create', app()->getLocale())
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors());
        }

        if($expenseOrder = ExpenseOrder::create($params)){
            Accounting::create(['company_id'=>$expenseOrder->company->id,'document_id'=>$expenseOrder->id,'document_type'=>'expense','debit_account'=>'9110','credit_account'=>'2910','amount'=>$expenseOrder->amount,'currency'=>'sum','date'=>date('Y-m-d',strtotime($expenseOrder->order_date))]);

        }else{
            dd($expenseOrder->getErrrors());
        }

        return redirect()->route('frontend.profile.modules.expense_order.index', app()->getLocale())->with('success', 'Update success');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\ExpenseOrder $expenseOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExpenseOrder $expenseOrder)
    {

        $expenseOrder->delete();
        return redirect()->route('frontend.profile.modules.expense_order.index', app()->getLocale());
    }


    public function getCases(Request $request){

        if(!empty($request->company_id)){

            $company_cases = CompanyCasse::where(['company_id'=>$request->company_id])->get();
            $cases = [];
            foreach ($company_cases as $case){
                $cases[] = "<option value='{$case->id}'>{$case->getTitle()}</option>";
            }

            return ['status'=>true,'data'=>$cases];
        }
        return ['status'=>false,'error'=>__('main.casses_not_found')];

    }
    public function print(ExpenseOrder $expenseOrder)
    {
        $companies         = Company::getCompany();

        $company_casses = CompanyCasse::with('company')->where(['id'=>$expenseOrder->casse_id])->first();

        $movements = Movements::where(['status'=>1])->get(); // статьи движений

        return view('frontend.profile.modules.expense_order.print', compact('expenseOrder','companies',  'company_casses', 'movements'));
    }

    public function exportPdf(ExpenseOrder $expenseOrder){

        if(!Company::checkCompany($expenseOrder->company_id)) abort(404);

        $expenseOrder->load(['company','casse','contract','movement']);

        return PdfHelper::create([
            'view'=>'frontend.profile.modules.expense_order.print',
            'data' => compact('expenseOrder'),
            'orientation' => 'landscape',
            'filename' => 'expense_order_'.$expenseOrder->order_date.'.pdf'
        ]);

    }

    public function exportCsv(){


    }
}
