<?php

namespace App\Models;

use App\Helpers\ObjectHelper;
use App\Http\Traits\ByCompany;
use App\Http\Traits\Locale;
use App\Http\Traits\MyCompany;
use App\Http\Traits\Order;
use App\Http\Traits\Status;
use App\Http\Traits\Statuses;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Company extends Model
{
    use HasFactory, Locale, Order,Status,MyCompany,Statuses;
    protected $guarded = [];

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 9;


    public function user() {
        return $this->hasOne(User::class,'id','user_id');
    }
    public function region() {
        return $this->hasOne(Region::class,'id','region_id');
    }
    public function city() {
        return $this->hasOne(City::class,'id','city_id');
    }
    public function nds() {
        return $this->hasOne(Nds::class,'id','nds_id');
    }

    public function casse(){
        return $this->hasMany(CompanyCasse::class,'id','company_id');
    }
    public function invoices(){
        return $this->hasMany(CompanyInvoice::class,'company_id','id')->where(['status'=>1]);
    }

    public function warehouse(){
        return $this->hasMany(CompanyWarehouse::class,'company_id','id');
    }

    public function ikpu(){
        return $this->belongsToMany(Ikpu::class,'company_ikpus','company_id','ikpu_id');
    }

    public function dibank() {
        return $this->hasOne(DibankOption::class,'company_id','id');
    }
    public function kapital(){
        return $this->hasOne(Kapital::class,'company_id','id');
    }

    public function plans(){
        return $this->belongsToMany(Accounting::class,'company_accountings','company_id','accounting_id');
    }


    public function primaryInvoice() {
        return $this->hasOne(CompanyInvoice::class,'company_id','id')->where(['is_main'=>true]);
    }

    public static function getCompany($company_id = null){

        if(!empty($company_id)){
            $company = Company::where(['id'=>$company_id])->get();
            return $company;
        }

        $current_company_id = session()->has('current_company_id')  ? session()->get('current_company_id') :0;
        // здесь список текущей компании, либо всех если она не указана
        if($current_company_id){
            $company = Company::where(['user_id'=>Auth::id(),'id'=>$current_company_id])->where(['status'=>Module::STATUS_ACTIVE])->get();
        }else{
            $company = Company::where(['user_id'=>Auth::id()])->where(['status'=>Module::STATUS_ACTIVE])->get();
        }

        return $company;
    }

    public static function getMyCompanyIds(){
        if(session()->has('current_company_id')){
            $ids[] = session()->get('current_company_id');
        }else{
            $companies = Company::where(['user_id'=>Auth::id()])->select('id')->get();
            $ids = ObjectHelper::getIds($companies);
        }
        return $ids;
    }
    public static function getMyCompaniesIds(){
        $companies = Company::where(['user_id'=>Auth::id()])->select('id')->get();
        $ids = ObjectHelper::getIds($companies);
        return $ids;
    }

    public static function getMyCompaniesInn(){
        if(session()->has('current_company_inn')){
            $inn[] = session()->get('current_company_inn');
        }else{
            $companies = Company::where(['user_id'=>Auth::id()])->select('inn')->get();
            $inn = ObjectHelper::getIds($companies,'inn');
        }
        return $inn;
    }

    public static function getCurrentCompany($company_id=null)
    {
        if(session()->has('selected_company')) return session()->get('selected_company');

        // здесь список текущей компании, либо всех если она не указана
        if(!empty($company_id)){
            $company = Company::where(['id'=>$company_id])->where(['status'=>Module::STATUS_ACTIVE])->first();
        }elseif(session()->has('current_company_id')){
            $company = Company::where(['user_id'=>Auth::id(),'id'=>session()->get('current_company_id')])->where(['status'=>Module::STATUS_ACTIVE])->first();
        }else{
            $company = null;
        }
        return $company;
    }

    public static function getCurrentCompanyId(){
        return session()->has('current_company') ? session()->get('current_company_id') : 0;
    }

    public static function getUserCompaniesId($user_id){
        return Company::where(['user_id'=>$user_id])->pluck('id')->toArray();
    }

    public static function checkCompany($company_id){
        $user_id = Auth::id();
        if(!$userCompanies = self::getUserCompaniesId($user_id)) {
            return false;
        }
        $curren_company_id = self::getCurrentCompanyId();
        if($curren_company_id==0 && !in_array($company_id, $userCompanies)) {
            return false;
        }elseif($curren_company_id != $company_id && !in_array($company_id, $userCompanies)){
            return false;
        }
        return true;
    }
    /** провекра срока токена */
    public function checkTokenExpire(){
        if ($this->token_expire<time()) {
            return true;
        }
        return false;
    }

    /** основой расчетный счет */
    public function getPrimaryAccount(){

    }

    public static function getByInn($inn,$field=null){
        if($company = Company::where(['inn'=>$inn])->first()){
            return $company->$field;
        }
        return '';
    }

    public static function getDtParams(&$params){
        if($company = Company::where(['id'=>$params['company_id']])->first()){
            $params['mfo_dt'] =  $company->mfo;
            $params['acc_dt'] =  $company->bank_code;
            $params['name_dt'] = $company->name;
            $params['inn_dt'] = $company->inn;
        }
    }

}
