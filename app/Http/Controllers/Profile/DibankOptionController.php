<?php

namespace App\Http\Controllers\Profile;

use App\Helpers\CryptHelper;
use App\Helpers\Elog;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\DibankOption;
use App\Services\DibankService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DibankOptionController extends Controller
{

    public function edit(DibankOption $dibankOption)
    {
        $step1Class = false; // !in_array($dibankOption->account_status,[\App\Services\DibankService::STATUS_WAIT,\App\Services\DibankService::STATUS_CONFIRM]);
        $step2Class = false; //$step1Class && !$dibankOption->account_status==\App\Services\DibankService::STATUS_BIND;
        $step3Class = false; //$step1Class && $step2Class && !$dibankOption->account_status==\App\Services\DibankService::STATUS_SIGN;

        // dd($step1Class,$step2Class,$step3Class);

        return view('frontend.profile.dibank_options.form', compact('dibankOption','step1Class','step2Class','step3Class'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param DibankOption $dibankOption
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DibankOption $dibankOption)
    {
        $params = $request->all();

        $params['dibank']['phone'] = correct_phone($params['dibank']['phone']);

        $dibankOption->encode($params['dibank']);
        $dibankOption->update($params['dibank']);

        // return redirect()->route('frontend.profile.companies.service', app()->getLocale());
        return redirect()->route('frontend.profile.companies.services', ['company'=>$dibankOption->company_id,app()->getLocale()]);
    }

    public function checkDibank(Request $request){
        if($request->has('company_id') && $request->has('login') && $request->has('password')) {

            $params = $request->all();
            $params['phone'] = correct_phone($params['phone']);
            $result = false;
            if(!$dibank = DibankOption::where(['company_id'=>$request->company_id])->first()){
                Elog::save('dibank not found, creating...');
                if($dibank = DibankOption::create($params)) {
                    $dibank->encode($params);
                    $dibank->save();
                    $dibank->load('company');
                    $result = DibankService::createProfile($dibank);
                }
            }

            // возможно потребуется повторная привязка ?

            if(!$result || !isset($result['user-key'])) $result = DibankService::login($dibank);
            if($result['success']) {
                Elog::save('checkDibank');
                Elog::save($result);
                $dibank->update(['login' => CryptHelper::encrypt($request->login), 'password' => CryptHelper::encrypt($request->password), 'user_key' => $result['user-key'], 'expire' => date('Y-m-d H:i:s',time() + 21600),'account_status' => DibankService::STATUS_BIND]);
                if(!empty($result)) return ['status'=>true/*,'data'=>$result*/];
            }


        }
        return ['status'=>false,'error'=>'data not set'];
    }
    public function signDibank(Request $request)
    {
        Elog::save('signDibank');
        if ($request->has('company_id') && $request->has('serial')/* && $request->has('signature')*/) {
            $filter = $request->all();

            $dibank = DibankOption::where(['company_id'=>$filter['company_id']])->first();

            $result = DibankService::signAccount($filter,$dibank);
            Elog::save($result);
            if($result['success']) {
                $dibank->update(['response_sign' => json_encode($result,JSON_UNESCAPED_UNICODE),'account_status' => DibankService::STATUS_SIGN]);
                if(!empty($result)) return ['status'=>true/*,'data'=>$result*/];
            }

        }
    }

    public function confirmDibank(Request $request)
    {
        Elog::save('confirmDibank');
        if ($request->has('company_id') && $request->has('sms') ) {
            $filter = $request->all();

            $dibank = DibankOption::where(['company_id'=>$filter['company_id']])->first();

            $result = DibankService::confirmAccount($filter,$dibank);
            Elog::save($result);
            if($result['success']) {
                $dibank->update(['account_status' => DibankService::STATUS_CONFIRM]);
                if(!empty($result)) return ['status'=>true/*,'data'=>$result*/];
            }

        }
        return ['status'=>false,'error'=>'data not set'];

    }



}
