<?php

namespace App\Models;

use App\Http\Traits\Locale;
use App\Http\Traits\MyCompany;
use App\Http\Traits\Order;
use App\Http\Traits\Status;
use App\Http\Traits\Statuses;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuarantItems extends Model
{
    use HasFactory, Locale, Order,Status;

    protected $guarded = [];

    public function guarant(){
        return $this->hasOne(Guarant::class,'id','guarant_id');
    }
    public function company(){
        return $this->hasOne(Company::class,'id','company_id');
    }
    public function unit(){
        return $this->hasOne(Unit::class,'id','unit_id');
    }
    public function ikpu(){
        return $this->hasOne(Ikpu::class,'id','ikpu_id');
    }



}
