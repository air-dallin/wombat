<?php

namespace App\Http\Traits;

use App\Helpers\ObjectHelper;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;

trait MyCompany
{
    public function scopeMyCompany($query,$company=null){

        $current_company_id = session()->has('current_company_id') ? session()->get('current_company_id') :0;
        if($current_company_id>0){
            return $query->where(['company_id'=>$current_company_id]);
        }else{
            if(!empty($company)) return $query->where(['company_id'=>$company->id]);
            // получить все компании клиента
            $companies = Company::where(['user_id'=>Auth::id()])->select('id')->get();
            return $query->whereIn('company_id',ObjectHelper::getIds($companies));
        }
    }

}
