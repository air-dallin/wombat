<?php

namespace App\Models;

use App\Http\Interfaces\FilterInterface;
use App\Http\Traits\Filters;
use App\Http\Traits\Locale;
use App\Http\Traits\MyCompany;
use App\Http\Traits\Order;
use App\Http\Traits\Statuses;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentOrder extends Model implements FilterInterface
{
    use HasFactory, Locale, Order, MyCompany,Statuses,Filters;


    protected $guarded = [];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }
    public function company(){
        return $this->hasOne(Company::class,'id','company_id');
    }

    public function contract(){
        return $this->hasOne(Contract::class,'id','contract_id');
    }
    public function purpose(){
        return $this->hasOne(PurposeCode::class,'id','purpose_id');
    }

    public function invoice(){
        return $this->hasOne(CompanyInvoice::class,'id','company_invoice_id');
    }

    public function getFieldInn()
    {
        return 'inn_dt';
    }

    public function getFieldContragentCompanyName()
    {
        return 'name_dt';
    }
}
