<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Elog;
use App\Helpers\SmsHelper;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Company;
use App\Models\DibankOption;
use App\Models\Image;
use App\Models\Kapital;
use App\Models\Region;
use App\Models\Token;
use App\Models\User;
use App\Models\UserInfo;
use App\Services\FacturaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function index($type)
    {
        $regions = Region::get();
        $cities = City::where(['region_id'=>1])->get();


        return view('frontend.users.step', compact('type','regions','cities'));
    }

    public function register()
    {
        $regions = Region::get();
        $cities = new City();

        return view('frontend.users.register',compact('regions','cities'));
    }


    public function complete(Request $request)
    {

       /* if($request->isMethod('post')) {

            $data = $request->all();

           // dd($data);

            $data['role'] = User::getRoleByType($request->type);
            unset($data['type']);
            if (!isset($data['email'])) $data['email'] = '';
            $data['password'] = Hash::make($data['password']);
            $code = SmsHelper::generateCode();
            $data['remember_token'] = $code;
            $data['phone'] = correct_phone($data['phone']);
            $user = User::create($data);
            $data['user_id'] = $user->id;
            $data['rating'] = 0;
            $data['rating_count'] = 0;
            if(!isset($data['address'])) $data['address'] =  '';
            if(!isset($data['position'])) $data['position'] = '';

            UserInfo::create($data);

            if( in_array($data['role'],[User::ROLE_COMPANY,User::ROLE_ADVISER_USER]) ){
                CompanyInfo::create($data);
                //unset($data['certificate']);
                if (isset($data['certificate'])) {
                    $item = $request->file('certificate');
                    Image::add($item,'certificate',$user->id);
                }
            }


            $msg = $_SERVER['SERVER_NAME'] . ' code: ' . $code;

            //SmsHelper::sendSms($user->phone, $msg);

            Session::put('user_id',$user->id);

            return redirect()->refresh();
        }

        if(!$user = User::where(['id'=>Session::get('user_id')])->first()){
            return redirect()->route('frontend.register.index',app()->getLocale())->with('user_not_found');
        }

        return view('frontend.users.sms', compact('user'));
*/
    }

    public function checkSmsCode(Request $request){

        $phone = correct_phone($request->phone);

        if($user = User::where(['phone'=>$phone])->first()){

            if($user->remember_token==$request->code){
                $user->phone_verified_at = date('Y-m-d H:i:s',time());
                $user->status = User::STATUS_CREATE; // STATUS_PHONE_CONFIRM;
                $user->remember_token =  Str::random(32);
                $user->role = User::ROLE_CLIENT;
                $user->save();
                Auth::login($user);
                return ['status'=>true];
            }else{
                return ['status'=>false,'error'=>__('main.code_incorrect')];
            }

        }

        return ['status'=>false,'error'=>__('main.user_not_found')];
    }

    public function sendSmsCode(Request $request){

        $data = $request->all();
        $phone = correct_phone($request->phone);
        $code = SmsHelper::generateCode();
        $msg = $_SERVER['SERVER_NAME'] . ' code: ' . $code;

        $isRegistration = $request->has('register');

        if( $user = User::where(['phone' => $phone])->first()){

            if($isRegistration){
                return ['status'=>false,'error' => __('main.user_exist')];
            }

            $user->remember_token =  $code;
            $user->save();
            SmsHelper::sendSms($phone, $msg);

            // отправка смс кода только пока нет смс сервиса
            return ['status'=>true,'sms'=>$code];

        }else{
            //dd($request->register);
            if($isRegistration){

                if($token = Token::where(['service'=>'factura'])->first()){
                    if(time()>$token->expire){
                        $result = FacturaService::getToken();
                    }else {
                        $result = ['access_token' => $token->token, 'expires_in' => $token->expire];
                    }
                }else{
                    $result = FacturaService::getToken();
                    if(!isset($result['access_token'])){
                        return ['status'=>false,'error'=>__('main.token_incorrect')];
                    }

                }

                $companyInfo = FacturaService::getCompanyInfo($request->inn,$result['access_token']);

                ELog::save('companyInfo');
                ELog::save($companyInfo);



                $data['role'] = User::ROLE_CLIENT; // getRoleByType($request->type);
                if (!isset($data['email'])) $data['email'] = '';
                $data['remember_token'] = $code;
                $data['phone'] = $phone;

                $user = User::create($data);
                $data['user_id'] = $user->id;

                UserInfo::create($data);

                    unset($data['phone'],$data['register'],$data['role'],$data['email'],$data['remember_token']);

                $data['region_id'] = 11;
                $data['city_id'] = 111;
                $data['inn'] = $request->inn;

                    if(isset($companyInfo['CompanyInn'])){
                       // return ['status'=>false,'error'=>__('main.inn_user_not_found') . ' ' . json_encode($companyInfo) . ' ' . json_encode($result)];
                        $bankInfo = FacturaService::getPrimaryAccount($companyInfo);

                        $data['factura_response'] = json_encode($companyInfo,JSON_UNESCAPED_UNICODE);
                        $data['name'] = $companyInfo['CompanyName'];
                        $data['address'] = $companyInfo['CompanyAddress'];
                        $data['oked'] = $companyInfo['Oked'];
                        $data['nds_code'] = $companyInfo['VatCode'];
                        $data['bank_code'] = $bankInfo['code'];
                        $data['bank_name'] = $bankInfo['name'];
                        $data['mfo'] = $bankInfo['mfo'];

                    }


                    $data['status'] = 1;

                $company = Company::create($data);

                if(!empty($company)) {
                    DibankOption::create(['company_id' => $company->id]);
                    Kapital::create(['company_id'=>$company->id,'date_from'=>date('Y-m-d')]);
                }
                //Session::put('sms-code',$code);
                SmsHelper::sendSms($phone, $msg);

                return ['status'=>true,'sms'=>$code];


            }
        }

        return ['status'=>false,'error'=>__('main.user_not_found')];
    }


    public function checkPhone(Request $request){
        if($request->has('phone')){
            $phone = correct_phone($request->phone);
            if(!$user = User::where(['phone'=>$phone])->first()){
                return ['status'=>true];
            }
            return ['status'=>false,'error'=>__('main.phone_exist')];
        }
        return ['status'=>false,'error'=>__('main.phone_not_set')];
    }

}
