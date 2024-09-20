<?php

namespace App\Models;

use App\Http\Traits\Locale;
use App\Http\Traits\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory, Locale,Order;


    protected $guarded = [];

    public function region(){
        return $this->hasOne(Region::class,'id','region_id');
    }
}
