<?php

namespace App\Models;

use App\Http\Interfaces\FilterInterface;
use App\Http\Traits\Filters;
use App\Http\Traits\Locale;
use App\Http\Traits\MyCompany;
use App\Http\Traits\Order;
use App\Http\Traits\Statuses;
use App\Services\DidoxService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseOrder extends Model implements FilterInterface
{
    use HasFactory, Locale, Order,MyCompany,Statuses, Filters;


    protected $guarded = [];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }
    public function company(){
        return $this->hasOne(Company::class,'id','company_id');
    }
    public function movement(){
        return $this->hasOne(Movements::class,'id','movement_id');
    }

    public function casse(){
        return $this->hasOne(CompanyCasse::class,'id','casse_id');
    }
    public function contract(){
        return $this->hasOne(Contract::class,'id','contract_id');
    }
    public function contractOutgoing(){
        return $this->hasOne(Contract::class,'id','contract_id')->where(['owner'=>DidoxService::OWNER_TYPE_OUTGOING]);
    }

    public function getFieldInn()
    {
        return 'contragent';
    }

    public function getFieldContragentCompanyName()
    {
        return 'contragent_company';
    }
}
