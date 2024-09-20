<?php

namespace App\Models;

use App\Http\Traits\ByCompany;
use App\Http\Traits\Locale;
use App\Http\Traits\MyCompany;
use App\Http\Traits\Status;
use App\Http\Traits\Order;
use App\Http\Traits\Statuses;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyWarehouse extends Model
{
    use HasFactory, Locale,Order,Status,ByCompany,Statuses,MyCompany;

    protected $guarded = [];

    public function company(){
        return $this->hasOne(Company::class,'id','company_id');
    }

}
