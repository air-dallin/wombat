<?php

namespace App\Models;

use App\Http\Traits\Locale;
use App\Http\Traits\Order;
use App\Http\Traits\Statuses;
use App\Http\Traits\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory, Locale, Order, Status, Statuses;

    protected $guarded = [];

    public function city(){
        return $this->hasOne(City::class,'id','city_id');
    }
}
