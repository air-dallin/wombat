<?php

namespace App\Http\Controllers\Profile;

use App\Helpers\CryptHelper;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Kapital;
use App\Models\PaymentOrder;
use App\Services\KapitalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KapitalController extends Controller
{

    public function edit(Company $company)
    {
        $company->with('kapital');
        return view('frontend.profile.kapital.form', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Company $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $params = $request->all();
        unset($params['inn']);
        $company->with('kapital');
        $company->kapital->encode($params);

        $company->kapital->update($params);

        return redirect()->route('frontend.profile.companies.services', app()->getLocale());
    }

    public function checkKapital(Request $request){
        $error = '';
        if($request->has('inn') && $request->has('pw')) {
            if($company = Company::with('kapital')->where(['inn'=>$request->inn])->first()) {
                $password = CryptHelper::encrypt($request->pw);
                $login = CryptHelper::encrypt($request->login);
                if (empty($company->kapital)) {
                    Kapital::create(['login' => $login, 'password' => $password, 'date_from' => $request->date_from, 'company_id' => $company->id]);
                    $company->load('kapital');
                }
                $company->kapital->update(['login' => $login, 'password' => $password, 'date_from' => $request->date_from]);

                $result = KapitalService::login($company->kapital);
                if (empty($result->error)) {
                    $expire = explode(':', $result->result->sid);
                    $company->kapital->update(['sid' => CryptHelper::encrypt($result->result->sid), 'response' => json_encode($result, JSON_UNESCAPED_UNICODE), 'expire' => $expire[0],'status'=>1]);
                    return ['status' => true];
                } else {
                    $error = $result->error->message;
                }

            }else {
                $error = __('main.kapital_bank_service_not_set');
            }

        }
        return ['status'=>false,'error'=>$error];
    }


    /** test actions */

   /* public function login(Request $request){

        $kapital = Kapital::with('company')->where(['id'=>5])->first();

        $date = $request->has('date') ?$request->date : date('d.m.Y');

        $data = [
              "sid"=> $kapital->sid,
              "branch"=> $kapital->company->mfo,
              "account"=> $kapital->company->bank_code,
              "date"=> $date
            ];

        //KapitalService::login($kapital);

        //$res = KapitalService::getAccounts($data);
        $res = KapitalService::getDocuments($data);
        $count = 0;
        if(!empty($res['result']['content'])) {
            //$oper_day = date('Y-m-d',strtotime( $res['result']['oper_day']));
            foreach ($res['result']['content'] as $document) {
                if(!$po = PaymentOrder::where(['general_id'=>$document['general_id']])->first()) {

                    echo '<pre>';
                    dump($document);
                    echo '</pre>';

                    $_data = $document; // json_decode($document, true);
                    $_data['company_id']= $kapital->company_id;
                    $_data['user_id'] = Auth::id();
                    $_data['status'] = $document['state']; // status=state
                    if(isset($document['o_date'])) $_data['o_date'] = date('Y-m-d',strtotime($document['o_date']));
                    if(isset($document['vdate'])) $_data['vdate'] = date('Y-m-d',strtotime($document['vdate']));
                    //  $_data['oper_day'] = $oper_day; //.date('Y-m-d', strtotime($document['oper_day'])); // status=state
                    PaymentOrder::create($_data);
                    $count++;
                }
            }
        }

        echo 'created: ' . $count;
        exit;


    }

    public function accounts(){

        $kapital = Kapital::where(['id'=>1])->first();

        dd(KapitalService::getAccounts($kapital));

    }


    public function documents(){

        $kapital = Kapital::where(['id'=>1])->first();

        dd(KapitalService::getDocuments($kapital));

    }*/



}
