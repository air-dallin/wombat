<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CompanyPlan extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;

    public static function updatePlan($doctype,$params){

        if($plan = CompanyPlan::where(['company_id'=>$params['company_id'],'document_type'=>$doctype])->first()){
               $plan->update(['plan_id'=>$params['plan_id']]);
        }else{
            CompanyPlan::create(['company_id'=>$params['company_id'],'document_type'=>$doctype,'plan_id'=>$params['plan_id']]);
        }

    }

}
