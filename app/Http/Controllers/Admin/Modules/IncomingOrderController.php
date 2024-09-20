<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Helpers\ObjectHelper;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyCasse;
use App\Models\Invoice;
use App\Models\Module;
use App\Models\Movements;
use App\Models\IncomingOrder;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class IncomingOrderController extends Controller
{

    public function index()
    {

        $incoming_orders = IncomingOrder::with(['company','casse','movement'])
            ->whereNotIn('status',[Status::STATUS_DELETED,Status::STATUS_DRAFT])->paginate(15);

        return view('admin.modules.incoming_order.index', compact('incoming_orders'));
    }

    public function draft()
    {

        $incoming_orders = IncomingOrder::with(['company','casse','movement'])->where(['status'=>Module::STATUS_DRAFT])->paginate(15);

        return view('admin.modules.incoming_order.index', compact('incoming_orders'));
    }

    public function create()
    {
        $companies   = Company::getCompany(); // with('casse')->where(['user_id'=>Auth::id()])->get();
        $invoices    = Invoice::where(['status'=>1])->get();

       // $company_casses = [];// CompanyCasse::with('company')->whereIn('company_id',ObjectHelper::getIds($companies))->get();
        $movements = Movements::where(['status'=>1])->get(); // статьи движений

        return view('admin.modules.incoming_order.form', compact('companies', 'invoices', /*'company_casses',*/ 'movements','companies'));
    }

    public function edit(IncomingOrder $incomingOrder)
    {
         $companies         = Company::getCompany($incomingOrder->company_id); // where(['user_id'=>Auth::id()])->get();
        $invoices    = Invoice::where(['status'=>1])->get();

        $company_casses = CompanyCasse::with('company')->where(['id'=>$incomingOrder->casse_id])->first();

        $movements = Movements::where(['status'=>1])->get(); // статьи движений
        $incoming_order = $incomingOrder;

        return view('admin.modules.incoming_order.form', compact('incoming_order','companies', 'invoices', 'company_casses', 'movements'));
    }

    public function print(IncomingOrder $incomingOrder)
    {
         $companies         = Company::getCompany($incomingOrder->company_id); // where(['user_id'=>Auth::id()])->get();
        $invoices    = Invoice::where(['status'=>1])->get();

        $company_casses = CompanyCasse::with('company')->where(['id'=>$incomingOrder->casse_id])->first();

        $movements = Movements::where(['status'=>1])->get(); // статьи движений
        $incoming_order = $incomingOrder;

        return view('admin.modules.incoming_order.print', compact('incoming_order','companies', 'invoices', 'company_casses', 'movements'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User         $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IncomingOrder $incomingOrder)
    {
        $params = $request->all();

        $incomingOrder->update($params);

        return redirect()->route('admin.modules.incoming_order.index', app()->getLocale());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'contract_number'  => 'required|string',
                'contract_date' => 'required|string',
                'order_date' => 'required|string',
                'contragent'   => 'required|string',
                'casse_id' => 'required',
                'company_id' => 'required',
                'amount' => 'required',
                'movement_id' => 'required',
                //'comment' => 'required',
            ]
        );

        $params = $request->all();
      //  dd($params);
        $params['user_id'] = Auth::id();
        if ($validator->fails()) {
            return redirect()->route('admin.modules.incoming_order.create', app()->getLocale())
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors());
        }
        /*$company_casses = CompanyCasse::with('company')->where(['id'=>$params['casse_id']])->first();

        $params['company_id'] = $company_casses->company_id;*/

        if(!$incomingOrder = IncomingOrder::create($params)){
            dd($incomingOrder->getErrrors());
        }

        return redirect()->route('admin.modules.incoming_order.index', app()->getLocale())->with('success', 'Update success');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\IncomingOrder $incomingOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(IncomingOrder $incomingOrder)
    {

        $incomingOrder->delete();
        return redirect()->route('admin.modules.incoming_order.index', app()->getLocale());
    }


}
