<?php

namespace App\Http\Traits;

use App\Helpers\ObjectHelper;
use App\Models\Company;

trait ByCompany
{
    public function scopeByCompany($query,$companies){
        if($current_company_id = Company::getCurrentCompanyId()) {
            $query->where(['company_id'=>$current_company_id]);
        }else{
            if(!empty($companies) && count($companies)){
                $query->whereIn('company_id',ObjectHelper::getIds($companies));
            }
        }
        return $query;
    }

}
