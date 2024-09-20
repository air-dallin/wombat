<?php

namespace App\Models;

use App\Http\Traits\Locale;
use App\Http\Traits\Order;
use App\Http\Traits\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocItems extends Model
{
    use HasFactory, Locale, Order,Status;

    protected $guarded = [];

    public function doc(){
        return $this->hasOne(Doc::class,'id','product_id');
    }
    public function company(){
        return $this->hasOne(Company::class,'id','company_id');
    }
    public function package(){
        return $this->hasOne(Package::class,'id','unit_id');
    }
    public function nds(){
        return $this->hasOne(Nds::class,'id','nds_id');
    }

    public function ikpu(){
        return $this->hasOne(Ikpu::class,'id','ikpu_id');
    }
    public function getProductOrigin(){
        return Product::getOriginLabel($this->product_origin);
    }
}