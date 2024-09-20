<?php

namespace App\Models;

use App\Http\Traits\ByCompany;
use App\Http\Traits\Locale;
use App\Http\Traits\Status;
use App\Http\Traits\Order;
use App\Http\Traits\Statuses;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyCasse extends Model
{
    use HasFactory, Locale,Order,Status,ByCompany,Statuses;

    protected $guarded = [];

    public function company(){
        return $this->hasOne(Company::class,'id','company_id');
    }
    /*
    public function casse(){
        return $this->hasOne(CompanyCasse::class,'id','casse_id');
    } */
    public function movement(){
        return $this->hasOne(Movements::class,'id','movement_id');
    }
}
