<?php

namespace App\Models;

use App\Http\Traits\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTarif extends Model
{
    use HasFactory,Order;

    protected $guarded = [];

    public function tarif(){
        return $this->hasOne(Tarif::class,'id','tarif_id');
    }
    public function region(){
        return $this->hasOne(Region::class,'id','region_id');
    }

}
