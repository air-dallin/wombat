<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\SmsHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {

        if($request->isMethod('post')){
            if($user = User::where(['phone'=>$request->phone])->whereIn('status',[User::STATUS_ACTIVE,User::STATUS_PHONE_CONFIRM])->first()) {

               /* $phone = correct_phone($request->phone);
                $code = SmsHelper::generateCode();
                $msg = $_SERVER['SERVER_NAME'] . ' code: ' . $code;
                $user->remember_token =  $code;
                $user->save();
                SmsHelper::sendSms($phone,$msg); */

                //if(Hash::check($request->password, $user->password)) {
                if($request->sms_code == $user->remember_token) {
                    Auth::login($user);
                    /*
                    if(in_array($user->role,[User::ROLE_ADMIN,User::ROLE_MODERATOR])){
                        return redirect()->route('admin.index', app()->getLocale());
                    }else {
                        return redirect()->route('frontend.profile.info', app()->getLocale());
                    } */
                    return redirect()->route('frontend.profile.companies.index', app()->getLocale());
                }else {
                    $error = 'login_password_incorrect';
                }
            }else{
                $error = 'user_not_found';
            }

            Session::flash('error',$error);

        }elseif(!Auth::guest()){
            return view('frontend.profile.companies.index');
        }

        return view('frontend.users.login');


    }



    public function logout()
    {

        $request = request();

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }
        $lang = $request->lang ?: config('app.fallback_locale');

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/'.$lang);
    }

}
