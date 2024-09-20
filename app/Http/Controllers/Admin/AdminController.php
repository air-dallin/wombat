<?php
namespace App\Http\Controllers\Admin;

use App\Models\Article;
use App\Models\Claim;
use App\Models\Ticket;
use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{

	public function test(){

		dd('locale'.app()->getLocale());
		dd('test');

	}


	public function login(Request $request){
        if($request->isMethod('post')){

            if($user = User::where(['phone'=>$request->phone])->whereIn('status',[User::STATUS_ACTIVE,User::STATUS_PHONE_CONFIRM])->first()) {
                if(Hash::check($request->password, $user->password)) {
                    $user->update(['login_at'=>date('Y-m-d H:i:s')]);
                    UserLogin::create(['user_id'=>$user->id,'login_at'=>date('Y-m-d H:i:s')]);
                    Auth::login($user);
                    return redirect()->route('admin.index', app()->getLocale());
                }else {
                    $error = 'login_password_incorrect';
                }
            }else{
                $error = 'user_not_found';
            }

            Session::flash('error',$error);
        }


        return view('auth.login');


    }

    public function logout(){
        if($userLogin = UserLogin::where(['user_id'=>Auth::id()])->orderBy('id','DESC')->first()) {
            $userLogin->update(['logout_at'=>date('Y-m-d H:i:s')]);
        }
        Auth::logout();
        return redirect((app()->getLocale() ?: config('app.fallback_locale')) . '/');
    }

	    // Dashboard
    public function index()
    {

        error_reporting(E_ALL);

        $page_title = 'Dashboard';
        $page_description = 'Some description for the page';
        $logo = "images/logo.png";
        $logoText = "images/logo-text.png";
        $action = __FUNCTION__;

        $users = User::with(['info.region','image'])->where(['status'=>User::STATUS_CREATE])->orderBy('id','DESC')->limit(10)->get();
        $publications = [] ;// Article::where(['type'=>Article::TYPE_PUBLICATION /*,'status'=>Article::STATUS_WAIT*/])->orderBy('id','DESC')->limit(10)->get();
        $tickets = []; // Ticket::with('userFrom.info.region','userTo.info')->where(['status'=>Ticket::STATUS_ACTIVE])->orderBy('id','DESC')->limit(10)->get();
        $claims = []; //Claim::with('userFrom.info.region')->where(['status'=>Claim::STATUS_ACTIVE])->orderBy('id','DESC')->limit(10)->get();

        //dd($users);

        return view('dashboard.index', compact('page_title', 'page_description','action','logo','logoText','users','publications','tickets','claims'));

    }


}
