<?php

namespace App\Http\Controllers\Profile;

use App\Helpers\Elog;
use App\Helpers\ObjectHelper;
use App\Http\Controllers\Controller;
use App\Models\Accounting;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyIkpu;
use App\Models\DibankOption;
use App\Models\District;
use App\Models\ExpenseOrder;
use App\Models\IncomingOrder;
use App\Models\Kapital;
use App\Models\Nds;
use App\Models\PaymentOrder;
use App\Models\Region;
use App\Models\User;
use App\Services\FacturaService;
use App\Services\KapitalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        if($request->has('cc')) Cache::flush();

        $companies = Company::where(['user_id' => Auth::id()])
            ->with('kapital','primaryInvoice')
            ->whereIn('status',[Company::STATUS_ACTIVE,Company::STATUS_INACTIVE])
            ->order()->paginate(15);

        $current_company_id = Company::getCurrentCompanyId();

        // TODO обновить данные с сервиса Kapital
        $companyInfo = [];
        if(/*$current_company_id &&*/ $companies){
            set_time_limit(120);
            foreach ($companies as $company ){
                if(Cache::has('kapital_login_company_'.$company->id)) {
                    $companyInfo[$company->id] = Cache::get('kapital_login_company_'.$company->id);
                }else{
                    if (!empty($company->kapital) && !empty($company->kapital->login)) {
                        $result = KapitalService::login($company->kapital);


                        /* $data = ['branch'=>$company->mfo,'account'=>$company->primaryInvoice->bank_invoice,'date'=>date('d.m.Y')];
                        $result = KapitalService::getAccounts($company->kapital,$data); */

                        Elog::save('Kapital login company_id: ' . $company->id);

                        if (empty($result->error) && !empty($result)) {
                            Elog::save($result);
                            if (count($result->result->clients[0]->accounts) > 1) {
                                foreach ($result->result->clients[0]->accounts as $account) {
                                    if ($account->canpay && strpos($account->account, '2020') === 0) {
                                        $companyInfo[$company->id] = ['status' => true, 'amount' => number_format($account->s_in / 100, 0, '.', ' ')];
                                        break;
                                    }
                                }
                            } else {
                                if ($result->result->clients[0]->accounts[0]->account->canpay) {
                                    $companyInfo[$company->id] = ['status' => true, 'amount' => number_format($result->result->clients[0]->accounts[0]->account->s_in / 100, 0, '.', ' ')];
                                } else {
                                    $companyInfo[$company->id] = ['status' => false, 'amount' => 0];
                                }
                            }
                        } else {
                            $companyInfo[$company->id] = ['status' => false, 'amount' => 0];
                        }
                    } else {
                        $companyInfo[$company->id] = ['status' => false, 'amount' => 0];
                    }
                    Cache::put('kapital_login_company_'.$company->id,$companyInfo[$company->id],1200);
                }
            }
        }
        $company_ids = Company::getMyCompaniesIds();

        if(empty($company_ids)) {
            $company_ids = [0];
            $companyIds = 0;
        }else {
            $companyIds = implode(',', $company_ids);
        }
        //Cache::put('payment_orders_amount_'.Auth::id(),'',20);

        if(!Cache::has('payment_orders_amount_'.Auth::id())) {
            $paymentOrderAmounts = DB::select("
                SELECT SUM(amount) AS amount,  m, y ,dir
                FROM(
                    SELECT po.amount/100 AS amount, month(po.vdate) AS m,year(po.vdate) as y ,po.dir
                    FROM payment_orders po
                    WHERE po.company_id IN ({$companyIds}) AND po.dir IN(1,2) and YEAR(po.vdate)=YEAR(NOW())
                    UNION ALL
                    SELECT eo.amount, month(eo.order_date) AS m,year(eo.order_date) as Y ,1 AS dir
                    FROM expense_orders eo
                    WHERE eo.company_id IN ({$companyIds}) and YEAR(eo.order_date)=YEAR(NOW())
                    UNION ALL
                    SELECT io.amount, month(io.order_date) AS m,year(io.order_date) as y ,2 AS dir
                    FROM incoming_orders io
                    WHERE io.company_id IN ({$companyIds}) and YEAR(io.order_date)=YEAR(NOW())
                ) AS res
                GROUP BY res.m,res.dir,res.y
                ORDER BY res.y ASC, res.m ASC");
            Cache::put('payment_orders_amount_'.Auth::id(),$paymentOrderAmounts,300);
        }else{
            $paymentOrderAmounts = Cache::get('payment_orders_amount_'.Auth::id());
        }

        $day1_from = date('Y-m-01',strtotime(date('Y-m-01') . ' -1 month'));
        $day2_from = date('Y-m-01');

        $day1_to = date('Y-m-d',strtotime(date('Y-m-d') . ' -1 month'));
        if(!validate_date($day1_to)) $day1_to = date('Y-m-28');
        $day2_to = date('Y-m-d');


        // для расчета % за месяц
        $paymentOrderAmountsByCompany = DB::select("
            SELECT SUM(amount) AS amount,  m, y ,dir,company_id
            FROM(
                SELECT po.amount/100 AS amount, month(po.vdate) AS m,year(po.vdate) as y ,po.dir,po.company_id
                FROM payment_orders po
                WHERE po.company_id IN({$companyIds}) AND po.dir IN(2) AND po.vdate BETWEEN '{$day1_from}' AND '{$day1_to}' or po.vdate BETWEEN '{$day2_from}' AND '{$day2_to}'
                UNION ALL
                SELECT io.amount, month(io.order_date) AS m,year(io.order_date) as y ,2 as dir,io.company_id
                FROM incoming_orders io
                WHERE io.company_id IN({$companyIds}) AND io.order_date BETWEEN '{$day1_from}' AND '{$day1_to}' or io.order_date BETWEEN '{$day2_from}' AND '{$day2_to}'
            ) AS res
            GROUP BY m,dir,Y,company_id");

        $ordersInfo = [];

        foreach ($paymentOrderAmountsByCompany as $order){
            $ordersInfo[$order->company_id][] = ['amount'=>$order->amount,'m'=>$order->m];
        }

        foreach ($ordersInfo as $key=>$order){
            if(!isset($order[1])) $ordersInfo[$key][1] = ['amount'=>0,'m'=>$ordersInfo[$key][0]['m']];
        }

        $payment_orders = DB::select("
                SELECT amount, date, dir,type,account,partner_company,partner_inn,status,id
                FROM(
                    SELECT po.amount/100 AS amount, po.vdate AS date, po.dir, 'payment_order' as type,po.acc_ct as account,po.name_dt as partner_company,po.inn_dt as partner_inn,po.status,po.id
                    FROM payment_orders po
                    WHERE po.company_id IN ({$companyIds}) AND po.dir IN(1,2)
                    UNION ALL
                    SELECT eo.amount, eo.order_date AS date,1 AS dir, 'expense_order' as type,eo.contragent_bank_code as account,eo.contragent_company as partner_company,eo.contragent as partner_inn,eo.status,eo.id
                    FROM expense_orders eo
                    WHERE eo.company_id IN ({$companyIds})
                    UNION ALL
                    SELECT io.amount, io.order_date AS date, 2 AS dir, 'incoming_order' as type,io.contragent_bank_code as account,io.contragent_company as partner_company,io.contragent as partner_inn,io.status,io.id
                    FROM incoming_orders io
                    WHERE io.company_id IN ({$companyIds})
                ) AS res
                ORDER BY res.date DESC
                LIMIT 10");

        $date_from = date('Y.m.01');
        $date_to = date('Y.m.d');

        $payment_orders_stat = DB::select("
                SELECT SUM(amount) as amount, dir
                FROM(
                    SELECT po.amount/100 AS amount, po.dir as dir
                    FROM payment_orders po
                    WHERE po.company_id IN ({$companyIds}) AND po.dir IN(1,2) AND vdate BETWEEN '{$date_from}' AND '{$date_to}'
                    UNION ALL
                    SELECT eo.amount, 1 AS dir
                    FROM expense_orders eo
                    WHERE eo.company_id IN ({$companyIds}) AND eo.order_date BETWEEN '{$date_from}' AND '{$date_to}'
                    UNION ALL
                    SELECT io.amount, 2 AS dir
                    FROM incoming_orders io
                    WHERE io.company_id IN ({$companyIds}) AND io.order_date BETWEEN '{$date_from}' AND '{$date_to}'
                ) AS res
                GROUP BY res.dir");

        $statInfo = [];

        foreach ($payment_orders_stat as $order){
            $statInfo[$order->dir] = number_format($order->amount,'0','.',' ') . ' '. __('main.sum');
        }

        return view('frontend.profile.companies.index', compact('companies','current_company_id','companyInfo','paymentOrderAmounts','payment_orders','paymentOrderAmountsByCompany','ordersInfo','statInfo'));
    }

    public function create()
    {
        $regions       = Region::all();
        $cities        = City::all();
        $district = District::all();
        $nds = Nds::all();

        return view('frontend.profile.companies.form', compact('regions', 'cities','district','nds'));
    }

    public function edit(Company $company)
    {
       if(!Company::checkCompany($company->id)) abort(404);

        session()->put('selected_company',$company);


        $regions    = Region::all();
        $cities     = City::all();
        $district = District::all();
        $nds = Nds::all();

        return view('frontend.profile.companies.form', compact('regions', 'cities','company','district','nds'));
    }


    public function info(Company $company)
    {
        if(!Company::checkCompany($company->id)) abort(404);

        session()->put('selected_company',$company);

        return view('frontend.profile.companies.info', compact('company'));
    }

    public function services()
    {
        $company = Company::getCurrentCompany();
        $dibank = null;
        $kapital = null;
        if($company) {
            $company = Company::with(['dibank','kapital'])->where(['id' => $company->id])->first();
            if (!isset($company->dibank)) {
                $dibank = DibankOption::create(['company_id' => $company->id]);
            } else {
                $dibank = $company->dibank;
            }
            if (!isset($company->kapital)) {
                $kapital = Kapital::create(['company_id' => $company->id,'date_from'=>date('Y-m-d')]);
            } else {
                $kapital = $company->kapital;
            }
        }


        return view('frontend.profile.companies.services', compact('company','dibank','kapital'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Company $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $params = $request->all();
        $company->load('dibank');

        $params['company']['phone'] = correct_phone($params['company']['phone']);
        $company->update($params['company']);
        if(isset($params['dibank'])) {
            $params['dibank']['phone'] = correct_phone($params['dibank']['phone']);
            $company->dibank->update($params['dibank']);
        }

        return redirect()->route('frontend.profile.companies.index', app()->getLocale());
    }

    public function store(Request $request)
    {
    /*    $validator = Validator::make($request->all(),
            [
                'name'  => 'required|string',
                'address' => 'string',
                'inn'   => 'required|unique:companies,inn',
                'nds_code' => 'required',
                'bank_code' => 'required',
                'mfo' => 'required',
                'oked' => 'required',
                //'payment_purpose' => 'required',
            ]
        );*/

        $params = $request->all();
        $params['company']['user_id'] = Auth::id();

      /*  if ($validator->fails()) {
            return redirect()->route('frontend.profile.companies.create', app()->getLocale())
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors());
            ;

        }*/

        $params['company']['phone'] = correct_phone($params['company']['phone']);
        $params['status'] =1;
        $company = Company::create($params['company']);
        $params['dibank']['company_id'] = $company->id;
        $params['dibank']['phone'] = correct_phone($params['dibank']['phone']);
        DibankOption::create($params['dibank']);
        Kapital::create(['company_id'=>$company->id,'date_from'=>date('Y-m-d')]);

        return redirect()->route('frontend.profile.companies.index', app()->getLocale());
    }

    public function destroy(Company $company){
        if(isset($company->image)) $company->image->delete();
        //$company->delete();
        $company->status = Company::STATUS_DELETE;
        $company->save();

        return redirect()->route('frontend.profile.companies.index', app()->getLocale())->with('success', 'Operation successfull completed!');
    }

    public function getCompanyByInn(Request $request){

        if(!empty($request->inn) && $request->has('user_id')){

            if( !$user = User::where(['id'=>$request->user_id])->first() ){
                return ['status'=>false,'error'=>__('main.user_not_found')];
            }

            $result= FacturaService::checkTokenExpire();
            if(!isset($result['access_token'])){
                return ['status'=>false,'error'=>__('main.token_incorrect')];
            }

            $companyInfo = FacturaService::getCompanyInfo($request['inn'],$result['access_token']);
            if(!isset($companyInfo['CompanyInn'])){
                return ['status'=>false,'error'=>__('main.inn_user_not_found')];
            }

            $bankInfo = FacturaService::getPrimaryAccount($companyInfo);

            $data['name'] = $companyInfo['CompanyName'];
            $data['address'] = $companyInfo['CompanyAddress'];
            $data['inn'] = $companyInfo['CompanyInn'];
            $data['oked'] = $companyInfo['Oked'];
            $data['nds_code'] = $companyInfo['VatCode'];
            $data['bank_code'] = $bankInfo['code'];
            $data['bank_name'] = $bankInfo['name'];
            $data['mfo'] = $bankInfo['mfo'];

            $data['pinfl'] = $companyInfo['Pinfl'];
            $data['phone'] = $companyInfo['PhoneNumber'];
            $data['director_pinfl'] = $companyInfo['DirectorPinfl'];

            $data['director'] = $companyInfo['DirectorName'];
            $data['accountant'] = $companyInfo['Accountant'];

            return ['status'=>true,'data'=>$data];
        }
        return ['status'=>false,'error'=>__('main.company_not_found')];

    }

    public function setActive(Request $request){
        $show_all = false;
        if($request->has('current_company')) {
            if(Session::has('current_company_id') ){
                $current_id = Session::get('current_company_id');
                if($current_id==$request->current_company_id){
                    $request->current_company_id = 0;
                    $request->current_company = null;
                    $show_all = true;
                }
            }
            Session::put('current_company_id', $request->current_company_id);
            Session::put('current_company', $request->current_company);

            return ['status'=>true,'show_all'=>$show_all];
        }
        return ['status'=>false,'show_all'=>$show_all];
    }

    public function getInfo(Request $request){
        if(!empty($request->company_id)){

            $company = Company::with(['invoices'])->where(['id'=>$request->company_id])->first();
            $data['name'] = $company->name;
            $data['address'] = $company->address;
            $data['inn'] = $company->inn;
            $data['oked'] = $company->oked;
            $data['nds_code'] = $company->nds_code;
            $data['bank_code'] = ObjectHelper::getSelectList( $company->invoices,'bank_invoice');
            $data['bank_name'] = $company->bank_name;
            $data['mfo'] = $company->mfo;
            $data['director'] = $company->director;
            $data['director_pinfl'] = $company->director_pinfl;
            $data['accountant'] = $company->cccountant;

            return ['status'=>true,'data'=>$data];
        }
        return ['status'=>false,'error'=>__('main.company_not_found')];
    }

    public function getIkpu(Request $request)
    {

        if ($request->has('company_id')) {
            $filter = $request->all();

            $companyIkpu = CompanyIkpu::where(['company_id'=>$filter['company_id']])->with('ikpu')->get();

            if(!empty($companyIkpu)) {
                $ikpus[] = '<option value="">'.__("main.choice_ikpu").'</option>';

                foreach ($companyIkpu as $company) {
                    $ikpus[] = "<option value='{$company->ikpu_id}'>{$company->getTitle()}</option>";
                }
                return ['status' => true, 'data' => $ikpus];
            }
        }
        return ['status'=>false];


    }



}
