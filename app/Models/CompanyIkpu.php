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
use Illuminate\Support\Str;

class CompanyIkpu extends Model
{
    use HasFactory, Locale,Order,Statuses,Status,ByCompany,MyCompany;

    protected $guarded = [];

    public function ikpu(){
        return $this->hasOne(Ikpu::class,'id','ikpu_id');
    }
    public function company(){
        return $this->hasOne(Company::class,'id','company_id');
    }

    public function getTitle(){
        return $this->ikpu->code . ' - ' . Str::limit($this->ikpu->getTitle(),100);
    }


}
