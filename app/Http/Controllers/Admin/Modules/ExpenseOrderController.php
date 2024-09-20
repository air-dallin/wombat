<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyCasse;
use App\Models\ExpenseOrder;
use App\Models\Invoice;
use App\Models\Movements;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExpenseOrderController extends Controller
{

    public function index()
    {

        $expense_orders = ExpenseOrder::with(['company','casse','movement','contract'])->active()/*where(['user_id'=>Auth::id()/ *,'status'=>[Module::STATUS_INACTIVE,Module::STATUS_ACTIVE]])*/->paginate(15);

        return view('admin.modules.expense_order.index', compact('expense_orders'));
    }

        public function draft()
        {

            $expense_order = ExpenseOrder::with(['company','casse','movement','contract'])->where([/*'user_id'=>Auth::id(),*/'status'=>Module::STATUS_DRAFT])->paginate(15);

            return view('admin.modules.expense_order.index', compact('expense_order'));
      }

    public function create()
    {
        $companies   = Company::getCompany(); // where(['user_id'=>Auth::id()])->get();

        $movements = Movements::where(['status'=>Status::STATUS_ACTIVE])->get(); // статьи движений


        return view('admin.modules.expense_order.form', compact('companies', 'movements'));
    }

    public function edit(ExpenseOrder $expenseOrder)
    {

        $companies   = Company::with('casse')->where(['user_id'=>$expenseOrder->user_id])->get();

       // $company_casses = CompanyCasse::with('company')->get();

        $movements = Movements::where(['status'=>1])->get(); // статьи движений
        $expense_order = $expenseOrder;

        return view('admin.modules.expense_order.form', compact('expense_order','companies', /*'company_casses',*/ 'movements'));
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

        return redirect()->route('admin.modules.expense_order.index', app()->getLocale());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'contract_number'  => 'required|string',
                'contract_date' => 'required|string',
                //'order_date' => 'required|string',
                'contragent'   => 'required|string',
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
            return redirect()->route('admin.modules.expense_order.create', app()->getLocale())
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors());
        }

        if(!$expenseOrder = ExpenseOrder::create($params)){
            dd($expenseOrder->getErrrors());
        }

        return redirect()->route('admin.modules.expense_order.index', app()->getLocale())->with('success', 'Update success');


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
        return redirect()->route('admin.modules.expense_order.index', app()->getLocale());
    }


    public function getCases(Request $request){

        if(!empty($request->company_id)){

            $company_cases = CompanyCasse::where(['company_id'=>$request->company_id])->get();

            foreach ($company_cases as $case){
                $cases[] = "<option value='{$case->id}'>{$case->getTitle()}</option>";
            }

            return ['status'=>true,'data'=>$cases];
        }
        return ['status'=>false];

    }

}
