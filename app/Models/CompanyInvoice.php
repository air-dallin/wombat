<?php

namespace App\Models;

use App\Http\Traits\ByCompany;
use App\Http\Traits\Locale;
use App\Http\Traits\MyCompany;
use App\Http\Traits\Order;
use App\Http\Traits\Status;
use App\Http\Traits\Statuses;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyInvoice extends Model
{
    use HasFactory, Locale,Order, Status,Statuses,ByCompany,MyCompany;


    protected $guarded = [];

    public function company(){
        return $this->hasOne(Company::class,'id','company_id');
    }

    public static function checkInvoice(&$payment_order){
        if(!empty($payment_order->acc_dt) && ! CompanyInvoice::where(['company_id'=>$payment_order->company_id,'bank_invoice'=>$payment_order->acc_dt])->first()){
           CompanyInvoice::create(['company_id'=>$payment_order->company_id,'bank_invoice'=>$payment_order->acc_dt,'status'=>1]);
        }
    }
}
