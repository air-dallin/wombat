<?php

namespace App\Models;

use App\Http\Traits\Images;
use App\Http\Traits\Locale;
use App\Http\Traits\Order;
use App\Http\Traits\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PaymentSystem extends Model
{
    use HasFactory, Status,Locale, Order /*, Images*/;

    protected $guarded = [];

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 9;


    public function image(){
        return $this->hasOne(Image::class,'object_id')->where('type','payment_system');;
    }


}
