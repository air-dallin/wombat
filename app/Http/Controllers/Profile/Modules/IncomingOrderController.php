<?php

namespace App\Http\Controllers\Profile\Modules;

use App\Exports\IncomingOrderExport;
use App\Helpers\ObjectHelper;
use App\Helpers\PdfHelper;
use App\Helpers\QueryHelper;
use App\Http\Controllers\Controller;
use App\Models\Accounting;
use App\Models\Company;
use App\Models\CompanyCasse;
use App\Models\Contract;
use App\Models\ExpenseOrder;
use App\Models\Invoice;
use App\Models\Module;
use App\Models\Movements;
use App\Models\IncomingOrder;
use App\Models\Status;
use App\Services\DidoxService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;


class IncomingOrderController extends Controller
{

    public function index(Request $request)
    {
        $incoming_orders = IncomingOrder::myCompany()->with(['company','casse','movement','contractIncoming'])->where(['incoming_orders.user_id'=>Auth::id()/*,'status'=>[Module::STATUS_INACTIVE,Module::STATUS_ACTIVE]*/])
            ->whereNotIn('status',[Status::STATUS_DELETED,Status::STATUS_DRAFT])->order()->filter()->orderBy('order_date','DESC')->paginate(15);
        if(QueryHelper::fixSearchQuery($request)){
            return response()->json(['status'=>true,'data'=>view('frontend.profile.modules.incoming_order.table', compact('incoming_orders'))->render()]);
        }
        return view('frontend.profile.modules.incoming_order.index', compact('incoming_orders'));
    }

    public function draft(Request $request)
    {
        $incoming_orders = IncomingOrder::myCompany()->with(['company','casse','movement','contractIncoming'])->draft()->where(['user_id'=>Auth::id()/*,'status'=>Module::STATUS_DRAFT*/])->filter()->orderBy('order_date','DESC')->paginate(15);
        if(QueryHelper::fixSearchQuery($request)){
            return response()->json(['status'=>true,'data'=>view('frontend.profile.modules.incoming_order.table', compact('incoming_orders'))->render()]);
        }
        return view('frontend.profile.modules.incoming_order.index', compact('incoming_orders'));
    }

    public function create()
    {
        $companies   = Company::getCompany();
       // $invoices    = Invoice::where(['status'=>1])->get();

       // $company_casses = []; // CompanyCasse::byCompany($companies)->where(['status' => Status::STATUS_ACTIVE])->get();
        $contracts    = Contract::myCompany()->/*where(['status'=>Status::STATUS_ACTIVE])->*/owner(DidoxService::OWNER_TYPE_INCOMING)->get(); // договора

        $movements = Movements::where(['status'=>Status::STATUS_ACTIVE])->get(); // статьи движений

        return view('frontend.profile.modules.incoming_order.form', compact('companies', /*'invoices',*/ /*'company_casses',*/ 'movements','companies','contracts'));
    }

    public function edit(IncomingOrder $incomingOrder)
    {
        $companies         = Company::getCompany();
        //$invoices    = Invoice::where(['status'=>Status::STATUS_ACTIVE])->get();
        $company_casses = [];
        if(!empty($companies) && count($companies)) $company_casses = CompanyCasse::whereIn('company_id', ObjectHelper::getIds($companies))->get();
        $movements = Movements::where(['status'=>Status::STATUS_ACTIVE])->get(); // статьи движений
        $incoming_order = $incomingOrder;
        $contracts    = Contract::myCompany()->where(['status'=>Status::STATUS_ACTIVE])->owner(DidoxService::OWNER_TYPE_INCOMING)->get(); // договора


        return view('frontend.profile.modules.incoming_order.form', compact('incoming_order','companies', /*'invoices', */'company_casses', 'movements','contracts'));
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

        return redirect()->route('frontend.profile.modules.incoming_order.index', app()->getLocale());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'contract_id'  => 'required',
                //'contract_date' => 'required|string',
                'order_date' => 'required|string',
                'contragent'   => 'required|string',
                'contragent_company' => 'required',
                'contragent_bank_code' => 'required',

                'casse_id' => 'required',
                'company_id' => 'required',
                'amount' => 'required',
                'movement_id' => 'required',
                //'comment' => 'required',
            ]
        );

        $params = $request->all();
        $params['user_id'] = Auth::id();
        if ($validator->fails()) {
            return redirect()->route('frontend.profile.modules.incoming_order.create', app()->getLocale())
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors());
        }

        if($incomingOrder = IncomingOrder::create($params)){
            Accounting::create(['company_id'=>$incomingOrder->company->id,'document_id'=>$incomingOrder->id,'document_type'=>'incoming','debit_account'=>'5010','credit_account'=>'9010','amount'=>$incomingOrder->amount,'currency'=>'sum','date'=>date('Y-m-d',strtotime($incomingOrder->order_date))]);

        }else{
            dd($incomingOrder->getErrrors());
        }

        return redirect()->route('frontend.profile.modules.incoming_order.index', app()->getLocale())->with('success', 'Update success');


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
        return redirect()->route('frontend.profile.modules.incoming_order.index', app()->getLocale());
    }


    public function getCasses(Request $request){

        if(!empty($request->company_id)){

            if($company_casses = CompanyCasse::where(['company_id'=>$request->company_id])->get()) {
                if(count($company_casses)) {
                    $casses = [];
                    foreach ($company_casses as $casse) {
                        $casses[] = "<option value='{$casse->id}'>{$casse->getTitle()}</option>";
                    }

                    return ['status' => true, 'data' => $casses];
                }
            }

        }
        return ['status'=>false,'error'=>__('main.casses_not_found')];

    }

    public function print(IncomingOrder $incomingOrder)
    {
        $companies = Company::getCompany();

        $company_casses = CompanyCasse::with('company')->where(['id'=>$incomingOrder->casse_id])->first();

        $movements = Movements::where(['status'=>1])->get(); // статьи движений

        return view('frontend.profile.modules.incoming_order.print', compact('incomingOrder','companies',  'company_casses', 'movements'));
    }

    public function exportPdf(IncomingOrder $incomingOrder){

        if(!Company::checkCompany($incomingOrder->company_id)) abort(404);

        $incomingOrder->load(['company','casse','contract','movement']);


        $company = $incomingOrder->company;
        $contract = $incomingOrder->contract;

        return PdfHelper::create([
            'view'=>'frontend.profile.modules.incoming_order.print',
            'data' => compact('incomingOrder','company','contract'),
            'orientation' => 'landscape',
            'filename' => 'incoming_order_'.$incomingOrder->order_date.'.pdf'
        ]);

    }

    public function exportCsv(){


    }


}
